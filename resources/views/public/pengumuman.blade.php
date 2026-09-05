@extends('layouts.public')

@section('title', 'Pusat Pengumuman & Edaran - Karang Taruna Kabupaten Bandung')
@section('meta_description', 'Pusat Pengumuman, Surat Edaran, dan Informasi Resmi Karang Taruna Kabupaten Bandung.')

@section('content')
  <!-- Page Banner -->
  <section class="page-banner">
    <div class="container">
      <div class="breadcrumb">
        <a href="{{ route('public.home') }}"><i class="ti ti-home"></i> Beranda</a>
        <span>/</span>
        <span>Aktivitas</span>
        <span>/</span>
        <span class="active">Pengumuman</span>
      </div>
      <h1>Pusat Pengumuman & Edaran</h1>
      <p>Pemberitahuan resmi, surat edaran organisasi, pendaftaran kegiatan kepemudaan, dan informasi penting lainnya.</p>
    </div>
  </section>

  <!-- Announcement Archive Section -->
  <section class="section">
    <div class="container">
      <!-- Announcement Grid -->
      <div class="announcement-archive-grid" id="announcementGrid">
        @forelse($announcements as $ann)
          <article class="announcement-archive-card {{ $ann->is_pinned ? 'pinned' : '' }}">
            <div class="announcement-card-top">
              <span class="announcement-pill {{ $ann->is_pinned ? '' : 'pill-info' }}">
                <i class="ti ti-{{ $ann->is_pinned ? 'pin' : 'bell' }}"></i> {{ $ann->category ?? 'Pengumuman' }}
              </span>
              @if($ann->end_date)
                <span class="announcement-expiry"><i class="ti ti-clock"></i> Batas: {{ \Carbon\Carbon::parse($ann->end_date)->translatedFormat('d F Y') }}</span>
              @endif
            </div>
            <div class="announcement-meta-row">
              <span class="meta-date"><i class="ti ti-calendar"></i> {{ $ann->created_at->translatedFormat('d F Y') }}</span>
            </div>
            <h2>{{ $ann->title }}</h2>
            <p>{{ $ann->excerpt ?? strip_tags($ann->content) }}</p>
            <div class="announcement-card-action">
              @if($ann->attachment_file)
                <a href="{{ asset('storage/' . $ann->attachment_file) }}" target="_blank" class="btn-primary-sm"><i class="ti ti-download"></i> Unduh Lampiran</a>
              @endif
              <span class="badge-status-active">{{ $ann->is_active ? 'Aktif' : 'Arsip' }}</span>
            </div>
          </article>
        @empty
          <div class="text-center py-12" style="grid-column: 1 / -1;">
            <i class="ti ti-bell-off" style="font-size: 3rem; color: #94a3b8;"></i>
            <h3 style="margin-top: 1rem; color: #475569;">Tidak ada pengumuman saat ini</h3>
            <p style="color: #64748b;">Pengumuman resmi dan surat edaran terbaru akan tampil di sini.</p>
          </div>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="pagination-wrapper" style="margin-top: 2.5rem;">
        {{ $announcements->links() }}
      </div>
    </div>
  </section>
@endsection
