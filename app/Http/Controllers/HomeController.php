<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use voku\helper\AntiXSS;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

use App\Models\News;
use App\Models\Agendas;
use App\Models\Visitor;
use App\Models\Kategori;
use App\Models\Dashboards;
use App\Models\Productions;
use App\Models\PadiPalawija;
use App\Models\Sayuran;
use App\Models\TanamanPangan;

class HomeController extends Controller
{
    public function visitor()
    {
        if (!session()->has('visitor')) {
            session([
                'visitor' => [
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'url' => request()->fullUrl(),
                    'visited_at' => now(),
                ]
            ]);
        }

        $currentUrl = request()->fullUrl();

        $visited = session('visited_urls', []);

        if (!in_array($currentUrl, $visited)) {

            Visitor::create([
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'url' => $currentUrl,
                'visited_at' => now(),
            ]);

            $visited[] = $currentUrl;
            session(['visited_urls' => $visited]);
        }
    }

    public function index()
    {
        $this->visitor();

        $agenda = Agendas::orderByDesc('date')->take(4)->get();
        $news = News::orderByDesc('published_at')->take(4)->get();

        $datas = Dashboards::first();

        $currentUrl = request()->fullUrl();
        $total = Visitor::where('url', $currentUrl)->count();
        $visitor = $total;

        $kategori = Kategori::all();

        $videos = Cache::remember('youtube_home_videos', 3600, function () {
            $apiKey = env('YOUTUBE_API_KEY');
            $channelId = env('YOUTUBE_CHANNEL_ID');

            if (!$apiKey || !$channelId) {
                return [];
            }

            try {
                $response = Http::timeout(3)->get('https://www.googleapis.com/youtube/v3/search', [
                    'part' => 'snippet',
                    'channelId' => $channelId,
                    'maxResults' => 3,
                    'order' => 'date',
                    'type' => 'video',
                    'key' => $apiKey,
                ]);

                $items = $response->json('items') ?? [];
                if (empty($items)) {
                    return [];
                }

                $videoIds = collect($items)
                    ->pluck('id.videoId')
                    ->filter()
                    ->implode(',');

                if (!$videoIds) {
                    return [];
                }

                $detailsResponse = Http::timeout(3)->get('https://www.googleapis.com/youtube/v3/videos', [
                    'part' => 'statistics,contentDetails',
                    'id' => $videoIds,
                    'key' => $apiKey,
                ]);

                $details = collect($detailsResponse->json('items'))
                    ->keyBy('id');

                return collect($items)->map(function ($video) use ($details) {
                    $id = $video['id']['videoId'] ?? '';

                    return [
                        'id' => $id,
                        'title' => $video['snippet']['title'] ?? '',
                        'thumbnail' => $video['snippet']['thumbnails']['medium']['url'] ?? '',
                        'published_at' => $video['snippet']['publishedAt'] ?? now(),
                        'views' => $details[$id]['statistics']['viewCount'] ?? 0,
                        'likes' => $details[$id]['statistics']['likeCount'] ?? 0,
                        'duration' => $details[$id]['contentDetails']['duration'] ?? 'PT0S',
                    ];
                })->all();
            } catch (\Throwable $e) {
                return [];
            }
        });

        // API siwawan dinonaktifkan sementara karena service sudah tidak tersedia
        $kelompokTani = null;

        $data = array(
            'head' => "Home",
            'title' => "Home",
            'menu' => "Home",
            'visitor' => $visitor,
            'news' => $news,
            'agenda' => $agenda,
            'kategori' => $kategori,
            'total' => $total,
            'data' => $datas,
            'kelompok_tani' => $kelompokTani,
            'videos' => $videos
        );

        return view('public.home.main')->with($data);
    }

    public function visiMisi()
    {
        return view('public.profil.visimisi', [
            'head' => 'Profil',
            'title' => 'Profil',
            'menu' => 'Visi & Misi',
        ]);
    }

    public function sejarah()
    {
        return view('public.profil.sejarah', [
            'head' => 'Profil',
            'title' => 'Profil',
            'menu' => 'Sejarah',
        ]);
    }

    public function struktur()
    {
        return view('public.profil.struktur', [
            'head' => 'Profil',
            'title' => 'Profil',
            'menu' => 'Struktur Organisasi',
        ]);
    }

    public function tupoksi()
    {
        return view('public.profil.tupoksi', [
            'head' => 'Profil',
            'title' => 'Profil',
            'menu' => 'Tugas Pokok dan Fungsi',
        ]);
    }

    public function daftarPejabat()
    {
        return view('public.profil.daftarPejabat', [
            'head' => 'Profil',
            'title' => 'Profil',
            'menu' => 'Daftar Pejabat',
        ]);
    }

    // ================= AGENDA =================
    public function agenda()
    {
        $agenda = Agendas::orderByDesc('date')->paginate(10);
        $lastest = Agendas::orderByDesc('date')->take(5)->get();

        $popular = Cache::remember('popular_agendas', 3600, function () {
            return Agendas::select('agendas.*')
                ->selectSub(function ($q) {
                    $q->from('visitors')
                        ->selectRaw('COUNT(*)')
                        ->whereColumn('visitors.url', 'like', DB::raw("CONCAT('%', agendas.slug)"));
                }, 'visitor_count')
                ->orderByDesc('visitor_count')
                ->limit(5)
                ->get();
        });

        return view('public.agenda.index', [
            'head' => 'Agenda',
            'title' => 'Agenda',
            'menu' => 'Agenda',
            'agenda' => $agenda,
            'lastest' => $lastest,
            'popular' => $popular
        ]);
    }

    public function agendaDetail($slug)
    {
        $this->visitor();

        $agenda = Agendas::where('slug', $slug)->firstOrFail();
        $lastest = Agendas::orderByDesc('date')->take(5)->get();

        $agenda->visitor_count = Cache::remember('agenda_visitor_count_' . $agenda->id, 300, function () use ($agenda) {
            return \DB::table('visitors')
                ->where('url', 'like', '%' . $agenda->slug)
                ->count();
        });

        $popular = Cache::remember('popular_agendas', 3600, function () {
            return Agendas::select('agendas.*')
                ->selectSub(function ($q) {
                    $q->from('visitors')
                        ->selectRaw('COUNT(*)')
                        ->whereColumn('visitors.url', 'like', DB::raw("CONCAT('%', agendas.slug)"));
                }, 'visitor_count')
                ->orderByDesc('visitor_count')
                ->limit(5)
                ->get();
        });

        return view('public.agenda.detail', [
            'head' => 'Agenda',
            'title' => 'Agenda',
            'menu' => 'Agenda',
            'agenda' => $agenda,
            'lastest' => $lastest,
            'popular' => $popular
        ]);
    }

    public function filterAgendas(Request $request)
    {
        $antiXss = new AntiXSS();

        $query = Agendas::query();

        if ($request->search) {
            $clean = $antiXss->xss_clean($request->search);

            $query->where(function ($q) use ($clean, ) {
                $q->where('title', 'like', "%" . $clean . "%");
            });
        }

        $agenda = $query->orderBy('date', 'asc')->paginate(9)->appends(request()->query());


        return response()->json([
            'success' => true,
            'data' => view('public.ajax.agendas', compact('agenda'))->render(),
        ]);
    }

    // ================= BERITA =================
    public function berita()
    {
        $news = News::with('programs')->orderByDesc('published_at')->paginate(9);
        $lastest = News::orderByDesc('published_at')->take(5)->get();

        $popular = News::orderByDesc('views')
            ->take(5)
            ->get();

        foreach ($popular as $item) {
            $item->visitor_count = $item->views;
        }

        return view('public.berita.index', [
            'head' => 'Berita',
            'title' => 'Berita',
            'menu' => 'Berita',
            'news' => $news,
            'lastest' => $lastest,
            'popular' => $popular
        ]);
    }

    public function beritaDetail($slug)
    {
        $this->visitor();

        $news = News::where('slug', $slug)->with('contents')->firstOrFail();
        $news->increment('views');
        $news->visitor_count = $news->views;

        $lastest = News::orderByDesc('published_at')->take(5)->get();

        $popular = News::orderByDesc('views')
            ->take(5)
            ->get();

        foreach ($popular as $item) {
            $item->visitor_count = $item->views;
        }

        return view('public.berita.detail', [
            'head' => 'Berita',
            'title' => 'Berita',
            'menu' => 'Detail',
            'news' => $news,
            'lastest' => $lastest,
            'popular' => $popular
        ]);
    }

    public function filterNews(Request $request)
    {
        $antiXss = new AntiXSS();

        $query = News::query();

        if ($request->search) {
            $clean = $antiXss->xss_clean($request->search);

            $query->where(function ($q) use ($clean, ) {
                $q->where('title', 'like', "%" . $clean . "%");
            });
        }

        $news = $query->with('programs')->orderBy('published_at', 'desc')->paginate(9)->appends(request()->query());


        return response()->json([
            'success' => true,
            'data' => view('public.ajax.news', compact('news'))->render(),
        ]);
    }

    public function program()
    {
        $kategori = Kategori::all();

        $data = array(
            'head' => "Program",
            'title' => "Program",
            'menu' => "Program",
            'select' => 0,
            'kategori' => $kategori,
        );

        return view('public.program.index')->with($data);
    }

    public function program_selection($select)
    {
        $kategori = Kategori::all();

        $data = array(
            'head' => "Program",
            'title' => "Program",
            'menu' => "Program",
            'select' => $select,
            'kategori' => $kategori,
        );

        return view('public.program.index')->with($data);
    }

    public function layanan()
    {
        $data = array(
            'head' => "Layanan",
            'title' => "Layanan",
            'menu' => "Layanan",
        );

        return view('public.layanan.index')->with($data);
    }

    public function dataDanInformasi()
    {
        $datas = Dashboards::first();

        // Produksi fallback
        $produksi = collect([]);

        // Data Padi & Palawija
        $komoditasList = [
            'Padi',
            'Jagung',
            'Kedelai',
            'Kacang Tanah',
            'Kacang Hijau',
            'Ubi Kayu',
            'Ubi Jalar'
        ];

        $availableYears = PadiPalawija::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = ['2026'];
        }

        $selectedTahun = (string) request()->query('tahun', $availableYears[0] ?? '2026');
        if (!in_array($selectedTahun, $availableYears)) {
            $selectedTahun = $availableYears[0] ?? '2026';
        }

        $padiKategoriList = [
            'tanam' => [
                'key' => 'tanam',
                'label' => 'Luas Tanam',
                'satuan' => 'Ha',
                'icon' => 'bx-spa',
                'target_sub1_label' => 'Target Okmar (MH)',
                'target_sub2_label' => 'Target Asep (MK)',
                'realisasi_sub1_label' => 'Realisasi MH',
                'realisasi_sub2_label' => 'Realisasi MK',
                'months_sub1' => ['Okt', 'Nop', 'Des', 'Jan', 'Feb', 'Mar'],
                'months_sub2' => ['Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep'],
            ],
            'panen' => [
                'key' => 'panen',
                'label' => 'Luas Panen',
                'satuan' => 'Ha',
                'icon' => 'bx-cut',
                'target_sub1_label' => 'Target Jan-Jun',
                'target_sub2_label' => 'Target Jul-Des',
                'realisasi_sub1_label' => 'Realisasi Jan-Jun',
                'realisasi_sub2_label' => 'Realisasi Jul-Des',
                'months_sub1' => ['Jan', 'Peb', 'Maret', 'April', 'Mei', 'Juni'],
                'months_sub2' => ['Juli', 'Agst', 'Sept', 'Okt', 'Nov', 'Des'],
            ],
            'produksi' => [
                'key' => 'produksi',
                'label' => 'Produksi',
                'satuan' => 'Ton',
                'icon' => 'bx-package',
                'target_sub1_label' => 'Target Jan-Jun',
                'target_sub2_label' => 'Target Jul-Des',
                'realisasi_sub1_label' => 'Realisasi Jan-Jun',
                'realisasi_sub2_label' => 'Realisasi Jul-Des',
                'months_sub1' => ['Jan', 'Peb', 'Maret', 'April', 'Mei', 'Juni'],
                'months_sub2' => ['Juli', 'Agst', 'Sept', 'Okt', 'Nov', 'Des'],
            ],
            'produktivitas' => [
                'key' => 'produktivitas',
                'label' => 'Produktivitas',
                'satuan' => 'Kw/Ha',
                'icon' => 'bx-line-chart',
                'target_sub1_label' => 'Target Jan-Jun',
                'target_sub2_label' => 'Target Jul-Des',
                'realisasi_sub1_label' => 'Rata-rata Jan-Jun',
                'realisasi_sub2_label' => 'Rata-rata Jul-Des',
                'months_sub1' => ['Jan', 'Peb', 'Maret', 'April', 'Mei', 'Juni'],
                'months_sub2' => ['Juli', 'Agst', 'Sept', 'Okt', 'Nov', 'Des'],
            ],
            'puso' => [
                'key' => 'puso',
                'label' => 'Puso (Gagal Panen)',
                'satuan' => 'Ha',
                'icon' => 'bx-error-circle',
                'target_sub1_label' => 'Target Jan-Jun',
                'target_sub2_label' => 'Target Jul-Des',
                'realisasi_sub1_label' => 'Realisasi Jan-Jun',
                'realisasi_sub2_label' => 'Realisasi Jul-Des',
                'months_sub1' => ['Jan', 'Peb', 'Maret', 'April', 'Mei', 'Juni'],
                'months_sub2' => ['Juli', 'Agst', 'Sept', 'Okt', 'Nov', 'Des'],
            ],
        ];

        // Ambil semua data tahun terpilih (semua kategori & komoditas)
        $allPadiPalawija = PadiPalawija::where('tahun', $selectedTahun)
            ->orderBy('no_urut', 'asc')
            ->get();

        $padiPalawijaGrouped = [];
        foreach ($allPadiPalawija as $it) {
            $kat = $it->kategori ?: 'tanam';
            $padiPalawijaGrouped[$kat][$it->komoditas][] = $it;
        }

        // Summary per [kategori][komoditas]
        $padiPalawijaSummaryGrouped = [];
        $summaryRows = PadiPalawija::selectRaw('
            kategori,
            komoditas,
            COUNT(*) as total_kecamatan,
            SUM(luas_sawah) as total_sawah,
            SUM(target_okmar) as target_okmar,
            SUM(target_asep) as target_asep,
            SUM(target_total) as target_total,
            SUM(jumlah_mh) as realisasi_mh,
            SUM(jumlah_mk) as realisasi_mk,
            SUM(total_realisasi) as total_realisasi,
            AVG(total_realisasi) as avg_realisasi,
            AVG(target_total) as avg_target
        ')
        ->where('tahun', $selectedTahun)
        ->groupBy('kategori', 'komoditas')
        ->get();

        foreach ($summaryRows as $sr) {
            $kat = $sr->kategori ?: 'tanam';
            $padiPalawijaSummaryGrouped[$kat][$sr->komoditas] = [
                'total_sawah' => (float)$sr->total_sawah,
                'target_sub1' => (float)$sr->target_okmar,
                'target_sub2' => (float)$sr->target_asep,
                'target_total' => (float)$sr->target_total,
                'realisasi_sub1' => (float)$sr->realisasi_mh,
                'realisasi_sub2' => (float)$sr->realisasi_mk,
                'total_realisasi' => (float)$sr->total_realisasi,
                'avg_target' => round((float)$sr->avg_target, 2),
                'avg_realisasi' => round((float)$sr->avg_realisasi, 2),
                'persentase' => (float)$sr->target_total > 0 ? round(((float)$sr->total_realisasi / (float)$sr->target_total) * 100, 2) : 0,
            ];
        }

        $padiPalawijaData = $padiPalawijaGrouped['tanam'] ?? [];
        $padiPalawijaSummary = $padiPalawijaSummaryGrouped['tanam'] ?? [];

        $totalTanamTarget = collect($padiPalawijaSummary)->sum('target_total');
        $totalTanamRealisasi = collect($padiPalawijaSummary)->sum('total_realisasi');
        $padiOverallStats = [
            'total_sawah' => PadiPalawija::where('komoditas', 'Padi')->where('tahun', $selectedTahun)->where('kategori', 'tanam')->sum('luas_sawah'),
            'total_target' => $totalTanamTarget,
            'total_realisasi' => $totalTanamRealisasi,
            'persentase' => $totalTanamTarget > 0 ? round(($totalTanamRealisasi / $totalTanamTarget) * 100, 2) : 0,
        ];

        // Data Sayuran & Hortikultura
        $sayuranKomoditasList = [
            'Bawang Merah',
            'Bawang Daun',
            'Cabe Besar',
            'Cabe Rawit',
            'Tomat',
            'Wortel',
            'Kubis',
            'Petsai / Sawi',
            'Rambutan',
            'Mangga',
            'Durian',
            'Jahe',
            'Kapulaga',
        ];

        $sayuranData = Sayuran::orderBy('tahun', 'asc')
            ->get()
            ->groupBy('komoditas');

        $sayuranSummary = Sayuran::selectRaw('
            komoditas,
            kategori,
            satuan_luas,
            satuan_produksi,
            COUNT(*) as total_tahun,
            SUM(luas_tanam) as total_tanam,
            SUM(luas_panen) as total_panen,
            AVG(produktivitas) as avg_produktivitas,
            SUM(produksi) as total_produksi
        ')
        ->groupBy('komoditas', 'kategori', 'satuan_luas', 'satuan_produksi')
        ->get()
        ->keyBy('komoditas');

        // Data Tanaman Pangan (Historis 13 Tahun)
        $tanamanPanganKomoditasList = [
            'Padi',
            'Jagung',
            'Kedelai',
            'Kacang Tanah',
            'Kacang Hijau',
            'Ubi Kayu',
            'Ubi Jalar',
        ];

        $tanamanPanganData = TanamanPangan::orderBy('tahun', 'asc')
            ->get()
            ->groupBy('komoditas');

        $tanamanPanganSummary = TanamanPangan::selectRaw('
            komoditas,
            COUNT(*) as total_tahun,
            SUM(luas_tanam) as total_tanam,
            SUM(luas_panen) as total_panen,
            AVG(produktivitas) as avg_produktivitas,
            SUM(produksi) as total_produksi
        ')
        ->groupBy('komoditas')
        ->get()
        ->keyBy('komoditas');

        $data = array(
            'head' => "Data dan Informasi",
            'title' => "Data dan Informasi",
            'menu' => "Data dan Informasi",
            'data_tani' => null,
            'kelompok_tani' => null,
            'data' => $datas,
            'produksi' => $produksi,
            'komoditasList' => $komoditasList,
            'availableYears' => $availableYears,
            'selectedTahun' => $selectedTahun,
            'padiKategoriList' => $padiKategoriList,
            'padiPalawijaGrouped' => $padiPalawijaGrouped,
            'padiPalawijaSummaryGrouped' => $padiPalawijaSummaryGrouped,
            'padiPalawijaData' => $padiPalawijaData,
            'padiPalawijaSummary' => $padiPalawijaSummary,
            'padiOverallStats' => $padiOverallStats,
            'sayuranKomoditasList' => $sayuranKomoditasList,
            'sayuranData' => $sayuranData,
            'sayuranSummary' => $sayuranSummary,
            'tanamanPanganKomoditasList' => $tanamanPanganKomoditasList,
            'tanamanPanganData' => $tanamanPanganData,
            'tanamanPanganSummary' => $tanamanPanganSummary,
        );

        return view('public.data.index')->with($data);
    }

    public function byKategori($id)
    {
        $news = News::with('programs')
            ->whereHas('programs.kategori', function ($q) use ($id) {
                $q->where('id', $id);
            })
            ->latest()
            ->paginate(6);

        return view('public.ajax.news', compact('news'));
    }
}