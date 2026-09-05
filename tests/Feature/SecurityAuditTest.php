<?php

namespace Tests\Feature;

use App\Domain\Content\Models\Article;
use App\Domain\PPKS\Models\PpksBeneficiary;
use App\Models\User;
use App\Services\ApprovalWorkflowService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * 1. Test: Konten pending / draft tidak boleh bocor ke API atau Website Publik
     */
    public function test_pending_article_cannot_be_accessed_publicly(): void
    {
        $admin = User::first();
        $pendingArticle = Article::create([
            'title' => 'Draft Rahasia Internal',
            'slug' => 'draft-rahasia-internal',
            'category_id' => 1,
            'user_id' => $admin->id,
            'unit_id' => $admin->unit_id,
            'content' => '<p>Konten rahasia belum diverifikasi.</p>',
            'featured_image' => 'draft.jpg',
            'news_scope' => 'daerah',
            'approval_status' => 'pending_approval',
            'is_published' => false,
        ]);

        // Test web route
        $webResponse = $this->get('/berita/draft-rahasia-internal');
        $webResponse->assertStatus(404);

        // Test API v1 route
        $apiResponse = $this->getJson('/api/v1/news/draft-rahasia-internal');
        $apiResponse->assertStatus(404);
    }

    /**
     * 2. Test: Non-verifikator / Admin Desa tidak dapat mem-bypass eksekusi Approval
     */
    public function test_regular_user_or_desa_admin_cannot_approve_content(): void
    {
        $adminDesa = User::where('email', 'desa.soreang@karangtaruna.id')->first();
        $article = Article::first();

        $this->expectException(AuthorizationException::class);

        $workflowService = app(ApprovalWorkflowService::class);
        $workflowService->approveContent($article, $adminDesa, 'article');
    }

    /**
     * 3. Test: Tenant Scope Isolation (Admin Desa hanya dapat query data unitnya sendiri)
     */
    public function test_tenant_scope_isolation_for_village_admin(): void
    {
        $adminDesa = User::where('email', 'desa.soreang@karangtaruna.id')->first();

        // Query artikel menggunakan scopeForUser
        $scopedArticles = Article::forUser($adminDesa)->get();

        $this->assertNotNull($scopedArticles);
        foreach ($scopedArticles as $art) {
            $this->assertEquals($adminDesa->unit_id, $art->unit_id);
        }
    }

    /**
     * 4. Test: PPKS NIK brute-force protection with Captcha & Masking
     */
    public function test_ppks_search_requires_valid_captcha_and_masks_data(): void
    {
        $verifiedWarga = PpksBeneficiary::where('verification_status', 'verified')->first();

        // 1. Request tanpa captcha harus gagal validasi
        $failResponse = $this->post('/cek-ppks', [
            'nik' => $verifiedWarga->nik,
        ]);
        $failResponse->assertSessionHasErrors('captcha');

        // 2. Request dengan captcha valid
        session(['ppks_captcha_answer' => 12]);
        $successResponse = $this->post('/cek-ppks', [
            'nik' => $verifiedWarga->nik,
            'captcha' => 12,
        ]);

        $successResponse->assertStatus(200);
        // Pastikan NIK lengkap tidak pernah tampil utuh di HTML publik
        $successResponse->assertDontSee($verifiedWarga->nik);
        $successResponse->assertSee(substr($verifiedWarga->nik, 0, 6).'******'.substr($verifiedWarga->nik, -4));
    }
}
