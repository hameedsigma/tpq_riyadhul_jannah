{{-- ============================================================
    program.blade.php - Halaman Program Pembelajaran
============================================================ --}}
@extends('layouts.app')

@section('title', 'Program Pembelajaran')

@section('content')

{{-- Page Header --}}
<div class="py-5 text-white text-center" style="background:linear-gradient(135deg,var(--emerald-dark),#065f46)">
    <div class="container">
        <h1 class="fw-700 mb-2"><i class="bi bi-journal-bookmark me-2"></i>Program Pembelajaran</h1>
        <p class="mb-0 opacity-75">Program unggulan untuk mencetak generasi Qurani</p>
    </div>
</div>

<section class="py-5" style="background:var(--gray-soft)">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Program <span>Unggulan Kami</span></h2>
            <div class="section-divider"></div>
            <p class="text-muted">Dirancang khusus untuk memaksimalkan potensi setiap santri</p>
        </div>

        <div class="row g-4">
            @forelse($programs as $prog)
            <div class="col-sm-6 col-lg-4">
                {{-- Card program dengan nomor urut --}}
                <div class="card-program p-4 h-100 bg-white">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="icon-wrap flex-shrink-0">
                            <i class="bi {{ $prog->ikon }}"></i>
                        </div>
                        <span class="badge rounded-pill ms-auto mt-1"
                              style="background:var(--emerald-light);color:var(--emerald);font-size:.7rem;">
                            Program #{{ $prog->urutan }}
                        </span>
                    </div>
                    <h5 class="fw-700">{{ $prog->nama }}</h5>
                    <p class="text-muted small mb-0">{{ $prog->deskripsi }}</p>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-journal-x" style="font-size:4rem;color:var(--emerald);opacity:.4"></i>
                <p class="text-muted mt-3">Program belum tersedia saat ini.</p>
            </div>
            @endforelse
        </div>

        {{-- CTA --}}
        <div class="text-center mt-5">
            <div class="p-4 rounded-4" style="background:var(--emerald-light)">
                <h4 class="fw-700 mb-2" style="color:var(--emerald)">Daftarkan Putra/Putri Anda Sekarang</h4>
                <p class="text-muted mb-3">Masa depan anak Anda dimulai dari sini</p>
                <a href="https://wa.me/6281234567890?text=Assalamu'alaikum,%20saya%20ingin%20informasi%20pendaftaran%20TPQ"
                   target="_blank" class="btn btn-wa me-2">
                    <i class="bi bi-whatsapp me-2"></i>Daftar via WhatsApp
                </a>
                <a href="{{ route('kontak') }}" class="btn btn-outline-success">
                    <i class="bi bi-envelope me-2"></i>Kirim Pesan
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
