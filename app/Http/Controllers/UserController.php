<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use voku\helper\AntiXSS;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::orderByDesc('created_at')->get();

        $data = array(
            'head' => "User",
            'title' => "User",
            'menu' => "User",
            'user' => $user
        );

        return view('admin.user.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = array(
            'head' => "User",
            'title' => "User",
            'menu' => "Tambah User"
        );

        return view('admin.user.add')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $antiXss = new AntiXSS();

        User::create([
            'name' => $antiXss->xss_clean($request->name),
            'email' => $antiXss->xss_clean($request->email),
            'username' => $antiXss->xss_clean($request->username),
            'password' => Hash::make($antiXss->xss_clean($request->password)),
            'bidang' => $antiXss->xss_clean($request->bidang),
        ]);

        return redirect()
            ->route('admin-user.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    public function cekUsername(Request $request)
    {
        $id = $request->id;
        $username = $request->username;

        $exists = User::where('username', $username)
            ->when($id, function ($q) use ($id) {
                $q->where('id', '!=', $id);
            })
            ->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        $data = array(
            'head' => "User",
            'title' => "User",
            'menu' => "Edit User",
            'user' => $user
        );

        return view('admin.user.edit')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $antiXss = new AntiXSS();
        $user = User::findOrFail($id);
        
        $data = [
            'name' => $antiXss->xss_clean($request->name),
            'email' => $antiXss->xss_clean($request->email),
            'username' => $antiXss->xss_clean($request->username),
            'bidang' => $antiXss->xss_clean($request->bidang),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make(
                $antiXss->xss_clean($request->password)
            );
        }

        $user->update($data);

        return redirect()
            ->route('admin-user.index')
            ->with('success', 'User berhasil dirubah');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = User::findOrFail($id);

        $data->delete();

        return response()->json([
            'message' => 'User berhasil dihapus'
        ]);
    }
}
