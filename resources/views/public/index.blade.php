@extends('layouts.public')

@section('title', 'Karang Taruna Kabupaten Bandung')
@section('meta_description', 'Portal Karang Taruna Kabupaten Bandung - Bersama Berkarya, Berdaya, dan Berdampak.')

@section('content')
  <!-- Hero Banner Slider -->
  <section class="hero-slider-section">
    <div class="hero-slider" id="heroSlider">
      @forelse($sliders as $index => $slider)
        <div class="hero-slide {{ $index === 0 ? 'active' : '' }}"
          data-bg="linear-gradient(90deg,rgba(15,23,42,.96) 0%,rgba(15,23,42,.85) 48%,rgba(15,23,42,.3) 75%),url('{{ asset('storage/' . $slider->image_path) }}') center/cover no-repeat">
          <div class="container">
            <div class="hero-content">
              @if($slider->subtitle_eyebrow)
                <div class="eyebrow">{{ $slider->subtitle_eyebrow }}</div>
              @endif
              <h1>{!! nl2br(e($slider->title)) !!}</h1>
              <p>{{ $slider->description }}</p>
            </div>
          </div>
        </div>
      @empty
        <!-- Slide 1 -->
        <div class="hero-slide active"
          data-bg="linear-gradient(90deg,rgba(15,23,42,.96) 0%,rgba(15,23,42,.85) 48%,rgba(15,23,42,.3) 75%),url('{{ asset('frontend/images/hero.svg') }}') center/cover no-repeat">
          <div class="container">
            <div class="hero-content">
              <div class="eyebrow">Pemuda hari ini, bangun masa depan Bandung</div>
              <h1>Bersama Berkarya,<br>Berdaya, dan <span>Berdampak</span></h1>
              <p>Karang Taruna Kabupaten Bandung hadir untuk menggerakkan potensi pemuda, memperkuat solidaritas sosial, dan mewujudkan masyarakat yang lebih maju, mandiri, dan sejahtera.</p>
            </div>
          </div>
        </div>

        <!-- Slide 2 -->
        <div class="hero-slide"
          data-bg="linear-gradient(90deg,rgba(15,23,42,.96) 0%,rgba(15,23,42,.85) 48%,rgba(15,23,42,.3) 75%),url('{{ asset('frontend/images/gallery-1.svg') }}') center/cover no-repeat">
          <div class="container">
            <div class="hero-content">
              <div class="eyebrow">Aksi Nyata Pemuda Kabupaten Bandung</div>
              <h1>Gerakan Sosial &<br>Kepedulian <span>Lingkungan</span></h1>
              <p>Mulai dari aksi tanam 1.000 pohon hingga bantuan sosial tanggap bencana, pemuda bergerak bersama menghadirkan perubahan positif bagi bumi Tatar Pasundan.</p>
            </div>
          </div>
        </div>

        <!-- Slide 3 -->
        <div class="hero-slide"
          data-bg="linear-gradient(90deg,rgba(15,23,42,.96) 0%,rgba(15,23,42,.85) 48%,rgba(15,23,42,.3) 75%),url('{{ asset('frontend/images/gallery-2.svg') }}') center/cover no-repeat">
          <div class="container">
            <div class="hero-content">
              <div class="eyebrow">Inovasi & Kemandirian Generasi Muda</div>
              <h1>Pelatihan Digital &<br>Kewirausahaan <span>Mandiri</span></h1>
              <p>Membekali pemuda dengan keterampilan digital masa kini, UMKM modern, dan inkubasi kepemimpinan muda berintegritas tinggi.</p>
            </div>
          </div>
        </div>
      @endforelse

      <!-- Slider Controls -->
      <button class="slider-btn prev-btn" id="prevSlide" aria-label="Slide sebelumnya"><i class="ti ti-chevron-left"></i></button>
      <button class="slider-btn next-btn" id="nextSlide" aria-label="Slide selanjutnya"><i class="ti ti-chevron-right"></i></button>

      <!-- Slider Dots -->
      <div class="slider-dots" id="sliderDots">
        @if(count($sliders) > 0)
          @foreach($sliders as $idx => $s)
            <button class="dot {{ $idx === 0 ? 'active' : '' }}" data-index="{{ $idx }}" aria-label="Slide {{ $idx + 1 }}"></button>
          @endforeach
        @else
          <button class="dot active" data-index="0" aria-label="Slide 1"></button>
          <button class="dot" data-index="1" aria-label="Slide 2"></button>
          <button class="dot" data-index="2" aria-label="Slide 3"></button>
        @endif
      </div>
    </div>
  </section>

  <!-- Section Berita Terbaru -->
  <section class="section" id="berita">
    <div class="container">
      <div class="section-head">
        <div>
          <h2>Berita Terbaru</h2>
          <p class="section-sub">Informasi terkini seputar kegiatan, program, dan kabar Karang Taruna Kabupaten Bandung.</p>
        </div>
        <a class="link" href="{{ route('public.news') }}">Lihat Semua Berita →</a>
      </div>
      <div class="grid-4">
        @forelse($articles as $index => $article)
          <article class="card">
            <a href="{{ route('public.news.detail', $article->slug) }}">
              <img class="card-img" src="{{ str_starts_with($article->featured_image ?? '', 'http') ? $article->featured_image : ($article->featured_image ? asset('storage/' . $article->featured_image) : asset('frontend/images/news-' . (($index % 4) + 1) . '.svg')) }}" alt="{{ $article->title }}" onerror="this.src='{{ asset('frontend/images/news-' . (($index % 4) + 1) . '.svg') }}'">
            </a>
            <div class="card-body">
              <span class="tag">{{ $article->category->name ?? 'Kegiatan' }}</span>
              <div class="date">{{ $article->published_at ? $article->published_at->translatedFormat('d F Y') : $article->created_at->translatedFormat('d F Y') }}</div>
              <h3><a href="{{ route('public.news.detail', $article->slug) }}">{{ $article->title }}</a></h3>
              <p>{{ Str::limit($article->excerpt ?? strip_tags($article->content), 90) }}</p>
            </div>
          </article>
        @empty
          <!-- Fallback dummy cards from template -->
          <article class="card">
            <a href="{{ route('public.news') }}"><img class="card-img" src="{{ asset('frontend/images/news-1.svg') }}" alt=""></a>
            <div class="card-body">
              <span class="tag">Kegiatan</span>
              <div class="date">12 April 2026</div>
              <h3><a href="{{ route('public.news') }}">Karang Taruna Gelar Aksi Tanam 1.000 Pohon</a></h3>
              <p>Wujud kepedulian pemuda terhadap lingkungan dan masa depan yang lebih hijau.</p>
            </div>
          </article>
          <article class="card">
            <a href="{{ route('public.news') }}"><img class="card-img" src="{{ asset('frontend/images/news-2.svg') }}" alt=""></a>
            <div class="card-body">
              <span class="tag">Program</span>
              <div class="date">10 April 2026</div>
              <h3><a href="{{ route('public.news') }}">Pelatihan Kewirausahaan Pemuda di Kecamatan Soreang</a></h3>
              <p>Mendorong kemandirian ekonomi pemuda melalui pelatihan dan pendampingan usaha.</p>
            </div>
          </article>
          <article class="card">
            <a href="{{ route('public.news') }}"><img class="card-img" src="{{ asset('frontend/images/news-3.svg') }}" alt=""></a>
            <div class="card-body">
              <span class="tag">Sosial</span>
              <div class="date">08 April 2026</div>
              <h3><a href="{{ route('public.news') }}">Karang Taruna Salurkan Bantuan untuk Warga</a></h3>
              <p>Pemuda hadir dan bergerak cepat untuk masyarakat yang membutuhkan.</p>
            </div>
          </article>
          <article class="card">
            <a href="{{ route('public.news') }}"><img class="card-img" src="{{ asset('frontend/images/news-4.svg') }}" alt=""></a>
            <div class="card-body">
              <span class="tag">Prestasi</span>
              <div class="date">05 April 2026</div>
              <h3><a href="{{ route('public.news') }}">Pemuda Bandung Raih Juara Tingkat Provinsi</a></h3>
              <p>Apresiasi bagi karya dan inovasi pemuda Kabupaten Bandung.</p>
            </div>
          </article>
        @endforelse
      </div>
    </div>
  </section>

  <!-- Section Program dan Informasi -->
  <section class="section" id="program">
    <div class="container">
      <div class="section-head">
        <div>
          <h2>Program dan Informasi</h2>
          <p class="section-sub">Program strategis pemberdayaan pemuda dan aksi nyata di masyarakat.</p>
        </div>
        <div class="program-slider-arrows">
          <button class="arrow-btn" id="prevProgram" aria-label="Program sebelumnya"><i class="ti ti-chevron-left"></i></button>
          <button class="arrow-btn" id="nextProgram" aria-label="Program selanjutnya"><i class="ti ti-chevron-right"></i></button>
        </div>
      </div>

      <div class="program-slider-wrapper">
        <div class="program-slider-track" id="programTrack">
          @forelse($featuredPrograms as $index => $prog)
            <article class="program-poster">
              <div class="poster-media">
                <img src="{{ $prog->poster_image ? asset('storage/' . $prog->poster_image) : asset('frontend/images/gallery-' . (($index % 5) + 1) . '.svg') }}" alt="{{ $prog->program_name }}" class="poster-img">
                <span class="poster-badge"><i class="ti ti-sparkles"></i> {{ $prog->division->division_name ?? 'Program' }}</span>
              </div>
              <div class="poster-content">
                <h3>{{ $prog->program_name }}</h3>
                <p>{{ Str::limit($prog->short_description ?? strip_tags($prog->detailed_description), 100) }}</p>
                <a href="{{ route('public.programs') }}" class="poster-link">Lihat Jadwal & Detail <i class="ti ti-arrow-right"></i></a>
              </div>
            </article>
          @empty
            <article class="program-poster">
              <div class="poster-media">
                <img src="{{ asset('frontend/images/gallery-1.svg') }}" alt="Sosial & Kemanusiaan" class="poster-img">
                <span class="poster-badge"><i class="ti ti-heart-handshake"></i> Sosial</span>
              </div>
              <div class="poster-content">
                <h3>Bakti Sosial & Kemanusiaan</h3>
                <p>Gerakan gotong royong tanggap bencana, santunan, dan solidaritas pemuda bagi masyarakat.</p>
                <a href="{{ route('public.programs') }}" class="poster-link">Lihat Jadwal & Detail <i class="ti ti-arrow-right"></i></a>
              </div>
            </article>
            <article class="program-poster">
              <div class="poster-media">
                <img src="{{ asset('frontend/images/gallery-2.svg') }}" alt="Kewirausahaan Pemuda" class="poster-img">
                <span class="poster-badge"><i class="ti ti-chart-line"></i> Ekonomi</span>
              </div>
              <div class="poster-content">
                <h3>Kewirausahaan & UMKM Pemuda</h3>
                <p>Inkubasi usaha mandiri, pendampingan legalitas, serta akses permodalan bagi pemuda Bandung.</p>
                <a href="{{ route('public.programs') }}" class="poster-link">Lihat Jadwal & Detail <i class="ti ti-arrow-right"></i></a>
              </div>
            </article>
            <article class="program-poster">
              <div class="poster-media">
                <img src="{{ asset('frontend/images/news-1.svg') }}" alt="Lingkungan Hidup" class="poster-img">
                <span class="poster-badge"><i class="ti ti-leaf"></i> Lingkungan</span>
              </div>
              <div class="poster-content">
                <h3>Bandung Resik & Hijau</h3>
                <p>Aksi penanaman pohon, edukasi pengelolaan sampah pemuda desa, dan konservasi alam.</p>
                <a href="{{ route('public.programs') }}" class="poster-link">Lihat Jadwal & Detail <i class="ti ti-arrow-right"></i></a>
              </div>
            </article>
            <article class="program-poster">
              <div class="poster-media">
                <img src="{{ asset('frontend/images/gallery-3.svg') }}" alt="Literasi Digital" class="poster-img">
                <span class="poster-badge"><i class="ti ti-device-laptop"></i> Digital</span>
              </div>
              <div class="poster-content">
                <h3>Literasi & Talenta Digital</h3>
                <p>Bootcamp pemrograman, content creation, dan digital marketing untuk pemuda desa.</p>
                <a href="{{ route('public.programs') }}" class="poster-link">Lihat Jadwal & Detail <i class="ti ti-arrow-right"></i></a>
              </div>
            </article>
            <article class="program-poster">
              <div class="poster-media">
                <img src="{{ asset('frontend/images/gallery-4.svg') }}" alt="Kepemimpinan Pemuda" class="poster-img">
                <span class="poster-badge"><i class="ti ti-compass"></i> Leader</span>
              </div>
              <div class="poster-content">
                <h3>Akademi Pemimpin Masa Depan</h3>
                <p>Mencetak kader kepemimpinan pemuda yang kritis, adaptif, beretika, dan siap membangun daerah.</p>
                <a href="{{ route('public.programs') }}" class="poster-link">Lihat Jadwal & Detail <i class="ti ti-arrow-right"></i></a>
              </div>
            </article>
          @endforelse
        </div>
      </div>
    </div>
  </section>

  <!-- Section Berita Daerah & Pengumuman -->
  <section class="section section-berita-pengumuman" id="berita-daerah">
    <div class="container two-col">
      <!-- Kolom Berita Daerah -->
      <div class="panel">
        <div class="section-head">
          <div>
            <h2>Berita Daerah</h2>
            <p class="section-sub">Informasi kegiatan dan kabar aktual dari kecamatan se-Kabupaten Bandung.</p>
          </div>
          <a class="link" href="{{ route('public.news', ['scope' => 'Kecamatan/Desa']) }}">Semua Berita Daerah →</a>
        </div>
        <div class="regional-news-list">
          @forelse($regionalArticles as $index => $rArticle)
            <article class="regional-news-item">
              <img src="{{ str_starts_with($rArticle->featured_image ?? '', 'http') ? $rArticle->featured_image : ($rArticle->featured_image ? asset('storage/' . $rArticle->featured_image) : asset('frontend/images/news-' . (($index % 3) + 2) . '.svg')) }}" alt="{{ $rArticle->title }}" class="regional-thumb" onerror="this.src='{{ asset('frontend/images/news-' . (($index % 3) + 2) . '.svg') }}'">
              <div class="regional-content">
                <div class="regional-meta">
                  <span class="tag-badge">{{ !empty($rArticle->district?->name) ? 'Kec. ' . $rArticle->district->name : ($rArticle->unit?->unit_name ?? 'Daerah') }}</span>
                  <span class="post-date"><i class="ti ti-calendar"></i> {{ $rArticle->published_at ? $rArticle->published_at->translatedFormat('d M Y') : $rArticle->created_at->translatedFormat('d M Y') }}</span>
                </div>
                <h3><a href="{{ route('public.news.detail', $rArticle->slug) }}">{{ $rArticle->title }}</a></h3>
                <p>{{ Str::limit($rArticle->excerpt ?? strip_tags($rArticle->content), 85) }}</p>
              </div>
            </article>
          @empty
            <article class="regional-news-item">
              <img src="{{ asset('frontend/images/news-2.svg') }}" alt="Berita Soreang" class="regional-thumb">
              <div class="regional-content">
                <div class="regional-meta">
                  <span class="tag-badge">Kec. Soreang</span>
                  <span class="post-date"><i class="ti ti-calendar"></i> 14 Apr 2026</span>
                </div>
                <h3>Pemberdayaan Sentra UMKM Pemuda di Kawasan Soreang</h3>
                <p>Kolaborasi pemuda desa dalam memperluas jangkauan pasar produk lokal.</p>
              </div>
            </article>
            <article class="regional-news-item">
              <img src="{{ asset('frontend/images/news-3.svg') }}" alt="Berita Cileunyi" class="regional-thumb">
              <div class="regional-content">
                <div class="regional-meta">
                  <span class="tag-badge">Kec. Cileunyi</span>
                  <span class="post-date"><i class="ti ti-calendar"></i> 11 Apr 2026</span>
                </div>
                <h3>Aksi Tanggap Darurat & Penyaluran Logistik Pemuda</h3>
                <p>Respon cepat relawan muda membantu pemukiman warga yang terdampak genangan.</p>
              </div>
            </article>
            <article class="regional-news-item">
              <img src="{{ asset('frontend/images/news-1.svg') }}" alt="Berita Ciwidey" class="regional-thumb">
              <div class="regional-content">
                <div class="regional-meta">
                  <span class="tag-badge">Kec. Ciwidey</span>
                  <span class="post-date"><i class="ti ti-calendar"></i> 08 Apr 2026</span>
                </div>
                <h3>Gerakan Pemuda Pelopor Wisata & Konservasi Hijau</h3>
                <p>Edukasi lingkungan hidup dan pengembangan potensi ekowisata berbasis komunitas pemuda.</p>
              </div>
            </article>
          @endforelse
        </div>
      </div>

      <!-- Kolom Pengumuman Resmi -->
      <div class="panel" id="pengumuman">
        <div class="section-head">
          <div>
            <h2>Pengumuman</h2>
            <p class="section-sub">Pemberitahuan resmi, edaran, dan pendaftaran program pemuda.</p>
          </div>
          <a class="link" href="{{ route('public.announcements') }}">Arsip Pengumuman →</a>
        </div>
        <div class="announcement-list">
          @forelse($announcements as $ann)
            <div class="announcement-card {{ $ann->is_pinned ? 'pinned' : '' }}">
              <div class="announcement-header">
                <span class="announcement-pill {{ $ann->is_pinned ? '' : 'pill-info' }}">
                  <i class="ti ti-{{ $ann->is_pinned ? 'pin' : 'bell' }}"></i> {{ $ann->category }}
                </span>
                <span class="post-date">{{ $ann->end_date ? 'Berakhir ' . \Carbon\Carbon::parse($ann->end_date)->translatedFormat('d M Y') : $ann->created_at->translatedFormat('d M Y') }}</span>
              </div>
              <h4>{{ $ann->title }}</h4>
              <p>{{ Str::limit($ann->excerpt ?? strip_tags($ann->content), 120) }}</p>
              @if($ann->attachment_file)
                <a href="{{ asset('storage/' . $ann->attachment_file) }}" target="_blank" class="announcement-link">Unduh Syarat & Ketentuan <i class="ti ti-download"></i></a>
              @endif
            </div>
          @empty
            <div class="announcement-card pinned">
              <div class="announcement-header">
                <span class="announcement-pill"><i class="ti ti-pin"></i> Penting</span>
                <span class="post-date">Berakhir 30 Apr 2026</span>
              </div>
              <h4>Open Recruitment Duta Kepemudaan Kabupaten Bandung 2026</h4>
              <p>Pendaftaran seleksi duta pemuda pelopor terbuka untuk pemuda/i usia 17-25 tahun ber-KTP Kabupaten Bandung.</p>
              <a href="{{ route('public.announcements') }}" class="announcement-link">Unduh Syarat & Ketentuan <i class="ti ti-download"></i></a>
            </div>
            <div class="announcement-card">
              <div class="announcement-header">
                <span class="announcement-pill pill-info"><i class="ti ti-bell"></i> Edaran</span>
                <span class="post-date">10 Apr 2026</span>
              </div>
              <h4>Jadwal Temu Karya Karang Taruna Tingkat Kecamatan</h4>
              <p>Pemberitahuan agenda konsolidasi kepengurusan dan pemilihan ketua karang taruna tingkat unit & desa.</p>
              <a href="{{ route('public.announcements') }}" class="announcement-link">Baca Surat Edaran <i class="ti ti-file-text"></i></a>
            </div>
            <div class="announcement-card">
              <div class="announcement-header">
                <span class="announcement-pill pill-info"><i class="ti ti-certificate"></i> Beasiswa</span>
                <span class="post-date">05 Apr 2026</span>
              </div>
              <h4>Bantuan Pelatihan Keterampilan Vokasi & Sertifikasi Digital</h4>
              <p>Kuota terbatas untuk 150 pemuda terpilih mengikuti sertifikasi gratis bidang IT & Multimedia.</p>
              <a href="{{ route('public.announcements') }}" class="announcement-link">Daftar Sekarang <i class="ti ti-arrow-right"></i></a>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </section>

  <!-- Section Agenda & Kegiatan + Prestasi Pemuda -->
  <section class="section" id="agenda">
    <div class="container two-col">
      <div class="panel">
        <div class="section-head">
          <div>
            <h2>Agenda & Kegiatan</h2>
            <p class="section-sub">Jangan lewatkan kegiatan Karang Taruna.</p>
          </div>
          <a class="link" href="{{ route('public.events') }}">Semua Agenda →</a>
        </div>
        <div class="agenda">
          @forelse($events as $event)
            <div class="agenda-item">
              <div class="agenda-date">
                <b>{{ $event->event_date ? $event->event_date->format('d') : '20' }}</b>
                <small>{{ $event->event_date ? $event->event_date->translatedFormat('M Y') : 'Sep 2026' }}</small>
              </div>
              <div>
                <strong>{{ $event->title }}</strong>
                <div class="section-sub">{{ $event->location_venue }}</div>
              </div>
              <span class="status">{{ ucfirst($event->event_status ?? 'Akan Datang') }}</span>
            </div>
          @empty
            <div class="agenda-item">
              <div class="agenda-date"><b>20</b><small>Sep 2026</small></div>
              <div><strong>Bakti Sosial Pemuda</strong>
                <div class="section-sub">Kec. Cileunyi, Kabupaten Bandung</div>
              </div><span class="status">Akan Datang</span>
            </div>
            <div class="agenda-item">
              <div class="agenda-date"><b>27</b><small>Sep 2026</small></div>
              <div><strong>Pelatihan Digital Marketing</strong>
                <div class="section-sub">Aula Kecamatan Soreang</div>
              </div><span class="status">Akan Datang</span>
            </div>
            <div class="agenda-item">
              <div class="agenda-date"><b>03</b><small>Okt 2026</small></div>
              <div><strong>Seminar Kepemudaan</strong>
                <div class="section-sub">Gedung Moh. Toha, Soreang</div>
              </div><span class="status">Akan Datang</span>
            </div>
          @endforelse
        </div>
      </div>

      <div class="panel" id="prestasi">
        <div class="section-head">
          <div>
            <h2>Prestasi Pemuda</h2>
            <p class="section-sub">Karya dan dedikasi pemuda yang membanggakan.</p>
          </div>
          <a class="link" href="{{ route('public.achievements') }}">Lihat Semua Prestasi →</a>
        </div>
        <div class="achievement-grid">
          @forelse($achievements as $index => $ach)
            <div class="mini-card">
              <img src="{{ $ach->certificate_image ? asset('storage/' . $ach->certificate_image) : asset('frontend/images/gallery-' . (($index % 3) + 4) . '.svg') }}" alt="{{ $ach->title }}" onerror="this.src='{{ asset('frontend/images/gallery-6.svg') }}'">
              <div>
                <strong>{{ $ach->title }}</strong>
                <p class="section-sub">{{ $ach->achievement_level ?? 'Tingkat Provinsi' }}</p>
              </div>
            </div>
          @empty
            <div class="mini-card"><img src="{{ asset('frontend/images/gallery-6.svg') }}" alt="">
              <div><strong>Juara Inovasi Sosial</strong>
                <p class="section-sub">Tingkat Jawa Barat</p>
              </div>
            </div>
            <div class="mini-card"><img src="{{ asset('frontend/images/gallery-4.svg') }}" alt="">
              <div><strong>Top 5 Karang Taruna</strong>
                <p class="section-sub">Tingkat Provinsi</p>
              </div>
            </div>
            <div class="mini-card"><img src="{{ asset('frontend/images/news-4.svg') }}" alt="">
              <div><strong>Penghargaan Pemuda</strong>
                <p class="section-sub">Kabupaten Bandung</p>
              </div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </section>

  <!-- Section Peta Sebaran & FAQ -->
  <section class="section" id="wilayah">
    <div class="container map-wrap">
      <div>
        <div class="section-head">
          <div>
            <h2>Peta Sebaran Wilayah</h2>
            <p class="section-sub">Jangkauan & titik koordinat Karang Taruna di Kabupaten Bandung.</p>
          </div>
          <a class="link" href="{{ route('public.map') }}">Lihat Peta Lengkap →</a>
        </div>
        <div class="map-card" style="display: flex; flex-direction: column; gap: 1rem; padding: 1.25rem; align-items: stretch; background: #fff;">
          <div class="map-num" style="display: flex; gap: 2rem; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem;">
            <div>
              <strong style="font-size: 28px; line-height: 1;">{{ $totalDistricts > 0 ? $totalDistricts : 31 }}</strong>
              <span style="font-size: 13px; color: #64748b;">Kecamatan</span>
            </div>
            <div>
              <strong style="font-size: 28px; line-height: 1;">{{ $totalVillages > 0 ? $totalVillages : 280 }}</strong>
              <span style="font-size: 13px; color: #64748b;">Desa/Kelurahan</span>
            </div>
            <div>
              <strong style="font-size: 28px; line-height: 1;">{{ $totalUnits > 0 ? $totalUnits : count($mapUnits) }}</strong>
              <span style="font-size: 13px; color: #64748b;">Unit Aktif</span>
            </div>
          </div>
          
          <!-- Legend Warna Unit -->
          <div style="display: flex; gap: 0.85rem; align-items: center; flex-wrap: wrap; font-size: 0.8rem; color: #475569; background: #f8fafc; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
            <span style="font-weight: 600; color: #1e293b;"><i class="ti ti-info-circle"></i> Keterangan Titik:</span>
            <span style="display: inline-flex; align-items: center; gap: 0.35rem;">
              <span style="width: 12px; height: 12px; border-radius: 50%; background-color: #ef4444; border: 2px solid #fff; box-shadow: 0 0 0 1px #ef4444;"></span> Kabupaten (Merah)
            </span>
            <span style="display: inline-flex; align-items: center; gap: 0.35rem;">
              <span style="width: 12px; height: 12px; border-radius: 50%; background-color: #3b82f6; border: 2px solid #fff; box-shadow: 0 0 0 1px #3b82f6;"></span> Kecamatan (Biru)
            </span>
            <span style="display: inline-flex; align-items: center; gap: 0.35rem;">
              <span style="width: 12px; height: 12px; border-radius: 50%; background-color: #10b981; border: 2px solid #fff; box-shadow: 0 0 0 1px #10b981;"></span> Desa / Kelurahan (Hijau)
            </span>
          </div>

          <div id="homeMapContainer" style="height: 380px; width: 100%; min-height: 380px; border-radius: 0.75rem; z-index: 10; border: 1px solid #e2e8f0;"></div>
        </div>
      </div>
      <div id="faq">
        <div class="section-head">
          <div>
            <h2>Pertanyaan Umum (FAQ)</h2>
            <p class="section-sub">Informasi yang sering ditanyakan seputar Karang Taruna.</p>
          </div>
        </div>
        <div class="faq-accordion">
          @forelse($faqs as $index => $faq)
            <div class="faq-item {{ $index === 0 ? 'active' : '' }}">
              <button class="faq-question" type="button">
                <span>{{ $faq->question }}</span>
                <i class="ti ti-chevron-down faq-chevron"></i>
              </button>
              <div class="faq-answer">
                <p>{!! nl2br(e(strip_tags($faq->answer))) !!}</p>
              </div>
            </div>
          @empty
            <div class="faq-item active">
              <button class="faq-question" type="button">
                <span>Bagaimana cara bergabung menjadi anggota Karang Taruna?</span>
                <i class="ti ti-chevron-down faq-chevron"></i>
              </button>
              <div class="faq-answer">
                <p>Pemuda/i usia 13–45 tahun di wilayah Kabupaten Bandung dapat mendaftar langsung melalui pengurus Karang Taruna di tingkat RT/RW atau Desa/Kelurahan domisili setempat.</p>
              </div>
            </div>

            <div class="faq-item">
              <button class="faq-question" type="button">
                <span>Apakah program pelatihan kewirausahaan gratis?</span>
                <i class="ti ti-chevron-down faq-chevron"></i>
              </button>
              <div class="faq-answer">
                <p>Ya, seluruh program pelatihan kerja, UMKM, dan sertifikasi talenta digital yang diselenggarakan Karang Taruna Kabupaten Bandung tidak dipungut biaya (100% Gratis).</p>
              </div>
            </div>

            <div class="faq-item">
              <button class="faq-question" type="button">
                <span>Bagaimana cara mengajukan proposal kolaborasi kegiatan?</span>
                <i class="ti ti-chevron-down faq-chevron"></i>
              </button>
              <div class="faq-answer">
                <p>Proposal dapat dikirimkan melalui email resmi <strong>info@karangtarunabandung.or.id</strong> atau langsung ke Sekretariat Karang Taruna di Jl. Al Fathu Soreang.</p>
              </div>
            </div>

            <div class="faq-item">
              <button class="faq-question" type="button">
                <span>Apa saja peran utama Karang Taruna di masyarakat?</span>
                <i class="ti ti-chevron-down faq-chevron"></i>
              </button>
              <div class="faq-answer">
                <p>Sebagai wadah pembinaan generasi muda dalam penanganan masalah kesejahteraan sosial, pemberdayaan potensi ekonomi produktif, dan pelestarian lingkungan.</p>
              </div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const mapEl = document.getElementById('homeMapContainer');
    if (mapEl) {
      // Inisialisasi Peta Leaflet Beranda (Kabupaten Bandung Center)
      const homeMap = L.map('homeMapContainer', {
        scrollWheelZoom: false
      }).setView([-7.0252, 107.5198], 10);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors'
      }).addTo(homeMap);

      setTimeout(function() {
        homeMap.invalidateSize();
      }, 250);

      // Helper untuk custom colored marker icon
      function getMarkerIcon(level) {
        let color = '#10b981'; // default desa: emerald green
        let iconName = 'ti-home';
        let borderColor = '#059669';

        if (level === 'kabupaten') {
          color = '#ef4444'; // merah
          iconName = 'ti-building-monument';
          borderColor = '#b91c1c';
        } else if (level === 'kecamatan') {
          color = '#3b82f6'; // biru
          iconName = 'ti-building-community';
          borderColor = '#1d4ed8';
        }

        return L.divIcon({
          className: 'custom-map-pin',
          html: `<div style="
            background-color: ${color};
            width: 32px;
            height: 32px;
            border-radius: 50% 50% 50% 0;
            transform: rotate(-45deg);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
          ">
            <i class="ti ${iconName}" style="
              transform: rotate(45deg);
              color: #ffffff;
              font-size: 14px;
            "></i>
          </div>`,
          iconSize: [32, 32],
          iconAnchor: [16, 32],
          popupAnchor: [0, -32]
        });
      }

      const dbMapUnits = @json($mapUnits ?? []);

      if (dbMapUnits && dbMapUnits.length > 0) {
        dbMapUnits.forEach(function(u) {
          if (u.latitude && u.longitude) {
            const level = (u.unit_level || 'desa').toLowerCase();
            const marker = L.marker([u.latitude, u.longitude], {
              icon: getMarkerIcon(level)
            });

            let badgeBg = '#ecfdf5';
            let badgeColor = '#047857';
            if (level === 'kabupaten') {
              badgeBg = '#fef2f2';
              badgeColor = '#b91c1c';
            } else if (level === 'kecamatan') {
              badgeBg = '#eff6ff';
              badgeColor = '#1d4ed8';
            }

            const levelLabel = 'KT ' + level.toUpperCase();
            const phoneLink = u.contact_phone ? `https://wa.me/${u.contact_phone.replace(/[^0-9]/g, '')}` : '#';

            marker.bindPopup(`
              <div style="font-family: sans-serif; padding: 4px; min-width: 210px;">
                <span style="font-size: 10px; font-weight: 700; color: ${badgeColor}; background: ${badgeBg}; padding: 2px 6px; border-radius: 4px; text-transform: uppercase;">${levelLabel}</span>
                <strong style="font-size: 13px; color: #1e293b; display: block; margin: 6px 0 4px 0;">${u.unit_name}</strong>
                <div style="font-size: 11px; color: #475569; margin-bottom: 3px;"><strong>Ketua:</strong> ${u.chairman_name || '-'}</div>
                <div style="font-size: 11px; color: #64748b; margin-bottom: 6px;">${u.office_address || 'Kabupaten Bandung'}</div>
                <div style="border-top: 1px solid #e2e8f0; padding-top: 6px; display: flex; justify-content: space-between; align-items: center;">
                  <a href="{{ route('public.directory') }}?level=${level}" style="font-size: 11px; color: #047857; font-weight: 600; text-decoration: none;">Direktori →</a>
                  ${u.contact_phone ? `<a href="${phoneLink}" target="_blank" style="font-size: 11px; color: #16a34a; font-weight: 600; text-decoration: none;"><i class="ti ti-brand-whatsapp"></i> Hubungi</a>` : ''}
                </div>
              </div>
            `);
            marker.addTo(homeMap);
          }
        });
      } else {
        // Default marker
        L.marker([-7.0252, 107.5198], { icon: getMarkerIcon('kabupaten') }).addTo(homeMap)
          .bindPopup('<b>Sekretariat Karang Taruna Kab. Bandung</b><br>Soreang, Kab. Bandung')
          .openPopup();
      }
    }
  });
</script>
@endpush

