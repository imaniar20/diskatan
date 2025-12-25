<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:100',
            'phone'    => 'required|string|max:20',
            'category' => 'required|string|max:50',
            'message'  => 'required|string|max:2000',
        ]);

        Message::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'category'   => $request->category,
            'message'    => $request->message,
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Pesan Anda berhasil dikirim 🙏'
        ]);
    }
}
