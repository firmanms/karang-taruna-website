<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Content\Models\Article;
use App\Domain\Content\Models\Event;
use App\Domain\PPKS\Models\PpksBeneficiary;
use App\Domain\Units\Models\UnitMember;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ArticleResource;
use App\Http\Resources\Api\V1\EventResource;
use App\Http\Resources\Api\V1\PpksBeneficiaryResource;
use App\Http\Resources\Api\V1\UnitMemberResource;
use App\Http\Resources\Api\V1\UnitResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApiManagementController extends Controller
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

    // ==========================================
    // 1. CRUD BERITA & ARTIKEL (Scoped Per User)
    // ==========================================

    public function articlesIndex(Request $request): JsonResponse
    {
        $articles = Article::forUser($request->user())
            ->with(['category', 'unit', 'district', 'village'])
            ->latest()
            ->paginate($request->get('per_page', 10));

        return $this->jsonResponse(
            true,
            'Daftar artikel CMS berhasil diambil',
            ArticleResource::collection($articles->items()),
            [
                'current_page' => $articles->currentPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
                'last_page' => $articles->lastPage(),
            ]
        );
    }

    public function articlesStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:news_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'image_caption' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable'],
            'news_scope' => ['nullable', 'in:pusat,daerah'],
            'featured_image' => ['nullable', 'image', 'max:512'],
        ]);

        $user = $request->user();
        $unit = $user->unit;

        $payload = [
            'category_id' => $validated['category_id'],
            'user_id' => $user->id,
            'unit_id' => $unit?->id,
            'district_id' => $unit?->district_id,
            'village_id' => $unit?->village_id,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::lower(Str::random(5)),
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 150),
            'content' => $validated['content'],
            'image_caption' => $validated['image_caption'] ?? null,
            'tags' => is_array($validated['tags'] ?? null) ? $validated['tags'] : ($validated['tags'] ? explode(',', (string) $validated['tags']) : null),
            'news_scope' => ($user->isSuperadmin() || $user->isVerifikator()) ? ($validated['news_scope'] ?? 'pusat') : 'daerah',
            'views_count' => 0,
            'is_featured' => false,
        ];

        // Approval workflow: Superadmin or can_auto_publish gets auto-approved
        if ($user->isSuperadmin() || $user->can_auto_publish) {
            $payload['approval_status'] = 'approved';
            $payload['approved_by'] = $user->id;
            $payload['approved_at'] = now();
            $payload['is_published'] = true;
            $payload['published_at'] = now();
        } else {
            $payload['approval_status'] = 'pending_approval';
            $payload['is_published'] = false;
        }

        if ($request->hasFile('featured_image')) {
            $payload['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }

        $article = Article::create($payload);

        return $this->jsonResponse(true, 'Artikel berhasil disimpan dan dikirim untuk moderasi', new ArticleResource($article->load(['category', 'unit', 'district', 'village'])), null, 201);
    }

    public function articlesShow(Request $request, int $id): JsonResponse
    {
        $article = Article::forUser($request->user())->with(['category', 'unit', 'district', 'village'])->find($id);

        if (! $article) {
            return $this->jsonResponse(false, 'Artikel tidak ditemukan atau di luar wewenang unit Anda', null, null, 404);
        }

        return $this->jsonResponse(true, 'Detail artikel CMS berhasil diambil', new ArticleResource($article));
    }

    public function articlesUpdate(Request $request, int $id): JsonResponse
    {
        $article = Article::forUser($request->user())->find($id);

        if (! $article) {
            return $this->jsonResponse(false, 'Artikel tidak ditemukan atau di luar wewenang unit Anda', null, null, 404);
        }

        $validated = $request->validate([
            'category_id' => ['sometimes', 'exists:news_categories,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['sometimes', 'string'],
            'image_caption' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable'],
            'featured_image' => ['nullable', 'image', 'max:512'],
        ]);

        if (isset($validated['title'])) {
            $article->title = $validated['title'];
            $article->slug = Str::slug($validated['title']).'-'.Str::lower(Str::random(5));
        }

        if (isset($validated['category_id'])) {
            $article->category_id = $validated['category_id'];
        }
        if (array_key_exists('excerpt', $validated)) {
            $article->excerpt = $validated['excerpt'];
        }
        if (isset($validated['content'])) {
            $article->content = $validated['content'];
        }
        if (array_key_exists('image_caption', $validated)) {
            $article->image_caption = $validated['image_caption'];
        }
        if (array_key_exists('tags', $validated)) {
            $article->tags = is_array($validated['tags']) ? $validated['tags'] : ($validated['tags'] ? explode(',', (string) $validated['tags']) : null);
        }

        if ($request->hasFile('featured_image')) {
            if ($article->featured_image && ! str_starts_with($article->featured_image, 'http')) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $article->featured_image = $request->file('featured_image')->store('articles', 'public');
        }

        $article->save();

        return $this->jsonResponse(true, 'Artikel berhasil diperbarui', new ArticleResource($article->load(['category', 'unit', 'district', 'village'])));
    }

    public function articlesDestroy(Request $request, int $id): JsonResponse
    {
        $article = Article::forUser($request->user())->find($id);

        if (! $article) {
            return $this->jsonResponse(false, 'Artikel tidak ditemukan atau di luar wewenang unit Anda', null, null, 404);
        }

        if ($article->featured_image && ! str_starts_with($article->featured_image, 'http')) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        return $this->jsonResponse(true, 'Artikel berhasil dihapus');
    }

    // ==========================================
    // 2. CRUD AGENDA & EVENT (Scoped Per User)
    // ==========================================

    public function eventsIndex(Request $request): JsonResponse
    {
        $events = Event::forUser($request->user())
            ->with(['category', 'unit'])
            ->latest('event_date')
            ->paginate($request->get('per_page', 10));

        return $this->jsonResponse(
            true,
            'Daftar agenda CMS berhasil diambil',
            EventResource::collection($events->items()),
            [
                'current_page' => $events->currentPage(),
                'per_page' => $events->perPage(),
                'total' => $events->total(),
                'last_page' => $events->lastPage(),
            ]
        );
    }

    public function eventsStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:event_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'start_time' => ['nullable', 'string'],
            'end_time' => ['nullable', 'string'],
            'location_venue' => ['required', 'string', 'max:255'],
            'location_address' => ['nullable', 'string'],
            'organizer' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'registration_link' => ['nullable', 'url'],
            'event_status' => ['nullable', 'in:Mendatang,Sedang Berlangsung,Selesai,Dibatalkan'],
            'thumbnail_image' => ['nullable', 'image', 'max:512'],
        ]);

        $user = $request->user();
        $unit = $user->unit;

        $payload = [
            'category_id' => $validated['category_id'],
            'user_id' => $user->id,
            'unit_id' => $unit?->id,
            'district_id' => $unit?->district_id,
            'village_id' => $unit?->village_id,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::lower(Str::random(5)),
            'event_date' => $validated['event_date'],
            'start_time' => $validated['start_time'] ?? null,
            'end_time' => $validated['end_time'] ?? null,
            'location_venue' => $validated['location_venue'],
            'location_address' => $validated['location_address'] ?? null,
            'organizer' => $validated['organizer'] ?? ($unit ? $unit->unit_name : 'Karang Taruna'),
            'description' => $validated['description'] ?? null,
            'registration_link' => $validated['registration_link'] ?? null,
            'event_status' => $validated['event_status'] ?? 'Mendatang',
            'approval_status' => ($user->isSuperadmin() || $user->can_auto_publish) ? 'approved' : 'pending_approval',
        ];

        if ($request->hasFile('thumbnail_image')) {
            $payload['thumbnail_image'] = $request->file('thumbnail_image')->store('events', 'public');
        }

        $event = Event::create($payload);

        return $this->jsonResponse(true, 'Agenda kegiatan berhasil dibuat', new EventResource($event->load(['category', 'unit'])), null, 201);
    }

    public function eventsUpdate(Request $request, int $id): JsonResponse
    {
        $event = Event::forUser($request->user())->find($id);

        if (! $event) {
            return $this->jsonResponse(false, 'Agenda tidak ditemukan atau di luar wewenang unit Anda', null, null, 404);
        }

        $validated = $request->validate([
            'category_id' => ['sometimes', 'exists:event_categories,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'event_date' => ['sometimes', 'date'],
            'start_time' => ['nullable', 'string'],
            'end_time' => ['nullable', 'string'],
            'location_venue' => ['sometimes', 'string', 'max:255'],
            'location_address' => ['nullable', 'string'],
            'organizer' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'registration_link' => ['nullable', 'url'],
            'event_status' => ['nullable', 'in:Mendatang,Sedang Berlangsung,Selesai,Dibatalkan'],
            'thumbnail_image' => ['nullable', 'image', 'max:512'],
        ]);

        if (isset($validated['title'])) {
            $event->title = $validated['title'];
            $event->slug = Str::slug($validated['title']).'-'.Str::lower(Str::random(5));
        }

        $event->fill($request->only([
            'category_id', 'event_date', 'start_time', 'end_time',
            'location_venue', 'location_address', 'organizer',
            'description', 'registration_link', 'event_status',
        ]));

        if ($request->hasFile('thumbnail_image')) {
            if ($event->thumbnail_image && ! str_starts_with($event->thumbnail_image, 'http')) {
                Storage::disk('public')->delete($event->thumbnail_image);
            }
            $event->thumbnail_image = $request->file('thumbnail_image')->store('events', 'public');
        }

        $event->save();

        return $this->jsonResponse(true, 'Agenda kegiatan berhasil diperbarui', new EventResource($event->load(['category', 'unit'])));
    }

    public function eventsDestroy(Request $request, int $id): JsonResponse
    {
        $event = Event::forUser($request->user())->find($id);

        if (! $event) {
            return $this->jsonResponse(false, 'Agenda tidak ditemukan atau di luar wewenang unit Anda', null, null, 404);
        }

        if ($event->thumbnail_image && ! str_starts_with($event->thumbnail_image, 'http')) {
            Storage::disk('public')->delete($event->thumbnail_image);
        }

        $event->delete();

        return $this->jsonResponse(true, 'Agenda kegiatan berhasil dihapus');
    }

    // ==========================================
    // 3. CRUD WARGA PPKS (Usulan Desa & Verifikasi)
    // ==========================================

    public function ppksIndex(Request $request): JsonResponse
    {
        $query = PpksBeneficiary::forUser($request->user())->with(['category', 'district', 'village', 'unit']);

        if ($request->filled('verification_status')) {
            $query->where('verification_status', $request->verification_status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $ppks = $query->latest()->paginate($request->get('per_page', 10));

        return $this->jsonResponse(
            true,
            'Data usulan warga PPKS berhasil diambil',
            PpksBeneficiaryResource::collection($ppks->items()),
            [
                'current_page' => $ppks->currentPage(),
                'per_page' => $ppks->perPage(),
                'total' => $ppks->total(),
                'last_page' => $ppks->lastPage(),
            ]
        );
    }

    public function ppksStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nik' => ['required', 'digits:16', 'unique:ppks_beneficiaries,nik'],
            'full_name' => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:ppks_categories,id'],
            'address_detail' => ['nullable', 'string'],
            'social_assistance_status' => ['nullable', 'string'],
            'mentor_unit' => ['nullable', 'string'],
            'last_survey_date' => ['nullable', 'date'],
        ]);

        $user = $request->user();
        $unit = $user->unit;

        $payload = [
            'nik' => $validated['nik'],
            'full_name' => $validated['full_name'],
            'category_id' => $validated['category_id'],
            'district_id' => $unit?->district_id,
            'village_id' => $unit?->village_id,
            'unit_id' => $unit?->id,
            'submitted_by' => $user->id,
            'address_detail' => $validated['address_detail'] ?? null,
            'social_assistance_status' => $validated['social_assistance_status'] ?? 'Usulan Baru Mandiri',
            'mentor_unit' => $validated['mentor_unit'] ?? ($unit ? $unit->unit_name : 'Karang Taruna'),
            'last_survey_date' => $validated['last_survey_date'] ?? now(),
            'verification_status' => ($user->isSuperadmin() || $user->isVerifikator()) ? 'verified' : 'pending_verification',
        ];

        if ($payload['verification_status'] === 'verified') {
            $payload['verified_by'] = $user->id;
            $payload['verified_at'] = now();
        }

        $ppks = PpksBeneficiary::create($payload);

        return $this->jsonResponse(true, 'Usulan data warga PPKS berhasil disimpan', new PpksBeneficiaryResource($ppks->load(['category', 'district', 'village', 'unit'])), null, 201);
    }

    public function ppksUpdate(Request $request, int $id): JsonResponse
    {
        $ppks = PpksBeneficiary::forUser($request->user())->find($id);

        if (! $ppks) {
            return $this->jsonResponse(false, 'Data PPKS tidak ditemukan atau di luar wewenang wilayah Anda', null, null, 404);
        }

        $validated = $request->validate([
            'nik' => ['sometimes', 'digits:16', 'unique:ppks_beneficiaries,nik,'.$id],
            'full_name' => ['sometimes', 'string', 'max:150'],
            'category_id' => ['sometimes', 'exists:ppks_categories,id'],
            'address_detail' => ['nullable', 'string'],
            'social_assistance_status' => ['nullable', 'string'],
            'mentor_unit' => ['nullable', 'string'],
            'last_survey_date' => ['nullable', 'date'],
            'verification_status' => ['nullable', 'in:pending_verification,verified,rejected'],
            'verification_notes' => ['nullable', 'string'],
        ]);

        $ppks->fill($request->only([
            'nik', 'full_name', 'category_id', 'address_detail',
            'social_assistance_status', 'mentor_unit', 'last_survey_date',
        ]));

        // Verifikasi hanya diizinkan untuk Superadmin & Verifikator Kabupaten
        $user = $request->user();
        if ($user->isSuperadmin() || $user->isVerifikator()) {
            if (isset($validated['verification_status'])) {
                $ppks->verification_status = $validated['verification_status'];
                if ($validated['verification_status'] === 'verified') {
                    $ppks->verified_by = $user->id;
                    $ppks->verified_at = now();
                }
            }
            if (array_key_exists('verification_notes', $validated)) {
                $ppks->verification_notes = $validated['verification_notes'];
            }
        }

        $ppks->save();

        return $this->jsonResponse(true, 'Data warga PPKS berhasil diperbarui', new PpksBeneficiaryResource($ppks->load(['category', 'district', 'village', 'unit'])));
    }

    public function ppksDestroy(Request $request, int $id): JsonResponse
    {
        $ppks = PpksBeneficiary::forUser($request->user())->find($id);

        if (! $ppks) {
            return $this->jsonResponse(false, 'Data PPKS tidak ditemukan atau di luar wewenang wilayah Anda', null, null, 404);
        }

        $ppks->delete();

        return $this->jsonResponse(true, 'Data warga PPKS berhasil dihapus');
    }

    // ==========================================
    // 4. CRUD STRUKTUR PENGURUS UNIT
    // ==========================================

    public function membersIndex(Request $request): JsonResponse
    {
        $user = $request->user();
        $unitId = $request->get('unit_id', $user->unit_id);

        $query = UnitMember::query()->orderBy('order_index');

        if ($unitId) {
            $query->where('unit_id', $unitId);
        } elseif (! $user->isSuperadmin()) {
            $query->where('unit_id', $user->unit_id ?? 0);
        }

        $members = $query->paginate($request->get('per_page', 20));

        return $this->jsonResponse(
            true,
            'Daftar pengurus unit berhasil diambil',
            UnitMemberResource::collection($members->items()),
            [
                'current_page' => $members->currentPage(),
                'per_page' => $members->perPage(),
                'total' => $members->total(),
                'last_page' => $members->lastPage(),
            ]
        );
    }

    public function membersStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'unit_id' => ['nullable', 'exists:karang_taruna_units,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'position_role' => ['required', 'string', 'max:255'],
            'division_section' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'order_index' => ['nullable', 'integer'],
            'photo' => ['nullable', 'image', 'max:512'],
        ]);

        $user = $request->user();
        $targetUnitId = ($user->isSuperadmin() && ! empty($validated['unit_id'])) ? $validated['unit_id'] : $user->unit_id;

        if (! $targetUnitId) {
            return $this->jsonResponse(false, 'Unit ID wajib ditentukan untuk pengurus ini', null, null, 422);
        }

        $payload = [
            'unit_id' => $targetUnitId,
            'full_name' => $validated['full_name'],
            'position_role' => $validated['position_role'],
            'division_section' => $validated['division_section'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'order_index' => $validated['order_index'] ?? 0,
            'is_active' => true,
        ];

        if ($request->hasFile('photo')) {
            $payload['photo_path'] = $request->file('photo')->store('members', 'public');
        }

        $member = UnitMember::create($payload);

        return $this->jsonResponse(true, 'Data pengurus berhasil ditambahkan', new UnitMemberResource($member), null, 201);
    }

    public function membersUpdate(Request $request, int $id): JsonResponse
    {
        $member = UnitMember::forUser($request->user())->find($id);

        if (! $member) {
            return $this->jsonResponse(false, 'Data pengurus tidak ditemukan atau di luar wewenang unit Anda', null, null, 404);
        }

        $validated = $request->validate([
            'full_name' => ['sometimes', 'string', 'max:255'],
            'position_role' => ['sometimes', 'string', 'max:255'],
            'division_section' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email'],
            'order_index' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'max:512'],
        ]);

        $member->fill($request->only([
            'full_name', 'position_role', 'division_section',
            'phone', 'email', 'order_index', 'is_active',
        ]));

        if ($request->hasFile('photo')) {
            if ($member->photo_path && ! str_starts_with($member->photo_path, 'http')) {
                Storage::disk('public')->delete($member->photo_path);
            }
            $member->photo_path = $request->file('photo')->store('members', 'public');
        }

        $member->save();

        return $this->jsonResponse(true, 'Data pengurus berhasil diperbarui', new UnitMemberResource($member));
    }

    public function membersDestroy(Request $request, int $id): JsonResponse
    {
        $member = UnitMember::forUser($request->user())->find($id);

        if (! $member) {
            return $this->jsonResponse(false, 'Data pengurus tidak ditemukan atau di luar wewenang unit Anda', null, null, 404);
        }

        if ($member->photo_path && ! str_starts_with($member->photo_path, 'http')) {
            Storage::disk('public')->delete($member->photo_path);
        }

        $member->delete();

        return $this->jsonResponse(true, 'Data pengurus berhasil dihapus');
    }

    // ==========================================
    // 5. PROFIL & UPDATE UNIT SENDIRI
    // ==========================================

    public function myUnit(Request $request): JsonResponse
    {
        $user = $request->user();
        $unit = $user->unit;

        if (! $unit) {
            return $this->jsonResponse(false, 'Akun Anda tidak terikat dengan unit Karang Taruna tertentu', null, null, 404);
        }

        return $this->jsonResponse(true, 'Data unit profil berhasil diambil', new UnitResource($unit->load(['district', 'village'])));
    }

    public function updateMyUnit(Request $request): JsonResponse
    {
        $user = $request->user();
        $unit = $user->unit;

        if (! $unit) {
            return $this->jsonResponse(false, 'Akun Anda tidak terikat dengan unit Karang Taruna tertentu', null, null, 404);
        }

        $validated = $request->validate([
            'chairman_name' => ['sometimes', 'string', 'max:255'],
            'secretary_name' => ['nullable', 'string', 'max:255'],
            'treasurer_name' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'contact_email' => ['nullable', 'email'],
            'office_address' => ['nullable', 'string'],
            'total_members' => ['nullable', 'integer'],
            'status_aktif' => ['nullable', 'in:Aktif,Demisioner,Restrukturisasi,PJS,Nonaktif'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'logo' => ['nullable', 'image', 'max:512'],
        ]);

        $unit->fill($request->only([
            'chairman_name', 'secretary_name', 'treasurer_name',
            'contact_phone', 'contact_email', 'office_address',
            'total_members', 'status_aktif', 'latitude', 'longitude',
        ]));

        if ($request->hasFile('logo')) {
            if ($unit->logo_path && ! str_starts_with($unit->logo_path, 'http')) {
                Storage::disk('public')->delete($unit->logo_path);
            }
            $unit->logo_path = $request->file('logo')->store('units/logos', 'public');
        }

        $unit->save();

        return $this->jsonResponse(true, 'Informasi unit berhasil diperbarui', new UnitResource($unit->load(['district', 'village'])));
    }
}
