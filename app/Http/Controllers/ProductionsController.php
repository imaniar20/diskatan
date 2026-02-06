<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use voku\helper\AntiXSS;
use App\Models\Productions;
use Illuminate\Support\Str;

class ProductionsController extends Controller
{
    public function index()
    {
        $produksi = Productions::orderByDesc('created_at')->get();

        $data = array(
            'head' => "Produksi",
            'title' => "Produksi",
            'menu' => "Produksi",
            'produksi' => $produksi
        );

        return view('admin.produksi.index')->with($data);
    }

    public function store(Request $request)
    {
        $antiXss = new AntiXSS();
        $unit = 'ton';
        Productions::create([
            'name' => $antiXss->xss_clean($request->nama),
            'value' => $antiXss->xss_clean($request->nilai),
            'unit' => $unit,
            'year' => $antiXss->xss_clean($request->tahun)
        ]);

        return redirect()
            ->route('admin-produksi.index')
            ->with('success', 'Produksi berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        $antiXss = new AntiXSS();
        $produksi = Productions::findOrFail($request->id);
        
        $data = [
            'name' => $antiXss->xss_clean($request->nama_edit),
            'value' => $antiXss->xss_clean($request->nilai_edit),
            'year' => $antiXss->xss_clean($request->tahun_edit)
        ];

        $produksi->update($data);

        return redirect()
            ->route('admin-produksi.index')
            ->with('success', 'Produksi berhasil dirubah');
    }

    public function destroy(string $id)
    {
        $data = Productions::findOrFail($id);

        $data->delete();

        return response()->json([
            'message' => 'Produksi berhasil dihapus'
        ]);
    }
}
