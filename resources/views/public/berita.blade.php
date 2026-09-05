@extends('layouts.public')

@section('title', 'Berita & Kabar Terkini - Karang Taruna Kabupaten Bandung')
@section('meta_description', 'Kumpulan Berita Terkini Karang Taruna Kabupaten Bandung - Berita Kegiatan Pusat dan Daerah Kecamatan.')

@section('content')
  <!-- Page Banner -->
  <section class="page-banner">
    <div class="container">
      <div class="breadcrumb">
        <a href="{{ route('public.home') }}"><i class="ti ti-home"></i> Beranda</a>
        <span>/</span>
        <span>Aktivitas</span>
        <span>/</span>
        <span class="active">Berita</span>
      </div>
      <h1>Berita & Kabar Terkini</h1>
      <p>Informasi kegiatan, aksi kepemudaan, inovasi sosial, dan kabar aktual dari pusat hingga pelosok kecamatan se-Kabupaten Bandung.</p>
    </div>
  </section>

  <!-- News List Section -->
  <section class="section">
    <div class="container">
      <!-- Search & Filter Controls -->
      <div class="news-filter-header">
        <div class="news-category-filters">
          <a href="{{ route('public.news') }}" class="filter-btn {{ !request('scope') && !request('kategori') ? 'active' : '' }}">Semua Berita</a>
          <a href="{{ route('public.news', ['scope' => 'Pusat/Kabupaten']) }}" class="filter-btn {{ request('scope') == 'Pusat/Kabupaten' ? 'active' : '' }}"><i class="ti ti-building-community"></i> Berita Pusat</a>
          <a href="{{ route('public.news', ['scope' => 'Kecamatan/Desa']) }}" class="filter-btn {{ request('scope') == 'Kecamatan/Desa' ? 'active' : '' }}"><i class="ti ti-map-pin"></i> Berita Daerah</a>
        </div>

        <form action="{{ route('public.news') }}" method="GET" class="news-search-input">
          @if(request('scope'))
            <input type="hidden" name="scope" value="{{ request('scope') }}">
          @endif
          @if(request('kategori'))
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
          @endif
          <i class="ti ti-search"></i>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul berita atau topik...">
        </form>
      </div>

      <!-- News Grid -->
      <div class="news-grid-list" id="newsGridList" style="margin-top: 2rem;">
        @forelse($articles as $article)
          <article class="news-card-item">
            <div class="news-card-thumb">
              <img src="{{ str_starts_with($article->featured_image ?? '', 'http') ? $article->featured_image : ($article->featured_image ? asset('storage/' . $article->featured_image) : asset('frontend/images/news-1.svg')) }}" alt="{{ $article->title }}" onerror="this.src='{{ asset('frontend/images/news-1.svg') }}'">
              <span class="category-pill {{ $article->news_scope === 'Pusat/Kabupaten' ? 'pusat' : 'daerah' }}">
                {{ $article->district->name ?? ($article->news_scope ?? 'Umum') }}
              </span>
            </div>
            <div class="news-card-body">
              <div class="news-card-meta">
                <span class="tag">{{ $article->category->name ?? 'Kegiatan' }}</span>
                <span class="date"><i class="ti ti-calendar"></i> {{ $article->published_at ? $article->published_at->translatedFormat('d M Y') : $article->created_at->translatedFormat('d M Y') }}</span>
              </div>
              <h3><a href="{{ route('public.news.detail', $article->slug) }}">{{ $article->title }}</a></h3>
              <p>{{ Str::limit($article->excerpt ?? strip_tags($article->content), 100) }}</p>
              <div class="news-card-footer">
                <small><i class="ti ti-map-pin"></i> {{ $article->district->name ?? 'Kab. Bandung' }}</small>
                <a href="{{ route('public.news.detail', $article->slug) }}" class="link-arrow">Baca <i class="ti ti-chevron-right"></i></a>
              </div>
            </div>
          </article>
        @empty
          <div class="col-span-3 text-center py-12" style="grid-column: 1 / -1;">
            <i class="ti ti-news-off" style="font-size: 3rem; color: #94a3b8;"></i>
            <h3 style="margin-top: 1rem; color: #475569;">Tidak ada berita yang ditemukan</h3>
            <p style="color: #64748b;">Silakan ubah filter pencarian atau kata kunci Anda.</p>
          </div>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="pagination-wrapper" style="margin-top: 2.5rem;">
        {{ $articles->links() }}
      </div>
    </div>
  </section>
@endsection
