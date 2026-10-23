<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JenisLayanan;
use App\Models\PertanyaanSurvei;
use App\Models\Responden;
use App\Models\SurveiRespons;
use App\Models\DetailRating;

class SurveiKepuasanPublicController extends Controller
{
    public function index()
    {
        $layanan = JenisLayanan::where('status', 'aktif')->orderBy('id')->get();
        $pertanyaan = PertanyaanSurvei::where('status', 'aktif')->orderBy('urutan')->get();

        // Hitung Statistik IKM
        $totalResponden = Responden::count();
        $totalRespons = SurveiRespons::count();

        // Rata-rata per pertanyaan
        $ratingsByPertanyaan = DetailRating::select('pertanyaan_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(id) as total_votes'))
            ->groupBy('pertanyaan_id')
            ->pluck('avg_rating', 'pertanyaan_id')
            ->toArray();

        $nrrTotal = 0;
        $pertanyaanAktifCount = $pertanyaan->count();

        foreach ($pertanyaan as $item) {
            $avg = $ratingsByPertanyaan[$item->id] ?? 0;
            $nrrTotal += $avg;
        }

        // Skor IKM Konversi = (Total NRR / Jumlah Unsur) * 20 (skala 100 untuk skala penilaian 1-5)
        $skorIkm = 0;
        $mutuPelayanan = 'Belum Ada Data';
        $badgeClass = 'bg-secondary';

        if ($pertanyaanAktifCount > 0 && $totalRespons > 0) {
            $nrrTertimbang = $nrrTotal / $pertanyaanAktifCount;
            $skorIkm = round($nrrTertimbang * 20, 2);

            if ($skorIkm >= 88.31) {
                $mutuPelayanan = 'A (Sangat Baik)';
                $badgeClass = 'bg-success';
            } elseif ($skorIkm >= 76.61) {
                $mutuPelayanan = 'B (Baik)';
                $badgeClass = 'bg-primary';
            } elseif ($skorIkm >= 65.00) {
                $mutuPelayanan = 'C (Kurang Baik)';
                $badgeClass = 'bg-warning text-dark';
            } else {
                $mutuPelayanan = 'D (Tidak Baik)';
                $badgeClass = 'bg-danger';
            }
        }

        // Distribusi Bintang Keseluruhan (1-5)
        $ratingCounts = DetailRating::select('rating', DB::raw('COUNT(*) as total'))
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        $ratingDistribution = [
            5 => $ratingCounts[5] ?? 0,
            4 => $ratingCounts[4] ?? 0,
            3 => $ratingCounts[3] ?? 0,
            2 => $ratingCounts[2] ?? 0,
            1 => $ratingCounts[1] ?? 0,
        ];

        $title = 'Layanan';
        $menu = 'Layanan';

        return view('public.survei.index', compact(
            'title',
            'menu',
            'layanan',
            'pertanyaan',
            'totalResponden',
            'skorIkm',
            'mutuPelayanan',
            'badgeClass',
            'ratingsByPertanyaan',
            'ratingDistribution'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'     => 'required|string|max:150',
            'usia'             => 'required|in:17-25,26-35,36-45,46-55,56+',
            'jenis_kelamin'    => 'required|in:Laki-laki,Perempuan',
            'no_telepon'       => 'nullable|string|max:25',
            'pekerjaan'        => 'nullable|string|max:100',
            'jenis_layanan_id' => 'required|exists:jenis_layanan,id',
            'ratings'          => 'required|array',
            'komentar'         => 'nullable|string|max:1000',
        ]);

        $pertanyaanAktif = PertanyaanSurvei::where('status', 'aktif')->pluck('id')->toArray();
        foreach ($pertanyaanAktif as $pId) {
            if (!isset($request->ratings[$pId]) || $request->ratings[$pId] < 1 || $request->ratings[$pId] > 5) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Harap berikan penilaian bintang untuk semua butir pertanyaan kuesioner!'
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            // 1. Simpan responden
            $responden = Responden::create([
                'nama_lengkap'  => $request->nama_lengkap,
                'usia'          => $request->usia,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_telepon'    => $request->no_telepon,
                'pekerjaan'     => $request->pekerjaan,
            ]);

            // 2. Simpan respon survei
            $respons = SurveiRespons::create([
                'responden_id'     => $responden->id,
                'jenis_layanan_id' => $request->jenis_layanan_id,
                'tanggal_survei'   => now()->toDateString(),
                'komentar'         => $request->komentar,
            ]);

            // 3. Simpan detail rating
            foreach ($pertanyaanAktif as $pId) {
                DetailRating::create([
                    'survei_respons_id' => $respons->id,
                    'pertanyaan_id'     => $pId,
                    'rating'            => (int) $request->ratings[$pId],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Terima kasih atas partisipasi Anda! Penilaian survei kepuasan telah berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data survei: ' . $e->getMessage()
            ], 500);
        }
    }
}
