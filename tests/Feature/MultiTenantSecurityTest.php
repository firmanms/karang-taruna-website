<?php

namespace Tests\Feature;

use App\Domain\Content\Models\Article;
use App\Domain\Content\Models\NewsCategory;
use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Territory\Models\RefVillage;
use App\Domain\Units\Models\KarangTarunaUnit;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class MultiTenantSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_desa_a_cannot_access_or_manage_desa_b_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        $adminDesaRole = Role::where('slug', 'admin-desa')->first();
        $newsCategory = NewsCategory::first();

        // 1. Setup Desa A
        $district = RefDistrict::where('name', 'Soreang')->first();
        $desaA = RefVillage::where('name', 'Soreang')->first();
        $unitDesaA = KarangTarunaUnit::where('unit_code', 'KT-DES-SOR-01')->first();

        $userDesaA = User::where('email', 'desa.soreang@karangtaruna.id')->first();

        // 2. Setup Desa B
        $desaB = RefVillage::create([
            'district_id' => $district->id,
            'kemendagri_code' => '32.04.05.2003',
            'name' => 'Sukajadi',
            'slug' => 'sukajadi',
            'type' => 'Desa',
        ]);

        $unitDesaB = KarangTarunaUnit::create([
            'unit_level' => 'desa',
            'district_id' => $district->id,
            'village_id' => $desaB->id,
            'unit_name' => 'Karang Taruna Tunas Harapan Desa Sukajadi',
            'unit_code' => 'KT-DES-SUK-01',
            'chairman_name' => 'Ketua Desa Sukajadi',
            'period_start_year' => 2024,
            'period_end_year' => 2029,
        ]);

        $userDesaB = User::create([
            'name' => 'Admin Desa Sukajadi',
            'email' => 'desa.sukajadi@karangtaruna.id',
            'password' => bcrypt('password'),
            'role_id' => $adminDesaRole->id,
            'unit_id' => $unitDesaB->id,
            'is_active' => true,
        ]);

        // 3. Buat Artikel milik Desa B
        $articleDesaB = Article::create([
            'category_id' => $newsCategory->id,
            'user_id' => $userDesaB->id,
            'unit_id' => $unitDesaB->id,
            'district_id' => $district->id,
            'village_id' => $desaB->id,
            'title' => 'Kegiatan Bakti Desa Sukajadi',
            'slug' => 'kegiatan-bakti-desa-sukajadi',
            'content' => 'Laporan kegiatan desa...',
            'featured_image' => 'articles/default.jpg',
            'approval_status' => 'draft',
        ]);

        // 4. Test Policy Server-Side Authorization
        // Admin Desa A TIDAK BOLEH update / delete artikel Desa B
        $this->assertFalse(Gate::forUser($userDesaA)->allows('update', $articleDesaB));
        $this->assertFalse(Gate::forUser($userDesaA)->allows('delete', $articleDesaB));

        // Admin Desa B BOLEH update artikel Desa B
        $this->assertTrue(Gate::forUser($userDesaB)->allows('update', $articleDesaB));

        // Superadmin BOLEH mengelola artikel Desa B
        $superadmin = User::where('email', 'superadmin@karangtaruna.id')->first();
        $this->assertTrue(Gate::forUser($superadmin)->allows('update', $articleDesaB));

        // 5. Test Query Scope Isolasi Tenant
        // Ketika User Desa A meminta list artikel scoped, artikel Desa B TIDAK BOLEH muncul
        $scopedArticlesForDesaA = Article::forUser($userDesaA)->pluck('id');
        $this->assertNotContains($articleDesaB->id, $scopedArticlesForDesaA);

        // Ketika User Desa B meminta list artikel scoped, artikel Desa B HARUS muncul
        $scopedArticlesForDesaB = Article::forUser($userDesaB)->pluck('id');
        $this->assertContains($articleDesaB->id, $scopedArticlesForDesaB);
    }
}
