@extends('layouts.public')

@section('title', 'Pengecekan Data PPKS - Karang Taruna Kabupaten Bandung')
@section('meta_description', 'Layanan integrasi penelusuran status Pemerlu Pelayanan Kesejahteraan Sosial (PPKS) terpadu di wilayah Kabupaten Bandung.')

@section('content')
  <!-- Page Banner -->
  <section class="page-banner">
    <div class="container">
      <div class="breadcrumb">
        <a href="{{ route('public.home') }}"><i class="ti ti-home"></i> Beranda</a>
        <span>/</span>
        <span>Layanan Sosial</span>
        <span>/</span>
        <span class="active">Cek Data PPKS</span>
      </div>
      <h1>Pengecekan Data PPKS</h1>
      <p>Layanan integrasi penelusuran status Pemerlu Pelayanan Kesejahteraan Sosial (PPKS) terpadu di wilayah Kabupaten Bandung.</p>
    </div>
  </section>

  <!-- Form Section -->
  <section class="section">
    <div class="container">
      <div class="ppks-wrapper">
        <!-- Form Card -->
        <div class="ppks-card">
          <div class="ppks-card-header">
            <div class="ppks-icon-badge">
              <i class="ti ti-heart-handshake"></i>
            </div>
            <div>
              <h2>Pencarian Status Bantuan & Terdaftar PPKS</h2>
              <p>Masukkan 16 digit Nomor Induk Kependudukan (NIK) Anda di bawah ini.</p>
            </div>
          </div>

          <form action="{{ route('public.ppks') }}" method="POST" class="ppks-form">
            @csrf
            <!-- NIK Input -->
            <div class="form-group">
              <label for="nikInput"><i class="ti ti-id"></i> Nomor Induk Kependudukan (NIK)</label>
              <div class="input-with-icon">
                <i class="ti ti-credit-card"></i>
                <input type="text" id="nikInput" name="nik" placeholder="Contoh: 3204xxxxxxxxxxxx" maxlength="16" required autocomplete="off">
              </div>
              @error('nik')
                <small class="text-red-500 font-semibold mt-1 block">{{ $message }}</small>
              @else
                <small class="form-hint">Pastikan NIK sesuai dengan KTP-el atau Kartu Keluarga resmi.</small>
              @enderror
            </div>

            <!-- Keamanan Captcha -->
            <div class="form-group">
              <label for="captchaInput"><i class="ti ti-shield-check"></i> Verifikasi Keamanan: Hitung <strong>{{ $captchaQuestion }}</strong></label>
              <div class="input-with-icon">
                <i class="ti ti-calculator"></i>
                <input type="number" id="captchaInput" name="captcha" placeholder="Tulis hasil hitungan di sini" required autocomplete="off">
              </div>
              @error('captcha')
                <small class="text-red-500 font-semibold mt-1 block">{{ $message }}</small>
              @enderror
            </div>

            <!-- Submit Button -->
            <div class="form-actions">
              <button type="submit" class="btn-ppks-submit">
                <i class="ti ti-search"></i> Cari Data PPKS
              </button>
              <a href="{{ route('public.ppks') }}" class="btn-ppks-reset">
                <i class="ti ti-rotate-clockwise"></i> Reset
              </a>
            </div>
          </form>

          @if($searched)
            @if($result)
              <!-- Result Card: Terdaftar -->
              <div class="ppks-result-card success" style="margin-top: 2rem;">
                <div class="result-head">
                  <span class="status-pill verified"><i class="ti ti-circle-check"></i> DATA DITEMUKAN & TERDAFTAR</span>
                  <span class="reg-date">Update: {{ \Carbon\Carbon::parse($result->updated_at)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="result-grid">
                  <div class="result-item">
                    <span>Nomor Induk Kependudukan (NIK)</span>
                    <strong>{{ substr($result->nik, 0, 6) . '******' . substr($result->nik, -4) }}</strong>
                  </div>
                  <div class="result-item">
                    <span>Nama Lengkap (Sesuai KTP)</span>
                    <strong>{{ substr($result->full_name, 0, 3) . str_repeat('*', max(3, strlen($result->full_name) - 3)) }}</strong>
                  </div>
                  <div class="result-item">
                    <span>Kategori PPKS</span>
                    <strong>{{ $result->category->category_name ?? 'Pemerlu Pelayanan Kesejahteraan Sosial' }}</strong>
                  </div>
                  <div class="result-item">
                    <span>Wilayah Domisili</span>
                    <strong>Kec. {{ $result->district->name ?? '-' }}, Desa {{ $result->village->name ?? '-' }}</strong>
                  </div>
                  <div class="result-item">
                    <span>Status Program Bantuan</span>
                    <strong class="text-green-700">{{ $result->social_assistance_status ?? 'Terverifikasi Penerima Manfaat' }}</strong>
                  </div>
                  <div class="result-item">
                    <span>Unit Pendamping</span>
                    <strong>{{ $result->unit->unit_name ?? 'Karang Taruna Setempat' }}</strong>
                  </div>
                </div>
                <div class="result-footer">
                  <p><i class="ti ti-info-circle"></i> Jika terdapat ketidaksesuaian data atau kendala penyaluran, silakan hubungi Seksi Kesejahteraan Sosial Karang Taruna di kantor desa setempat.</p>
                </div>
              </div>
            @else
              <!-- Result Card: Tidak Ditemukan -->
              <div class="ppks-result-card not-found" style="margin-top: 2rem;">
                <div class="result-head">
                  <span class="status-pill warning"><i class="ti ti-info-circle"></i> DATA TIDAK DITEMUKAN</span>
                </div>
                <div class="not-found-body">
                  <h3>NIK belum terdaftar dalam Basis Data PPKS</h3>
                  <p>Data NIK yang Anda masukkan belum tercatat dalam sistem Pemerlu Pelayanan Kesejahteraan Sosial (PPKS) Kabupaten Bandung periode berjalan.</p>
                  <div class="not-found-tips">
                    <strong>Langkah yang dapat dilakukan:</strong>
                    <ul>
                      <li>Pastikan Anda telah memasukkan 16 digit NIK dengan benar.</li>
                      <li>Ajukan permohonan pendataan mandiri melalui Musyawarah Desa (Musdes) atau Seksi Sosial Karang Taruna tingkat RW/Desa.</li>
                    </ul>
                  </div>
                </div>
              </div>
            @endif
          @endif
        </div>

        <!-- Info & Guidance Side Box -->
        <div class="ppks-side-guide">
          <div class="guide-card">
            <h3><i class="ti ti-help-circle"></i> Apa itu PPKS?</h3>
            <p>Pemerlu Pelayanan Kesejahteraan Sosial (PPKS) adalah perseorangan, keluarga, kelompok, dan/atau masyarakat yang karena suatu hambatan, kesulitan, atau gangguan, tidak dapat melaksanakan fungsi sosialnya sehingga memerlukan pelayanan sosial.</p>
          </div>

          <div class="guide-card">
            <h3><i class="ti ti-category"></i> Kategori PPKS</h3>
            <ul class="guide-list">
              <li><i class="ti ti-check"></i> Anak Balita & Lansia Terlantar</li>
              <li><i class="ti ti-check"></i> Penyandang Disabilitas</li>
              <li><i class="ti ti-check"></i> Korban Bencana Alam & Sosial</li>
              <li><i class="ti ti-check"></i> Keluarga Prasejahtera & Rentan</li>
              <li><i class="ti ti-check"></i> Pemuda Putus Sekolah Membutuhkan Vokasi</li>
            </ul>
          </div>

          <div class="guide-card help-contact">
            <h3><i class="ti ti-headset"></i> Butuh Bantuan Pendataan?</h3>
            <p>Hubungi Posko Pengaduan Sosial Karang Taruna Kabupaten Bandung:</p>
            <a href="https://wa.me/6281234567890" target="_blank" class="btn-wa-help">
              <i class="ti ti-brand-whatsapp"></i> Layanan Konsultasi WA
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
