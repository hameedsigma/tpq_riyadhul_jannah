-- ============================================================
-- database.sql - Struktur Database TPQ RIYADHUL JANNAH
-- Jalankan file ini di phpMyAdmin atau MySQL CLI
-- ============================================================

CREATE DATABASE IF NOT EXISTS `tpq_riyadhul_jannah`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `tpq_riyadhul_jannah`;

-- -------------------------------------------------------
-- Tabel: users (Admin)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nama`       VARCHAR(100) NOT NULL,
  `username`   VARCHAR(50)  NOT NULL UNIQUE,
  `email`      VARCHAR(150) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `role`       ENUM('admin','superadmin') NOT NULL DEFAULT 'admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Password default: "password" (sudah di-hash dengan bcrypt)
-- WAJIB ganti password setelah login pertama!
INSERT INTO `users` (`nama`, `username`, `email`, `password`, `role`) VALUES
('Super Admin', 'admin', 'admin@tpqriyadhuljannah.id',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin');

-- -------------------------------------------------------
-- Tabel: profil (Sejarah, Visi, Misi, Sambutan)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `profil` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `kunci`       VARCHAR(50) NOT NULL UNIQUE,
  `judul`       VARCHAR(200) NOT NULL,
  `konten`      TEXT NOT NULL,
  `updated_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `profil` (`kunci`, `judul`, `konten`) VALUES
('sejarah', 'Sejarah TPQ', 'TPQ Riyadhul Jannah berdiri pada tahun 2005 atas prakarsa para tokoh masyarakat dan ulama setempat yang peduli terhadap pendidikan Al-Quran generasi muda. Berawal dari sebuah mushola kecil dengan 15 santri, kini TPQ Riyadhul Jannah telah berkembang menjadi lembaga pendidikan Al-Quran terpercaya dengan ratusan santri aktif.'),
('visi', 'Visi', 'Menjadi lembaga pendidikan Al-Quran terdepan yang melahirkan generasi Qurani, berakhlak mulia, dan bermanfaat bagi agama, bangsa, dan negara.'),
('misi', 'Misi', '1. Menyelenggarakan pendidikan Al-Quran yang berkualitas dan menyenangkan.\n2. Membentuk karakter santri yang berakhlakul karimah berdasarkan Al-Quran dan Sunnah.\n3. Mengembangkan potensi santri melalui program tahfidz, tilawah, dan kajian Islam.\n4. Membangun kerjasama yang harmonis antara TPQ, orang tua, dan masyarakat.\n5. Mencetak hafidz/hafidzah yang mampu menjadi teladan di lingkungannya.'),
('sambutan', 'Sambutan Kepala TPQ', 'Bismillahirrahmanirrahim. Assalamu\'alaikum Warahmatullahi Wabarakatuh.\n\nAlhamdulillah, segala puji bagi Allah SWT yang telah memberikan kesempatan kepada kita untuk terus berkhidmat dalam pendidikan Al-Quran. TPQ Riyadhul Jannah hadir sebagai wadah bagi putra-putri kita untuk belajar membaca, memahami, dan mengamalkan Al-Quran sejak dini.\n\nKami berkomitmen untuk memberikan pendidikan terbaik dengan metode yang menyenangkan dan mudah dipahami. Semoga Allah SWT meridhoi setiap langkah kita dalam mendidik generasi Qurani.\n\nWassalamu\'alaikum Warahmatullahi Wabarakatuh.\n\nUstadz Ahmad Fauzi, S.Pd.I\nKepala TPQ Riyadhul Jannah');

-- -------------------------------------------------------
-- Tabel: program (Program Unggulan)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `program` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `nama`        VARCHAR(100) NOT NULL,
  `deskripsi`   TEXT NOT NULL,
  `ikon`        VARCHAR(50)  NOT NULL DEFAULT 'bi-book',
  `urutan`      TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `aktif`       TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `program` (`nama`, `deskripsi`, `ikon`, `urutan`) VALUES
('Iqra & Al-Quran', 'Program dasar membaca Al-Quran menggunakan metode Iqra yang terbukti efektif. Cocok untuk santri pemula usia 4-7 tahun.', 'bi-book-half', 1),
('Tahfidz Al-Quran', 'Program menghafal Al-Quran secara sistematis dengan target minimal 1 juz per tahun. Dibimbing oleh ustadz/ustadzah berpengalaman.', 'bi-journal-bookmark', 2),
('Tilawah & Tajwid', 'Program membaca Al-Quran dengan tartil dan indah sesuai kaidah tajwid. Santri diajarkan makhraj huruf yang benar.', 'bi-mic', 3),
('Akhlak & Adab', 'Program pembentukan karakter Islami melalui kisah-kisah teladan, hafalan doa harian, dan praktik adab dalam kehidupan sehari-hari.', 'bi-heart', 4),
('Kaligrafi Islam', 'Program seni menulis indah huruf Arab (khat) yang mengembangkan kreativitas santri sekaligus mendekatkan mereka pada keindahan Al-Quran.', 'bi-pen', 5),
('Bahasa Arab Dasar', 'Pengenalan kosakata dan percakapan bahasa Arab dasar agar santri dapat memahami makna ayat-ayat Al-Quran.', 'bi-translate', 6);

-- -------------------------------------------------------
-- Tabel: galeri (Foto Kegiatan)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `galeri` (
  `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `judul`       VARCHAR(200) NOT NULL,
  `deskripsi`   VARCHAR(500) DEFAULT NULL,
  `foto`        VARCHAR(255) NOT NULL,
  `kategori`    VARCHAR(50)  NOT NULL DEFAULT 'Kegiatan',
  `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Tabel: kontak (Informasi Kontak)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `kontak` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `kunci`      VARCHAR(50) NOT NULL UNIQUE,
  `nilai`      TEXT NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `kontak` (`kunci`, `nilai`) VALUES
('alamat',       'Jl. Masjid Al-Ikhlas No. 12, RT 03/RW 05, Kelurahan Sukamaju, Kecamatan Cibeunying, Kota Bandung, Jawa Barat 40123'),
('whatsapp',     '6281234567890'),
('whatsapp_text','0812-3456-7890'),
('email',        'info@tpqriyadhuljannah.id'),
('jam_belajar',  'Senin - Jumat: 15.30 - 17.30 WIB\nSabtu: 08.00 - 10.00 WIB'),
('maps_embed',   'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.798475948!2d107.6191!3d-6.9175!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwNTUnMDMuMCJTIDEwN8KwMzcnMDguOCJF!5e0!3m2!1sid!2sid!4v1234567890'),
('facebook',     'https://facebook.com/tpqriyadhuljannah'),
('instagram',    'https://instagram.com/tpqriyadhuljannah'),
('youtube',      'https://youtube.com/@tpqriyadhuljannah');

-- -------------------------------------------------------
-- Tabel: pengunjung (Statistik Pengunjung)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pengunjung` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ip_address` VARCHAR(45) NOT NULL,
  `tanggal`    DATE NOT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_visit` (`ip_address`, `tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Tabel: hero_settings (Pengaturan Tampilan Beranda/Hero)
-- Menyimpan semua konfigurasi visual section hero di halaman utama.
-- Satu baris = satu pengaturan (sistem key-value)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `hero_settings` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `kunci`      VARCHAR(80)  NOT NULL UNIQUE,
  `nilai`      TEXT         NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data default pengaturan hero
INSERT INTO `hero_settings` (`kunci`, `nilai`) VALUES
-- Tipe background: 'gambar' atau 'warna'
('bg_tipe',            'warna'),
-- Path gambar background (diisi otomatis saat upload)
('bg_gambar',          ''),
-- Warna gradient utama (kiri/atas)
('bg_warna_utama',     '#059669'),
-- Warna gradient kedua (kanan/bawah)
('bg_warna_kedua',     '#d97706'),
-- Arah gradient: 'to right', 'to bottom', '135deg', dll
('bg_arah_gradient',   '135deg'),
-- Opasitas overlay gelap di atas gambar (0.0 - 1.0)
('overlay_opasitas',   '0.75'),
-- Warna overlay di atas gambar
('overlay_warna',      '#059669'),
-- Judul utama hero (baris 1)
('hero_judul',         'Selamat Datang di'),
-- Judul highlight (baris 2, ditampilkan dengan warna gold)
('hero_judul_sub',     'TPQ Riyadhul Jannah'),
-- Teks deskripsi di bawah judul
('hero_deskripsi',     'Mencetak Generasi Qurani yang Berakhlak Mulia'),
-- Label tombol utama (kiri)
('tombol_utama_label', 'Lihat Program'),
-- Link tombol utama
('tombol_utama_link',  '#program'),
-- Label tombol kedua (kanan)
('tombol_dua_label',   'Daftar Sekarang'),
-- Apakah tombol kedua ke WhatsApp: '1' = ya, '0' = link biasa
('tombol_dua_wa',      '1');

