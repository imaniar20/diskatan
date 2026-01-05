<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboards;

class DashboardsController extends Controller
{
    public function index()
    {
        $datas = Dashboards::first();

        $data = array(
            'head' => "Data",
            'title' => "Data",
            'menu' => "Data",
            'data' => $datas
        );

        return view('admin.data.index')->with($data);
    }

    public function update(Request $request)
    {
        $request->validate([
            'hektar_luas_tanam' => 'required|integer|min:0',
            'ton_produksi' => 'required|integer|min:0',
            'kelompok_tani' => 'required|integer|min:0',
            'indeks_ketahanan_pangan' => 'required|numeric|min:0',

            'ucapan' => 'nullable|string',
            'alamat' => 'nullable|string',
            'telphone' => 'nullable|string|max:20',
            'jam_operasional' => 'nullable|string',

            'youtube' => 'nullable|string',
            'instagram' => 'nullable|string',
            'tiktok' => 'nullable|string',
            'facebook' => 'nullable|string',
        ]);

        // karena dashboard cuma 1 row
        $dashboard = Dashboards::first();

        // kalau belum ada datanya, bikin dulu
        if (!$dashboard) {
            $dashboard = Dashboards::create($request->all());
        } else {
            $dashboard->update($request->all());
        }

        return redirect()->back()->with('success', 'Dashboard berhasil diperbarui');
    }
}
