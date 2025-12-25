<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'rating'     => 'required|integer|min:1|max:5',
            'suggestion' => 'nullable|string|max:1000',
        ]);

        Survey::create([
            'rating'     => $request->rating,
            'suggestion' => $request->suggestion,
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'status'  => true
        ]);
    }
}
