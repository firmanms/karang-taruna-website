@extends('layouts.public')

@section('title', 'Peta Sebaran & Wilayah - Karang Taruna Kabupaten Bandung')
@section('meta_description', 'Peta Sebaran GIS dan Direktori Karang Taruna di 31 Kecamatan dan 280 Desa/Kelurahan Kabupaten Bandung.')

@push('styles')
<style>
  #mapContainer {
    height: 480px;
    width: 100%;
    border-radius: 1rem;
    z-index: 10;
  }
</style>
@endpush

@section('content')
  <!-- Page Banner -->
  <section class="page-banner">
    <div class="container">
      <div class="breadcrumb">
        <a href="{{ route('public.home') }}"><i class="ti ti-home"></i> Beranda</a>
        <span>/</span>
        <span>Profil</span>
        <span>/</span>
        <span class="active">Peta Sebaran</span>
      </div>
      <h1>Peta Sebaran & Wilayah</h1>
      <p>Jangkauan organisasi, direktori pengurus kecamatan, dan data sebaran 31 Karang Taruna Kecamatan serta 280 Desa/Kelurahan se-Kabupaten Bandung.</p>
    </div>
  </section>

  <!-- Map & Stats Section -->
  <section class="section">
    <div class="container">
      <!-- Stats Row -->
      <div class="territory-stats-grid">
        <div class="territory-stat-card">
          <div class="stat-icon-wrap"><i class="ti ti-map-2"></i></div>
          <div>
            <strong>{{ count($districts) }}</strong>
            <span>Kecamatan Terjangkau</span>
          </div>
        </div>
        <div class="territory-stat-card">
          <div class="stat-icon-wrap"><i class="ti ti-home-check"></i></div>
          <div>
            <strong>280</strong>
            <span>Desa / Kelurahan Aktif</span>
          </div>
        </div>
        <div class="territory-stat-card">
          <div class="stat-icon-wrap"><i class="ti ti-users"></i></div>
          <div>
            <strong>14.500+</strong>
            <span>Kader & Anggota Terdata</span>
          </div>
        </div>
        <div class="territory-stat-card">
          <div class="stat-icon-wrap"><i class="ti ti-building-community"></i></div>
          <div>
            <strong>100%</strong>
            <span>Cakupan Wilayah Kabupaten</span>
          </div>
        </div>
      </div>

      <!-- Interactive Map Visual Card -->
      <div class="map-visual-card" style="margin-top: 2rem;">
        <div class="map-visual-head" style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
          <div>
            <h2>Peta Interaktif GIS Kabupaten Bandung</h2>
            <p>Titik sebaran kantor sekretariat dan unit kerja Karang Taruna.</p>
          </div>
          <span class="map-badge-live"><span class="pulse-dot"></span> {{ count($units) }} Titik Koordinat</span>
        </div>
        <div id="mapContainer"></div>
      </div>

      <!-- Kecamatan Grid -->
      <div class="directory-section-header" style="margin-top: 3rem;">
        <div>
          <h2>Sebaran Kecamatan se-Kabupaten Bandung</h2>
          <p>Daftar 31 kecamatan di wilayah administratif Kabupaten Bandung.</p>
        </div>
      </div>

      <div class="kecamatan-grid" id="kecamatanGrid" style="margin-top: 1.5rem;">
        @foreach($districts as $dist)
          <div class="kecamatan-card">
            <div class="kec-card-head">
              <h3>Kecamatan {{ $dist->name }}</h3>
            </div>
            <div class="kec-card-body">
              <p><i class="ti ti-building"></i> <strong>Kode:</strong> {{ $dist->code }}</p>
              <p><i class="ti ti-users"></i> <strong>Jumlah Unit:</strong> {{ $dist->units_count ?? 1 }} Unit Aktif</p>
            </div>
            <div class="kec-card-footer">
              <span class="status-aktif"><i class="ti ti-circle-check"></i> Wilayah Terverifikasi</span>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Peta Leaflet (Kabupaten Bandung Center: -7.0252, 107.5198)
    const map = L.map('mapContainer').setView([-7.0252, 107.5198], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18,
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const unitsData = @json($units);

    if (unitsData && unitsData.length > 0) {
      unitsData.forEach(function(u) {
        if (u.latitude && u.longitude) {
          const marker = L.marker([u.latitude, u.longitude]).addTo(map);
          marker.bindPopup(`
            <div style="font-family: sans-serif; padding: 4px;">
              <strong style="font-size: 14px; color: #047857;">${u.unit_name}</strong><br>
              <span style="font-size: 12px; color: #475569;">Ketua: ${u.chairman_name || '-'}</span><br>
              <span style="font-size: 12px; color: #64748b;">Kontak: ${u.contact_phone || '-'}</span><br>
              <small style="font-size: 11px; color: #94a3b8;">${u.office_address || ''}</small>
            </div>
          `);
        }
      });
    } else {
      // Default marker di Soreang jika belum ada koordinat di DB
      L.marker([-7.0252, 107.5198]).addTo(map)
        .bindPopup('<b>Sekretariat Karang Taruna Kab. Bandung</b><br>Soreang, Kab. Bandung')
        .openPopup();
    }
  });
</script>
@endpush
