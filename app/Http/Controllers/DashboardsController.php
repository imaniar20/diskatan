<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use voku\helper\AntiXSS;

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
        $antiXss = new AntiXSS();

        $request->validate([
            'hektar_luas_tanam' => 'required|integer|min:0',
            'ton_produksi' => 'required|integer|min:0',
            'kelompok_tani' => 'required|integer|min:0',
            'indeks_ketahanan_pangan' => 'required|numeric|min:0',

            'nama_kadis' => 'nullable|string',
            // 'foto_kadis' => 'required|image|mimes:jpg,jpeg,png|max:2048',
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

        $thumbnailPath = $dashboard->foto_kadis;
        if ($request->hasFile('foto_kadis')) {
            $this->deleteFileIfExists($dashboard->foto_kadis);

            $file = $request->file('foto_kadis');
            $filename = Str::slug($request->nama_kadis) . '-' . time() . '.' . $file->getClientOriginalExtension();

            $thumbnailPath = $file->storeAs(
                'foto_kadis',
                $filename,
                'public'
            );
        }

        $dashboard->update([
            'hektar_luas_tanam' => $antiXss->xss_clean($request->hektar_luas_tanam),
            'ton_produksi' => $antiXss->xss_clean($request->ton_produksi),
            'kelompok_tani' => $antiXss->xss_clean($request->kelompok_tani),
            'indeks_ketahanan_pangan' => $antiXss->xss_clean($request->indeks_ketahanan_pangan),
            'nama_kadis' => $antiXss->xss_clean($request->nama_kadis),
            'foto_kadis' => $thumbnailPath,
            'ucapan' => $antiXss->xss_clean($request->ucapan),
            'alamat' => $antiXss->xss_clean($request->alamat),
            'telphone' => $antiXss->xss_clean($request->telphone),
            'jam_operasional' => $antiXss->xss_clean($request->jam_operasional),
            'youtube' => $antiXss->xss_clean($request->youtube),
            'instagram' => $antiXss->xss_clean($request->instagram),
            'tiktok' => $antiXss->xss_clean($request->tiktok),
            'facebook' => $antiXss->xss_clean($request->facebook),
        ]);
        

        return redirect()->back()->with('success', 'Dashboard berhasil diperbarui');
    }

    private function deleteFileIfExists($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return true;
        }
        return false;
    }
}
