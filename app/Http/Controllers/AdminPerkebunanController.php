<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perkebunan;
use App\Models\Komoditas;
use App\Services\PerkebunanExcelService;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminPerkebunanController extends Controller
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

        $komoditasList = Komoditas::getListBySektor('perkebunan');

        $selectedKomoditas = $request->query('komoditas', 'Kopi');
        if (!in_array($selectedKomoditas, $komoditasList)) {
            $selectedKomoditas = 'Kopi';
        }

        $query = Perkebunan::where('komoditas', $selectedKomoditas);

        if ($request->has('search') && !empty($request->search)) {
            $query->where('tahun', 'like', '%' . $request->search . '%');
        }

        $items = $query->orderBy('tahun', 'desc')->get();

        $totalTanam = $items->sum('luas_tanam');
        $totalPanen = $items->sum('luas_panen');
        $totalProduksi = $items->sum('produksi');
        $avgProduktivitas = $items->count() > 0 ? round($items->avg('produktivitas'), 2) : 0;

        $chartItems = $items->sortBy('tahun')->values();
        $chartCategories = $chartItems->pluck('tahun')->toArray();
        $chartTanam = $chartItems->pluck('luas_tanam')->toArray();
        $chartPanen = $chartItems->pluck('luas_panen')->toArray();
        $chartProduksi = $chartItems->pluck('produksi')->toArray();

        $availableYears = Perkebunan::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        $data = [
            'head' => 'Data Tahunan Perkebunan',
            'title' => 'Perkebunan Tahunan',
            'menu' => 'Perkebunan Tahunan',
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

        return view('admin.perkebunan_tahunan.index', $data);
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'komoditas'       => 'required|string',
            'tahun'           => 'required|string|max:10',
            'luas_tanam'      => 'nullable|numeric|min:0',
            'luas_panen'      => 'nullable|numeric|min:0',
            'produktivitas'   => 'nullable|numeric|min:0',
            'produksi'        => 'nullable|numeric|min:0',
            'satuan_luas'     => 'nullable|string',
            'satuan_produksi' => 'nullable|string',
        ]);

        $exists = Perkebunan::where('komoditas', $validated['komoditas'])
            ->where('tahun', $validated['tahun'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', "Data komoditas {$validated['komoditas']} tahun {$validated['tahun']} sudah ada.");
        }

        Perkebunan::create($validated);

        Komoditas::firstOrCreateBySektor('perkebunan', $validated['komoditas'], [
            'satuan_luas' => $validated['satuan_luas'] ?? 'Ha',
            'satuan_produksi' => $validated['satuan_produksi'] ?? 'Ton',
        ]);

        return redirect()->back()->with('success', 'Data Perkebunan Tahunan berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $this->checkAdmin();
        $item = Perkebunan::findOrFail($id);

        $validated = $request->validate([
            'luas_tanam'      => 'nullable|numeric|min:0',
            'luas_panen'      => 'nullable|numeric|min:0',
            'produktivitas'   => 'nullable|numeric|min:0',
            'produksi'        => 'nullable|numeric|min:0',
            'satuan_luas'     => 'nullable|string',
            'satuan_produksi' => 'nullable|string',
        ]);

        $item->update($validated);

        return redirect()->back()->with('success', 'Data Perkebunan Tahunan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->checkAdmin();
        $item = Perkebunan::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Data Perkebunan Tahunan berhasil dihapus.');
    }

    public function export(Request $request, PerkebunanExcelService $excelService): StreamedResponse
    {
        $this->checkAdmin();
        $tahun = $request->query('tahun', 'ALL');
        $spreadsheet = $excelService->export($tahun);

        $filename = "Data_Tahunan_Perkebunan_Kuningan.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function import(Request $request, PerkebunanExcelService $excelService)
    {
        $this->checkAdmin();
        $request->validate([
            'file_excel' => 'required|file|mimes:xls,xlsx|max:15360',
        ]);

        try {
            $result = $excelService->import($request->file('file_excel'));
            return redirect()->back()->with('success', $result['message']);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal impor data: ' . $e->getMessage());
        }
    }
}
