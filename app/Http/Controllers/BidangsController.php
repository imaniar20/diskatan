<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use voku\helper\AntiXSS;
use App\Models\Bidangs;

class BidangsController extends Controller
{
    public function index()
    {
        $bidang = Bidangs::orderByDesc('created_at')->get();

        $data = array(
            'head' => "Bidang",
            'title' => "Bidang",
            'menu' => "Bidang",
            'bidang' => $bidang
        );

        return view('admin.bidang.index')->with($data);
    }

    public function store(Request $request)
    {
        $antiXss = new AntiXSS();

        Bidangs::create([
            'nama' => $antiXss->xss_clean($request->name)
        ]);

        return redirect()
            ->route('admin-bidang.index')
            ->with('success', 'Bidang berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        $antiXss = new AntiXSS();
        $bidang = Bidangs::findOrFail($request->id);
        
        $data = [
            'nama' => $antiXss->xss_clean($request->nama_edit),
        ];

        $bidang->update($data);

        return redirect()
            ->route('admin-bidang.index')
            ->with('success', 'Bidang berhasil dirubah');
    }

    public function destroy(string $id)
    {
        $data = Bidangs::findOrFail($id);

        $data->delete();

        return response()->json([
            'message' => 'Bidang berhasil dihapus'
        ]);
    }
}
