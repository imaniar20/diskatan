<?php

use App\Models\Programs;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\CheckLogin;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AgendasController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\DashboardsController;
use App\Http\Controllers\ProgramsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BidangsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductionsController;

use App\Models\Agendas;
use App\Models\News;
use App\Models\User;
use App\Models\Bidangs;
use App\Models\Progams;
use App\Models\Kategori;

use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;


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
Route::get('/program/{slug}', [HomeController::class, 'program_selection']);

//data dan informasi
Route::get('/datadaninformasi', [HomeController::class, 'dataDanInformasi']);

//layanan
Route::get('/layanan', [HomeController::class, 'layanan']);

Route::get('/news/kategori/{id}', [HomeController::class, 'byKategori']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::middleware([CheckLogin::class])->group(function () {
        Route::get('/admin-dashboard', function () {
            $yearBefore = date('Y') - 1;
            $yearNow = date('Y');
            $years = [$yearBefore, $yearNow];

            //Agenda -------------------------------------------------------------------------------------------
            $chartAgenda = [];

            foreach ($years as $y) {
                $monthly = Agendas::selectRaw('MONTH(`date`) as month, COUNT(*) as total')
                    ->whereYear('date', $y)
                    ->groupByRaw('MONTH(`date`)')
                    ->orderByRaw('MONTH(`date`)')
                    ->get()
                    ->pluck('total', 'month'); // jadi key → month, value → total

                // handle bulan kosong → isi 0
                $chartAgenda[$y] = collect(range(1, 12))->map(function ($m) use ($monthly) {
                    return $monthly[$m] ?? 0;
                });
            }


            $agendaBefore = Agendas::whereYear('date', date('Y') - 1)->count();
            $agendaAfter = Agendas::whereYear('date', date('Y'))->count();

            if ($agendaBefore > 0) {
                $agendaGrowth = (($agendaAfter - $agendaBefore) / $agendaBefore) * 100;
            } else {
                $agendaGrowth = 0;
            }
            $agendaGrowth = round($agendaGrowth, 0) + 100;

            //Berita -------------------------------------------------------------------------------------------
            $chartNews = [];

            foreach ($years as $y) {
                $monthly = News::selectRaw('MONTH(`published_at`) as month, COUNT(*) as total')
                    ->whereYear('published_at', $y)
                    ->groupByRaw('MONTH(`published_at`)')
                    ->orderByRaw('MONTH(`published_at`)')
                    ->get()
                    ->pluck('total', 'month'); // jadi key → month, value → total

                // handle bulan kosong → isi 0
                $chartNews[$y] = collect(range(1, 12))->map(function ($m) use ($monthly) {
                    return $monthly[$m] ?? 0;
                });
            }


            $newsBefore = News::whereYear('published_at', date('Y') - 1)->count();
            $newsAfter = News::whereYear('published_at', date('Y'))->count();

            if ($newsBefore > 0) {
                $newsGrowth = (($newsAfter - $newsBefore) / $newsBefore) * 100;
            } else {
                $newsGrowth = 0;
            }
            $newsGrowth = round($newsGrowth, 0) + 100;
            
            
            //Bidang -------------------------------------------------------------------------------------------
            $bidang = Bidangs::with('user')->get();
            
            $agenda = Agendas::count();
            $news = News::count();
            $user = User::count();
            $program = Programs::count();
            $kategori = Kategori::count();

            $data = array(
                'head' => "Dashboard",
                'title' => "Dashboard",
                'menu' => "Dashboard",

                'chartAgenda' => $chartAgenda,
                'agendaBefore' => $agendaBefore,
                'agendaAfter' => $agendaAfter,
                'agendaGrowth' => $agendaGrowth,

                'chartNews' => $chartNews,
                'newsBefore' => $newsBefore,
                'newsAfter' => $newsAfter,
                'newsGrowth' => $newsGrowth,

                'agenda' => $agenda,
                'news' => $news,
                'user' => $user,
                'bidang' => $bidang,
                'program' => $program,
                'kategori' => $kategori
            );

            return view('admin.home.index')->with($data);
        });
    });

    Route::resource('/admin-data', DashboardsController::class);

    Route::resource('/admin-agenda', AgendasController::class);

    Route::resource('/admin-berita', NewsController::class);

    Route::resource('/admin-program', ProgramsController::class);

    Route::resource('/admin-produksi', ProductionsController::class);

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