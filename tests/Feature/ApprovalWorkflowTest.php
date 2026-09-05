<?php

namespace Tests\Feature;

use App\Domain\Content\Models\Article;
use App\Domain\Content\Models\ContentApprovalLog;
use App\Domain\Content\Models\NewsCategory;
use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Territory\Models\RefVillage;
use App\Domain\Units\Models\KarangTarunaUnit;
use App\Models\User;
use App\Services\ApprovalWorkflowService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_approval_workflow_lifecycle(): void
    {
        $this->seed(DatabaseSeeder::class);

        $workflowService = app(ApprovalWorkflowService::class);
        $userDesa = User::where('email', 'desa.soreang@karangtaruna.id')->first();
        $verifikator = User::where('email', 'verifikator@karangtaruna.id')->first();
        $category = NewsCategory::first();
        $district = RefDistrict::where('name', 'Soreang')->first();
        $village = RefVillage::where('name', 'Soreang')->first();
        $unit = KarangTarunaUnit::where('unit_code', 'KT-DES-SOR-01')->first();

        // 1. Admin Desa membuat draft artikel
        $article = Article::create([
            'category_id' => $category->id,
            'user_id' => $userDesa->id,
            'unit_id' => $unit->id,
            'district_id' => $district->id,
            'village_id' => $village->id,
            'title' => 'Pelatihan Digital Pemuda Soreang',
            'slug' => 'pelatihan-digital-pemuda-soreang',
            'content' => 'Konten pelatihan digital...',
            'featured_image' => 'articles/training.jpg',
            'approval_status' => 'draft',
            'is_published' => false,
        ]);

        $this->assertEquals('draft', $article->approval_status);
        $this->assertFalse($article->is_published);

        // 2. Submit for Approval
        $workflowService->submitForApproval($article, $userDesa, 'article');
        $article->refresh();
        $this->assertEquals('pending_approval', $article->approval_status);

        // Assert Log
        $this->assertDatabaseHas('content_approval_logs', [
            'content_type' => 'article',
            'content_id' => $article->id,
            'submitted_by' => $userDesa->id,
            'action_status' => 'submitted',
        ]);

        // 3. Verifikator minta revisi (Request Revision)
        $workflowService->requestRevision($article, $verifikator, 'article', 'Mohon lengkapi narasumber dan dokumentasi foto.');
        $article->refresh();
        $this->assertEquals('revision_required', $article->approval_status);
        $this->assertEquals('Mohon lengkapi narasumber dan dokumentasi foto.', $article->rejection_reason);

        // 4. Admin Desa memperbaiki dan Submit Ulang (Resubmit)
        $article->update(['content' => 'Konten pelatihan lengkap dengan narasumber...']);
        $workflowService->submitForApproval($article, $userDesa, 'article');
        $article->refresh();
        $this->assertEquals('pending_approval', $article->approval_status);
        $this->assertNull($article->rejection_reason);

        // 5. Verifikator Setujui (Approve)
        $workflowService->approveContent($article, $verifikator, 'article', 'Disetujui. Siap publikasi.');
        $article->refresh();
        $this->assertEquals('approved', $article->approval_status);
        $this->assertTrue($article->is_published);
        $this->assertNotNull($article->approved_at);
        $this->assertEquals($verifikator->id, $article->approved_by);

        // 6. Assert Seluruh Log Tercatat Lengkap
        $this->assertEquals(4, ContentApprovalLog::where('content_id', $article->id)->count());
    }
}
