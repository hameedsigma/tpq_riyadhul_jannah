<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// ============================================================
// ProgramController.php
// Mengelola halaman Program Pembelajaran TPQ.
// Data diambil dari tabel 'program' di database.
// ============================================================

class ProgramController extends Controller
{
    public function index()
    {
        // Ambil semua program yang aktif, diurutkan berdasarkan kolom 'urutan'
        // Kolom 'aktif' = 1 berarti program sedang berjalan/ditampilkan
        $programs = DB::table('program')
            ->where('aktif', 1)
            ->orderBy('urutan', 'asc')
            ->get();

        return view('program', compact('programs'));
    }
}
