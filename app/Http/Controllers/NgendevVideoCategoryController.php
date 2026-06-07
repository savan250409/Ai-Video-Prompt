<?php

namespace App\Http\Controllers;

use App\Models\NgendevVideoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NgendevVideoCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $categories  = NgendevVideoCategory::orderBy('sort_order')->orderBy('id')->paginate(10);
        $totalCount  = NgendevVideoCategory::count();
        $coupleCount = NgendevVideoCategory::where('type', 'Couple')->count();
        return view('ngendev.categories.index', compact('categories', 'totalCount', 'coupleCount'));
    }

    public function create()
    {
        return view('ngendev.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name'  => 'required|string|max:255',
            'type'           => 'required|in:Solo,Couple',
            'category_image' => 'nullable|array',
            'category_image.*' => 'image|max:5120',
        ]);

        $imageNames = [];
        if ($request->hasFile('category_image')) {
            foreach ($request->file('category_image') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('upload/ngendev/category_thumbnail_image'), $filename);
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

    public function update(Request $request, NgendevVideoCategory $category)
    {
        $request->validate([
            'category_name'    => 'required|string|max:255',
            'type'             => 'required|in:Solo,Couple',
            'category_image.*' => 'nullable|image|max:5120',
        ]);

        $imageNames = $category->category_image ?? [];
        if ($request->hasFile('category_image')) {
            // Delete old images
            foreach ($imageNames as $old) {
                $path = public_path('upload/ngendev/category_thumbnail_image/' . $old);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $imageNames = [];
            foreach ($request->file('category_image') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('upload/ngendev/category_thumbnail_image'), $filename);
                $imageNames[] = $filename;
            }
        }

        $category->update([
            'category_name'  => $request->category_name,
            'category_image' => $imageNames,
            'type'           => $request->type,
        ]);

        return redirect()->route('ngendev.categories.index')
                         ->with('success', 'Category updated successfully.');
    }

    public function destroy(NgendevVideoCategory $category)
    {
        // Delete images
        foreach (($category->category_image ?? []) as $img) {
            $path = public_path('upload/ngendev/category_thumbnail_image/' . $img);
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $category->delete();
        return redirect()->route('ngendev.categories.index')
                         ->with('success', 'Category deleted.');
    }

    // AJAX: update type inline
    public function updateType(Request $request)
    {
        $category = NgendevVideoCategory::findOrFail($request->id);
        $category->update(['type' => $request->type]);
        return response()->json(['success' => true]);
    }

    // AJAX: toggle status
    public function updateStatus(Request $request)
    {
        $category = NgendevVideoCategory::findOrFail($request->id);
        $category->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    // Reindex sort_order
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
