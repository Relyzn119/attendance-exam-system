# 🏥 RSU Bunda Thamrin Medan — Sistem Manajemen Diklat, CBT Ujian Digital, & E-Arsip Repositori Karyawan

![Laravel Version](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue Version](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)
![TypeScript](https://img.shields.io/badge/TypeScript-5.x-3178C6?style=for-the-badge&logo=typescript&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-6.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)

**Sistem Web Terpadu Pengelolaan Pendidikan & Pelatihan (Diklat), Ujian Online Berbasis Token (CBT), Generasi Sertifikat PDF Digital, serta Repositori E-Arsip Dokumen HRD Karyawan & Diklat (Non-Medis/Kontrak) RSU Bunda Thamrin Medan.**

---

## 📌 Gambaran Umum (Overview)

Aplikasi **Hospital System — RSU Bunda Thamrin Medan** adalah platform manajemen SDM & Diklat modern berbasis Full-Stack (Laravel 12 + Vue 3). Aplikasi ini dirancang khusus untuk memodernisasi dan mengotomatisasi seluruh alur kerja diklat rumah sakit untuk **karyawan non-medis, pegawai kontrak (Satpam, Cleaning Service, Dapur Gizi, Driver), serta staf operasional/administrasi**, yang meliputi:
1. **Absensi & Pelaksanaan Ujian Digital (CBT)** untuk evaluasi kompetensi diklat internal (K3RS, PPI, Code Red, Sanitasi, dll).
2. **Evaluasi Otomatis & Penerbitan Sertifikat Kelulusan PDF Resmi** lengkap dengan tanda tangan digital direktur/pembicara.
3. **Repositori E-Arsip Berkas HRD & Diklat** untuk menyimpan, menyaring, preview, dan mengunduh berkas laporan diklat serta dokumen peserta.

> ℹ️ **Catatan**: Peserta diklat dan repositori ini khusus untuk kegiatan diklat & karyawan non-medis/operasional. Data lisensi profesi dokter/tenaga medis (seperti STR, SIP, Ijazah Dokter) **tidak termasuk** di dalam sistem arsip diklat ini.

Sistem dilengkapi dengan antarmuka **Light Pearl White-Gold Glassmorphic UI** yang modern, responsif, serta terlindungi oleh sistem autentikasi multi-role.

---

## 🚀 Fitur-Fitur Utama Aplikasi

### 1. 🔑 **Multi-Role Authentication & User Portal**
- **Admin HRD Portal**: Akses penuh ke dashboard kontrol ujian, pembuatan token, pengelolaan bank soal, ekspor PDF, serta manajemen e-arsip berkas.
- **Peserta Portal**: Akses mandiri untuk pendaftaran peserta baru (Register), login, pengerjaan ujian berbasis token, dan cetak sertifikat hasil kelulusan.

### 2. 📝 **Modul Absensi & Computer-Based Testing (CBT) Ujian Diklat**
- **Ditujukan Khusus Pegawai Non-Medis & Kontrak**: Satpam / Security, Cleaning Service, Asisten Koki Dapur Gizi, Driver Operasional, Laundry, dan Staf Administrasi.
- **Token Akses Ujian Unik**: Verifikasi token unik yang di-generate khusus oleh Admin HRD per peserta untuk menjamin keabsahan ujian.
- **Lembar Ujian Interaktif (`LembarUjian.vue`)**:
  - Countdown timer real-time.
  - Grid navigasi nomor soal & penandaan status ragu-ragu/terjawab.
  - Auto-save jawaban & proteksi konfirmasi sebelum submit.
- **Penilaian & Evaluasi Otomatis**:
  - Perhitungan skor instant & status kelulusan (**LULUS / REMEDIAL**).
  - Pembahasan detail per soal dan evaluasi durasi pengerjaan.
- **Pengelolaan Bank Soal Terintegrasi**:
  - Penambahan, pengeditan, dan penghapusan bank soal diklat (K3RS, PPI, Code Red, Sanitasi Gizi, dll).
  - Fitur **Acak Soal Otomatis** untuk meminimalisir kecurangan antar peserta.

### 3. 📄 **Generasi Sertifikat Digital & Laporan PDF Resmi**
- **Pencetakan Sertifikat Kelulusan PDF**: Generasi otomatis sertifikat diklat berformat PDF dengan nomor registrasi unik RSU Bunda Thamrin Medan (menggunakan `barryvdh/laravel-dompdf`).
- **Kustomisasi Penandatangan & TTD Digital**:
  - Pengaturan nama Direktur & Pembicara.
  - Opsi tanda tangan digital (unggah file PNG/JPG TTD & stempel) atau tanda tangan basah.
- **Export PDF Rekap Absensi & Ujian**: Unduh laporan rekapitulasi nilai dan kehadiran peserta ujian diklat dalam bentuk dokumen PDF siap cetak.

### 4. 🗄️ **Modul Repositori E-Arsip Dokumen HRD & Diklat (`EArsipIndex.vue`)**
- **Penyimpanan Terpusat Berkas HRD & Diklat**: Mengarsip dokumen digital kegiatan Pendidikan & Pelatihan (Diklat), laporan kegiatan, sertifikat pelatihan, dan berkas administratif peserta.
- **Multi-Upload Berkas PDF**: Mendukung pengunggahan multiple berkas PDF per entitas record diklat.
- **Multi-Filtering Presisi & Pencarian Cerdas**:
  - Pencarian kata kunci (Nama Karyawan, NIK, Judul Diklat, Unit/Departemen).
  - Filter Tanggal Pelaksanaan/Upload (Exact Date).
  - Filter Bulan (Januari s.d. Desember).
  - Filter Minggu (Minggu Ke-1 s.d. Ke-4).
  - Filter Tahun (misal: 2024, 2025, 2026).
- **Interactive PDF Viewer**: Preview isi berkas PDF langsung di dalam modal tanpa perlu meninggalkan aplikasi.
- **Merge & Single PDF Download (.PDF)**: Seluruh lampiran berkas PDF yang diunggah untuk suatu entitas diklat di-merge (digabungkan) secara otomatis oleh sistem menjadi **1 File PDF Utuh** saat diunduh (menggunakan engine FPDI PDF Merger), sehingga lebih praktis dibanding format `.ZIP`.
- **Live Counter Statistics**: Panel statistik real-time menghitung Total Diklat/Pegawai, Total Berkas PDF, dan Diklat Bulan Ini.

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

### Backend (Server-Side)
- **Framework**: Laravel 12.x (PHP 8.2+)
- **Authentication & Security**: Laravel Sanctum & Role Management
- **PDF & PDF Merger Engine**: `barryvdh/laravel-dompdf`, `setasign/fpdi`, & `fpdf` (Merge multiple PDF files into 1 PDF)
- **File Handling & Storage**: Laravel Storage Disk
- **Database**: SQLite / MySQL / MariaDB

### Frontend (Client-Side)
- **Framework**: Vue 3 (Composition API / Options API Script Setup) & TypeScript
- **Build Tool**: Vite 6.x
- **Inertia Integration**: Inertia.js 2.0 (`@inertiajs/vue3`)
- **Styling**: Tailwind CSS v4 (Custom Glassmorphism Pearl-Gold Theme)
- **Icons & Typography**: Bootstrap Icons, Lucide Vue Next, Google Fonts (Instrument Sans / Inter)

---

## 📂 Struktur Utama Proyek

```
Hospital-System/
├── app/
│   ├── Http/Controllers/
│   │   ├── AdminController.php      # Manajemen Peserta, Token, PDF Rekap Absensi, Setting Sertifikat, PDF Merger Download
│   │   ├── AuthController.php       # Autentikasi User (Login, Register, Profile, Change Password)
│   │   ├── BankSoalController.php   # Manajemen Bank Soal & Logika Acak Soal
│   │   ├── PegawaiController.php    # Modul E-Arsip Diklat, Filtering, & Upload Berkas PDF
│   │   └── UjianController.php      # Execution Engine Ujian CBT, Submit, Review, & Cetak Sertifikat
│   └── Models/                      # Model Eloquent (User, Pegawai, BerkasPegawai, BankSoal, RiwayatUjian, dll)
├── database/
│   ├── migrations/                  # Migrasi Skema Tabel Database
│   └── seeders/                     # Seeder Data Awal (Soal, User Admin/Peserta, Sertifikat Setting)
├── resources/
│   ├── css/
│   │   └── app.css                  # Import Tailwind CSS v4 & Styling Glassmorphism Light Pearl-Gold
│   ├── js/
│   │   ├── components/
│   │   │   ├── DashboardAdmin.vue   # Panel Kontrol Admin (Absensi, Token, Bank Soal, Cert Setting)
│   │   │   ├── DashboardPeserta.vue # Dashboard Peserta (Masuk Token, Info Ujian)
│   │   │   ├── EArsipIndex.vue      # Modul Repositori E-Arsip Diklat & Filtering PDF
│   │   │   ├── LembarUjian.vue      # Antarmuka Ujian CBT (Timer, Navigasi, Ragu-ragu, Submit)
│   │   │   ├── Login.vue            # Form Login User/Admin
│   │   │   └── Register.vue         # Form Pendaftaran Peserta Baru
│   │   └── app.ts                   # Entry Point Vue SPA Router & App Initialization
│   └── views/
│       └── app.blade.php            # Master Blade Layout Root
├── routes/
│   ├── api.php                      # Endpoints REST API (Auth, Admin, Bank Soal, Ujian, Pegawai)
│   └── web.php                      # Web Route Entrypoint
├── storage/                         # Penyimpanan File Berkas PDF
├── composer.json                    # Dependensi Package PHP/Laravel
└── package.json                     # Dependensi Package Node.js/Vue/Tailwind
```

---

## 💡 Alur Penggunaan Sistem (Workflow Summary)

### A. Alur Ujian Diklat (Admin & Peserta Non-Medis/Kontrak)
1. **Registrasi Peserta**: Karyawan non-medis / pegawai kontrak mendaftar akun melalui form **Register** atau didaftarkan oleh Admin HRD.
2. **Generate Token Ujian**: Admin HRD masuk ke **Dashboard Admin (Menu Absensi & Ujian)** dan memilih tombol **Generate Token** untuk peserta yang akan ujian.
3. **Mulai Ujian CBT**: Peserta login ke akun masing-masing, memasukkan Kode Token Ujian di **Dashboard Peserta**, lalu mengerjakan ujian di **Lembar Ujian Interaktif**.
4. **Penilaian & Sertifikat**: Setelah ujian disubmit, sistem menghitung nilai secara otomatis. Jika peserta **LULUS**, tombol **Cetak Sertifikat PDF** dapat diakses langsung oleh peserta maupun admin.
5. **Ekspor Laporan**: Admin HRD dapat mengekspor rekapitulasi nilai dan daftar kehadiran seluruh peserta ke format PDF resmi.

### B. Alur E-Arsip Repositori Dokumen HRD & Diklat
1. **Input Data Diklat/Pegawai**: Admin HRD membuka menu **E-Arsip Pegawai** dan menekan **Tambah Data Diklat Baru**.
2. **Unggah Berkas PDF**: Admin melampirkan berkas fisik PDF (Dokumentasi Diklat, Sertifikat Pelatihan, Berkas Karyawan, dll) ke dalam entitas data.
3. **Pencarian & Filtering**: Admin dapat mencari data secara fleksibel menggunakan kombinasi pencarian kata kunci, tanggal upload, bulan, minggu, dan tahun.
4. **Preview & Merge PDF Download**: Admin dapat melihat isi dokumen PDF langsung di browser via **PDF Previewer** atau mengunduh seluruh berkas terlampir yang di-merge otomatis menjadi **1 File PDF**.

---

## ⚡ Petunjuk Instalasi & Memulai Proyek

### 1. Prasyarat Sistem
- PHP >= 8.2
- Composer >= 2.x
- Node.js >= 18.x & NPM
- Extension PHP: `pdo`, `mbstring`, `gd`

### 2. Langkah Instalasi

1. **Clone Repositori & Masuk ke Direktori**:
   ```bash
   git clone https://github.com/Relyzn119/attendance-exam-system.git Hospital-System
   cd Hospital-System
   ```

2. **Install Dependensi Backend (Composer)**:
   ```bash
   composer install
   ```

3. **Install Dependensi Frontend (NPM)**:
   ```bash
   npm install
   ```

4. **Konfigurasi Environment (`.env`)**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Pastikan pengaturan database (`DB_CONNECTION`, `DB_DATABASE`, dll) di file `.env` sudah disesuaikan.*

5. **Jalankan Migrasi Database & Seeder**:
   ```bash
   php artisan migrate --seed
   ```

6. **Buat Symlink Storage**:
   ```bash
   php artisan storage:link
   ```

7. **Jalankan Server Development**:
   ```bash
   npm run dev
   ```
   *Command di atas akan menjalankan PHP server, Queue listener, dan Vite bundler secara bersamaan.*

---

## 📄 Lisensi & Hak Cipta

Copyright © 2026 **RSU Bunda Thamrin Medan** — *HRD & Systems Development Team*. Hak Cipta Dilindungi Undang-Undang.
