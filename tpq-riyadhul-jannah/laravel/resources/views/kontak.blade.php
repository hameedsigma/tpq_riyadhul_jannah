{{-- ============================================================
    kontak.blade.php - Halaman Kontak & Form Pesan
============================================================ --}}
@extends('layouts.app')

@section('title', 'Kontak Kami')

@section('content')

{{-- Page Header --}}
<div class="py-5 text-white text-center" style="background:linear-gradient(135deg,var(--emerald-dark),#065f46)">
    <div class="container">
        <h1 class="fw-700 mb-2"><i class="bi bi-telephone me-2"></i>Hubungi Kami</h1>
        <p class="mb-0 opacity-75">Kami siap membantu Anda. Kirimkan pesan atau kunjungi kami langsung.</p>
    </div>
</div>

<section class="py-5" style="background:var(--gray-soft)">
    <div class="container">

        {{-- ── Flash Message Sukses (setelah form terkirim) ── --}}
        @if(session('sukses'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4 rounded-3" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('sukses') }}</div>
        </div>
        @endif

        <div class="row g-5">

            {{-- ── Kolom Kiri: Info Kontak + Maps ── --}}
            <div class="col-lg-5">
                <h4 class="fw-700 mb-4" style="color:var(--emerald)">Informasi Kontak</h4>

                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="kontak-card d-flex gap-3 align-items-start">
                        <div class="kontak-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <h6 class="fw-600 mb-1 text-white">Alamat</h6>
                            <p class="small mb-0" style="opacity:.8">{!! nl2br(e($kontak['alamat'] ?? '-')) !!}</p>
                        </div>
                    </div>
                    <div class="kontak-card d-flex gap-3 align-items-start">
                        <div class="kontak-icon" style="background:#25d366"><i class="bi bi-whatsapp"></i></div>
                        <div>
                            <h6 class="fw-600 mb-1 text-white">WhatsApp</h6>
                            <a href="https://wa.me/{{ $kontak['whatsapp'] ?? '' }}"
                               target="_blank" class="text-white small">
                                {{ $kontak['whatsapp_text'] ?? '-' }}
                            </a>
                        </div>
                    </div>
                    <div class="kontak-card d-flex gap-3 align-items-start">
                        <div class="kontak-icon" style="background:var(--gold)"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <h6 class="fw-600 mb-1 text-white">Email</h6>
                            <a href="mailto:{{ $kontak['email'] ?? '' }}"
                               class="text-white small">
                                {{ $kontak['email'] ?? '-' }}
                            </a>
                        </div>
                    </div>
                    <div class="kontak-card d-flex gap-3 align-items-start">
                        <div class="kontak-icon" style="background:#6366f1"><i class="bi bi-clock-fill"></i></div>
                        <div>
                            <h6 class="fw-600 mb-1 text-white">Jam Belajar</h6>
                            <p class="small mb-0" style="white-space:pre-line;opacity:.8">{{ $kontak['jam_belajar'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Google Maps --}}
                @if(!empty($kontak['maps_embed']))
                <div class="rounded-4 overflow-hidden shadow" style="height:250px">
                    <iframe src="{{ $kontak['maps_embed'] }}"
                            width="100%" height="100%" style="border:0"
                            allowfullscreen loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi TPQ Riyadhul Jannah">
                    </iframe>
                </div>
                @endif
            </div>

            {{-- ── Kolom Kanan: Form Kirim Pesan ── --}}
            <div class="col-lg-7">
                <div class="bg-white rounded-4 shadow-sm p-4">
                    <h4 class="fw-700 mb-1" style="color:var(--emerald)">Kirim Pesan</h4>
                    <p class="text-muted small mb-4">Isi formulir di bawah ini dan kami akan merespons secepatnya.</p>

                    {{--
                        @csrf → Wajib ada di setiap form POST di Laravel.
                        Laravel secara otomatis memeriksa token ini untuk mencegah
                        serangan CSRF (Cross-Site Request Forgery).
                    --}}
                    <form action="{{ route('kontak.kirim') }}" method="POST" novalidate>
                        @csrf

                        <div class="row g-3">
                            {{-- Nama --}}
                            <div class="col-md-6">
                                <label for="nama" class="form-label fw-500 small">
                                    Nama Lengkap <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person text-muted"></i></span>
                                    {{--
                                        old('nama') → menampilkan kembali nilai yang diketik user
                                        jika form gagal divalidasi, agar tidak perlu mengisi ulang.
                                        @error('nama') → menampilkan pesan error validasi untuk field 'nama'
                                    --}}
                                    <input type="text" name="nama" id="nama"
                                           class="form-control @error('nama') is-invalid @enderror"
                                           placeholder="Nama Anda"
                                           value="{{ old('nama') }}" required>
                                    @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-500 small">
                                    Alamat Email <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-envelope text-muted"></i></span>
                                    <input type="email" name="email" id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="email@anda.com"
                                           value="{{ old('email') }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Telepon (Opsional) --}}
                            <div class="col-12">
                                <label for="telepon" class="form-label fw-500 small">
                                    Nomor WhatsApp <span class="text-muted">(opsional)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-telephone text-muted"></i></span>
                                    <input type="text" name="telepon" id="telepon"
                                           class="form-control @error('telepon') is-invalid @enderror"
                                           placeholder="08xx-xxxx-xxxx"
                                           value="{{ old('telepon') }}">
                                    @error('telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Pesan --}}
                            <div class="col-12">
                                <label for="pesan" class="form-label fw-500 small">
                                    Isi Pesan <span class="text-danger">*</span>
                                </label>
                                <textarea name="pesan" id="pesan" rows="5"
                                          class="form-control @error('pesan') is-invalid @enderror"
                                          placeholder="Tuliskan pesan, pertanyaan, atau saran Anda di sini..."
                                          required>{{ old('pesan') }}</textarea>
                                @error('pesan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn w-100 py-3 fw-600 text-white"
                                        style="background:var(--emerald);border-radius:12px;">
                                    <i class="bi bi-send-fill me-2"></i>Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    /* Override warna input-group border saat fokus */
    .form-control:focus { border-color: var(--emerald); box-shadow: 0 0 0 .2rem rgba(5,150,105,.15); }
    .input-group-text { border-right: none; }
    .form-control { border-left: none; }
    /* Fix border saat is-invalid */
    .is-invalid { border-left: 1px solid #dc3545 !important; }
</style>
@endpush
