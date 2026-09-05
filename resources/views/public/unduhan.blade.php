@extends('layouts.public')

@section('title', 'Pusat Unduhan & Dokumen Resmi - Karang Taruna Kabupaten Bandung')
@section('meta_description', 'Akses berkas regulasi, AD/ART, modul panduan kegiatan, formulir pendaftaran, dan surat edaran resmi.')

@section('content')
  <!-- Page Banner -->
  <section class="page-banner">
    <div class="container">
      <div class="breadcrumb">
        <a href="{{ route('public.home') }}"><i class="ti ti-home"></i> Beranda</a>
        <span>/</span>
        <span>Publikasi</span>
        <span>/</span>
        <span class="active">Pusat Unduhan</span>
      </div>
      <h1>Pusat Unduhan & Dokumen Resmi</h1>
      <p>Akses berkas regulasi, AD/ART, modul panduan kegiatan, formulir pendaftaran, dan surat edaran resmi.</p>
    </div>
  </section>

  <!-- Downloads Table & Filter -->
  <section class="section">
    <div class="container">
      <div class="download-container">
        <!-- Document List Table -->
        <div class="download-table-wrap">
          <table class="download-table">
            <thead>
              <tr>
                <th>Nama Dokumen</th>
                <th>Kategori</th>
                <th>Format / Ukuran</th>
                <th>Tanggal</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="downloadTableBody">
              @forelse($downloads as $doc)
                <tr>
                  <td>
                    <div class="doc-title-cell">
                      <i class="ti ti-file-type-{{ strtolower($doc->file_format ?? 'pdf') }} text-red-500"></i>
                      <div>
                        <strong>{{ $doc->title }}</strong>
                        <small>{{ $doc->description ?? '-' }}</small>
                      </div>
                    </div>
                  </td>
                  <td><span class="tag-badge">{{ $doc->category->name ?? 'Dokumen' }}</span></td>
                  <td>{{ strtoupper($doc->file_format ?? 'PDF') }} / {{ $doc->file_size_human ?? '1 MB' }}</td>
                  <td>{{ $doc->release_date ? \Carbon\Carbon::parse($doc->release_date)->translatedFormat('d M Y') : $doc->created_at->translatedFormat('d M Y') }}</td>
                  <td>
                    <a href="{{ route('public.downloads.file', $doc->id) }}" class="btn-download" title="Unduh Berkas"><i class="ti ti-download"></i> Unduh ({{ $doc->download_count }})</a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-8 text-gray-500">Belum ada dokumen publik yang tersedia untuk diunduh.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper" style="margin-top: 2.5rem;">
          {{ $downloads->links() }}
        </div>
      </div>
    </div>
  </section>
@endsection
