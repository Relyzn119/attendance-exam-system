<template>
    <div class="w-full text-slate-100 font-sans pb-12 space-y-8">

        <!-- 1. HEADER ADMIN DASHBOARD (GLASSMORPHISM SHOWCASE) -->
        <div class="bg-slate-900/60 border border-white/15 rounded-3xl p-6 sm:p-8 backdrop-blur-xl shadow-2xl relative overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-500/10 border border-blue-400/30 rounded-full text-xs text-blue-300 font-semibold mb-2">
                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                        <span>Pusat Kendali Ujian & Diklat RSU Bunda Thamrin</span>
                    </div>
                    <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight uppercase flex items-center gap-3">
                        <i class="bi bi-speedometer2 text-blue-400"></i>
                        <span>Dashboard Admin Diklat</span>
                    </h1>
                    <p class="text-slate-300 text-xs sm:text-sm max-w-2xl">
                        Kelola Absensi Peserta, Token Akses Ujian Digital, & Penataan Bank Soal Terintegrasi.
                    </p>
                </div>

                <div class="self-start sm:self-auto shrink-0">
                    <span class="inline-flex items-center gap-1.5 bg-rose-500/20 text-rose-300 border border-rose-500/40 text-xs font-bold px-4 py-2 rounded-full shadow-lg backdrop-blur-md">
                        <i class="bi bi-shield-lock-fill"></i> Role: Admin Authorized
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. TABEL DATA PESERTA UJIAN & ABSENSI (GLASS CARD) -->
        <div class="bg-slate-900/60 border border-white/15 rounded-3xl overflow-hidden backdrop-blur-xl shadow-2xl">
            
            <!-- Table Header Bar -->
            <div class="p-6 border-b border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-950/40">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="bi bi-people-fill text-blue-400"></i>
                        <span>Daftar Peserta Ujian & Absensi</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar Karyawan/Peserta Registered dalam Sistem Diklat</p>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                    <span class="bg-blue-500/10 border border-blue-500/30 text-blue-300 text-xs font-bold px-3 py-1.5 rounded-full">
                        Total: {{ pagination.total }} Peserta
                    </span>
                    <a 
                        href="/api/admin/export-absensi" 
                        target="_blank" 
                        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-4 py-2 rounded-full shadow-lg transition-all active:scale-95"
                    >
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                        <span>Export PDF Absensi</span>
                    </a>
                </div>
            </div>

            <!-- Table Body -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/10 bg-slate-950/60 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6 text-center w-12">No</th>
                            <th class="py-4 px-6">Nama Peserta / NIK</th>
                            <th class="py-4 px-6 text-center">Gender</th>
                            <th class="py-4 px-6">No. HP / WA</th>
                            <th class="py-4 px-6">Email</th>
                            <th class="py-4 px-6 text-center">Aksi Token & Berkas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm">
                        <tr v-for="(p, index) in pesertaList" :key="p.id" class="hover:bg-white/5 transition-colors group">
                            <td class="py-4 px-6 text-center font-mono text-slate-400 text-xs">
                                {{ (pagination.current_page - 1) * 10 + index + 1 }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-white group-hover:text-blue-300 transition-colors">{{ p.nama }}</div>
                                <div class="text-xs text-slate-400 font-mono">NIK: {{ p.nik }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span v-if="p.jenis_kelamin === 'L'" class="px-3 py-1 bg-sky-500/20 text-sky-300 border border-sky-500/30 rounded-full text-xs font-semibold">
                                    Laki-Laki
                                </span>
                                <span v-else class="px-3 py-1 bg-pink-500/20 text-pink-300 border border-pink-500/30 rounded-full text-xs font-semibold">
                                    Perempuan
                                </span>
                            </td>
                            <td class="py-4 px-6 font-mono text-slate-300 text-xs">{{ p.no_hp }}</td>
                            <td class="py-4 px-6 text-slate-300 text-xs">{{ p.email }}</td>
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <button 
                                        @click="openModalDetail(p)" 
                                        class="p-2 bg-blue-600/30 hover:bg-blue-600 text-blue-200 hover:text-white rounded-xl transition-all border border-blue-500/30" 
                                        title="Lihat Detail Berkas & Data"
                                    >
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    <button 
                                        @click="openModalToken(p)" 
                                        class="p-2 bg-amber-500/30 hover:bg-amber-500 text-amber-200 hover:text-white rounded-xl transition-all border border-amber-500/30" 
                                        title="Generate Token Ujian"
                                    >
                                        <i class="bi bi-key-fill"></i>
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
            <div v-if="pagination.last_page > 1" class="p-4 border-t border-white/10 bg-slate-950/40 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <div>
                    Menampilkan {{ pagination.from }} - {{ pagination.to }} dari {{ pagination.total }} peserta
                </div>
                <div class="flex items-center gap-1">
                    <button 
                        @click="fetchPeserta(pagination.current_page - 1)" 
                        :disabled="pagination.current_page === 1"
                        class="px-3 py-1.5 rounded-lg border border-white/10 bg-slate-900 text-slate-300 hover:bg-white/10 disabled:opacity-40 disabled:cursor-not-allowed transition-all"
                    >
                        Previous
                    </button>
                    <button 
                        v-for="page in pagination.last_page" 
                        :key="page"
                        @click="fetchPeserta(page)"
                        :class="pagination.current_page === page ? 'bg-blue-600 text-white font-bold border-blue-400' : 'bg-slate-900 text-slate-300 hover:bg-white/10 border-white/10'"
                        class="px-3 py-1.5 rounded-lg border transition-all"
                    >
                        {{ page }}
                    </button>
                    <button 
                        @click="fetchPeserta(pagination.current_page + 1)" 
                        :disabled="pagination.current_page === pagination.last_page"
                        class="px-3 py-1.5 rounded-lg border border-white/10 bg-slate-900 text-slate-300 hover:bg-white/10 disabled:opacity-40 disabled:cursor-not-allowed transition-all"
                    >
                        Next
                    </button>
                </div>
            </div>

        </div>

        <!-- 3. BAGIAN BANK SOAL & SET 25 SOAL UJIAN (GLASS CARD) -->
        <div class="bg-slate-900/60 border border-white/15 rounded-3xl overflow-hidden backdrop-blur-xl shadow-2xl">
            
            <!-- Bank Soal Header Bar -->
            <div class="p-6 border-b border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-950/40">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="bi bi-journal-check text-emerald-400"></i>
                        <span>Bank Soal & Pengaturan Soal Ujian</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Atur & Centang Soal yang akan Dikeluarkan saat Ujian</p>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <button 
                        @click="showModalAddSoal = true" 
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs px-4 py-2.5 rounded-full shadow-lg transition-all active:scale-95"
                    >
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>Tambah Soal Baru</span>
                    </button>
                    <button 
                        @click="saveSelectedSoal" 
                        class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs px-4 py-2.5 rounded-full shadow-lg transition-all active:scale-95"
                    >
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Simpan Set Soal Ujian</span>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-4">
                
                <!-- COUNTER SOAL TERPILIH ALERT -->
                <div class="bg-blue-950/50 border border-blue-500/30 rounded-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-3 backdrop-blur-md">
                    <div class="text-xs text-blue-200 flex items-center gap-2">
                        <i class="bi bi-info-circle-fill text-blue-400 text-base"></i>
                        <span><strong>Mekanisme Soal Ujian:</strong> Centang soal di bawah ini untuk dijadikan materi ujian resmi peserta.</span>
                    </div>
                    <span class="bg-blue-600 text-white font-bold text-xs px-3.5 py-1.5 rounded-full shadow-md shrink-0">
                        Soal Terpilih: {{ selectedSoalIds.length }} / 25
                    </span>
                </div>

                <!-- TABLE BANK SOAL -->
                <div class="overflow-x-auto rounded-2xl border border-white/10">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/10 bg-slate-950/60 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                <th class="py-4 px-4 text-center w-12">Pilih</th>
                                <th class="py-4 px-4 text-center w-12">No</th>
                                <th class="py-4 px-6">Pertanyaan / Soal</th>
                                <th class="py-4 px-6">Opsi Jawaban</th>
                                <th class="py-4 px-4 text-center w-20">Kunci</th>
                                <th class="py-4 px-4 text-center w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-sm">
                            <tr 
                                v-for="(soal, index) in paginatedBankSoal" 
                                :key="soal.id" 
                                :class="selectedSoalIds.includes(soal.id) ? 'bg-amber-500/10 border-l-4 border-l-amber-400' : 'hover:bg-white/5'"
                                class="transition-colors"
                            >
                                <td class="py-4 px-4 text-center">
                                    <input 
                                        type="checkbox" 
                                        :value="soal.id" 
                                        v-model="selectedSoalIds" 
                                        class="w-4 h-4 rounded bg-slate-950 border-white/20 text-amber-500 focus:ring-amber-500 focus:ring-offset-slate-900 cursor-pointer" 
                                    />
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
                                    <span class="px-2.5 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-mono font-bold text-xs rounded-lg">
                                        {{ soal.kunci_jawaban }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <button 
                                        @click="deleteSoal(soal.id)" 
                                        class="p-2 bg-rose-500/20 hover:bg-rose-500/40 text-rose-300 hover:text-white rounded-xl transition-all" 
                                        title="Hapus Soal"
                                    >
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
                <div v-if="totalBankSoalPages > 1" class="p-4 border border-white/10 rounded-2xl bg-slate-950/40 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400 mt-4">
                    <div>
                        Menampilkan {{ (bankSoalCurrentPage - 1) * bankSoalPerPage + 1 }} - {{ Math.min(bankSoalCurrentPage * bankSoalPerPage, bankSoalList.length) }} dari {{ bankSoalList.length }} soal
                    </div>
                    <div class="flex items-center gap-1">
                        <button 
                            @click="bankSoalCurrentPage--" 
                            :disabled="bankSoalCurrentPage === 1"
                            class="px-3 py-1.5 rounded-lg border border-white/10 bg-slate-900 text-slate-300 hover:bg-white/10 disabled:opacity-40 disabled:cursor-not-allowed transition-all"
                        >
                            &lt;&lt; Prev
                        </button>
                        <button 
                            v-for="page in totalBankSoalPages" 
                            :key="page"
                            @click="bankSoalCurrentPage = page"
                            :class="bankSoalCurrentPage === page ? 'bg-emerald-600 text-white font-bold border-emerald-400' : 'bg-slate-900 text-slate-300 hover:bg-white/10 border-white/10'"
                            class="px-3 py-1.5 rounded-lg border transition-all"
                        >
                            {{ page }}
                        </button>
                        <button 
                            @click="bankSoalCurrentPage++" 
                            :disabled="bankSoalCurrentPage === totalBankSoalPages"
                            class="px-3 py-1.5 rounded-lg border border-white/10 bg-slate-900 text-slate-300 hover:bg-white/10 disabled:opacity-40 disabled:cursor-not-allowed transition-all"
                        >
                            Next &gt;&gt;
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- MODAL 1: DETAIL PESERTA -->
        <div v-if="selectedPesertaDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md overflow-y-auto">
            <div class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden text-slate-100">
                
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
                        <h4 class="text-xs font-bold text-blue-400 uppercase tracking-wider mb-3">1. Data Diri Lengkap</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs bg-slate-950/60 p-4 rounded-2xl border border-white/10">
                            <div><strong class="text-slate-400">Nama:</strong> <span class="text-white font-semibold">{{ selectedPesertaDetail.nama }}</span></div>
                            <div><strong class="text-slate-400">NIK:</strong> <span class="text-white font-mono">{{ selectedPesertaDetail.nik }}</span></div>
                            <div><strong class="text-slate-400">Email:</strong> <span class="text-white">{{ selectedPesertaDetail.email }}</span></div>
                            <div><strong class="text-slate-400">No HP:</strong> <span class="text-white font-mono">{{ selectedPesertaDetail.no_hp }}</span></div>
                            <div class="sm:col-span-2"><strong class="text-slate-400">Alamat:</strong> <span class="text-white">{{ selectedPesertaDetail.alamat }}</span></div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-blue-400 uppercase tracking-wider mb-3">2. Berkas Terunggah</h4>
                        <div class="space-y-2">
                            <div 
                                v-for="b in selectedPesertaDetail.berkas" 
                                :key="b.id" 
                                class="flex items-center justify-between p-3 bg-slate-950/60 border border-white/10 rounded-xl text-xs"
                            >
                                <span class="font-medium text-slate-200">{{ b.jenis_berkas }}</span>
                                <a 
                                    :href="'/storage/' + b.file_path" 
                                    target="_blank" 
                                    class="px-3 py-1 bg-rose-500/20 hover:bg-rose-500/40 text-rose-300 font-bold rounded-lg transition-all"
                                >
                                    Buka PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-slate-950/80 border-t border-white/10 text-right">
                    <button @click="selectedPesertaDetail = null" class="px-5 py-2 bg-white text-slate-950 font-bold text-xs rounded-full">
                        Tutup
                    </button>
                </div>

            </div>
        </div>

        <!-- MODAL 2: GENERATE TOKEN -->
        <div v-if="selectedPesertaToken" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
            <div class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden text-slate-100">
                
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
                    
                    <div v-if="activeToken" class="bg-slate-950/80 border border-amber-500/30 p-4 rounded-2xl space-y-3">
                        <div class="text-3xl sm:text-4xl font-mono font-black text-amber-400 tracking-widest">{{ activeToken }}</div>
                        <button 
                            @click="copyToken" 
                            class="px-4 py-1.5 bg-amber-500/20 hover:bg-amber-500/40 text-amber-200 text-xs font-bold rounded-full transition-all"
                        >
                            {{ isCopied ? 'Berhasil Disalin!' : 'Salin Kode Token' }}
                        </button>
                    </div>

                    <button 
                        @click="processGenerateToken" 
                        class="w-full py-3 bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg transition-all active:scale-95"
                    >
                        Generate Token Baru
                    </button>
                </div>

            </div>
        </div>

        <!-- MODAL 3: TAMBAH SOAL BARU -->
        <div v-if="showModalAddSoal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md overflow-y-auto">
            <div class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden text-slate-100">
                
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
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Pertanyaan Ujian</label>
                        <textarea v-model="formSoal.soal" rows="3" required class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ketik soal pertanyaan..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Opsi A</label>
                            <input v-model="formSoal.opsi_a" type="text" required class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Opsi B</label>
                            <input v-model="formSoal.opsi_b" type="text" required class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Opsi C</label>
                            <input v-model="formSoal.opsi_c" type="text" required class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Opsi D</label>
                            <input v-model="formSoal.opsi_d" type="text" required class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Kunci Jawaban Benar</label>
                        <select v-model="formSoal.kunci_jawaban" required class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option class="bg-slate-900" value="">-- Pilih Kunci Jawaban --</option>
                            <option class="bg-slate-900" value="A">A</option>
                            <option class="bg-slate-900" value="B">B</option>
                            <option class="bg-slate-900" value="C">C</option>
                            <option class="bg-slate-900" value="D">D</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg transition-all active:scale-95">
                            Simpan Soal ke Bank Soal
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
    data() {
        return {
            pesertaList: [],
            pagination: { current_page: 1, last_page: 1, total: 0, from: 0, to: 0 },
            selectedPesertaDetail: null,
            selectedPesertaToken: null,
            activeToken: '',
            isCopied: false,

            // Bank Soal
            bankSoalList: [],
            selectedSoalIds: [],
            showModalAddSoal: false,
            formSoal: { soal: '', opsi_a: '', opsi_b: '', opsi_c: '', opsi_d: '', kunci_jawaban: '' },

            // State Pagination Bank Soal (Tugas #1)
            bankSoalCurrentPage: 1,
            bankSoalPerPage: 10
        };
    },
    computed: {
        // Hitung total halaman bank soal
        totalBankSoalPages() {
            return Math.ceil(this.bankSoalList.length / this.bankSoalPerPage) || 1;
        },
        // Ambil 10 data soal sesuai halaman aktif
        paginatedBankSoal() {
            const start = (this.bankSoalCurrentPage - 1) * this.bankSoalPerPage;
            return this.bankSoalList.slice(start, start + this.bankSoalPerPage);
        }
    },
    mounted() {
        this.fetchPeserta(1);
        this.fetchBankSoal();
    },
    methods: {
        fetchPeserta(page = 1) {
            axios.get(`/api/admin/peserta?page=${page}`).then(res => {
                this.pesertaList = res.data.data;
                this.pagination = { current_page: res.data.current_page, last_page: res.data.last_page, total: res.data.total, from: res.data.from, to: res.data.to };
            });
        },
        fetchBankSoal() {
            axios.get('/api/admin/bank-soal').then(res => {
                this.bankSoalList = res.data;
                this.selectedSoalIds = res.data.filter(s => s.is_selected).map(s => s.id);
            });
        },
        submitSoalBaru() {
            axios.post('/api/admin/bank-soal', this.formSoal).then(res => {
                alert(res.data.message);
                this.showModalAddSoal = false;
                this.formSoal = { soal: '', opsi_a: '', opsi_b: '', opsi_c: '', opsi_d: '', kunci_jawaban: '' };
                this.fetchBankSoal();
            });
        },
        saveSelectedSoal() {
            axios.post('/api/admin/bank-soal/pilih', { selected_ids: this.selectedSoalIds }).then(res => {
                alert(res.data.message);
                this.fetchBankSoal();
            });
        },
        deleteSoal(id) {
            if (confirm('Apakah Anda yakin ingin menghapus soal ini?')) {
                axios.delete(`/api/admin/bank-soal/${id}`).then(() => {
                    this.fetchBankSoal();
                    // Jika halaman saat ini melebihi total halaman setelah delete, mundur 1 halaman
                    if (this.bankSoalCurrentPage > this.totalBankSoalPages) {
                        this.bankSoalCurrentPage = Math.max(1, this.totalBankSoalPages);
                    }
                });
            }
        },
        openModalDetail(peserta) { this.selectedPesertaDetail = peserta; },
        openModalToken(peserta) { this.selectedPesertaToken = peserta; this.activeToken = peserta.token ? peserta.token.kode_token : ''; this.isCopied = false; },
        processGenerateToken() {
            axios.post(`/api/admin/generate-token/${this.selectedPesertaToken.id}`).then(res => {
                this.activeToken = res.data.token;
                this.fetchPeserta(this.pagination.current_page);
            });
        },
        copyToken() {
            navigator.clipboard.writeText(this.activeToken);
            this.isCopied = true;
            setTimeout(() => { this.isCopied = false; }, 2000);
        }
    }
};
</script>