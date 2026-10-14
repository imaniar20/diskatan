<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PadiPalawija;
use App\Services\PadiPalawijaExcelService;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use voku\helper\AntiXSS;

class AdminPadiPalawijaController extends Controller
{
    /**
     * Memastikan hanya admin yang dapat mengakses
     */
    protected function checkAdmin()
    {
        if (!session()->has('user') || session('user')->username !== 'admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Administrator.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkAdmin();

        $komoditasList = [
            'Padi',
            'Jagung',
            'Kedelai',
            'Kacang Tanah',
            'Kacang Hijau',
            'Ubi Kayu',
            'Ubi Jalar',
        ];

        // Daftar tahun yang ada di database + opsi default
        $availableYears = PadiPalawija::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = ['2026', '2025'];
        }

        // Pastikan tahun sekarang dan 2026 ada di daftar jika relevan
        $currentYear = (string) date('Y');
        if (!in_array('2026', $availableYears)) {
            $availableYears[] = '2026';
        }
        if (!in_array($currentYear, $availableYears)) {
            $availableYears[] = $currentYear;
        }
        rsort($availableYears);

        $selectedTahun = $request->query('tahun', $availableYears[0] ?? '2026');
        $selectedKomoditas = $request->query('komoditas', 'Padi');
        if (!in_array($selectedKomoditas, $komoditasList)) {
            $selectedKomoditas = 'Padi';
        }

        $query = PadiPalawija::where('komoditas', $selectedKomoditas)
            ->where('tahun', $selectedTahun);

        if ($request->has('search') && !empty($request->search)) {
            $query->where('kecamatan', 'like', '%' . $request->search . '%');
        }

        $items = $query->orderBy('no_urut', 'asc')->get();

        // Ringkasan Statistik Komoditas Terpilih
        $totalSawah = $items->sum('luas_sawah');
        $totalTarget = $items->sum('target_total');
        $totalRealisasiMH = $items->sum('jumlah_mh');
        $totalRealisasiMK = $items->sum('jumlah_mk');
        $totalRealisasi = $items->sum('total_realisasi');
        $persentase = $totalTarget > 0 ? round(($totalRealisasi / $totalTarget) * 100, 2) : 0;

        $data = [
            'head' => 'Padi & Palawija',
            'title' => 'Padi & Palawija',
            'menu' => 'Padi & Palawija',
            'komoditasList' => $komoditasList,
            'selectedKomoditas' => $selectedKomoditas,
            'availableYears' => $availableYears,
            'selectedTahun' => $selectedTahun,
            'items' => $items,
            'stats' => [
                'totalSawah' => $totalSawah,
                'totalTarget' => $totalTarget,
                'totalRealisasiMH' => $totalRealisasiMH,
                'totalRealisasiMK' => $totalRealisasiMK,
                'totalRealisasi' => $totalRealisasi,
                'persentase' => $persentase,
            ],
        ];

        return view('admin.padi_palawija.index', $data);
    }

    /**
     * Export data Padi & Palawija per tahun ke file Excel (XLSX 7 Sheet)
     */
    public function export(Request $request, PadiPalawijaExcelService $excelService): StreamedResponse
    {
        $this->checkAdmin();

        $tahun = $request->query('tahun', '2026');
        $spreadsheet = $excelService->export($tahun);

        $filename = "Target_dan_Realisasi_Padi_Palawija_{$tahun}.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Import file Excel (XLS/XLSX) dan mengganti (replace) data untuk tahun terpilih
     */
    public function import(Request $request, PadiPalawijaExcelService $excelService)
    {
        $this->checkAdmin();

        $request->validate([
            'file_excel' => 'required|file|mimes:xls,xlsx|max:15360',
            'tahun'      => 'required|numeric|min:2020|max:2050',
        ], [
            'file_excel.required' => 'File Excel wajib diunggah.',
            'file_excel.mimes'    => 'Format file harus berupa .xls atau .xlsx.',
            'file_excel.max'      => 'Ukuran file maksimal adalah 15MB.',
            'tahun.required'      => 'Tahun target data wajib ditentukan.',
        ]);

        $tahun = (string) $request->tahun;
        $file = $request->file('file_excel');

        try {
            $result = $excelService->import($file, $tahun);

            if (!$result['success']) {
                return redirect()
                    ->route('admin-padi-palawija.index', ['tahun' => $tahun])
                    ->with('error', $result['message']);
            }

            return redirect()
                ->route('admin-padi-palawija.index', ['tahun' => $tahun])
                ->with('success', "{$result['message']} (Total: {$result['totalRows']} data berhasil disimpan).");

        } catch (\Throwable $th) {
            return redirect()
                ->route('admin-padi-palawija.index', ['tahun' => $tahun])
                ->with('error', 'Gagal memproses file Excel: ' . $th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAdmin();
        $antiXss = new AntiXSS();

        $request->validate([
            'komoditas' => 'required|string',
            'kecamatan' => 'required|string',
            'tahun'     => 'required|numeric',
        ]);

        $komoditas = $antiXss->xss_clean($request->komoditas);
        $kecamatan = strtoupper($antiXss->xss_clean($request->kecamatan));
        $tahun = (string) $request->tahun;
        $luasSawah = (float) $request->luas_sawah;

        $tOkmar = (float) $request->target_okmar;
        $tAsep = (float) $request->target_asep;
        $tTotal = (float) ($request->target_total ?: ($tOkmar + $tAsep));

        $rOkt = (float) $request->realisasi_okt;
        $rNop = (float) $request->realisasi_nop;
        $rDes = (float) $request->realisasi_des;
        $rJan = (float) $request->realisasi_jan;
        $rFeb = (float) $request->realisasi_feb;
        $rMar = (float) $request->realisasi_mar;
        $rMH = (float) ($request->jumlah_mh ?: ($rOkt + $rNop + $rDes + $rJan + $rFeb + $rMar));

        $rApr = (float) $request->realisasi_apr;
        $rMei = (float) $request->realisasi_mei;
        $rJun = (float) $request->realisasi_jun;
        $rJul = (float) $request->realisasi_jul;
        $rAgs = (float) $request->realisasi_ags;
        $rSep = (float) $request->realisasi_sep;
        $rMK = (float) ($request->jumlah_mk ?: ($rApr + $rMei + $rJun + $rJul + $rAgs + $rSep));

        $rTotal = (float) ($request->total_realisasi ?: ($rMH + $rMK));
        $persen = $tTotal > 0 ? round(($rTotal / $tTotal) * 100, 2) : 0;

        $lastNo = PadiPalawija::where('komoditas', $komoditas)->where('tahun', $tahun)->max('no_urut') ?: 0;
        $prevYear = (int)$tahun - 1;

        PadiPalawija::create([
            'komoditas' => $komoditas,
            'tahun' => $tahun,
            'musim_tanam' => "MT {$prevYear}/{$tahun} dan MT {$tahun}",
            'no_urut' => $lastNo + 1,
            'kecamatan' => $kecamatan,
            'luas_sawah' => $luasSawah,
            'target_okmar' => $tOkmar,
            'target_asep' => $tAsep,
            'target_total' => $tTotal,
            'realisasi_okt' => $rOkt,
            'realisasi_nop' => $rNop,
            'realisasi_des' => $rDes,
            'realisasi_jan' => $rJan,
            'realisasi_feb' => $rFeb,
            'realisasi_mar' => $rMar,
            'jumlah_mh' => $rMH,
            'realisasi_apr' => $rApr,
            'realisasi_mei' => $rMei,
            'realisasi_jun' => $rJun,
            'realisasi_jul' => $rJul,
            'realisasi_ags' => $rAgs,
            'realisasi_sep' => $rSep,
            'jumlah_mk' => $rMK,
            'total_realisasi' => $rTotal,
            'persentase_capaian' => $persen,
        ]);

        return redirect()
            ->route('admin-padi-palawija.index', ['komoditas' => $komoditas, 'tahun' => $tahun])
            ->with('success', "Data {$komoditas} Kecamatan {$kecamatan} Tahun {$tahun} berhasil ditambahkan");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkAdmin();
        $antiXss = new AntiXSS();

        $item = PadiPalawija::findOrFail($id);

        $kecamatan = strtoupper($antiXss->xss_clean($request->kecamatan_edit ?: $item->kecamatan));
        $luasSawah = (float) ($request->luas_sawah_edit ?? $item->luas_sawah);

        $tOkmar = (float) ($request->target_okmar_edit ?? $item->target_okmar);
        $tAsep = (float) ($request->target_asep_edit ?? $item->target_asep);
        $tTotal = (float) ($request->target_total_edit ?: ($tOkmar + $tAsep));

        $rOkt = (float) ($request->realisasi_okt_edit ?? $item->realisasi_okt);
        $rNop = (float) ($request->realisasi_nop_edit ?? $item->realisasi_nop);
        $rDes = (float) ($request->realisasi_des_edit ?? $item->realisasi_des);
        $rJan = (float) ($request->realisasi_jan_edit ?? $item->realisasi_jan);
        $rFeb = (float) ($request->realisasi_feb_edit ?? $item->realisasi_feb);
        $rMar = (float) ($request->realisasi_mar_edit ?? $item->realisasi_mar);
        $rMH = (float) ($rOkt + $rNop + $rDes + $rJan + $rFeb + $rMar);

        $rApr = (float) ($request->realisasi_apr_edit ?? $item->realisasi_apr);
        $rMei = (float) ($request->realisasi_mei_edit ?? $item->realisasi_mei);
        $rJun = (float) ($request->realisasi_jun_edit ?? $item->realisasi_jun);
        $rJul = (float) ($request->realisasi_jul_edit ?? $item->realisasi_jul);
        $rAgs = (float) ($request->realisasi_ags_edit ?? $item->realisasi_ags);
        $rSep = (float) ($request->realisasi_sep_edit ?? $item->realisasi_sep);
        $rMK = (float) ($rApr + $rMei + $rJun + $rJul + $rAgs + $rSep);

        $rTotal = (float) ($rMH + $rMK);
        $persen = $tTotal > 0 ? round(($rTotal / $tTotal) * 100, 2) : 0;

        $item->update([
            'kecamatan' => $kecamatan,
            'luas_sawah' => $luasSawah,
            'target_okmar' => $tOkmar,
            'target_asep' => $tAsep,
            'target_total' => $tTotal,
            'realisasi_okt' => $rOkt,
            'realisasi_nop' => $rNop,
            'realisasi_des' => $rDes,
            'realisasi_jan' => $rJan,
            'realisasi_feb' => $rFeb,
            'realisasi_mar' => $rMar,
            'jumlah_mh' => $rMH,
            'realisasi_apr' => $rApr,
            'realisasi_mei' => $rMei,
            'realisasi_jun' => $rJun,
            'realisasi_jul' => $rJul,
            'realisasi_ags' => $rAgs,
            'realisasi_sep' => $rSep,
            'jumlah_mk' => $rMK,
            'total_realisasi' => $rTotal,
            'persentase_capaian' => $persen,
        ]);

        return redirect()
            ->route('admin-padi-palawija.index', ['komoditas' => $item->komoditas, 'tahun' => $item->tahun])
            ->with('success', "Data {$item->komoditas} Kecamatan {$kecamatan} Tahun {$item->tahun} berhasil diubah");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->checkAdmin();
        $item = PadiPalawija::findOrFail($id);
        $komoditas = $item->komoditas;
        $kecamatan = $item->kecamatan;
        $tahun = $item->tahun;
        $item->delete();

        return redirect()
            ->route('admin-padi-palawija.index', ['komoditas' => $komoditas, 'tahun' => $tahun])
            ->with('success', "Data {$komoditas} Kecamatan {$kecamatan} Tahun {$tahun} berhasil dihapus");
    }
}
