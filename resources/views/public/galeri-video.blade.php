@extends('layouts.public')

@section('title', 'Galeri Video & Liputan - Karang Taruna Kabupaten Bandung')
@section('meta_description', 'Tayangan video dokumenter, liputan aksi sosial kemanusiaan, dan podcast inspirasi pemuda Kabupaten Bandung.')

@section('content')
  <!-- Page Banner -->
  <section class="page-banner">
    <div class="container">
      <div class="breadcrumb">
        <a href="{{ route('public.home') }}"><i class="ti ti-home"></i> Beranda</a>
        <span>/</span>
        <span>Galeri & Media</span>
        <span>/</span>
        <span class="active">Galeri Video</span>
      </div>
      <h1>Galeri Video & Liputan Media</h1>
      <p>Tayangan video dokumenter, liputan aksi sosial kemanusiaan, dan podcast inspirasi pemuda Kabupaten Bandung.</p>
    </div>
  </section>

  <!-- Video Gallery Grid -->
  <section class="section">
    <div class="container">
      <div class="video-grid">
        @forelse($videos as $video)
          <article class="video-card">
            <div class="video-thumb">
              <img src="{{ $video->thumbnail_image ? asset('storage/' . $video->thumbnail_image) : asset('frontend/images/hero.svg') }}" alt="{{ $video->title }}">
              <a href="{{ $video->youtube_url }}" target="_blank" rel="noopener" class="play-btn-small" aria-label="Putar Video">
                <i class="ti ti-player-play-filled"></i>
              </a>
              @if($video->duration_minutes)
                <span class="video-duration">{{ $video->duration_minutes }} min</span>
              @endif
            </div>
            <div class="video-card-body">
              <span class="tag-badge">Liputan</span>
              <h3>{{ $video->title }}</h3>
              <p>{{ Str::limit($video->description ?? '', 90) }}</p>
              <small><i class="ti ti-calendar"></i> {{ $video->created_at->translatedFormat('d M Y') }}</small>
            </div>
          </article>
        @empty
          <div class="text-center py-12" style="grid-column: 1 / -1;">
            <i class="ti ti-video-off" style="font-size: 3rem; color: #94a3b8;"></i>
            <h3 style="margin-top: 1rem; color: #475569;">Belum ada video yang diunggah</h3>
            <p style="color: #64748b;">Dokumentasi video liputan dan kegiatan akan dipublikasikan di sini.</p>
          </div>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="pagination-wrapper" style="margin-top: 2.5rem;">
        {{ $videos->links() }}
      </div>
    </div>
  </section>
@endsection
