<?php

namespace Tests\Feature;

use App\Domain\Content\Models\Achievement;
use App\Domain\Content\Models\Article;
use App\Domain\Content\Models\Event;
use App\Domain\Content\Models\EventCategory;
use App\Domain\Content\Models\NewsCategory;
use App\Models\User;
use App\Services\ApprovalWorkflowService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComprehensiveQALifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * 1. QA End-to-End Flow: Berita (Draft -> Submit -> Revision -> Edit -> Resubmit -> Approve -> Live)
     */
    public function test_qa_article_lifecycle(): void
    {
        $adminDesa = User::where('email', 'desa.soreang@karangtaruna.id')->first();
        $verifikator = User::where('email', 'superadmin@karangtaruna.id')->first();
        $category = NewsCategory::first();
        $workflow = app(ApprovalWorkflowService::class);

        // A. Create Draft
        $article = Article::create([
            'title' => 'Liputan Gotong Royong Pemuda Desa',
            'slug' => 'liputan-gotong-royong-pemuda-desa',
            'category_id' => $category->id,
            'user_id' => $adminDesa->id,
            'unit_id' => $adminDesa->unit_id,
            'content' => '<p>Gotong royong pembersihan saluran air.</p>',
            'featured_image' => 'articles/gotong-royong.jpg',
            'news_scope' => 'daerah',
            'approval_status' => 'draft',
            'is_published' => false,
        ]);

        $this->get('/berita/liputan-gotong-royong-pemuda-desa')->assertStatus(404);

        // B. Submit
        $workflow->submitForApproval($article, $adminDesa, 'article');
        $article->refresh();
        $this->assertEquals('pending_approval', $article->approval_status);

        // C. Request Revision
        $workflow->requestRevision($article, $verifikator, 'article', 'Tolong tambahkan foto sebelum dan sesudah kegiatan.');
        $article->refresh();
        $this->assertEquals('revision_required', $article->approval_status);
        $this->assertStringContainsString('sebelum dan sesudah', $article->rejection_reason);

        // D. Edit & Resubmit
        $article->update([
            'content' => '<p>Gotong royong pembersihan saluran air lengkap dengan dokumentasi foto.</p>',
        ]);
        $workflow->submitForApproval($article, $adminDesa, 'article');
        $article->refresh();
        $this->assertEquals('pending_approval', $article->approval_status);

        // E. Approve
        $workflow->approveContent($article, $verifikator, 'article', 'Lengkap dan siap terbit.');
        $article->refresh();
        $this->assertEquals('approved', $article->approval_status);
        $this->assertTrue($article->is_published);

        // F. Verify Live in Web & API
        $this->get('/berita/liputan-gotong-royong-pemuda-desa')
            ->assertStatus(200)
            ->assertSee('Liputan Gotong Royong Pemuda Desa');

        $this->getJson('/api/v1/news/liputan-gotong-royong-pemuda-desa')
            ->assertStatus(200)
            ->assertJsonPath('data.title', 'Liputan Gotong Royong Pemuda Desa');
    }

    /**
     * 2. QA End-to-End Flow: Agenda Kegiatan (Draft -> Submit -> Approve -> Live)
     */
    public function test_qa_event_lifecycle(): void
    {
        $adminKecamatan = User::where('email', 'kecamatan.soreang@karangtaruna.id')->first();
        $verifikator = User::where('email', 'superadmin@karangtaruna.id')->first();
        $category = EventCategory::first();
        $workflow = app(ApprovalWorkflowService::class);

        // A. Create Draft Event
        $event = Event::create([
            'title' => 'Turnamen Futsal Pemuda Antar Desa',
            'slug' => 'turnamen-futsal-pemuda-antar-desa',
            'category_id' => $category->id,
            'user_id' => $adminKecamatan->id,
            'unit_id' => $adminKecamatan->unit_id,
            'district_id' => $adminKecamatan->unit->district_id,
            'event_date' => now()->addDays(14),
            'start_time' => '08:00:00',
            'end_time' => '17:00:00',
            'location_venue' => 'GOR Soreang',
            'organizer' => 'Karang Taruna Kec. Soreang',
            'event_status' => 'Mendatang',
            'approval_status' => 'draft',
        ]);

        // B. Submit
        $workflow->submitForApproval($event, $adminKecamatan, 'event');
        $event->refresh();
        $this->assertEquals('pending_approval', $event->approval_status);

        // C. Approve
        $workflow->approveContent($event, $verifikator, 'event');
        $event->refresh();
        $this->assertEquals('approved', $event->approval_status);

        // D. Verify Live on Public Agenda page
        $this->get('/agenda')
            ->assertStatus(200)
            ->assertSee('Turnamen Futsal Pemuda Antar Desa');

        // E. Verify Live in API v1
        $this->getJson('/api/v1/events')
            ->assertStatus(200)
            ->assertSee('Turnamen Futsal Pemuda Antar Desa');
    }

    /**
     * 3. QA End-to-End Flow: Prestasi Pemuda (Draft -> Submit -> Approve -> Live)
     */
    public function test_qa_achievement_lifecycle(): void
    {
        $adminDesa = User::where('email', 'desa.soreang@karangtaruna.id')->first();
        $verifikator = User::where('email', 'superadmin@karangtaruna.id')->first();
        $workflow = app(ApprovalWorkflowService::class);

        // A. Create Draft Achievement
        $achievement = Achievement::create([
            'title' => 'Juara 1 Lomba Inovasi Agrobisnis Digital',
            'slug' => 'juara-1-lomba-inovasi-agrobisnis-digital',
            'recipient_name' => 'Rizky Pratama',
            'achievement_level' => 'Provinsi',
            'category_field' => 'Pertanian & Agroteknologi',
            'year' => 2026,
            'rank_position' => 'Juara 1',
            'awarded_by' => 'Dinas Tanaman Pangan dan Hortikultura Jabar',
            'unit_id' => $adminDesa->unit_id,
            'user_id' => $adminDesa->id,
            'district_id' => $adminDesa->unit->district_id,
            'approval_status' => 'draft',
        ]);

        // B. Submit
        $workflow->submitForApproval($achievement, $adminDesa, 'achievement');
        $achievement->refresh();
        $this->assertEquals('pending_approval', $achievement->approval_status);

        // C. Approve
        $workflow->approveContent($achievement, $verifikator, 'achievement');
        $achievement->refresh();
        $this->assertEquals('approved', $achievement->approval_status);

        // D. Verify Live on Public Prestasi page
        $this->get('/prestasi')
            ->assertStatus(200)
            ->assertSee('Juara 1 Lomba Inovasi Agrobisnis Digital');

        // E. Verify Live in API v1
        $this->getJson('/api/v1/achievements')
            ->assertStatus(200)
            ->assertSee('Juara 1 Lomba Inovasi Agrobisnis Digital');
    }

    /**
     * 4. QA Public Pages Responsiveness & Accessibility (Semua Halaman Publik 200 OK)
     */
    public function test_all_public_pages_return_successful_response(): void
    {
        $routes = [
            '/',
            '/tentang-kami',
            '/berita',
            '/agenda',
            '/program-kerja',
            '/pengumuman',
            '/prestasi',
            '/galeri-foto',
            '/galeri-video',
            '/unduhan',
            '/direktori',
            '/peta-sebaran',
            '/cek-ppks',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }
}
