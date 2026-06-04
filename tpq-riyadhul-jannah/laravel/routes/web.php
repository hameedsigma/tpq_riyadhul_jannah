<?php

// ============================================================
// routes/web.php - Routing Laravel 11
// TPQ Riyadhul Jannah - Halaman Publik
//
// File ini adalah "peta jalan" aplikasi Laravel.
// Setiap baris mendefinisikan: URL → Controller → View
// ============================================================

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\KontakController;

// ── Halaman Beranda / Home ───────────────────────────────────
//    URL   : namadomain.com/  atau  namadomain.com/beranda
//    Method: GET
//    Tampil: Semua section (hero, profil singkat, program, galeri, kontak)
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

// ── Halaman Profil & Sejarah TPQ ────────────────────────────
//    URL   : namadomain.com/profil
//    Method: GET
//    Tampil: Sejarah, Visi, Misi, Sambutan Kepala TPQ
Route::get('/profil', [ProfilController::class, 'index'])->name('profil');

// ── Halaman Program Pembelajaran ────────────────────────────
//    URL   : namadomain.com/program
//    Method: GET
//    Tampil: Daftar program unggulan (Iqra, Tahfidz, Tilawah, dll)
Route::get('/program', [ProgramController::class, 'index'])->name('program');

// ── Halaman Galeri Kegiatan ──────────────────────────────────
//    URL   : namadomain.com/galeri
//    Method: GET
//    Tampil: Grid foto kegiatan santri
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');

// ── Halaman Kontak (Form Pesan) ──────────────────────────────
//    URL   : namadomain.com/kontak
//    Method: GET  → Tampilkan form
//    Method: POST → Proses pengiriman pesan (validasi + simpan/kirim)
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak/kirim', [KontakController::class, 'kirim'])->name('kontak.kirim');
