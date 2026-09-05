@extends('layouts.public')

@section('title', $unit->unit_name . ' - Karang Taruna')
@section('meta_description', 'Profil kepengurusan, program kerja, berita, dan kegiatan ' . $unit->unit_name . ', Kabupaten Bandung.')

@push('styles')
<style>
  .unit-hero {
    background: linear-gradient(135deg, #047857 0%, #064e3b 100%);
    color: #fff;
    padding: 3.5rem 0 3rem;
    position: relative;
    overflow: hidden;
  }
  .unit-hero-wrap {
    display: flex;
    align-items: center;
    gap: 2rem;
    flex-wrap: wrap;
  }
  .unit-emblem {
    width: 100px;
    height: 100px;
    background: #fff;
    border-radius: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
  }
  .unit-emblem img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
  }
  .unit-hero-info {
    flex: 1;
    min-width: 260px;
  }
  .unit-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    background: rgba(255, 255, 255, 0.2);
    margin-bottom: 0.5rem;
  }
  .unit-hero h1 {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
  }
  .unit-hero p {
    color: #a7f3d0;
    font-size: 0.95rem;
  }
  .unit-quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    margin-top: -1.75rem;
    position: relative;
    z-index: 10;
  }
  .unit-stat-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    padding: 1.25rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  .unit-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
  }
  .unit-stat-card strong {
    display: block;
    font-size: 1.25rem;
    color: #0f172a;
    line-height: 1.2;
  }
  .unit-stat-card span {
    font-size: 0.8rem;
    color: #64748b;
  }
  .unit-content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-top: 2.5rem;
  }
  @media (max-width: 900px) {
    .unit-content-grid {
      grid-template-columns: 1fr;
    }
  }
  .unit-card-box {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
  }
  .unit-card-box h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-bottom: 2px solid #f1f5f9;
    padding-bottom: 0.75rem;
  }
  .unit-card-box h3 i {
    color: #047857;
  }
  .unit-info-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  .unit-info-list li {
    display: flex;
    justify-content: space-between;
    padding: 0.65rem 0;
    border-bottom: 1px solid #f8fafc;
    font-size: 0.9rem;
  }
  .unit-info-list li span {
    color: #64748b;
  }
  .unit-info-list li strong {
    color: #1e293b;
    text-align: right;
  }
  .unit-articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.25rem;
  }
  .sub-village-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.85rem;
    border-radius: 0.5rem;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    font-size: 0.85rem;
    color: #1e293b;
    text-decoration: none;
    transition: all 0.2s ease;
  }
  .sub-village-pill:hover {
    background: #ecfdf5;
    border-color: #10b981;
    color: #047857;
    transform: translateY(-2px);
  }
</style>
@endpush

@section('content')
  <!-- Unit Header Banner -->
  <section class="unit-hero">
    <div class="container">
      <div class="breadcrumb" style="margin-bottom: 1rem; color: #a7f3d0;">
        <a href="{{ route('public.home') }}" style="color: #fff;"><i class="ti ti-home"></i> Beranda</a>
        <span>/</span>
        <a href="{{ route('public.directory') }}" style="color: #fff;">Direktori</a>
        <span>/</span>
        <span class="active" style="color: #6ee7b7;">{{ $unit->unit_name }}</span>
      </div>

      <div class="unit-hero-wrap">
        <div class="unit-emblem">
          @if($unit->logo_path)
            <img src="{{ asset('storage/' . $unit->logo_path) }}" alt="{{ $unit->unit_name }}">
          @else
            <img src="{{ asset('frontend/images/logo.png') }}" alt="{{ $unit->unit_name }}">
          @endif
        </div>
        <div class="unit-hero-info">
          <div class="unit-hero-badge">
            <i class="ti ti-shield-check"></i> Karang Taruna Tingkat {{ ucfirst($unit->unit_level) }}
          </div>
          <h1>{{ $unit->unit_name }}</h1>
          <p><i class="ti ti-map-pin"></i> {{ $unit->office_address ?? 'Kabupaten Bandung, Jawa Barat' }}</p>
        </div>
        <div>
          @if($unit->contact_phone)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $unit->contact_phone) }}" target="_blank" class="btn btn-primary" style="background:#fff;color:#047857;border:none;">
              <i class="ti ti-brand-whatsapp"></i> Hubungi Pengurus
            </a>
          @endif
        </div>
      </div>
    </div>
  </section>

  <!-- Quick Stats Overlap -->
  <div class="container">
    <div class="unit-quick-stats">
      <div class="unit-stat-card">
        <div class="unit-stat-icon" style="background: #ecfdf5; color: #047857;"><i class="ti ti-users"></i></div>
        <div>
          <strong>{{ number_format($unit->total_members > 0 ? $unit->total_members : 35) }}</strong>
          <span>Kader & Anggota</span>
        </div>
      </div>
      <div class="unit-stat-card">
        <div class="unit-stat-icon" style="background: #eff6ff; color: #2563eb;"><i class="ti ti-news"></i></div>
        <div>
          <strong>{{ $articles->count() }}</strong>
          <span>Warta & Berita</span>
        </div>
      </div>
      <div class="unit-stat-card">
        <div class="unit-stat-icon" style="background: #fef3c7; color: #d97706;"><i class="ti ti-briefcase"></i></div>
        <div>
          <strong>{{ $workPrograms->count() }}</strong>
          <span>Program Kerja</span>
        </div>
      </div>
      <div class="unit-stat-card">
        <div class="unit-stat-icon" style="background: #fdf2f8; color: #db2777;"><i class="ti ti-calendar-event"></i></div>
        <div>
          <strong>{{ $events->count() }}</strong>
          <span>Agenda & Kegiatan</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Sub-site Content -->
  <section class="section" style="padding-top: 2rem;">
    <div class="container unit-content-grid">
      <!-- Left Column: Berita, Program Kerja, Agenda dari Unit Ini -->
      <div class="unit-main-feed">
        <!-- 1. Berita & Informasi Unit -->
        <div class="unit-card-box">
          <h3><i class="ti ti-news"></i> Warta Kegiatan & Berita {{ $unit->unit_name }}</h3>
          @if($articles->count() > 0)
            <div class="unit-articles-grid">
              @foreach($articles as $article)
                <article class="card" style="box-shadow: none; border: 1px solid #e2e8f0;">
                  <a href="{{ route('public.news.detail', $article->slug) }}">
                    <img class="card-img" src="{{ str_starts_with($article->featured_image ?? '', 'http') ? $article->featured_image : ($article->featured_image ? asset('storage/' . $article->featured_image) : asset('frontend/images/news-1.svg')) }}" alt="{{ $article->title }}">
                  </a>
                  <div class="card-body">
                    <span class="tag">{{ $article->category->name ?? 'Kegiatan' }}</span>
                    <div class="date">{{ $article->published_at ? $article->published_at->translatedFormat('d M Y') : $article->created_at->translatedFormat('d M Y') }}</div>
                    <h4 style="font-size: 1rem; margin: 0.35rem 0;"><a href="{{ route('public.news.detail', $article->slug) }}">{{ $article->title }}</a></h4>
                    <p style="font-size: 0.85rem;">{{ Str::limit($article->excerpt ?? strip_tags($article->content), 75) }}</p>
                  </div>
                </article>
              @endforeach
            </div>
          @else
            <p style="color: #64748b; font-style: italic;"><i class="ti ti-info-circle"></i> Belum ada artikel warta yang dipublikasikan oleh unit ini.</p>
          @endif
        </div>

        <!-- 2. Program Kerja Unit -->
        <div class="unit-card-box">
          <h3><i class="ti ti-briefcase"></i> Program Kerja & Rencana Aksi</h3>
          @if($workPrograms->count() > 0)
            <div style="display: grid; gap: 1rem;">
              @foreach($workPrograms as $prog)
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1rem; display: flex; gap: 1rem; align-items: flex-start;">
                  <div style="width: 40px; height: 40px; border-radius: 0.5rem; background: #ecfdf5; color: #047857; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0;">
                    <i class="ti ti-check"></i>
                  </div>
                  <div>
                    <span style="font-size: 0.75rem; font-weight: 700; color: #047857; background: #ecfdf5; padding: 2px 6px; border-radius: 4px;">{{ $prog->division->division_name ?? 'Program' }}</span>
                    <h4 style="margin: 0.25rem 0; font-size: 1rem; color: #1e293b;">{{ $prog->program_name }}</h4>
                    <p style="font-size: 0.85rem; color: #64748b; margin: 0;">{{ $prog->short_description ?? Str::limit(strip_tags($prog->detailed_description), 100) }}</p>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p style="color: #64748b; font-style: italic;"><i class="ti ti-info-circle"></i> Belum ada program kerja yang tercatat aktif untuk unit ini.</p>
          @endif
        </div>

        <!-- 3. Agenda Kegiatan Unit -->
        @if($events->count() > 0)
          <div class="unit-card-box">
            <h3><i class="ti ti-calendar-event"></i> Agenda Kegiatan Mendatang</h3>
            <div class="agenda">
              @foreach($events as $ev)
                <div class="agenda-item">
                  <div class="agenda-date">
                    <b>{{ $ev->event_date ? $ev->event_date->format('d') : '01' }}</b>
                    <small>{{ $ev->event_date ? $ev->event_date->translatedFormat('M Y') : '2026' }}</small>
                  </div>
                  <div>
                    <strong>{{ $ev->title }}</strong>
                    <div class="section-sub"><i class="ti ti-map-pin"></i> {{ $ev->location_venue }}</div>
                  </div>
                  <span class="status">{{ ucfirst($ev->event_status ?? 'Akan Datang') }}</span>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <!-- 4. Daftar Desa / Kelurahan di Bawah Kecamatan (Jika Unit Tingkat Kecamatan) -->
        @if($unit->unit_level === 'kecamatan' && $villages->count() > 0)
          <div class="unit-card-box">
            <h3><i class="ti ti-home-check"></i> Basis Karang Taruna Desa / Kelurahan ({{ $villages->count() }} Desa)</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
              @foreach($villages as $vill)
                @php
                  $vUnit = $vill->units->first();
                  $vSlug = $vUnit ? ($vUnit->slug ?? ($unit->slug . '-' . $vill->slug)) : ($unit->slug . '-' . $vill->slug);
                @endphp
                <a href="{{ route('public.village.detail', $vSlug) }}" class="sub-village-pill">
                  <i class="ti ti-building-community" style="color: #10b981;"></i>
                  <span>{{ $vill->type }} {{ $vill->name }}</span>
                  <i class="ti ti-arrow-right" style="font-size: 0.75rem; color: #94a3b8;"></i>
                </a>
              @endforeach
            </div>
          </div>
        @endif
      </div>

      <!-- Right Column: Sidebar Profil, Struktur Pengurus & Kontak -->
      <div class="unit-sidebar">
        <!-- Struktur Inti Pengurus -->
        <div class="unit-card-box">
          <h3><i class="ti ti-id-badge-2"></i> Pengurus Harian</h3>
          <ul class="unit-info-list">
            <li>
              <span>Ketua:</span>
              <strong>{{ $unit->chairman_name ?? '-' }}</strong>
            </li>
            <li>
              <span>Sekretaris:</span>
              <strong>{{ $unit->secretary_name ?? '-' }}</strong>
            </li>
            <li>
              <span>Bendahara:</span>
              <strong>{{ $unit->treasurer_name ?? '-' }}</strong>
            </li>
            <li>
              <span>Masa Bakti:</span>
              <strong>{{ $unit->period_start_year ?? '2024' }} - {{ $unit->period_end_year ?? '2029' }}</strong>
            </li>
            <li>
              <span>Status Lembaga:</span>
              <strong style="color: #059669;">{{ $unit->status_aktif }}</strong>
            </li>
          </ul>
        </div>

        <!-- Jajaran Anggota / Bidang Lainnya -->
        @if($unit->members->count() > 0)
          <div class="unit-card-box">
            <h3><i class="ti ti-users"></i> Struktur Pengurus</h3>
            <div style="display: grid; gap: 0.75rem;">
              @foreach($unit->members as $mbr)
                <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0; border-bottom: 1px solid #f8fafc;">
                  <div style="width: 36px; height: 36px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #475569; font-size: 0.85rem;">
                    {{ substr($mbr->full_name, 0, 1) }}
                  </div>
                  <div>
                    <strong style="font-size: 0.9rem; color: #1e293b; display: block;">{{ $mbr->full_name }}</strong>
                    <span style="font-size: 0.75rem; color: #047857; font-weight: 600;">{{ $mbr->position_role }}</span>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Informasi Sekretariat & Kontak -->
        <div class="unit-card-box">
          <h3><i class="ti ti-building-warehouse"></i> Sekretariat Resmi</h3>
          <ul class="unit-info-list">
            <li>
              <span>Alamat:</span>
              <strong>{{ $unit->office_address ?? '-' }}</strong>
            </li>
            @if($unit->contact_phone)
              <li>
                <span>No. Telp / WA:</span>
                <strong>{{ $unit->contact_phone }}</strong>
              </li>
            @endif
            @if($unit->contact_email)
              <li>
                <span>Email:</span>
                <strong>{{ $unit->contact_email }}</strong>
              </li>
            @endif
            @if($unit->sk_number)
              <li>
                <span>No. SK:</span>
                <strong>{{ $unit->sk_number }}</strong>
              </li>
            @endif
          </ul>

          @if($unit->latitude && $unit->longitude)
            <div style="margin-top: 1rem;">
              <a href="https://www.google.com/maps?q={{ $unit->latitude }},{{ $unit->longitude }}" target="_blank" class="btn btn-primary" style="width: 100%; justify-content: center; font-size: 0.85rem;">
                <i class="ti ti-map-pin"></i> Petunjuk Arah (Maps)
              </a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
@endsection
