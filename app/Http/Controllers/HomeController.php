<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use voku\helper\AntiXSS;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use App\Models\News;
use App\Models\Agendas;
use App\Models\Visitor;
use App\Models\Kategori;
use App\Models\Dashboards;
use App\Models\Productions;

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
        $news = News::orderByDesc('published_at')->take(3)->get();

        $datas = Dashboards::first();

        $currentUrl = request()->fullUrl();

        $total = Visitor::where('url', $currentUrl)->count();

        $currentUrl = request()->fullUrl();
        $visitor = Visitor::where('url', $currentUrl)->count();

        $kategori = Kategori::all();

        $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
            'part' => 'snippet',
            'channelId' => env('YOUTUBE_CHANNEL_ID'),
            'maxResults' => 3,
            'order' => 'date',
            'type' => 'video',
            'key' => env('YOUTUBE_API_KEY'),
        ]);
        $videos = $response->json('items') ?? [];
        $videoIds = collect($videos)
            ->pluck('id.videoId')
            ->implode(',');

        $detailsResponse = Http::get('https://www.googleapis.com/youtube/v3/videos', [
            'part' => 'statistics,contentDetails',
            'id' => $videoIds,
            'key' => env('YOUTUBE_API_KEY'),
        ]);

        $details = collect($detailsResponse->json('items'))
            ->keyBy('id');

        $videos = collect($videos)->map(function ($video) use ($details) {
            $id = $video['id']['videoId'];

            return [
                'id' => $id,
                'title' => $video['snippet']['title'],
                'thumbnail' => $video['snippet']['thumbnails']['medium']['url'],
                'published_at' => $video['snippet']['publishedAt'],
                'views' => $details[$id]['statistics']['viewCount'] ?? 0,
                'likes' => $details[$id]['statistics']['likeCount'] ?? 0,
                'duration' => $details[$id]['contentDetails']['duration'] ?? 'PT0S',
            ];
        });

        $response = Http::withHeaders([
            'X-API-KEY' => 'Ozd9LIoM+jeU3A53NCfLAz9sWgxJoib/gwv9CfQk8qs=',
        ])->get('https://siwawan.kuningankab.go.id/Controller/api.php');

        if ($response->failed()) {
            return response()->json([
                'status' => false,
                'message' => 'API gagal dipanggil',
                'error' => $response->body(),
            ], 500);
        }

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
            'kelompok_tani' => $response->json()['total'],
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
        foreach ($agenda as $new) {
            $new->visitor_count = \DB::table('visitors')
                ->where('url', 'like', '%' . $new->slug)
                ->count();
        }

        $popular = Agendas::select('agendas.*')
            ->selectSub(function ($q) {
                $q->from('visitors')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('visitors.url', 'like', DB::raw("CONCAT('%', agendas.slug)"));
            }, 'visitor_count')
            ->orderByDesc('visitor_count')
            ->limit(5)
            ->get();

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

        $agenda->visitor_count = \DB::table('visitors')
            ->where('url', 'like', '%' . $agenda->slug)
            ->count();

        $popular = Agendas::select('agendas.*')
            ->selectSub(function ($q) {
                $q->from('visitors')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('visitors.url', 'like', DB::raw("CONCAT('%', agendas.slug)"));
            }, 'visitor_count')
            ->orderByDesc('visitor_count')
            ->limit(5)
            ->get();

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
        $news = News::orderByDesc('published_at')->paginate(9);
        $lastest = News::orderByDesc('published_at')->take(5)->get();
        foreach ($news as $new) {
            $new->visitor_count = \DB::table('visitors')
                ->where('url', 'like', '%' . $new->slug)
                ->count();
        }

        $popular = News::select('news.*')
            ->selectSub(function ($q) {
                $q->from('visitors')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('visitors.url', 'like', DB::raw("CONCAT('%', news.slug)"));
            }, 'visitor_count')
            ->orderByDesc('visitor_count')
            ->limit(5)
            ->get();

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

        $lastest = News::orderByDesc('published_at')->take(5)->get();

        $news->visitor_count = \DB::table('visitors')
            ->where('url', 'like', '%' . $news->slug)
            ->count();

        $popular = News::select('news.*')
            ->selectSub(function ($q) {
                $q->from('visitors')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('visitors.url', 'like', DB::raw("CONCAT('%', news.slug)"));
            }, 'visitor_count')
            ->orderByDesc('visitor_count')
            ->limit(5)
            ->get();

        return view('public.berita.detail', [
            'head' => 'Berita',
            'title' => 'Berita',
            'menu' => 'Berita',
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

        $news = $query->orderBy('published_at', 'asc')->paginate(9)->appends(request()->query());


        return response()->json([
            'success' => true,
            'data' => view('public.ajax.news', compact('news'))->render(),
        ]);
    }

    public function program()
    {
        $kategori = Kategori::with([
            'programs.news' => function ($q) {
                $q->latest()->limit(5);
            }
        ])->get();

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
        $kategori = Kategori::with([
            'programs.news' => function ($q) {
                $q->latest()->limit(5);
            }
        ])->get();

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
        $response = Http::withHeaders([
            'X-API-KEY' => 'Ozd9LIoM+jeU3A53NCfLAz9sWgxJoib/gwv9CfQk8qs=',
        ])->get('https://siwawan.kuningankab.go.id/Controller/api.php');

        if ($response->failed()) {
            return response()->json([
                'status' => false,
                'message' => 'API gagal dipanggil',
                'error' => $response->body(),
            ], 500);
        }
        $datas = Dashboards::first();

        //produksi
        $produksi = Productions::all();
        
        $data = array(
            'head' => "Data dan Informasi",
            'title' => "Data dan Informasi",
            'menu' => "Data dan Informasi",
            'data_tani' => $response->json()['data'],
            'kelompok_tani' => $response->json()['total'],
            'data' => $datas,
            'produksi' => $produksi,
         );

        return view('public.data.index')->with($data);
    }

    public function byKategori($id)
    {
        $news = News::whereHas('programs.kategori', function ($q) use ($id) {
            $q->where('id', $id);
        })
            ->latest()
            ->paginate(6);

        return view('public.ajax.news', compact('news'));
    }
}