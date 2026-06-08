<?php

namespace App\Http\Controllers;

use App\Models\NgendevVideoCategory;
use App\Models\AiVideoNgdSetting;
use Illuminate\Http\Request;

class NgendevVideoCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // ── Helpers ────────────────────────────────────────────────────────────
    private function catDir(string $categoryName): string
    {
        return public_path('upload/ngendev/videos/' . $categoryName . '/category_thumbnail_image');
    }

    private function deleteImage(string $categoryName, string $filename): void
    {
        $path = $this->catDir($categoryName) . '/' . $filename;
        if (file_exists($path)) unlink($path);
    }

    // ── Index ───────────────────────────────────────────────────────────────
    public function index()
    {
        $perPage       = request('per_page', 10);
        $search        = request('search');
        $query         = NgendevVideoCategory::orderBy('id', 'desc');
        if ($search) $query->where('category_name', 'like', "%{$search}%");
        $categories    = $query->paginate($perPage)->appends(request()->query());
        $totalCount    = NgendevVideoCategory::count();
        $coupleCount   = NgendevVideoCategory::where('type', 'Couple')->count();
        $setting       = AiVideoNgdSetting::first();
        $coupleActive  = $setting ? (bool) $setting->couple_active : true;
        $allCategories = NgendevVideoCategory::orderBy('id', 'desc')->get();
        return view('ngendev.categories.index', compact('categories', 'totalCount', 'coupleCount', 'coupleActive', 'allCategories'));
    }

    public function create()
    {
        return view('ngendev.categories.create');
    }

    // ── Store ───────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'category_name'    => 'required|string|max:255',
            'type'             => 'required|in:Solo,Couple',
            'category_image'   => 'nullable|array',
            'category_image.*' => 'image|max:5120',
        ]);

        $imageNames = [];
        if ($request->hasFile('category_image')) {
            $dir = $this->catDir($request->category_name);
            if (!file_exists($dir)) mkdir($dir, 0755, true);
            foreach ($request->file('category_image') as $image) {
                $filename = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
                $image->move($dir, $filename);
                $imageNames[] = $filename;
            }
        }

        NgendevVideoCategory::create([
            'category_name'  => $request->category_name,
            'category_image' => $imageNames,
            'type'           => $request->type,
            'status'         => 1,
        ]);

        return redirect()->route('ngendev.categories.index')
                         ->with('success', 'Category added successfully.');
    }

    public function edit(NgendevVideoCategory $category)
    {
        return view('ngendev.categories.edit', compact('category'));
    }

    // ── Update ───────────────────────────────────────────────────────────────
    public function update(Request $request, NgendevVideoCategory $category)
    {
        $request->validate([
            'category_name'    => 'required|string|max:255',
            'type'             => 'required|in:Solo,Couple',
            'category_image.*' => 'nullable|image|max:5120',
        ]);

        $oldName    = $category->category_name;
        $newName    = $request->category_name;
        $imageNames = $category->category_image ?? [];

        if ($request->hasFile('category_image')) {
            // Delete old images from old path
            foreach ($imageNames as $old) {
                $this->deleteImage($oldName, $old);
            }
            $imageNames = [];
            $dir = $this->catDir($newName);
            if (!file_exists($dir)) mkdir($dir, 0755, true);
            foreach ($request->file('category_image') as $image) {
                $filename = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
                $image->move($dir, $filename);
                $imageNames[] = $filename;
            }
        } elseif ($oldName !== $newName && !empty($imageNames)) {
            // Name changed, no new images — move existing files to new folder
            $oldDir = $this->catDir($oldName);
            $newDir = $this->catDir($newName);
            if (!file_exists($newDir)) mkdir($newDir, 0755, true);
            foreach ($imageNames as $filename) {
                $oldPath = $oldDir . '/' . $filename;
                $newPath = $newDir . '/' . $filename;
                if (file_exists($oldPath)) rename($oldPath, $newPath);
            }
            // Remove old dir if empty
            if (file_exists($oldDir) && count(array_diff(scandir($oldDir), ['.', '..'])) === 0) {
                rmdir($oldDir);
            }
        }

        $category->update([
            'category_name'  => $newName,
            'category_image' => $imageNames,
            'type'           => $request->type,
        ]);

        return redirect()->route('ngendev.categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    // ── Destroy ──────────────────────────────────────────────────────────────
    public function destroy(NgendevVideoCategory $category)
    {
        foreach (($category->category_image ?? []) as $img) {
            $this->deleteImage($category->category_name, $img);
        }
        // Remove folder if empty
        $dir = $this->catDir($category->category_name);
        if (file_exists($dir) && count(array_diff(scandir($dir), ['.', '..'])) === 0) {
            rmdir($dir);
        }
        $category->delete();
        return redirect()->route('ngendev.categories.index')
                         ->with('success', 'Category deleted.');
    }

    // ── AJAX helpers ─────────────────────────────────────────────────────────
    public function updateType(Request $request)
    {
        $category = NgendevVideoCategory::findOrFail($request->id);
        $category->update(['type' => $request->type]);
        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request)
    {
        $category = NgendevVideoCategory::findOrFail($request->id);
        $category->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function saveOrder(Request $request)
    {
        $order = $request->input('order', []);
        foreach ($order as $position => $id) {
            NgendevVideoCategory::where('id', $id)->update(['sort_order' => $position + 1]);
        }
        return response()->json(['success' => true]);
    }

    public function toggleCoupleActive()
    {
        $setting = AiVideoNgdSetting::firstOrCreate([]);
        $setting->couple_active = $setting->couple_active ? 0 : 1;
        $setting->save();
        $state = $setting->couple_active ? 'ON' : 'OFF';
        return redirect()->route('ngendev.categories.index')
                         ->with('success', "Couple categories turned {$state}.");
    }

    public function reindex()
    {
        $cats = NgendevVideoCategory::orderBy('id')->get();
        foreach ($cats as $i => $cat) {
            $cat->update(['sort_order' => $i]);
        }
        return redirect()->route('ngendev.categories.index')
                         ->with('success', 'Categories reindexed.');
    }
}
