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
      <div class="territory-stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));">
        <div class="territory-stat-card">
          <div class="stat-icon-wrap"><i class="ti ti-map-2"></i></div>
          <div>
            <strong>{{ $totalDistricts ?? 31 }}</strong>
            <span>Kecamatan</span>
          </div>
        </div>
        <div class="territory-stat-card">
          <div class="stat-icon-wrap"><i class="ti ti-home-check"></i></div>
          <div>
            <strong>{{ $totalVillages ?? 280 }}</strong>
            <span>Desa / Kelurahan</span>
          </div>
        </div>
        <div class="territory-stat-card">
          <div class="stat-icon-wrap"><i class="ti ti-building-community"></i></div>
          <div>
            <strong>{{ $totalUnits ?? 312 }}</strong>
            <span>Unit Karang Taruna</span>
          </div>
        </div>
        <div class="territory-stat-card">
          <div class="stat-icon-wrap"><i class="ti ti-users"></i></div>
          <div>
            <strong>{{ number_format($totalMembers > 0 ? $totalMembers : 14500, 0, ',', '.') }}</strong>
            <span>Kader & Anggota</span>
          </div>
        </div>
        <div class="territory-stat-card">
          <div class="stat-icon-wrap"><i class="ti ti-calendar-event"></i></div>
          <div>
            <strong>{{ $totalEvents ?? 0 }}</strong>
            <span>Agenda Kegiatan</span>
          </div>
        </div>
        <div class="territory-stat-card">
          <div class="stat-icon-wrap"><i class="ti ti-briefcase"></i></div>
          <div>
            <strong>{{ $totalPrograms ?? 0 }}</strong>
            <span>Program Kerja</span>
          </div>
        </div>
        <div class="territory-stat-card">
          <div class="stat-icon-wrap"><i class="ti ti-trophy"></i></div>
          <div>
            <strong>{{ $totalAchievements ?? 0 }}</strong>
            <span>Prestasi Pemuda</span>
          </div>
        </div>
      </div>

      <!-- Interactive Map Visual Card with Filter Header -->
      <div class="map-visual-card" style="margin-top: 2rem;">
        <div class="map-visual-head" style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
          <div>
            <h2>Peta Interaktif GIS Karang Taruna</h2>
            <p>Visualisasi pemetaan unit kerja Karang Taruna se-Kabupaten Bandung.</p>
          </div>
          <span class="map-badge-live"><span class="pulse-dot"></span> <span id="markerCountDisplay">{{ count($units) }}</span> Titik Terpetakan</span>
        </div>

        <!-- GIS Filter Controls Bar -->
        <div class="gis-filter-bar" style="display: flex; gap: 1rem; margin-bottom: 1.25rem; flex-wrap: wrap; background: #f8fafc; padding: 1rem; border-radius: 0.75rem; border: 1px solid #e2e8f0;">
          <div style="flex: 1; min-width: 200px;">
            <label style="font-size: 12px; font-weight: 600; color: #475569; display: block; margin-bottom: 4px;"><i class="ti ti-map-pin"></i> Filter Kecamatan</label>
            <select id="filterDistrict" class="form-control" style="width: 100%; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #cbd5e1; background: #fff; font-size: 14px;">
              <option value="">-- Semua Kecamatan --</option>
              @foreach($districts as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
              @endforeach
            </select>
          </div>

          <div style="flex: 1; min-width: 180px;">
            <label style="font-size: 12px; font-weight: 600; color: #475569; display: block; margin-bottom: 4px;"><i class="ti ti-layers-intersect"></i> Tingkatan Unit</label>
            <select id="filterLevel" class="form-control" style="width: 100%; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #cbd5e1; background: #fff; font-size: 14px;">
              <option value="">-- Semua Tingkatan --</option>
              <option value="kabupaten">Tingkat Kabupaten</option>
              <option value="kecamatan">Tingkat Kecamatan</option>
              <option value="desa">Tingkat Desa/Kelurahan</option>
            </select>
          </div>

          <div style="display: flex; align-items: flex-end;">
            <button id="resetGisFilter" type="button" style="padding: 0.5rem 1rem; background: #047857; color: #fff; border: none; border-radius: 0.5rem; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px;">
              <i class="ti ti-rotate-clockwise"></i> Reset Filter
            </button>
          </div>
        </div>

        <!-- Legend Warna Tingkatan Unit -->
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; margin-bottom: 1rem; font-size: 0.85rem; color: #475569; background: #fff; padding: 0.6rem 1rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
          <span style="font-weight: 600; color: #1e293b;"><i class="ti ti-info-circle"></i> Keterangan Titik Marker:</span>
          <span style="display: inline-flex; align-items: center; gap: 0.35rem;">
            <span style="width: 14px; height: 14px; border-radius: 50%; background-color: #ef4444; border: 2px solid #fff; box-shadow: 0 0 0 1px #ef4444;"></span> <strong>Kabupaten</strong> (Merah)
          </span>
          <span style="display: inline-flex; align-items: center; gap: 0.35rem;">
            <span style="width: 14px; height: 14px; border-radius: 50%; background-color: #3b82f6; border: 2px solid #fff; box-shadow: 0 0 0 1px #3b82f6;"></span> <strong>Kecamatan</strong> (Biru)
          </span>
          <span style="display: inline-flex; align-items: center; gap: 0.35rem;">
            <span style="width: 14px; height: 14px; border-radius: 50%; background-color: #10b981; border: 2px solid #fff; box-shadow: 0 0 0 1px #10b981;"></span> <strong>Desa / Kelurahan</strong> (Hijau)
          </span>
        </div>

        <div id="mapContainer"></div>
      </div>

      <!-- Kecamatan Grid -->
      <div class="directory-section-header" style="margin-top: 3rem;">
        <div>
          <h2>Sebaran 31 Kecamatan se-Kabupaten Bandung</h2>
          <p>Daftar wilayah administratif dan jangkauan organisasi Karang Taruna.</p>
        </div>
      </div>

      <div class="kecamatan-grid" id="kecamatanGrid" style="margin-top: 1.5rem;">
        @foreach($districts as $dist)
          <div class="kecamatan-card">
            <div class="kec-card-head">
              <h3>Kecamatan {{ $dist->name }}</h3>
            </div>
            <div class="kec-card-body">
              <p><i class="ti ti-building"></i> <strong>Kode Kemendagri:</strong> {{ $dist->kemendagri_code ?? '-' }}</p>
              <p><i class="ti ti-users"></i> <strong>Jumlah Unit:</strong> {{ $dist->units_count ?? 1 }} Unit Lembaga</p>
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
      attribution: '&copy; OpenStreetMap contributors | Karang Taruna Kab. Bandung'
    }).addTo(map);

    const unitsData = @json($units);
    let markersLayer = L.layerGroup().addTo(map);

    // Helper untuk custom colored marker icon
    function getMarkerIcon(level) {
      let color = '#10b981'; // default desa: hijau
      let iconName = 'ti-home';

      if (level === 'kabupaten') {
        color = '#ef4444'; // merah
        iconName = 'ti-building-monument';
      } else if (level === 'kecamatan') {
        color = '#3b82f6'; // biru
        iconName = 'ti-building-community';
      }

      return L.divIcon({
        className: 'custom-map-pin',
        html: `<div style="
          background-color: ${color};
          width: 32px;
          height: 32px;
          border-radius: 50% 50% 50% 0;
          transform: rotate(-45deg);
          display: flex;
          align-items: center;
          justify-content: center;
          border: 2px solid #ffffff;
          box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        ">
          <i class="ti ${iconName}" style="
            transform: rotate(45deg);
            color: #ffffff;
            font-size: 14px;
          "></i>
        </div>`,
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
      });
    }

    function renderMarkers(filteredUnits) {
      markersLayer.clearLayers();
      document.getElementById('markerCountDisplay').textContent = filteredUnits.length;

      if (filteredUnits && filteredUnits.length > 0) {
        filteredUnits.forEach(function(u) {
          if (u.latitude && u.longitude) {
            const level = (u.unit_level || 'desa').toLowerCase();
            const marker = L.marker([u.latitude, u.longitude], {
              icon: getMarkerIcon(level)
            });
            
            let badgeBg = '#ecfdf5';
            let badgeColor = '#047857';
            if (level === 'kabupaten') {
              badgeBg = '#fef2f2';
              badgeColor = '#b91c1c';
            } else if (level === 'kecamatan') {
              badgeBg = '#eff6ff';
              badgeColor = '#1d4ed8';
            }

            let unitSlug = u.slug || (u.district ? u.district.slug : u.id);
            let detailUrl = "{{ route('public.directory') }}";
            if (level === 'kecamatan') {
              detailUrl = "{{ url('/kecamatan') }}/" + unitSlug;
            } else if (level === 'desa') {
              if (!u.slug && u.district && u.village) {
                unitSlug = u.district.slug + '-' + u.village.slug;
              }
              detailUrl = "{{ url('/desa') }}/" + unitSlug;
            } else if (level === 'kabupaten') {
              detailUrl = "{{ route('public.profile') }}";
            }

            const phoneLink = u.contact_phone ? `https://wa.me/${u.contact_phone.replace(/[^0-9]/g, '')}` : '#';

            marker.bindPopup(`
              <div style="font-family: sans-serif; padding: 6px; min-width: 220px;">
                <div style="font-size: 11px; font-weight: 700; color: ${badgeColor}; background: ${badgeBg}; display: inline-block; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; margin-bottom: 4px;">
                  KT ${level.toUpperCase()}
                </div>
                <strong style="font-size: 14px; color: #1e293b; display: block; margin-bottom: 6px;">${u.unit_name}</strong>
                <div style="font-size: 12px; color: #475569; margin-bottom: 4px;">
                  <strong>Ketua:</strong> ${u.chairman_name || '-'}
                </div>
                <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">
                  <strong>Alamat:</strong> ${u.office_address || 'Kabupaten Bandung'}
                </div>
                <div style="font-size: 12px; color: #10b981; margin-bottom: 8px;">
                  <strong>Status:</strong> <span style="background: #ecfdf5; padding: 2px 6px; border-radius: 4px; font-weight: 600;">${u.status_aktif || 'Aktif'}</span>
                </div>
                <div style="display: flex; gap: 6px; margin-top: 6px; border-top: 1px solid #e2e8f0; padding-top: 6px; align-items: center;">
                  <a href="${detailUrl}" style="font-size: 11px; color: #047857; font-weight: 700; text-decoration: none;">Lihat Unit Sub-Site →</a>
                  ${u.contact_phone ? `<a href="${phoneLink}" target="_blank" style="font-size: 11px; color: #16a34a; font-weight: 600; margin-left: auto; text-decoration: none;"><i class="ti ti-brand-whatsapp"></i> Hubungi</a>` : ''}
                </div>
              </div>
            `);
            markersLayer.addLayer(marker);
          }
        });
      } else {
        L.marker([-7.0252, 107.5198], { icon: getMarkerIcon('kabupaten') }).addTo(markersLayer)
          .bindPopup('<b>Sekretariat Karang Taruna Kab. Bandung</b><br>Soreang, Kab. Bandung')
          .openPopup();
      }
    }

    // Initial render
    renderMarkers(unitsData);

    // Filter Handlers
    const filterDistrict = document.getElementById('filterDistrict');
    const filterLevel = document.getElementById('filterLevel');
    const resetBtn = document.getElementById('resetGisFilter');

    function applyFilter() {
      const selectedDistrict = filterDistrict.value;
      const selectedLevel = filterLevel.value;

      const filtered = unitsData.filter(function(u) {
        const matchDistrict = !selectedDistrict || (u.district_id && u.district_id.toString() === selectedDistrict);
        const matchLevel = !selectedLevel || (u.unit_level && u.unit_level.toLowerCase() === selectedLevel.toLowerCase());
        return matchDistrict && matchLevel;
      });

      renderMarkers(filtered);
    }

    filterDistrict.addEventListener('change', applyFilter);
    filterLevel.addEventListener('change', applyFilter);
    resetBtn.addEventListener('click', function() {
      filterDistrict.value = '';
      filterLevel.value = '';
      renderMarkers(unitsData);
    });
  });
</script>
@endpush
