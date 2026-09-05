<?php

namespace Database\Seeders;

use App\Domain\Content\Models\Achievement;
use App\Domain\Content\Models\Announcement;
use App\Domain\Content\Models\Article;
use App\Domain\Content\Models\Event;
use App\Domain\Content\Models\EventCategory;
use App\Domain\Content\Models\NewsCategory;
use App\Domain\Content\Models\ProgramDivision;
use App\Domain\Content\Models\WorkProgram;
use App\Domain\PPKS\Models\PpksBeneficiary;
use App\Domain\PPKS\Models\PpksCategory;
use App\Domain\Settings\Models\Faq;
use App\Domain\Settings\Models\HeroSlider;
use App\Domain\Settings\Models\OrganizationValue;
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
            'slug' => 'kabupaten-bandung',
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
            'slug' => 'soreang',
            'chairman_name' => 'Ketua KT Soreang',
            'contact_phone' => '081234567891',
            'office_address' => 'Jl. Al-Fathu No. 45, Soreang, Kab. Bandung',
            'latitude' => -7.0252,
            'longitude' => 107.5198,
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
            'slug' => 'soreang-soreang',
            'chairman_name' => 'Ketua KT Desa Soreang',
            'contact_phone' => '081234567892',
            'office_address' => 'Balai Desa Soreang, Kab. Bandung',
            'latitude' => -7.0285,
            'longitude' => 107.5215,
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
        $profile = ProfileOrganization::create([
            'org_name' => 'Karang Taruna Kabupaten Bandung',
            'legal_basis' => 'Permensos No. 25 Tahun 2019 tentang Karang Taruna',
            'vision' => 'Mewujudkan Generasi Muda Kabupaten Bandung yang Berkarakter, Mandiri, Berdaya Saing, dan Berjiwa Sosial Menuju Bandung BEDAS (Bangkit, Edukatif, Dinamis, Agamis, dan Sejahtera).',
            'missions' => [
                'Mengembangkan potensi minat, bakat, dan kreativitas pemuda.',
                'Membangun kemandirian ekonomi melalui UMKM dan digitalisasi pemuda.',
                'Meningkatkan kepedulian dan kepekaan sosial terhadap persoalan kemasyarakatan.',
                'Menjalin sinergi kemitraan strategis dengan pemerintah daerah dan swasta.',
            ],
            'history_content' => '<p>Karang Taruna Kabupaten Bandung merupakan organisasi sosial kepemudaan yang berkedudukan di wilayah Kabupaten Bandung sebagai wadah pengembangan generasi muda non-partisan. Tumbuh atas dasar kesadaran dan rasa tanggung jawab sosial dari, oleh, dan untuk masyarakat, khususnya generasi muda di wilayah desa/kelurahan.</p><p>Dengan semangat <em>Aditya Karya Mahatva Yodha</em>, pemuda Kabupaten Bandung senantiasa hadir sebagai garda terdepan dalam aksi kesetiakawanan sosial, pemberdayaan ekonomi kreatif, dan pelestarian nilai kearifan lokal Tatar Pasundan.</p>',
            'period_years' => 'Masa Bakti 2024 - 2029',
            'address_office' => 'Jl. Al Fathu Soreang, Kabupaten Bandung',
            'email_official' => 'info@karangtarunabandung.or.id',
            'phone_official' => '(022) 5897 1234',
        ]);

        $profile->missions()->createMany([
            ['mission_text' => 'Mengembangkan potensi minat, bakat, dan kreativitas pemuda.', 'order_index' => 1],
            ['mission_text' => 'Membangun kemandirian ekonomi melalui UMKM dan digitalisasi pemuda.', 'order_index' => 2],
            ['mission_text' => 'Meningkatkan kepedulian dan kepekaan sosial terhadap persoalan kemasyarakatan.', 'order_index' => 3],
            ['mission_text' => 'Menjalin sinergi kemitraan strategis dengan pemerintah daerah dan swasta.', 'order_index' => 4],
        ]);

        OrganizationValue::create([
            'icon_class' => 'ti ti-flame text-emerald-600',
            'title' => 'Solidaritas & Kesetiakawanan',
            'description' => 'Mengutamakan kepedulian gotong royong dan empati terhadap sesama warga.',
            'order_index' => 1,
        ]);

        OrganizationValue::create([
            'icon_class' => 'ti ti-bulb text-emerald-600',
            'title' => 'Inovasi & Kreativitas',
            'description' => 'Terus beradaptasi dengan perkembangan teknologi dan solusi modern.',
            'order_index' => 2,
        ]);

        OrganizationValue::create([
            'icon_class' => 'ti ti-shield-check text-emerald-600',
            'title' => 'Integritas & Tanggung Jawab',
            'description' => 'Menjaga amanah organisasi secara transparan dan akuntabel.',
            'order_index' => 3,
        ]);

        SiteSetting::create(['setting_key' => 'site_name', 'setting_value' => 'Karang Taruna Kabupaten Bandung', 'description' => 'Nama Website']);
        SiteSetting::create(['setting_key' => 'site_tagline', 'setting_value' => 'Bersama Berkarya, Berdaya, dan Berdampak', 'description' => 'Tagline Utama']);
        SiteSetting::create(['setting_key' => 'contact_email', 'setting_value' => 'info@karangtarunabandung.or.id', 'description' => 'Email Kontak']);
        SiteSetting::create(['setting_key' => 'contact_phone', 'setting_value' => '(022) 5897 1234', 'description' => 'Nomor Telepon']);

        // 8. Seed Hero Sliders
        HeroSlider::create([
            'title' => "Bersama Berkarya,\nBerdaya, dan Berdampak",
            'subtitle_eyebrow' => 'Pemuda hari ini, bangun masa depan Bandung',
            'description' => 'Karang Taruna Kabupaten Bandung hadir untuk menggerakkan potensi pemuda, memperkuat solidaritas sosial, dan mewujudkan masyarakat yang lebih maju, mandiri, dan sejahtera.',
            'image_path' => 'frontend/images/hero.svg',
            'order_index' => 1,
            'is_active' => true,
        ]);

        HeroSlider::create([
            'title' => "Gerakan Sosial &\nKepedulian Lingkungan",
            'subtitle_eyebrow' => 'Aksi Nyata Pemuda Kabupaten Bandung',
            'description' => 'Mulai dari aksi tanam 1.000 pohon hingga bantuan sosial tanggap bencana, pemuda bergerak bersama menghadirkan perubahan positif bagi bumi Tatar Pasundan.',
            'image_path' => 'frontend/images/gallery-1.svg',
            'order_index' => 2,
            'is_active' => true,
        ]);

        HeroSlider::create([
            'title' => "Pelatihan Digital &\nKewirausahaan Mandiri",
            'subtitle_eyebrow' => 'Inovasi & Kemandirian Generasi Muda',
            'description' => 'Membekali pemuda dengan keterampilan digital masa kini, UMKM modern, dan inkubasi kepemimpinan muda berintegritas tinggi.',
            'image_path' => 'frontend/images/gallery-2.svg',
            'order_index' => 3,
            'is_active' => true,
        ]);

        // 9. Seed Berita Utama & Berita Daerah
        $catPusat = NewsCategory::first();
        $catDaerah = NewsCategory::skip(1)->first();
        $adminUser = User::first();

        Article::create([
            'title' => 'Karang Taruna Gelar Aksi Tanam 1.000 Pohon',
            'slug' => 'karang-taruna-gelar-aksi-tanam-1000-pohon',
            'category_id' => $catPusat->id,
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'excerpt' => 'Wujud kepedulian pemuda terhadap lingkungan dan masa depan yang lebih hijau.',
            'content' => '<p>Pengurus Karang Taruna Kabupaten Bandung bersama ratusan relawan pemuda lintas kecamatan menggelar gerakan pelestarian alam dan konservasi hulu daerah aliran sungai Citarum.</p>',
            'featured_image' => 'frontend/images/news-1.svg',
            'news_scope' => 'pusat',
            'approval_status' => 'approved',
            'is_published' => true,
            'published_at' => now()->subDays(2),
            'views_count' => 1240,
        ]);

        Article::create([
            'title' => 'Pelatihan Kewirausahaan Pemuda di Kecamatan Soreang',
            'slug' => 'pelatihan-kewirausahaan-pemuda-di-kecamatan-soreang',
            'category_id' => $catPusat->id,
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'excerpt' => 'Mendorong kemandirian ekonomi pemuda melalui pelatihan dan pendampingan usaha.',
            'content' => '<p>Mendorong kemandirian ekonomi generasi muda melalui pendampingan legalitas NIB, sertifikasi produk, dan pemasaran digital.</p>',
            'featured_image' => 'frontend/images/news-2.svg',
            'news_scope' => 'pusat',
            'approval_status' => 'approved',
            'is_published' => true,
            'published_at' => now()->subDays(4),
            'views_count' => 890,
        ]);

        Article::create([
            'title' => 'Karang Taruna Salurkan Bantuan untuk Warga',
            'slug' => 'karang-taruna-salurkan-bantuan-untuk-warga',
            'category_id' => $catPusat->id,
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'excerpt' => 'Pemuda hadir dan bergerak cepat untuk masyarakat yang membutuhkan.',
            'content' => '<p>Penyaluran 250 paket sembako dan santunan pendidikan bagi anak yatim piatu di lingkungan wilayah Kabupaten Bandung.</p>',
            'featured_image' => 'frontend/images/news-3.svg',
            'news_scope' => 'pusat',
            'approval_status' => 'approved',
            'is_published' => true,
            'published_at' => now()->subDays(6),
            'views_count' => 670,
        ]);

        Article::create([
            'title' => 'Pemuda Bandung Raih Juara Tingkat Provinsi',
            'slug' => 'pemuda-bandung-raih-juara-tingkat-provinsi',
            'category_id' => $catPusat->id,
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'excerpt' => 'Apresiasi bagi karya dan inovasi pemuda Kabupaten Bandung.',
            'content' => '<p>Penghargaan Aditya Karya Mahatva Yodha Award atas konsistensi program mitigasi kebencanaan dan bakti sosial masyarakat.</p>',
            'featured_image' => 'frontend/images/news-4.svg',
            'news_scope' => 'pusat',
            'approval_status' => 'approved',
            'is_published' => true,
            'published_at' => now()->subDays(8),
            'views_count' => 1540,
        ]);

        // Berita Daerah
        $cileunyi = $districtMap['Cileunyi'] ?? $soreang;
        $ciwidey = $districtMap['Ciwidey'] ?? $soreang;

        Article::create([
            'title' => 'Pemberdayaan Sentra UMKM Pemuda di Kawasan Soreang',
            'slug' => 'pemberdayaan-sentra-umkm-pemuda-di-kawasan-soreang',
            'category_id' => $catDaerah->id,
            'unit_id' => $unitKecamatanSoreang->id,
            'district_id' => $soreang->id,
            'user_id' => $adminUser->id,
            'excerpt' => 'Kolaborasi pemuda desa dalam memperluas jangkauan pasar produk lokal.',
            'content' => '<p>Mendorong produk unggulan UMKM binaan pemuda desa menjangkau marketplace nasional.</p>',
            'featured_image' => 'frontend/images/news-2.svg',
            'news_scope' => 'daerah',
            'approval_status' => 'approved',
            'is_published' => true,
            'published_at' => now()->subDays(1),
            'views_count' => 420,
        ]);

        Article::create([
            'title' => 'Aksi Tanggap Darurat & Penyaluran Logistik Pemuda',
            'slug' => 'aksi-tanggap-darurat-dan-penyaluran-logistik-pemuda',
            'category_id' => $catDaerah->id,
            'unit_id' => $unitKabupaten->id,
            'district_id' => $cileunyi->id,
            'user_id' => $adminUser->id,
            'excerpt' => 'Respon cepat relawan muda membantu pemukiman warga yang terdampak genangan.',
            'content' => '<p>Penyaluran bantuan makanan siap saji dan peralatan pembersihan pasca bencana.</p>',
            'featured_image' => 'frontend/images/news-3.svg',
            'news_scope' => 'daerah',
            'approval_status' => 'approved',
            'is_published' => true,
            'published_at' => now()->subDays(3),
            'views_count' => 580,
        ]);

        Article::create([
            'title' => 'Gerakan Pemuda Pelopor Wisata & Konservasi Hijau',
            'slug' => 'gerakan-pemuda-pelopor-wisata-dan-konservasi-hijau',
            'category_id' => $catDaerah->id,
            'unit_id' => $unitKabupaten->id,
            'district_id' => $ciwidey->id,
            'user_id' => $adminUser->id,
            'excerpt' => 'Edukasi lingkungan hidup dan pengembangan potensi ekowisata berbasis komunitas pemuda.',
            'content' => '<p>Inisiasi jalur trekking wisata ramah lingkungan dan budidaya tanaman endemik.</p>',
            'featured_image' => 'frontend/images/news-1.svg',
            'news_scope' => 'daerah',
            'approval_status' => 'approved',
            'is_published' => true,
            'published_at' => now()->subDays(5),
            'views_count' => 310,
        ]);

        // 10. Seed Program Kerja Unggulan
        $div1 = ProgramDivision::first();
        $div2 = ProgramDivision::skip(1)->first();
        $div3 = ProgramDivision::skip(2)->first();

        WorkProgram::create([
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'division_id' => $div2->id,
            'program_name' => 'Bakti Sosial & Kemanusiaan',
            'slug' => 'bakti-sosial-kemanusiaan',
            'target_participants' => 'Masyarakat Rentan & Prasejahtera',
            'output_indicators' => 'Penyaluran sembako & tanggap bencana',
            'budget_amount' => 10000000,
            'budget_source' => 'Swadaya / BAZNAS',
            'execution_time' => 'Tahunan / Insidental',
            'short_description' => 'Gerakan gotong royong tanggap bencana, santunan, dan solidaritas pemuda bagi masyarakat.',
            'detailed_description' => 'Program berkala bantuan sosial dan tanggap bencana di seluruh wilayah Kabupaten Bandung.',
            'poster_image' => 'frontend/images/gallery-1.svg',
            'progress_status' => 'Rencana',
            'is_featured_home' => true,
            'approval_status' => 'approved',
        ]);

        WorkProgram::create([
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'division_id' => $div3->id,
            'program_name' => 'Kewirausahaan & UMKM Pemuda',
            'slug' => 'kewirausahaan-umkm-pemuda',
            'target_participants' => 'Wirausaha Muda Desa',
            'output_indicators' => 'Pendampingan 100 NIB & Sertifikasi Halal',
            'budget_amount' => 6000000,
            'budget_source' => 'Dinas Koperasi / Swadaya',
            'execution_time' => 'Mei – Agustus 2026',
            'short_description' => 'Inkubasi usaha mandiri, pendampingan legalitas, serta akses permodalan bagi pemuda Bandung.',
            'detailed_description' => 'Pendampingan legalitas NIB, sertifikasi halal, dan foto produk gratis.',
            'poster_image' => 'frontend/images/gallery-2.svg',
            'progress_status' => 'Rencana',
            'is_featured_home' => true,
            'approval_status' => 'approved',
        ]);

        WorkProgram::create([
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'division_id' => $div2->id,
            'program_name' => 'Bandung Resik & Hijau',
            'slug' => 'bandung-resik-hijau',
            'target_participants' => 'Pemuda & Komunitas Lingkungan',
            'output_indicators' => '1.000 Pohon Ditanam di DAS Citarum',
            'budget_amount' => 7500000,
            'budget_source' => 'CSR / DLH',
            'execution_time' => '12 April 2026',
            'short_description' => 'Aksi penanaman pohon, edukasi pengelolaan sampah pemuda desa, dan konservasi alam.',
            'detailed_description' => 'Gerakan menanam 1.000 pohon di sepanjang hulu daerah aliran sungai Citarum.',
            'poster_image' => 'frontend/images/news-1.svg',
            'progress_status' => 'Rencana',
            'is_featured_home' => true,
            'approval_status' => 'approved',
        ]);

        WorkProgram::create([
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'division_id' => $div1->id,
            'program_name' => 'Literasi & Talenta Digital',
            'slug' => 'literasi-talenta-digital',
            'target_participants' => 'Remaja & Pemuda Putus Sekolah',
            'output_indicators' => '50 Pemuda Terampil Digital',
            'budget_amount' => 3000000,
            'budget_source' => 'Swadaya / Kemitraan',
            'execution_time' => 'Oktober 2026',
            'short_description' => 'Bootcamp pemrograman, content creation, dan digital marketing untuk pemuda desa.',
            'detailed_description' => 'Pelatihan teknologi dan digitalisasi administrasi kepengurusan unit.',
            'poster_image' => 'frontend/images/gallery-3.svg',
            'progress_status' => 'Rencana',
            'is_featured_home' => true,
            'approval_status' => 'approved',
        ]);

        WorkProgram::create([
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'division_id' => $div1->id,
            'program_name' => 'Akademi Pemimpin Masa Depan',
            'slug' => 'akademi-pemimpin-masa-depan',
            'target_participants' => 'Pengurus Unit & Kader Baru',
            'output_indicators' => 'Kader Pemimpin Berkarakter',
            'budget_amount' => 5000000,
            'budget_source' => 'Sponsorship / Swadaya',
            'execution_time' => '26 September 2026',
            'short_description' => 'Mencetak kader kepemimpinan pemuda yang kritis, adaptif, beretika, dan siap membangun daerah.',
            'detailed_description' => 'Latihan dasar kepemimpinan pemuda (LDKP) dan outbound pembentukan karakter.',
            'poster_image' => 'frontend/images/gallery-4.svg',
            'progress_status' => 'Rencana',
            'is_featured_home' => true,
            'approval_status' => 'approved',
        ]);

        // 11. Seed Pengumuman Resmi
        Announcement::create([
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'title' => 'Open Recruitment Duta Kepemudaan Kabupaten Bandung 2026',
            'slug' => 'open-recruitment-duta-kepemudaan-kabupaten-bandung-2026',
            'announcement_number' => '042/SE/KT-KAB/IV/2026',
            'category' => 'Seleksi',
            'excerpt' => 'Pendaftaran seleksi duta pemuda pelopor terbuka untuk pemuda/i usia 17-25 tahun ber-KTP Kabupaten Bandung.',
            'content' => 'Seleksi duta kepemudaan membuka peluang pengembangan kepemimpinan dan jejaring pemuda.',
            'is_pinned' => true,
            'approval_status' => 'approved',
            'valid_until' => now()->addDays(30),
        ]);

        Announcement::create([
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'title' => 'Jadwal Temu Karya Karang Taruna Tingkat Kecamatan',
            'slug' => 'jadwal-temu-karya-karang-taruna-tingkat-kecamatan',
            'announcement_number' => '038/SE/KT-KAB/IV/2026',
            'category' => 'Edaran Resmi',
            'excerpt' => 'Pemberitahuan agenda konsolidasi kepengurusan dan pemilihan ketua karang taruna tingkat unit & desa.',
            'content' => 'Seluruh unit diharapkan segera mempersiapkan berkas evaluasi pertanggungjawaban program.',
            'is_pinned' => false,
            'approval_status' => 'approved',
            'valid_until' => now()->addDays(15),
        ]);

        Announcement::create([
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'title' => 'Bantuan Pelatihan Keterampilan Vokasi & Sertifikasi Digital',
            'slug' => 'bantuan-pelatihan-keterampilan-vokasi-dan-sertifikasi-digital',
            'announcement_number' => '031/KOMINFO-KT/III/2026',
            'category' => 'Beasiswa',
            'excerpt' => 'Kuota terbatas untuk 150 pemuda terpilih mengikuti sertifikasi gratis bidang IT & Multimedia.',
            'content' => 'Program peningkatan keahlian kerja bagi generasi muda yang membutuhkan vokasi mandiri.',
            'is_pinned' => false,
            'approval_status' => 'approved',
            'valid_until' => now()->addDays(20),
        ]);

        // 12. Seed Agenda & Kegiatan
        $catEvent1 = EventCategory::first();
        $catEvent2 = EventCategory::skip(1)->first();

        Event::create([
            'title' => 'Bakti Sosial Pemuda',
            'slug' => 'bakti-sosial-pemuda',
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'category_id' => $catEvent2->id,
            'event_date' => now()->addDays(15),
            'start_time' => '08:00',
            'location_venue' => 'Kec. Cileunyi, Kabupaten Bandung',
            'organizer' => 'Karang Taruna Kabupaten Bandung',
            'description' => 'Penyaluran 250 paket sembako, pemeriksaan kesehatan gratis, dan santunan yatim piatu.',
            'event_status' => 'Mendatang',
            'approval_status' => 'approved',
        ]);

        Event::create([
            'title' => 'Pelatihan Digital Marketing',
            'slug' => 'pelatihan-digital-marketing',
            'unit_id' => $unitKecamatanSoreang->id,
            'user_id' => $adminUser->id,
            'category_id' => $catEvent1->id,
            'event_date' => now()->addDays(22),
            'start_time' => '09:00',
            'location_venue' => 'Aula Kecamatan Soreang',
            'organizer' => 'Karang Taruna Kec. Soreang',
            'description' => 'Workshop intensif promosi produk UMKM pemuda desa di marketplace dan media sosial.',
            'event_status' => 'Mendatang',
            'approval_status' => 'approved',
        ]);

        Event::create([
            'title' => 'Seminar Kepemudaan',
            'slug' => 'seminar-kepemudaan',
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'category_id' => $catEvent1->id,
            'event_date' => now()->addDays(28),
            'start_time' => '08:30',
            'location_venue' => 'Gedung Moh. Toha, Soreang',
            'organizer' => 'Karang Taruna Kabupaten Bandung',
            'description' => 'Membangun karakter kepemimpinan pemuda tangguh di era kecerdasan buatan.',
            'event_status' => 'Mendatang',
            'approval_status' => 'approved',
        ]);

        // 13. Seed Prestasi Pemuda
        Achievement::create([
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'title' => 'Juara Inovasi Sosial',
            'slug' => 'juara-inovasi-sosial',
            'recipient_name' => 'Karang Taruna Kab. Bandung',
            'category_field' => 'Inovasi Sosial',
            'year' => 2026,
            'achievement_level' => 'Provinsi',
            'rank_position' => 'Juara 1',
            'awarded_by' => 'Dinas Sosial Provinsi Jawa Barat',
            'description' => 'Program percontohan inkubasi digital ekonomi kreatif pemuda desa.',
            'certificate_image' => 'frontend/images/gallery-6.svg',
            'approval_status' => 'approved',
        ]);

        Achievement::create([
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'title' => 'Top 5 Karang Taruna',
            'slug' => 'top-5-karang-taruna',
            'recipient_name' => 'Karang Taruna Kab. Bandung',
            'category_field' => 'Kelembagaan & Relawan',
            'year' => 2025,
            'achievement_level' => 'Provinsi',
            'rank_position' => 'Top 5 Terbaik',
            'awarded_by' => 'Pengurus Karang Taruna Jawa Barat',
            'description' => 'Apresiasi atas konsistensi program mitigasi bencana dan bakti sosial masyarakat.',
            'certificate_image' => 'frontend/images/gallery-4.svg',
            'approval_status' => 'approved',
        ]);

        Achievement::create([
            'unit_id' => $unitKabupaten->id,
            'user_id' => $adminUser->id,
            'title' => 'Penghargaan Pemuda',
            'slug' => 'penghargaan-pemuda',
            'recipient_name' => 'Ahmad Fauzi & Tim',
            'category_field' => 'Lingkungan Hidup',
            'year' => 2025,
            'achievement_level' => 'Kabupaten',
            'rank_position' => 'Pemuda Pelopor Lingkungan',
            'awarded_by' => 'Pemerintah Kabupaten Bandung',
            'description' => 'Apresiasi Bupati Bandung atas gerakan konservasi mata air dan bank sampah.',
            'certificate_image' => 'frontend/images/news-4.svg',
            'approval_status' => 'approved',
        ]);

        // 14. Seed FAQ
        Faq::create([
            'question' => 'Bagaimana cara bergabung menjadi anggota Karang Taruna?',
            'answer' => 'Pemuda/i usia 13–45 tahun di wilayah Kabupaten Bandung dapat mendaftar langsung melalui pengurus Karang Taruna di tingkat RT/RW atau Desa/Kelurahan domisili setempat.',
            'order_index' => 1,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Apakah program pelatihan kewirausahaan gratis?',
            'answer' => 'Ya, seluruh program pelatihan kerja, UMKM, dan sertifikasi talenta digital yang diselenggarakan Karang Taruna Kabupaten Bandung tidak dipungut biaya (100% Gratis).',
            'order_index' => 2,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Bagaimana cara mengajukan proposal kolaborasi kegiatan?',
            'answer' => 'Proposal dapat dikirimkan melalui email resmi info@karangtarunabandung.or.id atau langsung ke Sekretariat Karang Taruna di Jl. Al Fathu Soreang.',
            'order_index' => 3,
            'is_active' => true,
        ]);

        Faq::create([
            'question' => 'Apa saja peran utama Karang Taruna di masyarakat?',
            'answer' => 'Sebagai wadah pembinaan generasi muda dalam penanganan masalah kesejahteraan sosial, pemberdayaan potensi ekonomi produktif, dan pelestarian lingkungan.',
            'order_index' => 4,
            'is_active' => true,
        ]);

        // 15. Seed Sample Data Warga PPKS
        $ppksCatBalita = PpksCategory::where('category_code', 'PPKS-01')->first();
        $ppksCatDisabilitas = PpksCategory::where('category_code', 'PPKS-09')->first();
        $ppksCatLansia = PpksCategory::where('category_code', 'PPKS-08')->first();
        $ppksCatFakir = PpksCategory::where('category_code', 'PPKS-24')->first();

        if ($ppksCatDisabilitas) {
            PpksBeneficiary::create([
                'nik' => '3204051203990001',
                'full_name' => 'Ahmad Suhendar',
                'category_id' => $ppksCatDisabilitas->id,
                'district_id' => $soreang->id,
                'village_id' => $desaSoreang->id,
                'unit_id' => $unitDesaSoreang->id,
                'submitted_by' => $adminUser->id,
                'address_detail' => 'Kp. Cikambuy RT 02 RW 04, Desa Soreang',
                'social_assistance_status' => 'Bantuan Kursi Roda & Pelatihan Usaha Mandiri',
                'mentor_unit' => 'Karang Taruna Satria Muda Desa Soreang',
                'last_survey_date' => now()->subDays(10),
                'verification_status' => 'verified',
                'verified_by' => $adminUser->id,
                'verified_at' => now()->subDays(5),
            ]);
        }

        if ($ppksCatLansia) {
            PpksBeneficiary::create([
                'nik' => '3204055508500002',
                'full_name' => 'Siti Rohani',
                'category_id' => $ppksCatLansia->id,
                'district_id' => $soreang->id,
                'village_id' => $desaSoreang->id,
                'unit_id' => $unitDesaSoreang->id,
                'submitted_by' => $adminUser->id,
                'address_detail' => 'Jl. Soreang Indah RT 01 RW 08',
                'social_assistance_status' => 'Bantuan Sembako Lansia & Kartu Sehat',
                'mentor_unit' => 'Karang Taruna Satria Muda Desa Soreang',
                'last_survey_date' => now()->subDays(12),
                'verification_status' => 'verified',
                'verified_by' => $adminUser->id,
                'verified_at' => now()->subDays(6),
            ]);
        }

        if ($ppksCatFakir) {
            PpksBeneficiary::create([
                'nik' => '3204052109880003',
                'full_name' => 'Ujang Permana',
                'category_id' => $ppksCatFakir->id,
                'district_id' => $soreang->id,
                'village_id' => $desaSoreang->id,
                'unit_id' => $unitDesaSoreang->id,
                'submitted_by' => $adminUser->id,
                'address_detail' => 'Kp. Babakan RT 04 RW 02',
                'social_assistance_status' => 'Usulan Bantuan Modal Usaha Karang Taruna',
                'mentor_unit' => 'Karang Taruna Satria Muda Desa Soreang',
                'last_survey_date' => now()->subDays(2),
                'verification_status' => 'pending_verification',
            ]);
        }
    }
}
