<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use voku\helper\AntiXSS;
use App\Models\Kategori;

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

        Kategori::create([
            'nama' => $antiXss->xss_clean($request->name)
        ]);

        return redirect()
            ->route('admin-kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        $antiXss = new AntiXSS();
        $kategori = Kategori::findOrFail($request->id);
        
        $data = [
            'nama' => $antiXss->xss_clean($request->nama_edit),
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
}
