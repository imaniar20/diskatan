<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;


class HomeController extends Controller
{
    public function index()
    {
        $news = News::orderByDesc('created_at')->take(3)->get();

        $data = array(
            'head' => "Home",
            'title' => "Home",
            'menu' => "Home",
            'news'  => $news
        );

        return view('public.home.index')->with($data);
    }
}