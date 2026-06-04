<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

// ============================================================
// KontakController.php
// Mengelola halaman Kontak:
//   index() → Menampilkan halaman dengan form dan info kontak
//   kirim() → Memproses pengiriman form pesan dari pengunjung
// ============================================================

class KontakController extends Controller
{
    // ── GET /kontak → Tampilkan halaman kontak ───────────────
    public function index()
    {
        // Ambil semua informasi kontak dari database
        $kontakRows = DB::table('kontak')->select('kunci', 'nilai')->get();

        // Ubah menjadi key-value: $kontak['alamat'], $kontak['whatsapp'], dll
        $kontak = [];
        foreach ($kontakRows as $row) {
            $kontak[$row->kunci] = $row->nilai;
        }

        return view('kontak', compact('kontak'));
    }

    // ── POST /kontak/kirim → Proses form pesan ───────────────
    public function kirim(Request $request)
    {
        // ── Validasi Input ──────────────────────────────────
        // Laravel akan otomatis kembali ke halaman sebelumnya
        // dengan pesan error jika validasi gagal
        $validated = $request->validate([
            'nama'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'telepon' => 'nullable|string|max:20',
            'pesan'   => 'required|string|min:10|max:1000',
        ], [
            // Pesan error dalam Bahasa Indonesia
            'nama.required'    => 'Nama lengkap wajib diisi.',
            'email.required'   => 'Alamat email wajib diisi.',
            'email.email'      => 'Format email tidak valid.',
            'pesan.required'   => 'Isi pesan wajib diisi.',
            'pesan.min'        => 'Pesan minimal 10 karakter.',
        ]);

        // ── Simpan pesan ke database (opsional) ────────────
        // Catatan: Anda perlu membuat tabel 'pesan_masuk' di database.sql
        // Struktur tabel:
        // CREATE TABLE pesan_masuk (
        //   id INT AUTO_INCREMENT PRIMARY KEY,
        //   nama VARCHAR(100), email VARCHAR(150),
        //   telepon VARCHAR(20), pesan TEXT,
        //   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        // );

        // DB::table('pesan_masuk')->insert([
        //     'nama'       => $validated['nama'],
        //     'email'      => $validated['email'],
        //     'telepon'    => $validated['telepon'] ?? null,
        //     'pesan'      => $validated['pesan'],
        //     'created_at' => now(),
        // ]);

        // ── Redirect kembali dengan pesan sukses ────────────
        // session()->flash() menyimpan pesan hanya untuk 1 request berikutnya
        return redirect()
            ->route('kontak')
            ->with('sukses', 'Terima kasih! Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.');
    }
}
