<?php

namespace Tests\Feature;

use App\Domain\Content\Models\Article;
use App\Domain\Content\Models\NewsCategory;
use App\Domain\PPKS\Models\PpksBeneficiary;
use App\Domain\PPKS\Models\PpksCategory;
use App\Models\User;
use App\Services\ApprovalWorkflowService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentWorkflowLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test alur lengkap 1: Draft -> Submit -> Approve -> Published (dan tampil di web publik)
     */
    public function test_workflow_draft_to_submit_to_approve_to_published(): void
    {
        $adminDesa = User::where('email', 'desa.soreang@karangtaruna.id')->first();
        $superadmin = User::where('email', 'superadmin@karangtaruna.id')->first();
        $category = NewsCategory::first();

        // 1. Admin Desa membuat artikel dalam status draft
        $article = Article::create([
            'title' => 'Inisiatif Pengelolaan Sampah Mandiri',
            'slug' => 'inisiatif-pengelolaan-sampah-mandiri',
            'category_id' => $category->id,
            'user_id' => $adminDesa->id,
            'unit_id' => $adminDesa->unit_id,
            'content' => '<p>Program pemilahan sampah oleh pemuda desa.</p>',
            'featured_image' => 'articles/sample.jpg',
            'news_scope' => 'daerah',
            'approval_status' => 'draft',
            'is_published' => false,
        ]);

        $this->assertEquals('draft', $article->approval_status);
        $this->assertFalse($article->is_published);

        // Belum tampil di web publik
        $this->get('/berita/inisiatif-pengelolaan-sampah-mandiri')->assertStatus(404);

        // 2. Admin Desa mengajukan konten (Submit)
        $workflow = app(ApprovalWorkflowService::class);
        $submitted = $workflow->submitForApproval($article, $adminDesa, 'article');

        $this->assertTrue($submitted);
        $article->refresh();
        $this->assertEquals('pending_approval', $article->approval_status);

        // Masih 404 karena belum approved
        $this->get('/berita/inisiatif-pengelolaan-sampah-mandiri')->assertStatus(404);

        // 3. Superadmin / Verifikator menyetujui konten (Approve)
        $approved = $workflow->approveContent($article, $superadmin, 'article', 'Disetujui untuk portal resmi.');

        $this->assertTrue($approved);
        $article->refresh();
        $this->assertEquals('approved', $article->approval_status);
        $this->assertTrue($article->is_published);
        $this->assertNotNull($article->published_at);
        $this->assertEquals($superadmin->id, $article->approved_by);

        // 4. Konten kini dapat diakses di portal publik & API v1
        $webRes = $this->get('/berita/inisiatif-pengelolaan-sampah-mandiri');
        $webRes->assertStatus(200);
        $webRes->assertSee('Inisiatif Pengelolaan Sampah Mandiri');

        $apiRes = $this->getJson('/api/v1/news/inisiatif-pengelolaan-sampah-mandiri');
        $apiRes->assertStatus(200);
        $apiRes->assertJsonPath('data.title', 'Inisiatif Pengelolaan Sampah Mandiri');
    }

    /**
     * Test alur lengkap 2: Draft -> Submit -> Revision Required -> Resubmit -> Approve
     */
    public function test_workflow_draft_to_revision_to_resubmit_to_approve(): void
    {
        $adminDesa = User::where('email', 'desa.soreang@karangtaruna.id')->first();
        $superadmin = User::where('email', 'superadmin@karangtaruna.id')->first();
        $category = NewsCategory::first();
        $workflow = app(ApprovalWorkflowService::class);

        // 1. Create and submit
        $article = Article::create([
            'title' => 'Festival Musik Pemuda Desa',
            'slug' => 'festival-musik-pemuda-desa',
            'category_id' => $category->id,
            'user_id' => $adminDesa->id,
            'unit_id' => $adminDesa->unit_id,
            'content' => '<p>Deskripsi singkat acara.</p>',
            'featured_image' => 'articles/festival.jpg',
            'news_scope' => 'daerah',
            'approval_status' => 'draft',
            'is_published' => false,
        ]);

        $workflow->submitForApproval($article, $adminDesa, 'article');
        $article->refresh();
        $this->assertEquals('pending_approval', $article->approval_status);

        // 2. Verifikator meminta revisi
        $notes = 'Mohon lengkapi jadwal waktu pelaksanaan dan susunan kepanitiaan.';
        $revised = $workflow->requestRevision($article, $superadmin, 'article', $notes);

        $this->assertTrue($revised);
        $article->refresh();
        $this->assertEquals('revision_required', $article->approval_status);
        $this->assertEquals($notes, $article->rejection_reason);

        // 3. Admin Desa memperbaiki konten lalu mengajukan ulang (Resubmit)
        $article->update([
            'content' => '<p>Deskripsi lengkap beserta jadwal dan susunan panitia.</p>',
        ]);

        $resubmitted = $workflow->submitForApproval($article, $adminDesa, 'article');
        $this->assertTrue($resubmitted);
        $article->refresh();
        $this->assertEquals('pending_approval', $article->approval_status);

        // 4. Verifikator menyetujui
        $approved = $workflow->approveContent($article, $superadmin, 'article');
        $this->assertTrue($approved);
        $article->refresh();
        $this->assertEquals('approved', $article->approval_status);
        $this->assertTrue($article->is_published);
    }

    /**
     * Test alur lengkap 3: PPKS Verification Lifecycle
     */
    public function test_ppks_verification_workflow(): void
    {
        $adminDesa = User::where('email', 'desa.soreang@karangtaruna.id')->first();
        $superadmin = User::where('email', 'superadmin@karangtaruna.id')->first();
        $category = PpksCategory::first();

        // 1. Admin Desa mengusulkan data warga PPKS
        $beneficiary = PpksBeneficiary::create([
            'nik' => '3204052308900009',
            'full_name' => 'Budi Setiawan',
            'category_id' => $category->id,
            'district_id' => $adminDesa->unit->district_id,
            'village_id' => $adminDesa->unit->village_id,
            'unit_id' => $adminDesa->unit_id,
            'submitted_by' => $adminDesa->id,
            'address_detail' => 'Kp. Cijagra RT 01 RW 03',
            'social_assistance_status' => 'Usulan Bantuan PKH',
            'verification_status' => 'pending_verification',
        ]);

        $this->assertEquals('pending_verification', $beneficiary->verification_status);

        // 2. Sebelum diverifikasi, NIK tidak ditemukan di halaman cek publik
        session(['ppks_captcha_answer' => 10]);
        $searchResBefore = $this->post('/cek-ppks', [
            'nik' => '3204052308900009',
            'captcha' => 10,
        ]);
        $searchResBefore->assertStatus(200);
        $searchResBefore->assertSee('NIK belum terdaftar dalam Basis Data PPKS');

        // 3. Verifikator memverifikasi data warga
        $beneficiary->update([
            'verification_status' => 'verified',
            'verified_by' => $superadmin->id,
            'verified_at' => now(),
        ]);

        // 4. Setelah diverifikasi, NIK muncul di pencarian publik (dengan data tersamar)
        session(['ppks_captcha_answer' => 10]);
        $searchResAfter = $this->post('/cek-ppks', [
            'nik' => '3204052308900009',
            'captcha' => 10,
        ]);
        $searchResAfter->assertStatus(200);
        $searchResAfter->assertSee('DATA DITEMUKAN', false);
        $searchResAfter->assertSee('320405******0009');
        $searchResAfter->assertSee('Bud**********');
    }
}
