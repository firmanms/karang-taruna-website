<?php

namespace App\Http\Controllers\Public;

use App\Domain\Content\Models\Achievement;
use App\Domain\Content\Models\Announcement;
use App\Domain\Content\Models\Article;
use App\Domain\Content\Models\Download;
use App\Domain\Content\Models\Event;
use App\Domain\Content\Models\NewsCategory;
use App\Domain\Content\Models\PhotoGallery;
use App\Domain\Content\Models\VideoGallery;
use App\Domain\Content\Models\WorkProgram;
use App\Domain\PPKS\Models\PpksBeneficiary;
use App\Domain\PPKS\Models\PpksCheckLog;
use App\Domain\Settings\Models\Faq;
use App\Domain\Settings\Models\HeroSlider;
use App\Domain\Settings\Models\ProfileOrganization;
use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Territory\Models\RefVillage;
use App\Domain\Units\Models\KarangTarunaUnit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublicPortalController extends Controller
{
    /**
     * Halaman Beranda Utama
     */
    public function index()
    {
        $sliders = HeroSlider::where('is_active', true)->orderBy('order_index')->get();
        $profile = ProfileOrganization::first();

        $articles = Article::published()
            ->where(function ($q) {
                $q->where('news_scope', 'Pusat/Kabupaten')
                    ->orWhereNull('news_scope');
            })
            ->with(['category', 'unit', 'district'])
            ->latest('published_at')
            ->take(4)
            ->get();

        $regionalArticles = Article::published()
            ->where('news_scope', 'Kecamatan/Desa')
            ->with(['category', 'unit', 'district'])
            ->latest('published_at')
            ->take(3)
            ->get();

        // Fallback jika belum ada yang di-tag Kecamatan/Desa, ambil latest
        if ($regionalArticles->isEmpty()) {
            $regionalArticles = Article::published()
                ->with(['category', 'unit', 'district'])
                ->latest('published_at')
                ->skip(4)
                ->take(3)
                ->get();
        }

        $events = Event::where('approval_status', 'approved')
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->take(3)
            ->get();

        $featuredPrograms = WorkProgram::where('approval_status', 'approved')
            ->where('is_featured_home', true)
            ->take(6)
            ->get();

        if ($featuredPrograms->isEmpty()) {
            $featuredPrograms = WorkProgram::where('approval_status', 'approved')
                ->take(6)
                ->get();
        }

        $achievements = Achievement::where('approval_status', 'approved')
            ->latest('year')
            ->take(3)
            ->get();

        $photos = PhotoGallery::where('approval_status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        $announcements = Announcement::active()
            ->latest()
            ->take(3)
            ->get();

        $faqs = Faq::where('is_active', true)->orderBy('order_index')->take(5)->get();

        $totalDistricts = RefDistrict::count();
        $totalVillages = RefVillage::count();
        $totalUnits = KarangTarunaUnit::where('status_aktif', 'Aktif')->count();
        $totalMembers = KarangTarunaUnit::sum('total_members');

        return view('public.index', compact(
            'sliders',
            'profile',
            'articles',
            'regionalArticles',
            'events',
            'featuredPrograms',
            'achievements',
            'photos',
            'announcements',
            'faqs',
            'totalDistricts',
            'totalVillages',
            'totalUnits',
            'totalMembers'
        ));
    }

    /**
     * Halaman Profil / Tentang Kami
     */
    public function profile()
    {
        $profile = ProfileOrganization::with('missions')->first();

        return view('public.tentang-kami', compact('profile'));
    }

    /**
     * Halaman Indeks Berita
     */
    public function news(Request $request)
    {
        $query = Article::published()->with(['category', 'unit', 'district']);

        if ($request->filled('kategori')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->q.'%')
                    ->orWhere('content', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->filled('scope')) {
            $query->where('news_scope', $request->scope);
        }

        $articles = $query->latest('published_at')->paginate(9)->withQueryString();
        $categories = NewsCategory::all();

        return view('public.berita', compact('articles', 'categories'));
    }

    /**
     * Halaman Detail Berita
     */
    public function newsDetail(string $slug)
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->with(['category', 'unit', 'author', 'district', 'village'])
            ->firstOrFail();

        // Increment Views Count
        $article->increment('views_count');

        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category_id', $article->category_id)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('public.detail-berita', compact('article', 'relatedArticles'));
    }

    /**
     * Halaman Agenda Kegiatan
     */
    public function events(Request $request)
    {
        $query = Event::where('approval_status', 'approved')->with(['category', 'unit']);

        if ($request->filled('status')) {
            $query->where('event_status', $request->status);
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(8)->withQueryString();

        return view('public.agenda', compact('events'));
    }

    /**
     * Halaman Program Kerja
     */
    public function programs()
    {
        $programs = WorkProgram::where('approval_status', 'approved')
            ->with(['division', 'unit'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('public.program', compact('programs'));
    }

    /**
     * Halaman Pengumuman
     */
    public function announcements()
    {
        $announcements = Announcement::active()->latest()->paginate(10);

        return view('public.pengumuman', compact('announcements'));
    }

    /**
     * Halaman Galeri Foto
     */
    public function photoGallery()
    {
        $photos = PhotoGallery::where('approval_status', 'approved')
            ->with(['category', 'unit'])
            ->latest()
            ->paginate(12);

        return view('public.galeri-foto', compact('photos'));
    }

    /**
     * Halaman Galeri Video
     */
    public function videoGallery()
    {
        $videos = VideoGallery::where('approval_status', 'approved')
            ->latest()
            ->paginate(8);

        return view('public.galeri-video', compact('videos'));
    }

    /**
     * Halaman Prestasi Pemuda
     */
    public function achievements()
    {
        $achievements = Achievement::where('approval_status', 'approved')
            ->latest('year')
            ->paginate(9);

        return view('public.prestasi', compact('achievements'));
    }

    /**
     * Halaman Pusat Unduhan Dokumen
     */
    public function downloads()
    {
        $downloads = Download::publicDownloads()
            ->with('category')
            ->latest('release_date')
            ->paginate(15);

        return view('public.unduhan', compact('downloads'));
    }

    /**
     * Download counter tracker
     */
    public function downloadFile(int $id)
    {
        $download = Download::publicDownloads()->findOrFail($id);
        $download->increment('download_count');

        return response()->download(storage_path('app/public/'.$download->file_path));
    }

    /**
     * Halaman Direktori Unit (Kabupaten, Kecamatan, Desa)
     */
    public function directory(Request $request)
    {
        $query = KarangTarunaUnit::where('is_verified', true)->with(['district', 'village']);

        if ($request->filled('level')) {
            $query->where('unit_level', $request->level);
        }

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        $units = $query->paginate(12)->withQueryString();
        $districts = RefDistrict::orderBy('name')->get();

        return view('public.direktori', compact('units', 'districts'));
    }

    /**
     * Halaman Peta Sebaran GIS
     */
    public function territoryMap()
    {
        $districts = RefDistrict::withCount('units')->get();
        $units = KarangTarunaUnit::where('is_verified', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'unit_name', 'unit_level', 'chairman_name', 'contact_phone', 'office_address', 'latitude', 'longitude', 'status_aktif']);

        return view('public.peta-sebaran', compact('districts', 'units'));
    }

    /**
     * Halaman Cek PPKS Mandiri Publik
     */
    public function checkPpks(Request $request)
    {
        $result = null;
        $searched = false;

        if ($request->isMethod('post')) {
            $request->validate([
                'nik' => ['required', 'digits:16'],
            ]);

            $searched = true;
            $nikInput = $request->nik;
            $maskedNik = substr($nikInput, 0, 6).'******'.substr($nikInput, -4);

            $beneficiary = PpksBeneficiary::where('nik', $nikInput)
                ->where('verification_status', 'verified')
                ->with(['category', 'district', 'village', 'unit'])
                ->first();

            // Log pencarian
            PpksCheckLog::create([
                'searched_nik_masked' => $maskedNik,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'result_status' => $beneficiary ? 'found' : 'not_found',
            ]);

            $result = $beneficiary;
        }

        return view('public.cek-ppks', compact('result', 'searched'));
    }
}
