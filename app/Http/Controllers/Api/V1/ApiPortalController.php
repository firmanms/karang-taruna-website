<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Content\Models\Achievement;
use App\Domain\Content\Models\Article;
use App\Domain\Content\Models\Download;
use App\Domain\Content\Models\Event;
use App\Domain\Content\Models\PhotoGallery;
use App\Domain\Content\Models\WorkProgram;
use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Territory\Models\RefVillage;
use App\Domain\Units\Models\KarangTarunaUnit;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AchievementResource;
use App\Http\Resources\Api\V1\ArticleResource;
use App\Http\Resources\Api\V1\DownloadResource;
use App\Http\Resources\Api\V1\EventResource;
use App\Http\Resources\Api\V1\PhotoGalleryResource;
use App\Http\Resources\Api\V1\TerritoryDistrictResource;
use App\Http\Resources\Api\V1\UnitResource;
use App\Http\Resources\Api\V1\WorkProgramResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiPortalController extends Controller
{
    /**
     * Standard response helper
     */
    protected function jsonResponse(bool $success, string $message, mixed $data = null, mixed $meta = null, int $statusCode = 200): JsonResponse
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];

        if ($meta !== null) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * 1. Public API: News & Articles
     */
    public function news(Request $request): JsonResponse
    {
        $query = Article::published()->with(['category', 'district', 'village', 'unit']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('news_scope')) {
            $query->where('news_scope', $request->news_scope);
        }

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $sort = $request->get('sort', 'latest');
        if ($sort === 'popular') {
            $query->orderBy('views_count', 'desc');
        } else {
            $query->latest('published_at');
        }

        $articles = $query->paginate($request->get('per_page', 10));

        return $this->jsonResponse(
            true,
            'Daftar berita publik berhasil diambil',
            ArticleResource::collection($articles->items()),
            [
                'current_page' => $articles->currentPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
                'last_page' => $articles->lastPage(),
            ]
        );
    }

    /**
     * Detail Berita by Slug
     */
    public function newsDetail(string $slug): JsonResponse
    {
        $article = Article::published()->where('slug', $slug)->with(['category', 'district', 'village', 'unit'])->first();

        if (! $article) {
            return $this->jsonResponse(false, 'Berita tidak ditemukan atau belum dipublikasikan', null, null, 404);
        }

        $article->increment('views_count');

        return $this->jsonResponse(true, 'Detail berita berhasil diambil', new ArticleResource($article));
    }

    /**
     * 2. Public API: Events & Agendas
     */
    public function events(Request $request): JsonResponse
    {
        $query = Event::where('approval_status', 'approved')->with(['category', 'unit']);

        if ($request->filled('status')) {
            $query->where('event_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('organizer', 'like', "%{$search}%")
                    ->orWhere('location_venue', 'like', "%{$search}%");
            });
        }

        $events = $query->orderBy('event_date', 'desc')->paginate($request->get('per_page', 10));

        return $this->jsonResponse(
            true,
            'Daftar agenda kegiatan berhasil diambil',
            EventResource::collection($events->items()),
            [
                'current_page' => $events->currentPage(),
                'per_page' => $events->perPage(),
                'total' => $events->total(),
                'last_page' => $events->lastPage(),
            ]
        );
    }

    /**
     * 3. Public API: Work Programs
     */
    public function programs(Request $request): JsonResponse
    {
        $query = WorkProgram::where('approval_status', 'approved')->with(['division', 'unit']);

        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        if ($request->filled('status')) {
            $query->where('progress_status', $request->status);
        }

        $programs = $query->latest()->paginate($request->get('per_page', 10));

        return $this->jsonResponse(
            true,
            'Daftar program kerja berhasil diambil',
            WorkProgramResource::collection($programs->items()),
            [
                'current_page' => $programs->currentPage(),
                'per_page' => $programs->perPage(),
                'total' => $programs->total(),
                'last_page' => $programs->lastPage(),
            ]
        );
    }

    /**
     * 4. Public API: Achievements
     */
    public function achievements(Request $request): JsonResponse
    {
        $query = Achievement::where('approval_status', 'approved')->with('unit');

        if ($request->filled('level')) {
            $query->where('achievement_level', $request->level);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $achievements = $query->latest('year')->paginate($request->get('per_page', 10));

        return $this->jsonResponse(
            true,
            'Daftar prestasi pemuda berhasil diambil',
            AchievementResource::collection($achievements->items()),
            [
                'current_page' => $achievements->currentPage(),
                'per_page' => $achievements->perPage(),
                'total' => $achievements->total(),
                'last_page' => $achievements->lastPage(),
            ]
        );
    }

    /**
     * 5. Public API: Galleries (Photos)
     */
    public function galleries(Request $request): JsonResponse
    {
        $photos = PhotoGallery::where('approval_status', 'approved')
            ->with(['category', 'unit'])
            ->latest()
            ->paginate($request->get('per_page', 12));

        return $this->jsonResponse(
            true,
            'Galeri foto kegiatan berhasil diambil',
            PhotoGalleryResource::collection($photos->items()),
            [
                'current_page' => $photos->currentPage(),
                'per_page' => $photos->perPage(),
                'total' => $photos->total(),
                'last_page' => $photos->lastPage(),
            ]
        );
    }

    /**
     * 6. Public API: Downloads
     */
    public function downloads(Request $request): JsonResponse
    {
        $downloads = Download::publicDownloads()
            ->with('category')
            ->latest('release_date')
            ->paginate($request->get('per_page', 15));

        return $this->jsonResponse(
            true,
            'Daftar dokumen publik berhasil diambil',
            DownloadResource::collection($downloads->items()),
            [
                'current_page' => $downloads->currentPage(),
                'per_page' => $downloads->perPage(),
                'total' => $downloads->total(),
                'last_page' => $downloads->lastPage(),
            ]
        );
    }

    /**
     * 7. Public API: Karang Taruna Units Directory
     */
    public function units(Request $request): JsonResponse
    {
        $query = KarangTarunaUnit::where('is_verified', true)->with(['district', 'village']);

        if ($request->filled('level')) {
            $query->where('unit_level', $request->level);
        }

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        $units = $query->paginate($request->get('per_page', 12));

        return $this->jsonResponse(
            true,
            'Direktori unit Karang Taruna berhasil diambil',
            UnitResource::collection($units->items()),
            [
                'current_page' => $units->currentPage(),
                'per_page' => $units->perPage(),
                'total' => $units->total(),
                'last_page' => $units->lastPage(),
            ]
        );
    }

    /**
     * 8. Public API: Territory Master (Districts & Villages)
     */
    public function territory(): JsonResponse
    {
        $districts = RefDistrict::withCount(['villages', 'units'])->orderBy('name')->get();

        return $this->jsonResponse(
            true,
            'Master data wilayah administratif berhasil diambil',
            TerritoryDistrictResource::collection($districts)
        );
    }

    /**
     * 9. Public API: Aggregate Statistics
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_districts' => RefDistrict::count(),
            'total_villages' => RefVillage::count(),
            'total_units' => KarangTarunaUnit::where('is_verified', true)->count(),
            'total_members' => (int) KarangTarunaUnit::where('is_verified', true)->sum('total_members'),
            'total_events' => Event::where('approval_status', 'approved')->count(),
            'total_programs' => WorkProgram::where('approval_status', 'approved')->count(),
            'total_achievements' => Achievement::where('approval_status', 'approved')->count(),
            'total_articles' => Article::published()->count(),
            'total_downloads' => Download::publicDownloads()->count(),
        ];

        return $this->jsonResponse(true, 'Statistik portal berhasil diambil', $stats);
    }

    /**
     * 10. Private API Authentication (Sanctum)
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial email atau kata sandi tidak valid.'],
            ]);
        }

        if (! $user->is_active) {
            return $this->jsonResponse(false, 'Akun pengguna sedang non-aktif. Silakan hubungi administrator.', null, null, 403);
        }

        $token = $user->createToken('api_auth_token')->plainTextToken;

        return $this->jsonResponse(true, 'Login berhasil', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role?->name,
                'unit' => $user->unit?->unit_name,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Profile user yang sedang login via Token
     */
    public function userProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->jsonResponse(true, 'Profil pengguna terotentikasi', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role?->name,
            'unit' => $user->unit ? [
                'id' => $user->unit->id,
                'unit_name' => $user->unit->unit_name,
                'unit_level' => $user->unit->unit_level,
            ] : null,
        ]);
    }

    /**
     * Logout & Revoke Token
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->jsonResponse(true, 'Token sesi API berhasil dicabut.');
    }
}
