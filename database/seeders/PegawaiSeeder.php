<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pegawai;
use App\Models\BerkasPegawai;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pegawai: dr. Budi Santoso, Sp.PD (Dokter)
        $budi = Pegawai::create([
            'nik' => '10928371',
            'nama_lengkap' => 'dr. Budi Santoso, Sp.PD',
            'kategori_peran' => 'Dokter',
            'unit_departemen' => 'Poliklinik Penyakit Dalam',
            'email_resmi' => 'budi.santoso@bundathamrin.com',
            'no_hp' => '0812-3456-7890',
            'pendidikan_terakhir' => 'Spesialis Penyakit Dalam - FK USU',
            'no_str' => 'STR-DOKTER-112233',
            'no_sip' => 'SIP-DOKTER-445566',
            'tanggal_upload' => '2026-08-01',
        ]);

        // Berkas dr. Budi
        for ($i = 1; $i <= 4; $i++) {
            BerkasPegawai::create([
                'pegawai_id' => $budi->id,
                'jenis_berkas' => $i == 1 ? 'Ijazah Profesi / Gelar' : ($i == 2 ? 'STR (Surat Tanda Registrasi)' : 'SIP (Surat Izin Praktik)'),
                'judul_dokumen' => 'Dokumen Resmi Budi Santoso #' . $i,
                'nama_file' => 'Dokumen_Budi_' . $i . '.pdf',
                'file_path' => 'berkas_pegawai/sample.pdf',
                'file_size' => '1.5 MB',
                'catatan_hrd' => 'Terverifikasi Asli',
                'tanggal_upload' => '2026-08-01',
            ]);
        }

        // 2. Pegawai: dr. Siti Rahma, Sp.A (Dokter)
        $siti = Pegawai::create([
            'nik' => '10928372',
            'nama_lengkap' => 'dr. Siti Rahma, Sp.A',
            'kategori_peran' => 'Dokter',
            'unit_departemen' => 'Poliklinik Anak',
            'email_resmi' => 'siti.rahma@bundathamrin.com',
            'no_hp' => '0813-9876-5432',
            'pendidikan_terakhir' => 'Spesialis Anak - FK UI',
            'no_str' => 'STR-DOKTER-778899',
            'no_sip' => 'SIP-DOKTER-990011',
            'tanggal_upload' => '2026-08-01',
        ]);

        for ($i = 1; $i <= 3; $i++) {
            BerkasPegawai::create([
                'pegawai_id' => $siti->id,
                'jenis_berkas' => 'Ijazah Profesi / Gelar',
                'judul_dokumen' => 'Dokumen Siti Rahma #' . $i,
                'nama_file' => 'Dokumen_Siti_' . $i . '.pdf',
                'file_path' => 'berkas_pegawai/sample.pdf',
                'file_size' => '1.2 MB',
                'catatan_hrd' => 'Valid',
                'tanggal_upload' => '2026-08-01',
            ]);
        }

        // 3. Pegawai: Ns. Ratna Dewi, S.Kep (Perawat) - Sesuai Gambar 2
        $ratna = Pegawai::create([
            'nik' => '20811001',
            'nama_lengkap' => 'Ns. Ratna Dewi, S.Kep',
            'kategori_peran' => 'Perawat',
            'unit_departemen' => 'IGD (Instalasi Gawat Darurat)',
            'email_resmi' => 'ratna.dewi@bundathamrin.com',
            'no_hp' => '0852-1122-3344',
            'pendidikan_terakhir' => 'S1 Keperawatan & Ners - Universitas Sumatera Utara',
            'no_str' => 'STR-PERAWAT-99182301',
            'no_sip' => 'SIP-PERAWAT-311/2024',
            'tanggal_upload' => '2026-08-02',
        ]);

        $berkasRatna = [
            [
                'jenis_berkas' => 'Ijazah Profesi / Gelar',
                'judul_dokumen' => 'Ijazah Sarjana Keperawatan & Profesi Ners',
                'nama_file' => 'Ijazah_Ners_Ratna_Dewi.pdf',
                'file_size' => '1.9 MB',
            ],
            [
                'jenis_berkas' => 'Transkrip Nilai Akademik',
                'judul_dokumen' => 'Transkrip Nilai Ners Keperawatan',
                'nama_file' => 'Transkrip_Ners_Ratna_Dewi.pdf',
                'file_size' => '1.3 MB',
            ],
            [
                'jenis_berkas' => 'STR (Surat Tanda Registrasi)',
                'judul_dokumen' => 'STR Perawat Kompetensi Gawat Darurat',
                'nama_file' => 'STR_Perawat_Ratna_Dewi.pdf',
                'file_size' => '1.0 MB',
            ],
            [
                'jenis_berkas' => 'Sertifikat Pelatihan / Diklat',
                'judul_dokumen' => 'Sertifikat Pelatihan BCLS & Emergency Nursing',
                'nama_file' => 'Sertifikat_BCLS_IGD_Ratna.pdf',
                'file_size' => '1.7 MB',
            ],
        ];

        foreach ($berkasRatna as $b) {
            BerkasPegawai::create([
                'pegawai_id' => $ratna->id,
                'jenis_berkas' => $b['jenis_berkas'],
                'judul_dokumen' => $b['judul_dokumen'],
                'nama_file' => $b['nama_file'],
                'file_path' => 'berkas_pegawai/' . $b['nama_file'],
                'file_size' => $b['file_size'],
                'catatan_hrd' => 'Terverifikasi Asli HRD',
                'tanggal_upload' => '2026-08-02',
            ]);
        }

        // 4. Pegawai: Sari Wulandari, S.Farm, Apt (Penunjang Medis)
        $sari = Pegawai::create([
            'nik' => '30712001',
            'nama_lengkap' => 'Sari Wulandari, S.Farm, Apt',
            'kategori_peran' => 'Penunjang Medis',
            'unit_departemen' => 'Instalasi Farmasi',
            'email_resmi' => 'sari.wulandari@bundathamrin.com',
            'no_hp' => '0821-4433-2211',
            'pendidikan_terakhir' => 'Profesi Apoteker - ITB',
            'no_str' => 'STRA-88776655',
            'no_sip' => 'SIPA-11223344',
            'tanggal_upload' => '2026-07-15',
        ]);

        for ($i = 1; $i <= 3; $i++) {
            BerkasPegawai::create([
                'pegawai_id' => $sari->id,
                'jenis_berkas' => 'Ijazah Profesi / Gelar',
                'judul_dokumen' => 'Dokumen Farmasi #' . $i,
                'nama_file' => 'Dokumen_Farmasi_' . $i . '.pdf',
                'file_path' => 'berkas_pegawai/sample.pdf',
                'file_size' => '2.0 MB',
                'catatan_hrd' => 'Terverifikasi',
                'tanggal_upload' => '2026-07-15',
            ]);
        }

        // 5. Pegawai: Dian Sastro, S.E. (Staf Administrasi)
        $dian = Pegawai::create([
            'nik' => '40113001',
            'nama_lengkap' => 'Dian Sastro, S.E.',
            'kategori_peran' => 'Staf Administrasi',
            'unit_departemen' => 'Diklat & Pengembangan SDM',
            'email_resmi' => 'dian.sastro@bundathamrin.com',
            'no_hp' => '0811-9988-7766',
            'pendidikan_terakhir' => 'S1 Ekonomi - UI',
            'no_str' => null,
            'no_sip' => null,
            'tanggal_upload' => '2026-08-01',
        ]);

        for ($i = 1; $i <= 2; $i++) {
            BerkasPegawai::create([
                'pegawai_id' => $dian->id,
                'jenis_berkas' => 'Ijazah Profesi / Gelar',
                'judul_dokumen' => 'Dokumen Administrasi #' . $i,
                'nama_file' => 'Dokumen_Admin_' . $i . '.pdf',
                'file_path' => 'berkas_pegawai/sample.pdf',
                'file_size' => '1.1 MB',
                'catatan_hrd' => 'Terverifikasi',
                'tanggal_upload' => '2026-08-01',
            ]);
        }
    }
}