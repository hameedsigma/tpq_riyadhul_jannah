{{-- ============================================================
    profil.blade.php - Halaman Profil & Sejarah TPQ
============================================================ --}}
@extends('layouts.app')

@section('title', 'Profil & Sejarah')

@section('content')

{{-- Page Header --}}
<div class="py-5 text-white text-center" style="background:linear-gradient(135deg,var(--emerald-dark),#065f46)">
    <div class="container">
        <h1 class="fw-700 mb-2"><i class="bi bi-building me-2"></i>Profil TPQ Riyadhul Jannah</h1>
        <p class="mb-0 opacity-75">Mengenal lebih dalam lembaga pendidikan Al-Quran kami</p>
    </div>
</div>

<section class="py-5" style="background:var(--gray-soft)">
    <div class="container">

        {{-- ── Sejarah ── --}}
        <div class="row align-items-center g-4 mb-5">
            <div class="col-lg-6">
                <img src="{{ asset('../assets/images/sejarah.jpg') }}"
                     alt="Sejarah TPQ Riyadhul Jannah"
                     class="img-fluid rounded-4 shadow"
                     style="max-height:420px;width:100%;object-fit:cover;"
                     onerror="this.src='https://placehold.co/600x420/059669/white?text=Sejarah+TPQ'">
            </div>
            <div class="col-lg-6">
                <h3 class="fw-700 mb-3" style="color:var(--emerald)">
                    <i class="bi bi-clock-history me-2"></i>Sejarah Kami
                </h3>
                {{-- nl2br → konversi newline \n menjadi tag <br> HTML --}}
                <p class="text-muted lh-lg">{!! nl2br(e($profil['sejarah'] ?? 'Sejarah belum tersedia.')) !!}</p>
            </div>
        </div>

        {{-- ── Visi & Misi ── --}}
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="visi-misi-card h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div style="width:40px;height:40px;background:var(--emerald);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-eye-fill text-white"></i>
                        </div>
                        <h4 class="mb-0 fw-700">Visi</h4>
                    </div>
                    <p class="text-muted mb-0 lh-lg">{{ $profil['visi'] ?? 'Visi belum tersedia.' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="visi-misi-card gold h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div style="width:40px;height:40px;background:var(--gold);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-bullseye text-white"></i>
                        </div>
                        <h4 class="mb-0 fw-700">Misi</h4>
                    </div>
                    <div class="text-muted lh-lg" style="white-space:pre-line">{{ $profil['misi'] ?? 'Misi belum tersedia.' }}</div>
                </div>
            </div>
        </div>

        {{-- ── Sambutan Kepala TPQ ── --}}
        @if(!empty($profil['sambutan']))
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="p-4 rounded-4 shadow-sm bg-white border" style="border-color:var(--emerald-light) !important">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ asset('../assets/images/kepala.jpg') }}"
                             alt="Kepala TPQ"
                             class="rounded-circle shadow-sm"
                             style="width:72px;height:72px;object-fit:cover;border:3px solid var(--emerald-light);"
                             onerror="this.src='https://placehold.co/72x72/059669/white?text=K'">
                        <div>
                            <h5 class="mb-0 fw-700" style="color:var(--emerald)">Sambutan Kepala TPQ</h5>
                            <small class="text-muted">Pesan untuk Orang Tua & Santri</small>
                        </div>
                    </div>
                    <div class="text-muted lh-lg fst-italic" style="white-space:pre-line">
                        {{ $profil['sambutan'] }}
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</section>

@endsection
