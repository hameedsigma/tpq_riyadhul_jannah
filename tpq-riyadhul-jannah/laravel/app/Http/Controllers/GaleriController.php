<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// ============================================================
// GaleriController.php
// Mengelola halaman Galeri Kegiatan TPQ.
// Data diambil dari tabel 'galeri' di database.
// Foto disimpan di /uploads/galeri/ (folder PHP Native).
// ============================================================

class GaleriController extends Controller
{
    public function index()
    {
        // Ambil semua foto dari database, dikelompokkan per judul album.
        // Penjelasan setiap kolom aggregate:
        //   MIN(foto)     → Foto pertama sebagai cover album (thumbnail)
        //   COUNT(*)      → Total foto dalam satu album
        //   GROUP_CONCAT  → Semua nama file foto digabung dengan koma
        $galeri = DB::table('galeri')
            ->select(
                'judul',
                'kategori',
                DB::raw('MIN(foto) as cover'),
                DB::raw('COUNT(*) as total'),
                DB::raw('GROUP_CONCAT(foto ORDER BY id ASC SEPARATOR ",") as foto_list')
            )
            ->groupBy('judul', 'kategori')
            ->orderByDesc(DB::raw('MAX(created_at)'))
            ->paginate(12); // Tampilkan 12 album per halaman

        // Ambil daftar kategori unik untuk filter
        $kategori = DB::table('galeri')
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('galeri', compact('galeri', 'kategori'));
    }
}
