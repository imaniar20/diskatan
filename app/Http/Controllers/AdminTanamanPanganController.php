<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TanamanPangan;
use App\Services\TanamanPanganExcelService;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use voku\helper\AntiXSS;

class AdminTanamanPanganController extends Controller
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

        $selectedKomoditas = $request->query('komoditas', 'Padi');
        if (!in_array($selectedKomoditas, $komoditasList)) {
            $selectedKomoditas = 'Padi';
        }

        $query = TanamanPangan::where('komoditas', $selectedKomoditas);

        if ($request->has('search') && !empty($request->search)) {
            $query->where('tahun', 'like', '%' . $request->search . '%');
        }

        $items = $query->orderBy('tahun', 'desc')->get();

        // Statistik Kumulatif Komoditas
        $totalTanam = $items->sum('luas_tanam');
        $totalPanen = $items->sum('luas_panen');
        $totalProduksi = $items->sum('produksi');
        $avgProduktivitas = $items->count() > 0 ? round($items->avg('produktivitas'), 2) : 0;

        // Data Grafik Tren Tahunan
        $chartItems = $items->sortBy('tahun')->values();
        $chartCategories = $chartItems->pluck('tahun')->toArray();
        $chartTanam = $chartItems->pluck('luas_tanam')->toArray();
        $chartPanen = $chartItems->pluck('luas_panen')->toArray();
        $chartProduksi = $chartItems->pluck('produksi')->toArray();

        // Daftar tahun
        $availableYears = TanamanPangan::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        $data = [
            'head' => 'Tanaman Pangan',
            'title' => 'Tanaman Pangan',
            'menu' => 'Tanaman Pangan',
            'komoditasList' => $komoditasList,
            'selectedKomoditas' => $selectedKomoditas,
            'availableYears' => $availableYears,
            'items' => $items,
            'stats' => [
                'totalTanam'       => $totalTanam,
                'totalPanen'       => $totalPanen,
                'totalProduksi'    => $totalProduksi,
                'avgProduktivitas' => $avgProduktivitas,
            ],
            'chart' => [
                'categories' => $chartCategories,
                'tanam'      => $chartTanam,
                'panen'      => $chartPanen,
                'produksi'   => $chartProduksi,
            ],
        ];

        return view('admin.tanaman_pangan.index', $data);
    }

    /**
     * Export data tanaman pangan ke file Excel (XLSX multi-sheet)
     */
    public function export(Request $request, TanamanPanganExcelService $excelService): StreamedResponse
    {
        $this->checkAdmin();

        $tahun = $request->query('tahun', 'ALL');
        $spreadsheet = $excelService->export($tahun);

        $filename = "Data_Tanaman_Pangan_DKPP_Kuningan.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Import file Excel Tanaman Pangan dan replace data
     */
    public function import(Request $request, TanamanPanganExcelService $excelService)
    {
        $this->checkAdmin();

        $request->validate([
            'file_excel' => 'required|file|mimes:xls,xlsx|max:15360',
        ], [
            'file_excel.required' => 'File Excel wajib diunggah.',
            'file_excel.mimes'    => 'Format file harus berupa .xls atau .xlsx.',
            'file_excel.max'      => 'Ukuran file maksimal adalah 15MB.',
        ]);

        $targetTahun = $request->input('tahun', 'ALL');
        $file = $request->file('file_excel');

        try {
            $result = $excelService->import($file, $targetTahun);

            if (!$result['success']) {
                return redirect()
                    ->route('admin-tanaman-pangan.index')
                    ->with('error', $result['message']);
            }

            return redirect()
                ->route('admin-tanaman-pangan.index')
                ->with('success', "{$result['message']} (Total: {$result['totalRows']} data berhasil disimpan).");

        } catch (\Throwable $th) {
            return redirect()
                ->route('admin-tanaman-pangan.index')
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
            'tahun'     => 'required|numeric|min:2000|max:2060',
        ]);

        $komoditas = $antiXss->xss_clean($request->komoditas);
        $tahun = (string) (int)$request->tahun;

        $exists = TanamanPangan::where('komoditas', $komoditas)->where('tahun', $tahun)->exists();
        if ($exists) {
            return redirect()
                ->route('admin-tanaman-pangan.index', ['komoditas' => $komoditas])
                ->with('error', "Data {$komoditas} Tahun {$tahun} sudah ada. Silakan gunakan fitur Edit.");
        }

        $luasTanam = (float) $request->luas_tanam;
        $luasPanen = (float) $request->luas_panen;
        $produksi  = (float) $request->produksi;
        $produktivitas = (float) $request->produktivitas;
        if ($produktivitas == 0 && $luasPanen > 0 && $produksi > 0) {
            $produktivitas = round(($produksi / $luasPanen) * 10, 2);
        }

        TanamanPangan::create([
            'komoditas'       => $komoditas,
            'tahun'           => $tahun,
            'luas_tanam'      => $luasTanam,
            'luas_panen'      => $luasPanen,
            'produktivitas'   => $produktivitas,
            'produksi'        => $produksi,
            'satuan_luas'     => 'Ha',
            'satuan_produksi' => 'Ton',
        ]);

        return redirect()
            ->route('admin-tanaman-pangan.index', ['komoditas' => $komoditas])
            ->with('success', "Data {$komoditas} Tahun {$tahun} berhasil ditambahkan");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkAdmin();
        $item = TanamanPangan::findOrFail($id);

        $luasTanam = (float) ($request->luas_tanam_edit ?? $item->luas_tanam);
        $luasPanen = (float) ($request->luas_panen_edit ?? $item->luas_panen);
        $produksi  = (float) ($request->produksi_edit ?? $item->produksi);
        $produktivitas = (float) ($request->produktivitas_edit ?? $item->produktivitas);

        if ($produktivitas == 0 && $luasPanen > 0 && $produksi > 0) {
            $produktivitas = round(($produksi / $luasPanen) * 10, 2);
        }

        $item->update([
            'luas_tanam'    => $luasTanam,
            'luas_panen'    => $luasPanen,
            'produktivitas' => $produktivitas,
            'produksi'      => $produksi,
        ]);

        return redirect()
            ->route('admin-tanaman-pangan.index', ['komoditas' => $item->komoditas])
            ->with('success', "Data {$item->komoditas} Tahun {$item->tahun} berhasil diperbarui");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->checkAdmin();
        $item = TanamanPangan::findOrFail($id);
        $komoditas = $item->komoditas;
        $tahun = $item->tahun;
        $item->delete();

        return redirect()
            ->route('admin-tanaman-pangan.index', ['komoditas' => $komoditas])
            ->with('success', "Data {$komoditas} Tahun {$tahun} berhasil dihapus");
    }
}
