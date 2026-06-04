{{-- ============================================================
    layouts/app.blade.php - Master Layout Laravel
    TPQ Riyadhul Jannah

    Semua halaman publik mewarisi layout ini.
    Cara pakai di view lain:
      @extends('layouts.app')
      @section('title', 'Judul Halaman')
      @section('content') ... @endsection
============================================================ --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TPQ Riyadhul Jannah - Mencetak Generasi Qurani yang Berakhlak Mulia">

    {{-- Judul halaman dinamis. @yield('title') diisi oleh masing-masing view --}}
    <title>@yield('title', 'Beranda') | TPQ Riyadhul Jannah</title>

    {{-- Favicon - mengambil dari folder assets PHP Native yang sudah ada --}}
    <link rel="icon" href="{{ asset('../assets/images/logo.png') }}" type="image/png">

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Google Fonts: Poppins (teks) + Amiri (Arab) --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* ── CSS Variables (Palet Warna Utama) ── */
        :root {
            --emerald:       #059669;
            --emerald-dark:  #047857;
            --emerald-light: #d1fae5;
            --gold:          #d97706;
            --gold-light:    #fef3c7;
            --white:         #ffffff;
            --gray-soft:     #f8fafc;
            --text-dark:     #1e293b;
            --text-muted:    #64748b;
        }

        * { scroll-behavior: smooth; }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            background: var(--white);
        }

        /* ── Navbar ── */
        .navbar {
            background: rgba(255,255,255,.97) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,.08);
        }
        .navbar-brand .brand-text { font-weight: 700; color: var(--emerald); font-size: 1.1rem; }
        .navbar-brand .brand-sub  { font-size: .7rem; color: var(--gold); letter-spacing: .05em; }
        .nav-link { font-weight: 500; color: var(--text-dark) !important; transition: color .2s; }
        .nav-link:hover,
        .nav-link.active { color: var(--emerald) !important; }
        .navbar-toggler { border-color: var(--emerald); }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23059669' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* ── Section Titles ── */
        .section-title { font-weight: 700; color: var(--text-dark); }
        .section-title span { color: var(--emerald); }
        .section-divider {
            width: 60px; height: 4px;
            background: linear-gradient(90deg, var(--emerald), var(--gold));
            border-radius: 2px; margin: .75rem auto 1.5rem;
        }

        /* ── Cards Program ── */
        .card-program {
            border: none; border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,.07);
            transition: transform .3s, box-shadow .3s;
        }
        .card-program:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 35px rgba(5,150,105,.15);
        }
        .card-program .icon-wrap {
            width: 64px; height: 64px; border-radius: 16px;
            background: var(--emerald-light);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; color: var(--emerald); margin-bottom: 1rem;
        }

        /* ── Galeri ── */
        .galeri-item { position: relative; overflow: hidden; border-radius: 12px; cursor: pointer; }
        .galeri-item img { width: 100%; height: 220px; object-fit: cover; transition: transform .4s; }
        .galeri-item:hover img { transform: scale(1.08); }
        .galeri-overlay {
            position: absolute; inset: 0;
            background: rgba(5,150,105,.75);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            opacity: 0; transition: opacity .3s;
            color: #fff; text-align: center; padding: 1rem;
        }
        .galeri-item:hover .galeri-overlay { opacity: 1; }

        /* ── Kontak Section ── */
        .kontak-section { background: linear-gradient(135deg, var(--emerald-dark) 0%, #065f46 100%); }
        .kontak-card {
            background: rgba(255,255,255,.1);
            border-radius: 16px; padding: 1.5rem;
            color: #fff; border: 1px solid rgba(255,255,255,.15);
        }
        .kontak-icon {
            width: 48px; height: 48px; background: var(--gold);
            border-radius: 12px; display: flex; align-items: center;
            justify-content: center; font-size: 1.3rem; color: #fff; flex-shrink: 0;
        }

        /* ── WhatsApp Button ── */
        .btn-wa {
            background: #25d366; color: #fff; border: none;
            border-radius: 50px; padding: .75rem 2rem;
            font-weight: 600; transition: all .3s;
        }
        .btn-wa:hover { background: #128c7e; color: #fff; transform: translateY(-2px); }

        /* ── Hero ── */
        .hero-section {
            min-height: 85vh;
            background: linear-gradient(135deg,
                rgba(5,150,105,.92) 0%,
                rgba(4,120,87,.85) 60%,
                rgba(217,119,6,.7) 100%);
            display: flex; align-items: center;
        }
        .hero-section .arabic-text {
            font-family: 'Amiri', serif;
            font-size: 2rem; color: var(--gold-light);
        }
        .hero-section h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 700; color: #fff;
        }
        .btn-hero-primary {
            background: var(--gold); border: none; color: #fff;
            font-weight: 600; padding: .75rem 2rem; border-radius: 50px; transition: all .3s;
        }
        .btn-hero-primary:hover { background: #b45309; transform: translateY(-2px); color: #fff; }
        .btn-hero-outline {
            border: 2px solid #fff; color: #fff;
            font-weight: 600; padding: .75rem 2rem; border-radius: 50px; transition: all .3s;
        }
        .btn-hero-outline:hover { background: #fff; color: var(--emerald); }

        /* ── Visi Misi ── */
        .visi-misi-card {
            border-left: 4px solid var(--emerald);
            border-radius: 0 12px 12px 0;
            background: #fff; padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
        }
        .visi-misi-card.gold { border-left-color: var(--gold); }

        /* ── Footer ── */
        footer { background: #0f172a; color: rgba(255,255,255,.7); }
        footer a { color: rgba(255,255,255,.6); text-decoration: none; transition: color .2s; }
        footer a:hover { color: var(--gold); }

        /* ── Scroll to top ── */
        #scrollTop {
            position: fixed; bottom: 2rem; right: 2rem; z-index: 999;
            width: 44px; height: 44px; border-radius: 50%;
            background: var(--emerald); color: #fff; border: none;
            display: none; align-items: center; justify-content: center;
            box-shadow: 0 4px 15px rgba(5,150,105,.4); transition: all .3s;
            cursor: pointer;
        }
        #scrollTop.show { display: flex; }
        #scrollTop:hover { background: var(--emerald-dark); transform: translateY(-3px); }

        @media (max-width: 768px) {
            .hero-section { min-height: 90vh; text-align: center; }
            .hero-section .arabic-text { font-size: 1.4rem; }
        }
    </style>

    {{-- Slot untuk CSS tambahan per halaman --}}
    @stack('styles')
</head>
<body>

{{-- ── Navbar ── --}}
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('beranda') }}">
            {{-- Logo dari folder assets PHP Native --}}
            <img src="{{ asset('../assets/images/logo.png') }}"
                 alt="Logo TPQ Riyadhul Jannah"
                 width="44" height="44"
                 style="border-radius:8px; object-fit:cover;"
                 onerror="this.style.display='none'">
            <div>
                <div class="brand-text">TPQ Riyadhul Jannah</div>
                <div class="brand-sub">Lembaga Pendidikan Al-Quran</div>
            </div>
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMenu"
                aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto gap-1">
                {{-- 
                    request()->routeIs('beranda') mengecek apakah halaman saat ini
                    adalah route bernama 'beranda', lalu tambahkan class 'active'
                --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}"
                       href="{{ route('beranda') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profil') ? 'active' : '' }}"
                       href="{{ route('profil') }}">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('program') ? 'active' : '' }}"
                       href="{{ route('program') }}">Program</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('galeri') ? 'active' : '' }}"
                       href="{{ route('galeri') }}">Galeri</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kontak') ? 'active' : '' }}"
                       href="{{ route('kontak') }}">Kontak</a>
                </li>
                <li class="nav-item ms-2">
                    <a class="btn btn-sm px-3 py-2"
                       style="background:var(--emerald);color:#fff;border-radius:50px;font-weight:600;"
                       href="/tpq-riyadhul-jannah/admin/login.php">
                        <i class="bi bi-shield-lock me-1"></i>Admin
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- ── Konten Halaman (diisi oleh masing-masing view) ── --}}
<main>
    @yield('content')
</main>

{{-- ── Footer ── --}}
<footer class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="footer-brand mb-2">
                    <i class="bi bi-book-half me-2" style="color:var(--gold)"></i>
                    TPQ Riyadhul Jannah
                </div>
                <p class="small mb-3">Mencetak Generasi Qurani yang Berakhlak Mulia sejak 2005.</p>
                <div class="d-flex gap-3">
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" aria-label="YouTube"><i class="bi bi-youtube fs-5"></i></a>
                </div>
            </div>
            <div class="col-md-4">
                <h6 class="text-white fw-600 mb-3">Navigasi Cepat</h6>
                <ul class="list-unstyled small">
                    <li class="mb-1"><a href="{{ route('beranda') }}">Beranda</a></li>
                    <li class="mb-1"><a href="{{ route('profil') }}">Profil & Sejarah</a></li>
                    <li class="mb-1"><a href="{{ route('program') }}">Program Pembelajaran</a></li>
                    <li class="mb-1"><a href="{{ route('galeri') }}">Galeri Kegiatan</a></li>
                    <li class="mb-1"><a href="{{ route('kontak') }}">Kontak Kami</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="text-white fw-600 mb-3">Kontak</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="bi bi-geo-alt me-2" style="color:var(--gold)"></i>Bandung, Jawa Barat</li>
                    <li class="mb-2"><i class="bi bi-whatsapp me-2 text-success"></i>0812-3456-7890</li>
                    <li class="mb-2"><i class="bi bi-envelope me-2" style="color:var(--gold)"></i>info@tpqriyadhuljannah.id</li>
                </ul>
            </div>
        </div>
        <hr style="border-color:rgba(255,255,255,.1)" class="mt-4">
        <div class="text-center small">
            <p class="mb-0">© {{ date('Y') }} TPQ Riyadhul Jannah. Semua hak dilindungi.</p>
        </div>
    </div>
</footer>

{{-- Tombol Scroll ke Atas --}}
<button id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Scroll ke atas">
    <i class="bi bi-arrow-up"></i>
</button>

{{-- Bootstrap 5 JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Tampilkan/sembunyikan tombol scroll ke atas
    window.addEventListener('scroll', () => {
        document.getElementById('scrollTop').classList.toggle('show', window.scrollY > 400);
    });
</script>

{{-- Slot untuk JS tambahan per halaman --}}
@stack('scripts')

</body>
</html>
