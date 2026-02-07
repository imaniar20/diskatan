<?php

namespace App\Http\Controllers;

use App\Models\KetahananPangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KetahananPanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = KetahananPangan::orderBy('created_at', 'desc')->get();
        return view('ketahanan-pangan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statusList = KetahananPangan::getAllStatus();
        return view('ketahanan-pangan.create', compact('statusList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_desa' => 'required|string|max:255',
            'status_ketahanan' => 'required|in:Sangat Tahan,Tahan,Agak Rawan,Rawan,Sangat Rawan',
            'jumlah_kk' => 'required|integer|min:0',
            'tahun' => 'required|integer|min:2000|max:2100',
            'keterangan' => 'nullable|string'
        ]);

        KetahananPangan::create($validated);

        return redirect()->route('ketahanan-pangan.index')
            ->with('success', 'Data ketahanan pangan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(KetahananPangan $ketahananPangan)
    {
        return view('ketahanan-pangan.show', compact('ketahananPangan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KetahananPangan $ketahananPangan)
    {
        $statusList = KetahananPangan::getAllStatus();
        return view('ketahanan-pangan.edit', compact('ketahananPangan', 'statusList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KetahananPangan $ketahananPangan)
    {
        $validated = $request->validate([
            'nama_desa' => 'required|string|max:255',
            'status_ketahanan' => 'required|in:Sangat Tahan,Tahan,Agak Rawan,Rawan,Sangat Rawan',
            'jumlah_kk' => 'required|integer|min:0',
            'tahun' => 'required|integer|min:2000|max:2100',
            'keterangan' => 'nullable|string'
        ]);

        $ketahananPangan->update($validated);

        return redirect()->route('ketahanan-pangan.index')
            ->with('success', 'Data ketahanan pangan berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KetahananPangan $ketahananPangan)
    {
        $ketahananPangan->delete();

        return redirect()->route('ketahanan-pangan.index')
            ->with('success', 'Data ketahanan pangan berhasil dihapus!');
    }

    /**
     * Get chart data for visualization
     */
    public function chartData(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));

        // Hitung persentase per status
        $total = KetahananPangan::where('tahun', $tahun)->count();
        
        if ($total == 0) {
            return response()->json([
                'labels' => [],
                'series' => [],
                'total' => 0
            ]);
        }

        $data = KetahananPangan::select('status_ketahanan', DB::raw('COUNT(*) as jumlah'))
            ->where('tahun', $tahun)
            ->groupBy('status_ketahanan')
            ->get();

        $chartData = [];
        $allStatus = KetahananPangan::getAllStatus();

        foreach ($allStatus as $status) {
            $item = $data->firstWhere('status_ketahanan', $status);
            $jumlah = $item ? $item->jumlah : 0;
            $persentase = $total > 0 ? round(($jumlah / $total) * 100, 1) : 0;
            
            $chartData[] = [
                'status' => $status,
                'jumlah' => $jumlah,
                'persentase' => $persentase
            ];
        }

        return response()->json([
            'data' => $chartData,
            'total' => $total,
            'tahun' => $tahun
        ]);
    }

    /**
     * Display dashboard with charts
     */
    public function dashboard()
    {
        $tahunList = KetahananPangan::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('ketahanan-pangan.dashboard', compact('tahunList'));
    }
}