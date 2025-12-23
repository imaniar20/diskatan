<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use voku\helper\AntiXSS;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::orderByDesc('created_at')->get();

        $data = array(
            'head' => "Berita",
            'title' => "Berita",
            'menu' => "Berita",
            'news' => $news
        );

        return view('admin.news.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = array(
            'head' => "Berita",
            'title' => "Berita",
            'menu' => "Tambah Berita",
        );

        return view('admin.news.add')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $antiXss = new AntiXSS();

        // 1. VALIDASI
        $validated = $request->validate([
            'title' => 'required|string|min:3',
            'thumbnail' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:published,draft',
            'published_at' => 'required|date',
            'newsContent' => 'required',
        ]);

        // 2. UPLOAD THUMBNAIL
        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');

            // nama file aman & unik
            $filename = Str::slug($request->title) . '-' . time() . '.' . $file->getClientOriginalExtension();

            $thumbnailPath = $file->storeAs(
                'news/thumbnail',
                $filename,
                'public'
            );
        }

        $content = $antiXss->xss_clean($request->newsContent);

        preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $src) {
                // Handle path seperti: "../storage/tmp/news/news-1766318148-WZdnr.png"
                if (strpos($src, '../storage/tmp/news/') !== false) {
                    // Ekstrak filename
                    $tmpPath = str_replace('../storage/', '', $src);
                    // hasil: tmp/news/news-1766318148-WZdnr.png

                    $newPath = str_replace('tmp/news', 'news/content', $tmpPath);

                    if (Storage::disk('public')->exists($tmpPath)) {
                        // Move file
                        Storage::disk('public')->move($tmpPath, $newPath);

                        // Update src - TANPA ../
                        $newSrc = asset('storage/' . $newPath);
                        $content = str_replace($src, $newSrc, $content);

                        // Debug
                        \Log::info('Image processed', [
                            'old' => $src,
                            'new' => $newSrc,
                            'tmp' => $tmpPath,
                            'new_path' => $newPath
                        ]);
                    }
                }
            }
        }

        $this->cleanupTempDirectory();

        $title = $antiXss->xss_clean($request->title);
        $slug = $this->generateUniqueSlugNews($title);

        // 3. SIMPAN DATA
        News::create([
            'title' => $title,
            'slug' => $slug,
            'thumbnail' => $thumbnailPath,
            'content' => $content,
            'status' => $antiXss->xss_clean($request->status),
            'published_at' => $antiXss->xss_clean($request->published_at)
        ]);

        // 4. REDIRECT
        return redirect()
            ->route('admin-berita.index')
            ->with('success', 'Berita berhasil ditambahkan');
    }

    private function cleanupTempDirectory()
    {
        $tmpDir = 'tmp/news';
        if (Storage::disk('public')->exists($tmpDir)) {
            $files = Storage::disk('public')->files($tmpDir);
            if (empty($files)) {
                Storage::disk('public')->deleteDirectory($tmpDir);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $news = News::where('slug', $slug)->first();
        $fileExists = $news->thumbnail && Storage::disk('public')->exists('/' . $news->thumbnail);

        $data = array(
            'head' => "Berita",
            'menu' => "Berita",
            'title' => "Ubah Berita",
            'news' => $news,
            'fileExists' => $fileExists
        );
        return view('admin/news/edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $antiXss = new AntiXSS();

        $validated = $request->validate([
            'title' => 'required|string|min:3',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:published,draft',
            'published_at' => 'required|date',
            'newsContent' => 'required',
        ]);

        $news = News::findOrFail($id);

        $thumbnailPath = $news->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $this->deleteFileIfExists($news->thumbnail);

            $file = $request->file('thumbnail');
            $filename = Str::slug($request->title) . '-' . time() . '.' . $file->getClientOriginalExtension();

            $thumbnailPath = $file->storeAs(
                'news/thumbnail',
                $filename,
                'public'
            );
        }

        $content = $antiXss->xss_clean($request->newsContent);

        preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $src) {

                $urlPath = parse_url($src, PHP_URL_PATH);

                if (Str::startsWith($urlPath, '/storage/tmp/news/')) {

                    $tmpPath = ltrim(str_replace('/storage/', '', $urlPath), '/');

                    $newPath = str_replace('tmp/news', 'news/content', $tmpPath);

                    if (Storage::disk('public')->exists($tmpPath)) {

                        Storage::disk('public')->move($tmpPath, $newPath);
                        
                        $content = str_replace(
                            $src,
                            asset('storage/' . $newPath),
                            $content
                        );
                    }
                }
            }
        }

        $this->cleanupTempDirectory();

        $title = $antiXss->xss_clean($request->title);
        $slug = $news->slug;

        if (strtolower($news->title) !== strtolower($title)) {
            $slug = $this->generateUniqueSlugNews($title);
        }
        $news->update([
            'title' => $title,
            'slug' => $slug,
            'thumbnail' => $thumbnailPath,
            'content' => $content,
            'status' => $antiXss->xss_clean($request->status),
            'published_at' => $antiXss->xss_clean($request->published_at),
            'updated_at' => now()
        ]);

        return redirect()
            ->route('admin-berita.index')
            ->with('success', 'Berita berhasil diperbarui');
    }

    /**
     * Delete file if it exists in storage
     */
    private function deleteFileIfExists($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return true;
        }
        return false;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    function generateUniqueSlugNews($title)
    {
        $slug = Str::limit(Str::slug($title), 150, '');
        $originalSlug = $slug;
        $count = 1;

        while (News::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
