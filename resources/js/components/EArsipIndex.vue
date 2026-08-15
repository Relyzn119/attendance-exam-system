<template>
  <div class="min-h-screen bg-slate-100 p-4 md:p-6 font-sans text-slate-800">

    <!-- 1. TOP BANNER HEADER & COUNTER STATS (Gambar 5) -->
    <div class="bg-[#0b132a] text-white rounded-2xl p-6 md:p-8 shadow-xl mb-6 relative overflow-hidden">
      <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-950/80 border border-blue-500/30 rounded-full text-xs text-blue-300 font-medium mb-4">
        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 v5m-4 0h4" />
        </svg>
        Database Repository Pegawai & Dokter Resmi RSU Bunda Thamrin
      </div>

      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
        <div>
          <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white mb-2">
            Arsip Data Pegawai & Berkas Dokumen HRD
          </h1>
          <p class="text-slate-300 text-sm max-w-3xl leading-relaxed">
            Khusus penyimpanan data pegawai aktif (Dokter, Perawat, Penunjang Medis & HRD) lengkap dengan berkas PDF Ijazah, Transkrip Nilai, STR, dan SIP.
          </p>
        </div>

        <button @click="openModalTambah" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm px-5 py-3 rounded-xl shadow-lg transition-all transform active:scale-95 whitespace-nowrap self-start lg:self-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
          </svg>
          Tambah Data Pegawai Baru
        </button>
      </div>

      <!-- COUNTER STATS GRID -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-slate-900/70 border border-slate-700/60 rounded-xl p-4 text-center backdrop-blur-md">
          <span class="text-xs font-semibold tracking-wider text-slate-400 uppercase block mb-1">TOTAL DOKTER</span>
          <div class="text-2xl md:text-3xl font-extrabold text-white tracking-tight font-mono">
            {{ statistics.total_dokter || 0 }} <span class="text-lg font-bold text-slate-300">Personel</span>
          </div>
        </div>

        <div class="bg-slate-900/70 border border-slate-700/60 rounded-xl p-4 text-center backdrop-blur-md">
          <span class="text-xs font-semibold tracking-wider text-slate-400 uppercase block mb-1">TOTAL PERAWAT</span>
          <div class="text-2xl md:text-3xl font-extrabold text-white tracking-tight font-mono">
            {{ statistics.total_perawat || 0 }} <span class="text-lg font-bold text-slate-300">Personel</span>
          </div>
        </div>

        <div class="bg-slate-900/70 border border-slate-700/60 rounded-xl p-4 text-center backdrop-blur-md">
          <span class="text-xs font-semibold tracking-wider text-slate-400 uppercase block mb-1">STAF & PENUNJANG</span>
          <div class="text-2xl md:text-3xl font-extrabold text-white tracking-tight font-mono">
            {{ statistics.staf_penunjang || 0 }} <span class="text-lg font-bold text-slate-300">Personel</span>
          </div>
        </div>
      </div>
    </div>

    <!-- 2. FILTER BAR & PENCARIAN (Gambar 5 & 7) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center mb-4">
        <div class="md:col-span-5 relative">
          <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input v-model="searchQuery" @input="debounceFetch" type="text" placeholder="Cari nama, NIK, unit, atau email..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
        </div>

        <div class="md:col-span-4">
          <select v-model="selectedPeran" @change="fetchData" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all font-medium text-slate-700">
            <option value="Semua Peran / Profession">Semua Peran / Profession</option>
            <option value="Dokter Spesialis / Umum">Dokter Spesialis / Umum</option>
            <option value="Perawat / Ners">Perawat / Ners</option>
            <option value="Penunjang Medis (Farmasi/Lab)">Penunjang Medis (Farmasi/Lab)</option>
            <option value="Staf Administrasi / HRD">Staf Administrasi / HRD</option>
          </select>
        </div>

        <div class="md:col-span-3">
          <input v-model="selectedDate" @change="fetchData" type="date" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-slate-700" />
        </div>
      </div>

      <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100 text-xs">
        <span class="text-slate-500 font-medium mr-1">Filter Tanggal Cepat:</span>
        <button @click="setQuickDate('', 'Semua Tanggal')" :class="quickDateLabel === 'Semua Tanggal' ? 'bg-blue-600 text-white font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-3 py-1.5 rounded-lg transition-all">
          Semua Tanggal
        </button>
        <button @click="setQuickDate('2026-08-02', '02 Agt 2026 (Hari Ini)')" :class="quickDateLabel === '02 Agt 2026 (Hari Ini)' ? 'bg-blue-600 text-white font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-3 py-1.5 rounded-lg transition-all">
          02 Agt 2026 (Hari Ini)
        </button>
        <button @click="setQuickDate('2026-08-01', '01 Agt 2026')" :class="quickDateLabel === '01 Agt 2026' ? 'bg-blue-600 text-white font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-3 py-1.5 rounded-lg transition-all">
          01 Agt 2026
        </button>
        <button @click="setQuickDate('2026-07-28', '28 Jul 2026')" :class="quickDateLabel === '28 Jul 2026' ? 'bg-blue-600 text-white font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'" class="px-3 py-1.5 rounded-lg transition-all">
          28 Jul 2026
        </button>
      </div>
    </div>

    <!-- 3. DATA TABLE PEGAWAI (Gambar 1) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
      <div v-if="isLoading" class="p-12 text-center text-slate-400">
        <svg class="w-8 h-8 mx-auto animate-spin text-blue-600 mb-2" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Memuat data pegawai...</span>
      </div>

      <div v-else-if="pegawaiList.length === 0" class="p-12 text-center text-slate-400">
        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <p class="font-medium text-slate-600">Tidak ada data pegawai yang sesuai dengan filter.</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-slate-100 bg-slate-50/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
              <th class="py-4 px-6">NAMA PEGAWAI</th>
              <th class="py-4 px-6">KATEGORI PERAN</th>
              <th class="py-4 px-6">UNIT / DEPARTEMEN</th>
              <th class="py-4 px-6">DOKUMEN TERLAMPIR</th>
              <th class="py-4 px-6">TANGGAL UPLOAD DATA</th>
              <th class="py-4 px-6 text-center">AKSI HRD</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-sm">
            <tr v-for="item in pegawaiList" :key="item.id" class="hover:bg-blue-50/30 transition-colors">
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-sm uppercase shadow-inner">
                    {{ getInitial(item.nama_lengkap) }}
                  </div>
                  <div>
                    <div class="font-bold text-slate-900 leading-snug">{{ item.nama_lengkap }}</div>
                    <div class="text-xs text-slate-400 font-mono">NIK: {{ item.nik }}</div>
                  </div>
                </div>
              </td>
              <td class="py-4 px-6">
                <span :class="getRoleBadgeClass(item.kategori_peran)" class="inline-block px-3 py-1 rounded-full text-xs font-semibold shadow-xs">
                  {{ item.kategori_peran }}
                </span>
              </td>
              <td class="py-4 px-6 font-medium text-slate-700">{{ item.unit_departemen }}</td>
              <td class="py-4 px-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-lg text-xs font-semibold">
                  <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <span>{{ item.berkas_pegawais_count || 0 }} Berkas PDF</span>
                </div>
              </td>
              <td class="py-4 px-6 text-xs text-slate-500 font-mono">Upload: {{ item.tanggal_upload }}</td>
              <td class="py-4 px-6 text-center">
                <button @click="openModalDetail(item.id)" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-xl shadow-sm transition-all active:scale-95">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  Lihat Detail
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- 4. MODAL FORM TAMBAH PEGAWAI BARU (Gambar 6) -->
    <div v-if="showModalTambah" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-3xl overflow-hidden transform transition-all my-8">
        <div class="bg-[#0b132a] text-white p-6 flex items-center justify-between border-b border-slate-800">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600/30 border border-blue-500/40 flex items-center justify-center text-blue-400">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-bold text-white leading-tight">Tambah Data Pegawai & Lampiran HRD</h3>
              <p class="text-xs text-slate-300">Input Pegawai Resmi & Lampiran Berkas Transkrip/Ijazah/STR</p>
            </div>
          </div>
          <button @click="closeModalTambah" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-slate-800 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <form @submit.prevent="submitTambahPegawai" class="p-6 space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">NIK <span class="text-rose-500">*</span></label>
              <input v-model="formTambah.nik" required type="text" placeholder="e.g., 10928399" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
              <input v-model="formTambah.nama_lengkap" required type="text" placeholder="e.g., dr. Kevin Sanjaya, Sp.OT" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Peran / Profesi <span class="text-rose-500">*</span></label>
              <select v-model="formTambah.kategori_peran" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="Dokter Spesialis / Umum">Dokter Spesialis / Umum</option>
                <option value="Perawat / Ners">Perawat / Ners</option>
                <option value="Penunjang Medis (Farmasi/Lab)">Penunjang Medis (Farmasi/Lab)</option>
                <option value="Staf Administrasi / HRD">Staf Administrasi / HRD</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Unit / Poliklinik / Departemen <span class="text-rose-500">*</span></label>
              <input v-model="formTambah.unit_departemen" required type="text" placeholder="e.g., Poliklinik Ortopedi / IGD" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Resmi</label>
              <input v-model="formTambah.email_resmi" type="email" placeholder="e.g., kevin@bundathamrin.com" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">No. Handphone / WhatsApp</label>
              <input v-model="formTambah.no_hp" type="text" placeholder="e.g., 0812-3344-5566" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Pendidikan Terakhir</label>
              <input v-model="formTambah.pendidikan_terakhir" type="text" placeholder="e.g., Spesialis Ortopedi - FK USU" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Tanggal Upload HRD</label>
              <input v-model="formTambah.tanggal_upload" type="date" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>
          </div>

          <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 mt-4">
            <label class="block text-xs font-bold text-blue-900 uppercase tracking-wider mb-3">LAMPIRAN BERKAS PDF OTOMATIS (HRD UPLOAD):</label>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <label class="flex items-center gap-2.5 bg-white p-3 rounded-lg border border-slate-200 cursor-pointer hover:border-blue-400 transition-colors">
                <input v-model="formTambah.lampiran_ijazah" type="checkbox" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500" />
                <span class="text-xs font-semibold text-slate-700">Ijazah Profesi</span>
              </label>
              <label class="flex items-center gap-2.5 bg-white p-3 rounded-lg border border-slate-200 cursor-pointer hover:border-blue-400 transition-colors">
                <input v-model="formTambah.lampiran_transkrip" type="checkbox" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500" />
                <span class="text-xs font-semibold text-slate-700">Transkrip Nilai</span>
              </label>
              <label class="flex items-center gap-2.5 bg-white p-3 rounded-lg border border-slate-200 cursor-pointer hover:border-blue-400 transition-colors">
                <input v-model="formTambah.lampiran_str_sip" type="checkbox" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500" />
                <span class="text-xs font-semibold text-slate-700">STR / SIP Medis</span>
              </label>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="closeModalTambah" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition-all">Batal</button>
            <button type="submit" :disabled="isSubmitting" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition-all inline-flex items-center gap-2">
              <span>{{ isSubmitting ? 'Menyimpan...' : 'Simpan Data Pegawai & Dokumen' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- 5. MODAL DETAIL PEGAWAI & BERKAS (Gambar 2) -->
    <div v-if="showModalDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-slate-900/70 backdrop-blur-md">
      <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 w-full max-w-5xl overflow-hidden my-6 transform transition-all">

        <div v-if="isLoadingDetail" class="p-16 text-center text-slate-400">
          <svg class="w-10 h-10 mx-auto animate-spin text-blue-600 mb-3" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span class="text-sm font-medium">Memuat rincian arsip pegawai...</span>
        </div>

        <div v-else-if="selectedPegawai">
          <!-- Header Banner Modal Detail (Gambar 2) -->
          <div class="bg-[#0b132a] text-white p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border-b border-slate-800">
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white font-extrabold text-2xl flex items-center justify-center shadow-lg border border-blue-400/30">
                {{ getInitial(selectedPegawai.nama_lengkap) }}
              </div>
              <div>
                <div class="flex items-center gap-3 mb-1">
                  <h2 class="text-xl md:text-2xl font-black text-white leading-tight">
                    {{ selectedPegawai.nama_lengkap }}
                  </h2>
                  <span :class="getRoleBadgeClass(selectedPegawai.kategori_peran)" class="px-3 py-0.5 rounded-full text-xs font-bold shadow-xs">
                    {{ selectedPegawai.kategori_peran }}
                  </span>
                </div>
                <div class="text-xs text-slate-300 flex flex-wrap items-center gap-3 font-mono">
                  <span>NIK: {{ selectedPegawai.nik }}</span>
                  <span>•</span>
                  <span>Unit: {{ selectedPegawai.unit_departemen }}</span>
                </div>
              </div>
            </div>

            <button @click="closeModalDetail" class="text-slate-400 hover:text-white p-2 rounded-xl hover:bg-slate-800 transition-colors self-end md:self-auto">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Body Content Detail (Gambar 2) -->
          <div class="p-6 md:p-8 space-y-8 max-h-[80vh] overflow-y-auto">
            <!-- Grid 6 Informasi Utama -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">PENDIDIKAN TERAKHIR</span>
                <div class="flex items-start gap-2.5 text-slate-800 font-semibold text-xs leading-relaxed">
                  <svg class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                  </svg>
                  <span>{{ selectedPegawai.pendidikan_terakhir || '-' }}</span>
                </div>
              </div>

              <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">NO. STR (SURAT TANDA REGISTRASI)</span>
                <div class="flex items-center gap-2 text-slate-800 font-bold text-xs font-mono">
                  <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                  <span>{{ selectedPegawai.no_str || '-' }}</span>
                </div>
              </div>

              <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">NO. SIP (SURAT IZIN PRAKTIK)</span>
                <div class="flex items-center gap-2 text-slate-800 font-bold text-xs font-mono">
                  <svg class="w-4 h-4 text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <span>{{ selectedPegawai.no_sip || '-' }}</span>
                </div>
              </div>

              <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">EMAIL RESMI</span>
                <div class="flex items-center gap-2 text-slate-800 font-semibold text-xs">
                  <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  <span>{{ selectedPegawai.email_resmi || '-' }}</span>
                </div>
              </div>

              <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">KONTAK NO. HP</span>
                <div class="flex items-center gap-2 text-slate-800 font-bold text-xs font-mono">
                  <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                  <span>{{ selectedPegawai.no_hp || '-' }}</span>
                </div>
              </div>

              <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">TANGGAL UPLOAD DATA HRD</span>
                <div class="flex items-center gap-2 text-slate-800 font-semibold text-xs font-mono">
                  <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span>{{ selectedPegawai.tanggal_upload }}</span>
                </div>
              </div>
            </div>

            <!-- SEKSI DAFTAR BERKAS TERUPLOAD -->
            <div class="pt-4 border-t border-slate-100">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                  <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Dokumen Terupload HRD ({{ selectedPegawai.berkas_pegawais ? selectedPegawai.berkas_pegawais.length : 0 }})
                  </h3>
                  <p class="text-xs text-slate-400 mt-0.5">Transkrip Nilai, Ijazah Profesi, STR, SIP, KK, KTP, CV, Surat Lamaran, dan Sertifikat Terverifikasi HRD</p>
                </div>

                <button @click="toggleFormUpload" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-md transition-all active:scale-95 whitespace-nowrap self-start sm:self-auto">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                  </svg>
                  <span>{{ showFormUpload ? 'Tutup Form Upload' : '+ Upload Dokumen Baru' }}</span>
                </button>
              </div>

              <!-- 6. FORM UPLOAD DOKUMEN RESMI HRD (Gambar 3 & 4) -->
              <div v-if="showFormUpload" class="bg-blue-50/40 border border-blue-200/80 rounded-2xl p-5 mb-6 transition-all shadow-sm">
                <div class="flex items-center gap-2 font-extrabold text-blue-900 text-sm uppercase tracking-wider mb-4">
                  <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                  </svg>
                  FORM UPLOAD DOKUMEN RESMI HRD (PDF)
                </div>

                <form @submit.prevent="submitUploadDokumen" class="space-y-4">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Jenis Dokumen <span class="text-rose-500">*</span></label>
                      <select v-model="formUpload.jenis_berkas" required class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="Transkrip Nilai Akademik">Transkrip Nilai Akademik</option>
                        <option value="Ijazah Profesi / Gelar">Ijazah Profesi / Gelar</option>
                        <option value="STR (Surat Tanda Registrasi)">STR (Surat Tanda Registrasi)</option>
                        <option value="SIP (Surat Izin Praktik)">SIP (Surat Izin Praktik)</option>
                        <option value="Sertifikat Pelatihan / Diklat">Sertifikat Pelatihan / Diklat</option>
                        <option value="Kartu Keluarga (KK)">Kartu Keluarga (KK)</option>
                        <option value="KTP (Kartu Tanda Penduduk)">KTP (Kartu Tanda Penduduk)</option>
                        <option value="CV (Curriculum Vitae)">CV (Curriculum Vitae)</option>
                        <option value="Surat Lamaran">Surat Lamaran</option>
                        <option value="Dokumen Lainnya">Dokumen Lainnya</option>
                      </select>
                    </div>

                    <div>
                      <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Judul Dokumen <span class="text-rose-500">*</span></label>
                      <input v-model="formUpload.judul_dokumen" required type="text" placeholder="e.g., Transkrip Nilai S1 Keperawatan / Kartu Keluarga" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                    </div>

                    <div>
                      <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Pilih File PDF <span class="text-rose-500">*</span></label>
                      <input @change="handleFileChange" required type="file" accept="application/pdf" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    </div>

                    <div>
                      <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Catatan HRD (Opsional)</label>
                      <input v-model="formUpload.catatan_hrd" type="text" placeholder="e.g., Terverifikasi Asli Kemenkes / Dukcapil" class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                    </div>
                  </div>

                  <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="toggleFormUpload" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition-all">Batal</button>
                    <button type="submit" :disabled="isUploading" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-300 text-white font-bold text-xs rounded-xl shadow-md transition-all inline-flex items-center gap-2">
                      <svg v-if="isUploading" class="w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <span>{{ isUploading ? 'Mengunggah PDF...' : 'Simpan & Verifikasi PDF' }}</span>
                    </button>
                  </div>
                </form>
              </div>

              <!-- Grid Cards Dokumen PDF (Gambar 2) -->
              <div v-if="selectedPegawai.berkas_pegawais && selectedPegawai.berkas_pegawais.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="berkas in selectedPegawai.berkas_pegawais" :key="berkas.id" class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-blue-300 transition-all shadow-xs flex flex-col justify-between">
                  <div>
                    <div class="flex items-start justify-between gap-2 mb-3">
                      <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0 border border-rose-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                      </div>

                      <div class="flex flex-col items-end gap-1">
                        <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 font-bold text-[10px] uppercase rounded-md tracking-wider">
                          {{ berkas.jenis_berkas }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                          <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                          </svg>
                          Valid
                        </span>
                      </div>
                    </div>

                    <h4 class="font-bold text-slate-900 text-sm leading-snug mb-1">
                      {{ berkas.judul_dokumen }}
                    </h4>
                    <p class="text-xs text-slate-400 font-mono mb-1 truncate">
                      {{ berkas.nama_file }}
                    </p>
                    <div class="text-[11px] text-slate-400 font-mono mb-4">
                      Ukuran: {{ berkas.file_size || '1.5 MB' }} • Upload: {{ berkas.tanggal_upload }}
                    </div>
                  </div>

                  <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-100">
                    <button @click="viewPdf(berkas.file_path)" class="w-full py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold text-xs rounded-xl transition-all inline-flex items-center justify-center gap-1.5">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                      Lihat PDF
                    </button>

                    <button @click="downloadPdf(berkas.file_path, berkas.nama_file)" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all inline-flex items-center justify-center gap-1.5">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                      </svg>
                      Unduh
                    </button>
                  </div>
                </div>
              </div>

              <div v-else class="p-8 text-center text-slate-400 bg-slate-50 border border-slate-100 rounded-2xl">
                Belum ada dokumen PDF yang diunggah untuk pegawai ini.
              </div>
            </div>
          </div>

          <!-- Footer Disclaimer (Gambar 2) -->
          <div class="bg-slate-50 p-6 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100">
            <span class="text-xs text-slate-500 italic text-center sm:text-left">
              * Data terenkripsi dan tersimpan di database resmi RSU Bunda Thamrin
            </span>
            <button @click="closeModalDetail" class="px-8 py-2.5 bg-[#1b253e] hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-md transition-all">
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

// States
const statistics = ref({ total_dokter: 0, total_perawat: 0, staf_penunjang: 0 });
const pegawaiList = ref([]);
const isLoading = ref(false);

// Filter States
const searchQuery = ref('');
const selectedPeran = ref('Semua Peran / Profession');
const selectedDate = ref('');
const quickDateLabel = ref('Semua Tanggal');

// Modal Tambah State (Gambar 6)
const showModalTambah = ref(false);
const isSubmitting = ref(false);
const formTambah = ref({
    nik: '',
    nama_lengkap: '',
    kategori_peran: 'Dokter Spesialis / Umum',
    unit_departemen: '',
    email_resmi: '',
    no_hp: '',
    pendidikan_terakhir: '',
    tanggal_upload: new Date().toISOString().split('T')[0],
    lampiran_ijazah: true,
    lampiran_transkrip: true,
    lampiran_str_sip: true,
});

// Modal Detail State (Gambar 2)
const showModalDetail = ref(false);
const isLoadingDetail = ref(false);
const selectedPegawai = ref(null);

// Modal Upload State (Gambar 3 & 4)
const showFormUpload = ref(false);
const isUploading = ref(false);
const formUpload = ref({
    jenis_berkas: 'Transkrip Nilai Akademik',
    judul_dokumen: '',
    catatan_hrd: '',
    file_pdf: null,
});

let debounceTimer = null;

// Fetch Data dengan Filter
const fetchData = async () => {
    isLoading.value = true;
    try {
        const params = {};
        if (searchQuery.value) params.search = searchQuery.value;
        if (selectedPeran.value && selectedPeran.value !== 'Semua Peran / Profession') {
            params.kategori_peran = selectedPeran.value;
        }
        if (selectedDate.value) params.tanggal_upload = selectedDate.value;

        const response = await axios.get('/api/admin/pegawai', { params });
        if (response.data.success) {
            statistics.value = response.data.statistics;
            pegawaiList.value = response.data.data;
        }
    } catch (error) {
        console.error('Gagal mengambil data pegawai:', error);
    } finally {
        isLoading.value = false;
    }
};

const debounceFetch = () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        fetchData();
    }, 400);
};

const setQuickDate = (dateVal, label) => {
    selectedDate.value = dateVal;
    quickDateLabel.value = label;
    fetchData();
};

// Modal Tambah Handlers
const openModalTambah = () => {
    formTambah.value = {
        nik: '',
        nama_lengkap: '',
        kategori_peran: 'Dokter Spesialis / Umum',
        unit_departemen: '',
        email_resmi: '',
        no_hp: '',
        pendidikan_terakhir: '',
        tanggal_upload: new Date().toISOString().split('T')[0],
        lampiran_ijazah: true,
        lampiran_transkrip: true,
        lampiran_str_sip: true,
    };
    showModalTambah.value = true;
};

const closeModalTambah = () => {
    showModalTambah.value = false;
};

const submitTambahPegawai = async () => {
    isSubmitting.value = true;
    try {
        const response = await axios.post('/api/admin/pegawai', formTambah.value);
        if (response.data.success) {
            alert('Data Pegawai Baru & Dokumen Lampiran berhasil disimpan!');
            closeModalTambah();
            fetchData();
        }
    } catch (error) {
        const msg = error.response?.data?.message || 'Gagal menyimpan data pegawai';
        alert('Error: ' + msg);
    } finally {
        isSubmitting.value = false;
    }
};

// Modal Detail Handlers (Gambar 2)
const openModalDetail = async (id) => {
    showModalDetail.value = true;
    isLoadingDetail.value = true;
    showFormUpload.value = false;
    try {
        const response = await axios.get('/api/admin/pegawai/' + id);
        if (response.data.success) {
            selectedPegawai.value = response.data.data;
        }
    } catch  {
        alert('Gagal memuat detail pegawai');
        showModalDetail.value = false;
    } finally {
        isLoadingDetail.value = false;
    }
};

const closeModalDetail = () => {
    showModalDetail.value = false;
    selectedPegawai.value = null;
    showFormUpload.value = false;
};

// Form Upload Dokumen Handlers (Gambar 3 & 4)
const toggleFormUpload = () => {
    showFormUpload.value = !showFormUpload.value;
    if (showFormUpload.value) {
        formUpload.value = {
            jenis_berkas: 'Transkrip Nilai Akademik',
            judul_dokumen: '',
            catatan_hrd: '',
            file_pdf: null,
        };
    }
};

const handleFileChange = (e) => {
    if (e.target.files && e.target.files[0]) {
        formUpload.value.file_pdf = e.target.files[0];
    }
};

const submitUploadDokumen = async () => {
    if (!formUpload.value.file_pdf) {
        return alert('Harap pilih file PDF terlebih dahulu!');
    }

    isUploading.value = true;
    const formData = new FormData();
    formData.append('jenis_berkas', formUpload.value.jenis_berkas);
    formData.append('judul_dokumen', formUpload.value.judul_dokumen);
    formData.append('catatan_hrd', formUpload.value.catatan_hrd || '');
    formData.append('file_pdf', formUpload.value.file_pdf);

    try {
        const response = await axios.post(
            `/api/admin/pegawai/${selectedPegawai.value.id}/upload-berkas`,
            formData,
            { headers: { 'Content-Type': 'multipart/form-data' } }
        );

        if (response.data.success) {
            alert('Dokumen PDF berhasil diupload & diverifikasi!');
            showFormUpload.value = false;
            openModalDetail(selectedPegawai.value.id);
            fetchData();
        }
    } catch (error) {
        const msg = error.response?.data?.message || 'Gagal mengupload dokumen PDF';
        alert('Error Upload: ' + msg);
    } finally {
        isUploading.value = false;
    }
};

// Helpers Action PDF
const viewPdf = (filePath) => {
    if (!filePath) return alert('File PDF tidak ditemukan');
    const url = filePath.startsWith('http') || filePath.startsWith('/') ? filePath : '/' + filePath;
    window.open(url, '_blank');
};

const downloadPdf = (filePath, fileName) => {
    if (!filePath) return alert('File PDF tidak ditemukan');
    const url = filePath.startsWith('http') || filePath.startsWith('/') ? filePath : '/' + filePath;
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', fileName || 'dokumen.pdf');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

// Helpers UI
const getInitial = (name) => {
    if (!name) return 'P';
    const cleanName = name.replace(/dr\.|Sp\.[A-Z]+|S\.Kep|S\.Farm|Apt|Ns\.|S\.E\./gi, '').trim();
    return cleanName.charAt(0) || 'P';
};

const getRoleBadgeClass = (role) => {
    switch (role) {
        case 'Dokter':
            return 'bg-blue-50 text-blue-600 border border-blue-200';
        case 'Perawat':
            return 'bg-emerald-50 text-emerald-600 border border-emerald-200';
        case 'Penunjang Medis':
            return 'bg-amber-50 text-amber-700 border border-amber-200';
        case 'Staf Administrasi':
            return 'bg-slate-100 text-slate-700 border border-slate-200';
        default:
            return 'bg-slate-100 text-slate-600';
    }
};

onMounted(() => {
    fetchData();
});
</script>

<style scoped>
/* Melindungi ikon SVG dari pembesaran liar akibat ketiadaan style Tailwind */
svg {
  display: inline-block;
  vertical-align: middle;
  flex-shrink: 0;
}

/* Memperjelas ukuran ikon standar */
.w-4 { width: 1rem !important; height: 1rem !important; }
.w-5 { width: 1.25rem !important; height: 1.25rem !important; }
.w-6 { width: 1.5rem !important; height: 1.5rem !important; }
.w-8 { width: 2rem !important; height: 2rem !important; }
.w-10 { width: 2.5rem !important; height: 2.5rem !important; }
.w-12 { width: 3rem !important; height: 3rem !important; }
.w-14 { width: 3.5rem !important; height: 3.5rem !important; }

/* Menyelaraskan tabel jika terkena style reset dari Bootstrap */
table {
  width: 100%;
  border-collapse: collapse;
}

input, select {
  box-sizing: border-box;
}
</style>