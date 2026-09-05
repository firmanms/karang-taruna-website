@extends('layouts.public')

@section('title', 'Prestasi Pemuda - Karang Taruna Kabupaten Bandung')
@section('meta_description', 'Prestasi, penghargaan, dan apresiasi karya pemuda Karang Taruna Kabupaten Bandung di tingkat daerah, provinsi, dan nasional.')

@section('content')
  <!-- Page Banner -->
  <section class="page-banner">
    <div class="container">
      <div class="breadcrumb">
        <a href="{{ route('public.home') }}"><i class="ti ti-home"></i> Beranda</a>
        <span>/</span>
        <span>Profil</span>
        <span>/</span>
        <span class="active">Prestasi Pemuda</span>
      </div>
      <h1>Prestasi & Penghargaan Pemuda</h1>
      <p>Apresiasi atas dedikasi, kreasi, dan inovasi membanggakan pemuda Karang Taruna Kabupaten Bandung di tingkat daerah, provinsi, dan nasional.</p>
    </div>
  </section>

  <!-- Achievement Cards Grid -->
  <section class="section">
    <div class="container">
      <div class="achievements-full-grid">
        @forelse($achievements as $ach)
          <div class="achievement-full-card">
            <div class="ach-badge-year">{{ $ach->year }}</div>
            <img src="{{ $ach->certificate_image ? asset('storage/' . $ach->certificate_image) : asset('frontend/images/gallery-6.svg') }}" alt="{{ $ach->title }}" class="ach-img">
            <div class="ach-body">
              <span class="tag-badge">{{ $ach->achievement_level }}</span>
              <h3>{{ $ach->title }}</h3>
              <p>{{ $ach->description ?? '-' }}</p>
              <div class="ach-footer">
                <span><i class="ti ti-building-community"></i> Diberikan oleh: {{ $ach->awarding_body ?? 'Pemerintah / Lembaga' }}</span>
              </div>
            </div>
          </div>
        @empty
          <div class="text-center py-12" style="grid-column: 1 / -1;">
            <i class="ti ti-trophy-off" style="font-size: 3rem; color: #94a3b8;"></i>
            <h3 style="margin-top: 1rem; color: #475569;">Belum ada data prestasi pemuda</h3>
            <p style="color: #64748b;">Informasi penghargaan dan apresiasi akan ditampilkan di halaman ini.</p>
          </div>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="pagination-wrapper" style="margin-top: 2.5rem;">
        {{ $achievements->links() }}
      </div>
    </div>
  </section>
@endsection
