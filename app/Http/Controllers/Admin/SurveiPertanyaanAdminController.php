<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PertanyaanSurvei;

class SurveiPertanyaanAdminController extends Controller
{
    public function index()
    {
        $head = "Survei Kepuasan";
        $title = "Master Pertanyaan";
        $menu = "Survei Kepuasan";

        $pertanyaan = PertanyaanSurvei::withCount('detailRating')->orderBy('urutan')->get();

        return view('admin.survei.pertanyaan', compact('head', 'title', 'menu', 'pertanyaan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'teks_pertanyaan'  => 'required|string',
            'icon'             => 'nullable|string|max:50',
            'kunci_pertanyaan' => 'required|string|max:50|unique:pertanyaan_survei,kunci_pertanyaan',
            'urutan'           => 'required|integer',
            'status'           => 'required|in:aktif,nonaktif',
        ]);

        PertanyaanSurvei::create([
            'teks_pertanyaan'  => $request->teks_pertanyaan,
            'icon'             => $request->icon ?? 'bi-star',
            'kunci_pertanyaan' => $request->kunci_pertanyaan,
            'urutan'           => $request->urutan,
            'status'           => $request->status,
        ]);

        return redirect()->back()->with('success', 'Butir pertanyaan survei berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pertanyaan = PertanyaanSurvei::findOrFail($id);

        $request->validate([
            'teks_pertanyaan'  => 'required|string',
            'icon'             => 'nullable|string|max:50',
            'kunci_pertanyaan' => 'required|string|max:50|unique:pertanyaan_survei,kunci_pertanyaan,' . $id,
            'urutan'           => 'required|integer',
            'status'           => 'required|in:aktif,nonaktif',
        ]);

        $pertanyaan->update([
            'teks_pertanyaan'  => $request->teks_pertanyaan,
            'icon'             => $request->icon ?? 'bi-star',
            'kunci_pertanyaan' => $request->kunci_pertanyaan,
            'urutan'           => $request->urutan,
            'status'           => $request->status,
        ]);

        return redirect()->back()->with('success', 'Butir pertanyaan survei berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pertanyaan = PertanyaanSurvei::withCount('detailRating')->findOrFail($id);

        if ($pertanyaan->detail_rating_count > 0) {
            $pertanyaan->update(['status' => 'nonaktif']);
            return redirect()->back()->with('warning', 'Pertanyaan telah memiliki riwayat penilaian dari responden. Status otomatis diubah menjadi Nonaktif.');
        }

        $pertanyaan->delete();
        return redirect()->back()->with('success', 'Pertanyaan survei berhasil dihapus.');
    }
}
