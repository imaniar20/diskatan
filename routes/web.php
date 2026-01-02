<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckLogin;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AgendasController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BidangsController;
use App\Http\Controllers\MessageController;

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

Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');

Route::post('/contact', [MessageController::class, 'store'])
    ->name('contact.store');

// profil
Route::get('/visi-misi', [HomeController::class, 'visiMisi']);
Route::get('/sejarah', [HomeController::class, 'sejarah']);
Route::get('/struktur', [HomeController::class, 'struktur']);
Route::get('/tupoksi', [HomeController::class, 'tupoksi']);
Route::get('/daftar-pejabat', [HomeController::class, 'daftarPejabat']);

// agenda
Route::get('/agenda', [HomeController::class, 'agenda']);
Route::get('/agenda/{slug}', [HomeController::class, 'agendaDetail'])->name('agenda.detail');
Route::post('/agenda/cari', [HomeController::class, 'filterAgendas'])->name('agenda.filter');

// berita
Route::get('/berita', [HomeController::class, 'berita']);
Route::get('/berita/{slug}', [HomeController::class, 'beritaDetail'])->name('berita.detail');
Route::post('/berita/cari', [HomeController::class, 'filterNews'])->name('berita.filter');

//progaram
Route::get('/program', [HomeController::class, 'program']);
Route::get('/news/kategori/{id}', [HomeController::class, 'byKategori']);

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

    Route::resource('/admin-program', ProgramsController::class);

    Route::resource('/admin-kategori', KategoriController::class);

    Route::resource('/admin-bidang', BidangsController::class);

    Route::resource('/admin-user', UserController::class);
    Route::post('/cek-username', [UserController::class, 'cekUsername'])->name('cek.username');

    Route::post('/admin/upload-image', [ImageController::class, 'upload'])
        ->middleware('web')
        ->name('admin.upload-image');

    Route::get('/download/{filename}', [ImageController::class, 'download'])
        ->where('filename', '.*')
        ->name('download.file');

});