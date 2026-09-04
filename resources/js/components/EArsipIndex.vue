<template>
  <div class="w-full text-slate-800 font-sans pb-12 relative z-20">

    <!-- 1. HERO HEADER & FLOATING TICKER STATS LIGHT PEARL GOLD -->
    <div class="relative mb-8">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">

        <!-- BAGIAN KIRI: HERO TYPOGRAPHY BOLD & CTA BUTTONS -->
        <div class="lg:col-span-7 space-y-5">
          <!-- Tag Badge Pill -->
          <div
            class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-amber-100/90 border border-amber-300/60 rounded-full text-xs text-amber-900 font-extrabold uppercase shadow-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
            <span>Repository Dokumentasi & E-Arsip Diklat • RSU Bunda Thamrin</span>
          </div>

          <!-- Headline Utama -->
          <h1
            class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 uppercase leading-[1.08]">
            DATA DOKUMENTASI & ARSIP DIKLAT <span
              class="text-gold-gradient">TERINTEGRASI</span>
          </h1>

          <!-- Deskripsi -->
          <p class="text-slate-600 text-sm sm:text-base max-w-2xl leading-relaxed font-medium">
            Pusat penyimpanan berkas digital resmi kegiatan Pendidikan & Pelatihan (Diklat) RSU Bunda Thamrin terenkripsi lengkap dengan lampiran dokumen PDF.
          </p>

          <!-- CTA Buttons -->
          <div class="flex flex-wrap items-center gap-3.5 pt-2">
            <button @click="openModalTambah"
              class="btn-gold-shimmer font-black text-xs sm:text-sm px-7 py-4 rounded-full shadow-lg transition-all active:scale-95 group flex items-center gap-2.5 cursor-pointer">
              <i class="bi bi-plus-circle-fill text-lg group-hover:rotate-90 transition-transform"></i>
              <span>Tambah Data Diklat Baru</span>
            </button>

            <a href="#tabel-diklat"
              class="inline-flex items-center gap-2 bg-white hover:bg-amber-50 text-slate-800 font-extrabold text-xs sm:text-sm px-6 py-4 rounded-full border border-amber-300 shadow-md transition-all active:scale-95">
              <i class="bi bi-search text-amber-600"></i>
              <span>Eksplor Data Arsip Diklat</span>
            </a>
          </div>
        </div>

        <!-- BAGIAN KANAN: FLOATING GLASS TICKER WIDGETS -->
        <div class="lg:col-span-5 space-y-3.5">

          <!-- Widget 1: TOTAL DOKUMENTASI DIKLAT -->
          <div
            class="glass-pearl-card hover-tilt-card rounded-2xl p-4.5 shadow-md group">
            <div class="flex items-center justify-between mb-1.5">
              <div class="flex items-center gap-2">
                <span
                  class="text-xs font-mono font-bold text-amber-900 bg-amber-100 border border-amber-300 px-2.5 py-0.5 rounded-md">DKL/MAIN</span>
                <span class="text-xs font-black text-slate-800 uppercase tracking-wider">TOTAL DOKUMENTASI DIKLAT</span>
              </div>
              <span
                class="text-xs font-black text-emerald-800 bg-emerald-100 border border-emerald-300 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                <i class="bi bi-arrow-up-short text-emerald-600"></i> Active
              </span>
            </div>

            <div class="flex items-end justify-between">
              <div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 font-mono tracking-tight">
                  {{ statistics.total_diklat || statistics.total_dokter || 0 }} <span
                    class="text-xs font-sans text-slate-500 font-bold">Kegiatan</span>
                </div>
                <div class="text-[11px] text-slate-500 font-medium">Dokumentasi Diklat Terdaftar</div>
              </div>
              <svg class="w-24 h-10 text-amber-500 opacity-80 group-hover:opacity-100 transition-opacity" fill="none"
                stroke="currentColor" viewBox="0 0 100 30">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M0 25 Q25 5, 50 18 T100 8" />
              </svg>
            </div>
          </div>

          <!-- Widget 2: TOTAL BERKAS PDF -->
          <div
            class="glass-pearl-card hover-tilt-card rounded-2xl p-4.5 shadow-md group">
            <div class="flex items-center justify-between mb-1.5">
              <div class="flex items-center gap-2">
                <span
                  class="text-xs font-mono font-bold text-emerald-900 bg-emerald-100 border border-emerald-300 px-2.5 py-0.5 rounded-md">PDF/FILE</span>
                <span class="text-xs font-black text-slate-800 uppercase tracking-wider">TOTAL BERKAS PDF</span>
              </div>
              <span
                class="text-xs font-black text-emerald-800 bg-emerald-100 border border-emerald-300 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                <i class="bi bi-check2-all text-emerald-600"></i> Verified
              </span>
            </div>

            <div class="flex items-end justify-between">
              <div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 font-mono tracking-tight">
                  {{ statistics.total_berkas_pdf || statistics.total_perawat || 0 }} <span
                    class="text-xs font-sans text-slate-500 font-bold">File PDF</span>
                </div>
                <div class="text-[11px] text-slate-500 font-medium">File Berkas PDF Ter-upload</div>
              </div>
              <svg class="w-24 h-10 text-emerald-500 opacity-80 group-hover:opacity-100 transition-opacity" fill="none"
                stroke="currentColor" viewBox="0 0 100 30">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M0 20 Q20 28, 45 10 T100 5" />
              </svg>
            </div>
          </div>

          <!-- Widget 3: DOKUMENTASI BULAN INI -->
          <div
            class="glass-pearl-card hover-tilt-card rounded-2xl p-4.5 shadow-md group">
            <div class="flex items-center justify-between mb-1.5">
              <div class="flex items-center gap-2">
                <span
                  class="text-xs font-mono font-bold text-purple-900 bg-purple-100 border border-purple-300 px-2.5 py-0.5 rounded-md">NEW/RECENT</span>
                <span class="text-xs font-black text-slate-800 uppercase tracking-wider">DOKUMENTASI BULAN INI</span>
              </div>
              <span
                class="text-xs font-black text-amber-900 bg-amber-100 border border-amber-300 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                <i class="bi bi-clock-history text-amber-600"></i> Recent
              </span>
            </div>

            <div class="flex items-end justify-between">
              <div>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 font-mono tracking-tight">
                  {{ statistics.diklat_bulan_ini || statistics.staf_penunjang || 0 }} <span
                    class="text-xs font-sans text-slate-500 font-bold">Kegiatan</span>
                </div>
                <div class="text-[11px] text-slate-500 font-medium">Kegiatan Diklat Terbaru Bulan Ini</div>
              </div>
              <svg class="w-24 h-10 text-amber-600 opacity-80 group-hover:opacity-100 transition-opacity" fill="none"
                stroke="currentColor" viewBox="0 0 100 30">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M0 15 Q35 2, 70 20 T100 12" />
              </svg>
            </div>
          </div>

        </div>

      </div>
    </div>

    <!-- 2. FILTER BAR & PENCARIAN LIGHT PEARL GOLD -->
    <!-- 2. FILTER BAR & PENCARIAN LIGHT PEARL GOLD -->
    <div id="tabel-diklat"
      class="glass-pearl-card hover-tilt-card rounded-3xl p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center mb-4">

        <!-- Search Input -->
        <div class="md:col-span-4 relative">
          <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-amber-600 text-base"></i>
          <input v-model="searchQuery" @input="onFilterChange" type="text"
            placeholder="Cari judul, deskripsi..."
            class="w-full pl-11 pr-4 py-3 bg-white/85 border border-amber-300/80 rounded-2xl text-xs sm:text-sm text-slate-900 font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all shadow-xs" />
          <button v-if="searchQuery" @click="searchQuery = ''; onFilterChange()"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-amber-700 p-1">
            <i class="bi bi-x-circle-fill"></i>
          </button>
        </div>

        <!-- Filter Tahun Dropdown -->
        <div class="md:col-span-2 relative">
          <i class="bi bi-calendar-range absolute left-4 top-1/2 -translate-y-1/2 text-amber-600 text-base"></i>
          <select v-model="selectedTahun" @change="onFilterChange"
            class="w-full pl-11 pr-4 py-3 bg-white/85 border border-amber-300/80 rounded-2xl text-xs sm:text-sm text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all shadow-xs [color-scheme:light]">
            <option value="">Semua Tahun</option>
            <option v-for="year in yearOptions" :key="year" :value="year">{{ year }}</option>
          </select>
        </div>

        <!-- Filter Bulan Dropdown -->
        <div class="md:col-span-3 relative">
          <i class="bi bi-calendar-month absolute left-4 top-1/2 -translate-y-1/2 text-amber-600 text-base"></i>
          <select v-model="selectedBulan" @change="onFilterChange"
            class="w-full pl-11 pr-4 py-3 bg-white/85 border border-amber-300/80 rounded-2xl text-xs sm:text-sm text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all shadow-xs [color-scheme:light]">
            <option value="">Semua Bulan</option>
            <option value="1">Januari</option>
            <option value="2">Februari</option>
            <option value="3">Maret</option>
            <option value="4">April</option>
            <option value="5">Mei</option>
            <option value="6">Juni</option>
            <option value="7">Juli</option>
            <option value="8">Agustus</option>
            <option value="9">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
          </select>
        </div>

        <!-- Filter Minggu Dropdown -->
        <div class="md:col-span-2 relative">
          <i class="bi bi-calendar-week absolute left-4 top-1/2 -translate-y-1/2 text-amber-600 text-base"></i>
          <select v-model="selectedMinggu" @change="onFilterChange"
            class="w-full pl-11 pr-4 py-3 bg-white/85 border border-amber-300/80 rounded-2xl text-xs sm:text-sm text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all shadow-xs [color-scheme:light]">
            <option value="">Semua Minggu</option>
            <option value="1">Minggu 1 (Tgl 1-7)</option>
            <option value="2">Minggu 2 (Tgl 8-14)</option>
            <option value="3">Minggu 3 (Tgl 15-21)</option>
            <option value="4">Minggu 4 (Tgl 22-31)</option>
          </select>
        </div>

        <!-- Date Filter Input -->
        <div class="md:col-span-2 relative">
          <i class="bi bi-calendar-event absolute left-4 top-1/2 -translate-y-1/2 text-amber-600 text-base"></i>
          <input v-model="selectedDate" @change="onFilterChange" type="date"
            class="w-full pl-11 pr-4 py-3 bg-white/85 border border-amber-300/80 rounded-2xl text-xs sm:text-sm text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 transition-all shadow-xs [color-scheme:light]" />
        </div>

      </div>

      <!-- Quick Preset Filter Date Pills -->
      <div class="flex flex-wrap items-center gap-2 pt-3 border-t border-amber-200/50">
        <span class="text-xs font-black text-amber-900 uppercase tracking-wider mr-1">Quick Filter:</span>
        <button @click="resetAllFilters" :class="[
          'px-3.5 py-1.5 rounded-full text-xs font-bold transition-all shadow-xs',
          (!selectedDate && !selectedTahun && !selectedBulan && !selectedMinggu && !searchQuery) ? 'bg-amber-500 text-white shadow-md' : 'bg-white/80 text-slate-700 border border-amber-300/60 hover:bg-amber-100'
        ]">Semua Data</button>
        <button @click="setQuickDate(todayDate, 'Hari Ini')" :class="[
          'px-3.5 py-1.5 rounded-full text-xs font-bold transition-all shadow-xs',
          selectedDate === todayDate ? 'bg-amber-500 text-white shadow-md' : 'bg-white/80 text-slate-700 border border-amber-300/60 hover:bg-amber-100'
        ]">Hari Ini</button>
        <button @click="setQuickBulan(currentMonthVal)" :class="[
          'px-3.5 py-1.5 rounded-full text-xs font-bold transition-all shadow-xs',
          selectedBulan == currentMonthVal ? 'bg-amber-500 text-white shadow-md' : 'bg-white/80 text-slate-700 border border-amber-300/60 hover:bg-amber-100'
        ]">Bulan Ini</button>
        
        <button v-if="selectedDate || selectedTahun || selectedBulan || selectedMinggu || searchQuery" @click="resetAllFilters"
          class="ml-auto text-xs text-rose-700 font-bold bg-rose-50 hover:bg-rose-100 px-3.5 py-1.5 rounded-full border border-rose-300 transition-all flex items-center gap-1 shadow-xs">
          <i class="bi bi-x-lg"></i> Reset Filter
        </button>
      </div>
    </div>

    <!-- 3. DATA TABLE DOKUMENTASI DIKLAT (GLASS CARD WHITE-GOLD) -->
    <div class="glass-pearl-table shadow-2xl">

      <!-- Loading State -->
      <div v-if="isLoading" class="p-16 text-center text-slate-600 space-y-3">
        <div class="animate-spin rounded-full h-10 w-10 border-4 border-amber-500 border-t-transparent mx-auto"></div>
        <p class="font-bold text-sm text-amber-900">Memuat database dokumentasi diklat & berkas...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="pegawaiList.length === 0" class="p-16 text-center text-slate-600 space-y-3">
        <i class="bi bi-folder-x text-5xl text-amber-500"></i>
        <p class="font-bold text-slate-700">Belum ada data dokumentasi diklat yang sesuai dengan pencarian/filter.</p>
        <button @click="openModalTambah" class="inline-flex items-center gap-2 text-xs font-black text-amber-700 hover:underline">
          <i class="bi bi-plus-lg"></i> Tambah Data Diklat Baru
        </button>
      </div>

      <!-- Table Content -->
      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-amber-100/80 border-b border-amber-300/40 text-xs font-black text-amber-950 uppercase tracking-wider">
              <th class="py-4 px-5 text-center">NO</th>
              <th class="py-4 px-6">JUDUL DOKUMENTASI DIKLAT</th>
              <th class="py-4 px-6">DESKRIPSI / KETERANGAN</th>
              <th class="py-4 px-5 text-center">TANGGAL</th>
              <th class="py-4 px-5 text-center">DOKUMEN BERKAS PDF</th>
              <th class="py-4 px-6 text-center">AKSI</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-amber-200/40 text-xs sm:text-sm">
            <tr v-for="(item, index) in displayedPegawaiList" :key="item.id" class="hover:bg-amber-100/40 transition-colors group">
              
              <!-- NO -->
              <td class="py-4 px-5 text-center font-mono font-bold text-slate-600">
                {{ startIndex + index + 1 }}
              </td>

              <!-- JUDUL DOKUMENTASI DIKLAT -->
              <td class="py-4 px-6">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-400/30 flex items-center justify-center text-amber-600 font-bold text-base shrink-0">
                    <i class="bi bi-journal-bookmark-fill"></i>
                  </div>
                  <div>
                    <div class="font-black text-slate-900 group-hover:text-amber-800 transition-colors leading-snug">
                      {{ item.nama_lengkap }}
                    </div>
                    <div class="text-[11px] text-slate-500 font-mono mt-0.5 font-semibold">
                      ID: {{ item.nik }}
                    </div>
                  </div>
                </div>
              </td>

              <!-- DESKRIPSI / KETERANGAN -->
              <td class="py-4 px-6 text-slate-700 font-medium max-w-xs truncate">
                {{ item.unit_departemen || '-' }}
              </td>

              <!-- TANGGAL -->
              <td class="py-4 px-5 text-center font-mono text-slate-700 whitespace-nowrap">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50/80 border border-amber-200/80 rounded-lg text-xs font-bold shadow-xs">
                  <i class="bi bi-calendar3 text-amber-600"></i>
                  {{ item.tanggal_upload }}
                </span>
              </td>

              <!-- DOKUMEN BERKAS PDF -->
              <td class="py-4 px-5 text-center">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100/90 border border-emerald-300 rounded-full text-xs font-black text-emerald-800 shadow-xs">
                  <i class="bi bi-file-earmark-pdf-fill text-emerald-600"></i>
                  <span>{{ item.berkas_pegawais_count || 0 }} Berkas PDF</span>
                </div>
              </td>

              <!-- AKSI BUTTONS -->
              <td class="py-4 px-6 text-center">
                <div class="flex items-center justify-center gap-1.5">
                  <button @click="openModalDetail(item.id)" title="Detail Arsip Diklat"
                    class="p-2 rounded-xl bg-blue-100 hover:bg-blue-600 text-blue-700 hover:text-white transition-all border border-blue-300 shadow-xs transform active:scale-95">
                    <i class="bi bi-eye-fill"></i>
                  </button>

                  <button @click="openModalEdit(item)" title="Edit Data Diklat & Berkas"
                    class="p-2 rounded-xl bg-amber-100 hover:bg-amber-500 text-amber-800 hover:text-white transition-all border border-amber-300 shadow-xs transform active:scale-95">
                    <i class="bi bi-pencil-square"></i>
                  </button>

                  <button @click="downloadZip(item.id, item.nama_lengkap)" title="Download Semua Berkas Disatukan (1 File PDF)"
                    class="p-2 rounded-xl bg-purple-100 hover:bg-purple-600 text-purple-800 hover:text-white transition-all border border-purple-300 shadow-xs transform active:scale-95">
                    <i class="bi bi-file-earmark-pdf-fill"></i>
                  </button>

                  <button @click="confirmHapusPegawai(item)" title="Hapus Permanen Data Diklat"
                    class="p-2 rounded-xl bg-rose-100 hover:bg-rose-500 text-rose-700 hover:text-white transition-all border border-rose-300 shadow-xs transform active:scale-95">
                    <i class="bi bi-trash3-fill"></i>
                  </button>
                </div>
              </td>

            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Controls Footer -->
      <div v-if="!isLoading && pegawaiList.length > 0"
        class="p-4 bg-amber-50/60 border-t border-amber-200/60 flex flex-wrap items-center justify-between gap-4 text-xs">
        <div class="text-slate-700 font-medium">
          Menampilkan <span class="font-black text-amber-900 font-mono">{{ startIndex + 1 }}</span> - <span
            class="font-black text-amber-900 font-mono">{{ endIndex }}</span> dari
          <span class="font-black text-amber-900 font-mono">{{ pegawaiList.length }}</span> total data diklat
        </div>

        <div class="flex items-center gap-1.5">
          <button @click="prevPage" :disabled="currentPage === 1"
            class="px-3 py-1.5 rounded-lg bg-white/80 border border-amber-300/60 text-slate-800 font-bold hover:bg-amber-100 disabled:opacity-40 transition-all">
            <i class="bi bi-chevron-left"></i> Prev
          </button>

          <div class="flex items-center gap-1 font-mono">
            <button v-for="page in totalPages" :key="page" @click="currentPage = page" :class="[
              'w-8 h-8 rounded-lg font-black transition-all border',
              currentPage === page ? 'bg-amber-500 text-white border-amber-600 shadow-sm' : 'bg-white/80 border-amber-300/60 text-slate-800 hover:bg-amber-100'
            ]">
              {{ page }}
            </button>
          </div>

          <button @click="nextPage" :disabled="currentPage === totalPages"
            class="px-3 py-1.5 rounded-lg bg-white/80 border border-amber-300/60 text-slate-800 font-bold hover:bg-amber-100 disabled:opacity-40 transition-all">
            Next <i class="bi bi-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- 4. MODAL FORM TAMBAH DOKUMENTASI DIKLAT BARU -->
    <Teleport to="body">
    <div v-if="showModalTambah"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-md">
      <div
        class="glass-pearl-card border border-amber-300/60 rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden my-8 max-h-[90vh] flex flex-col text-slate-800">

        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-amber-500/15 via-amber-400/10 to-yellow-500/15 p-6 flex items-center justify-between border-b border-amber-300/40 shrink-0">
          <div class="flex items-center gap-3">
            <div
              class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-400/40 flex items-center justify-center text-amber-700 shadow-xs">
              <i class="bi bi-journal-plus text-xl"></i>
            </div>
            <div>
              <h3 class="text-lg font-black text-slate-900 leading-tight">Tambah Data Dokumentasi Diklat Baru</h3>
              <p class="text-xs text-slate-600 font-medium">Input Judul Diklat, Deskripsi & Lampiran Dokumen PDF</p>
            </div>
          </div>
          <button @click="closeModalTambah"
            class="text-slate-500 hover:text-rose-600 p-1.5 rounded-xl hover:bg-rose-50 transition-colors">
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>

        <!-- Form Body -->
        <form @submit.prevent="submitTambahPegawai" class="p-6 space-y-5 overflow-y-auto flex-grow">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <!-- 1. Tambahkan Judul -->
            <div class="md:col-span-2">
              <label class="block text-xs font-black text-amber-900 uppercase tracking-wider mb-1.5">Tambahkan Judul *</label>
              <input v-model="formTambah.judul" required type="text" placeholder="Masukkan Judul Kegiatan Diklat / Pelatihan..."
                class="w-full px-4 py-3 bg-white/90 border border-amber-300/80 rounded-xl text-sm text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 placeholder-slate-400 shadow-xs" />
            </div>

            <!-- 2. Deskripsi (Optional) -->
            <div class="md:col-span-2">
              <label class="block text-xs font-black text-amber-900 uppercase tracking-wider mb-1.5">Deskripsi (Optional)</label>
              <textarea v-model="formTambah.deskripsi" rows="3" placeholder="Masukkan keterangan / deskripsi singkat mengenai dokumentasi diklat ini..."
                class="w-full px-4 py-3 bg-white/90 border border-amber-300/80 rounded-xl text-sm text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 placeholder-slate-400 shadow-xs"></textarea>
            </div>

            <!-- 3. Tanggal -->
            <div class="md:col-span-2">
              <label class="block text-xs font-black text-amber-900 uppercase tracking-wider mb-1.5">Tanggal *</label>
              <input v-model="formTambah.tanggal_upload" required type="date"
                class="w-full px-4 py-3 bg-white/90 border border-amber-300/80 rounded-xl text-sm text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 shadow-xs [color-scheme:light]" />
            </div>

          </div>

          <!-- SECTION LAMPIRAN DOKUMEN BERKAS (BERKAS 1, BERKAS 2, DST.) -->
          <div class="border-t border-amber-200/60 pt-4 mt-4">
            <div class="flex items-center justify-between mb-3">
              <label class="block text-xs font-black text-amber-900 uppercase tracking-wider">
                LAMPIRAN BERKAS FILE (PDF / JPG / PNG / WEBP):
              </label>
              <button type="button" @click="tambahFieldBerkasTambah"
                class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-extrabold shadow-sm transition-all flex items-center gap-1.5 cursor-pointer active:scale-95">
                <i class="bi bi-plus-circle-fill text-sm"></i>
                <span>+Tambah Berkas Baru</span>
              </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">

              <div v-for="i in berkasTambahCount" :key="i" class="bg-white/80 p-3 rounded-xl border border-amber-200/80 shadow-xs">
                <label class="block font-bold text-slate-800 mb-1">{{ i }}. Berkas {{ i }} (PDF / Gambar)</label>
                <input @change="handleFileTambah($event, 'file_berkas_' + i)" type="file" accept="application/pdf,image/png,image/jpeg,image/jpg,image/webp,.pdf,.png,.jpg,.jpeg,.webp"
                  class="w-full text-xs text-slate-600 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-extrabold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200" />
              </div>

            </div>
          </div>

          <!-- Action Footer -->
          <div class="flex items-center justify-end gap-3 pt-4 border-t border-amber-200/60 shrink-0">
            <button type="button" @click="closeModalTambah"
              class="px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 text-xs font-bold rounded-xl transition-all shadow-xs">Batal</button>
            <button type="submit" :disabled="isSubmitting"
              class="px-6 py-2.5 btn-gold-shimmer text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-lg transition-all inline-flex items-center gap-2 cursor-pointer">
              <span v-if="isSubmitting"
                class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></span>
              <span>{{ isSubmitting ? 'Menyimpan...' : 'Simpan Data Diklat & Berkas' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
    </Teleport>

    <!-- 5. MODAL EDIT DOKUMENTASI DIKLAT & KELOLA BERKAS -->
    <div v-if="showModalEdit"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-slate-950/80 backdrop-blur-md">
      <div
        class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden my-8 max-h-[90vh] flex flex-col text-slate-100">

        <div class="bg-amber-950/60 p-6 flex items-center justify-between border-b border-amber-500/30 shrink-0">
          <div class="flex items-center gap-3">
            <div
              class="w-10 h-10 rounded-xl bg-amber-600/30 border border-amber-500/40 flex items-center justify-center text-amber-400">
              <i class="bi bi-pencil-square text-xl"></i>
            </div>
            <div>
              <h3 class="text-lg font-bold text-white leading-tight">Edit Data Dokumentasi Diklat & Upload Berkas Baru</h3>
              <p class="text-xs text-amber-200">Perbarui Judul, Deskripsi & Kelola File PDF Berkas</p>
            </div>
          </div>
          <button @click="closeModalEdit"
            class="text-amber-200 hover:text-white p-1 rounded-lg hover:bg-amber-900/50 transition-colors">
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>

        <form @submit.prevent="submitEditPegawai" class="p-6 space-y-5 overflow-y-auto flex-grow">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <div class="md:col-span-2">
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Tambahkan Judul *</label>
              <input v-model="formEdit.nama_lengkap" required type="text" placeholder="Masukkan Judul Kegiatan Diklat..."
                class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
            </div>

            <div class="md:col-span-2">
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Deskripsi (Optional)</label>
              <textarea v-model="formEdit.unit_departemen" rows="3" placeholder="Masukkan deskripsi..."
                class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
            </div>

            <div class="md:col-span-2">
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Tanggal *</label>
              <input v-model="formEdit.tanggal_upload" required type="date"
                class="w-full px-3.5 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-amber-500 [color-scheme:dark]" />
            </div>

          </div>

          <!-- BERKAS EXISTING -->
          <div v-if="berkasExisting && berkasExisting.length > 0"
            class="bg-white/80 border border-amber-200/80 rounded-2xl p-4 mt-4 shadow-xs">
            <label class="block text-xs font-black text-amber-900 uppercase tracking-wider mb-3">
              DOKUMEN PDF TERUNGGAH SAAT INI:
            </label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <div v-for="berkas in berkasExisting" :key="berkas.id"
                class="flex items-center justify-between p-3 bg-amber-50/80 rounded-xl border border-amber-200/80">
                <div class="flex items-center gap-2.5 overflow-hidden">
                  <i class="bi bi-file-earmark-pdf-fill text-rose-600 text-lg shrink-0"></i>
                  <div class="truncate">
                    <div class="text-xs font-bold text-slate-900 truncate">{{ berkas.jenis_berkas }}</div>
                    <div class="text-[11px] text-slate-500 truncate">{{ berkas.nama_file }} ({{ berkas.file_size }})</div>
                  </div>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                  <button type="button" @click="viewPdf(berkas)" title="Lihat PDF"
                    class="p-1.5 bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white rounded-lg transition-colors border border-blue-300">
                    <i class="bi bi-eye-fill"></i>
                  </button>
                  <button type="button" @click="deleteBerkasIndividual(berkas.id)" title="Hapus Berkas"
                    class="p-1.5 bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white rounded-lg transition-colors border border-rose-300">
                    <i class="bi bi-trash"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- UPLOAD DOKUMEN BERKAS BARU (SAAT EDIT) -->
          <div class="border-t border-amber-200/60 pt-4 mt-4">
            <div class="flex items-center justify-between mb-3">
              <label class="block text-xs font-black text-amber-900 uppercase tracking-wider">
                TAMBAH / UPDATE LAMPIRAN BERKAS (PDF / GAMBAR):
              </label>
              <button type="button" @click="tambahFieldBerkasEdit"
                class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-extrabold shadow-sm transition-all flex items-center gap-1.5 cursor-pointer active:scale-95">
                <i class="bi bi-plus-circle-fill text-sm"></i>
                <span>+Tambah Berkas Baru</span>
              </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
              <div v-for="i in berkasEditCount" :key="i" class="bg-white/80 p-3 rounded-xl border border-amber-200/80 shadow-xs">
                <label class="block font-bold text-slate-800 mb-1">Berkas {{ i }} (PDF / Gambar)</label>
                <input @change="handleFileEdit($event, 'file_berkas_' + i)" type="file" accept="application/pdf,image/png,image/jpeg,image/jpg,image/webp,.pdf,.png,.jpg,.jpeg,.webp"
                  class="w-full text-xs text-slate-600 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-extrabold file:bg-amber-100 file:text-amber-900 hover:file:bg-amber-200" />
              </div>
            </div>
          </div>

          <!-- Action Footer -->
          <div class="flex items-center justify-end gap-3 pt-4 border-t border-amber-200/60 shrink-0">
            <button type="button" @click="closeModalEdit"
              class="px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 text-xs font-bold rounded-xl transition-all shadow-xs">Batal</button>
            <button type="submit" :disabled="isSubmittingEdit"
              class="px-6 py-2.5 btn-gold-shimmer text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-lg transition-all inline-flex items-center gap-2 cursor-pointer">
              <span v-if="isSubmittingEdit"
                class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></span>
              <span>{{ isSubmittingEdit ? 'Memperbarui...' : 'Simpan Perubahan' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- 6. MODAL DETAIL DOKUMENTASI DIKLAT (READ-ONLY) -->
    <div v-if="showModalDetail"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto bg-slate-900/40 backdrop-blur-md">
      <div
        class="glass-pearl-card border border-amber-300/60 rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden my-8 max-h-[90vh] flex flex-col text-slate-800">

        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-amber-500/15 via-amber-400/10 to-yellow-500/15 p-6 flex items-center justify-between border-b border-amber-300/40 shrink-0">
          <div class="flex items-center gap-3">
            <div
              class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-400/40 flex items-center justify-center text-amber-700 shadow-xs">
              <i class="bi bi-journal-check text-xl"></i>
            </div>
            <div>
              <h3 class="text-lg font-black text-slate-900 leading-tight">Detail Arsip Dokumentasi Diklat</h3>
              <p class="text-xs text-slate-600 font-medium">Pratinjau Informasi & Lampiran Berkas PDF</p>
            </div>
          </div>
          <button @click="closeModalDetail"
            class="text-slate-500 hover:text-rose-600 p-1.5 rounded-xl hover:bg-rose-50 transition-colors">
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>

        <!-- Detail Content -->
        <div class="p-6 space-y-6 overflow-y-auto flex-grow">
          <div v-if="isLoadingDetail" class="p-12 text-center text-slate-600 space-y-3">
            <div class="animate-spin rounded-full h-8 w-8 border-3 border-amber-500 border-t-transparent mx-auto"></div>
            <p class="text-sm font-bold text-amber-900">Memuat rincian diklat...</p>
          </div>

          <div v-else-if="selectedPegawai">
            <!-- Information Grid -->
            <div class="bg-white/80 border border-amber-200/80 rounded-2xl p-5 mb-6 shadow-xs">
              <div class="flex items-start gap-4">
                <div
                  class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white font-black text-2xl shadow-md shrink-0">
                  <i class="bi bi-journal-text"></i>
                </div>
                <div class="space-y-2 flex-grow">
                  <h2 class="text-xl font-black text-slate-900 leading-snug">
                    {{ selectedPegawai.nama_lengkap }}
                  </h2>

                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-2 border-t border-amber-200/60">
                    <div>
                      <span class="text-slate-500 block mb-0.5 font-bold uppercase">Deskripsi / Keterangan:</span>
                      <span class="font-bold text-slate-900">{{ selectedPegawai.unit_departemen || '-' }}</span>
                    </div>
                    <div>
                      <span class="text-slate-500 block mb-0.5 font-bold uppercase">Tanggal Pelaksanaan:</span>
                      <span class="font-extrabold text-amber-800 font-mono">{{ selectedPegawai.tanggal_upload }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- List Berkas PDF Terlampir -->
            <div>
              <div class="flex items-center justify-between mb-4">
                <h4 class="text-xs font-black text-amber-900 uppercase tracking-wider">
                  LAMPIRAN DOKUMEN & BERKAS ({{ selectedPegawai.berkas_pegawais ? selectedPegawai.berkas_pegawais.length : 0 }})
                </h4>
                <button v-if="selectedPegawai.berkas_pegawais && selectedPegawai.berkas_pegawais.length > 0"
                  @click="downloadZip(selectedPegawai.id, selectedPegawai.nama_lengkap)"
                  class="px-3 py-1.5 bg-purple-100 hover:bg-purple-600 border border-purple-300 text-purple-800 hover:text-white rounded-lg text-xs font-bold transition-all inline-flex items-center gap-1.5 shadow-xs">
                  <i class="bi bi-file-earmark-pdf-fill"></i>
                  <span>Download PDF Gabungan</span>
                </button>
              </div>

              <div v-if="!selectedPegawai.berkas_pegawais || selectedPegawai.berkas_pegawais.length === 0"
                class="p-8 text-center bg-white/80 border border-amber-200/80 rounded-2xl text-slate-600 text-xs font-medium">
                Belum ada berkas PDF yang terunggah untuk diklat ini.
              </div>

              <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div v-for="berkas in selectedPegawai.berkas_pegawais" :key="berkas.id"
                  class="bg-white/80 border border-amber-200/80 rounded-xl p-4 flex items-center justify-between hover:border-amber-400 transition-all group shadow-xs">
                  <div class="flex items-center gap-3 truncate">
                    <div
                      class="w-10 h-10 rounded-lg bg-rose-100 border border-rose-300 flex items-center justify-center text-rose-600 text-lg shrink-0">
                      <i class="bi bi-file-earmark-pdf-fill"></i>
                    </div>
                    <div class="truncate">
                      <div class="text-xs font-bold text-slate-900 truncate group-hover:text-amber-800 transition-colors">
                        {{ berkas.jenis_berkas }}
                      </div>
                      <div class="text-[11px] text-slate-500 truncate mt-0.5 font-medium">
                        {{ berkas.nama_file }} • {{ berkas.file_size }}
                      </div>
                    </div>
                  </div>

                  <div class="flex items-center gap-1.5 shrink-0 ml-2">
                    <button @click="viewPdf(berkas)" title="Buka / Preview Berkas"
                      class="px-3 py-1.5 bg-blue-100 hover:bg-blue-600 border border-blue-300 text-blue-800 hover:text-white rounded-lg text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                      <i class="bi bi-eye-fill"></i>
                      <span>Buka / Preview Berkas</span>
                    </button>
                    <button @click="downloadPdf(berkas.file_path, berkas.nama_file)" title="Unduh File"
                      class="p-1.5 bg-white hover:bg-amber-100 border border-amber-300/80 text-slate-800 rounded-lg text-xs transition-all shadow-xs">
                      <i class="bi bi-download"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="bg-amber-50/60 p-4 border-t border-amber-200/60 flex justify-end shrink-0">
          <button @click="closeModalDetail"
            class="px-5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl transition-all">Tutup</button>
        </div>
      </div>
    </div>

    <!-- 7. MODAL HAPUS DOKUMENTASI DIKLAT -->
    <div v-if="showModalHapus"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
      <div
        class="bg-slate-900 border border-rose-500/30 rounded-3xl shadow-2xl w-full max-w-md p-6 text-slate-100 space-y-4">
        <div class="w-12 h-12 rounded-2xl bg-rose-500/20 border border-rose-500/40 flex items-center justify-center text-rose-400 text-2xl mx-auto">
          <i class="bi bi-exclamation-triangle-fill"></i>
        </div>

        <div class="text-center space-y-1">
          <h3 class="text-lg font-bold text-white">Konfirmasi Hapus Data Diklat</h3>
          <p class="text-xs text-slate-300">
            Apakah Anda yakin ingin menghapus data diklat <span class="font-bold text-rose-400">"{{ pegawaiToDelete?.nama_lengkap }}"</span> beserta seluruh berkas PDF terlampir?
          </p>
        </div>

        <div class="flex items-center justify-center gap-3 pt-2">
          <button @click="closeModalHapus"
            class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold rounded-xl transition-all">Batal</button>
          <button @click="submitHapusPegawai" :disabled="isDeletingPegawai"
            class="px-6 py-2.5 bg-rose-600 hover:bg-rose-500 disabled:bg-rose-800 text-white text-xs font-bold rounded-xl shadow-lg transition-all inline-flex items-center gap-2">
            <span v-if="isDeletingPegawai"
              class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></span>
            <span>{{ isDeletingPegawai ? 'Menghapus...' : 'Ya, Hapus Permanen' }}</span>
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

// Reactive State
const pegawaiList = ref([]);
const statistics = ref({
  total_diklat: 0,
  total_berkas_pdf: 0,
  diklat_bulan_ini: 0
});
const isLoading = ref(true);
const isSubmitting = ref(false);
const isSubmittingEdit = ref(false);
const isDeletingPegawai = ref(false);
const isDownloadingZip = ref(false);

// Filter & Search State
const searchQuery = ref('');
const selectedDate = ref('');
const selectedTahun = ref('');
const selectedBulan = ref('');
const selectedMinggu = ref('');
const quickDateLabel = ref('');
let debounceTimer = null;

const currentMonthVal = computed(() => (new Date().getMonth() + 1).toString());
const currentYear = new Date().getFullYear();
const yearOptions = computed(() => {
  const years = [];
  const startYear = 2020;
  for (let y = currentYear + 1; y >= startYear; y--) {
    years.push(y);
  }
  return years;
});

const resetAllFilters = () => {
  searchQuery.value = '';
  selectedDate.value = '';
  selectedTahun.value = '';
  selectedBulan.value = '';
  selectedMinggu.value = '';
  quickDateLabel.value = '';
  currentPage.value = 1;
  fetchData();
};

const setQuickBulan = (bln) => {
  selectedDate.value = '';
  selectedBulan.value = bln;
  selectedMinggu.value = '';
  quickDateLabel.value = 'Bulan Ini';
  currentPage.value = 1;
  fetchData();
};

// Pagination State
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Modal Controls State
const showModalTambah = ref(false);
const showModalEdit = ref(false);
const showModalDetail = ref(false);
const showModalHapus = ref(false);

// Dynamic File Upload Fields Count
const berkasTambahCount = ref(10);
const berkasEditCount = ref(10);

const tambahFieldBerkasTambah = () => {
  berkasTambahCount.value++;
  formTambah.value[`file_berkas_${berkasTambahCount.value}`] = null;
};

const tambahFieldBerkasEdit = () => {
  berkasEditCount.value++;
  formEdit.value[`file_berkas_${berkasEditCount.value}`] = null;
};

const selectedPegawai = ref(null);
const pegawaiToDelete = ref(null);
const isLoadingDetail = ref(false);
const berkasExisting = ref([]);

// Form States
const formTambah = ref({
  judul: '',
  deskripsi: '',
  tanggal_upload: new Date().toISOString().split('T')[0],
  file_berkas_1: null,
  file_berkas_2: null,
  file_berkas_3: null,
  file_berkas_4: null,
  file_berkas_5: null,
  file_berkas_6: null,
  file_berkas_7: null,
  file_berkas_8: null,
  file_berkas_9: null,
  file_berkas_10: null,
});

const formEdit = ref({
  id: null,
  nama_lengkap: '',
  unit_departemen: '',
  tanggal_upload: '',
  file_berkas_1: null,
  file_berkas_2: null,
  file_berkas_3: null,
  file_berkas_4: null,
  file_berkas_5: null,
  file_berkas_6: null,
  file_berkas_7: null,
  file_berkas_8: null,
  file_berkas_9: null,
  file_berkas_10: null,
});

// Computed Properties
const todayDate = computed(() => new Date().toISOString().split('T')[0]);

const totalPages = computed(() => {
  return Math.ceil(pegawaiList.value.length / itemsPerPage.value) || 1;
});

const displayedPegawaiList = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return pegawaiList.value.slice(start, end);
});

const startIndex = computed(() => (currentPage.value - 1) * itemsPerPage.value);
const endIndex = computed(() => {
  const end = currentPage.value * itemsPerPage.value;
  return end > pegawaiList.value.length ? pegawaiList.value.length : end;
});

// Methods
const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const fetchData = async () => {
  isLoading.value = true;
  try {
    const params = {};
    if (searchQuery.value) params.search = searchQuery.value;
    if (selectedDate.value) params.tanggal_upload = selectedDate.value;
    if (selectedTahun.value) params.tahun = selectedTahun.value;
    if (selectedBulan.value) params.bulan = selectedBulan.value;
    if (selectedMinggu.value) params.minggu = selectedMinggu.value;

    const response = await axios.get('/api/admin/pegawai', { params });
    if (response.data.success) {
      statistics.value = response.data.statistics;
      pegawaiList.value = response.data.data;
      
      if (currentPage.value > totalPages.value) {
        currentPage.value = 1;
      }
    }
  } catch (error) {
    console.error('Gagal mengambil data diklat:', error);
  } finally {
    isLoading.value = false;
  }
};

const onFilterChange = () => {
  currentPage.value = 1;
  debounceFetch();
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
  currentPage.value = 1;
  fetchData();
};

// Modal Tambah Handlers
const openModalTambah = () => {
  berkasTambahCount.value = 10;
  formTambah.value = {
    judul: '',
    deskripsi: '',
    tanggal_upload: new Date().toISOString().split('T')[0],
    file_berkas_1: null,
    file_berkas_2: null,
    file_berkas_3: null,
    file_berkas_4: null,
    file_berkas_5: null,
    file_berkas_6: null,
    file_berkas_7: null,
    file_berkas_8: null,
    file_berkas_9: null,
    file_berkas_10: null,
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

  formData.append('nama_lengkap', formTambah.value.judul);
  formData.append('judul', formTambah.value.judul);
  formData.append('unit_departemen', formTambah.value.deskripsi || '');
  formData.append('deskripsi', formTambah.value.deskripsi || '');
  formData.append('tanggal_upload', formTambah.value.tanggal_upload);

  Object.keys(formTambah.value).forEach(key => {
    if (key.startsWith('file_berkas_') && formTambah.value[key]) {
      formData.append(key, formTambah.value[key]);
    }
  });

  try {
    const response = await axios.post('/api/admin/pegawai', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    if (response.data.success) {
      alert('Data Dokumentasi Diklat & Berkas berhasil disimpan!');
      closeModalTambah();
      fetchData();
    }
  } catch (error) {
    const msg = error.response?.data?.message || 'Gagal menyimpan data diklat';
    alert('Error: ' + msg);
  } finally {
    isSubmitting.value = false;
  }
};

// Modal Edit Handlers
const openModalEdit = async (item) => {
  berkasEditCount.value = 10;
  formEdit.value = {
    id: item.id,
    nama_lengkap: item.nama_lengkap || '',
    unit_departemen: item.unit_departemen || '',
    tanggal_upload: item.tanggal_upload || '',
    file_berkas_1: null,
    file_berkas_2: null,
    file_berkas_3: null,
    file_berkas_4: null,
    file_berkas_5: null,
    file_berkas_6: null,
    file_berkas_7: null,
    file_berkas_8: null,
    file_berkas_9: null,
    file_berkas_10: null,
  };

  berkasExisting.value = [];
  showModalEdit.value = true;

  try {
    const response = await axios.get('/api/admin/pegawai/' + item.id);
    if (response.data.success) {
      berkasExisting.value = response.data.data.berkas_pegawais || [];
    }
  } catch (err) {
    console.error('Gagal mengambil daftar berkas diklat:', err);
  }
};

const closeModalEdit = () => {
  showModalEdit.value = false;
};

const handleFileEdit = (e, key) => {
  if (e.target.files && e.target.files[0]) {
    formEdit.value[key] = e.target.files[0];
  }
};

const submitEditPegawai = async () => {
  isSubmittingEdit.value = true;
  const formData = new FormData();
  formData.append('_method', 'PUT');

  formData.append('nama_lengkap', formEdit.value.nama_lengkap);
  formData.append('judul', formEdit.value.nama_lengkap);
  formData.append('unit_departemen', formEdit.value.unit_departemen || '');
  formData.append('deskripsi', formEdit.value.unit_departemen || '');
  formData.append('tanggal_upload', formEdit.value.tanggal_upload);

  Object.keys(formEdit.value).forEach(key => {
    if (key.startsWith('file_berkas_') && formEdit.value[key]) {
      formData.append(key, formEdit.value[key]);
    }
  });

  try {
    const response = await axios.post(`/api/admin/pegawai/${formEdit.value.id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    if (response.data.success) {
      alert('Data Dokumentasi Diklat & Berkas berhasil diperbarui!');
      closeModalEdit();
      fetchData();
    }
  } catch (error) {
    const msg = error.response?.data?.message || 'Gagal memperbarui data diklat';
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
    alert('Gagal memuat detail diklat');
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
      alert('Data Dokumentasi Diklat & seluruh berkas PDF terlampir berhasil dihapus!');
      closeModalHapus();
      fetchData();
    }
  } catch (error) {
    const msg = error.response?.data?.message || 'Gagal menghapus diklat';
    alert('Error Hapus: ' + msg);
  } finally {
    isDeletingPegawai.value = false;
  }
};

// Download Semua Dokumen & Gambar Disatukan dalam 1 File PDF
const downloadZip = async (id, judulDiklat) => {
  isDownloadingZip.value = true;
  try {
    const response = await axios.get(`/api/admin/pegawai/${id}/download-zip`, {
      responseType: 'blob'
    });

    const cleanName = (judulDiklat || 'Diklat').replace(/[^a-zA-Z0-9_-]/g, '_');
    const blob = new Blob([response.data], { type: 'application/pdf' });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', `Arsip_Diklat_${cleanName}.pdf`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
  } catch {
    alert('Gagal mengunduh PDF gabungan. Pastikan diklat memiliki dokumen terunggah.');
  } finally {
    isDownloadingZip.value = false;
  }
};

// Helpers Action PDF
const viewPdf = (berkas) => {
  if (!berkas) return alert('File dokumen tidak ditemukan');
  if (typeof berkas === 'object' && berkas.id) {
    window.open(`/api/admin/berkas/${berkas.id}/preview`, '_blank');
    return;
  }
  const pathStr = typeof berkas === 'string' ? berkas : (berkas.file_path || '');
  if (!pathStr) return alert('File dokumen tidak ditemukan');
  const url = pathStr.startsWith('http') || pathStr.startsWith('/') ? pathStr : '/' + pathStr;
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

onMounted(() => {
  fetchData();
});
</script>