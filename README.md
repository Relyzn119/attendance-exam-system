> **Sistem Manajemen Diklat, Absensi Ujian Pegawai Kontrak (Satpam, Cleaning Service, Dapur Gizi), serta Repositori Berkas HRD (Ijazah, Transkrip Nilai, STR, SIP) Pegawai Medis berbasis Web.**
---


### **Portal Diklat & Repositori Pegawai Rumah Sakit**

**Portal Diklat & Repositori Pegawai Rumah Sakit** adalah aplikasi web manajemen rumah sakit terpadu yang dirancang khusus untuk memisahkan fokus antara **Pelaksanaan Diklat & Ujian Pegawai Kontrak/Non-Resmi** dengan **Penyimpanan Berkas Digital Pegawai Medis Resmi**.

Aplikasi ini hadir untuk memenuhi kebutuhan efisiensi operasional rumah sakit dengan memberikan solusi otomatisasi token absensi ujian, evaluasi kompetensi diklat, pencetakan sertifikat otomatis, hingga manajemen arsip dokumen HRD yang aman dan terverifikasi.

---

## 🚀 Fitur Utama Aplikasi

### 1. 📋 **Absensi & Ujian Diklat (Khusus Pegawai Kontrak & Non-Resmi)**

- Ditujukan khusus untuk tenaga operasional non-pegawai resmi, seperti **Satpam / Security, Cleaning Service, Asisten Koki Dapur Gizi, Driver Operasional, dan Petugas Laundry**.
    
- **Verifikasi Kode Akses Unik (Token)** untuk memastikan keabsahan peserta sebelum memulai ujian evaluasi diklat.
    
- Interface ujian interaktif dengan penghitung waktu (timer), navigasi nomor soal, serta proteksi penyelesaian ujian.
    

### 2. 📊 **Riwayat Hasil Ujian & Cetak Sertifikat Digital**

- Pemantauan real-time nilai ujian, tingkat kelulusan, dan status (LULUS / REMEDIAL).
    
- **Detail Per Soal & Durasi**: Menganalisis waktu pengerjaan per soal serta pembahasan jawaban secara rinci.
    
- **Generasi Sertifikat Resmi PDF**: Mencetak sertifikat kelulusan diklat yang dilengkapi nomor registrasi resmi RSU Bunda Thamrin Medan.
    
- **Bank Soal Terintegrasi**: Pengelolaan dan penambahan bank soal diklat (K3RS, PPI, Sanitasi Gizi, Code Red, dll).
    

### 3. 🗄️ **Database Repositori Pegawai & Dokter Resmi**

- Tempat penyimpanan arsip data terpusat untuk **Dokter Spesialis/Umum, Perawat/Ners, Penunjang Medis (Farmasi/Lab), dan Staf Administrasi/HRD**.
    
- **Pencarian Berdasarkan Tanggal Upload HRD**: Memudahkan filter dan audit data pegawai berdasarkan waktu pengunggahan data.
    
- **Detail Dokumen Resmi (PDF Viewer Interaktif)**: Fitur untuk melihat dan mengunduh berkas fisik resmi seperti **Ijazah Profesi, Transkrip Nilai Akademik, Surat Tanda Registrasi (STR), dan Surat Izin Praktik (SIP)**.
    
- **Upload Berkas Baru oleh HRD**: Memungkinkan HRD melampirkan berkas PDF baru secara langsung ke profil pegawai yang bersangkutan.
    

### 4. 👷 **Manajemen Pegawai Kontrak & Generate Kode Ujian**

- Pengelolaan master data personel non-pegawai resmi beserta vendor outsourcing pendukung.
    
- Fitur **Generate Kode Ujian Otomatis** dengan token khusus untuk diberikan kepada personel kontrak sebelum mengikuti diklat.
    

---

## 🛠️ Teknologi yang Digunakan (Tech Stack)

- **Frontend Framework**: React 18 (TypeScript)
    
- **Build Tool & Server**: Vite & Express (Full-Stack Support)
    
- **Styling**: Tailwind CSS
    
- **Iconography**: Lucide React
    
- **Document Management**: Custom Interactive PDF & Certificate Renderer
    

---

## 📂 Struktur Utama Aplikasi

codeText

```
├── src/
│   ├── components/
│   │   ├── Menu1AbsensiUjian/     # Akses Kode Token, Pengerjaan Ujian, Sertifikat
│   │   ├── Menu2RiwayatUjian/     # Monitoring Nilai, Analytics, Bank Soal
│   │   ├── Menu3DataPegawai/      # Database Pegawai Medis & Viewer Dokumen PDF (Ijazah/STR)
│   │   ├── ContractStaffTable/    # Data Pegawai Kontrak & Generate Kode
│   │   └── HeaderNavbar.tsx       # Header Navigasi & Statistik
│   ├── data/
│   │   └── initialData.ts         # Master Data Initial (Pegawai, Kontrak, Soal, Hasil Ujian)
│   ├── types.ts                   # Definisi Interface TypeScript
│   └── App.tsx                    # Entry Point Utama Aplikasi
```

---

## 💡 Alur Penggunaan Sistem (Workflow Summary)

1. **Pengelolaan Pegawai Kontrak**: HRD mendaftarkan data Satpam/CS/Koki Gizi dan menekan tombol **"Generate Kode Ujian"**.
    
2. **Pelaksanaan Ujian**: Pegawai kontrak memasukkan Kode Token di **Menu 1 (Absensi & Ujian)** untuk mengerjakan evaluasi diklat.
    
3. **Evaluasi & Sertifikat**: Setelah selesai, nilai dihitung otomatis. Jika LULUS, sertifikat resmi dapat langsung dicetak di **Menu 2**.
    
4. **Arsip Berkas Pegawai Resmi**: HRD mengelola dokumen digital Dokter dan Perawat (Transkrip, Ijazah, STR, SIP) di **Menu 3** melalui tombol **"Lihat Detail"**.
