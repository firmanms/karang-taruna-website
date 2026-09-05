@extends('layouts.public')

@section('title', 'Agenda & Kegiatan - Karang Taruna Kabupaten Bandung')
@section('meta_description', 'Jadwal Agenda Kegiatan, Aksi Sosial, dan Pelatihan Pemuda Karang Taruna Kabupaten Bandung.')

@section('content')
  <!-- Page Banner -->
  <section class="page-banner">
    <div class="container">
      <div class="breadcrumb">
        <a href="{{ route('public.home') }}"><i class="ti ti-home"></i> Beranda</a>
        <span>/</span>
        <span>Aktivitas</span>
        <span>/</span>
        <span class="active">Agenda</span>
      </div>
      <h1>Agenda & Kegiatan</h1>
      <p>Jadwal lengkap kegiatan bakti sosial, workshop, seminar, pelatihan kerja, dan agenda kepemudaan mendatang di Kabupaten Bandung.</p>
    </div>
  </section>

  <!-- Agenda Content Section -->
  <section class="section">
    <div class="container">
      <!-- Search & Filter Controls -->
      <div class="news-filter-header">
        <div class="news-category-filters">
          <a href="{{ route('public.events') }}" class="filter-btn {{ !request('status') ? 'active' : '' }}">Semua Agenda</a>
          <a href="{{ route('public.events', ['status' => 'upcoming']) }}" class="filter-btn {{ request('status') == 'upcoming' ? 'active' : '' }}"><i class="ti ti-calendar-time"></i> Akan Datang</a>
          <a href="{{ route('public.events', ['status' => 'completed']) }}" class="filter-btn {{ request('status') == 'completed' ? 'active' : '' }}"><i class="ti ti-circle-check"></i> Selesai</a>
        </div>
      </div>

      <!-- Agenda List Cards -->
      <div class="agenda-full-list" id="agendaGrid" style="margin-top: 2rem;">
        @forelse($events as $event)
          <article class="agenda-full-card">
            <div class="agenda-badge-date">
              <span class="date-day">{{ $event->event_date ? $event->event_date->format('d') : '01' }}</span>
              <span class="date-month">{{ $event->event_date ? $event->event_date->translatedFormat('M Y') : 'Jan 2026' }}</span>
              @if($event->start_time)
                <span class="date-time"><i class="ti ti-clock"></i> {{ \Carbon\Carbon::parse($event->start_time)->format('H.i') }} WIB</span>
              @endif
            </div>
            <div class="agenda-details">
              <div class="agenda-meta-top">
                <span class="badge-status-active">{{ ucfirst($event->event_status) }}</span>
                <span class="agenda-cat-tag"><i class="ti ti-tag"></i> {{ $event->category->name ?? 'Kegiatan' }}</span>
              </div>
              <h2>{{ $event->title }}</h2>
              <p>{{ Str::limit($event->description ?? '', 180) }}</p>
              <div class="agenda-location-bar">
                <span><i class="ti ti-map-pin"></i> <strong>Lokasi:</strong> {{ $event->location_venue ?? 'Kabupaten Bandung' }}</span>
                <span><i class="ti ti-user-check"></i> <strong>Penyelenggara:</strong> {{ $event->organizer_name ?? ($event->unit->unit_name ?? 'Karang Taruna') }}</span>
              </div>
            </div>
          </article>
        @empty
          <div class="text-center py-12">
            <i class="ti ti-calendar-off" style="font-size: 3rem; color: #94a3b8;"></i>
            <h3 style="margin-top: 1rem; color: #475569;">Tidak ada agenda kegiatan saat ini</h3>
            <p style="color: #64748b;">Silakan periksa kembali beberapa saat lagi untuk update agenda mendatang.</p>
          </div>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="pagination-wrapper" style="margin-top: 2.5rem;">
        {{ $events->links() }}
      </div>
    </div>
  </section>
@endsection
