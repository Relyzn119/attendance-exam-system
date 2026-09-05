# 🤖 Project Context & Guidelines for AI Agents (Hospital-System)

Project Name: **RSU Bunda Thamrin Medan — Sistem Manajemen Diklat & E-Arsip Repositori Karyawan**

## 🎯 Key Domain Rules & Scope
1. **Target Audience / Peserta Diklat**:
   - Sistem ini ditujukan khusus untuk kegiatan Diklat & evaluasi karyawan non-medis / pegawai kontrak (Satpam/Security, Cleaning Service, Asisten Koki Dapur Gizi, Driver Operasional, Laundry, Staf Administrasi).
   - **PENTING**: Dokumen lisensi medis dokter/perawat seperti **STR, SIP, maupun Ijazah Profesi Dokter TIDAK ADA dan TIDAK TERMASUK** di dalam repositori atau modul diklat ini.

2. **Metode Download Berkas E-Arsip**:
   - Seluruh berkas PDF yang terlampir pada satu data diklat/peserta **DI-MERGE (digabungkan)** oleh backend (`FPDI / FPDF`) menjadi **1 File PDF Utuh** saat diunduh.
   - **TIDAK LAGI** menggunakan format `.ZIP`.

3. **Multi-Role System**:
   - **Admin HRD**: Akses penuh ke dashboard kontrol ujian, generate token, bank soal, setting sertifikat (Direktur/Pembicara/TTD), export PDF rekap absensi, dan E-Arsip.
   - **Peserta**: Login & Register mandiri, pengerjaan ujian CBT via Token, review pembahasan, dan cetak Sertifikat PDF kelulusan.

## 🛠️ Tech Stack & Architecture
- **Backend**: Laravel 12.x (PHP 8.2+), Sanctum, DomPDF (`barryvdh/laravel-dompdf`), FPDI (`setasign/fpdi`).
- **Frontend**: Vue 3 (Script Setup / Composition API) + TypeScript + Vite 6 + Inertia.js 2.0.
- **Styling**: Tailwind CSS v4 (Glassmorphism Pearl-Gold Theme).
- **Core Components**:
  - `DashboardAdmin.vue`: Pusat kendali absensi, token, bank soal, sertifikat.
  - `DashboardPeserta.vue`: Token input & status ujian peserta.
  - `LembarUjian.vue`: Interface ujian CBT dengan countdown timer, grid navigasi, & submit.
  - `EArsipIndex.vue`: Modul repositori E-Arsip, multi-filtering, PDF viewer modal, & PDF merge download.
