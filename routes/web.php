<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/visi-misi', function () {
    return view('public.profil.visimisi');
});

Route::get('/sejarah', function () {
    return view('public.profil.sejarah');
});

Route::get('/struktur', function () {
    return view('public.profil.struktur');
});

Route::get('/tupoksi', function () {
    return view('public.profil.tupoksi');
});

Route::get('/daftar-pejabat', function () {
    return view('public.profil.daftarPejabat');
});

Route::get('/agenda', function () {
    return view('public.agenda.index');
});

Route::get('/berita', function () {
    return view('public.berita.index');
});

Route::get('/berita-detail', function () {
    return view('public.berita.detail');
});