# Logical Test dan Technical Test

Jawaban untuk logical dan technical test ada pada file logical_test.php dan techniqal_test.sql

# School Management System

Sistem manajemen sekolah berbasis web untuk mengelola data siswa, nilai, dan lokasi rumah siswa.

## Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Vue 3 + Inertia.js
- **Database**: PostgreSQL
- **Styling**: Tailwind CSS
- **Map**: Leaflet.js
- **Chart**: ApexCharts

## Arsitektur

Proyek ini menggunakan arsitektur **Monolith Modern** dengan Laravel + Inertia.js + Vue (digabung), dengan alasan:

1. **Kecepatan development** — Satu codebase, satu deployment, tanpa overhead konfigurasi dua aplikasi terpisah
2. **Skala aplikasi** — Aplikasi ini tidak memerlukan skala besar, sehingga monolith lebih dari cukup
3. **Belum ada kebutuhan mobile** — Tidak diperlukan REST API terpisah untuk konsumsi mobile app
4. **Single codebase** — Perubahan langsung tercermin tanpa risiko breaking change antar dua aplikasi
5. **Auth lebih sederhana** — Laravel Session + CSRF sudah battle-tested tanpa perlu token management
6. **Validasi terintegrasi** — Error validasi dari Laravel otomatis diteruskan ke Vue component via Inertia

## Fitur

- Autentikasi (Login / Logout)
- Dashboard dengan card statistik, chart rata-rata nilai per kelas, dan map lokasi siswa
- Manajemen Data Siswa (CRUD + peta lokasi rumah)
- Manajemen Data Nilai (CRUD + Import/Export Excel)

## Requirements

Pastikan sudah terinstall:

- PHP >= 8.2
- Composer
- Node.js >= 20
- NPM
- PostgreSQL

## Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd school-management
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env`, sesuaikan konfigurasi database:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=school_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Jalankan Migration & Seeder
```bash
php artisan migrate --seed
```

Seeder akan membuat data awal:
- 3 siswa (Andi, Budi, Citra)
- 6 data nilai sesuai masing-masing siswa

### 5. Jalankan Development Server

Buka **dua terminal** secara bersamaan:
```bash
# Terminal 1 — Laravel
php artisan serve

# Terminal 2 — Vite
npm run dev
```

Akses aplikasi di browser: [http://localhost:8000](http://localhost:8000)

## Login

Setelah instalasi, daftarkan akun baru melalui halaman register:
```
http://localhost:8000/register
```

## Build untuk Production
```bash
npm run build
php artisan optimize
```