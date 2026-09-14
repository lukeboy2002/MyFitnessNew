<?php

namespace App\Http\Controllers;

use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EditorUploadController extends Controller
{
    /**
     * Handle image upload from TipTap editor and return a public URL.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'max:5120'], // 5MB
        ]);

        $file = $validated['image'];

        // Build a safe filename with extension preserved
        $ext = $file->getClientOriginalExtension();
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeBase = Str::slug($name);
        $filename = $safeBase.'-'.Str::random(8).'.'.$ext;

        $path = $file->storeAs('editor', $filename, 'public');

        // Compress to <= 1.1 megabytes (MB)
        $absolute = storage_path('app/public/'.$path);
        ImageCompressor::compressToMaxBytes($absolute, 1024_000);

        // Ensure the storage symlink exists (developer must run: php artisan storage:link)
        $url = asset('storage/'.$path);

        return response()->json([
            'url' => $url,
            'path' => $path,
        ]);
    }

    /**
     * Remove an image from storage.
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'path' => ['required', 'string'],
        ]);

        $path = $validated['path'];

        // Only allow deleting from the 'editor' directory to prevent directory traversal
        if (! str_starts_with($path, 'editor/')) {
            return response()->json(['error' => 'Invalid path'], 400);
        }

        if (file_exists(storage_path('app/public/'.$path))) {
            unlink(storage_path('app/public/'.$path));

            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'File not found'], 404);
    }
}
