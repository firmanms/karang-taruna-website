@extends('layouts.public')

@section('title', $article->title . ' - Karang Taruna Kabupaten Bandung')
@section('meta_description', Str::limit($article->excerpt ?? strip_tags($article->content), 150))

@section('content')
  <!-- Breadcrumb & Header -->
  <section class="page-banner page-banner-news">
    <div class="container">
      <div class="breadcrumb">
        <a href="{{ route('public.home') }}"><i class="ti ti-home"></i> Beranda</a>
        <span>/</span>
        <a href="{{ route('public.news') }}">Berita</a>
        <span>/</span>
        <span class="active">Detail Berita</span>
      </div>
      <div class="news-category-badge">{{ $article->category->name ?? 'Kegiatan' }}</div>
      <h1>{{ $article->title }}</h1>
      <div class="news-post-meta">
        <span><i class="ti ti-user"></i> {{ $article->author->name ?? ($article->unit->unit_name ?? 'Humas Karang Taruna') }}</span>
        <span>•</span>
        <span><i class="ti ti-calendar"></i> {{ $article->published_at ? $article->published_at->translatedFormat('d F Y') : $article->created_at->translatedFormat('d F Y') }}</span>
        <span>•</span>
        <span><i class="ti ti-eye"></i> {{ number_format($article->views_count) }} Pembaca</span>
      </div>
    </div>
  </section>

  <!-- News Content & Sidebar -->
  <section class="section news-detail-section">
    <div class="container static-grid">
      <!-- Article Content -->
      <article class="news-article">
        <div class="article-featured-image">
          <img src="{{ str_starts_with($article->featured_image ?? '', 'http') ? $article->featured_image : ($article->featured_image ? asset('storage/' . $article->featured_image) : asset('frontend/images/news-1.svg')) }}" alt="{{ $article->title }}" onerror="this.src='{{ asset('frontend/images/news-1.svg') }}'">
          @if($article->image_caption)
            <span class="img-caption">{{ $article->image_caption }}</span>
          @endif
        </div>

        <div class="article-body">
          {!! $article->content !!}

          <!-- Social Share & Tags -->
          <div class="article-footer" style="margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div class="article-tags">
              <span>Tags:</span>
              <a href="{{ route('public.news') }}">#{{ $article->category->name ?? 'Berita' }}</a>
              <a href="{{ route('public.news') }}">#KarangTaruna</a>
              <a href="{{ route('public.news') }}">#BandungBedas</a>
            </div>
            <div class="article-share" style="display: flex; gap: 0.5rem; align-items: center;">
              <span>Bagikan:</span>
              <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . url()->current()) }}" target="_blank" class="share-btn whatsapp" title="Bagikan ke WhatsApp"><i class="ti ti-brand-whatsapp"></i></a>
              <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-btn facebook" title="Bagikan ke Facebook"><i class="ti ti-brand-facebook"></i></a>
              <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="share-btn twitter" title="Bagikan ke X/Twitter"><i class="ti ti-brand-x"></i></a>
            </div>
          </div>
        </div>
      </article>

      <!-- Sidebar -->
      <aside class="static-sidebar">
        <!-- Berita Terkait -->
        <div class="sidebar-card">
          <h3>Berita Terkait</h3>
          <div class="sidebar-news-list">
            @forelse($relatedArticles as $rel)
              <a href="{{ route('public.news.detail', $rel->slug) }}" class="sidebar-news-item">
                <img src="{{ str_starts_with($rel->featured_image ?? '', 'http') ? $rel->featured_image : ($rel->featured_image ? asset('storage/' . $rel->featured_image) : asset('frontend/images/news-2.svg')) }}" alt="{{ $rel->title }}" onerror="this.src='{{ asset('frontend/images/news-2.svg') }}'">
                <div>
                  <h4>{{ Str::limit($rel->title, 55) }}</h4>
                  <small><i class="ti ti-calendar"></i> {{ $rel->published_at ? $rel->published_at->translatedFormat('d M Y') : $rel->created_at->translatedFormat('d M Y') }}</small>
                </div>
              </a>
            @empty
              <p class="text-sm text-gray-500 py-2">Belum ada berita terkait lainnya.</p>
            @endforelse
          </div>
        </div>

        <!-- Call to Action Card -->
        <div class="sidebar-card cta-card">
          <h3>Layanan Informasi</h3>
          <p>Dapatkan informasi resmi dan validasi NIK bagi penerima manfaat sosial.</p>
          <a href="{{ route('public.ppks') }}" class="btn btn-primary" style="width:100%;justify-content:center">Cek Data PPKS</a>
        </div>
      </aside>
    </div>
  </section>
@endsection
