<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HortikulturaUtama;
use App\Models\Komoditas;
use App\Services\HortikulturaUtamaExcelService;
use App\Services\PadiPalawijaExcelService;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminHortikulturaUtamaController extends Controller
{
    protected function checkAdmin()
    {
        if (!session()->has('user') || session('user')->username !== 'admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Administrator.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdmin();

        $komoditasList = Komoditas::getListBySektor('hortikultura');

        $kategoriConfig = [
            'tanam' => [
                'key' => 'tanam',
                'label' => 'Luas Tanam',
                'satuan' => 'Ha',
                'icon' => 'bx-spa',
                'badge' => 'bg-label-success',
                'target_sub1_label' => 'Target MH',
                'target_sub2_label' => 'Target MK',
                'realisasi_sub1_label' => 'Realisasi MH (Okt-Mar)',
                'realisasi_sub2_label' => 'Realisasi MK (Apr-Sep)',
                'persen_sub_label' => '% MH',
                'persen_total_label' => '% Tahun',
                'months_sub1' => ['Okt', 'Nop', 'Des', 'Jan', 'Feb', 'Mar'],
                'months_sub2' => ['Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep'],
            ],
            'panen' => [
                'key' => 'panen',
                'label' => 'Luas Panen',
                'satuan' => 'Ha',
                'icon' => 'bx-cut',
                'badge' => 'bg-label-primary',
                'target_sub1_label' => 'Target Jan-Jun',
                'target_sub2_label' => 'Target Jul-Des',
                'realisasi_sub1_label' => 'Realisasi Jan-Jun',
                'realisasi_sub2_label' => 'Realisasi Jul-Des',
                'persen_sub_label' => '% Jan-Jun',
                'persen_total_label' => '% Tahun',
                'months_sub1' => ['Jan', 'Peb', 'Maret', 'April', 'Mei', 'Juni'],
                'months_sub2' => ['Juli', 'Agst', 'Sept', 'Okt', 'Nov', 'Des'],
            ],
            'produksi' => [
                'key' => 'produksi',
                'label' => 'Produksi',
                'satuan' => 'Ton',
                'icon' => 'bx-package',
                'badge' => 'bg-label-warning',
                'target_sub1_label' => 'Target Jan-Jun',
                'target_sub2_label' => 'Target Jul-Des',
                'realisasi_sub1_label' => 'Realisasi Jan-Jun',
                'realisasi_sub2_label' => 'Realisasi Jul-Des',
                'persen_sub_label' => '% Jan-Jun',
                'persen_total_label' => '% Tahun',
                'months_sub1' => ['Jan', 'Peb', 'Maret', 'April', 'Mei', 'Juni'],
                'months_sub2' => ['Juli', 'Agst', 'Sept', 'Okt', 'Nov', 'Des'],
            ],
            'produktivitas' => [
                'key' => 'produktivitas',
                'label' => 'Produktivitas',
                'satuan' => 'Kw/Ha',
                'icon' => 'bx-line-chart',
                'badge' => 'bg-label-info',
                'target_sub1_label' => 'Target Jan-Jun',
                'target_sub2_label' => 'Target Jul-Des',
                'realisasi_sub1_label' => 'Rata-rata Jan-Jun',
                'realisasi_sub2_label' => 'Rata-rata Jul-Des',
                'persen_sub_label' => '% Jan-Jun',
                'persen_total_label' => '% Tahun',
                'months_sub1' => ['Jan', 'Peb', 'Maret', 'April', 'Mei', 'Juni'],
                'months_sub2' => ['Juli', 'Agst', 'Sept', 'Okt', 'Nov', 'Des'],
            ],
            'puso' => [
                'key' => 'puso',
                'label' => 'Puso (Gagal Panen)',
                'satuan' => 'Ha',
                'icon' => 'bx-error-circle',
                'badge' => 'bg-label-danger',
                'target_sub1_label' => 'Target Jan-Jun',
                'target_sub2_label' => 'Target Jul-Des',
                'realisasi_sub1_label' => 'Realisasi Jan-Jun',
                'realisasi_sub2_label' => 'Realisasi Jul-Des',
                'persen_sub_label' => '% Jan-Jun',
                'persen_total_label' => '% Total',
                'months_sub1' => ['Jan', 'Peb', 'Maret', 'April', 'Mei', 'Juni'],
                'months_sub2' => ['Juli', 'Agst', 'Sept', 'Okt', 'Nov', 'Des'],
            ],
        ];

        $availableYears = HortikulturaUtama::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = ['2026', '2025'];
        }
        if (!in_array('2026', $availableYears)) {
            $availableYears[] = '2026';
        }
        rsort($availableYears);

        $selectedTahun = $request->query('tahun', $availableYears[0] ?? '2026');
        $selectedKomoditas = $request->query('komoditas', 'Bawang Merah');
        if (!in_array($selectedKomoditas, $komoditasList)) {
            $selectedKomoditas = 'Bawang Merah';
        }

        $selectedKategori = $request->query('kategori', 'tanam');
        if (!array_key_exists($selectedKategori, $kategoriConfig)) {
            $selectedKategori = 'tanam';
        }

        $query = HortikulturaUtama::where('komoditas', $selectedKomoditas)
            ->where('tahun', $selectedTahun)
            ->where('kategori', $selectedKategori);

        if ($request->has('search') && !empty($request->search)) {
            $query->where('kecamatan', 'like', '%' . $request->search . '%');
        }

        $items = $query->orderBy('no_urut', 'asc')->get();

        $activeConfig = $kategoriConfig[$selectedKategori];
        $totalSawah = $items->sum('luas_sawah');
        $totalTarget = $items->sum('target_total');
        $totalRealisasiSub1 = $items->sum('jumlah_mh');
        $totalRealisasiSub2 = $items->sum('jumlah_mk');
        $totalRealisasi = $items->sum('total_realisasi');

        $stats = [
            'card1_title' => 'Luas Lahan/Sawah',
            'card1_value' => number_format($totalSawah, 1) . ' Ha',
            'card1_sub' => 'Total Se-Kabupaten',
            'card1_icon' => 'bx-map-alt',
            'card1_badge' => 'bg-label-primary',

            'card2_title' => 'Target Total',
            'card2_value' => number_format($totalTarget, 1) . ' ' . $activeConfig['satuan'],
            'card2_sub' => 'Musim Hujan + Kemarau',
            'card2_icon' => 'bx-target-lock',
            'card2_badge' => 'bg-label-warning',

            'card3_title' => 'Total Realisasi',
            'card3_value' => number_format($totalRealisasi, 1) . ' ' . $activeConfig['satuan'],
            'card3_sub' => 'Total MH + MK',
            'card3_icon' => 'bx-check-double',
            'card3_badge' => 'bg-label-success',

            'card4_title' => 'Persentase Capaian',
            'card4_value' => ($totalTarget > 0 ? round(($totalRealisasi / $totalTarget) * 100, 2) : 0) . '%',
            'card4_sub' => 'Capaian Terhadap Target',
            'card4_icon' => 'bx-pie-chart-alt',
            'card4_badge' => 'bg-label-info',
        ];

        $chartCategories = $items->pluck('kecamatan')->toArray();
        $chartTarget = $items->pluck('target_total')->toArray();
        $chartRealisasi = $items->pluck('total_realisasi')->toArray();

        $masterKecamatan = PadiPalawijaExcelService::MASTER_KECAMATAN;

        $data = [
            'head' => 'Data Utama Hortikultura',
            'title' => 'Hortikultura Utama',
            'menu' => 'Hortikultura Utama',
            'komoditasList' => $komoditasList,
            'selectedKomoditas' => $selectedKomoditas,
            'kategoriConfig' => $kategoriConfig,
            'selectedKategori' => $selectedKategori,
            'activeConfig' => $activeConfig,
            'availableYears' => $availableYears,
            'selectedTahun' => $selectedTahun,
            'items' => $items,
            'stats' => $stats,
            'masterKecamatan' => $masterKecamatan,
            'chart' => [
                'categories' => $chartCategories,
                'target' => $chartTarget,
                'realisasi' => $chartRealisasi,
            ],
        ];

        return view('admin.hortikultura_utama.index', $data);
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'komoditas' => 'required|string',
            'kategori' => 'required|string',
            'tahun' => 'required|string|max:10',
            'kecamatan' => 'required|string',
            'satuan' => 'nullable|string',
            'luas_sawah' => 'nullable|numeric|min:0',
            'target_okmar' => 'nullable|numeric|min:0',
            'target_asep' => 'nullable|numeric|min:0',
            'realisasi_okt' => 'nullable|numeric|min:0',
            'realisasi_nop' => 'nullable|numeric|min:0',
            'realisasi_des' => 'nullable|numeric|min:0',
            'realisasi_jan' => 'nullable|numeric|min:0',
            'realisasi_feb' => 'nullable|numeric|min:0',
            'realisasi_mar' => 'nullable|numeric|min:0',
            'realisasi_apr' => 'nullable|numeric|min:0',
            'realisasi_mei' => 'nullable|numeric|min:0',
            'realisasi_jun' => 'nullable|numeric|min:0',
            'realisasi_jul' => 'nullable|numeric|min:0',
            'realisasi_ags' => 'nullable|numeric|min:0',
            'realisasi_sep' => 'nullable|numeric|min:0',
        ]);

        $this->calculateTotalsAndSave(new HortikulturaUtama(), $validated);

        return redirect()->back()->with('success', 'Data Hortikultura Utama berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $this->checkAdmin();
        $item = HortikulturaUtama::findOrFail($id);

        $validated = $request->validate([
            'satuan' => 'nullable|string',
            'luas_sawah' => 'nullable|numeric|min:0',
            'target_okmar' => 'nullable|numeric|min:0',
            'target_asep' => 'nullable|numeric|min:0',
            'realisasi_okt' => 'nullable|numeric|min:0',
            'realisasi_nop' => 'nullable|numeric|min:0',
            'realisasi_des' => 'nullable|numeric|min:0',
            'realisasi_jan' => 'nullable|numeric|min:0',
            'realisasi_feb' => 'nullable|numeric|min:0',
            'realisasi_mar' => 'nullable|numeric|min:0',
            'realisasi_apr' => 'nullable|numeric|min:0',
            'realisasi_mei' => 'nullable|numeric|min:0',
            'realisasi_jun' => 'nullable|numeric|min:0',
            'realisasi_jul' => 'nullable|numeric|min:0',
            'realisasi_ags' => 'nullable|numeric|min:0',
            'realisasi_sep' => 'nullable|numeric|min:0',
        ]);

        $this->calculateTotalsAndSave($item, $validated);

        return redirect()->back()->with('success', 'Data Hortikultura Utama berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->checkAdmin();
        $item = HortikulturaUtama::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Data Hortikultura Utama berhasil dihapus.');
    }

    public function export(Request $request, HortikulturaUtamaExcelService $excelService): StreamedResponse
    {
        $this->checkAdmin();
        $tahun = $request->query('tahun', date('Y'));
        $spreadsheet = $excelService->export($tahun);

        $filename = "Data_Utama_Hortikultura_{$tahun}.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function import(Request $request, HortikulturaUtamaExcelService $excelService)
    {
        $this->checkAdmin();
        $request->validate([
            'file_excel' => 'required|file|mimes:xls,xlsx|max:15360',
            'tahun' => 'required|string',
        ]);

        $tahun = $request->input('tahun');
        $komoditas = $request->input('komoditas');

        try {
            $result = $excelService->import($request->file('file_excel'), $tahun, $komoditas);
            return redirect()->back()->with('success', $result['message']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal impor data: ' . $e->getMessage());
        }
    }

    private function calculateTotalsAndSave(HortikulturaUtama $item, array $data): HortikulturaUtama
    {
        foreach ($data as $key => $val) {
            $item->{$key} = $val;
        }

        $targetOkmar = (float)($item->target_okmar ?? 0);
        $targetAsep = (float)($item->target_asep ?? 0);
        $item->target_total = $targetOkmar + $targetAsep;

        $rOkt = (float)($item->realisasi_okt ?? 0);
        $rNop = (float)($item->realisasi_nop ?? 0);
        $rDes = (float)($item->realisasi_des ?? 0);
        $rJan = (float)($item->realisasi_jan ?? 0);
        $rFeb = (float)($item->realisasi_feb ?? 0);
        $rMar = (float)($item->realisasi_mar ?? 0);
        $sumMh = $rOkt + $rNop + $rDes + $rJan + $rFeb + $rMar;
        $item->jumlah_mh = $sumMh;

        $rApr = (float)($item->realisasi_apr ?? 0);
        $rMei = (float)($item->realisasi_mei ?? 0);
        $rJun = (float)($item->realisasi_jun ?? 0);
        $rJul = (float)($item->realisasi_jul ?? 0);
        $rAgs = (float)($item->realisasi_ags ?? 0);
        $rSep = (float)($item->realisasi_sep ?? 0);
        $sumMk = $rApr + $rMei + $rJun + $rJul + $rAgs + $rSep;
        $item->jumlah_mk = $sumMk;

        $totalReal = $sumMh + $sumMk;
        $item->total_realisasi = $totalReal;

        $item->persentase_sub = $targetOkmar > 0 ? round(($sumMh / $targetOkmar) * 100, 2) : 0;
        $item->persentase_capaian = $item->target_total > 0 ? round(($totalReal / $item->target_total) * 100, 2) : 0;

        $item->save();
        return $item;
    }
}
