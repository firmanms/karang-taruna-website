@extends('layouts.public')

@section('title', 'Program Kerja - Karang Taruna Kabupaten Bandung')
@section('meta_description', 'Matriks Program Kerja dan Inisiatif Karang Taruna Kabupaten Bandung Masa Bakti 2024–2029.')

@section('content')
  <!-- Page Banner -->
  <section class="page-banner">
    <div class="container">
      <div class="breadcrumb">
        <a href="{{ route('public.home') }}"><i class="ti ti-home"></i> Beranda</a>
        <span>/</span>
        <span>Aktivitas</span>
        <span>/</span>
        <span class="active">Program Kerja</span>
      </div>
      <h1>Matriks Program Kerja</h1>
      <p>Rencana strategis, sasaran capaian, dan estimasi pembiayaan program kerja kepengurusan periode 2024–2029.</p>
    </div>
  </section>

  <!-- Program Matrix Content -->
  <section class="section">
    <div class="container">
      <div class="matrix-header-block">
        <span class="matrix-pill">KARANG TARUNA KABUPATEN BANDUNG</span>
        <h2>Matriks Program Kerja</h2>
        <p>Masa Bakti Tahun 2024–2029</p>
      </div>

      <!-- Matrix Table Container -->
      <div class="matrix-table-card" style="margin-top: 2rem;">
        <div class="matrix-table-wrap">
          <table class="matrix-table">
            <thead>
              <tr>
                <th style="width: 50px;">NO</th>
                <th style="min-width: 200px;">PROGRAM & SASARAN</th>
                <th style="min-width: 220px;">TUJUAN / OUTPUT</th>
                <th style="min-width: 170px;">PELAKSANAAN</th>
                <th style="min-width: 150px;">PEMBIAYAAN</th>
                <th style="min-width: 160px;">PELAKSANA</th>
                <th style="min-width: 180px;">STATUS</th>
              </tr>
            </thead>
            <tbody id="matrixTableBody">
              @forelse($programs as $index => $prog)
                <tr>
                  <td class="text-center font-bold">{{ $programs->firstItem() + $index }}</td>
                  <td>
                    <strong>{{ $prog->program_name }}</strong>
                    <small>Sasaran: {{ $prog->target_audience ?? 'Generasi Muda & Warga' }}</small>
                  </td>
                  <td>{{ Str::limit($prog->short_description ?? strip_tags($prog->detailed_description), 120) }}</td>
                  <td>
                    <span>{{ $prog->start_date ? \Carbon\Carbon::parse($prog->start_date)->format('d M Y') : '2024–2029' }}</span>
                    <small>{{ $prog->location ?? 'Kabupaten Bandung' }}</small>
                  </td>
                  <td>
                    @if($prog->budget_estimate)
                      <span class="cost-badge">Rp {{ number_format($prog->budget_estimate, 0, ',', '.') }}</span>
                    @else
                      <span class="cost-badge">Swadaya</span>
                    @endif
                    <small>{{ $prog->budget_source ?? 'Kas/Mitra' }}</small>
                  </td>
                  <td>{{ $prog->division->division_name ?? ($prog->unit->unit_name ?? 'Pengurus Harian') }}</td>
                  <td>
                    <span class="tag-badge" style="background: #e0f2fe; color: #0369a1; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">
                      {{ ucfirst($prog->execution_status ?? 'Rencana') }}
                    </span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-8 text-gray-500">Belum ada matriks program kerja yang diterbitkan saat ini.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div class="pagination-wrapper" style="margin-top: 2.5rem;">
        {{ $programs->links() }}
      </div>
    </div>
  </section>
@endsection
