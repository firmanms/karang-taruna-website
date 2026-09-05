@extends('layouts.public')

@section('title', 'Direktori Karang Taruna - Kabupaten, Kecamatan & Desa')
@section('meta_description', 'Database kepengurusan, kontak sekretariat, dan informasi legalitas Karang Taruna Tingkat Kabupaten, 31 Kecamatan, dan 280 Desa/Kelurahan.')

@section('content')
  <!-- Page Banner -->
  <section class="page-banner">
    <div class="container">
      <div class="breadcrumb">
        <a href="{{ route('public.home') }}"><i class="ti ti-home"></i> Beranda</a>
        <span>/</span>
        <span>Profil</span>
        <span>/</span>
        <span class="active">Direktori Organisasi</span>
      </div>
      <h1>Direktori Karang Taruna</h1>
      <p>Database kepengurusan, kontak sekretariat, dan informasi legalitas organisasi Karang Taruna Tingkat Kabupaten, 31 Kecamatan, dan 280 Desa/Kelurahan.</p>
    </div>
  </section>

  <!-- Main Directory Content -->
  <section class="section">
    <div class="container">
      <!-- Level Tier Selector (Kabupaten, Kecamatan, Desa) -->
      <div class="dir-level-tabs">
        <a href="{{ route('public.directory', ['level' => 'kabupaten']) }}" class="dir-level-btn {{ request('level', 'kabupaten') == 'kabupaten' ? 'active' : '' }}">
          <i class="ti ti-building-community"></i>
          <div>
            <strong>Tingkat Kabupaten</strong>
            <small>Pengurus Harian</small>
          </div>
        </a>
        <a href="{{ route('public.directory', ['level' => 'kecamatan']) }}" class="dir-level-btn {{ request('level') == 'kecamatan' ? 'active' : '' }}">
          <i class="ti ti-map-pin"></i>
          <div>
            <strong>Tingkat Kecamatan</strong>
            <small>31 Kecamatan</small>
          </div>
        </a>
        <a href="{{ route('public.directory', ['level' => 'desa']) }}" class="dir-level-btn {{ request('level') == 'desa' ? 'active' : '' }}">
          <i class="ti ti-home-check"></i>
          <div>
            <strong>Tingkat Desa / Kelurahan</strong>
            <small>280 Basis Desa</small>
          </div>
        </a>
      </div>

      <!-- Direktori Cards List -->
      <div class="kecamatan-dir-grid" style="margin-top: 2rem;">
        @forelse($units as $unit)
          <div class="dir-kec-card">
            <div class="dir-kec-top">
              <h4>{{ $unit->unit_name }}</h4>
            </div>
            <div class="dir-kec-body">
              <p><i class="ti ti-user-check"></i> <strong>Ketua:</strong> {{ $unit->chairman_name ?? '-' }}</p>
              <p><i class="ti ti-map-pin"></i> <strong>Sekretariat:</strong> {{ $unit->office_address ?? '-' }}</p>
              <p><i class="ti ti-calendar"></i> <strong>Periode:</strong> {{ $unit->period_start_year ?? '2024' }} - {{ $unit->period_end_year ?? '2029' }}</p>
              <p><i class="ti ti-phone"></i> <strong>Kontak:</strong> {{ $unit->contact_phone ?? '-' }}</p>
            </div>
            <div class="dir-kec-footer">
              <span class="status-verified"><i class="ti ti-shield-check"></i> {{ $unit->status_aktif }}</span>
              <div style="display: flex; gap: 0.5rem; align-items: center; margin-left: auto;">
                <a href="{{ $unit->public_url }}" class="btn btn-primary" style="padding: 0.35rem 0.75rem; font-size: 0.8rem; border-radius: 0.5rem; text-decoration: none;">
                  Profil & Konten →
                </a>
                @if($unit->contact_phone)
                  <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $unit->contact_phone) }}" target="_blank" class="btn-wa-sm" title="Hubungi WhatsApp"><i class="ti ti-brand-whatsapp"></i></a>
                @endif
              </div>
            </div>
          </div>
        @empty
          <div class="text-center py-12" style="grid-column: 1 / -1;">
            <i class="ti ti-folder-off" style="font-size: 3rem; color: #94a3b8;"></i>
            <h3 style="margin-top: 1rem; color: #475569;">Tidak ada unit direktori yang ditemukan</h3>
            <p style="color: #64748b;">Silakan pilih tingkatan kepengurusan di atas.</p>
          </div>
        @endforelse
      </div>

      <!-- Pagination -->
      <div class="pagination-wrapper" style="margin-top: 2.5rem;">
        {{ $units->links() }}
      </div>
    </div>
  </section>
@endsection
