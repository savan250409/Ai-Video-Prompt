<?php

namespace App\Http\Controllers;

use App\Models\NgendevVideo;
use App\Models\NgendevVideoCategory;
use Illuminate\Http\Request;

class NgendevVideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $categories = NgendevVideoCategory::orderBy('category_name')->get();

        $query = NgendevVideo::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('ai_prompt', 'like', "%{$q}%")
                   ->orWhere('ai_model', 'like', "%{$q}%")
                   ->orWhereHas('category', fn($c) => $c->where('category_name', 'like', "%{$q}%"));
            });
        }

        $perPage    = (int) $request->get('per_page', 10);
        $videos     = $query->orderBy('category_id')->orderBy('id')->paginate($perPage)->withQueryString();
        $totalCount = NgendevVideo::count();

        return view('ngendev.videos.index', compact('categories', 'videos', 'totalCount'));
    }

    public function create()
    {
        $categories = NgendevVideoCategory::orderBy('category_name')->get();
        return view('ngendev.videos.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'     => 'required|exists:ngendev_video_categories,id',
            'ai_prompt'       => 'required|string|max:3990',
            'no_of_video'     => 'required|mimes:webp|max:5120',
            'video_thumbnail' => 'required|mimes:webp|max:5120',
            'video_path'      => 'required|mimes:mp4,mov,avi,webm|max:102400',
        ]);

        $category  = NgendevVideoCategory::findOrFail($request->category_id);
        $catFolder = $category->category_name;

        // Upload no_of_video image
        $noOfVideoName = null;
        if ($request->hasFile('no_of_video')) {
            $file          = $request->file('no_of_video');
            $noOfVideoName = time() . '_' . $file->getClientOriginalName();
            $destDir       = public_path('upload/ngendev/videos/' . $catFolder . '/no_of_video_image');
            if (!is_dir($destDir)) mkdir($destDir, 0755, true);
            $file->move($destDir, $noOfVideoName);
        }

        // Upload thumbnail
        $thumbnailName = null;
        if ($request->hasFile('video_thumbnail')) {
            $file          = $request->file('video_thumbnail');
            $thumbnailName = time() . '_' . $file->getClientOriginalName();
            $destDir       = public_path('upload/ngendev/videos/' . $catFolder . '/video_thumbnail');
            if (!is_dir($destDir)) mkdir($destDir, 0755, true);
            $file->move($destDir, $thumbnailName);
        }

        // Upload video
        $videoName = null;
        if ($request->hasFile('video_path')) {
            $file      = $request->file('video_path');
            $videoName = time() . '_' . $file->getClientOriginalName();
            $destDir   = public_path('upload/ngendev/videos/' . $catFolder . '/category_video');
            if (!is_dir($destDir)) mkdir($destDir, 0755, true);
            $file->move($destDir, $videoName);
        }

        NgendevVideo::create([
            'category_id'     => $request->category_id,
            'ai_prompt'       => $request->ai_prompt,
            'no_of_video'     => $noOfVideoName,
            'name_change'     => $request->has('name_change') ? 1 : 0,
            'video_thumbnail' => $thumbnailName,
            'video_path'      => $videoName,
        ]);

        return redirect()->route('ngendev.videos.index')
                         ->with('success', 'Video added successfully.');
    }

    public function edit(NgendevVideo $video)
    {
        $categories = NgendevVideoCategory::orderBy('category_name')->get();
        return view('ngendev.videos.form', compact('video', 'categories'));
    }

    public function update(Request $request, NgendevVideo $video)
    {
        $request->validate([
            'category_id'     => 'required|exists:ngendev_video_categories,id',
            'ai_prompt'       => 'required|string|max:3990',
            'no_of_video'     => 'nullable|mimes:webp|max:5120',
            'video_thumbnail' => 'nullable|mimes:webp|max:5120',
            'video_path'      => 'nullable|mimes:mp4,mov,avi,webm|max:102400',
        ]);

        $category        = NgendevVideoCategory::findOrFail($request->category_id);
        $catFolder       = $category->category_name;
        $oldCategory     = $video->category;
        $categoryChanged = $oldCategory && $oldCategory->id != $request->category_id;

        // ── no_of_video image ──────────────────────────────────────────────
        $noOfVideoName = $video->no_of_video;
        if ($request->hasFile('no_of_video')) {
            if ($video->no_of_video && $oldCategory) {
                $old = public_path('upload/ngendev/videos/' . $oldCategory->category_name . '/no_of_video_image/' . $video->no_of_video);
                if (file_exists($old)) unlink($old);
            }
            $file          = $request->file('no_of_video');
            $noOfVideoName = time() . '_' . $file->getClientOriginalName();
            $destDir       = public_path('upload/ngendev/videos/' . $catFolder . '/no_of_video_image');
            if (!is_dir($destDir)) mkdir($destDir, 0755, true);
            $file->move($destDir, $noOfVideoName);
        } elseif ($categoryChanged && $video->no_of_video && $oldCategory) {
            $oldPath = public_path('upload/ngendev/videos/' . $oldCategory->category_name . '/no_of_video_image/' . $video->no_of_video);
            $newDir  = public_path('upload/ngendev/videos/' . $catFolder . '/no_of_video_image');
            if (!is_dir($newDir)) mkdir($newDir, 0755, true);
            if (file_exists($oldPath)) rename($oldPath, $newDir . '/' . $video->no_of_video);
        }

        // ── thumbnail ──────────────────────────────────────────────────────
        $thumbnailName = $video->video_thumbnail;
        if ($request->hasFile('video_thumbnail')) {
            if ($video->video_thumbnail && $oldCategory) {
                $old = public_path('upload/ngendev/videos/' . $oldCategory->category_name . '/video_thumbnail/' . $video->video_thumbnail);
                if (file_exists($old)) unlink($old);
            }
            $file          = $request->file('video_thumbnail');
            $thumbnailName = time() . '_' . $file->getClientOriginalName();
            $destDir       = public_path('upload/ngendev/videos/' . $catFolder . '/video_thumbnail');
            if (!is_dir($destDir)) mkdir($destDir, 0755, true);
            $file->move($destDir, $thumbnailName);
        } elseif ($categoryChanged && $video->video_thumbnail && $oldCategory) {
            $oldPath = public_path('upload/ngendev/videos/' . $oldCategory->category_name . '/video_thumbnail/' . $video->video_thumbnail);
            $newDir  = public_path('upload/ngendev/videos/' . $catFolder . '/video_thumbnail');
            if (!is_dir($newDir)) mkdir($newDir, 0755, true);
            if (file_exists($oldPath)) rename($oldPath, $newDir . '/' . $video->video_thumbnail);
        }

        // ── video file ─────────────────────────────────────────────────────
        $videoName = $video->video_path;
        if ($request->hasFile('video_path')) {
            if ($video->video_path && $oldCategory) {
                $old = public_path('upload/ngendev/videos/' . $oldCategory->category_name . '/category_video/' . $video->video_path);
                if (file_exists($old)) unlink($old);
            }
            $file      = $request->file('video_path');
            $videoName = time() . '_' . $file->getClientOriginalName();
            $destDir   = public_path('upload/ngendev/videos/' . $catFolder . '/category_video');
            if (!is_dir($destDir)) mkdir($destDir, 0755, true);
            $file->move($destDir, $videoName);
        } elseif ($categoryChanged && $video->video_path && $oldCategory) {
            $oldPath = public_path('upload/ngendev/videos/' . $oldCategory->category_name . '/category_video/' . $video->video_path);
            $newDir  = public_path('upload/ngendev/videos/' . $catFolder . '/category_video');
            if (!is_dir($newDir)) mkdir($newDir, 0755, true);
            if (file_exists($oldPath)) rename($oldPath, $newDir . '/' . $video->video_path);
        }

        $video->update([
            'category_id'     => $request->category_id,
            'ai_prompt'       => $request->ai_prompt,
            'no_of_video'     => $noOfVideoName,
            'name_change'     => $request->has('name_change') ? 1 : 0,
            'video_thumbnail' => $thumbnailName,
            'video_path'      => $videoName,
        ]);

        return redirect()->route('ngendev.videos.index')
                         ->with('success', 'Video updated successfully.');
    }

    public function destroy(NgendevVideo $video)
    {
        if ($video->no_of_video && $video->category) {
            $np = public_path('upload/ngendev/videos/' . $video->category->category_name . '/no_of_video_image/' . $video->no_of_video);
            if (file_exists($np)) unlink($np);
        }
        if ($video->video_thumbnail && $video->category) {
            $tp = public_path('upload/ngendev/videos/' . $video->category->category_name . '/video_thumbnail/' . $video->video_thumbnail);
            if (file_exists($tp)) unlink($tp);
        }
        if ($video->video_path && $video->category) {
            $vp = public_path('upload/ngendev/videos/' . $video->category->category_name . '/category_video/' . $video->video_path);
            if (file_exists($vp)) unlink($vp);
        }
        $video->delete();

        return redirect()->route('ngendev.videos.index')
                         ->with('success', 'Video deleted.');
    }

    public function updateNameChange(Request $request)
    {
        $video = NgendevVideo::findOrFail($request->id);
        $video->update(['name_change' => $request->value]);
        return response()->json(['success' => true]);
    }

    public function reindex()
    {
        $videos = NgendevVideo::orderBy('category_id')->orderBy('id')->get();
        foreach ($videos as $i => $v) {
            $v->update(['sort_order' => $i]);
        }
        return redirect()->route('ngendev.videos.index')
                         ->with('success', 'Videos reindexed.');
    }
}
