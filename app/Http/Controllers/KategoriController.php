<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use voku\helper\AntiXSS;
use App\Models\Kategori;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::orderByDesc('created_at')->get();

        $data = array(
            'head' => "Kategori",
            'title' => "Kategori",
            'menu' => "Kategori",
            'kategori' => $kategori
        );

        return view('admin.kategori.index')->with($data);
    }

    public function store(Request $request)
    {
        $antiXss = new AntiXSS();

        $name = $antiXss->xss_clean($request->name);

        $slug = $this->generateUniqueSlug($name);

        Kategori::create([
            'slug' => $slug,
            'nama' => $name,
            'description' => $antiXss->xss_clean($request->deskripsi),
            'icon' => $antiXss->xss_clean($request->icon)
        ]);

        return redirect()
            ->route('admin-kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        $antiXss = new AntiXSS();
        $kategori = Kategori::findOrFail($request->id);

        $name = $antiXss->xss_clean($request->nama_edit);

        // $slug = $this->generateUniqueSlug($name);
        
        $data = [
            // 'slug' => $slug,
            'nama' => $name,
            'description' => $antiXss->xss_clean($request->deskripsi_edit),
            'icon' => $antiXss->xss_clean($request->icon_edit)
        ];

        $kategori->update($data);

        return redirect()
            ->route('admin-kategori.index')
            ->with('success', 'Kategori berhasil dirubah');
    }

    public function destroy(string $id)
    {
        $data = Kategori::findOrFail($id);

        $data->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus'
        ]);
    }

    function generateUniqueSlug($name)
    {
        $slug = Str::limit(Str::slug($name), 150, '');
        $originalSlug = $slug;
        $count = 1;

        while (Kategori::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
