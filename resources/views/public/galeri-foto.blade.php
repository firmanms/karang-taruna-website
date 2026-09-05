@extends('layouts.public')

@section('title', 'Galeri Dokumentasi Foto - Karang Taruna Kabupaten Bandung')
@section('meta_description', 'Potret aksi nyata, gotong royong, dan kebersamaan pemuda Karang Taruna di seluruh penjuru Kabupaten Bandung.')

@section('content')
  <!-- Page Banner -->
  <section class="page-banner">
    <div class="container">
      <div class="breadcrumb">
        <a href="{{ route('public.home') }}"><i class="ti ti-home"></i> Beranda</a>
        <span>/</span>
        <span>Galeri & Media</span>
        <span>/</span>
        <span class="active">Galeri Foto</span>
      </div>
      <h1>Galeri Dokumentasi Foto</h1>
      <p>Potret aksi nyata, gotong royong, dan kebersamaan pemuda Karang Taruna di seluruh penjuru Kabupaten Bandung.</p>
    </div>
  </section>

  <!-- Photo Grid -->
  <section class="section">
    <div class="container">
      <div class="photo-gallery-grid">
        @forelse($photos as $photo)
          <div class="photo-card">
            <div class="photo-wrap">
              <img src="{{ str_starts_with($photo->image_path ?? '', 'http') ? $photo->image_path : asset('storage/' . $photo->image_path) }}" alt="{{ $photo->title }}" onerror="this.src='{{ asset('frontend/images/gallery-1.svg') }}'">
              <div class="photo-overlay">
                <span class="photo-tag">{{ $photo->category->name ?? 'Dokumentasi' }}</span>
                <h4>{{ $photo->title }}</h4>
                <p><i class="ti ti-calendar"></i> {{ $photo->created_at->translatedFormat('d F Y') }} • {{ $photo->unit->unit_name ?? 'Kab. Bandung' }}</p>
              </div>
            </div>
          </div>
        @empty
          <div class="text-center py-12" style="grid-column: 1 / -1;">
            <i class="ti ti-photo-off" style="font-size: 3rem; color: #94a3b8;"></i>
            <h3 style="margin-top: 1rem; color: #475569;">Belum ada dokumentasi foto</h3>
            <p style="color: #64748b;">Foto dokumentasi kegiatan dan aksi pemuda akan muncul di sini.</p>
          </div>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="pagination-wrapper" style="margin-top: 2.5rem;">
        {{ $photos->links() }}
      </div>
    </div>
  </section>
@endsection
