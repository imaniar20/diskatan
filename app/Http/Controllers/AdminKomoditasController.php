<?php

namespace App\Http\Controllers;

use App\Models\Komoditas;
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

        $data = array(
            'head' => "Komoditas",
            'title' => "Komoditas",
            'menu' => "Komoditas",
            'komoditas' => $komoditas,
            'selectedSektor' => $selectedSektor,
            'search' => $search,
            'counts' => $counts,
        );

        $head = "Komoditas";
        $title = "Komoditas";
        $menu = "Komoditas";


        return view('admin.komoditas.index', compact('head', 'title', 'menu', 'komoditas', 'selectedSektor', 'search', 'counts'));
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

        return redirect()->route('admin.komoditas.index', ['sektor' => $komoditas->sektor])
            ->with('success', "Komoditas '{$komoditas->nama}' berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $this->checkAdmin();

        $komoditas = Komoditas::findOrFail($id);
        $nama = $komoditas->nama;
        $sektor = $komoditas->sektor;
        $komoditas->delete();

        return redirect()->route('admin.komoditas.index', ['sektor' => $sektor])
            ->with('success', "Komoditas '{$nama}' berhasil dihapus.");
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
