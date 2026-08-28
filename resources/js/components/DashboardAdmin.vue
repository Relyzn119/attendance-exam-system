<template>
    <div class="w-full text-slate-100 font-sans pb-12 space-y-8">

        <!-- 1. HEADER ADMIN DASHBOARD (GLASSMORPHISM SHOWCASE) -->
        <div
            class="bg-slate-900/60 border border-white/15 rounded-3xl p-6 sm:p-8 backdrop-blur-xl shadow-2xl relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                <div class="space-y-1">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 bg-blue-500/10 border border-blue-400/30 rounded-full text-xs text-blue-300 font-semibold mb-2">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        <span>Pusat Kendali Ujian & Diklat RSU Bunda Thamrin</span>
                    </div>
                    <h1
                        class="text-2xl sm:text-4xl font-black text-white tracking-tight uppercase flex items-center gap-3">
                        <i class="bi bi-speedometer2 text-blue-400"></i>
                        <span>Dashboard Admin Diklat</span>
                    </h1>
                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl">
                        Kelola Absensi Peserta, Token Akses Ujian Digital, & Penataan Bank Soal Terintegrasi.
                    </p>
                </div>

                <div class="self-start sm:self-auto shrink-0 flex items-center gap-2">
                    <button @click="openModalChangePasswordAdmin"
                        class="inline-flex items-center gap-1.5 bg-slate-800 hover:bg-slate-700 text-amber-300 border border-amber-500/40 text-xs font-bold px-4 py-2 rounded-full shadow-lg backdrop-blur-md transition-all active:scale-95">
                        <i class="bi bi-key-fill"></i> Ganti Password Admin
                    </button>
                    <span
                        class="inline-flex items-center gap-1.5 bg-rose-500/20 text-rose-300 border border-rose-500/40 text-xs font-bold px-4 py-2 rounded-full shadow-lg backdrop-blur-md">
                        <i class="bi bi-shield-lock-fill"></i> Role: Admin Authorized
                    </span>
                </div>
            </div>
        </div>

        <!-- CARD PENGATURAN DATA SERTIFIKAT (NAMA DIREKTUR, PEMBICARA, & TTD) -->
        <div class="bg-slate-900/60 border border-white/15 rounded-3xl p-6 backdrop-blur-xl shadow-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-amber-500/10 border border-amber-400/30 rounded-full text-xs text-amber-300 font-semibold">
                    <i class="bi bi-award-fill text-amber-400"></i>
                    <span>Konfigurasi Cetak Sertifikat Ujian</span>
                </div>
                <h3 class="text-lg font-extrabold text-white flex items-center gap-2 pt-1">
                    <span>Pengaturan Nama Direktur, Pembicara, & Tanda Tangan</span>
                </h3>
                <p class="text-xs text-slate-300 max-w-2xl">
                    Atur nama penandatangan sertifikat (Direktur & Pembicara) serta pilih opsi tanda tangan digital (gambar TTD) atau tanda tangan basah.
                </p>
                <div class="pt-1 flex items-center gap-4 text-xs text-slate-400 flex-wrap">
                    <span><strong class="text-slate-200">Direktur:</strong> {{ sertifikatForm.nama_direktur || 'dr. Iskandar Candra, M.Kes, FISQua, KMK, CHQP' }}</span>
                    <span>•</span>
                    <span><strong class="text-slate-200">Pembicara:</strong> {{ sertifikatForm.nama_pembicara || 'JUPENTIUS SITUMORANG' }}</span>
                    <span>•</span>
                    <span><strong class="text-slate-200">Tipe TTD:</strong> 
                        <span :class="sertifikatForm.tipe_ttd === 'digital' ? 'text-emerald-400 font-bold' : 'text-amber-400 font-bold'">
                            {{ sertifikatForm.tipe_ttd === 'digital' ? 'Digital (Gambar TTD)' : 'Basah (Cetak Tanpa TTD)' }}
                        </span>
                    </span>
                    <span>•</span>
                    <span><strong class="text-slate-200">Background:</strong> 
                        <span :class="sertifikatForm.use_bg_watermark ? 'text-blue-400 font-bold' : 'text-emerald-400 font-bold'">
                            {{ sertifikatForm.use_bg_watermark ? 'Watermark Gambar' : 'Polos Putih' }}
                        </span>
                    </span>
                </div>
            </div>

            <button @click="openModalSertifikat"
                class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs px-5 py-3 rounded-2xl shadow-lg transition-all active:scale-95 shrink-0 uppercase tracking-wider">
                <i class="bi bi-pencil-square text-base"></i>
                <span>Ubah Data Sertifikat</span>
            </button>
        </div>

        <!-- 2. TABEL DATA PESERTA UJIAN & ABSENSI (GLASS CARD) -->
        <div class="bg-slate-900/60 border border-white/15 rounded-3xl overflow-hidden backdrop-blur-xl shadow-2xl">

            <!-- Table Header Bar -->
            <div
                class="p-6 border-b border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-950/40">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="bi bi-people-fill text-blue-400"></i>
                        <span>Daftar Peserta Ujian & Absensi</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar Karyawan/Peserta Registered dalam Sistem Diklat</p>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                    <span
                        class="bg-blue-500/10 border border-blue-500/30 text-blue-300 text-xs font-bold px-3 py-1.5 rounded-full">
                        Total: {{ pagination.total }} Peserta
                    </span>
                    <div class="flex items-center gap-2 bg-slate-950/70 p-1.5 px-3 rounded-full border border-white/15">
                        <i class="bi bi-calendar-date text-amber-400 text-xs"></i>
                        <input v-model="tanggalAbsensiPdf" type="date"
                            class="bg-transparent text-xs text-white focus:outline-none [color-scheme:dark]" />
                    </div>
                    <a :href="'/api/admin/export-absensi?tanggal=' + tanggalAbsensiPdf" target="_blank"
                        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-4 py-2 rounded-full shadow-lg transition-all active:scale-95">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                        <span>Export PDF Absensi</span>
                    </a>
                </div>
            </div>

            <!-- Table Body -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="border-b border-white/10 bg-slate-950/60 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6 text-center w-12">No</th>
                            <th class="py-4 px-6">Nama Peserta / NIK</th>
                            <th class="py-4 px-6 text-center">Gender</th>
                            <th class="py-4 px-6">No. HP / WA</th>
                            <th class="py-4 px-6">Email</th>
                            <th class="py-4 px-6 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm">
                        <tr v-for="(p, index) in pesertaList" :key="p.id"
                            class="hover:bg-white/5 transition-colors group">
                            <td class="py-4 px-6 text-center font-mono text-slate-400 text-xs">
                                {{ (pagination.current_page - 1) * 10 + index + 1 }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-white group-hover:text-blue-300 transition-colors">{{ p.nama
                                }}</div>
                                <div class="text-xs text-slate-400 font-mono">NIK: {{ p.nik }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span v-if="p.jenis_kelamin === 'L'"
                                    class="px-3 py-1 bg-sky-500/20 text-sky-300 border border-sky-500/30 rounded-full text-xs font-semibold">
                                    Laki-Laki
                                </span>
                                <span v-else
                                    class="px-3 py-1 bg-pink-500/20 text-pink-300 border border-pink-500/30 rounded-full text-xs font-semibold">
                                    Perempuan
                                </span>
                            </td>
                            <td class="py-4 px-6 font-mono text-slate-300 text-xs">{{ p.no_hp }}</td>
                            <td class="py-4 px-6 text-slate-300 text-xs">{{ p.email }}</td>
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-center gap-1.5 flex-wrap justify-center">
                                    <!-- BUTTON 1: DETAIL DATA & BERKAS -->
                                    <button @click="openModalDetail(p)"
                                        class="p-2 bg-blue-600/30 hover:bg-blue-600 text-blue-200 hover:text-white rounded-xl transition-all border border-blue-500/30"
                                        title="Lihat Detail Berkas & Data">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>

                                    <!-- BUTTON 2: EDIT DATA PESERTA -->
                                    <button @click="openModalEdit(p)"
                                        class="p-2 bg-amber-500/30 hover:bg-amber-500 text-amber-200 hover:text-white rounded-xl transition-all border border-amber-500/30"
                                        title="Edit Data Peserta">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- BUTTON 3: GENERATE TOKEN -->
                                    <button @click="openModalToken(p)"
                                        class="p-2 bg-sky-500/30 hover:bg-sky-500 text-sky-200 hover:text-white rounded-xl transition-all border border-sky-500/30"
                                        title="Generate Token Ujian">
                                        <i class="bi bi-key-fill"></i>
                                    </button>

                                    <!-- BUTTON 4: REVIEW SOAL & JAWABAN PESERTA -->
                                    <button v-if="hasRiwayatUjian(p)" @click="openModalReview(p)"
                                        class="p-2 bg-purple-600/30 hover:bg-purple-600 text-purple-200 hover:text-white rounded-xl transition-all border border-purple-500/30"
                                        title="Lihat Riwayat & Review Jawaban Ujian">
                                        <i class="bi bi-journal-check"></i>
                                    </button>

                                    <!-- BUTTON 5: CETAK SERTIFIKAT SALINAN ADMIN -->
                                    <button v-if="hasRiwayatUjian(p)" @click="cetakSertifikat(p)"
                                        class="p-2 bg-emerald-600/30 hover:bg-emerald-600 text-emerald-200 hover:text-white rounded-xl transition-all border border-emerald-500/30"
                                        title="Cetak Sertifikat Peserta (Salinan Admin)">
                                        <i class="bi bi-patch-check-fill"></i>
                                    </button>

                                    <!-- BUTTON 6: BUKA KEMBALI UJIAN (RESET UJIAN) -->
                                    <button v-if="hasRiwayatUjian(p)" @click="confirmResetUjian(p)"
                                        class="p-2 bg-amber-500/30 hover:bg-amber-500 text-amber-200 hover:text-white rounded-xl transition-all border border-amber-500/30"
                                        title="Buka Kembali Ujian (Peserta Dapat Ujian Ulang)">
                                        <i class="bi bi-arrow-counterclockwise text-base"></i>
                                    </button>

                                    <!-- BUTTON 7: DELETE PESERTA -->
                                    <button @click="confirmDeletePeserta(p)"
                                        class="p-2 bg-rose-500/20 hover:bg-rose-500/40 text-rose-300 hover:text-white rounded-xl transition-all border border-rose-500/30"
                                        title="Hapus Data Peserta">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="pesertaList.length === 0">
                            <td colspan="6" class="text-center py-12 text-slate-400">
                                <i class="bi bi-inbox text-4xl mb-2 block"></i>
                                <span>Belum ada peserta yang mendaftar.</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Table Pagination -->
            <div v-if="pagination.last_page > 1"
                class="p-4 border-t border-white/10 bg-slate-950/40 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <div>
                    Menampilkan {{ pagination.from }} - {{ pagination.to }} dari {{ pagination.total }} peserta
                </div>
                <div class="flex items-center gap-1">
                    <button @click="fetchPeserta(pagination.current_page - 1)" :disabled="pagination.current_page === 1"
                        class="px-3 py-1.5 rounded-lg border border-white/10 bg-slate-900 text-slate-300 hover:bg-white/10 disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                        Previous
                    </button>
                    <button v-for="page in pagination.last_page" :key="page" @click="fetchPeserta(page)"
                        :class="pagination.current_page === page ? 'bg-blue-600 text-white font-bold border-blue-400' : 'bg-slate-900 text-slate-300 hover:bg-white/10 border-white/10'"
                        class="px-3 py-1.5 rounded-lg border transition-all">
                        {{ page }}
                    </button>
                    <button @click="fetchPeserta(pagination.current_page + 1)"
                        :disabled="pagination.current_page === pagination.last_page"
                        class="px-3 py-1.5 rounded-lg border border-white/10 bg-slate-900 text-slate-300 hover:bg-white/10 disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                        Next
                    </button>
                </div>
            </div>

        </div>

        <!-- 3. BAGIAN BANK SOAL & SET 25 SOAL UJIAN (GLASS CARD) -->
        <div class="bg-slate-900/60 border border-white/15 rounded-3xl overflow-hidden backdrop-blur-xl shadow-2xl">

            <!-- Bank Soal Header Bar -->
            <div
                class="p-6 border-b border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-950/40">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="bi bi-journal-check text-emerald-400"></i>
                        <span>Bank Soal & Pengaturan Soal Ujian</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Atur & Centang Soal yang akan Dikeluarkan saat Ujian</p>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <button @click="showModalAddSoal = true"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-4 py-2.5 rounded-full shadow-lg transition-all active:scale-95">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>Tambah Soal Baru</span>
                    </button>
                    <button @click="saveSelectedSoal"
                        class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-full shadow-lg transition-all active:scale-95">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Simpan Set Soal Manual (Model 1)</span>
                    </button>
                    <button @click="openModalAcakSoal"
                        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white font-bold text-xs px-4 py-2.5 rounded-full shadow-lg transition-all active:scale-95">
                        <i class="bi bi-shuffle text-base"></i>
                        <span>Acak Soal (Model 2)</span>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-4">

                <!-- COUNTER SOAL TERPILIH ALERT -->
                <div
                    class="bg-blue-950/50 border border-blue-500/30 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-3 backdrop-blur-md">
                    <div class="text-xs text-blue-200 flex items-center gap-2">
                        <i class="bi bi-info-circle-fill text-blue-400 text-base"></i>
                        <span>
                            <strong>Model Ujian Aktif:</strong> 
                            <span v-if="ujianSetting.model_ujian === 'acak'" class="text-purple-300 font-bold">
                                Model 2 (Acak Soal - {{ ujianSetting.tipe_acak === 'per_peserta' ? 'Soal Berbeda Per Peserta' : 'Soal Sama Untuk Semua' }}, Kesulitan: {{ uppercaseFirst(ujianSetting.tingkat_kesulitan) }}, Target: {{ ujianSetting.jumlah_soal }} Soal)
                            </span>
                            <span v-else class="text-amber-300 font-bold">
                                Model 1 (Pemilihan Manual - {{ selectedSoalIds.length }} Soal Terpilih)
                            </span>
                        </span>
                    </div>
                    <span
                        class="bg-blue-600 text-white font-bold text-xs px-3.5 py-1.5 rounded-full shadow-md shrink-0">
                        Total Bank Soal: {{ bankSoalList.length }} Soal
                    </span>
                </div>

                <!-- TABLE BANK SOAL -->
                <div class="overflow-x-auto rounded-2xl border border-white/10">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-white/10 bg-slate-950/60 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="py-4 px-4 text-center w-12">Pilih</th>
                                <th class="py-4 px-4 text-center w-12">No</th>
                                <th class="py-4 px-6">Pertanyaan / Soal</th>
                                <th class="py-4 px-6">Opsi Jawaban</th>
                                <th class="py-4 px-4 text-center w-28">Kesulitan</th>
                                <th class="py-4 px-4 text-center w-20">Kunci</th>
                                <th class="py-4 px-4 text-center w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-sm">
                            <tr v-for="(soal, index) in paginatedBankSoal" :key="soal.id"
                                :class="selectedSoalIds.includes(soal.id) ? 'bg-amber-500/10 border-l-4 border-l-amber-400' : 'hover:bg-white/5'"
                                class="transition-colors">
                                <td class="py-4 px-4 text-center">
                                    <input type="checkbox" :value="soal.id" v-model="selectedSoalIds"
                                        class="w-4 h-4 rounded bg-slate-950 border-white/20 text-amber-500 focus:ring-amber-500 focus:ring-offset-slate-900 cursor-pointer" />
                                </td>
                                <td class="py-4 px-4 text-center font-mono text-slate-400 text-xs">
                                    {{ (bankSoalCurrentPage - 1) * bankSoalPerPage + index + 1 }}
                                </td>
                                <td class="py-4 px-6 font-medium text-slate-100 leading-relaxed">
                                    {{ soal.soal }}
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-300 space-y-1">
                                    <div><strong class="text-blue-400">A.</strong> {{ soal.opsi_a }}</div>
                                    <div><strong class="text-blue-400">B.</strong> {{ soal.opsi_b }}</div>
                                    <div><strong class="text-blue-400">C.</strong> {{ soal.opsi_c }}</div>
                                    <div><strong class="text-blue-400">D.</strong> {{ soal.opsi_d }}</div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span v-if="soal.tingkat_kesulitan === 'mudah'" class="px-2 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-bold text-[11px] rounded-md">Mudah</span>
                                    <span v-else-if="soal.tingkat_kesulitan === 'normal'" class="px-2 py-0.5 bg-blue-500/20 text-blue-300 border border-blue-500/30 font-bold text-[11px] rounded-md">Normal</span>
                                    <span v-else-if="soal.tingkat_kesulitan === 'sulit'" class="px-2 py-0.5 bg-rose-500/20 text-rose-300 border border-rose-500/30 font-bold text-[11px] rounded-md">Sulit</span>
                                    <span v-else class="px-2 py-0.5 bg-slate-800 text-slate-400 font-medium text-[11px] rounded-md">Tidak Ada</span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span
                                        class="px-2.5 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-mono font-bold text-xs rounded-lg">
                                        {{ soal.kunci_jawaban }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <button @click="deleteSoal(soal.id)"
                                        class="p-2 bg-rose-500/20 hover:bg-rose-500/40 text-rose-300 hover:text-white rounded-xl transition-all"
                                        title="Hapus Soal">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="bankSoalList.length === 0">
                                <td colspan="6" class="text-center py-12 text-slate-400">
                                    <i class="bi bi-journal-x text-4xl mb-2 block"></i>
                                    <span>Bank Soal masih kosong. Silakan tambah soal baru.</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION BANK SOAL (10 SOAL PER HALAMAN) -->
                <div v-if="totalBankSoalPages > 1"
                    class="p-4 border border-white/10 rounded-2xl bg-slate-950/40 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 mt-4">
                    <div>
                        Menampilkan {{ (bankSoalCurrentPage - 1) * bankSoalPerPage + 1 }} - {{
                            Math.min(bankSoalCurrentPage * bankSoalPerPage, bankSoalList.length) }} dari {{
                            bankSoalList.length }} soal
                    </div>
                    <div class="flex items-center gap-1">
                        <button @click="bankSoalCurrentPage--" :disabled="bankSoalCurrentPage === 1"
                            class="px-3 py-1.5 rounded-lg border border-white/10 bg-slate-900 text-slate-300 hover:bg-white/10 disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                            &lt;&lt; Prev
                        </button>
                        <button v-for="page in totalBankSoalPages" :key="page" @click="bankSoalCurrentPage = page"
                            :class="bankSoalCurrentPage === page ? 'bg-emerald-600 text-white font-bold border-emerald-400' : 'bg-slate-900 text-slate-300 hover:bg-white/10 border-white/10'"
                            class="px-3 py-1.5 rounded-lg border transition-all">
                            {{ page }}
                        </button>
                        <button @click="bankSoalCurrentPage++" :disabled="bankSoalCurrentPage === totalBankSoalPages"
                            class="px-3 py-1.5 rounded-lg border border-white/10 bg-slate-900 text-slate-300 hover:bg-white/10 disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                            Next &gt;&gt;
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- MODAL 1: DETAIL PESERTA -->
        <div v-if="selectedPesertaDetail"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md overflow-y-auto">
            <div
                class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden text-slate-100">

                <div class="bg-slate-950/80 p-5 flex items-center justify-between border-b border-white/10">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="bi bi-person-lines-fill text-blue-400"></i>
                        <span>Detail Registrasi: {{ selectedPesertaDetail.nama }}</span>
                    </h3>
                    <button @click="selectedPesertaDetail = null" class="text-slate-400 hover:text-white">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    <div>
                        <h4 class="text-xs font-bold text-blue-400 uppercase tracking-wider mb-3">1. Data Diri Lengkap
                        </h4>
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs bg-slate-950/60 p-4 rounded-2xl border border-white/10">
                            <div><strong class="text-slate-400">Nama:</strong> <span class="text-white font-semibold">{{
                                selectedPesertaDetail.nama }}</span></div>
                            <div><strong class="text-slate-400">NIK:</strong> <span class="text-white font-mono">{{
                                selectedPesertaDetail.nik }}</span></div>
                            <div><strong class="text-slate-400">Email:</strong> <span class="text-white">{{
                                selectedPesertaDetail.email }}</span></div>
                            <div><strong class="text-slate-400">No HP:</strong> <span class="text-white font-mono">{{
                                selectedPesertaDetail.no_hp }}</span></div>
                            <div class="sm:col-span-2"><strong class="text-slate-400">Alamat:</strong> <span
                                    class="text-white">{{ selectedPesertaDetail.alamat }}</span></div>
                        </div>
                    </div>

                    <div>
                        <!-- HEADER BERKAS + TOMBOL DOWNLOAD ALL (.ZIP) -->
                        <!-- DI BAGIAN SECTION 2 BERKAS TERUNGGAH -->
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-bold text-blue-400 uppercase tracking-wider">2. Berkas Terunggah
                            </h4>

                            <button @click="downloadZip(selectedPesertaDetail.id, selectedPesertaDetail.nama)"
                                :disabled="isDownloadingZip || !selectedPesertaDetail.berkas || selectedPesertaDetail.berkas.length === 0"
                                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 disabled:bg-slate-800 text-white font-bold text-xs px-4 py-2 rounded-full shadow-lg transition-all active:scale-95">
                                <span v-if="isDownloadingZip"
                                    class="animate-spin rounded-full h-3.5 w-3.5 border-2 border-white border-t-transparent"></span>
                                <i v-else class="bi bi-file-earmark-zip-fill text-sm"></i>
                                <span>{{ isDownloadingZip ? 'Mengunduh ZIP...' : 'Download Semua (ZIP)' }}</span>
                            </button>
                        </div>


                        <div class="space-y-2">
                            <div v-for="b in selectedPesertaDetail.berkas" :key="b.id"
                                class="flex items-center justify-between p-3 bg-slate-950/60 border border-white/10 rounded-xl text-xs">
                                <span class="font-medium text-slate-200">{{ b.jenis_berkas }}</span>
                                <a :href="`/api/admin/berkas/${b.id}/preview`" target="_blank"
                                    class="px-3 py-1 bg-rose-500/20 hover:bg-rose-500/40 text-rose-300 font-bold rounded-lg transition-all">
                                    <i class="bi bi-file-earmark-pdf-fill mr-1"></i> Buka PDF
                                </a>
                            </div>

                            <div v-if="!selectedPesertaDetail.berkas || selectedPesertaDetail.berkas.length === 0"
                                class="text-center py-4 text-slate-400 text-xs bg-slate-950/30 rounded-xl">
                                Belum ada berkas PDF terunggah.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FOOTER MODAL DETAIL DENGAN TOMBOL DOWNLOAD ZIP -->
                <div class="p-4 bg-slate-950/80 border-t border-white/10 flex items-center justify-between">
                    <button v-if="selectedPesertaDetail.berkas && selectedPesertaDetail.berkas.length > 0"
                        @click="downloadZip(selectedPesertaDetail.id, selectedPesertaDetail.nama)"
                        :disabled="isDownloadingZip"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-400 disabled:bg-slate-800 text-slate-950 font-extrabold text-xs rounded-full shadow-lg transition-all active:scale-95 cursor-pointer">
                        <i class="bi bi-file-earmark-zip-fill"></i>
                        <span>{{ isDownloadingZip ? 'Mengunduh...' : 'Download All Berkas (.zip)' }}</span>
                    </button>
                    <span v-else></span>

                    <button @click="selectedPesertaDetail = null"
                        class="px-5 py-2 bg-white text-slate-950 font-bold text-xs rounded-full hover:bg-slate-200 transition-all">
                        Tutup
                    </button>
                </div>

            </div>
        </div>

        <!-- MODAL EDIT PESERTA -->
        <div v-if="selectedPesertaEdit"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md overflow-y-auto">
            <div
                class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-xl overflow-hidden text-slate-100">

                <div class="bg-slate-950/80 p-5 flex items-center justify-between border-b border-white/10">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="bi bi-pencil-square text-amber-400"></i>
                        <span>Edit Data Peserta: {{ selectedPesertaEdit.nama }}</span>
                    </h3>
                    <button @click="selectedPesertaEdit = null" class="text-slate-400 hover:text-white">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="submitEditPeserta" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Nama
                                Lengkap</label>
                            <input v-model="formEditPeserta.nama" type="text" required
                                class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">NIK</label>
                            <input v-model="formEditPeserta.nik" type="text"
                                class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Jenis
                                Kelamin</label>
                            <select v-model="formEditPeserta.jenis_kelamin"
                                class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option class="bg-slate-900" value="L">Laki-Laki</option>
                                <option class="bg-slate-900" value="P">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">No. HP /
                                WhatsApp</label>
                            <input v-model="formEditPeserta.no_hp" type="text"
                                class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Email
                            Resmi</label>
                        <input v-model="formEditPeserta.email" type="email" required
                            class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Alamat
                            Lengkap</label>
                        <textarea v-model="formEditPeserta.alamat" rows="2"
                            class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" @click="selectedPesertaEdit = null"
                            class="px-5 py-2.5 bg-slate-800 text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-700">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg transition-all active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- MODAL 2: GENERATE TOKEN -->
        <!-- MODAL 2: GENERATE TOKEN -->
        <div v-if="selectedPesertaToken"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
            <div
                class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden text-slate-100">

                <div class="bg-amber-950/60 p-5 flex items-center justify-between border-b border-amber-500/30">
                    <h3 class="text-base font-bold text-amber-300 flex items-center gap-2">
                        <i class="bi bi-key-fill"></i> Generate Token Ujian
                    </h3>
                    <button @click="selectedPesertaToken = null" class="text-amber-200 hover:text-white">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="p-6 text-center space-y-4">
                    <h4 class="font-bold text-white text-lg">{{ selectedPesertaToken.nama }}</h4>

                    <!-- Pilihan Tipe Token -->
                    <div class="text-left bg-slate-950/60 p-3.5 rounded-2xl border border-white/10 space-y-1.5">
                        <label class="block text-xs font-bold text-amber-400 uppercase tracking-wider">
                            Pilih Tipe / Peruntukan Token
                        </label>
                        <select v-model="selectedTipeToken"
                            class="w-full px-3.5 py-2 bg-slate-900 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="absensi">Absensi Saja (Tanpa Ujian & Sertifikat)</option>
                            <option value="ujian">Masuk ke Ujian (+ Otomatis Absensi)</option>
                        </select>
                    </div>

                    <!-- Pilihan Durasi Pengerjaan Ujian (Jika Tipe Ujian) -->
                    <div v-if="selectedTipeToken === 'ujian'" class="text-left bg-slate-950/60 p-3.5 rounded-2xl border border-white/10 space-y-1.5">
                        <label class="block text-xs font-bold text-amber-400 uppercase tracking-wider">
                            Pilih Durasi Waktu Ujian
                        </label>
                        <select v-model="selectedDurasiMenit"
                            class="w-full px-3.5 py-2 bg-slate-900 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option :value="15">15 Menit (Tes Singkat)</option>
                            <option :value="30">30 Menit</option>
                            <option :value="45">45 Menit</option>
                            <option :value="60">60 Menit (1 Jam - Standar)</option>
                            <option :value="90">90 Menit (1.5 Jam)</option>
                            <option :value="120">120 Menit (2 Jam)</option>
                        </select>
                    </div>

                    <div v-if="activeToken"
                        class="bg-slate-950/80 border border-amber-500/30 p-4 rounded-2xl space-y-3">
                        <div class="text-3xl sm:text-4xl font-mono font-black text-amber-400 tracking-widest">{{
                            activeToken }}</div>
                        <p class="text-xs text-amber-200/80">Durasi Diset: {{ selectedDurasiMenit }} Menit</p>
                        <button @click="copyToken"
                            class="px-4 py-1.5 bg-amber-500/20 hover:bg-amber-500/40 text-amber-200 text-xs font-bold rounded-full transition-all">
                            {{ isCopied ? 'Berhasil Disalin!' : 'Salin Kode Token' }}
                        </button>
                    </div>

                    <button @click="processGenerateToken"
                        class="w-full py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg transition-all active:scale-95">
                        Generate Token Baru
                    </button>
                </div>

            </div>
        </div>

        <!-- MODAL 3: TAMBAH SOAL BARU -->
        <div v-if="showModalAddSoal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md overflow-y-auto">
            <div
                class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden text-slate-100">

                <div class="bg-slate-950/80 p-5 flex items-center justify-between border-b border-white/10">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="bi bi-plus-circle text-blue-400"></i>
                        <span>Tambah Soal ke Bank Soal</span>
                    </h3>
                    <button @click="showModalAddSoal = false" class="text-slate-400 hover:text-white">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="submitSoalBaru" class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Pertanyaan
                            Ujian</label>
                        <textarea v-model="formSoal.soal" rows="3" required
                            class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Ketik soal pertanyaan..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Opsi
                                A</label>
                            <input v-model="formSoal.opsi_a" type="text" required
                                class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Opsi
                                B</label>
                            <input v-model="formSoal.opsi_b" type="text" required
                                class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Opsi
                                C</label>
                            <input v-model="formSoal.opsi_c" type="text" required
                                class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Opsi
                                D</label>
                            <input v-model="formSoal.opsi_d" type="text" required
                                class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Kunci Jawaban Benar</label>
                            <select v-model="formSoal.kunci_jawaban" required
                                class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option class="bg-slate-900" value="">-- Pilih Kunci Jawaban --</option>
                                <option class="bg-slate-900" value="A">A</option>
                                <option class="bg-slate-900" value="B">B</option>
                                <option class="bg-slate-900" value="C">C</option>
                                <option class="bg-slate-900" value="D">D</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Tingkat Kesulitan</label>
                            <select v-model="formSoal.tingkat_kesulitan" required
                                class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option class="bg-slate-900" value="tidak_ada">Tidak Ada (Default - Pemilihan Manual)</option>
                                <option class="bg-slate-900" value="mudah">Mudah</option>
                                <option class="bg-slate-900" value="normal">Normal</option>
                                <option class="bg-slate-900" value="sulit">Sulit</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full py-3 bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg transition-all active:scale-95">
                            Simpan Soal ke Bank Soal
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- MODAL 4: REVIEW HASIL UJIAN PESERTA -->
        <div v-if="selectedPesertaReview"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md overflow-y-auto">
            <div
                class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden text-slate-100 my-8">

                <div class="bg-slate-950/80 p-5 flex items-center justify-between border-b border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-purple-500/20 text-purple-400 rounded-2xl border border-purple-500/30">
                            <i class="bi bi-journal-text text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-white">
                                Review Hasil Ujian: {{ selectedPesertaReview.nama }}
                            </h3>
                            <p class="text-xs text-slate-400">Analisis Soal, Jawaban Peserta, Kunci Jawaban, & Skor
                                Ujian</p>
                        </div>
                    </div>
                    <button @click="selectedPesertaReview = null" class="text-slate-400 hover:text-white p-2">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

               <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
                    <!-- RINGKASAN SKOR -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-blue-950/40 border border-blue-500/30 p-4 rounded-2xl text-center space-y-1">
                            <span class="text-xs text-blue-300 font-semibold uppercase tracking-wider block">Skor / Nilai Akhir</span>
                            <span class="text-3xl font-black text-blue-400 font-mono">
                                {{ reviewData.nilai_akhir ?? reviewData.nilai ?? reviewData.skor ?? 0 }}
                            </span>
                        </div>
                        <div class="bg-emerald-950/40 border border-emerald-500/30 p-4 rounded-2xl text-center space-y-1">
                            <span class="text-xs text-emerald-300 font-semibold uppercase tracking-wider block">Jawaban Benar</span>
                            <span class="text-3xl font-black text-emerald-400 font-mono">
                                {{ reviewData.jawaban_benar ?? reviewData.total_benar ?? 0 }} Soal
                            </span>
                        </div>
                        <div class="bg-rose-950/40 border border-rose-500/30 p-4 rounded-2xl text-center space-y-1">
                            <span class="text-xs text-rose-300 font-semibold uppercase tracking-wider block">Jawaban Salah</span>
                            <span class="text-3xl font-black text-rose-400 font-mono">
                                {{ reviewData.jawaban_salah ?? reviewData.total_salah ?? 0 }} Soal
                            </span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center gap-2">
                            <i class="bi bi-list-check text-purple-400"></i>
                            <span>Lembar Jawaban Per Soal ({{ getDetailList(reviewData).length }} Soal)</span>
                        </h4>

                        <div v-for="(item, idx) in getDetailList(reviewData)" :key="item.id || idx"
                            class="p-4 rounded-2xl border bg-slate-950/50 space-y-3"
                            :class="isDetailBenar(item) ? 'border-emerald-500/30' : 'border-rose-500/30'">
                             <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-2.5 text-xs font-medium text-slate-200">
                                    <span
                                        class="px-2.5 py-1 bg-slate-800 rounded-lg text-white font-mono font-bold shrink-0">No.
                                        {{ idx + 1 }}</span>
                                    <span class="leading-relaxed">{{ item.soal ? item.soal.soal : (item.bank_soal ?
                                        item.bank_soal.soal : item.pertanyaan) }}</span>
                                </div>
                                                               <span
                                    v-if="isDetailBenar(item)"
                                    class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold rounded-full shrink-0 inline-flex items-center gap-1">
                                    <i class="bi bi-check-circle-fill"></i> Benar
                                </span>
                                <span v-else
                                    class="px-3 py-1 bg-rose-500/20 text-rose-300 border border-rose-500/30 text-xs font-bold rounded-full shrink-0 inline-flex items-center gap-1">
                                    <i class="bi bi-x-circle-fill"></i> Salah
                                </span>
                            </div>

                                                      <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs pt-1">
                                <div
                                    class="p-2.5 rounded-xl bg-slate-900 border border-white/10 flex items-center justify-between">
                                    <span class="text-slate-400">Jawaban Peserta:</span>
                                    <span class="font-bold font-mono px-2.5 py-0.5 rounded text-white"
                                        :class="isDetailBenar(item) ? 'bg-emerald-600' : 'bg-rose-600'">
                                        {{ item.jawaban_user || '-' }}
                                    </span>
                                </div>

                               <div
                                    class="p-2.5 rounded-xl bg-slate-900 border border-white/10 flex items-center justify-between">
                                    <span class="text-slate-400">Kunci Jawaban Benar:</span>
                                    <span class="font-bold font-mono px-2.5 py-0.5 rounded bg-blue-600 text-white">
                                        {{ item.soal ? item.soal.kunci_jawaban : (item.kunci_jawaban || '-') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-if="getDetailList(reviewData).length === 0"
                            class="text-center py-8 text-slate-400 text-xs">
                            <i class="bi bi-journal-x text-3xl mb-1 block"></i>
                            <span>Detail lembar jawaban tidak ditemukan.</span>
                        </div>
                    </div>

                </div>

                <div class="p-4 bg-slate-950/80 border-t border-white/10 text-right">
                    <button @click="selectedPesertaReview = null"
                        class="px-5 py-2 bg-white text-slate-950 font-bold text-xs rounded-full hover:bg-slate-200 transition-all">
                        Tutup Review
                    </button>
                </div>

            </div>
        </div>

        <!-- MODAL GANTI PASSWORD ADMIN -->
        <div v-if="showModalPasswordAdmin"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md overflow-y-auto">
            <div
                class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden text-slate-100 my-8">

                <div class="bg-slate-950/80 p-5 flex items-center justify-between border-b border-white/10">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="bi bi-key-fill text-amber-400"></i>
                        <span>Ganti Password Admin</span>
                    </h3>
                    <button @click="showModalPasswordAdmin = false" class="text-slate-400 hover:text-white">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="submitChangePasswordAdmin" class="p-6 space-y-4">
                    <div>
                        <label class="block font-bold text-xs text-slate-300 mb-1 uppercase tracking-wider">Password Lama</label>
                        <input v-model="formPasswordAdmin.current_password" type="password" required
                            placeholder="Masukkan password lama Anda"
                            class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" />
                    </div>

                    <div>
                        <label class="block font-bold text-xs text-slate-300 mb-1 uppercase tracking-wider">Password Baru</label>
                        <input v-model="formPasswordAdmin.new_password" type="password" required minlength="6"
                            placeholder="Minimal 6 karakter"
                            class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" />
                    </div>

                    <div>
                        <label class="block font-bold text-xs text-slate-300 mb-1 uppercase tracking-wider">Konfirmasi Password Baru</label>
                        <input v-model="formPasswordAdmin.new_password_confirmation" type="password" required minlength="6"
                            placeholder="Ulangi password baru"
                            class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-500" />
                    </div>

                    <div class="pt-3 flex items-center justify-end gap-2">
                        <button type="button" @click="showModalPasswordAdmin = false"
                            class="px-5 py-2.5 bg-slate-800 text-slate-300 font-bold text-xs rounded-full hover:bg-slate-700 transition-all">
                            Batal
                        </button>
                        <button type="submit" :disabled="isChangingPasswordAdmin"
                            class="px-6 py-2.5 bg-amber-500 hover:bg-amber-400 disabled:bg-slate-800 text-slate-950 font-extrabold text-xs uppercase tracking-wider rounded-full shadow-lg transition-all active:scale-95 flex items-center gap-2">
                            <span v-if="isChangingPasswordAdmin"
                                class="animate-spin rounded-full h-3.5 w-3.5 border-2 border-slate-950 border-t-transparent"></span>
                            <span>{{ isChangingPasswordAdmin ? 'Memproses...' : 'Simpan Password Baru' }}</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- MODAL UBAH DATA SERTIFIKAT -->
        <div v-if="showModalSertifikat"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md overflow-y-auto">
            <div class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden text-slate-100 my-8">

                <div class="bg-amber-950/60 p-5 flex items-center justify-between border-b border-amber-500/30">
                    <h3 class="text-base font-bold text-amber-300 flex items-center gap-2">
                        <i class="bi bi-award-fill"></i>
                        <span>Ubah Data Sertifikat</span>
                    </h3>
                    <button @click="showModalSertifikat = false" class="text-amber-200 hover:text-white">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="submitSertifikatSetting" class="p-6 space-y-4">

                    <!-- Field Ubah Nama Direktur -->
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">
                            Nama Direktur RSU Bunda Thamrin
                        </label>
                        <input v-model="sertifikatForm.nama_direktur" type="text"
                            placeholder="Contoh: dr. Iskandar Candra, M.Kes, FISQua, KMK, CHQP"
                            class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
                        <p class="text-[11px] text-slate-400 mt-1">Kosongkan jika ingin menggunakan nama default.</p>
                    </div>

                    <!-- Field Ubah Nama Pembicara -->
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">
                            Nama Pembicara Pelatihan
                        </label>
                        <input v-model="sertifikatForm.nama_pembicara" type="text"
                            placeholder="Contoh: JUPENTIUS SITUMORANG"
                            class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
                        <p class="text-[11px] text-slate-400 mt-1">Kosongkan jika ingin menggunakan nama default.</p>
                    </div>

                    <!-- Opsi Tipe Latar Belakang Sertifikat -->
                    <div class="bg-slate-950/60 p-4 rounded-2xl border border-white/10 space-y-2">
                        <label class="block text-xs font-bold text-amber-400 uppercase tracking-wider">
                            Pilih Latar Belakang Sertifikat
                        </label>
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <label :class="[
                                'flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all text-xs font-bold',
                                sertifikatForm.use_bg_watermark ? 'bg-amber-500/20 border-amber-500 text-amber-300' : 'bg-slate-900 border-white/10 text-slate-400 hover:text-white'
                            ]">
                                <input type="radio" v-model="sertifikatForm.use_bg_watermark" :value="true" class="hidden" />
                                <i class="bi bi-image text-base"></i>
                                <span>Background Watermark</span>
                            </label>

                            <label :class="[
                                'flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all text-xs font-bold',
                                !sertifikatForm.use_bg_watermark ? 'bg-amber-500/20 border-amber-500 text-amber-300' : 'bg-slate-900 border-white/10 text-slate-400 hover:text-white'
                            ]">
                                <input type="radio" v-model="sertifikatForm.use_bg_watermark" :value="false" class="hidden" />
                                <i class="bi bi-square-fill text-base text-slate-100"></i>
                                <span>Polos Putih (Rekomendasi TTD)</span>
                            </label>
                        </div>
                        <p class="text-[11px] text-slate-400 pt-1">
                            * Pilih <strong>Polos Putih</strong> agar gambar TTD ber-background putih menyatu 100% tanpa garis/kotak bayangan.
                        </p>
                    </div>

                    <!-- Opsi Tipe Tanda Tangan -->
                    <div class="bg-slate-950/60 p-4 rounded-2xl border border-white/10 space-y-2">
                        <label class="block text-xs font-bold text-amber-400 uppercase tracking-wider">
                            Pilih Opsi Tanda Tangan
                        </label>
                        <div class="grid grid-cols-2 gap-3 pt-1">
                            <label :class="[
                                'flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all text-xs font-bold',
                                sertifikatForm.tipe_ttd === 'digital' ? 'bg-amber-500/20 border-amber-500 text-amber-300' : 'bg-slate-900 border-white/10 text-slate-400 hover:text-white'
                            ]">
                                <input type="radio" v-model="sertifikatForm.tipe_ttd" value="digital" class="hidden" />
                                <i class="bi bi-file-earmark-image text-base"></i>
                                <span>Tanda Tangan Digital</span>
                            </label>

                            <label :class="[
                                'flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all text-xs font-bold',
                                sertifikatForm.tipe_ttd === 'basah' ? 'bg-amber-500/20 border-amber-500 text-amber-300' : 'bg-slate-900 border-white/10 text-slate-400 hover:text-white'
                            ]">
                                <input type="radio" v-model="sertifikatForm.tipe_ttd" value="basah" class="hidden" />
                                <i class="bi bi-pen-fill text-base"></i>
                                <span>Tanda Tangan Basah</span>
                            </label>
                        </div>
                        <p v-if="sertifikatForm.tipe_ttd === 'basah'" class="text-[11px] text-amber-200/80 pt-1">
                            * Opsi TTD Basah: Sertifikat dicetak hanya nama tanpa gambar tanda tangan (area TTD dikosongkan untuk TTD fisik).
                        </p>
                    </div>

                    <!-- Upload Gambar TTD jika Tipe Digital -->
                    <div v-if="sertifikatForm.tipe_ttd === 'digital'" class="space-y-4 pt-1">
                        
                        <!-- Upload TTD Direktur (Posisi Kiri) -->
                        <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-white/10">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">
                                Upload Gambar TTD Direktur (Posisi Kiri)
                            </label>
                            <input type="file" @change="onFileTtdDirekturChange" accept="image/png, image/jpeg, image/jpg, image/svg+xml"
                                class="w-full text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-500/20 file:text-amber-300 hover:file:bg-amber-500/40 cursor-pointer" />
                            <p class="text-[11px] text-slate-400 mt-1">Format: PNG, JPG, SVG (Max 2MB). Jika tidak di-upload, menggunakan gambar default.</p>
                        </div>

                        <!-- Upload TTD Pembicara (Posisi Kanan) -->
                        <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-white/10">
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">
                                Upload Gambar TTD Pembicara (Posisi Kanan)
                            </label>
                            <input type="file" @change="onFileTtdPembicaraChange" accept="image/png, image/jpeg, image/jpg, image/svg+xml"
                                class="w-full text-xs text-slate-300 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-500/20 file:text-amber-300 hover:file:bg-amber-500/40 cursor-pointer" />
                            <p class="text-[11px] text-slate-400 mt-1">Format: PNG, JPG, SVG (Max 2MB). Jika tidak di-upload, menggunakan gambar default.</p>
                        </div>

                    </div>

                    <div class="pt-3 flex items-center justify-end gap-2">
                        <button type="button" @click="showModalSertifikat = false"
                            class="px-5 py-2.5 bg-slate-800 text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-700">
                            Batal
                        </button>
                        <button type="submit" :disabled="isSubmittingSertifikat"
                            class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 disabled:bg-slate-800 text-slate-950 font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg transition-all active:scale-95 flex items-center gap-2">
                            <span v-if="isSubmittingSertifikat"
                                class="animate-spin rounded-full h-3.5 w-3.5 border-2 border-slate-950 border-t-transparent"></span>
                            <span>{{ isSubmittingSertifikat ? 'Memproses...' : 'Simpan Perubahan' }}</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <!-- MODAL ACAK SOAL (MODEL 2) -->
        <div v-if="showModalAcakSoal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md overflow-y-auto">
            <div class="bg-slate-900 border border-purple-500/30 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden text-slate-100 my-8">

                <div class="bg-purple-950/60 p-5 flex items-center justify-between border-b border-purple-500/30">
                    <h3 class="text-base font-bold text-purple-300 flex items-center gap-2">
                        <i class="bi bi-shuffle"></i>
                        <span>Pengaturan Acak Soal Ujian (Model 2)</span>
                    </h3>
                    <button @click="showModalAcakSoal = false" class="text-purple-200 hover:text-white">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form @submit.prevent="submitAcakSoal" class="p-6 space-y-5">

                    <!-- 1. Pilihan Distribusi Soal (Tipe Acak) -->
                    <div class="bg-slate-950/60 p-4 rounded-2xl border border-white/10 space-y-2">
                        <label class="block text-xs font-bold text-purple-400 uppercase tracking-wider">
                            Distribusi Soal Ke Peserta
                        </label>
                        <div class="space-y-2 pt-1">
                            <label :class="[
                                'flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all text-xs font-bold',
                                acakForm.tipe_acak === 'per_peserta' ? 'bg-purple-500/20 border-purple-500 text-purple-200' : 'bg-slate-900 border-white/10 text-slate-400 hover:text-white'
                            ]">
                                <input type="radio" v-model="acakForm.tipe_acak" value="per_peserta" class="hidden" />
                                <i class="bi bi-people-fill text-lg text-purple-400"></i>
                                <div>
                                    <span class="block text-white">Setiap Peserta Mendapatkan Soal Berbeda</span>
                                    <span class="text-[11px] font-normal text-slate-400">Sistem mengacak kombinasi soal unik secara dinamis untuk setiap peserta saat ujian dimulai.</span>
                                </div>
                            </label>

                            <label :class="[
                                'flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all text-xs font-bold',
                                acakForm.tipe_acak === 'semua_sama' ? 'bg-purple-500/20 border-purple-500 text-purple-200' : 'bg-slate-900 border-white/10 text-slate-400 hover:text-white'
                            ]">
                                <input type="radio" v-model="acakForm.tipe_acak" value="semua_sama" class="hidden" />
                                <i class="bi bi-journal-check text-lg text-amber-400"></i>
                                <div>
                                    <span class="block text-white">Semua Peserta Mendapatkan Soal Yang Sama</span>
                                    <span class="text-[11px] font-normal text-slate-400">Sistem memilih N soal acak sekali sekarang, lalu set soal tersebut diberikan secara seragam ke semua peserta.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- 2. Pilih Jumlah Soal -->
                    <div class="bg-slate-950/60 p-4 rounded-2xl border border-white/10 space-y-2">
                        <label class="block text-xs font-bold text-purple-400 uppercase tracking-wider">
                            Pilih Jumlah Soal Ujian
                        </label>
                        <div class="flex flex-wrap gap-2 pt-1">
                            <button type="button" v-for="preset in [20, 25, 30, 50]" :key="preset"
                                @click="acakForm.jumlah_preset = preset; acakForm.is_custom = false"
                                :class="[!acakForm.is_custom && acakForm.jumlah_preset === preset ? 'bg-purple-600 text-white font-bold border-purple-400 shadow-md' : 'bg-slate-900 text-slate-300 border-white/10 hover:bg-slate-800', 'px-3.5 py-2 rounded-xl text-xs border transition-all']">
                                {{ preset }} Soal
                            </button>
                            <button type="button" @click="acakForm.is_custom = true"
                                :class="[acakForm.is_custom ? 'bg-purple-600 text-white font-bold border-purple-400 shadow-md' : 'bg-slate-900 text-slate-300 border-white/10 hover:bg-slate-800', 'px-3.5 py-2 rounded-xl text-xs border transition-all']">
                                Custom...
                            </button>
                        </div>
                        <div v-if="acakForm.is_custom" class="pt-2">
                            <label class="block text-[11px] text-slate-300 font-bold mb-1">Ketik Jumlah Soal Custom:</label>
                            <input v-model.number="acakForm.jumlah_custom" type="number" min="1" placeholder="Contoh: 36, 55, dll." required
                                class="w-full px-3.5 py-2 bg-slate-900 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-purple-500" />
                        </div>
                    </div>

                    <!-- 3. Pilih Tingkat Kesulitan Ujian -->
                    <div class="bg-slate-950/60 p-4 rounded-2xl border border-white/10 space-y-2">
                        <label class="block text-xs font-bold text-purple-400 uppercase tracking-wider">
                            Pilih Tingkat Kesulitan Ujian
                        </label>
                        <div class="grid grid-cols-3 gap-2 pt-1">
                            <label :class="[
                                'flex flex-col items-center justify-center p-3 rounded-xl border cursor-pointer transition-all text-center',
                                acakForm.tingkat_kesulitan === 'mudah' ? 'bg-emerald-500/20 border-emerald-500 text-emerald-300 font-bold' : 'bg-slate-900 border-white/10 text-slate-400 hover:text-white'
                            ]">
                                <input type="radio" v-model="acakForm.tingkat_kesulitan" value="mudah" class="hidden" />
                                <span class="text-xs uppercase tracking-wider block">Mudah</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">50% M, 30% N, 20% S</span>
                            </label>

                            <label :class="[
                                'flex flex-col items-center justify-center p-3 rounded-xl border cursor-pointer transition-all text-center',
                                acakForm.tingkat_kesulitan === 'normal' ? 'bg-blue-500/20 border-blue-500 text-blue-300 font-bold' : 'bg-slate-900 border-white/10 text-slate-400 hover:text-white'
                            ]">
                                <input type="radio" v-model="acakForm.tingkat_kesulitan" value="normal" class="hidden" />
                                <span class="text-xs uppercase tracking-wider block">Normal</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">50% N, 30% S, 20% M</span>
                            </label>

                            <label :class="[
                                'flex flex-col items-center justify-center p-3 rounded-xl border cursor-pointer transition-all text-center',
                                acakForm.tingkat_kesulitan === 'sulit' ? 'bg-rose-500/20 border-rose-500 text-rose-300 font-bold' : 'bg-slate-900 border-white/10 text-slate-400 hover:text-white'
                            ]">
                                <input type="radio" v-model="acakForm.tingkat_kesulitan" value="sulit" class="hidden" />
                                <span class="text-xs uppercase tracking-wider block">Sulit</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">50% S, 30% N, 20% M</span>
                            </label>
                        </div>
                    </div>

                    <p class="text-[11px] text-amber-300/80 bg-amber-950/40 p-3 rounded-xl border border-amber-500/20">
                        * Catatan: Soal yang ditandai <strong>"Tidak Ada"</strong> pada tingkat kesulitan akan diabaikan dan tidak akan diikutsertakan dalam pengacakan (khusus untuk pemilihan manual Model 1).
                    </p>

                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" @click="showModalAcakSoal = false"
                            class="px-5 py-2.5 bg-slate-800 text-slate-300 font-bold text-xs rounded-xl hover:bg-slate-700">
                            Batal
                        </button>
                        <button type="submit" :disabled="isSubmittingAcak"
                            class="px-6 py-2.5 bg-purple-600 hover:bg-purple-500 disabled:bg-slate-800 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg transition-all active:scale-95 flex items-center gap-2">
                            <span v-if="isSubmittingAcak"
                                class="animate-spin rounded-full h-3.5 w-3.5 border-2 border-white border-t-transparent"></span>
                            <span>{{ isSubmittingAcak ? 'Memproses Acak...' : 'Simpan & Terapkan Acak Soal' }}</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</template>

<script lang="js">
import axios from 'axios';

export default {
    props: ['user'],
    data() {
        return {
            isDownloadingZip: false,
            pesertaList: [],
            pagination: { current_page: 1, last_page: 1, total: 0, from: 0, to: 0 },
            selectedPesertaDetail: null,
            selectedPesertaToken: null,
            activeToken: '',
            isCopied: false,
            selectedTipeToken: 'ujian',
            selectedDurasiMenit: 60,
            tanggalAbsensiPdf: new Date().toISOString().split('T')[0],

            // State Ganti Password Admin
            showModalPasswordAdmin: false,
            isChangingPasswordAdmin: false,
            formPasswordAdmin: {
                current_password: '',
                new_password: '',
                new_password_confirmation: ''
            },


            // Edit Peserta
            selectedPesertaEdit: null,
            formEditPeserta: { id: null, nama: '', nik: '', jenis_kelamin: 'L', no_hp: '', email: '', alamat: '' },

            // Bank Soal
            bankSoalList: [],
            selectedSoalIds: [],
            showModalAddSoal: false,
            formSoal: { soal: '', opsi_a: '', opsi_b: '', opsi_c: '', opsi_d: '', kunci_jawaban: '', tingkat_kesulitan: 'tidak_ada' },

            // Ujian Setting & Acak Soal
            ujianSetting: { model_ujian: 'manual', tipe_acak: 'semua_sama', jumlah_soal: 25, tingkat_kesulitan: 'normal' },
            showModalAcakSoal: false,
            isSubmittingAcak: false,
            acakForm: {
                tipe_acak: 'semua_sama',
                jumlah_preset: 25,
                is_custom: false,
                jumlah_custom: 25,
                tingkat_kesulitan: 'normal'
            },

            // Pagination Bank Soal
            bankSoalCurrentPage: 1,
            bankSoalPerPage: 10,

            // Review Jawaban Ujian Peserta
            selectedPesertaReview: null,
            reviewData: { nilai: 0, total_benar: 0, total_salah: 0, detail: [] },

            // Sertifikat Settings
            showModalSertifikat: false,
            isSubmittingSertifikat: false,
            sertifikatForm: {
                nama_direktur: '',
                nama_pembicara: '',
                tipe_ttd: 'digital',
                use_bg_watermark: true,
                file_ttd_direktur: null,
                file_ttd_pembicara: null
            }

        };
    },
    computed: {
        totalBankSoalPages() {
            return Math.ceil(this.bankSoalList.length / this.bankSoalPerPage) || 1;
        },
        paginatedBankSoal() {
            const start = (this.bankSoalCurrentPage - 1) * this.bankSoalPerPage;
            return this.bankSoalList.slice(start, start + this.bankSoalPerPage);
        }
    },
    mounted() {
        this.fetchPeserta(1);
        this.fetchBankSoal();
        this.fetchSertifikatSetting();
    },
    methods: {
        getPdfUrl(berkas) {
            if (!berkas) return '#';
            // Jika parameter yang dikirim berupa object berkas
            if (typeof berkas === 'object' && berkas.id) {
                return `/api/admin/berkas/${berkas.id}/preview`;
            }
            // Fallback path biasa
            const cleanPath = String(berkas).replace(/^(\/?storage\/|\/?public\/)+/g, '');
            return `/storage/${cleanPath}`;
        },

        fetchPeserta(page = 1) {
            axios.get(`/api/admin/peserta?page=${page}`).then(res => {
                this.pesertaList = res.data.data;
                this.pagination = { current_page: res.data.current_page, last_page: res.data.last_page, total: res.data.total, from: res.data.from, to: res.data.to };
            });
        },

        // EDIT & DELETE PESERTA
        openModalEdit(p) {
            this.selectedPesertaEdit = p;
            this.formEditPeserta = {
                id: p.id,
                nama: p.nama || '',
                nik: p.nik || '',
                jenis_kelamin: p.jenis_kelamin || 'L',
                no_hp: p.no_hp || '',
                email: p.email || '',
                alamat: p.alamat || ''
            };
        },
        submitEditPeserta() {
            axios.put(`/api/admin/peserta/${this.formEditPeserta.id}`, this.formEditPeserta).then(res => {
                alert(res.data.message || 'Data peserta berhasil diperbarui!');
                this.selectedPesertaEdit = null;
                this.fetchPeserta(this.pagination.current_page);
            }).catch(err => {
                alert('Gagal mengupdate data peserta: ' + (err.response?.data?.message || err.message));
            });
        },
        confirmDeletePeserta(p) {
            if (confirm(`Apakah Anda yakin ingin menghapus peserta ${p.nama}? Seluruh data & berkasnya akan terhapus.`)) {
                axios.delete(`/api/admin/peserta/${p.id}`).then(res => {
                    alert(res.data.message || 'Peserta berhasil dihapus!');
                    this.fetchPeserta(this.pagination.current_page);
                }).catch(err => {
                    alert('Gagal menghapus peserta: ' + (err.response?.data?.message || err.message));
                });
            }
        },

        uppercaseFirst(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        },
        fetchBankSoal() {
            axios.get('/api/admin/bank-soal').then(res => {
                if (res.data.soal) {
                    this.bankSoalList = res.data.soal;
                    this.ujianSetting = res.data.setting || this.ujianSetting;
                    this.selectedSoalIds = res.data.soal.filter(s => s.is_selected).map(s => s.id);
                } else {
                    this.bankSoalList = res.data;
                    this.selectedSoalIds = res.data.filter(s => s.is_selected).map(s => s.id);
                }
            });
        },
        submitSoalBaru() {
            axios.post('/api/admin/bank-soal', this.formSoal).then(res => {
                alert(res.data.message);
                this.showModalAddSoal = false;
                this.formSoal = { soal: '', opsi_a: '', opsi_b: '', opsi_c: '', opsi_d: '', kunci_jawaban: '', tingkat_kesulitan: 'tidak_ada' };
                this.fetchBankSoal();
            });
        },
        saveSelectedSoal() {
            axios.post('/api/admin/bank-soal/pilih', { selected_ids: this.selectedSoalIds }).then(res => {
                alert(res.data.message);
                this.fetchBankSoal();
            });
        },
        openModalAcakSoal() {
            this.acakForm.tipe_acak = this.ujianSetting.tipe_acak || 'semua_sama';
            this.acakForm.tingkat_kesulitan = this.ujianSetting.tingkat_kesulitan || 'normal';
            const jml = this.ujianSetting.jumlah_soal || 25;
            if ([20, 25, 30, 50].includes(jml)) {
                this.acakForm.jumlah_preset = jml;
                this.acakForm.is_custom = false;
            } else {
                this.acakForm.is_custom = true;
                this.acakForm.jumlah_custom = jml;
            }
            this.showModalAcakSoal = true;
        },
        submitAcakSoal() {
            const jumlahSoal = this.acakForm.is_custom ? this.acakForm.jumlah_custom : this.acakForm.jumlah_preset;
            if (!jumlahSoal || jumlahSoal < 1) {
                alert('Jumlah soal harus lebih dari 0!');
                return;
            }
            this.isSubmittingAcak = true;
            axios.post('/api/admin/bank-soal/acak', {
                tipe_acak: this.acakForm.tipe_acak,
                jumlah_soal: jumlahSoal,
                tingkat_kesulitan: this.acakForm.tingkat_kesulitan
            }).then(res => {
                this.isSubmittingAcak = false;
                alert(res.data.message || 'Acak Soal berhasil diterapkan!');
                this.showModalAcakSoal = false;
                this.fetchBankSoal();
            }).catch(err => {
                this.isSubmittingAcak = false;
                alert(err.response?.data?.message || 'Gagal menerapkan Acak Soal.');
            });
        },
        deleteSoal(id) {
            if (confirm('Apakah Anda yakin ingin menghapus soal ini?')) {
                axios.delete(`/api/admin/bank-soal/${id}`).then(() => {
                    this.fetchBankSoal();
                    if (this.bankSoalCurrentPage > this.totalBankSoalPages) {
                        this.bankSoalCurrentPage = Math.max(1, this.totalBankSoalPages);
                    }
                });
            }
        },
        openModalDetail(peserta) { this.selectedPesertaDetail = peserta; },
        openModalToken(peserta) {
            this.selectedPesertaToken = peserta;
            this.activeToken = peserta.token ? peserta.token.kode_token : '';
            this.selectedTipeToken = peserta.token ? (peserta.token.tipe_token || 'ujian') : 'ujian';
            this.selectedDurasiMenit = peserta.token ? (peserta.token.durasi_menit || 60) : 60;
            this.isCopied = false;
        },
        processGenerateToken() {
            axios.post(`/api/admin/generate-token/${this.selectedPesertaToken.id}`, {
                tipe_token: this.selectedTipeToken,
                durasi_menit: this.selectedDurasiMenit
            }).then(res => {
                this.activeToken = res.data.token;
                this.fetchPeserta(this.pagination.current_page);
            });
        },
        copyToken() {
            if (!this.activeToken) return;

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(this.activeToken).then(() => {
                    this.isCopied = true;
                    setTimeout(() => { this.isCopied = false; }, 2000);
                }).catch(() => {
                    this.fallbackCopyText(this.activeToken);
                });
            } else {
                this.fallbackCopyText(this.activeToken);
            }
        },
        fallbackCopyText(text) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                document.execCommand('copy');
                this.isCopied = true;
                setTimeout(() => { this.isCopied = false; }, 2000);
            } catch (err) {
                alert('Gagal menyalin token: ' + err);
            }

            document.body.removeChild(textArea);
        },
        hasRiwayatUjian(peserta) {
            if (Array.isArray(peserta.riwayat_ujian)) {
                return peserta.riwayat_ujian.length > 0;
            }
            return !!(peserta.riwayat_ujian && peserta.riwayat_ujian.id);
        },
        isDetailBenar(item) {
            if (!item) return false;
            if (item.is_benar === true || item.is_benar === 1 || item.is_benar === '1') return true;
            
            // Fallback perbandingan manual antara jawaban user dengan kunci di objek soal
            const kunci = item.soal ? item.soal.kunci_jawaban : item.kunci_jawaban;
            if (item.jawaban_user && kunci) {
                return String(item.jawaban_user).trim().toUpperCase() === String(kunci).trim().toUpperCase();
            }
            return false;
        },
        confirmResetUjian(p) {
            if (confirm(`Apakah Anda yakin ingin membuka kembali ujian untuk ${p.nama}? Sesi ujian sebelumnya akan di-reset dan token akan diaktifkan kembali sehingga peserta dapat melakukan ujian ulang.`)) {
                axios.post(`/api/admin/peserta/${p.id}/reset-ujian`).then(res => {
                    alert(res.data.message || 'Sesi ujian berhasil dibuka kembali!');
                    this.fetchPeserta(this.pagination.current_page);
                }).catch(err => {
                    alert('Gagal membuka kembali ujian: ' + (err.response?.data?.message || err.message));
                });
            }
        },
        cetakSertifikat(peserta) {
            let riwayatId = null;
            if (Array.isArray(peserta.riwayat_ujian) && peserta.riwayat_ujian.length > 0) {
                riwayatId = peserta.riwayat_ujian[0].id;
            } else if (peserta.riwayat_ujian && peserta.riwayat_ujian.id) {
                riwayatId = peserta.riwayat_ujian.id;
            }

            if (riwayatId) {
                window.open(`/api/ujian/sertifikat/${riwayatId}`, '_blank');
            } else {
                alert('Peserta belum memiliki riwayat ujian.');
            }
        },
openModalReview(peserta) {
            let riwayatId = null;
            if (Array.isArray(peserta.riwayat_ujian) && peserta.riwayat_ujian.length > 0) {
                // Ambil ujian terbaru berdasarkan ID terbesar
                const sorted = [...peserta.riwayat_ujian].sort((a, b) => b.id - a.id);
                riwayatId = sorted[0].id;
            } else if (peserta.riwayat_ujian && peserta.riwayat_ujian.id) {
                riwayatId = peserta.riwayat_ujian.id;
            }

            if (!riwayatId) {
                alert('Peserta ini belum menyelesaikan Ujian.');
                return;
            }

            this.selectedPesertaReview = peserta;
            this.reviewData = { nilai_akhir: 0, jawaban_benar: 0, jawaban_salah: 0, detail_jawaban: [] };

            axios.get(`/api/ujian/review/${riwayatId}`).then(res => {
                // FIX BINDING: Unwrap objek res.data.riwayat jika ada
                this.reviewData = res.data.riwayat || res.data;
            }).catch(err => {
                alert('Gagal mengambil data review ujian: ' + err);
            });
        },
        // METHOD DOWNLOAD ZIP PESERTA TANPA BUKA TAB KOSONG
        // downloadZipPesertaDirect(peserta) {
        //     if (!peserta.berkas || peserta.berkas.length === 0) {
        //         alert('Peserta ini belum memiliki berkas terunggah.');
        //         return;
        //     }
        //     // Memicu unduhan langsung tanpa muka tab kosong
        //     window.location.href = `/api/admin/peserta/${peserta.id}/download-zip`;
        // },
        // METHOD DOWNLOAD ZIP VIA AXIOS BLOB (DIRECT FILE SAVING)

        getDetailList(data) {
            if (!data) return [];
            if (Array.isArray(data.detail)) return data.detail;
            if (Array.isArray(data.detail_jawaban)) return data.detail_jawaban;
            if (Array.isArray(data.detailJawaban)) return data.detailJawaban;
            if (Array.isArray(data.details)) return data.details;
            if (Array.isArray(data)) return data;
            return [];
        },
        downloadZip(pesertaId, namaLengkap) {
            if (this.isDownloadingZip) return;
            this.isDownloadingZip = true;

            axios({
                url: `/api/admin/peserta/${pesertaId}/download-zip`,
                method: 'GET',
                responseType: 'blob'
            }).then(response => {
                // Pengecekan jika response berbentuk JSON Error atau HTML Fallback
                if (response.data.type === 'application/json' || response.data.type === 'text/html') {
                    const reader = new FileReader();
                    reader.onload = () => {
                        try {
                            const res = JSON.parse(reader.result);
                            alert(res.message || 'Gagal mengunduh file ZIP');
                        } catch {
                            alert('Gagal mengunduh file ZIP: Dokumen fisik tidak ditemukan.');
                        }
                    };
                    reader.readAsText(response.data);
                    this.isDownloadingZip = false;
                    return;
                }

                // Memuat file Blob bertipe ZIP
                const blob = new Blob([response.data], { type: 'application/zip' });
                const cleanName = (namaLengkap || 'Peserta').replace(/[^a-zA-Z0-9_-]/g, '_');
                const fileName = `Berkas-Peserta_${cleanName}.zip`;

                // Pemicu unduhan file otomatis ke browser
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', fileName);
                document.body.appendChild(link);
                link.click();

                setTimeout(() => {
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);
                }, 100);

                this.isDownloadingZip = false;
            }).catch(err => {
                alert('Gagal mengunduh berkas ZIP: ' + (err.response?.data?.message || err.message));
                this.isDownloadingZip = false;
            });
        },
        openModalChangePasswordAdmin() {
            this.formPasswordAdmin = { current_password: '', new_password: '', new_password_confirmation: '' };
            this.showModalPasswordAdmin = true;
        },
        submitChangePasswordAdmin() {
            if (this.formPasswordAdmin.new_password !== this.formPasswordAdmin.new_password_confirmation) {
                alert('Konfirmasi password baru tidak cocok!');
                return;
            }
            this.isChangingPasswordAdmin = true;
            const adminId = this.user ? this.user.id : 1;
            axios.post(`/api/change-password/${adminId}`, this.formPasswordAdmin).then(res => {
                this.isChangingPasswordAdmin = false;
                alert(res.data.message || 'Password Admin berhasil diperbarui!');
                this.showModalPasswordAdmin = false;
            }).catch(err => {
                this.isChangingPasswordAdmin = false;
                alert(err.response?.data?.message || 'Gagal memperbarui password Admin.');
            });
        },

        // SERTIFIKAT SETTING METHODS
        fetchSertifikatSetting() {
            axios.get('/api/admin/sertifikat-setting').then(res => {
                if (res.data) {
                    this.sertifikatForm.nama_direktur = res.data.nama_direktur || '';
                    this.sertifikatForm.nama_pembicara = res.data.nama_pembicara || '';
                    this.sertifikatForm.tipe_ttd = res.data.tipe_ttd || 'digital';
                    this.sertifikatForm.use_bg_watermark = res.data.use_bg_watermark !== undefined ? Boolean(res.data.use_bg_watermark) : true;
                }
            });
        },
        openModalSertifikat() {
            this.fetchSertifikatSetting();
            this.showModalSertifikat = true;
        },
        onFileTtdDirekturChange(event) {
            this.sertifikatForm.file_ttd_direktur = event.target.files[0] || null;
        },
        onFileTtdPembicaraChange(event) {
            this.sertifikatForm.file_ttd_pembicara = event.target.files[0] || null;
        },
        submitSertifikatSetting() {
            this.isSubmittingSertifikat = true;
            const formData = new FormData();
            formData.append('nama_direktur', this.sertifikatForm.nama_direktur || '');
            formData.append('nama_pembicara', this.sertifikatForm.nama_pembicara || '');
            formData.append('tipe_ttd', this.sertifikatForm.tipe_ttd || 'digital');
            formData.append('use_bg_watermark', this.sertifikatForm.use_bg_watermark ? 1 : 0);
            if (this.sertifikatForm.file_ttd_direktur) {
                formData.append('ttd_direktur', this.sertifikatForm.file_ttd_direktur);
            }
            if (this.sertifikatForm.file_ttd_pembicara) {
                formData.append('ttd_pembicara', this.sertifikatForm.file_ttd_pembicara);
            }

            axios.post('/api/admin/sertifikat-setting', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            }).then(res => {
                this.isSubmittingSertifikat = false;
                alert(res.data.message || 'Pengaturan sertifikat berhasil disimpan!');
                this.showModalSertifikat = false;
                this.fetchSertifikatSetting();
            }).catch(err => {
                this.isSubmittingSertifikat = false;
                alert(err.response?.data?.message || 'Gagal menyimpan pengaturan sertifikat.');
            });
        }
        

    }
};
</script>