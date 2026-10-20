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

        $kategoriConfig = [
            'tanam' => [
                'key' => 'tanam',
                'label' => 'Luas Tanam',
                'satuan' => 'Ha',
                'icon' => 'bx-spa',
                'badge' => 'bg-label-success',
                'target_sub1_label' => 'Target Okmar (MH)',
                'target_sub2_label' => 'Target Asep (MK)',
                'realisasi_sub1_label' => 'Realisasi MH (Okt-Mar)',
                'realisasi_sub2_label' => 'Realisasi MK (Apr-Sep)',
                'persen_sub_label' => '% Okt-Mar',
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

        // Daftar tahun yang ada di database + opsi default
        $availableYears = PadiPalawija::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = ['2026', '2025'];
        }

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

        $selectedKategori = $request->query('kategori', 'tanam');
        if (!array_key_exists($selectedKategori, $kategoriConfig)) {
            $selectedKategori = 'tanam';
        }

        $query = PadiPalawija::where('komoditas', $selectedKomoditas)
            ->where('tahun', $selectedTahun)
            ->where('kategori', $selectedKategori);

        if ($request->has('search') && !empty($request->search)) {
            $query->where('kecamatan', 'like', '%' . $request->search . '%');
        }

        $items = $query->orderBy('no_urut', 'asc')->get();

        // Ringkasan Statistik Dinamis Berdasarkan Kategori
        $activeConfig = $kategoriConfig[$selectedKategori];
        $totalSawah = $items->sum('luas_sawah');
        $totalTargetSub1 = $items->sum('target_okmar');
        $totalTargetSub2 = $items->sum('target_asep');
        $totalTarget = $items->sum('target_total');
        $totalRealisasiSub1 = $items->sum('jumlah_mh');
        $totalRealisasiSub2 = $items->sum('jumlah_mk');
        $totalRealisasi = $items->sum('total_realisasi');

        if ($selectedKategori === 'produktivitas') {
            $avgTarget = $items->count() > 0 ? round($items->avg('target_total'), 2) : 0;
            $avgSub1 = $items->count() > 0 ? round($items->avg('jumlah_mh'), 2) : 0;
            $avgSub2 = $items->count() > 0 ? round($items->avg('jumlah_mk'), 2) : 0;
            $avgTotal = $items->count() > 0 ? round($items->avg('total_realisasi'), 2) : 0;

            $stats = [
                'card1_title' => 'Target Rata-rata',
                'card1_value' => $avgTarget . ' Kw/Ha',
                'card1_sub' => "Target Produktivitas {$selectedKomoditas}",
                'card1_icon' => 'bx-target-lock',
                'card1_badge' => 'bg-label-warning',

                'card2_title' => 'Rata-rata Jan-Jun',
                'card2_value' => $avgSub1 . ' Kw/Ha',
                'card2_sub' => 'Realisasi Semester 1',
                'card2_icon' => 'bx-calendar-alt',
                'card2_badge' => 'bg-label-primary',

                'card3_title' => 'Rata-rata Jul-Des',
                'card3_value' => $avgSub2 . ' Kw/Ha',
                'card3_sub' => 'Realisasi Semester 2',
                'card3_icon' => 'bx-calendar',
                'card3_badge' => 'bg-label-info',

                'card4_title' => 'Rata-rata Total',
                'card4_value' => $avgTotal . ' Kw/Ha',
                'card4_sub' => "Capaian Produktivitas Tahun {$selectedTahun}",
                'card4_icon' => 'bx-line-chart',
                'card4_badge' => 'bg-label-success',
            ];
        } elseif ($selectedKategori === 'puso') {
            $kecamatanTerdampak = $items->filter(fn($x) => $x->total_realisasi > 0)->count();

            $stats = [
                'card1_title' => 'Puso Jan-Jun',
                'card1_value' => number_format($totalRealisasiSub1, 1) . ' Ha',
                'card1_sub' => 'Kejadian Semester 1',
                'card1_icon' => 'bx-error-alt',
                'card1_badge' => 'bg-label-warning',

                'card2_title' => 'Puso Jul-Des',
                'card2_value' => number_format($totalRealisasiSub2, 1) . ' Ha',
                'card2_sub' => 'Kejadian Semester 2',
                'card2_icon' => 'bx-error',
                'card2_badge' => 'bg-label-danger',

                'card3_title' => 'Total Luas Puso',
                'card3_value' => number_format($totalRealisasi, 1) . ' Ha',
                'card3_sub' => "Akumulasi Tahun {$selectedTahun}",
                'card3_icon' => 'bx-shield-x',
                'card3_badge' => 'bg-label-danger',

                'card4_title' => 'Kecamatan Terdampak',
                'card4_value' => $kecamatanTerdampak . ' / ' . $items->count(),
                'card4_sub' => 'Kecamatan Terkena Puso',
                'card4_icon' => 'bx-map-pin',
                'card4_badge' => 'bg-label-info',
            ];
        } else {
            // Tanam, Panen, Produksi
            $persentase = $totalTarget > 0 ? round(($totalRealisasi / $totalTarget) * 100, 2) : 0;
            $unit = $activeConfig['satuan'];

            $stats = [
                'card1_title' => ($selectedKategori === 'tanam' ? 'Luas Baku Sawah' : 'Total Baku Lahan'),
                'card1_value' => number_format($totalSawah, 0) . ' Ha',
                'card1_sub' => "Total 32 Kecamatan ({$selectedTahun})",
                'card1_icon' => 'bx-map-alt',
                'card1_badge' => 'bg-label-primary',

                'card2_title' => 'Total Target',
                'card2_value' => number_format($totalTarget, 0) . ' ' . $unit,
                'card2_sub' => "Target {$activeConfig['label']} ({$selectedTahun})",
                'card2_icon' => 'bx-target-lock',
                'card2_badge' => 'bg-label-warning',

                'card3_title' => 'Total Realisasi',
                'card3_value' => number_format($totalRealisasi, 0) . ' ' . $unit,
                'card3_sub' => ($selectedKategori === 'tanam' ? 'Realisasi MH + MK' : 'Realisasi Sem 1 + Sem 2'),
                'card3_icon' => 'bx-check-circle',
                'card3_badge' => 'bg-label-success',

                'card4_title' => 'Persentase Capaian',
                'card4_value' => $persentase . '%',
                'card4_sub' => 'Capaian Terhadap Target',
                'card4_icon' => 'bx-trending-up',
                'card4_badge' => 'bg-label-info',
            ];
        }

        $data = [
            'head' => 'Padi & Palawija',
            'title' => 'Padi & Palawija',
            'menu' => 'Padi & Palawija',
            'komoditasList' => $komoditasList,
            'selectedKomoditas' => $selectedKomoditas,
            'kategoriConfig' => $kategoriConfig,
            'selectedKategori' => $selectedKategori,
            'activeConfig' => $activeConfig,
            'availableYears' => $availableYears,
            'selectedTahun' => $selectedTahun,
            'items' => $items,
            'stats' => $stats,
        ];

        return view('admin.padi_palawija.index', $data);
    }

    /**
     * Export data Padi & Palawija per tahun ke file Excel (XLSX 7 Sheet Lengkap)
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

            $detailMsg = "Berhasil mengimpor {$result['totalRows']} data Tahun {$tahun} (" . implode(', ', $result['sheets']) . ").";

            return redirect()
                ->route('admin-padi-palawija.index', ['tahun' => $tahun])
                ->with('success', $detailMsg);

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
            'kategori'  => 'required|string',
            'kecamatan' => 'required|string',
            'tahun'     => 'required|numeric',
        ]);

        $komoditas = $antiXss->xss_clean($request->komoditas);
        $kategori = $antiXss->xss_clean($request->kategori);
        $kecamatan = strtoupper($antiXss->xss_clean($request->kecamatan));
        $tahun = (string) $request->tahun;
        $luasSawah = (float) $request->luas_sawah;

        $tSub1 = (float) $request->target_okmar;
        $tSub2 = (float) $request->target_asep;
        $tTotal = (float) ($request->target_total ?: ($tSub1 + $tSub2));

        $rJan = (float) $request->realisasi_jan;
        $rFeb = (float) $request->realisasi_feb;
        $rMar = (float) $request->realisasi_mar;
        $rApr = (float) $request->realisasi_apr;
        $rMei = (float) $request->realisasi_mei;
        $rJun = (float) $request->realisasi_jun;
        $rJul = (float) $request->realisasi_jul;
        $rAgs = (float) $request->realisasi_ags;
        $rSep = (float) $request->realisasi_sep;
        $rOkt = (float) $request->realisasi_okt;
        $rNop = (float) $request->realisasi_nop;
        $rDes = (float) $request->realisasi_des;

        if ($kategori === 'tanam') {
            // MH = Okt + Nop + Des + Jan + Feb + Mar
            $rSub1 = (float) ($request->jumlah_mh ?: ($rOkt + $rNop + $rDes + $rJan + $rFeb + $rMar));
            // MK = Apr + Mei + Jun + Jul + Ags + Sep
            $rSub2 = (float) ($request->jumlah_mk ?: ($rApr + $rMei + $rJun + $rJul + $rAgs + $rSep));
        } else {
            // Sem 1 = Jan + Feb + Mar + Apr + Mei + Jun
            $rSub1 = (float) ($request->jumlah_mh ?: ($rJan + $rFeb + $rMar + $rApr + $rMei + $rJun));
            // Sem 2 = Jul + Ags + Sep + Okt + Nop + Des
            $rSub2 = (float) ($request->jumlah_mk ?: ($rJul + $rAgs + $rSep + $rOkt + $rNop + $rDes));
        }

        $rTotal = (float) ($request->total_realisasi ?: ($rSub1 + $rSub2));
        $pSub = $tSub1 > 0 ? round(($rSub1 / $tSub1) * 100, 2) : 0;
        $pTotal = $tTotal > 0 ? round(($rTotal / $tTotal) * 100, 2) : 0;

        $lastNo = PadiPalawija::where('komoditas', $komoditas)
            ->where('kategori', $kategori)
            ->where('tahun', $tahun)
            ->max('no_urut') ?: 0;

        $prevYear = (int)$tahun - 1;
        $musimTanam = $kategori === 'tanam' ? "MT {$prevYear}/{$tahun} dan MT {$tahun}" : "Tahun {$tahun}";
        $satuan = match($kategori) {
            'tanam', 'panen', 'puso' => 'Ha',
            'produksi' => 'Ton',
            'produktivitas' => 'Kw/Ha',
            default => 'Ha'
        };

        PadiPalawija::create([
            'komoditas'          => $komoditas,
            'kategori'           => $kategori,
            'satuan'             => $satuan,
            'tahun'              => $tahun,
            'musim_tanam'        => $musimTanam,
            'no_urut'            => $lastNo + 1,
            'kecamatan'          => $kecamatan,
            'luas_sawah'         => $luasSawah,
            'target_okmar'       => $tSub1,
            'target_asep'        => $tSub2,
            'target_total'       => $tTotal,
            'realisasi_okt'      => $rOkt,
            'realisasi_nop'      => $rNop,
            'realisasi_des'      => $rDes,
            'realisasi_jan'      => $rJan,
            'realisasi_feb'      => $rFeb,
            'realisasi_mar'      => $rMar,
            'jumlah_mh'          => $rSub1,
            'realisasi_apr'      => $rApr,
            'realisasi_mei'      => $rMei,
            'realisasi_jun'      => $rJun,
            'realisasi_jul'      => $rJul,
            'realisasi_ags'      => $rAgs,
            'realisasi_sep'      => $rSep,
            'jumlah_mk'          => $rSub2,
            'total_realisasi'    => $rTotal,
            'persentase_sub'     => $pSub,
            'persentase_capaian' => $pTotal,
        ]);

        return redirect()
            ->route('admin-padi-palawija.index', [
                'komoditas' => $komoditas,
                'kategori' => $kategori,
                'tahun' => $tahun
            ])
            ->with('success', "Data {$komoditas} ({$kategori}) Kecamatan {$kecamatan} Tahun {$tahun} berhasil ditambahkan");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkAdmin();
        $antiXss = new AntiXSS();

        $item = PadiPalawija::findOrFail($id);
        $kategori = $item->kategori ?: 'tanam';

        $kecamatan = strtoupper($antiXss->xss_clean($request->kecamatan_edit ?: $item->kecamatan));
        $luasSawah = (float) ($request->luas_sawah_edit ?? $item->luas_sawah);

        $tSub1 = (float) ($request->target_okmar_edit ?? $item->target_okmar);
        $tSub2 = (float) ($request->target_asep_edit ?? $item->target_asep);
        $tTotal = (float) ($request->target_total_edit ?: ($tSub1 + $tSub2));

        $rJan = (float) ($request->realisasi_jan_edit ?? $item->realisasi_jan);
        $rFeb = (float) ($request->realisasi_feb_edit ?? $item->realisasi_feb);
        $rMar = (float) ($request->realisasi_mar_edit ?? $item->realisasi_mar);
        $rApr = (float) ($request->realisasi_apr_edit ?? $item->realisasi_apr);
        $rMei = (float) ($request->realisasi_mei_edit ?? $item->realisasi_mei);
        $rJun = (float) ($request->realisasi_jun_edit ?? $item->realisasi_jun);
        $rJul = (float) ($request->realisasi_jul_edit ?? $item->realisasi_jul);
        $rAgs = (float) ($request->realisasi_ags_edit ?? $item->realisasi_ags);
        $rSep = (float) ($request->realisasi_sep_edit ?? $item->realisasi_sep);
        $rOkt = (float) ($request->realisasi_okt_edit ?? $item->realisasi_okt);
        $rNop = (float) ($request->realisasi_nop_edit ?? $item->realisasi_nop);
        $rDes = (float) ($request->realisasi_des_edit ?? $item->realisasi_des);

        if ($kategori === 'tanam') {
            $rSub1 = (float) ($rOkt + $rNop + $rDes + $rJan + $rFeb + $rMar);
            $rSub2 = (float) ($rApr + $rMei + $rJun + $rJul + $rAgs + $rSep);
        } else {
            $rSub1 = (float) ($rJan + $rFeb + $rMar + $rApr + $rMei + $rJun);
            $rSub2 = (float) ($rJul + $rAgs + $rSep + $rOkt + $rNop + $rDes);
        }

        if ($kategori === 'produktivitas') {
            $rTotal = (float) ($request->total_realisasi_edit ?? $item->total_realisasi);
        } else {
            $rTotal = (float) ($rSub1 + $rSub2);
        }

        $pSub = $tSub1 > 0 ? round(($rSub1 / $tSub1) * 100, 2) : 0;
        $pTotal = $tTotal > 0 ? round(($rTotal / $tTotal) * 100, 2) : 0;

        $item->update([
            'kecamatan'          => $kecamatan,
            'luas_sawah'         => $luasSawah,
            'target_okmar'       => $tSub1,
            'target_asep'        => $tSub2,
            'target_total'       => $tTotal,
            'realisasi_okt'      => $rOkt,
            'realisasi_nop'      => $rNop,
            'realisasi_des'      => $rDes,
            'realisasi_jan'      => $rJan,
            'realisasi_feb'      => $rFeb,
            'realisasi_mar'      => $rMar,
            'jumlah_mh'          => $rSub1,
            'realisasi_apr'      => $rApr,
            'realisasi_mei'      => $rMei,
            'realisasi_jun'      => $rJun,
            'realisasi_jul'      => $rJul,
            'realisasi_ags'      => $rAgs,
            'realisasi_sep'      => $rSep,
            'jumlah_mk'          => $rSub2,
            'total_realisasi'    => $rTotal,
            'persentase_sub'     => $pSub,
            'persentase_capaian' => $pTotal,
        ]);

        return redirect()
            ->route('admin-padi-palawija.index', [
                'komoditas' => $item->komoditas,
                'kategori' => $item->kategori,
                'tahun' => $item->tahun
            ])
            ->with('success', "Data {$item->komoditas} ({$item->kategori}) Kecamatan {$kecamatan} Tahun {$item->tahun} berhasil diubah");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->checkAdmin();
        $item = PadiPalawija::findOrFail($id);
        $komoditas = $item->komoditas;
        $kategori = $item->kategori;
        $kecamatan = $item->kecamatan;
        $tahun = $item->tahun;
        $item->delete();

        return redirect()
            ->route('admin-padi-palawija.index', [
                'komoditas' => $komoditas,
                'kategori' => $kategori,
                'tahun' => $tahun
            ])
            ->with('success', "Data {$komoditas} ({$kategori}) Kecamatan {$kecamatan} Tahun {$tahun} berhasil dihapus");
    }
}
