@extends('layouts.public')

@section('title', 'Tentang Kami - Karang Taruna Kabupaten Bandung')
@section('meta_description', 'Mengenal lebih dekat profil, sejarah, visi, misi, dan struktur organisasi Karang Taruna Kabupaten Bandung.')

@section('content')
  <!-- Page Header Banner -->
  <section class="page-banner">
    <div class="container">
      <div class="breadcrumb">
        <a href="{{ route('public.home') }}"><i class="ti ti-home"></i> Beranda</a>
        <span>/</span>
        <span>Profil</span>
        <span>/</span>
        <span class="active">Tentang Kami</span>
      </div>
      <h1>Tentang Karang Taruna Kabupaten Bandung</h1>
      <p>Mengenal lebih dekat visi, misi, sejarah perjuangan, dan struktur organisasi kepemudaan Kabupaten Bandung.</p>
    </div>
  </section>

  <!-- Content Section -->
  <section class="section static-page-content">
    <div class="container static-grid">
      <!-- Main Content -->
      <article class="static-article">
        <h2>Sejarah & Landasan Gerak</h2>
        <p>
          {{ $profile->history ?? 'Karang Taruna Kabupaten Bandung merupakan organisasi sosial kepemudaan yang berkedudukan di wilayah Kabupaten Bandung sebagai wadah pengembangan generasi muda non-partisan. Tumbuh atas dasar kesadaran dan rasa tanggung jawab sosial dari, oleh, dan untuk masyarakat, khususnya generasi muda di wilayah desa/kelurahan.' }}
        </p>
        <p>
          Dengan semangat <em>Aditya Karya Mahatva Yodha</em>, pemuda Kabupaten Bandung senantiasa hadir sebagai garda terdepan dalam aksi kesetiakawanan sosial, pemberdayaan ekonomi kreatif, dan pelestarian nilai kearifan lokal Tatar Pasundan.
        </p>

        <div class="vision-mission-cards">
          <div class="vm-card">
            <div class="vm-icon"><i class="ti ti-eye"></i></div>
            <h3>Visi Kami</h3>
            <p>{{ $profile->vision ?? 'Mewujudkan Generasi Muda Kabupaten Bandung yang Berkarakter, Mandiri, Berdaya Saing, dan Berjiwa Sosial Menuju Bandung BEDAS (Bangkit, Edukatif, Dinamis, Agamis, dan Sejahtera).' }}</p>
          </div>
          <div class="vm-card">
            <div class="vm-icon"><i class="ti ti-target"></i></div>
            <h3>Misi Kami</h3>
            @if($profile && $profile->missions && count($profile->missions) > 0)
              <ul>
                @foreach($profile->missions as $mission)
                  <li>{{ $mission->mission_text ?? $mission->title ?? $mission }}</li>
                @endforeach
              </ul>
            @else
              <ul>
                <li>Mengembangkan potensi minat, bakat, dan kreativitas pemuda.</li>
                <li>Membangun kemandirian ekonomi melalui UMKM dan digitalisasi pemuda.</li>
                <li>Meningkatkan kepedulian dan kepekaan sosial terhadap persoalan kemasyarakatan.</li>
                <li>Menjalin sinergi kemitraan strategis dengan pemerintah daerah dan swasta.</li>
              </ul>
            @endif
          </div>
        </div>

        <h2>Struktur Kepengurusan</h2>
        <p>Kepengurusan Karang Taruna Kabupaten Bandung periode 2024–2029 terdiri atas Majelis Pertimbangan Karang Taruna (MPKT), Pengurus Harian, serta Bidang-Bidang Strategis:</p>
        
        <div class="org-structure-grid">
          <div class="org-card">
            <div class="org-role">Ketua Umum</div>
            <h4>Kang Ahmad Fauzi, S.Sos</h4>
            <small>Masa Bakti 2024 - 2029</small>
          </div>
          <div class="org-card">
            <div class="org-role">Sekretaris Umum</div>
            <h4>Rian Hidayat, M.Si</h4>
            <small>Masa Bakti 2024 - 2029</small>
          </div>
          <div class="org-card">
            <div class="org-role">Bendahara Umum</div>
            <h4>Siti Nurjanah, S.E</h4>
            <small>Masa Bakti 2024 - 2029</small>
          </div>
        </div>

        <h2>Nilai-Nilai Dasar</h2>
        <div class="values-list">
          <div class="value-item">
            <i class="ti ti-flame text-emerald-600"></i>
            <div>
              <strong>Solidaritas & Kesetiakawanan</strong>
              <p>Mengutamakan kepedulian gotong royong dan empati terhadap sesama warga.</p>
            </div>
          </div>
          <div class="value-item">
            <i class="ti ti-bulb text-emerald-600"></i>
            <div>
              <strong>Inovasi & Kreativitas</strong>
              <p>Terus beradaptasi dengan perkembangan teknologi dan solusi modern.</p>
            </div>
          </div>
          <div class="value-item">
            <i class="ti ti-shield-check text-emerald-600"></i>
            <div>
              <strong>Integritas & Tanggung Jawab</strong>
              <p>Menjaga amanah organisasi secara transparan dan akuntabel.</p>
            </div>
          </div>
        </div>
      </article>

      <!-- Sidebar -->
      <aside class="static-sidebar">
        <div class="sidebar-card">
          <h3>Informasi Organisasi</h3>
          <ul class="sidebar-info-list">
            <li><span>Status:</span> <strong>Organisasi Sosial Kepemudaan</strong></li>
            <li><span>Wilayah Kerja:</span> <strong>Kabupaten Bandung</strong></li>
            <li><span>Jumlah Kecamatan:</span> <strong>31 Kecamatan</strong></li>
            <li><span>Jumlah Desa/Kel:</span> <strong>280 Desa/Kelurahan</strong></li>
            <li><span>Sekretariat:</span> <strong>{{ $profile->address ?? 'Jl. Al Fathu, Soreang' }}</strong></li>
          </ul>
        </div>

        <div class="sidebar-card cta-card">
          <h3>Punya Pertanyaan?</h3>
          <p>Hubungi sekretariat Karang Taruna Kabupaten Bandung untuk konsultasi & kolaborasi.</p>
          <a href="{{ route('public.home') }}#kontak" class="btn btn-primary" style="width:100%;justify-content:center">Hubungi Kami</a>
        </div>
      </aside>
    </div>
  </section>
@endsection
