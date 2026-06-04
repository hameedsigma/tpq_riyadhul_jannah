<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// ============================================================
// ProfilController.php
// Mengelola halaman Profil & Sejarah TPQ.
// Data diambil dari tabel 'profil' di database.
// ============================================================

class ProfilController extends Controller
{
    public function index()
    {
        // Ambil semua data profil dari database
        // Tabel 'profil' berisi: sejarah, visi, misi, sambutan
        $profilRows = DB::table('profil')->select('kunci', 'konten', 'judul')->get();

        // Ubah menjadi key-value agar mudah dipakai di view
        // Contoh akses: $profil['sejarah'], $profil['visi']
        $profil = [];
        $judul  = [];
        foreach ($profilRows as $row) {
            $profil[$row->kunci] = $row->konten;
            $judul[$row->kunci]  = $row->judul;
        }

        return view('profil', compact('profil', 'judul'));
    }
}
