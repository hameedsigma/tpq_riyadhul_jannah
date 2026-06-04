-- ============================================================
-- migration_hero_settings.sql
-- Jalankan file ini di phpMyAdmin jika website sudah berjalan
-- sebelumnya (database sudah ada tapi tabel hero_settings belum).
--
-- Jika Anda install dari awal, cukup gunakan database.sql saja.
-- ============================================================

USE `tpq_riyadhul_jannah`;

CREATE TABLE IF NOT EXISTS `hero_settings` (
  `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `kunci`      VARCHAR(80)  NOT NULL UNIQUE,
  `nilai`      TEXT         NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `hero_settings` (`kunci`, `nilai`) VALUES
('bg_tipe',            'warna'),
('bg_gambar',          ''),
('bg_warna_utama',     '#059669'),
('bg_warna_kedua',     '#d97706'),
('bg_arah_gradient',   '135deg'),
('overlay_opasitas',   '0.75'),
('overlay_warna',      '#059669'),
('hero_judul',         'Selamat Datang di'),
('hero_judul_sub',     'TPQ Riyadhul Jannah'),
('hero_deskripsi',     'Mencetak Generasi Qurani yang Berakhlak Mulia'),
('tombol_utama_label', 'Lihat Program'),
('tombol_utama_link',  '#program'),
('tombol_dua_label',   'Daftar Sekarang'),
('tombol_dua_wa',      '1');
