<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\JenisLayanan;
use App\Models\PertanyaanSurvei;
use App\Models\Responden;
use App\Models\SurveiRespons;
use App\Models\DetailRating;

class SurveiKepuasanAdminController extends Controller
{
    public function index(Request $request)
    {
        $head = "Survei Kepuasan";
        $title = "Survei Kepuasan";
        $menu = "Survei Kepuasan";

        $layanan = JenisLayanan::orderBy('id')->get();
        $pertanyaan = PertanyaanSurvei::where('status', 'aktif')->orderBy('urutan')->get();

        // Query Respon
        $query = SurveiRespons::with(['responden', 'jenisLayanan', 'detailRating.pertanyaan'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('jenis_layanan_id')) {
            $query->where('jenis_layanan_id', $request->jenis_layanan_id);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_survei', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_survei', '<=', $request->tanggal_selesai);
        }

        $responses = $query->paginate(15)->appends($request->all());

        // Hitung Statistik IKM
        $totalResponden = Responden::count();
        $totalRespons = SurveiRespons::count();

        $ratingsByPertanyaan = DetailRating::select('pertanyaan_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(id) as total_votes'))
            ->groupBy('pertanyaan_id')
            ->pluck('avg_rating', 'pertanyaan_id')
            ->toArray();

        $nrrTotal = 0;
        $pertanyaanCount = $pertanyaan->count();
        foreach ($pertanyaan as $item) {
            $nrrTotal += ($ratingsByPertanyaan[$item->id] ?? 0);
        }

        $skorIkm = 0;
        $mutuPelayanan = 'Belum Ada Data';
        $badgeClass = 'bg-secondary';

        if ($pertanyaanCount > 0 && $totalRespons > 0) {
            $nrrTertimbang = $nrrTotal / $pertanyaanCount;
            $skorIkm = round($nrrTertimbang * 20, 2);

            if ($skorIkm >= 88.31) {
                $mutuPelayanan = 'A (Sangat Baik)';
                $badgeClass = 'bg-success';
            } elseif ($skorIkm >= 76.61) {
                $mutuPelayanan = 'B (Baik)';
                $badgeClass = 'bg-primary';
            } elseif ($skorIkm >= 65.00) {
                $mutuPelayanan = 'C (Kurang Baik)';
                $badgeClass = 'bg-warning';
            } else {
                $mutuPelayanan = 'D (Tidak Baik)';
                $badgeClass = 'bg-danger';
            }
        }

        return view('admin.survei.index', compact(
            'head',
            'title',
            'menu',
            'responses',
            'layanan',
            'pertanyaan',
            'totalResponden',
            'totalRespons',
            'skorIkm',
            'mutuPelayanan',
            'badgeClass',
            'ratingsByPertanyaan'
        ));
    }

    public function detail($id)
    {
        $response = SurveiRespons::with(['responden', 'jenisLayanan', 'detailRating.pertanyaan'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $response
        ]);
    }

    public function destroy($id)
    {
        $response = SurveiRespons::findOrFail($id);
        $respondenId = $response->responden_id;

        $response->delete();

        // Jika responden tidak memiliki respon lain, bersihkan responden
        if (SurveiRespons::where('responden_id', $respondenId)->count() === 0) {
            Responden::destroy($respondenId);
        }

        return redirect()->back()->with('success', 'Data hasil survei berhasil dihapus.');
    }

    public function exportCsv()
    {
        $fileName = 'laporan_survei_kepuasan_' . date('Ymd_His') . '.csv';
        $responses = SurveiRespons::with(['responden', 'jenisLayanan', 'detailRating.pertanyaan'])
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Tanggal', 'Nama Responden', 'Jenis Kelamin', 'Usia', 'No. HP', 'Pekerjaan', 'Layanan Dinilai', 'Rata-rata Rating', 'Kritik & Saran'];

        $callback = function() use($responses, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for UTF-8
            fputcsv($file, $columns);

            foreach ($responses as $row) {
                $avgRating = $row->detailRating->avg('rating');
                fputcsv($file, [
                    $row->id,
                    $row->tanggal_survei,
                    $row->responden->nama_lengkap ?? '-',
                    $row->responden->jenis_kelamin ?? '-',
                    $row->responden->usia ?? '-',
                    $row->responden->no_telepon ?? '-',
                    $row->responden->pekerjaan ?? '-',
                    $row->jenisLayanan->nama_layanan ?? '-',
                    number_format($avgRating, 2),
                    $row->komentar ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
