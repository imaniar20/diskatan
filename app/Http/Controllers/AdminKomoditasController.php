<?php

namespace App\Http\Controllers;

use App\Models\Komoditas;
use App\Models\PadiPalawija;
use App\Models\TanamanPangan;
use App\Models\HortikulturaUtama;
use App\Models\Sayuran;
use App\Models\PerkebunanUtama;
use App\Models\Perkebunan;
use Illuminate\Http\Request;

class AdminKomoditasController extends Controller
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

        $selectedSektor = $request->get('sektor', 'all');
        $search = $request->get('search');

        $query = Komoditas::orderBy('sektor', 'asc')
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc');

        if ($selectedSektor !== 'all' && in_array($selectedSektor, array_keys(Komoditas::$sektorOptions))) {
            $query->where('sektor', $selectedSektor);
        }

        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $komoditas = $query->paginate(20)->appends($request->all());

        $counts = [
            'all' => Komoditas::count(),
            'tanaman_pangan' => Komoditas::where('sektor', 'tanaman_pangan')->count(),
            'hortikultura' => Komoditas::where('sektor', 'hortikultura')->count(),
            'perkebunan' => Komoditas::where('sektor', 'perkebunan')->count(),
        ];

        // Kumpulkan semua tahun yang tercatat pada seluruh tabel data pertanian
        $availableYears = collect()
            ->merge(PadiPalawija::select('tahun')->distinct()->pluck('tahun'))
            ->merge(TanamanPangan::select('tahun')->distinct()->pluck('tahun'))
            ->merge(HortikulturaUtama::select('tahun')->distinct()->pluck('tahun'))
            ->merge(Sayuran::select('tahun')->distinct()->pluck('tahun'))
            ->merge(PerkebunanUtama::select('tahun')->distinct()->pluck('tahun'))
            ->merge(Perkebunan::select('tahun')->distinct()->pluck('tahun'))
            ->filter()
            ->map(fn($y) => (string) $y)
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        $head = "Komoditas";
        $title = "Komoditas";
        $menu = "Komoditas";

        return view('admin.komoditas.index', compact(
            'head',
            'title',
            'menu',
            'komoditas',
            'selectedSektor',
            'search',
            'counts',
            'availableYears'
        ));
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'sektor' => 'required|in:tanaman_pangan,hortikultura,perkebunan',
            'nama' => 'required|string|max:100',
            'kategori_sayuran' => 'nullable|string|max:50',
            'satuan_luas' => 'nullable|string|max:20',
            'satuan_produksi' => 'nullable|string|max:20',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['satuan_luas'] = $validated['satuan_luas'] ?: 'Ha';
        $validated['satuan_produksi'] = $validated['satuan_produksi'] ?: 'Ton';
        $validated['urutan'] = $validated['urutan'] ?? 0;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Cek duplikasi sektor & nama
        $exists = Komoditas::where('sektor', $validated['sektor'])
            ->where('nama', $validated['nama'])
            ->exists();

        if ($exists) {
            return back()->with('error', "Komoditas '{$validated['nama']}' sudah terdaftar pada sektor ini.");
        }

        Komoditas::create($validated);

        return redirect()->route('admin.komoditas.index', ['sektor' => $validated['sektor']])
            ->with('success', "Komoditas '{$validated['nama']}' berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();

        $komoditas = Komoditas::findOrFail($id);
        $oldNama = $komoditas->nama;
        $oldSektor = $komoditas->sektor;

        $validated = $request->validate([
            'sektor' => 'required|in:tanaman_pangan,hortikultura,perkebunan',
            'nama' => 'required|string|max:100',
            'kategori_sayuran' => 'nullable|string|max:50',
            'satuan_luas' => 'nullable|string|max:20',
            'satuan_produksi' => 'nullable|string|max:20',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Cek duplikasi jika nama berubah
        $exists = Komoditas::where('sektor', $validated['sektor'])
            ->where('nama', $validated['nama'])
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->with('error', "Komoditas '{$validated['nama']}' sudah terdaftar pada sektor ini.");
        }

        $validated['satuan_luas'] = $validated['satuan_luas'] ?: 'Ha';
        $validated['satuan_produksi'] = $validated['satuan_produksi'] ?: 'Ton';
        $validated['urutan'] = $validated['urutan'] ?? 0;
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $komoditas->update($validated);

        if ($oldNama !== $komoditas->nama) {
            Komoditas::renameAgriculturalData($oldSektor, $oldNama, $komoditas->nama);
        }

        return redirect()->route('admin.komoditas.index', ['sektor' => $komoditas->sektor])
            ->with('success', "Komoditas '{$komoditas->nama}' berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $this->checkAdmin();

        $komoditas = Komoditas::findOrFail($id);
        $nama = $komoditas->nama;
        $sektor = $komoditas->sektor;

        // Hapus seluruh data pertanian terkait komoditas ini
        $deletedAgriculturalCount = $komoditas->deleteRelatedAgriculturalData();
        $komoditas->delete();

        $infoData = $deletedAgriculturalCount > 0
            ? " beserta {$deletedAgriculturalCount} data pertaniannya"
            : " (beserta data pertaniannya)";

        return redirect()->route('admin.komoditas.index', ['sektor' => $sektor])
            ->with('success', "Komoditas '{$nama}'{$infoData} berhasil dihapus.");
    }

    public function clearData(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'sektor' => 'required|in:all,tanaman_pangan,hortikultura,perkebunan',
            'tahun' => 'required|string',
        ], [
            'tahun.required' => 'Silakan pilih tahun data yang akan dihapus terlebih dahulu.',
        ]);

        $sektor = $request->input('sektor', 'all');
        $tahun = $request->input('tahun');
        $hapusMaster = $request->boolean('hapus_komoditas');

        $deletedRows = 0;

        $applyTahun = function ($query) use ($tahun) {
            if ($tahun !== 'ALL') {
                $query->where('tahun', $tahun);
            }
            return $query;
        };

        if ($sektor === 'all' || $sektor === 'tanaman_pangan') {
            $deletedRows += $applyTahun(PadiPalawija::query())->delete();
            $deletedRows += $applyTahun(TanamanPangan::query())->delete();
        }

        if ($sektor === 'all' || $sektor === 'hortikultura') {
            $deletedRows += $applyTahun(HortikulturaUtama::query())->delete();
            $deletedRows += $applyTahun(Sayuran::query())->delete();
        }

        if ($sektor === 'all' || $sektor === 'perkebunan') {
            $deletedRows += $applyTahun(PerkebunanUtama::query())->delete();
            $deletedRows += $applyTahun(Perkebunan::query())->delete();
        }

        $deletedKomoditasCount = 0;
        if ($hapusMaster && $tahun === 'ALL') {
            $kQuery = Komoditas::query();
            if ($sektor !== 'all') {
                $kQuery->where('sektor', $sektor);
            }
            $deletedKomoditasCount = $kQuery->delete();
        }

        $sektorLabel = match ($sektor) {
            'tanaman_pangan' => 'Tanaman Pangan',
            'hortikultura' => 'Hortikultura',
            'perkebunan' => 'Perkebunan',
            default => 'Semua Sektor',
        };
        $tahunLabel = $tahun === 'ALL' ? 'Semua Tahun' : "Tahun {$tahun}";

        $msg = "Berhasil membersihkan {$deletedRows} data pertanian pada {$sektorLabel} ({$tahunLabel}).";
        if ($deletedKomoditasCount > 0) {
            $msg .= " Serta menghapus {$deletedKomoditasCount} daftar Master Komoditas.";
        }

        return redirect()->route('admin.komoditas.index', ['sektor' => $sektor])
            ->with('success', $msg);
    }

    public function toggleStatus($id)
    {
        $this->checkAdmin();

        $komoditas = Komoditas::findOrFail($id);
        $komoditas->is_active = !$komoditas->is_active;
        $komoditas->save();

        $statusStr = $komoditas->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Komoditas '{$komoditas->nama}' berhasil {$statusStr}.");
    }
}
