<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use voku\helper\AntiXSS;
use App\Models\Agendas;
use App\Models\Programs;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AgendasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (session('user')->bidang_id == '1') {
            $agenda = Agendas::with('user')->orderByDesc('date')->get();
        } else {
            $agenda = Agendas::where('user_id', session('user')->id)->with('user')->orderByDesc('date')->get();
        }
        
        $data = array(
            'head' => "Agenda",
            'title' => "Agenda",
            'menu' => "Agenda",
            'agenda' => $agenda
        );

        return view('admin.agenda.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $program = Programs::orderByDesc('name')->get();

        $data = array(
            'head' => "Agenda",
            'title' => "Agenda",
            'menu' => "Tambah Agenda",
            'program' => $program
        );

        return view('admin.agenda.add')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $antiXss = new AntiXSS();

        // 1. VALIDASI
        $validated = $request->validate([
            'program' => 'required',
            'title' => 'required|string|min:3',
            'thumbnail' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'location' => 'required|string|min:3',
            'published_at' => 'required|date',
            'agendaContent' => 'required',
        ]);

        // 2. UPLOAD THUMBNAIL
        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');

            // nama file aman & unik
            $filename = Str::slug($request->title) . '-' . time() . '.' . $file->getClientOriginalExtension();

            $thumbnailPath = $file->storeAs(
                'agendas/thumbnail',
                $filename,
                'public'
            );
        }

        $content = $antiXss->xss_clean($request->agendaContent);

        preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $src) {
                // Handle path seperti: "../storage/tmp/news/news-1766318148-WZdnr.png"
                if (strpos($src, '../storage/tmp/') !== false) {
                    // Ekstrak filename
                    $tmpPath = str_replace('../storage/', '', $src);
                    // hasil: tmp/news/news-1766318148-WZdnr.png

                    $agendaPath = str_replace('tmp', 'agendas/content', $tmpPath);
                    // dd($agendaPath);
                    if (Storage::disk('public')->exists($tmpPath)) {
                        // Move file
                        Storage::disk('public')->move($tmpPath, $agendaPath);

                        // Update src - TANPA ../
                        $agendaSrc = asset('storage/' . $agendaPath);
                        $content = str_replace($src, $agendaSrc, $content);
                    }
                }
            }
        }

        $this->cleanupTempDirectory();

        $title = $antiXss->xss_clean($request->title);
        $slug = $this->generateUniqueSlugAgendas($title);

        // 3. SIMPAN DATA
        Agendas::create([
            'user_id' => session('user')->id,
            'program_id' => $antiXss->xss_clean($request->program),
            'title' => $title,
            'slug' => $slug,
            'thumbnail' => $thumbnailPath,
            'content' => $content,
            'location' => $antiXss->xss_clean($request->location),
            'date' => $antiXss->xss_clean($request->published_at)
        ]);

        // 4. REDIRECT
        return redirect()
            ->route('admin-agenda.index')
            ->with('success', 'Agenda berhasil ditambahkan');
    }

    private function cleanupTempDirectory()
    {
        $tmpDir = 'tmp';
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
        $news = Agendas::where('slug', $slug)->first();
        $user = session('user');

        if ($user->bidang_id != 1 && $user->id != $news->user_id) {
            abort(403);
        }

        $fileExists = $news->thumbnail && Storage::disk('public')->exists('/' . $news->thumbnail);

        $program = Programs::orderByDesc('name')->get();

        $data = array(
            'head' => "Agenda",
            'title' => "Agenda",
            'menu' => "Ubah Agenda",
            'news' => $news,
            'fileExists' => $fileExists,
            'program' => $program
        );
        return view('admin/agenda/edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $antiXss = new AntiXSS();

        $validated = $request->validate([
            'program' => 'required',
            'title' => 'required|string|min:3',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'location' => 'required|string|min:3',
            'published_at' => 'required|date',
            'agendaContent' => 'required',
        ]);

        $news = Agendas::findOrFail($id);
        $user = session('user');

        if ($user->bidang_id != 1 && $user->id != $news->user_id) {
            abort(403);
        }

        $thumbnailPath = $news->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $this->deleteFileIfExists($news->thumbnail);

            $file = $request->file('thumbnail');
            $filename = Str::slug($request->title) . '-' . time() . '.' . $file->getClientOriginalExtension();

            $thumbnailPath = $file->storeAs(
                'agendas/thumbnail',
                $filename,
                'public'
            );
        }

        $content = $antiXss->xss_clean($request->agendaContent);

        preg_match_all('/<img[^>]+src="([^">]+)"/', $content, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $src) {
                try {
                    // Decode URL jika ada encoded characters
                    $decodedSrc = urldecode($src);

                    // Cek berbagai pattern path yang mungkin
                    $patterns = [
                        '/storage/tmp/',
                        '../storage/tmp/',
                        '../../storage/tmp/',
                        'storage/tmp/',
                        '/public/storage/tmp/'
                    ];

                    $isTmpImage = false;
                    $relativePath = '';

                    foreach ($patterns as $pattern) {
                        if (strpos($decodedSrc, $pattern) !== false) {
                            $isTmpImage = true;

                            // Ekstrak path relatif dari berbagai format
                            $relativePath = $this->extractRelativePath($decodedSrc, $pattern);
                            break;
                        }
                    }

                    if ($isTmpImage && $relativePath) {
                        // Normalize path (hapus ../ yang berlebihan)
                        $relativePath = $this->normalizePath($relativePath);

                        // Pastikan masih dalam folder tmp
                        if (Str::startsWith($relativePath, 'tmp/')) {
                            // Path baru untuk agenda
                            $newPath = str_replace('tmp/', 'agendas/content/', $relativePath);

                            // Pindahkan file jika ada
                            if (Storage::disk('public')->exists($relativePath)) {
                                Storage::disk('public')->move($relativePath, $newPath);

                                // URL baru
                                $newUrl = asset('storage/' . $newPath);
                                $content = str_replace($src, $newUrl, $content);
                            }
                        }
                    }

                } catch (\Exception $e) {
                    \Log::error('Error moving tmp image: ' . $e->getMessage(), ['src' => $src]);
                    continue;
                }
            }
        }


        $this->cleanupTempDirectory();

        $title = $antiXss->xss_clean($request->title);
        $slug = $news->slug;

        if (strtolower($news->title) !== strtolower($title)) {
            $slug = $this->generateUniqueSlugAgendas($title);
        }
        $news->update([
            'user_id' => session('user')->id,
            'program_id' => $antiXss->xss_clean($request->program),
            'title' => $title,
            'slug' => $slug,
            'thumbnail' => $thumbnailPath,
            'content' => $content,
            'location' => $antiXss->xss_clean($request->location),
            'date' => $antiXss->xss_clean($request->published_at),
            'updated_at' => now()
        ]);

        return redirect()
            ->route('admin-agenda.index')
            ->with('success', 'Agenda berhasil diperbarui');
    }

    // Helper methods
    private function extractRelativePath($src, $pattern)
    {
        // Ekstrak bagian setelah pattern
        $position = strpos($src, $pattern);
        if ($position !== false) {
            $path = substr($src, $position + strlen($pattern));
            return 'tmp/' . ltrim($path, '/');
        }
        return '';
    }

    private function normalizePath($path)
    {
        // Hapus path traversal yang berbahaya
        $path = str_replace('../', '', $path);
        $path = str_replace('..\\', '', $path);

        // Hapus leading/trailing slashes
        $path = trim($path, '/');

        return $path;
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
    public function destroy(string $slug)
    {
        $agenda = Agendas::where('slug', $slug)->first();
        $user = session('user');

        if ($user->bidang_id != 1 && $user->id != $agenda->user_id) {
            abort(403);
        }
        // hapus thumbnail
        if ($agenda->thumbnail && Storage::disk('public')->exists($agenda->thumbnail)) {
            Storage::disk('public')->delete($agenda->thumbnail);
        }

        // hapus gambar konten
        if ($agenda->content) {
            preg_match_all('/<img[^>]+src="([^">]+)"/i', $agenda->content, $matches);
            foreach ($matches[1] ?? [] as $src) {
                $path = parse_url($src, PHP_URL_PATH);
                if ($path && str_starts_with($path, '/storage/agendas/content/')) {
                    $file = ltrim(str_replace('/storage/', '', $path), '/');
                    Storage::disk('public')->delete($file);
                }
            }
        }

        $agenda->delete();

        return response()->json([
            'message' => 'Agenda berhasil dihapus'
        ]);

    }

    function generateUniqueSlugAgendas($title)
    {
        $slug = Str::limit(Str::slug($title), 150, '');
        $originalSlug = $slug;
        $count = 1;

        while (Agendas::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}

