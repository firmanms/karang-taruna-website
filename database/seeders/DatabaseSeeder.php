<?php

namespace Database\Seeders;

use App\Domain\Content\Models\EventCategory;
use App\Domain\Content\Models\NewsCategory;
use App\Domain\Content\Models\ProgramDivision;
use App\Domain\PPKS\Models\PpksCategory;
use App\Domain\Settings\Models\ProfileOrganization;
use App\Domain\Settings\Models\SiteSetting;
use App\Domain\Territory\Models\RefDistrict;
use App\Domain\Territory\Models\RefVillage;
use App\Domain\Units\Models\KarangTarunaUnit;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $superadminRole = Role::create([
            'role_name' => 'Superadmin Kabupaten',
            'slug' => 'superadmin',
            'tier_level' => 'kabupaten',
            'permissions' => ['*'],
        ]);

        $verifikatorRole = Role::create([
            'role_name' => 'Verifikator Kabupaten',
            'slug' => 'verifikator',
            'tier_level' => 'kabupaten',
            'permissions' => ['approve_content', 'view_all'],
        ]);

        $adminKecamatanRole = Role::create([
            'role_name' => 'Admin Kecamatan',
            'slug' => 'admin-kecamatan',
            'tier_level' => 'kecamatan',
            'permissions' => ['manage_district_unit', 'submit_content'],
        ]);

        $adminDesaRole = Role::create([
            'role_name' => 'Admin Desa',
            'slug' => 'admin-desa',
            'tier_level' => 'desa',
            'permissions' => ['manage_village_unit', 'submit_content', 'submit_ppks'],
        ]);

        Role::create([
            'role_name' => 'Kontributor Berita',
            'slug' => 'kontributor',
            'tier_level' => 'desa',
            'permissions' => ['submit_articles'],
        ]);

        // 2. Seed 31 Kecamatan di Kabupaten Bandung
        $districts = [
            ['code' => '32.04.05', 'name' => 'Soreang', 'lat' => -7.0279, 'lng' => 107.5186],
            ['code' => '32.04.06', 'name' => 'Pasirjambu', 'lat' => -7.0984, 'lng' => 107.4789],
            ['code' => '32.04.07', 'name' => 'Ciwidey', 'lat' => -7.0877, 'lng' => 107.4581],
            ['code' => '32.04.08', 'name' => 'Rancabali', 'lat' => -7.1422, 'lng' => 107.3827],
            ['code' => '32.04.09', 'name' => 'Cangkuang', 'lat' => -7.0494, 'lng' => 107.5482],
            ['code' => '32.04.10', 'name' => 'Banjaran', 'lat' => -7.0458, 'lng' => 107.5878],
            ['code' => '32.04.11', 'name' => 'Pameungpeuk', 'lat' => -7.0136, 'lng' => 107.5861],
            ['code' => '32.04.12', 'name' => 'Arjasari', 'lat' => -7.0678, 'lng' => 107.6169],
            ['code' => '32.04.13', 'name' => 'Cimaung', 'lat' => -7.0861, 'lng' => 107.5503],
            ['code' => '32.04.14', 'name' => 'Pangalengan', 'lat' => -7.1764, 'lng' => 107.5683],
            ['code' => '32.04.15', 'name' => 'Kertasari', 'lat' => -7.2144, 'lng' => 107.6789],
            ['code' => '32.04.16', 'name' => 'Pacet', 'lat' => -7.0833, 'lng' => 107.7167],
            ['code' => '32.04.17', 'name' => 'Ibun', 'lat' => -7.1167, 'lng' => 107.7833],
            ['code' => '32.04.18', 'name' => 'Paseh', 'lat' => -7.0667, 'lng' => 107.7667],
            ['code' => '32.04.19', 'name' => 'Cikancung', 'lat' => -7.0167, 'lng' => 107.8167],
            ['code' => '32.04.25', 'name' => 'Baleendah', 'lat' => -6.9939, 'lng' => 107.6256],
            ['code' => '32.04.26', 'name' => 'Dayeuhkolot', 'lat' => -6.9842, 'lng' => 107.6189],
            ['code' => '32.04.27', 'name' => 'Bojongsoang', 'lat' => -6.9781, 'lng' => 107.6433],
            ['code' => '32.04.28', 'name' => 'Margahayu', 'lat' => -6.9744, 'lng' => 107.5689],
            ['code' => '32.04.29', 'name' => 'Margaasih', 'lat' => -6.9458, 'lng' => 107.5458],
            ['code' => '32.04.30', 'name' => 'Katapang', 'lat' => -7.0094, 'lng' => 107.5583],
            ['code' => '32.04.31', 'name' => 'Kutawaringin', 'lat' => -6.9833, 'lng' => 107.5167],
            ['code' => '32.04.32', 'name' => 'Ciparay', 'lat' => -7.0333, 'lng' => 107.7000],
            ['code' => '32.04.33', 'name' => 'Majalaya', 'lat' => -7.0500, 'lng' => 107.7500],
            ['code' => '32.04.34', 'name' => 'Solokanjeruk', 'lat' => -7.0167, 'lng' => 107.7333],
            ['code' => '32.04.35', 'name' => 'Rancaekek', 'lat' => -6.9667, 'lng' => 107.7667],
            ['code' => '32.04.36', 'name' => 'Cileunyi', 'lat' => -6.9333, 'lng' => 107.7333],
            ['code' => '32.04.37', 'name' => 'Cimenyan', 'lat' => -6.8667, 'lng' => 107.6667],
            ['code' => '32.04.38', 'name' => 'Cilengkrang', 'lat' => -6.8833, 'lng' => 107.7167],
            ['code' => '32.04.39', 'name' => 'Nagreg', 'lat' => -7.0333, 'lng' => 107.8833],
            ['code' => '32.04.40', 'name' => 'Cicalengka', 'lat' => -6.9833, 'lng' => 107.8333],
        ];

        $districtMap = [];
        foreach ($districts as $d) {
            $districtMap[$d['name']] = RefDistrict::create([
                'kemendagri_code' => $d['code'],
                'name' => $d['name'],
                'slug' => Str::slug($d['name']),
                'latitude_center' => $d['lat'],
                'longitude_center' => $d['lng'],
            ]);
        }

        // Contoh Seeding Desa untuk Soreang
        $soreang = $districtMap['Soreang'];
        $desaPanyirapan = RefVillage::create([
            'district_id' => $soreang->id,
            'kemendagri_code' => '32.04.05.2001',
            'name' => 'Panyirapan',
            'slug' => 'panyirapan',
            'type' => 'Desa',
            'postal_code' => '40911',
            'total_rw' => 12,
            'total_rt' => 48,
            'latitude_center' => -7.0312,
            'longitude_center' => 107.5190,
        ]);

        $desaSoreang = RefVillage::create([
            'district_id' => $soreang->id,
            'kemendagri_code' => '32.04.05.2002',
            'name' => 'Soreang',
            'slug' => 'soreang',
            'type' => 'Desa',
            'postal_code' => '40911',
            'total_rw' => 15,
            'total_rt' => 60,
            'latitude_center' => -7.0279,
            'longitude_center' => 107.5186,
        ]);

        // 3. Seed Master Lembaga Karang Taruna
        $unitKabupaten = KarangTarunaUnit::create([
            'unit_level' => 'kabupaten',
            'unit_name' => 'Karang Taruna Kabupaten Bandung',
            'unit_code' => 'KT-KAB-BDG',
            'chairman_name' => 'Ketua Karang Taruna Kabupaten',
            'secretary_name' => 'Sekretaris Kabupaten',
            'treasurer_name' => 'Bendahara Kabupaten',
            'contact_phone' => '081234567890',
            'contact_email' => 'info@karangtarunabandungkab.or.id',
            'office_address' => 'Jl. Raya Soreang No. 123, Soreang, Kabupaten Bandung',
            'latitude' => -7.0279,
            'longitude' => 107.5186,
            'period_start_year' => 2024,
            'period_end_year' => 2029,
            'total_members' => 150,
            'status_aktif' => 'Aktif',
            'is_verified' => true,
        ]);

        $unitKecamatanSoreang = KarangTarunaUnit::create([
            'unit_level' => 'kecamatan',
            'district_id' => $soreang->id,
            'unit_name' => 'Karang Taruna Kecamatan Soreang',
            'unit_code' => 'KT-KEC-SOR',
            'chairman_name' => 'Ketua KT Soreang',
            'period_start_year' => 2024,
            'period_end_year' => 2029,
            'total_members' => 45,
            'status_aktif' => 'Aktif',
            'is_verified' => true,
        ]);

        $unitDesaSoreang = KarangTarunaUnit::create([
            'unit_level' => 'desa',
            'district_id' => $soreang->id,
            'village_id' => $desaSoreang->id,
            'unit_name' => 'Karang Taruna Satria Muda Desa Soreang',
            'unit_code' => 'KT-DES-SOR-01',
            'chairman_name' => 'Ketua KT Desa Soreang',
            'period_start_year' => 2024,
            'period_end_year' => 2029,
            'total_members' => 30,
            'status_aktif' => 'Aktif',
            'is_verified' => true,
        ]);

        // 4. Seed Default Users
        User::create([
            'name' => 'Superadmin Kabupaten Bandung',
            'email' => 'superadmin@karangtaruna.id',
            'password' => Hash::make('password'),
            'role_id' => $superadminRole->id,
            'unit_id' => $unitKabupaten->id,
            'can_auto_publish' => true,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Verifikator Kabupaten',
            'email' => 'verifikator@karangtaruna.id',
            'password' => Hash::make('password'),
            'role_id' => $verifikatorRole->id,
            'unit_id' => $unitKabupaten->id,
            'can_auto_publish' => false,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Kecamatan Soreang',
            'email' => 'kecamatan.soreang@karangtaruna.id',
            'password' => Hash::make('password'),
            'role_id' => $adminKecamatanRole->id,
            'unit_id' => $unitKecamatanSoreang->id,
            'can_auto_publish' => false,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin Desa Soreang',
            'email' => 'desa.soreang@karangtaruna.id',
            'password' => Hash::make('password'),
            'role_id' => $adminDesaRole->id,
            'unit_id' => $unitDesaSoreang->id,
            'can_auto_publish' => false,
            'is_active' => true,
        ]);

        // 5. Seed 26 Jenis Kategori PPKS (Kemensos)
        $ppksCategories = [
            ['code' => 'PPKS-01', 'name' => 'Anak Balita Telantar'],
            ['code' => 'PPKS-02', 'name' => 'Anak Telantar'],
            ['code' => 'PPKS-03', 'name' => 'Anak yang Berhadapan dengan Hukum'],
            ['code' => 'PPKS-04', 'name' => 'Anak Jalanan'],
            ['code' => 'PPKS-05', 'name' => 'Anak dengan Kedisabilitasan'],
            ['code' => 'PPKS-06', 'name' => 'Anak Korban Tindak Kekerasan'],
            ['code' => 'PPKS-07', 'name' => 'Anak yang Memerlukan Perlindungan Khusus'],
            ['code' => 'PPKS-08', 'name' => 'Lanjut Usia Telantar'],
            ['code' => 'PPKS-09', 'name' => 'Penyandang Disabilitas'],
            ['code' => 'PPKS-10', 'name' => 'Tuna Susila'],
            ['code' => 'PPKS-11', 'name' => 'Gelandangan'],
            ['code' => 'PPKS-12', 'name' => 'Pengemis'],
            ['code' => 'PPKS-13', 'name' => 'Pemulung'],
            ['code' => 'PPKS-14', 'name' => 'Kelompok Minoritas'],
            ['code' => 'PPKS-15', 'name' => 'Bekas Warga Binaan Pemasyarakatan (BWBP)'],
            ['code' => 'PPKS-16', 'name' => 'Orang dengan HIV/AIDS (ODHA)'],
            ['code' => 'PPKS-17', 'name' => 'Korban Penyalahgunaan NAPZA'],
            ['code' => 'PPKS-18', 'name' => 'Korban Trafficking'],
            ['code' => 'PPKS-19', 'name' => 'Korban Tindak Kekerasan'],
            ['code' => 'PPKS-20', 'name' => 'Pekerja Migran Bermasalah Sosial'],
            ['code' => 'PPKS-21', 'name' => 'Korban Bencana Alam'],
            ['code' => 'PPKS-22', 'name' => 'Korban Bencana Sosial'],
            ['code' => 'PPKS-23', 'name' => 'Perempuan Rawan Sosial Ekonomi'],
            ['code' => 'PPKS-24', 'name' => 'Fakir Miskin'],
            ['code' => 'PPKS-25', 'name' => 'Keluarga Bermasalah Sosial Psikologis'],
            ['code' => 'PPKS-26', 'name' => 'Komunitas Adat Terpencil'],
        ];

        foreach ($ppksCategories as $cat) {
            PpksCategory::create([
                'category_code' => $cat['code'],
                'category_name' => $cat['name'],
            ]);
        }

        // 6. Seed Taksonomi Master Lainnya
        NewsCategory::create(['name' => 'Berita Utama', 'slug' => 'berita-utama', 'type' => 'pusat']);
        NewsCategory::create(['name' => 'Kabar Wilayah', 'slug' => 'kabar-wilayah', 'type' => 'daerah']);
        NewsCategory::create(['name' => 'Sosial & Kepemudaan', 'slug' => 'sosial-kepemudaan', 'type' => 'umum']);

        EventCategory::create(['name' => 'Pelatihan & Workshop', 'slug' => 'pelatihan-workshop']);
        EventCategory::create(['name' => 'Bakti Sosial & Gotong Royong', 'slug' => 'bakti-sosial']);
        EventCategory::create(['name' => 'Olahraga & Seni Budaya', 'slug' => 'olahraga-seni']);

        ProgramDivision::create(['division_name' => 'Pemberdayaan Pemuda & Olahraga', 'slug' => 'pemuda-olahraga', 'order_index' => 1]);
        ProgramDivision::create(['division_name' => 'Sosial & Pengabdian Masyarakat', 'slug' => 'sosial-pengabdian', 'order_index' => 2]);
        ProgramDivision::create(['division_name' => 'Ekonomi Kreatif & Kewirausahaan', 'slug' => 'ekonomi-kreatif', 'order_index' => 3]);

        // 7. Seed Profil & Site Settings
        ProfileOrganization::create([
            'org_name' => 'Karang Taruna Kabupaten Bandung',
            'legal_basis' => 'Permensos No. 25 Tahun 2019 tentang Karang Taruna',
            'vision' => 'Terwujudnya Generasi Muda Kabupaten Bandung yang Berkarakter, Berdaya Saing, Kreatif, dan Berjiwa Sosial Luhur Menuju Masyarakat yang Mandiri dan Sejahtera.',
            'period_years' => 'Masa Bakti 2024 - 2029',
            'address_office' => 'Jl. Raya Soreang No. 123, Kabupaten Bandung, Jawa Barat',
            'email_official' => 'kontak@karangtarunabandungkab.or.id',
            'phone_official' => '+62 821-2345-6789',
        ]);

        SiteSetting::create(['setting_key' => 'site_name', 'setting_value' => 'Karang Taruna Kabupaten Bandung', 'description' => 'Nama Website']);
        SiteSetting::create(['setting_key' => 'site_tagline', 'setting_value' => 'Bersama Berkarya, Berdaya, dan Berdampak', 'description' => 'Tagline Utama']);
        SiteSetting::create(['setting_key' => 'contact_email', 'setting_value' => 'kontak@karangtarunabandungkab.or.id', 'description' => 'Email Kontak']);
        SiteSetting::create(['setting_key' => 'contact_phone', 'setting_value' => '+62 821-2345-6789', 'description' => 'Nomor Telepon']);
    }
}
