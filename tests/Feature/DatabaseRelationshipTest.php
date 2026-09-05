<?php

namespace Tests\Feature;

use App\Domain\PPKS\Models\PpksCategory;
use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Units\Models\KarangTarunaUnit;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_relationships_and_seeder_data_are_accessible(): void
    {
        $this->seed(DatabaseSeeder::class);

        // 1. Test Roles & Users
        $superadmin = User::where('email', 'superadmin@karangtaruna.id')->first();
        $this->assertNotNull($superadmin);
        $this->assertInstanceOf(Role::class, $superadmin->role);
        $this->assertEquals('superadmin', $superadmin->role->slug);
        $this->assertTrue($superadmin->isSuperadmin());

        // 2. Test Districts & Villages
        $soreangDistrict = RefDistrict::where('name', 'Soreang')->first();
        $this->assertNotNull($soreangDistrict);
        $this->assertGreaterThanOrEqual(1, $soreangDistrict->villages()->count());
        $village = $soreangDistrict->villages()->first();
        $this->assertEquals($soreangDistrict->id, $village->district->id);

        // 3. Test Karang Taruna Units
        $unitKecamatan = KarangTarunaUnit::where('unit_code', 'KT-KEC-SOR')->first();
        $this->assertNotNull($unitKecamatan);
        $this->assertEquals('kecamatan', $unitKecamatan->unit_level);
        $this->assertEquals($soreangDistrict->id, $unitKecamatan->district->id);

        // 4. Test 26 PPKS Categories
        $this->assertEquals(26, PpksCategory::count());

        // 5. Test 31 Districts
        $this->assertEquals(31, RefDistrict::count());
    }
}
