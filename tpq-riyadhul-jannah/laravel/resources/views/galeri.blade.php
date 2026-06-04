{{-- ============================================================
    galeri.blade.php - Halaman Galeri Kegiatan
============================================================ --}}
@extends('layouts.app')

@section('title', 'Galeri Kegiatan')

@section('content')

{{-- Page Header --}}
<div class="py-5 text-white text-center" style="background:linear-gradient(135deg,var(--emerald-dark),#065f46)">
    <div class="container">
        <h1 class="fw-700 mb-2"><i class="bi bi-images me-2"></i>Galeri Kegiatan</h1>
        <p class="mb-0 opacity-75">Momen berharga perjalanan santri TPQ Riyadhul Jannah</p>
    </div>
</div>

<section class="py-5" style="background:var(--gray-soft)">
    <div class="container">

        {{-- Filter Kategori --}}
        @if($kategori->count() > 0)
        <div class="text-center mb-4">
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <button class="btn btn-sm filter-btn active" data-kategori="semua"
                        style="border-radius:50px;background:var(--emerald);color:#fff;border:none;">
                    Semua
                </button>
                @foreach($kategori as $kat)
                <button class="btn btn-sm filter-btn" data-kategori="{{ $kat }}"
                        style="border-radius:50px;border:2px solid var(--emerald);color:var(--emerald);">
                    {{ $kat }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Grid Foto --}}
        @if($galeri->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-images" style="font-size:5rem;color:var(--emerald);opacity:.4"></i>
            <h5 class="text-muted mt-3">Foto kegiatan akan segera ditambahkan</h5>
        </div>
        @else
        <div class="row g-3" id="galeriGrid">
            @foreach($galeri as $album)
            <div class="col-6 col-md-4 col-lg-3 galeri-col" data-kategori="{{ $album->kategori }}">
                <div class="galeri-item"
                     onclick="bukaLightbox('{{ asset('../uploads/galeri/' . $album->cover) }}', '{{ addslashes($album->judul) }}')">
                    <img src="{{ asset('../uploads/galeri/' . $album->cover) }}"
                         alt="{{ $album->judul }}"
                         loading="lazy"
                         onerror="this.src='https://placehold.co/400x300/059669/white?text=Foto+Kegiatan'">
                    <div class="galeri-overlay">
                        <i class="bi bi-zoom-in fs-3 mb-2"></i>
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

        {{-- Pagination Laravel --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $galeri->links() }}
        </div>
        @endif

    </div>
</section>

{{-- ── Lightbox Modal ── --}}
<div id="lightboxOverlay" onclick="tutupLightbox()"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.9);z-index:9999;align-items:center;justify-content:center;flex-direction:column;">
    <button onclick="tutupLightbox()" style="position:absolute;top:1rem;right:1.5rem;background:none;border:none;color:#fff;font-size:2.5rem;cursor:pointer;">
        <i class="bi bi-x-circle"></i>
    </button>
    <img id="lightboxImg" src="" alt="" style="max-width:90vw;max-height:80vh;border-radius:8px;object-fit:contain;">
    <p id="lightboxCaption" class="text-white mt-3 fw-600"></p>
</div>

@endsection

@push('scripts')
<script>
    // ── Lightbox ──────────────────────────────────────────────
    function bukaLightbox(src, judul) {
        document.getElementById('lightboxImg').src = src;
        document.getElementById('lightboxCaption').textContent = judul;
        const overlay = document.getElementById('lightboxOverlay');
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Cegah scroll saat lightbox terbuka
    }

    function tutupLightbox() {
        document.getElementById('lightboxOverlay').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Tutup lightbox dengan tombol Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') tutupLightbox();
    });

    // ── Filter Kategori ───────────────────────────────────────
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Update tampilan tombol aktif
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.style.background = '';
                b.style.color = 'var(--emerald)';
                b.style.border = '2px solid var(--emerald)';
            });
            this.style.background = 'var(--emerald)';
            this.style.color = '#fff';
            this.style.border = 'none';

            // Filter tampilan kolom galeri
            const target = this.dataset.kategori;
            document.querySelectorAll('.galeri-col').forEach(col => {
                if (target === 'semua' || col.dataset.kategori === target) {
                    col.style.display = '';
                } else {
                    col.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
