<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckLogin;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
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

//public
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
//end

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::middleware([CheckLogin::class])->group(function () {
        Route::get('/admin-dashboard', function () {
            $data = array(
                'head' => "Dashboard",
                'title' => "Dashboard",
                'menu' => "Dashboard"
            );

            return view('admin.home.index')->with($data);
        });
    });
    Route::resource('/admin-berita', NewsController::class);
});