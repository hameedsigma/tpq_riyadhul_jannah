{{-- ============================================================
    beranda.blade.php - Halaman Beranda
    Menampilkan semua section: Hero, Profil, Program, Galeri, Kontak
============================================================ --}}
@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- ══════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════ --}}
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <p class="arabic-text mb-2">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ</p>
                <h1 class="mb-3">
                    Selamat Datang di<br>
                    <span style="color:var(--gold-light)">TPQ Riyadhul Jannah</span>
                </h1>
                <p class="mb-4" style="font-size:1.15rem;color:rgba(255,255,255,.9)">
                    Mencetak Generasi Qurani yang Berakhlak Mulia
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('program') }}" class="btn btn-hero-primary">
                        <i class="bi bi-book me-2"></i>Lihat Program
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-hero-outline">
                        <i class="bi bi-whatsapp me-2"></i>Daftar Sekarang
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-center mt-4 mt-lg-0">
                <div class="text-center text-white">
                    <div style="background:rgba(255,255,255,.15);border-radius:20px;padding:2rem;backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,.2)">
                        <i class="bi bi-book-half" style="font-size:5rem;color:var(--gold-light)"></i>
                        <h4 class="mt-3 mb-1">Pendaftaran Santri Baru</h4>
                        <p class="small mb-3">Tahun Ajaran {{ date('Y') }}/{{ date('Y')+1 }}</p>
                        <a href="https://wa.me/6281234567890?text=Assalamu'alaikum,%20saya%20ingin%20mendaftarkan%20anak%20saya"
                           target="_blank" class="btn btn-wa w-100">
                            <i class="bi bi-whatsapp me-2"></i>Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     PROFIL SINGKAT
══════════════════════════════════════════ --}}
<section class="py-5" style="background:var(--gray-soft)">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Profil <span>TPQ Kami</span></h2>
            <div class="section-divider"></div>
            <p class="text-muted">Mengenal lebih dekat TPQ Riyadhul Jannah</p>
        </div>

        <div class="row align-items-center g-4 mb-4">
            <div class="col-lg-6">
                <img src="{{ asset('../assets/images/sejarah.jpg') }}"
                     alt="Sejarah TPQ Riyadhul Jannah"
                     class="img-fluid rounded-4 shadow"
                     style="max-height:380px;width:100%;object-fit:cover;"
                     onerror="this.src='https://placehold.co/600x380/059669/white?text=TPQ+Riyadhul+Jannah'">
            </div>
            <div class="col-lg-6">
                <h3 class="fw-700 mb-3" style="color:var(--emerald)">
                    <i class="bi bi-clock-history me-2"></i>Sejarah Kami
                </h3>
                <p class="text-muted lh-lg">{{ $profil['sejarah'] ?? 'Sejarah belum tersedia.' }}</p>
                <div class="d-flex gap-3 mt-3">
                    <div class="text-center p-3 rounded-3" style="background:var(--emerald-light)">
                        <div class="fw-700 fs-4" style="color:var(--emerald)">{{ $statistik['tahun_berdiri'] }}</div>
                        <small class="text-muted">Tahun Berdiri</small>
                    </div>
                    <div class="text-center p-3 rounded-3" style="background:var(--gold-light)">
                        <div class="fw-700 fs-4" style="color:var(--gold)">{{ $statistik['tahun_berjalan'] }}+</div>
                        <small class="text-muted">Tahun Berpengalaman</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Visi & Misi --}}
        <div class="row g-4">
            <div class="col-md-6">
                <div class="visi-misi-card h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div style="width:36px;height:36px;background:var(--emerald);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-eye text-white"></i>
                        </div>
                        <h5 class="mb-0 fw-700">Visi</h5>
                    </div>
                    <p class="text-muted mb-0 lh-lg">{{ $profil['visi'] ?? 'Visi belum tersedia.' }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="visi-misi-card gold h-100">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div style="width:36px;height:36px;background:var(--gold);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-bullseye text-white"></i>
                        </div>
                        <h5 class="mb-0 fw-700">Misi</h5>
                    </div>
                    <div class="text-muted lh-lg" style="white-space:pre-line">{{ $profil['misi'] ?? 'Misi belum tersedia.' }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     PROGRAM UNGGULAN
══════════════════════════════════════════ --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Program <span>Unggulan</span></h2>
            <div class="section-divider"></div>
            <p class="text-muted">Berbagai program pembelajaran Al-Quran yang kami tawarkan</p>
        </div>
        <div class="row g-4">
            @forelse($programs as $prog)
            <div class="col-sm-6 col-lg-4">
                <div class="card-program p-4 h-100">
                    <div class="icon-wrap">
                        <i class="bi {{ $prog->ikon }}"></i>
                    </div>
                    <h5>{{ $prog->nama }}</h5>
                    <p class="text-muted small mb-0">{{ $prog->deskripsi }}</p>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">Program belum tersedia.</p>
            </div>
            @endforelse
        </div>

        {{-- CTA Daftar --}}
        <div class="text-center mt-5">
            <div class="p-4 rounded-4" style="background:var(--emerald-light)">
                <h4 class="fw-700 mb-2" style="color:var(--emerald)">Tertarik Mendaftarkan Putra/Putri Anda?</h4>
                <p class="text-muted mb-3">Hubungi kami sekarang untuk informasi pendaftaran dan jadwal belajar</p>
                <a href="https://wa.me/{{ $kontak['whatsapp'] ?? '6281234567890' }}?text=Assalamu'alaikum,%20saya%20ingin%20bertanya%20tentang%20program%20di%20TPQ%20Riyadhul%20Jannah"
                   target="_blank" class="btn btn-wa">
                    <i class="bi bi-whatsapp me-2"></i>Tanya via WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     GALERI KEGIATAN
══════════════════════════════════════════ --}}
<section class="py-5" style="background:var(--gray-soft)">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Galeri <span>Kegiatan</span></h2>
            <div class="section-divider"></div>
            <p class="text-muted">Momen berharga kegiatan santri TPQ Riyadhul Jannah</p>
        </div>

        @if($galeri->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-images" style="font-size:4rem;color:var(--emerald);opacity:.4"></i>
            <p class="text-muted mt-3">Foto kegiatan akan segera ditambahkan</p>
        </div>
        @else
        <div class="row g-3">
            @foreach($galeri->take(8) as $album)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="galeri-item">
                    <img src="{{ asset('../uploads/galeri/' . $album->cover) }}"
                         alt="{{ $album->judul }}"
                         onerror="this.src='https://placehold.co/400x300/059669/white?text=Foto+Kegiatan'">
                    <div class="galeri-overlay">
                        <i class="bi bi-collection fs-3 mb-2"></i>
                        <small class="fw-600">{{ $album->judul }}</small>
                        <div class="d-flex gap-2 mt-1 justify-content-center">
                            <span class="badge" style="background:var(--gold)">{{ $album->kategori }}</span>
                            <span class="badge bg-light text-dark">{{ $album->total }} Foto</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('galeri') }}" class="btn btn-hero-primary">
                Lihat Semua Galeri <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>

@endsection
