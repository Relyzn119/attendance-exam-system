<template>
  <div class="w-full text-slate-100 font-sans pb-12">

    <!-- 1. HERO HEADER & FLOATING TICKER STATS -->
    <div class="relative mb-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
        
        <!-- BAGIAN KIRI: HERO TYPOGRAPHY BOLD & CTA BUTTONS -->
        <div class="lg:col-span-7 space-y-5">
          <!-- Tag Badge Pill -->
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-blue-500/10 border border-blue-400/30 rounded-full text-xs text-blue-300 font-semibold backdrop-blur-md shadow-lg">
            <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
            <span>Database Repository & HRD System • RSU Bunda Thamrin</span>
          </div>

          <!-- Headline Utama -->
          <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white uppercase leading-[1.08] drop-shadow-md">
            DATA PEGAWAI & ARSIP DOKUMEN <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-sky-300 to-emerald-400">TERINTEGRASI</span>
          </h1>

          <!-- Deskripsi -->
          <p class="text-slate-300 text-sm sm:text-base max-w-2xl leading-relaxed font-normal">
            Pusat penyimpanan berkas digital resmi pegawai RSU Bunda Thamrin (Dokter, Perawat, Penunjang Medis & HRD) terenkripsi lengkap dengan lampiran PDF Ijazah, STR, SIP, dan Sertifikat.
          </p>

          <!-- CTA Buttons -->
          <div class="flex flex-wrap items-center gap-3 pt-2">
            <button 
              @click="openModalTambah"
              class="inline-flex items-center gap-2.5 bg-white hover:bg-slate-100 text-slate-950 font-extrabold text-xs sm:text-sm px-6 py-3.5 rounded-full shadow-xl transition-all transform active:scale-95 group"
            >
              <i class="bi bi-plus-circle-fill text-blue-600 text-base group-hover:rotate-90 transition-transform"></i>
              <span>Tambah Data Pegawai</span>
            </button>

            <a 
              href="#tabel-pegawai"
              class="inline-flex items-center gap-2 bg-slate-900/60 hover:bg-slate-800/80 text-white font-bold text-xs sm:text-sm px-6 py-3.5 rounded-full border border-white/20 backdrop-blur-md transition-all active:scale-95"
            >
              <i class="bi bi-search text-slate-300"></i>
              <span>Eksplor Data Arsip</span>
            </a>
          </div>
        </div>

        <!-- BAGIAN KANAN: FLOATING GLASS TICKER WIDGETS -->
        <div class="lg:col-span-5 space-y-3">
          
          <!-- Widget 1: TOTAL DOKTER -->
          <div class="bg-slate-900/60 hover:bg-slate-900/80 border border-white/15 rounded-2xl p-4 backdrop-blur-xl shadow-2xl transition-all hover:border-blue-400/40 group">
            <div class="flex items-center justify-between mb-1">
              <div class="flex items-center gap-2">
                <span class="text-xs font-mono font-bold text-blue-400 bg-blue-950/80 border border-blue-500/30 px-2 py-0.5 rounded">DOC/MED</span>
                <span class="text-xs font-bold text-slate-300 uppercase tracking-wider">TOTAL DOKTER</span>
              </div>
              <span class="text-xs font-bold text-emerald-400 bg-emerald-950/60 border border-emerald-500/30 px-2 py-0.5 rounded-full flex items-center gap-1">
                <i class="bi bi-arrow-up-short"></i> +1.2% Active
              </span>
            </div>
            
            <div class="flex items-end justify-between">
              <div>
                <div class="text-2xl sm:text-3xl font-black text-white font-mono tracking-tight">
                  {{ statistics.total_dokter || 0 }} <span class="text-xs font-sans text-slate-400 font-semibold">Personel</span>
                </div>
                <div class="text-[11px] text-slate-400">Dokter Spesialis & Umum Resmi</div>
              </div>
              <svg class="w-24 h-10 text-blue-400 opacity-80 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 100 30">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M0 25 Q25 5, 50 18 T100 8" />
              </svg>
            </div>
          </div>

          <!-- Widget 2: TOTAL PERAWAT -->
          <div class="bg-slate-900/60 hover:bg-slate-900/80 border border-white/15 rounded-2xl p-4 backdrop-blur-xl shadow-2xl transition-all hover:border-emerald-400/40 group">
            <div class="flex items-center justify-between mb-1">
              <div class="flex items-center gap-2">
                <span class="text-xs font-mono font-bold text-emerald-400 bg-emerald-950/80 border border-emerald-500/30 px-2 py-0.5 rounded">NRS/NURSE</span>
                <span class="text-xs font-bold text-slate-300 uppercase tracking-wider">TOTAL PERAWAT</span>
              </div>
              <span class="text-xs font-bold text-emerald-400 bg-emerald-950/60 border border-emerald-500/30 px-2 py-0.5 rounded-full flex items-center gap-1">
                <i class="bi bi-arrow-up-short"></i> +2.4% Verified
              </span>
            </div>
            
            <div class="flex items-end justify-between">
              <div>
                <div class="text-2xl sm:text-3xl font-black text-white font-mono tracking-tight">
                  {{ statistics.total_perawat || 0 }} <span class="text-xs font-sans text-slate-400 font-semibold">Personel</span>
                </div>
                <div class="text-[11px] text-slate-400">Perawat & Ners Terverifikasi STR</div>
              </div>
              <svg class="w-24 h-10 text-emerald-400 opacity-80 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 100 30">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M0 20 Q20 28, 45 10 T100 5" />
              </svg>
            </div>
          </div>

          <!-- Widget 3: STAF & PENUNJANG -->
          <div class="bg-slate-900/60 hover:bg-slate-900/80 border border-white/15 rounded-2xl p-4 backdrop-blur-xl shadow-2xl transition-all hover:border-purple-400/40 group">
            <div class="flex items-center justify-between mb-1">
              <div class="flex items-center gap-2">
                <span class="text-xs font-mono font-bold text-purple-400 bg-purple-950/80 border border-purple-500/30 px-2 py-0.5 rounded">STF/SUPP</span>
                <span class="text-xs font-bold text-slate-300 uppercase tracking-wider">STAF & PENUNJANG</span>
              </div>
              <span class="text-xs font-bold text-sky-400 bg-sky-950/60 border border-sky-500/30 px-2 py-0.5 rounded-full flex items-center gap-1">
                <i class="bi bi-dash"></i> Stable
              </span>
            </div>
            
            <div class="flex items-end justify-between">
              <div>
                <div class="text-2xl sm:text-3xl font-black text-white font-mono tracking-tight">
                  {{ statistics.staf_penunjang || 0 }} <span class="text-xs font-sans text-slate-400 font-semibold">Personel</span>
                </div>
                <div class="text-[11px] text-slate-400">Penunjang Medis & Staf Administrasi</div>
              </div>
              <svg class="w-24 h-10 text-purple-400 opacity-80 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 100 30">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M0 15 Q35 2, 70 20 T100 12" />
              </svg>
            </div>
          </div>

        </div>

      </div>
    </div>

    <!-- 2. FILTER BAR & PENCARIAN -->
    <div id="tabel-pegawai" class="bg-slate-900/60 border border-white/15 rounded-3xl p-5 mb-6 backdrop-blur-xl shadow-2xl">
      <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center mb-4">
        
        <!-- Search Input -->
        <div class="md:col-span-5 relative">
          <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
            <i class="bi bi-search text-base"></i>
          </div>
          <input 
            v-model="searchQuery" 
            @input="debounceFetch" 
            type="text"
            placeholder="Cari nama, NIK, unit, atau email..."
            class="w-full pl-10 pr-4 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
          />
        </div>

        <!-- Filter Peran -->
        <div class="md:col-span-4">
          <select 
            v-model="selectedPeran" 
            @change="fetchData"
            class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm font-medium text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
          >
            <option class="bg-slate-900 text-white" value="Semua Peran / Profession">Semua Peran / Profession</option>
            <option class="bg-slate-900 text-white" value="Dokter Spesialis / Umum">Dokter Spesialis / Umum</option>
            <option class="bg-slate-900 text-white" value="Perawat / Ners">Perawat / Ners</option>
            <option class="bg-slate-900 text-white" value="Penunjang Medis (Farmasi/Lab)">Penunjang Medis (Farmasi/Lab)</option>
            <option class="bg-slate-900 text-white" value="Staf Administrasi / HRD">Staf Administrasi / HRD</option>
          </select>
        </div>

        <!-- Date Picker -->
        <div class="md:col-span-3">
          <input 
            v-model="selectedDate" 
            @change="fetchData" 
            type="date"
            class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
          />
        </div>
      </div>

      <!-- Quick Date Filter Pills -->
      <div class="flex flex-wrap items-center gap-2 pt-3 border-t border-white/10 text-xs">
        <span class="text-slate-400 font-medium mr-1 flex items-center gap-1">
          <i class="bi bi-funnel"></i> Quick Date:
        </span>
        
        <button 
          @click="setQuickDate('', 'Semua Tanggal')"
          :class="quickDateLabel === 'Semua Tanggal' ? 'bg-blue-600 text-white font-bold border-blue-400' : 'bg-slate-950/50 text-slate-300 border-white/10 hover:bg-white/10'"
          class="px-3 py-1.5 rounded-full border transition-all"
        >
          Semua Tanggal
        </button>

        <button 
          @click="setQuickDate('2026-08-02', '02 Agt 2026 (Hari Ini)')"
          :class="quickDateLabel === '02 Agt 2026 (Hari Ini)' ? 'bg-blue-600 text-white font-bold border-blue-400' : 'bg-slate-950/50 text-slate-300 border-white/10 hover:bg-white/10'"
          class="px-3 py-1.5 rounded-full border transition-all"
        >
          Hari Ini
        </button>

        <button 
          @click="setQuickDate('2026-08-01', '01 Agt 2026')"
          :class="quickDateLabel === '01 Agt 2026' ? 'bg-blue-600 text-white font-bold border-blue-400' : 'bg-slate-950/50 text-slate-300 border-white/10 hover:bg-white/10'"
          class="px-3 py-1.5 rounded-full border transition-all"
        >
          01 Agt 2026
        </button>
      </div>
    </div>

    <!-- 3. DATA TABLE PEGAWAI -->
    <div class="bg-slate-900/60 border border-white/15 rounded-3xl overflow-hidden backdrop-blur-xl shadow-2xl">
      
      <!-- LOADING STATE -->
      <div v-if="isLoading" class="p-16 text-center text-slate-400">
        <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent mb-3"></div>
        <p class="font-medium text-sm">Memuat database pegawai & dokumen...</p>
      </div>

      <!-- EMPTY STATE -->
      <div v-else-if="pegawaiList.length === 0" class="p-16 text-center text-slate-400">
        <i class="bi bi-inbox text-5xl text-slate-500 mb-3 block"></i>
        <p class="font-medium text-slate-300">Tidak ada data pegawai yang sesuai dengan filter.</p>
      </div>

      <!-- TABLE CONTENT -->
      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-white/10 bg-slate-950/50 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
              <th class="py-4 px-6">NAMA PEGAWAI</th>
              <th class="py-4 px-6">KATEGORI PERAN</th>
              <th class="py-4 px-6">UNIT / DEPARTEMEN</th>
              <th class="py-4 px-6">BERKAS TERLAMPIR</th>
              <th class="py-4 px-6">TGL UPLOAD</th>
              <th class="py-4 px-6 text-center">AKSI HRD</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-white/5 text-sm">
            <tr 
              v-for="item in pegawaiList" 
              :key="item.id" 
              class="hover:bg-white/5 transition-colors group"
            >
              <!-- NAMA & AVATAR -->
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-extrabold flex items-center justify-center text-sm uppercase shadow-md border border-white/20 shrink-0">
                    {{ getInitial(item.nama_lengkap) }}
                  </div>
                  <div>
                    <div class="font-bold text-white leading-snug group-hover:text-blue-300 transition-colors">
                      {{ item.nama_lengkap }}
                    </div>
                    <div class="text-xs text-slate-400 font-mono">NIK: {{ item.nik || '-' }}</div>
                  </div>
                </div>
              </td>

              <!-- KATEGORI PERAN BADGE -->
              <td class="py-4 px-6">
                <span 
                  :class="getRoleBadgeClass(item.kategori_peran)"
                  class="inline-block px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-md shadow-xs"
                >
                  {{ item.kategori_peran }}
                </span>
              </td>

              <!-- UNIT / DEPARTEMEN -->
              <td class="py-4 px-6 font-medium text-slate-200">
                {{ item.unit_departemen || '-' }}
              </td>

              <!-- BERKAS TERLAMPIR BADGE -->
              <td class="py-4 px-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-500/10 text-blue-300 border border-blue-500/30 rounded-lg text-xs font-semibold backdrop-blur-md">
                  <i class="bi bi-file-earmark-pdf text-blue-400"></i>
                  <span>{{ item.berkas_pegawais_count || 0 }} Berkas PDF</span>
                </div>
              </td>

              <!-- TANGGAL UPLOAD -->
              <td class="py-4 px-6 text-xs text-slate-400 font-mono">
                {{ item.tanggal_upload }}
              </td>

              <!-- AKSI HRD BUTTONS -->
              <td class="py-4 px-6 text-center">
                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                  <button 
                    @click="openModalDetail(item.id)" 
                    title="Lihat Detail Read-Only"
                    class="inline-flex items-center gap-1 bg-blue-600/80 hover:bg-blue-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-md transition-all active:scale-95 whitespace-nowrap"
                  >
                    <i class="bi bi-eye"></i> Detail
                  </button>

                  <button 
                    @click="openModalEdit(item)" 
                    title="Edit Profil & Kelola Berkas"
                    class="inline-flex items-center gap-1 bg-amber-500/80 hover:bg-amber-500 text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-md transition-all active:scale-95 whitespace-nowrap"
                  >
                    <i class="bi bi-pencil"></i> Edit
                  </button>

                  <button 
                    @click="confirmHapusPegawai(item)" 
                    title="Hapus Permanen Data Pegawai"
                    class="inline-flex items-center gap-1 bg-rose-600/80 hover:bg-rose-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-md transition-all active:scale-95 whitespace-nowrap"
                  >
                    <i class="bi bi-trash"></i> Hapus
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- 4. MODAL FORM TAMBAH PEGAWAI BARU -->
    <div v-if="showModalTambah" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-slate-950/80 backdrop-blur-md">
      <div class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden my-8 max-h-[90vh] flex flex-col text-slate-100">
        
        <!-- Header Modal -->
        <div class="bg-slate-950/80 p-6 flex items-center justify-between border-b border-white/10 shrink-0">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600/30 border border-blue-500/40 flex items-center justify-center text-blue-400">
              <i class="bi bi-person-plus-fill text-xl"></i>
            </div>
            <div>
              <h3 class="text-lg font-bold text-white leading-tight">Tambah Data Pegawai & Lampiran HRD</h3>
              <p class="text-xs text-slate-400">Input Pegawai Resmi & Lampiran Berkas Dokumen PDF</p>
            </div>
          </div>
          <button @click="closeModalTambah" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors">
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>

        <!-- Form Body -->
        <form @submit.prevent="submitTambahPegawai" class="p-6 space-y-5 overflow-y-auto flex-grow">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">NIK (Nomor Induk Karyawan)</label>
              <input v-model="formTambah.nik" type="text" placeholder="e.g., 10928399" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Nama Lengkap & Gelar <span class="text-rose-400">*</span></label>
              <input v-model="formTambah.nama_lengkap" required type="text" placeholder="e.g., dr. Kevin Sanjaya, Sp.OT" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Peran / Profesi <span class="text-rose-400">*</span></label>
              <select v-model="formTambah.kategori_peran" required class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option class="bg-slate-900" value="Dokter Spesialis / Umum">Dokter Spesialis / Umum</option>
                <option class="bg-slate-900" value="Perawat / Ners">Perawat / Ners</option>
                <option class="bg-slate-900" value="Penunjang Medis (Farmasi/Lab)">Penunjang Medis (Farmasi/Lab)</option>
                <option class="bg-slate-900" value="Staf Administrasi / HRD">Staf Administrasi / HRD</option>
                <option class="bg-slate-900" value="Pegawai Lainnya">Pegawai Lainnya (Isi Sendiri...)</option>
              </select>

              <div v-if="formTambah.kategori_peran === 'Pegawai Lainnya'" class="mt-2">
                <input v-model="formTambah.kategori_peran_custom" required type="text" placeholder="Ketik Peran Kustom..." class="w-full px-3.5 py-2 bg-amber-950/40 border border-amber-500/40 rounded-xl text-xs font-semibold text-white focus:outline-none" />
              </div>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Unit / Departemen</label>
              <input v-model="formTambah.unit_departemen" type="text" placeholder="e.g., Poliklinik Ortopedi / IGD" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Email Resmi</label>
              <input v-model="formTambah.email_resmi" type="email" placeholder="e.g., kevin@bundathamrin.com" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">No. HP / WhatsApp</label>
              <input v-model="formTambah.no_hp" type="text" placeholder="e.g., 0812-3344-5566" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Pendidikan Terakhir</label>
              <input v-model="formTambah.pendidikan_terakhir" type="text" placeholder="e.g., Spesialis Ortopedi - FK USU" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Tanggal Upload HRD</label>
              <input v-model="formTambah.tanggal_upload" type="date" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
          </div>

          <!-- UPLOAD DOKUMEN SECTION (9 INPUT BERKAS PDF) -->
          <div class="bg-slate-950/60 border border-white/10 rounded-2xl p-4 mt-4">
            <div class="flex items-center justify-between mb-3">
              <label class="block text-xs font-bold text-blue-400 uppercase tracking-wider">
                UPLOAD DOKUMEN BERKAS PDF (OPSIONAL):
              </label>
              <span class="text-[11px] text-slate-400 italic">Format file: PDF (Max 10MB)</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">1. KTP (Kartu Tanda Penduduk)</label>
                <input @change="handleFileTambah($event, 'file_ktp')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">2. Kartu Keluarga (KK)</label>
                <input @change="handleFileTambah($event, 'file_kk')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">3. CV (Curriculum Vitae)</label>
                <input @change="handleFileTambah($event, 'file_cv')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">4. Surat Lamaran</label>
                <input @change="handleFileTambah($event, 'file_lamaran')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">5. Ijazah Profesi / Gelar</label>
                <input @change="handleFileTambah($event, 'file_ijazah')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">6. Transkrip Nilai Akademik</label>
                <input @change="handleFileTambah($event, 'file_transkrip')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">7. STR (Surat Tanda Registrasi)</label>
                <input @change="handleFileTambah($event, 'file_str')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">8. SIP (Surat Izin Praktik)</label>
                <input @change="handleFileTambah($event, 'file_sip')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10 md:col-span-2">
                <label class="block font-bold text-slate-300 mb-1">9. Dokumen Lainnya (Sertifikat / Berkas Tambahan)</label>
                <input @change="handleFileTambah($event, 'file_lainnya')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50" />
              </div>
            </div>
          </div>

          <!-- Action Footer -->
          <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10 shrink-0">
            <button type="button" @click="closeModalTambah" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl transition-all">Batal</button>
            <button type="submit" :disabled="isSubmitting" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-500 disabled:bg-blue-800 text-white text-xs font-bold rounded-xl shadow-lg transition-all inline-flex items-center gap-2">
              <span v-if="isSubmitting" class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></span>
              <span>{{ isSubmitting ? 'Menyimpan...' : 'Simpan Pegawai & Dokumen' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- 5. MODAL EDIT PEGAWAI & KELOLA BERKAS (TETAP LENGKAP DENGAN 9 UPLOAD DOKUMEN BARU) -->
    <div v-if="showModalEdit" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-slate-950/80 backdrop-blur-md">
      <div class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden my-8 max-h-[90vh] flex flex-col text-slate-100">
        
        <div class="bg-amber-950/60 p-6 flex items-center justify-between border-b border-amber-500/30 shrink-0">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-600/30 border border-amber-500/40 flex items-center justify-center text-amber-400">
              <i class="bi bi-pencil-square text-xl"></i>
            </div>
            <div>
              <h3 class="text-lg font-bold text-white leading-tight">Edit Data Pegawai & Upload Dokumen Baru</h3>
              <p class="text-xs text-amber-200">Perbarui Profil Pegawai & Kelola Lampiran Berkas PDF</p>
            </div>
          </div>
          <button @click="closeModalEdit" class="text-amber-200 hover:text-white p-1 rounded-lg hover:bg-amber-900/50 transition-colors">
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>

        <form @submit.prevent="submitEditPegawai" class="p-6 space-y-5 overflow-y-auto flex-grow">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">NIK</label>
              <input v-model="formEdit.nik" type="text" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Nama Lengkap & Gelar *</label>
              <input v-model="formEdit.nama_lengkap" required type="text" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Peran / Profesi *</label>
              <select v-model="formEdit.kategori_peran" required class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                <option class="bg-slate-900" value="Dokter Spesialis / Umum">Dokter Spesialis / Umum</option>
                <option class="bg-slate-900" value="Perawat / Ners">Perawat / Ners</option>
                <option class="bg-slate-900" value="Penunjang Medis (Farmasi/Lab)">Penunjang Medis (Farmasi/Lab)</option>
                <option class="bg-slate-900" value="Staf Administrasi / HRD">Staf Administrasi / HRD</option>
                <option class="bg-slate-900" value="Pegawai Lainnya">Pegawai Lainnya (Isi Sendiri...)</option>
              </select>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Unit / Departemen</label>
              <input v-model="formEdit.unit_departemen" type="text" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Email Resmi</label>
              <input v-model="formEdit.email_resmi" type="email" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">No. HP / WhatsApp</label>
              <input v-model="formEdit.no_hp" type="text" class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
            </div>
          </div>

          <!-- BERKAS EXISTING -->
          <div v-if="berkasExisting && berkasExisting.length > 0" class="bg-slate-950/60 border border-white/10 rounded-2xl p-4 mt-4">
            <label class="block text-xs font-bold text-amber-400 uppercase tracking-wider mb-3">
              DOKUMEN PDF TERUNGGAH SAAT INI:
            </label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div v-for="berkas in berkasExisting" :key="berkas.id" class="bg-slate-900 p-3 rounded-xl border border-white/10 flex items-center justify-between gap-2">
                <div class="truncate">
                  <div class="font-bold text-white text-xs truncate">{{ berkas.judul_dokumen }}</div>
                  <div class="text-[11px] text-slate-400 font-mono truncate">{{ berkas.jenis_berkas }} • {{ berkas.file_size }}</div>
                </div>
                <button type="button" @click="deleteBerkasIndividual(berkas.id)" class="px-2.5 py-1 bg-rose-500/20 hover:bg-rose-500/40 text-rose-300 font-bold text-xs rounded-lg transition-colors whitespace-nowrap">
                  Hapus
                </button>
              </div>
            </div>
          </div>

          <!-- UPLOAD 9 DOKUMEN BARU / REVISI DOKUMEN PADA EDIT MODAL -->
          <div class="bg-slate-950/60 border border-white/10 rounded-2xl p-4 mt-4">
            <div class="flex items-center justify-between mb-3">
              <label class="block text-xs font-bold text-amber-400 uppercase tracking-wider">
                UPLOAD / REVISI DOKUMEN BERKAS PDF BARU (OPSIONAL):
              </label>
              <span class="text-[11px] text-slate-400 italic">Format file: PDF (Max 10MB)</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">1. KTP (Kartu Tanda Penduduk)</label>
                <input @change="handleFileEdit($event, 'file_ktp')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-amber-600/30 file:text-amber-300 hover:file:bg-amber-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">2. Kartu Keluarga (KK)</label>
                <input @change="handleFileEdit($event, 'file_kk')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-amber-600/30 file:text-amber-300 hover:file:bg-amber-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">3. CV (Curriculum Vitae)</label>
                <input @change="handleFileEdit($event, 'file_cv')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-amber-600/30 file:text-amber-300 hover:file:bg-amber-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">4. Surat Lamaran</label>
                <input @change="handleFileEdit($event, 'file_lamaran')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-amber-600/30 file:text-amber-300 hover:file:bg-amber-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">5. Ijazah Profesi / Gelar</label>
                <input @change="handleFileEdit($event, 'file_ijazah')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-amber-600/30 file:text-amber-300 hover:file:bg-amber-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">6. Transkrip Nilai Akademik</label>
                <input @change="handleFileEdit($event, 'file_transkrip')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-amber-600/30 file:text-amber-300 hover:file:bg-amber-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">7. STR (Surat Tanda Registrasi)</label>
                <input @change="handleFileEdit($event, 'file_str')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-amber-600/30 file:text-amber-300 hover:file:bg-amber-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10">
                <label class="block font-bold text-slate-300 mb-1">8. SIP (Surat Izin Praktik)</label>
                <input @change="handleFileEdit($event, 'file_sip')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-amber-600/30 file:text-amber-300 hover:file:bg-amber-600/50" />
              </div>

              <div class="bg-slate-900 p-2.5 rounded-xl border border-white/10 md:col-span-2">
                <label class="block font-bold text-slate-300 mb-1">9. Dokumen Lainnya (Sertifikat / Berkas Tambahan)</label>
                <input @change="handleFileEdit($event, 'file_lainnya')" type="file" accept="application/pdf" class="w-full text-xs text-slate-400 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-amber-600/30 file:text-amber-300 hover:file:bg-amber-600/50" />
              </div>
            </div>
          </div>

          <!-- Action Footer -->
          <div class="flex items-center justify-end gap-3 pt-4 border-t border-white/10 shrink-0">
            <button type="button" @click="closeModalEdit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl transition-all">Batal</button>
            <button type="submit" :disabled="isSubmittingEdit" class="px-6 py-2.5 bg-amber-600 hover:bg-amber-500 disabled:bg-amber-800 text-white text-xs font-bold rounded-xl shadow-lg transition-all inline-flex items-center gap-2">
              <span v-if="isSubmittingEdit" class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></span>
              <span>{{ isSubmittingEdit ? 'Memperbarui...' : 'Simpan Perubahan' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- 6. MODAL DETAIL PEGAWAI & BERKAS (READ-ONLY) -->
    <div v-if="showModalDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-slate-950/80 backdrop-blur-md">
      <div class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-5xl overflow-hidden my-6 transform transition-all text-slate-100">
        
        <div v-if="isLoadingDetail" class="p-16 text-center text-slate-400">
          <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent mb-3"></div>
          <p class="text-sm font-medium">Memuat rincian arsip pegawai...</p>
        </div>

        <div v-else-if="selectedPegawai">
          <!-- Header Detail -->
          <div class="bg-slate-950/80 p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-white/10">
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-black text-2xl flex items-center justify-center shadow-lg border border-white/20 shrink-0">
                {{ getInitial(selectedPegawai.nama_lengkap) }}
              </div>
              <div>
                <div class="flex items-center gap-3 mb-1">
                  <h2 class="text-xl sm:text-2xl font-black text-white leading-tight">
                    {{ selectedPegawai.nama_lengkap }}
                  </h2>
                  <span :class="getRoleBadgeClass(selectedPegawai.kategori_peran)" class="px-3 py-0.5 rounded-full text-xs font-bold">
                    {{ selectedPegawai.kategori_peran }}
                  </span>
                </div>
                <div class="text-xs text-slate-400 flex flex-wrap items-center gap-3 font-mono">
                  <span>NIK: {{ selectedPegawai.nik || '-' }}</span>
                  <span>•</span>
                  <span>Unit: {{ selectedPegawai.unit_departemen || '-' }}</span>
                </div>
              </div>
            </div>

            <button @click="closeModalDetail" class="text-slate-400 hover:text-white p-2 rounded-xl hover:bg-white/10 transition-colors">
              <i class="bi bi-x-lg text-xl"></i>
            </button>
          </div>

          <!-- Detail Body -->
          <div class="p-6 sm:p-8 space-y-8 max-h-[75vh] overflow-y-auto">
            
            <!-- Grid Info -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="bg-slate-950/60 border border-white/10 rounded-2xl p-4">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">PENDIDIKAN TERAKHIR</span>
                <div class="font-semibold text-xs text-white">{{ selectedPegawai.pendidikan_terakhir || '-' }}</div>
              </div>

              <div class="bg-slate-950/60 border border-white/10 rounded-2xl p-4">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">EMAIL RESMI</span>
                <div class="font-semibold text-xs text-white">{{ selectedPegawai.email_resmi || '-' }}</div>
              </div>

              <div class="bg-slate-950/60 border border-white/10 rounded-2xl p-4">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">NO. HP / WHATSAPP</span>
                <div class="font-semibold text-xs text-white font-mono">{{ selectedPegawai.no_hp || '-' }}</div>
              </div>
            </div>

            <!-- List Berkas PDF -->
            <div class="pt-4 border-t border-white/10">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                  <h3 class="text-lg font-black text-white flex items-center gap-2">
                    <i class="bi bi-folder2-open text-blue-400"></i>
                    <span>Dokumen Terupload HRD ({{ selectedPegawai.berkas_pegawais ? selectedPegawai.berkas_pegawais.length : 0 }})</span>
                  </h3>
                  <p class="text-xs text-slate-400 mt-0.5">Mode Pratinjau Dokumen & Download Berkas Resmi</p>
                </div>

                <button 
                  @click="downloadZip(selectedPegawai.id, selectedPegawai.nama_lengkap)"
                  :disabled="isDownloadingZip || !selectedPegawai.berkas_pegawais || selectedPegawai.berkas_pegawais.length === 0"
                  class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 disabled:bg-slate-800 text-white font-bold text-xs px-5 py-2.5 rounded-full shadow-lg transition-all active:scale-95"
                >
                  <span v-if="isDownloadingZip" class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></span>
                  <i v-else class="bi bi-file-zip text-base"></i>
                  <span>{{ isDownloadingZip ? 'Mengunduh ZIP...' : 'Download Semua (ZIP)' }}</span>
                </button>
              </div>

              <!-- File Cards -->
              <div v-if="selectedPegawai.berkas_pegawais && selectedPegawai.berkas_pegawais.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="berkas in selectedPegawai.berkas_pegawais" :key="berkas.id" class="bg-slate-950/60 border border-white/10 rounded-2xl p-4 flex flex-col justify-between">
                  <div>
                    <div class="flex items-center justify-between mb-2">
                      <span class="px-2.5 py-0.5 bg-blue-500/20 text-blue-300 font-bold text-[10px] uppercase rounded-md border border-blue-500/30">
                        {{ berkas.jenis_berkas }}
                      </span>
                      <span class="text-[11px] font-bold text-emerald-400 bg-emerald-950/60 px-2 py-0.5 rounded-md border border-emerald-500/30">
                        Valid
                      </span>
                    </div>

                    <h4 class="font-bold text-white text-sm leading-snug mb-1">{{ berkas.judul_dokumen }}</h4>
                    <p class="text-xs text-slate-400 font-mono mb-3 truncate">{{ berkas.nama_file }}</p>
                  </div>

                  <div class="grid grid-cols-2 gap-2 pt-3 border-t border-white/10 text-xs">
                    <button @click="viewPdf(berkas.file_path)" class="py-2 bg-blue-600/30 hover:bg-blue-600/50 text-blue-200 font-bold rounded-xl transition-all flex items-center justify-center gap-1.5">
                      <i class="bi bi-file-earmark-pdf"></i> Lihat PDF
                    </button>
                    <button @click="downloadPdf(berkas.file_path, berkas.nama_file)" class="py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold rounded-xl transition-all flex items-center justify-center gap-1.5">
                      <i class="bi bi-download"></i> Unduh
                    </button>
                  </div>
                </div>
              </div>

              <div v-else class="p-8 text-center text-slate-400 bg-slate-950/40 border border-white/10 rounded-2xl">
                Belum ada dokumen PDF yang diunggah untuk pegawai ini.
              </div>
            </div>

          </div>

          <!-- Modal Footer -->
          <div class="bg-slate-950/80 p-5 flex items-center justify-between border-t border-white/10">
            <span class="text-xs text-slate-400 italic">
              * Data terenkripsi dan tersimpan di database resmi RSU Bunda Thamrin
            </span>
            <button @click="closeModalDetail" class="px-6 py-2.5 bg-white hover:bg-slate-200 text-slate-950 font-bold text-xs rounded-full shadow-lg transition-all">
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- 7. MODAL KONFIRMASI HAPUS PEGAWAI -->
    <div v-if="showModalHapus" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
      <div class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden text-slate-100">
        <div class="bg-rose-950/60 p-5 flex items-center justify-between border-b border-rose-500/30">
          <div class="flex items-center gap-2.5">
            <i class="bi bi-exclamation-triangle-fill text-rose-400 text-xl"></i>
            <h3 class="text-base font-bold text-white">Hapus Data Pegawai</h3>
          </div>
          <button @click="closeModalHapus" class="text-rose-200 hover:text-white p-1">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>

        <div class="p-6 space-y-4 text-center">
          <p class="text-sm text-slate-300 leading-relaxed">
            Apakah Anda yakin ingin menghapus data pegawai <strong class="text-white">{{ pegawaiToDelete?.nama_lengkap }}</strong>?
          </p>

          <div class="bg-rose-950/40 border border-rose-500/30 rounded-xl p-3 text-xs text-rose-300 text-left">
            ⚠️ Tindakan ini akan menghapus permanen profil pegawai dan seluruh berkas dokumen PDF miliknya.
          </div>

          <div class="flex items-center justify-center gap-3 pt-2">
            <button @click="closeModalHapus" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl">Batal</button>
            <button @click="submitHapusPegawai" :disabled="isDeletingPegawai" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold rounded-xl shadow-lg inline-flex items-center gap-2">
              <span v-if="isDeletingPegawai" class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></span>
              <span>{{ isDeletingPegawai ? 'Menghapus...' : 'Ya, Hapus Permanen' }}</span>
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

// Modal Tambah State
const showModalTambah = ref(false);
const isSubmitting = ref(false);
const formTambah = ref({
  nik: '',
  nama_lengkap: '',
  kategori_peran: 'Dokter Spesialis / Umum',
  kategori_peran_custom: '',
  unit_departemen: '',
  email_resmi: '',
  no_hp: '',
  pendidikan_terakhir: '',
  tanggal_upload: new Date().toISOString().split('T')[0],
  file_ijazah: null,
  file_transkrip: null,
  file_str: null,
  file_sip: null,
  file_ktp: null,
  file_kk: null,
  file_cv: null,
  file_lamaran: null,
  file_lainnya: null,
});

// Modal Edit State
const showModalEdit = ref(false);
const isSubmittingEdit = ref(false);
const berkasExisting = ref([]);
const formEdit = ref({
  id: null,
  nik: '',
  nama_lengkap: '',
  kategori_peran: 'Dokter Spesialis / Umum',
  kategori_peran_custom: '',
  unit_departemen: '',
  email_resmi: '',
  no_hp: '',
  pendidikan_terakhir: '',
  file_ijazah: null,
  file_transkrip: null,
  file_str: null,
  file_sip: null,
  file_ktp: null,
  file_kk: null,
  file_cv: null,
  file_lamaran: null,
  file_lainnya: null,
});

// Modal Detail State
const showModalDetail = ref(false);
const isLoadingDetail = ref(false);
const selectedPegawai = ref(null);
const isDownloadingZip = ref(false);

// Modal Hapus State
const showModalHapus = ref(false);
const pegawaiToDelete = ref(null);
const isDeletingPegawai = ref(false);

let debounceTimer = null;

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
    kategori_peran_custom: '',
    unit_departemen: '',
    email_resmi: '',
    no_hp: '',
    pendidikan_terakhir: '',
    tanggal_upload: new Date().toISOString().split('T')[0],
    file_ijazah: null,
    file_transkrip: null,
    file_str: null,
    file_sip: null,
    file_ktp: null,
    file_kk: null,
    file_cv: null,
    file_lamaran: null,
    file_lainnya: null,
  };
  showModalTambah.value = true;
};

const closeModalTambah = () => {
  showModalTambah.value = false;
};

const handleFileTambah = (e, key) => {
  if (e.target.files && e.target.files[0]) {
    formTambah.value[key] = e.target.files[0];
  }
};

const submitTambahPegawai = async () => {
  isSubmitting.value = true;
  const formData = new FormData();

  Object.keys(formTambah.value).forEach(key => {
    if (formTambah.value[key] !== null && formTambah.value[key] !== '') {
      formData.append(key, formTambah.value[key]);
    }
  });

  try {
    const response = await axios.post('/api/admin/pegawai', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

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

// Modal Edit Handlers
const openModalEdit = async (item) => {
  const isStandardRole = ['Dokter', 'Perawat', 'Penunjang Medis', 'Staf Administrasi'].includes(item.kategori_peran);

  let defaultSelect = 'Dokter Spesialis / Umum';
  if (item.kategori_peran === 'Perawat') defaultSelect = 'Perawat / Ners';
  if (item.kategori_peran === 'Penunjang Medis') defaultSelect = 'Penunjang Medis (Farmasi/Lab)';
  if (item.kategori_peran === 'Staf Administrasi') defaultSelect = 'Staf Administrasi / HRD';
  if (!isStandardRole) defaultSelect = 'Pegawai Lainnya';

  formEdit.value = {
    id: item.id,
    nik: item.nik || '',
    nama_lengkap: item.nama_lengkap || '',
    kategori_peran: defaultSelect,
    kategori_peran_custom: isStandardRole ? '' : item.kategori_peran,
    unit_departemen: item.unit_departemen || '',
    email_resmi: item.email_resmi || '',
    no_hp: item.no_hp || '',
    pendidikan_terakhir: item.pendidikan_terakhir || '',
    file_ijazah: null,
    file_transkrip: null,
    file_str: null,
    file_sip: null,
    file_ktp: null,
    file_kk: null,
    file_cv: null,
    file_lamaran: null,
    file_lainnya: null,
  };

  berkasExisting.value = [];
  showModalEdit.value = true;

  try {
    const response = await axios.get('/api/admin/pegawai/' + item.id);
    if (response.data.success) {
      berkasExisting.value = response.data.data.berkas_pegawais || [];
    }
  } catch (err) {
    console.error('Gagal mengambil daftar berkas pegawai:', err);
  }
};

const closeModalEdit = () => {
  showModalEdit.value = false;
};

// Handler untuk menyimpan file saat Edit Pegawai
const handleFileEdit = (e, key) => {
  if (e.target.files && e.target.files[0]) {
    formEdit.value[key] = e.target.files[0];
  }
};

const submitEditPegawai = async () => {
  isSubmittingEdit.value = true;
  const formData = new FormData();
  formData.append('_method', 'PUT');

  Object.keys(formEdit.value).forEach(key => {
    if (formEdit.value[key] !== null && formEdit.value[key] !== '') {
      formData.append(key, formEdit.value[key]);
    }
  });

  try {
    const response = await axios.post(`/api/admin/pegawai/${formEdit.value.id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    if (response.data.success) {
      alert('Data Pegawai & Dokumen berhasil diperbarui!');
      closeModalEdit();
      fetchData();
    }
  } catch (error) {
    const msg = error.response?.data?.message || 'Gagal memperbarui data pegawai';
    alert('Error Edit: ' + msg);
  } finally {
    isSubmittingEdit.value = false;
  }
};

const deleteBerkasIndividual = async (berkasId) => {
  if (!confirm('Apakah Anda yakin ingin menghapus berkas dokumen ini?')) return;

  try {
    const response = await axios.delete('/api/admin/berkas/' + berkasId);
    if (response.data.success) {
      alert('Berkas berhasil dihapus!');
      berkasExisting.value = berkasExisting.value.filter(b => b.id !== berkasId);
      fetchData();
    }
  } catch {
    alert('Gagal menghapus berkas');
  }
};

// Modal Detail Handlers
const openModalDetail = async (id) => {
  showModalDetail.value = true;
  isLoadingDetail.value = true;
  try {
    const response = await axios.get('/api/admin/pegawai/' + id);
    if (response.data.success) {
      selectedPegawai.value = response.data.data;
    }
  } catch {
    alert('Gagal memuat detail pegawai');
    showModalDetail.value = false;
  } finally {
    isLoadingDetail.value = false;
  }
};

const closeModalDetail = () => {
  showModalDetail.value = false;
  selectedPegawai.value = null;
};

// Modal Hapus Handlers
const confirmHapusPegawai = (item) => {
  pegawaiToDelete.value = item;
  showModalHapus.value = true;
};

const closeModalHapus = () => {
  showModalHapus.value = false;
  pegawaiToDelete.value = null;
};

const submitHapusPegawai = async () => {
  if (!pegawaiToDelete.value) return;

  isDeletingPegawai.value = true;
  try {
    const response = await axios.delete('/api/admin/pegawai/' + pegawaiToDelete.value.id);
    if (response.data.success) {
      alert('Data Pegawai & seluruh berkas PDF terlampir berhasil dihapus!');
      closeModalHapus();
      fetchData();
    }
  } catch (error) {
    const msg = error.response?.data?.message || 'Gagal menghapus pegawai';
    alert('Error Hapus: ' + msg);
  } finally {
    isDeletingPegawai.value = false;
  }
};

// Download Semua Dokumen ZIP
const downloadZip = async (id, namaPegawai) => {
  isDownloadingZip.value = true;
  try {
    const response = await axios.get(`/api/admin/pegawai/${id}/download-zip`, {
      responseType: 'blob'
    });

    const cleanName = namaPegawai.replace(/[^a-zA-Z0-9_-]/g, '_');
    const url = window.URL.createObjectURL(new Blob([response.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', `Arsip_Dokumen_${cleanName}.zip`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
  } catch {
    alert('Gagal mengunduh file ZIP. Pastikan pegawai memiliki dokumen terunggah.');
  } finally {
    isDownloadingZip.value = false;
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
      return 'bg-blue-500/20 text-blue-300 border border-blue-500/40';
    case 'Perawat':
      return 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40';
    case 'Penunjang Medis':
      return 'bg-amber-500/20 text-amber-300 border border-amber-500/40';
    case 'Staf Administrasi':
      return 'bg-purple-500/20 text-purple-300 border border-purple-500/40';
    default:
      return 'bg-slate-500/20 text-slate-300 border border-slate-500/40';
  }
};

onMounted(() => {
  fetchData();
});
</script>