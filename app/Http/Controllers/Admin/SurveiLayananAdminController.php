<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisLayanan;

class SurveiLayananAdminController extends Controller
{
    public function index()
    {
        $head = "Survei Kepuasan";
        $title = "Master Layanan";
        $menu = "Survei Kepuasan";

        $layanan = JenisLayanan::withCount('surveiRespons')->orderBy('id')->get();

        return view('admin.survei.layanan', compact('head', 'title', 'menu', 'layanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:150|unique:jenis_layanan,nama_layanan',
            'deskripsi'    => 'nullable|string',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        JenisLayanan::create([
            'nama_layanan' => $request->nama_layanan,
            'deskripsi'    => $request->deskripsi,
            'status'       => $request->status,
        ]);

        return redirect()->back()->with('success', 'Master jenis layanan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $layanan = JenisLayanan::findOrFail($id);

        $request->validate([
            'nama_layanan' => 'required|string|max:150|unique:jenis_layanan,nama_layanan,' . $id,
            'deskripsi'    => 'nullable|string',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        $layanan->update([
            'nama_layanan' => $request->nama_layanan,
            'deskripsi'    => $request->deskripsi,
            'status'       => $request->status,
        ]);

        return redirect()->back()->with('success', 'Master jenis layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $layanan = JenisLayanan::withCount('surveiRespons')->findOrFail($id);

        if ($layanan->survei_respons_count > 0) {
            $layanan->update(['status' => 'nonaktif']);
            return redirect()->back()->with('warning', 'Layanan memiliki riwayat survei responden. Status otomatis diubah menjadi Nonaktif.');
        }

        $layanan->delete();
        return redirect()->back()->with('success', 'Jenis layanan berhasil dihapus.');
    }
}
