<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $file = $request->file('file');
        $name = 'news-' . time() . '-' . Str::random(5) . '.' . $file->extension();

        $path = $file->storeAs('tmp/news', $name, 'public');

        return response()->json([
            'location' => asset('storage/' . $path)
        ]);
    }

    public function download($filename)
    {
        $filename = ltrim($filename, '/'); // jaga-jaga

        if (!Storage::disk('public')->exists($filename)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($filename);
    }
}
