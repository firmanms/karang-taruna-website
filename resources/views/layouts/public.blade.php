<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="description" content="@yield('meta_description', 'Portal Karang Taruna Kabupaten Bandung')">
  <title>@yield('title', 'Karang Taruna Kabupaten Bandung')</title>

  <!-- Favicon / Web Icon -->
  <link rel="icon" type="image/png" href="{{ asset('frontend/images/logo.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('frontend/images/logo.png') }}">

  <!-- Tailwind CSS (CDN) -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- Tabler Icons (CDN) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />
  <!-- ApexCharts -->
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

  <!-- Leaflet CSS & JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <link href="https://unpkg.com/@idds/styles@latest/dist/index.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
  @stack('styles')
</head>

<body>
  <header class="header">
    <div class="container nav">
      <a href="{{ route('public.home') }}" class="brand-logo">
        <img class="logo-emblem" src="{{ asset('frontend/images/logo.png') }}" alt="Lambang Karang Taruna">
        <div class="brand-text">
          <strong>Karang Taruna</strong>
          <small>Kabupaten Bandung</small>
        </div>
      </a>
      <nav class="nav-links">
        <a href="{{ route('public.home') }}" class="nav-item">Beranda</a>

        <!-- Submenu Profil -->
        <div class="nav-dropdown">
          <button class="dropdown-toggle" type="button">
            <span>Profil</span>
            <i class="ti ti-chevron-down dropdown-arrow"></i>
          </button>
          <div class="dropdown-menu slide-dropdown">
            <a href="{{ route('public.profile') }}" class="dropdown-link">Tentang Kami</a>
            <a href="{{ route('public.achievements') }}" class="dropdown-link">Prestasi Pemuda</a>
            <a href="{{ route('public.map') }}" class="dropdown-link">Wilayah & Sebaran</a>
          </div>
        </div>

        <!-- Submenu Direktori Wilayah -->
        <div class="nav-dropdown">
          <button class="dropdown-toggle" type="button">
            <span>Direktori</span>
            <i class="ti ti-chevron-down dropdown-arrow"></i>
          </button>
          <div class="dropdown-menu slide-dropdown">
            <a href="{{ route('public.directory', ['level' => 'kabupaten']) }}" class="dropdown-link">Tingkat Kabupaten</a>
            <a href="{{ route('public.directory', ['level' => 'kecamatan']) }}" class="dropdown-link">Karang Taruna Kecamatan</a>
            <a href="{{ route('public.directory', ['level' => 'desa']) }}" class="dropdown-link">Karang Taruna Desa / Kelurahan</a>
          </div>
        </div>

        <!-- Submenu Layanan Sosial -->
        <div class="nav-dropdown">
          <button class="dropdown-toggle" type="button">
            <span>Layanan</span>
            <i class="ti ti-chevron-down dropdown-arrow"></i>
          </button>
          <div class="dropdown-menu slide-dropdown">
            @if(\App\Domain\Settings\Models\SiteSetting::isEnabled('enable_ppks_service', true))
            <a href="{{ route('public.ppks') }}" class="dropdown-link">Cek Data PPKS</a>
            @endif
            <a href="{{ route('public.downloads') }}" class="dropdown-link">Pusat Unduhan</a>
          </div>
        </div>

        <!-- Submenu Publikasi & Galeri -->
        <div class="nav-dropdown">
          <button class="dropdown-toggle" type="button">
            <span>Galeri & Media</span>
            <i class="ti ti-chevron-down dropdown-arrow"></i>
          </button>
          <div class="dropdown-menu slide-dropdown">
            <a href="{{ route('public.photos') }}" class="dropdown-link">Galeri Foto</a>
            <a href="{{ route('public.videos') }}" class="dropdown-link">Galeri Video</a>
          </div>
        </div>

        <!-- Submenu Program & Kegiatan -->
        <div class="nav-dropdown">
          <button class="dropdown-toggle" type="button">
            <span>Aktivitas</span>
            <i class="ti ti-chevron-down dropdown-arrow"></i>
          </button>
          <div class="dropdown-menu slide-dropdown">
            <a href="{{ route('public.programs') }}" class="dropdown-link">Program Kerja</a>
            <a href="{{ route('public.events') }}" class="dropdown-link">Agenda & Kegiatan</a>
            <a href="{{ route('public.news') }}" class="dropdown-link">Berita Terbaru</a>
            <a href="{{ route('public.announcements') }}" class="dropdown-link">Pengumuman</a>
            <a href="{{ route('public.home') }}#faq" class="dropdown-link">FAQ</a>
          </div>
        </div>

        <!-- <a href="#kontak" class="nav-item">Kontak</a> -->
      </nav>
      <button class="menu-btn" aria-label="Buka menu"><i class="ti ti-menu-2"></i></button>
    </div>
  </header>

  <main id="beranda">
    @yield('content')
  </main>

  <footer class="footer" id="kontak">
    <div class="container">
      <div class="footer-grid">
        <div>
          <a href="{{ route('public.home') }}" class="brand-logo footer-brand">
            <img class="logo-emblem" src="{{ asset('frontend/images/logo.png') }}" alt="Lambang Karang Taruna">
            <div class="brand-text">
              <strong style="color:#ffffff">Karang Taruna</strong>
              <small style="color:#6ee7b7">Kabupaten Bandung</small>
            </div>
          </a>
          <p>Bersama pemuda, membangun masyarakat yang lebih maju, mandiri, dan sejahtera.</p>
        </div>
        <div>
          <h4>Kontak Kami</h4>
          <p>Jl. Al Fathu Soreang, Kabupaten Bandung</p>
          <p>info@karangtarunabandung.or.id</p>
          <p>(022) 5897 1234</p>
          <div class="footer-socials">
            <a href="#" aria-label="Instagram" class="social-icon"><i class="ti ti-brand-instagram"></i></a>
            <a href="#" aria-label="YouTube" class="social-icon"><i class="ti ti-brand-youtube"></i></a>
            <a href="#" aria-label="Facebook" class="social-icon"><i class="ti ti-brand-facebook"></i></a>
            <a href="#" aria-label="TikTok" class="social-icon"><i class="ti ti-brand-tiktok"></i></a>
            <a href="#" aria-label="WhatsApp" class="social-icon"><i class="ti ti-brand-whatsapp"></i></a>
          </div>
        </div>
        <div>
          <h4>Tautan Cepat</h4>
          <div class="footer-links">
            <a href="{{ route('public.profile') }}">Profil</a>
            <a href="{{ route('public.news') }}">Berita</a>
            <a href="{{ route('public.programs') }}">Program Kerja</a>
            <a href="{{ route('public.map') }}">Wilayah</a>
          </div>
        </div>
        <div>
          <h4>Statistik Pengunjung</h4>
          <!-- Visitor Counter Widget -->
          <div class="visitor-widget">
            <div class="visitor-stats">
              <div class="visitor-item">
                <span>Hari Ini:</span>
                <strong id="visitorToday">{{ $visitorStats['today'] ?? '1' }}</strong>
              </div>
              <div class="visitor-item">
                <span>Bulan Ini:</span>
                <strong id="visitorMonth">{{ $visitorStats['month'] ?? '1' }}</strong>
              </div>
              <div class="visitor-item">
                <span>Total Pengunjung:</span>
                <strong id="visitorTotal">{{ $visitorStats['total'] ?? '1' }}</strong>
              </div>
              <div class="visitor-item online-status">
                <span>Online Saat Ini:</span>
                <strong id="visitorOnline"><span class="pulse-dot"></span> {{ $visitorStats['online'] ?? 1 }} User</strong>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="footer-bottom">
        <span>© {{ date('Y') }} Karang Taruna Kabupaten Bandung.</span>
        <span>Kebijakan Privasi · Syarat & Ketentuan · Peta Situs</span>
      </div>
    </div>
  </footer>

  <script src="https://unpkg.com/@idds/js@latest/dist/index.iife.js"></script>
  <script src="{{ asset('frontend/js/app.js') }}"></script>
  @stack('scripts')
</body>

</html>