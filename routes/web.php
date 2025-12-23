<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckLogin;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AgendasController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ImageController;

use App\Models\Agendas;
use App\Models\News;

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
    $data = array(
        'head' => "Profil",
        'title' => "Profil",
        'menu' => "Visi & Misi",
    );

    return view('public.profil.visimisi')->with($data);
});

Route::get('/sejarah', function () {
    $data = array(
        'head' => "Profil",
        'title' => "Profil",
        'menu' => "Sejarah",
    );

    return view('public.profil.sejarah')->with($data);
});

Route::get('/struktur', function () {
    $data = array(
        'head' => "Profil",
        'title' => "Profil",
        'menu' => "Struktur Organisasi",
    );

    return view('public.profil.struktur')->with($data);
});

Route::get('/tupoksi', function () {
    $data = array(
        'head' => "Profil",
        'title' => "Profil",
        'menu' => "Tugas Pokok dan Fungsi",
    );

    return view('public.profil.tupoksi')->with($data);
});

Route::get('/daftar-pejabat', function () {
    $data = array(
        'head' => "Profil",
        'title' => "Profil",
        'menu' => "Daftar Pejabat",
    );

    return view('public.profil.daftarPejabat')->with($data);
});

Route::get('/agenda', function () {
    $agenda = Agendas::orderByDesc('date')->paginate(10);

    $data = array(
        'head' => "Agenda",
        'title' => "Agenda",
        'menu' => "Agenda",
        'agenda' => $agenda
    );

    return view('public.agenda.index')->with($data);
});

Route::get('/berita', function () {
    $news = News::orderByDesc('published_at')->paginate(9);

    $data = array(
        'head' => "Berita",
        'title' => "Berita",
        'menu' => "Berita",
        'news' => $news
    );

    return view('public.berita.index')->with($data);
});

Route::get('/berita/{slug}', function ($slug) {
    $news = News::where('slug', $slug)->firstOrFail();

    $data = array(
        'head' => "Berita",
        'title' => "Berita",
        'menu' => "Berita",
        'news' => $news
    );

    return view('public.berita.detail')->with($data);
})->name('berita.detail');
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

    Route::resource('/admin-agenda', AgendasController::class);

    Route::resource('/admin-berita', NewsController::class);

    Route::post('/admin/upload-image', [ImageController::class, 'upload'])
        ->middleware('web')
        ->name('admin.upload-image');

    Route::get('/download/{filename}', [ImageController::class, 'download'])
        ->where('filename', '.*')
        ->name('download.file');

});