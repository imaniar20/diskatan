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
use App\Models\HortikulturaUtama;
use App\Models\PerkebunanUtama;
use App\Models\Perkebunan;
use App\Models\Komoditas;

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
        $komoditasList = Komoditas::getListBySektor('tanaman_pangan');

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
            SUM(realisasi_okt) as realisasi_okt,
            SUM(realisasi_nop) as realisasi_nop,
            SUM(realisasi_des) as realisasi_des,
            SUM(realisasi_jan) as realisasi_jan,
            SUM(realisasi_feb) as realisasi_feb,
            SUM(realisasi_mar) as realisasi_mar,
            SUM(realisasi_apr) as realisasi_apr,
            SUM(realisasi_mei) as realisasi_mei,
            SUM(realisasi_jun) as realisasi_jun,
            SUM(realisasi_jul) as realisasi_jul,
            SUM(realisasi_ags) as realisasi_ags,
            SUM(realisasi_sep) as realisasi_sep,
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
                'total_sawah' => (float) $sr->total_sawah,
                'target_sub1' => (float) $sr->target_okmar,
                'target_sub2' => (float) $sr->target_asep,
                'target_total' => (float) $sr->target_total,
                'realisasi_okt' => (float) $sr->realisasi_okt,
                'realisasi_nop' => (float) $sr->realisasi_nop,
                'realisasi_des' => (float) $sr->realisasi_des,
                'realisasi_jan' => (float) $sr->realisasi_jan,
                'realisasi_feb' => (float) $sr->realisasi_feb,
                'realisasi_mar' => (float) $sr->realisasi_mar,
                'realisasi_apr' => (float) $sr->realisasi_apr,
                'realisasi_mei' => (float) $sr->realisasi_mei,
                'realisasi_jun' => (float) $sr->realisasi_jun,
                'realisasi_jul' => (float) $sr->realisasi_jul,
                'realisasi_ags' => (float) $sr->realisasi_ags,
                'realisasi_sep' => (float) $sr->realisasi_sep,
                'realisasi_sub1' => (float) $sr->realisasi_mh,
                'realisasi_sub2' => (float) $sr->realisasi_mk,
                'total_realisasi' => (float) $sr->total_realisasi,
                'avg_target' => round((float) $sr->avg_target, 2),
                'avg_realisasi' => round((float) $sr->avg_realisasi, 2),
                'persentase' => (float) $sr->target_total > 0 ? round(((float) $sr->total_realisasi / (float) $sr->target_total) * 100, 2) : 0,
            ];
        }

        // Hitung ulang ringkasan produktivitas se-kabupaten sesuai rumus Excel: (Produksi Ton / Panen Ha) * 10
        if (isset($padiPalawijaSummaryGrouped['produktivitas'])) {
            $monthKeys = ['okt', 'nop', 'des', 'jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'ags', 'sep'];
            foreach ($padiPalawijaSummaryGrouped['produktivitas'] as $kmd => &$prodv) {
                $panen = $padiPalawijaSummaryGrouped['panen'][$kmd] ?? null;
                $produksi = $padiPalawijaSummaryGrouped['produksi'][$kmd] ?? null;
                if ($panen && $produksi) {
                    $tPanen1 = (float) $panen['target_sub1'];
                    $tPanen2 = (float) $panen['target_sub2'];
                    $tPanenTot = (float) $panen['target_total'];
                    $rPanen1 = (float) $panen['realisasi_sub1'];
                    $rPanen2 = (float) $panen['realisasi_sub2'];
                    $rPanenTot = (float) $panen['total_realisasi'];

                    $tProd1 = (float) $produksi['target_sub1'];
                    $tProd2 = (float) $produksi['target_sub2'];
                    $tProdTot = (float) $produksi['target_total'];
                    $rProd1 = (float) $produksi['realisasi_sub1'];
                    $rProd2 = (float) $produksi['realisasi_sub2'];
                    $rProdTot = (float) $produksi['total_realisasi'];

                    $prodv['target_sub1'] = $tPanen1 > 0 ? round(($tProd1 / $tPanen1) * 10, 2) : 0;
                    $prodv['target_sub2'] = $tPanen2 > 0 ? round(($tProd2 / $tPanen2) * 10, 2) : 0;
                    $prodv['target_total'] = $tPanenTot > 0 ? round(($tProdTot / $tPanenTot) * 10, 2) : 0;
                    $prodv['realisasi_sub1'] = $rPanen1 > 0 ? round(($rProd1 / $rPanen1) * 10, 2) : 0;
                    $prodv['realisasi_sub2'] = $rPanen2 > 0 ? round(($rProd2 / $rPanen2) * 10, 2) : 0;
                    $prodv['total_realisasi'] = $rPanenTot > 0 ? round(($rProdTot / $rPanenTot) * 10, 2) : 0;

                    foreach ($monthKeys as $mk) {
                        $pM = (float) ($panen['realisasi_' . $mk] ?? 0);
                        $prM = (float) ($produksi['realisasi_' . $mk] ?? 0);
                        $prodv['realisasi_' . $mk] = $pM > 0 ? round(($prM / $pM) * 10, 2) : 0;
                    }

                    $prodv['avg_target'] = $prodv['target_total'];
                    $prodv['avg_realisasi'] = $prodv['total_realisasi'];
                    $prodv['persentase'] = $prodv['target_total'] > 0 ? round(($prodv['total_realisasi'] / $prodv['target_total']) * 100, 2) : 0;
                }
            }
            unset($prodv);
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
        $sayuranKomoditasList = Komoditas::getListBySektor('hortikultura');

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

        // Data Tanaman Pangan (Historis)
        $tanamanPanganKomoditasList = Komoditas::getListBySektor('tanaman_pangan');

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

        // Hitung jumlah tahun dinamis untuk data tahunan
        $tanamanPanganTahunCount = TanamanPangan::distinct()->count('tahun') ?: 13;
        $sayuranTahunCount = Sayuran::distinct()->count('tahun') ?: 13;
        $perkebunanTahunCount = Perkebunan::distinct()->count('tahun') ?: 10;

        // Data Utama Hortikultura
        $allHortikulturaUtama = HortikulturaUtama::where('tahun', $selectedTahun)
            ->orderBy('no_urut', 'asc')
            ->get();

        $hortikulturaGrouped = [];
        foreach ($allHortikulturaUtama as $it) {
            $kat = $it->kategori ?: 'tanam';
            $hortikulturaGrouped[$kat][$it->komoditas][] = $it;
        }

        $hortikulturaSummaryGrouped = [];
        $hortiSummaryRows = HortikulturaUtama::selectRaw('
            kategori,
            komoditas,
            COUNT(*) as total_kecamatan,
            SUM(luas_sawah) as total_sawah,
            SUM(target_okmar) as target_okmar,
            SUM(target_asep) as target_asep,
            SUM(target_total) as target_total,
            SUM(realisasi_okt) as realisasi_okt,
            SUM(realisasi_nop) as realisasi_nop,
            SUM(realisasi_des) as realisasi_des,
            SUM(realisasi_jan) as realisasi_jan,
            SUM(realisasi_feb) as realisasi_feb,
            SUM(realisasi_mar) as realisasi_mar,
            SUM(realisasi_apr) as realisasi_apr,
            SUM(realisasi_mei) as realisasi_mei,
            SUM(realisasi_jun) as realisasi_jun,
            SUM(realisasi_jul) as realisasi_jul,
            SUM(realisasi_ags) as realisasi_ags,
            SUM(realisasi_sep) as realisasi_sep,
            SUM(jumlah_mh) as realisasi_mh,
            SUM(jumlah_mk) as realisasi_mk,
            SUM(total_realisasi) as total_realisasi,
            AVG(total_realisasi) as avg_realisasi,
            AVG(target_total) as avg_target
        ')
            ->where('tahun', $selectedTahun)
            ->groupBy('kategori', 'komoditas')
            ->get();

        foreach ($hortiSummaryRows as $sr) {
            $kat = $sr->kategori ?: 'tanam';
            $hortikulturaSummaryGrouped[$kat][$sr->komoditas] = [
                'total_sawah' => (float) $sr->total_sawah,
                'target_sub1' => (float) $sr->target_okmar,
                'target_sub2' => (float) $sr->target_asep,
                'target_total' => (float) $sr->target_total,
                'realisasi_okt' => (float) $sr->realisasi_okt,
                'realisasi_nop' => (float) $sr->realisasi_nop,
                'realisasi_des' => (float) $sr->realisasi_des,
                'realisasi_jan' => (float) $sr->realisasi_jan,
                'realisasi_feb' => (float) $sr->realisasi_feb,
                'realisasi_mar' => (float) $sr->realisasi_mar,
                'realisasi_apr' => (float) $sr->realisasi_apr,
                'realisasi_mei' => (float) $sr->realisasi_mei,
                'realisasi_jun' => (float) $sr->realisasi_jun,
                'realisasi_jul' => (float) $sr->realisasi_jul,
                'realisasi_ags' => (float) $sr->realisasi_ags,
                'realisasi_sep' => (float) $sr->realisasi_sep,
                'realisasi_sub1' => (float) $sr->realisasi_mh,
                'realisasi_sub2' => (float) $sr->realisasi_mk,
                'total_realisasi' => (float) $sr->total_realisasi,
                'avg_target' => round((float) $sr->avg_target, 2),
                'avg_realisasi' => round((float) $sr->avg_realisasi, 2),
                'persentase' => (float) $sr->target_total > 0 ? round(((float) $sr->total_realisasi / (float) $sr->target_total) * 100, 2) : 0,
            ];
        }

        // Hitung ulang ringkasan produktivitas hortikultura se-kabupaten: (Produksi Ton / Panen Ha) * 10
        if (isset($hortikulturaSummaryGrouped['produktivitas'])) {
            $monthKeys = ['okt', 'nop', 'des', 'jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'ags', 'sep'];
            foreach ($hortikulturaSummaryGrouped['produktivitas'] as $kmd => &$hProdv) {
                $hPanen = $hortikulturaSummaryGrouped['panen'][$kmd] ?? null;
                $hProduksi = $hortikulturaSummaryGrouped['produksi'][$kmd] ?? null;
                if ($hPanen && $hProduksi) {
                    $tPanen1 = (float) $hPanen['target_sub1'];
                    $tPanen2 = (float) $hPanen['target_sub2'];
                    $tPanenTot = (float) $hPanen['target_total'];
                    $rPanen1 = (float) $hPanen['realisasi_sub1'];
                    $rPanen2 = (float) $hPanen['realisasi_sub2'];
                    $rPanenTot = (float) $hPanen['total_realisasi'];

                    $tProd1 = (float) $hProduksi['target_sub1'];
                    $tProd2 = (float) $hProduksi['target_sub2'];
                    $tProdTot = (float) $hProduksi['target_total'];
                    $rProd1 = (float) $hProduksi['realisasi_sub1'];
                    $rProd2 = (float) $hProduksi['realisasi_sub2'];
                    $rProdTot = (float) $hProduksi['total_realisasi'];

                    $hProdv['target_sub1'] = $tPanen1 > 0 ? round(($tProd1 / $tPanen1) * 10, 2) : 0;
                    $hProdv['target_sub2'] = $tPanen2 > 0 ? round(($tProd2 / $tPanen2) * 10, 2) : 0;
                    $hProdv['target_total'] = $tPanenTot > 0 ? round(($tProdTot / $tPanenTot) * 10, 2) : 0;
                    $hProdv['realisasi_sub1'] = $rPanen1 > 0 ? round(($rProd1 / $rPanen1) * 10, 2) : 0;
                    $hProdv['realisasi_sub2'] = $rPanen2 > 0 ? round(($rProd2 / $rPanen2) * 10, 2) : 0;
                    $hProdv['total_realisasi'] = $rPanenTot > 0 ? round(($rProdTot / $rPanenTot) * 10, 2) : 0;

                    foreach ($monthKeys as $mk) {
                        $pM = (float) ($hPanen['realisasi_' . $mk] ?? 0);
                        $prM = (float) ($hProduksi['realisasi_' . $mk] ?? 0);
                        $hProdv['realisasi_' . $mk] = $pM > 0 ? round(($prM / $pM) * 10, 2) : 0;
                    }

                    $hProdv['avg_target'] = $hProdv['target_total'];
                    $hProdv['avg_realisasi'] = $hProdv['total_realisasi'];
                    $hProdv['persentase_sub'] = $hProdv['target_sub1'] > 0 ? round(($hProdv['realisasi_sub1'] / $hProdv['target_sub1']) * 100, 2) : 0;
                    $hProdv['persentase'] = $hProdv['target_total'] > 0 ? round(($hProdv['total_realisasi'] / $hProdv['target_total']) * 100, 2) : 0;
                }
            }
            unset($hProdv);
        }

        $hortikulturaUtamaData = $hortikulturaGrouped['tanam'] ?? [];
        $hortikulturaUtamaSummary = $hortikulturaSummaryGrouped['tanam'] ?? [];

        // Data Utama Perkebunan
        $allPerkebunanUtama = PerkebunanUtama::where('tahun', $selectedTahun)
            ->orderBy('no_urut', 'asc')
            ->get();

        $perkebunanGrouped = [];
        foreach ($allPerkebunanUtama as $it) {
            $kat = $it->kategori ?: 'tanam';
            $perkebunanGrouped[$kat][$it->komoditas][] = $it;
        }

        $perkebunanSummaryGrouped = [];
        $perkebunanSummaryRows = PerkebunanUtama::selectRaw('
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

        foreach ($perkebunanSummaryRows as $sr) {
            $kat = $sr->kategori ?: 'tanam';
            $perkebunanSummaryGrouped[$kat][$sr->komoditas] = [
                'total_sawah' => (float) $sr->total_sawah,
                'target_sub1' => (float) $sr->target_okmar,
                'target_sub2' => (float) $sr->target_asep,
                'target_total' => (float) $sr->target_total,
                'realisasi_sub1' => (float) $sr->realisasi_mh,
                'realisasi_sub2' => (float) $sr->realisasi_mk,
                'total_realisasi' => (float) $sr->total_realisasi,
                'avg_target' => round((float) $sr->avg_target, 2),
                'avg_realisasi' => round((float) $sr->avg_realisasi, 2),
                'persentase' => (float) $sr->target_total > 0 ? round(((float) $sr->total_realisasi / (float) $sr->target_total) * 100, 2) : 0,
            ];
        }

        $perkebunanUtamaData = $perkebunanGrouped['tanam'] ?? [];
        $perkebunanUtamaSummary = $perkebunanSummaryGrouped['tanam'] ?? [];

        // Data Tahunan Perkebunan (Historis Dinamis)
        $perkebunanKomoditasList = Komoditas::getListBySektor('perkebunan');

        $perkebunanTahunanData = Perkebunan::orderBy('tahun', 'asc')
            ->get()
            ->groupBy('komoditas');

        $perkebunanTahunanSummary = Perkebunan::selectRaw('
            komoditas,
            satuan_luas,
            satuan_produksi,
            COUNT(*) as total_tahun,
            SUM(luas_tanam) as total_tanam,
            SUM(luas_panen) as total_panen,
            AVG(produktivitas) as avg_produktivitas,
            SUM(produksi) as total_produksi
        ')
            ->groupBy('komoditas', 'satuan_luas', 'satuan_produksi')
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
            'sayuranTahunCount' => $sayuranTahunCount,

            'tanamanPanganKomoditasList' => $tanamanPanganKomoditasList,
            'tanamanPanganData' => $tanamanPanganData,
            'tanamanPanganSummary' => $tanamanPanganSummary,
            'tanamanPanganTahunCount' => $tanamanPanganTahunCount,

            'hortikulturaKomoditasList' => $sayuranKomoditasList,
            'hortikulturaGrouped' => $hortikulturaGrouped,
            'hortikulturaSummaryGrouped' => $hortikulturaSummaryGrouped,
            'hortikulturaUtamaData' => $hortikulturaUtamaData,
            'hortikulturaUtamaSummary' => $hortikulturaUtamaSummary,

            'perkebunanKomoditasList' => $perkebunanKomoditasList,
            'perkebunanGrouped' => $perkebunanGrouped,
            'perkebunanSummaryGrouped' => $perkebunanSummaryGrouped,
            'perkebunanUtamaData' => $perkebunanUtamaData,
            'perkebunanUtamaSummary' => $perkebunanUtamaSummary,
            'perkebunanTahunanData' => $perkebunanTahunanData,
            'perkebunanTahunanSummary' => $perkebunanTahunanSummary,
            'perkebunanTahunCount' => $perkebunanTahunCount,
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