<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use voku\helper\AntiXSS;
use App\Models\Programs;
use App\Models\Kategori;

class ProgramsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $program = Programs::orderByDesc('created_at')->get();

        $data = array(
            'head' => "Program",
            'title' => "Program",
            'menu' => "Program & Kegiatan",
            'program' => $program
        );

        return view('admin.program.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::orderByDesc('created_at')->get();

        $data = array(
            'head' => "Program",
            'title' => "Program",
            'menu' => "Tambah Program & Kegiatan",
            'kategori' => $kategori
        );

        return view('admin.program.add')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $antiXss = new AntiXSS();

        Programs::create([
            'kategori_id' => $antiXss->xss_clean($request->kategori),
            'name' => $antiXss->xss_clean($request->name),
            'status' => $antiXss->xss_clean($request->status),
            'tahun' => $antiXss->xss_clean($request->tahun)
        ]);

        return redirect()
            ->route('admin-program.index')
            ->with('success', 'Program berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $program = Programs::findOrFail($id);
        $kategori = Kategori::orderByDesc('created_at')->get();
        // dd($program);
        $data = array( 
            'head' => "Program",
            'title' => "Program",
            'menu' => "Edit Program & Kegiatan",
            'program' => $program,
            'kategori' => $kategori
        );

        return view('admin.program.edit')->with($data);
    }

    public function update(Request $request, string $id)
    {
        $antiXss = new AntiXSS();
        $program = Programs::findOrFail($id);
        // dd('test');
        $data = [
            'kategori_id' => $antiXss->xss_clean($request->kategori),
            'name' => $antiXss->xss_clean($request->name),
            'status' => $antiXss->xss_clean($request->status),
            'tahun' => $antiXss->xss_clean($request->tahun)
        ];

        $program->update($data);

        return redirect()
            ->route('admin-program.index')
            ->with('success', 'Program berhasil dirubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Programs::findOrFail($id);

        $data->delete();

        return response()->json([
            'message' => 'Program berhasil dihapus'
        ]);
    }
}
