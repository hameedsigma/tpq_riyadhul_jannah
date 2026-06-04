<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// ============================================================
// BerandaController.php
// Mengambil semua data dari database untuk halaman utama.
// Halaman ini menampilkan SEMUA section sekaligus:
// Hero → Statistik → Profil Singkat → Program → Galeri → Kontak
// ============================================================

class BerandaController extends Controller
{
    public function index()
    {
        // ── 1. Ambil data Profil (Sejarah, Visi, Misi, Sambutan) ──
        // Ubah hasil query dari array of rows menjadi key-value
        // agar mudah diakses di view: $profil['sejarah'], $profil['visi'], dll
        $profilRows = DB::table('profil')->select('kunci', 'konten')->get();
        $profil = [];
        foreach ($profilRows as $row) {
            $profil[$row->kunci] = $row->konten;
        }

        // ── 2. Ambil daftar Program yang aktif ──
        // Diurutkan berdasarkan kolom 'urutan' secara ascending
        $programs = DB::table('program')
            ->where('aktif', 1)
            ->orderBy('urutan', 'asc')
            ->get();

        // ── 3. Ambil Galeri (dikelompokkan per judul/album) ──
        // Mengambil cover (foto pertama) dan total foto per album
        $galeri = DB::table('galeri')
            ->select(
                'judul',
                'kategori',
                DB::raw('MIN(foto) as cover'),        // Foto pertama sebagai cover
                DB::raw('COUNT(*) as total'),          // Total foto dalam album
                DB::raw('GROUP_CONCAT(foto ORDER BY id ASC SEPARATOR ",") as foto_list')
            )
            ->groupBy('judul', 'kategori')
            ->orderByDesc(DB::raw('MAX(created_at)')) // Album terbaru tampil duluan
            ->limit(8)                                 // Batasi 8 album di beranda
            ->get();

        // ── 4. Ambil data Kontak (alamat, WA, email, dll) ──
        $kontakRows = DB::table('kontak')->select('kunci', 'nilai')->get();
        $kontak = [];
        foreach ($kontakRows as $row) {
            $kontak[$row->kunci] = $row->nilai;
        }

        // ── 5. Data statistik statis (bisa diubah ke database nantinya) ──
        $statistik = [
            'santri'          => 350,
            'hafidz'          => 48,
            'ustadz'          => 15,
            'tahun_berdiri'   => 2005,
            'tahun_berjalan'  => date('Y') - 2005,
        ];

        // ── Kirim semua data ke view 'beranda.blade.php' ──
        // Fungsi 'compact()' adalah shortcut untuk membuat array:
        // ['profil' => $profil, 'programs' => $programs, ...]
        return view('beranda', compact(
            'profil',
            'programs',
            'galeri',
            'kontak',
            'statistik'
        ));
    }
}
