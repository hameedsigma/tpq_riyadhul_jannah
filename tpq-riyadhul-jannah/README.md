# TPQ Riyadhul Jannah - Website Resmi

Proyek website untuk TPQ Riyadhul Jannah dengan **arsitektur hibrida**:
- **Frontend Publik**: Laravel 11 (MVC, Blade Templating)
- **Backend Admin**: PHP Native (tanpa framework)

---

## Struktur Folder Lengkap

```
tpq-riyadhul-jannah/
│
├── .htaccess                  ← [ROUTING UTAMA] Mengarahkan lalu lintas
├── config.php                 ← Konfigurasi database PHP Native
├── database.sql               ← Struktur & data awal database
├── index.php                  ← (Opsional) Landing page PHP Native lama
│
├── assets/                    ← Aset statis publik
│   └── images/
│       ├── logo.png
│       ├── kepala.jpg
│       └── sejarah.jpg
│
├── uploads/                   ← File upload dari Admin
│   └── galeri/                ← Foto kegiatan
│
├── admin/                     ← [PHP NATIVE] Panel Admin
│   ├── login.php
│   ├── dashboard.php
│   ├── profil.php
│   ├── program.php
│   ├── galeri.php
│   ├── kontak.php
│   ├── ganti-password.php
│   ├── logout.php
│   └── includes/
│       ├── auth.php
│       ├── sidebar.php
│       └── sidebar_end.php
│
└── laravel/                   ← [LARAVEL 11] Frontend Publik
    ├── .env                   ← Konfigurasi environment (JANGAN di-Git)
    ├── .env.example           ← Template .env (di-commit ke Git)
    ├── artisan                ← CLI Laravel
    ├── composer.json
    │
    ├── app/
    │   └── Http/
    │       └── Controllers/
    │           ├── BerandaController.php
    │           ├── ProfilController.php
    │           ├── ProgramController.php
    │           ├── GaleriController.php
    │           └── KontakController.php
    │
    ├── routes/
    │   └── web.php            ← Definisi semua URL publik
    │
    ├── resources/
    │   └── views/
    │       ├── layouts/
    │       │   └── app.blade.php   ← Master layout (Navbar + Footer)
    │       ├── beranda.blade.php
    │       ├── profil.blade.php
    │       ├── program.blade.php
    │       ├── galeri.blade.php
    │       └── kontak.blade.php
    │
    └── public/                ← Document root Laravel
        ├── .htaccess          ← Front controller Laravel
        └── index.php          ← Entry point Laravel
```

---

## Cara Install dari Awal (XAMPP)

### 1. Clone / Salin Proyek ke XAMPP

```bash
# Letakkan di:
C:\xampp\htdocs\tpq-riyadhul-jannah\
```

### 2. Buat Database

Buka phpMyAdmin → Import file `database.sql`, atau jalankan:

```sql
CREATE DATABASE tpq_riyadhul_jannah CHARACTER SET utf8mb4;
```

### 3. Install Laravel 11 di dalam folder `laravel/`

```bash
# Masuk ke folder proyek
cd C:\xampp\htdocs\tpq-riyadhul-jannah

# Install Laravel baru di subfolder 'laravel'
composer create-project laravel/laravel laravel

# Atau jika sudah ada folder laravel, install dependensinya saja:
cd laravel
composer install
```

### 4. Konfigurasi .env Laravel

```bash
# Salin template
copy laravel\.env.example laravel\.env

# Generate APP_KEY
cd laravel
php artisan key:generate
```

Edit `laravel/.env`:

```ini
APP_URL=http://localhost/tpq-riyadhul-jannah

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tpq_riyadhul_jannah
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Salin File-File Kustom ke Laravel

Salin file-file yang sudah dibuat:
- `laravel/routes/web.php` → ganti file yang ada
- `laravel/app/Http/Controllers/*.php` → taruh di folder ini
- `laravel/resources/views/` → taruh semua file blade

### 6. Akses Website

| URL | Keterangan |
|-----|-----------|
| `http://localhost/tpq-riyadhul-jannah/` | Halaman publik (Laravel) |
| `http://localhost/tpq-riyadhul-jannah/profil` | Halaman Profil (Laravel) |
| `http://localhost/tpq-riyadhul-jannah/admin` | Panel Admin (PHP Native) |

---

## Cara Kerja .htaccess Utama

```
Pengunjung akses URL
        │
        ▼
.htaccess utama di root
        │
        ├── URL dimulai /admin/  → Langsung ke PHP Native (tidak diarahkan)
        ├── URL dimulai /assets/ → Langsung ke folder assets
        ├── URL dimulai /uploads/→ Langsung ke folder uploads
        │
        └── URL lainnya → Diteruskan ke laravel/public/index.php
                                          │
                                          ▼
                                  Laravel router (routes/web.php)
                                          │
                                          ▼
                                  Controller → View (.blade.php)
```

---

## Integrasi Database: Laravel ↔ PHP Native

Kunci utamanya sangat sederhana: **gunakan nama database yang sama**.

### PHP Native (`config.php`)
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'tpq_riyadhul_jannah');  // ← nama database
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Laravel (`laravel/.env`)
```ini
DB_HOST=127.0.0.1
DB_DATABASE=tpq_riyadhul_jannah          # ← nama database SAMA
DB_USERNAME=root
DB_PASSWORD=
```

Karena keduanya menunjuk ke database `tpq_riyadhul_jannah` yang sama:
- Admin PHP Native **edit** data → Laravel **langsung membaca** perubahan itu.
- Tidak ada sinkronisasi, tidak ada duplikasi. **Satu database, dua sistem**.

---

## Login Admin Default

| Field    | Value         |
|----------|---------------|
| Username | `admin`       |
| Password | `password`    |

> **WAJIB ganti password** setelah login pertama!

---

## Teknologi yang Digunakan

- **Backend Admin**: PHP 8+ Native, PDO, Session
- **Frontend Publik**: Laravel 11, Blade Templating
- **Database**: MySQL (via XAMPP)
- **CSS Framework**: Bootstrap 5.3
- **Icons**: Bootstrap Icons 1.11
- **Fonts**: Google Fonts (Poppins, Amiri)
