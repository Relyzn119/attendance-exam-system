<template>
   <div class="w-full text-slate-800 font-sans pb-12 space-y-8 relative z-20" v-if="userData">
    
    <!-- 1. WELCOME HERO CARD LIGHT PEARL GOLD -->
    <div class="glass-pearl-card hover-tilt-card rounded-3xl p-6 sm:p-9 relative overflow-hidden">
      <!-- Decorative Glows -->
      <div class="absolute -top-16 -right-16 w-36 h-36 bg-amber-400/20 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-16 -left-16 w-36 h-36 bg-yellow-300/30 rounded-full blur-3xl pointer-events-none"></div>

      <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
        <div>
          <div class="inline-flex items-center gap-2 px-3.5 py-1 bg-emerald-100/90 border border-emerald-300/60 rounded-full text-xs text-emerald-900 font-extrabold tracking-wider uppercase mb-3 shadow-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Peserta Diklat Terdaftar • RSU Bunda Thamrin</span>
          </div>
          <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">
            Selamat Datang, <span class="text-gold-gradient">{{ userData.nama }}</span>!
          </h1>
          <p class="text-xs sm:text-sm text-slate-600 font-medium mt-1">
            Akses portal diklat medis, token ujian interaktif, & e-sertifikat resmi karyawan.
          </p>
        </div>

        <button 
          @click="showModalDetail = true" 
          class="btn-gold-shimmer font-black text-xs px-6 py-3.5 rounded-full shadow-lg transition-all active:scale-95 shrink-0 flex items-center gap-2"
        >
          <i class="bi bi-person-vcard-fill text-base"></i>
          <span>Detail Registrasi</span>
        </button>
      </div>
    </div>

    <!-- 2. CARD PROFIL DATA DIRI PESERTA -->
    <div class="glass-pearl-card hover-tilt-card rounded-3xl p-6 sm:p-8">
      <div class="border-b border-amber-200/60 pb-4 mb-5 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 text-white font-black flex items-center justify-center text-xl shadow-md">
            <i class="bi bi-person-badge"></i>
          </div>
          <div>
            <h3 class="text-xl sm:text-2xl font-black text-slate-900">{{ userData.nama }}</h3>
            <p class="text-xs font-mono font-bold text-amber-800">NIK: {{ userData.nik }}</p>
          </div>
        </div>

        <span v-if="userData.jenis_kelamin === 'L'" class="px-3.5 py-1.5 bg-sky-100/90 text-sky-800 border border-sky-300 rounded-full text-xs font-extrabold shadow-xs">
          <i class="bi bi-gender-male me-1"></i>Laki-Laki
        </span>
        <span v-else class="px-3.5 py-1.5 bg-pink-100/90 text-pink-800 border border-pink-300 rounded-full text-xs font-extrabold shadow-xs">
          <i class="bi bi-gender-female me-1"></i>Perempuan
        </span>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm">
        <div class="bg-white/80 p-4 rounded-2xl border border-amber-200/60 shadow-xs">
          <span class="text-amber-800 block text-xs uppercase font-extrabold mb-1">Email Resmi</span>
          <span class="text-slate-900 font-bold break-all">{{ userData.email }}</span>
        </div>
        <div class="bg-white/80 p-4 rounded-2xl border border-amber-200/60 shadow-xs">
          <span class="text-amber-800 block text-xs uppercase font-extrabold mb-1">Jabatan / Unit</span>
          <span class="text-slate-900 font-bold">{{ userData.jabatan || '-' }}</span>
        </div>
        <div class="bg-white/80 p-4 rounded-2xl border border-amber-200/60 shadow-xs">
          <span class="text-amber-800 block text-xs uppercase font-extrabold mb-1">No. HP / WhatsApp</span>
          <span class="text-slate-900 font-mono font-bold">{{ userData.no_hp }}</span>
        </div>
        <div class="bg-white/80 p-4 rounded-2xl border border-amber-200/60 shadow-xs sm:col-span-2">
          <span class="text-amber-800 block text-xs uppercase font-extrabold mb-1">Alamat Lengkap</span>
          <span class="text-slate-700 font-medium leading-relaxed">{{ userData.alamat }}</span>
        </div>
      </div>

      <div class="pt-6 flex flex-wrap items-center gap-3 border-t border-amber-200/40 mt-5">
        <button
          @click="openModalEditProfile"
          class="btn-gold-shimmer font-extrabold text-xs px-5 py-3 rounded-full shadow-md transition-all active:scale-95 shrink-0 flex items-center gap-2"
        >
          <i class="bi bi-pencil-square"></i>
          <span>Edit Data Diri</span>
        </button>

        <button
          @click="openModalChangePassword"
          class="bg-white hover:bg-amber-50 text-slate-800 font-extrabold text-xs px-5 py-3 rounded-full border border-amber-300 shadow-md transition-all active:scale-95 shrink-0 flex items-center gap-2"
        >
          <i class="bi bi-key-fill text-amber-600"></i>
          <span>Ganti Password</span>
        </button>
      </div>
    </div>

    <!-- 3. RINGKASAN HASIL UJIAN (JIKA SUDAH SELESAI UJIAN) -->
    <div v-if="hasCompletedExam"
      class="glass-pearl-card hover-tilt-card border border-emerald-300/80 rounded-3xl overflow-hidden shadow-xl">
      <div
        class="p-4 sm:p-5 bg-gradient-to-r from-emerald-500/20 to-teal-500/10 border-b border-emerald-300/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2 text-emerald-900 font-black text-sm sm:text-base">
          <i class="bi bi-trophy-fill text-amber-500 text-xl"></i>
          <span>Hasil Akhir Ujian Diklat Anda</span>
        </div>
        <span
          class="px-3.5 py-1.5 bg-emerald-100 text-emerald-900 border border-emerald-300 rounded-full text-xs font-extrabold shrink-0 shadow-xs">
          ● Status: Lulus / Selesai
        </span>
      </div>

      <div class="p-6 sm:p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center text-center">

          <!-- SKOR UJIAN -->
          <div class="md:border-r border-amber-200/60 p-2">
            <span class="text-xs font-extrabold text-amber-800 uppercase tracking-wider block mb-1">SKOR NILAI UJIAN</span>
            <div class="text-5xl sm:text-6xl font-black font-mono text-emerald-600 tracking-tight">
              {{ latestExam.nilai_akhir }}
            </div>
          </div>

          <!-- BENAR & SALAH STATS -->
          <div class="md:border-r border-amber-200/60 p-2 space-y-3">
            <div class="flex items-center justify-center gap-3">
              <span
                class="px-3.5 py-1.5 bg-emerald-100 text-emerald-900 border border-emerald-300 rounded-xl text-xs font-black">
                <i class="bi bi-check-circle-fill me-1 text-emerald-600"></i>Benar: {{ latestExam.jawaban_benar }}
              </span>
              <span
                class="px-3.5 py-1.5 bg-rose-100 text-rose-900 border border-rose-300 rounded-xl text-xs font-black">
                <i class="bi bi-x-circle-fill me-1 text-rose-600"></i>Salah: {{ latestExam.jawaban_salah }}
              </span>
            </div>
            <p class="text-xs text-slate-600 font-medium">Total Soal Dijawab: {{ latestExam.total_soal }} Soal</p>
          </div>

          <!-- REVIEW ACTION -->
          <div class="p-2">
            <button @click="openModalReview"
              class="w-full py-3.5 bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-md transition-all active:scale-95 inline-flex items-center justify-center gap-2 cursor-pointer">
              <i class="bi bi-journal-text text-base"></i>
              <span>Lihat Review Jawaban</span>
            </button>
          </div>

        </div>
      </div>
    </div>

    <!-- 4. GRID LAYOUT: TOKEN INPUT & SERTIFIKAT -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      <!-- KOTAK 1: ABSENSI & INPUT TOKEN UJIAN -->
      <div
        class="glass-pearl-card hover-tilt-card rounded-3xl p-6 sm:p-8 flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-2.5 text-amber-800 font-black text-base mb-3 border-b border-amber-200/60 pb-3">
            <i class="bi bi-key-fill text-xl text-amber-500"></i>
            <span>Akses Absensi & Ujian Diklat</span>
          </div>

          <p class="text-xs text-slate-600 leading-relaxed mb-6 font-medium">
            Masukkan <strong class="text-slate-900">Kode Token Absensi / Ujian</strong> yang diberikan oleh Admin Diklat untuk membuka akses lembar pengerjaan.
          </p>

          <form @submit.prevent="masukUjian" class="space-y-4">
            <div v-if="hasCompletedExam" class="text-[11px] text-amber-900 font-extrabold bg-amber-100/90 border border-amber-300/80 p-3 rounded-xl flex items-center gap-2">
              <i class="bi bi-info-circle-fill text-amber-600 text-sm shrink-0"></i>
              <span>Ujian selesai! Anda tetap dapat menginput Kode Token baru untuk Absensi Kehadiran di pertemuan berikutnya.</span>
            </div>

            <div>
              <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">Kode Token Kunci Absensi / Ujian</label>
              <input v-model="tokenInput" type="text" placeholder="Contoh: A7X9K2" required
                class="w-full px-4 py-3.5 bg-white/90 border border-amber-300 rounded-xl text-center text-lg sm:text-2xl font-mono font-black tracking-widest text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 uppercase transition-all shadow-xs" />
            </div>

            <button type="submit"
              class="w-full py-4 btn-gold-shimmer font-black text-xs uppercase tracking-wider rounded-xl shadow-lg transition-all active:scale-95 cursor-pointer">
              Input Token Absensi / Ujian
            </button>
          </form>
        </div>
      </div>

      <!-- KOTAK 2: STATUS SERTIFIKAT HASIL UJIAN -->
      <div
        class="glass-pearl-card hover-tilt-card rounded-3xl p-6 sm:p-8 flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-2.5 text-amber-800 font-black text-base mb-3 border-b border-amber-200/60 pb-3">
            <i class="bi bi-award-fill text-xl text-amber-500"></i>
            <span>Sertifikat Hasil Ujian Diklat</span>
          </div>

          <!-- IF COMPLETED -->
          <div v-if="hasCompletedExam" class="text-center py-4 space-y-3">
            <div
              class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white flex items-center justify-center text-3xl shadow-lg shadow-emerald-500/30">
              <i class="bi bi-patch-check-fill"></i>
            </div>

            <div>
              <h4 class="font-black text-emerald-800 text-lg">Sertifikat Anda Sudah Terbit!</h4>
              <p class="text-xs font-mono text-slate-600 mt-0.5 font-bold">Nomor: {{ latestExam.nomor_sertifikat }}</p>
            </div>

            <a :href="'/api/ujian/sertifikat/' + latestExam.id" target="_blank"
              class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs uppercase tracking-wider px-6 py-3.5 rounded-xl shadow-lg transition-all active:scale-95 mt-2">
              <i class="bi bi-file-earmark-pdf-fill text-base"></i>
              <span>Cetak / Download Sertifikat (PDF)</span>
            </a>
          </div>

          <!-- IF LOCKED -->
          <div v-else class="text-center py-4 space-y-3">
            <div
              class="w-16 h-16 mx-auto rounded-2xl bg-amber-100 border border-amber-300 flex items-center justify-center text-amber-700 text-3xl shadow-inner">
              <i class="bi bi-lock-fill"></i>
            </div>

            <div>
              <h4 class="font-bold text-slate-700 text-base">Sertifikat Masih Terkunci</h4>
              <p class="text-xs text-slate-500 max-w-xs mx-auto mt-1 font-medium">Sertifikat otomatis terbuka dan bisa dicetak setelah Anda menyelesaikan ujian diklat.</p>
            </div>

            <button disabled
              class="px-6 py-3 bg-slate-200 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed">
              Cetak Sertifikat (Terkunci)
            </button>
          </div>
        </div>
      </div>

    </div>

    <!-- MODAL 1: DETAIL DATA REGISTRASI -->
    <div v-if="showModalDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md overflow-y-auto">
      <div class="glass-pearl-card rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden text-slate-800 my-8 border border-amber-300">
        
        <div class="bg-amber-100/80 p-5 flex items-center justify-between border-b border-amber-200">
          <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
            <i class="bi bi-person-lines-fill text-amber-600"></i>
            <span>Data Registrasi Peserta</span>
          </h3>
          <button @click="showModalDetail = false" class="text-slate-500 hover:text-slate-900">
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>

        <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
          <h4 class="text-xs font-extrabold text-amber-800 uppercase tracking-wider mb-3">Data Diri Peserta</h4>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs bg-white/80 p-5 rounded-2xl border border-amber-200/80 shadow-xs">
            <div><strong class="text-slate-500">Nama:</strong> <span class="text-slate-900 font-bold">{{ userData.nama }}</span></div>
            <div><strong class="text-slate-500">NIK:</strong> <span class="text-slate-900 font-mono font-bold">{{ userData.nik }}</span></div>
            <div><strong class="text-slate-500">Email:</strong> <span class="text-slate-900 font-bold">{{ userData.email }}</span></div>
            <div><strong class="text-slate-500">Jabatan:</strong> <span class="text-slate-900 font-bold">{{ userData.jabatan || '-' }}</span></div>
            <div><strong class="text-slate-500">No HP:</strong> <span class="text-slate-900 font-mono font-bold">{{ userData.no_hp }}</span></div>
            <div><strong class="text-slate-500">Jenis Kelamin:</strong> <span class="text-slate-900 font-bold">{{ userData.jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}</span></div>
            <div><strong class="text-slate-500">NPWP:</strong> <span class="text-slate-900 font-mono font-bold">{{ userData.npwp || '-' }}</span></div>
            <div class="sm:col-span-2"><strong class="text-slate-500">Alamat:</strong> <span class="text-slate-900 font-semibold">{{ userData.alamat }}</span></div>
          </div>
        </div>

        <div class="p-4 bg-amber-50/80 border-t border-amber-200 text-right">
          <button @click="showModalDetail = false" class="btn-gold-shimmer px-6 py-2.5 font-extrabold text-xs rounded-xl shadow-md">
            Tutup
          </button>
        </div>

      </div>
    </div>

  </div>

    <!-- MODAL 2: REVIEW JAWABAN UJIAN -->
    <div v-if="showModalReview && reviewData"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md overflow-y-auto">
      <div
        class="glass-pearl-card border border-amber-300/60 rounded-3xl shadow-2xl w-full max-w-3xl overflow-hidden text-slate-800 flex flex-col max-h-[85vh]">

        <div class="bg-gradient-to-r from-amber-500/15 via-amber-400/10 to-yellow-500/15 p-5 flex items-center justify-between border-b border-amber-300/40 shrink-0">
          <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
            <i class="bi bi-journal-check text-amber-600"></i>
            <span>Review Jawaban Ujian Diklat</span>
          </h3>
          <button @click="showModalReview = false" class="text-slate-500 hover:text-rose-600 p-1.5 rounded-xl hover:bg-rose-50 transition-colors">
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>

        <div class="p-6 space-y-4 overflow-y-auto flex-grow">
          <div v-for="(detail, index) in reviewData.detail_jawaban" :key="detail.id"
            class="bg-white/80 border rounded-2xl overflow-hidden shadow-xs"
            :class="detail.is_benar ? 'border-emerald-300' : 'border-rose-300'">
            <!-- Question Header -->
            <div class="p-3.5 px-5 flex items-center justify-between text-xs font-bold"
              :class="detail.is_benar ? 'bg-emerald-100/90 text-emerald-900' : 'bg-rose-100/90 text-rose-900'">
              <span>Soal No. {{ index + 1 }}</span>
              <span v-if="detail.is_benar"
                class="px-2.5 py-0.5 bg-emerald-200 border border-emerald-400 text-emerald-950 rounded-full flex items-center gap-1 font-extrabold">
                <i class="bi bi-check-circle-fill text-emerald-600"></i> BENAR
              </span>
              <span v-else
                class="px-2.5 py-0.5 bg-rose-200 border border-rose-400 text-rose-950 rounded-full flex items-center gap-1 font-extrabold">
                <i class="bi bi-x-circle-fill text-rose-600"></i> SALAH
              </span>
            </div>

            <!-- Question Body -->
            <div class="p-5 space-y-3">
              <p class="font-bold text-slate-900 text-sm leading-relaxed">{{ detail.soal.soal }}</p>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                <div
                  :class="{ 'font-bold text-emerald-900 bg-emerald-100 p-2.5 rounded-xl border border-emerald-300': detail.soal.kunci_jawaban === 'A', 'text-slate-700 p-2.5 bg-slate-50/60 rounded-xl border border-slate-200/60': detail.soal.kunci_jawaban !== 'A' }">
                  <strong>A.</strong> {{ detail.soal.opsi_a }}
                </div>
                <div
                  :class="{ 'font-bold text-emerald-900 bg-emerald-100 p-2.5 rounded-xl border border-emerald-300': detail.soal.kunci_jawaban === 'B', 'text-slate-700 p-2.5 bg-slate-50/60 rounded-xl border border-slate-200/60': detail.soal.kunci_jawaban !== 'B' }">
                  <strong>B.</strong> {{ detail.soal.opsi_b }}
                </div>
                <div
                  :class="{ 'font-bold text-emerald-400 bg-emerald-100 p-2.5 rounded-xl border border-emerald-300': detail.soal.kunci_jawaban === 'C', 'text-slate-700 p-2.5 bg-slate-50/60 rounded-xl border border-slate-200/60': detail.soal.kunci_jawaban !== 'C' }">
                  <strong>C.</strong> {{ detail.soal.opsi_c }}
                </div>
                <div
                  :class="{ 'font-bold text-emerald-400 bg-emerald-100 p-2.5 rounded-xl border border-emerald-300': detail.soal.kunci_jawaban === 'D', 'text-slate-700 p-2.5 bg-slate-50/60 rounded-xl border border-slate-200/60': detail.soal.kunci_jawaban !== 'D' }">
                  <strong>D.</strong> {{ detail.soal.opsi_d }}
                </div>
              </div>

              <div
                class="pt-3 border-t border-amber-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                <div>
                  Jawaban Anda:
                  <strong :class="detail.is_benar ? 'text-emerald-700' : 'text-rose-700'"
                    class="font-mono font-bold text-sm ml-1">
                    {{ detail.jawaban_user || 'Tidak Dijawab' }}
                  </strong>
                </div>
                <div>
                  Kunci Jawaban Benar:
                  <strong class="text-emerald-700 font-mono font-bold text-sm ml-1">
                    {{ detail.soal.kunci_jawaban }}
                  </strong>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="p-4 bg-amber-50/60 border-t border-amber-200/60 text-right shrink-0">
          <button @click="showModalReview = false"
            class="px-6 py-2.5 btn-gold-shimmer text-white font-bold text-xs rounded-xl shadow-md cursor-pointer">
            Tutup Review
          </button>
        </div>

      </div>
    </div>

    <!-- MODAL EDIT DATA PESERTA -->
    <div v-if="showModalEdit" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md overflow-y-auto">
      <div class="glass-pearl-card border border-amber-300/60 rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden text-slate-800 my-8">
        
        <div class="bg-gradient-to-r from-amber-500/15 via-amber-400/10 to-yellow-500/15 p-5 flex items-center justify-between border-b border-amber-300/40">
          <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
            <i class="bi bi-pencil-square text-amber-600"></i>
            <span>Edit Data Diri Peserta</span>
          </h3>
          <button @click="showModalEdit = false" class="text-slate-500 hover:text-rose-600 p-1.5 rounded-xl hover:bg-rose-50 transition-colors">
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>

        <form @submit.prevent="submitEditProfile" class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
          
          <div class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
              <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lengkap</label>
                <input v-model="formEdit.nama" type="text" required class="w-full px-3.5 py-2.5 bg-white/90 border border-amber-300/80 rounded-xl text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 shadow-xs" />
              </div>

              <div>
                <label class="block font-bold text-slate-700 mb-1">NIK (16 Digit)</label>
                <input v-model="formEdit.nik" type="text" required class="w-full px-3.5 py-2.5 bg-white/90 border border-amber-300/80 rounded-xl text-slate-900 font-mono font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 shadow-xs" />
              </div>

              <div>
                <label class="block font-bold text-slate-700 mb-1">Email Resmi</label>
                <input v-model="formEdit.email" type="email" required class="w-full px-3.5 py-2.5 bg-white/90 border border-amber-300/80 rounded-xl text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 shadow-xs" />
              </div>

              <div>
                <label class="block font-bold text-slate-700 mb-1">Jenis Kelamin</label>
                <select v-model="formEdit.jenis_kelamin" required class="w-full px-3.5 py-2.5 bg-white/90 border border-amber-300/80 rounded-xl text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 shadow-xs [color-scheme:light]">
                  <option value="L">Laki-Laki</option>
                  <option value="P">Perempuan</option>
                </select>
              </div>

              <div>
                <label class="block font-bold text-slate-700 mb-1">No. HP / WhatsApp</label>
                <input v-model="formEdit.no_hp" type="text" required class="w-full px-3.5 py-2.5 bg-white/90 border border-amber-300/80 rounded-xl text-slate-900 font-mono font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 shadow-xs" />
              </div>

              <div>
                <label class="block font-bold text-slate-700 mb-1">Jabatan</label>
                <input v-model="formEdit.jabatan" type="text" required class="w-full px-3.5 py-2.5 bg-white/90 border border-amber-300/80 rounded-xl text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 shadow-xs" />
              </div>

              <div class="sm:col-span-2">
                <label class="block font-bold text-slate-700 mb-1">NPWP <span class="text-slate-500 font-normal">(Opsional)</span></label>
                <input v-model="formEdit.npwp" type="text" class="w-full px-3.5 py-2.5 bg-white/90 border border-amber-300/80 rounded-xl text-slate-900 font-mono font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 shadow-xs" />
              </div>

              <div class="sm:col-span-2">
                <label class="block font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                <textarea v-model="formEdit.alamat" rows="2" required class="w-full px-3.5 py-2.5 bg-white/90 border border-amber-300/80 rounded-xl text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 shadow-xs"></textarea>
              </div>
            </div>
          </div>

          <div class="pt-2 flex items-center justify-end gap-2 border-t border-amber-200/60">
            <button type="button" @click="showModalEdit = false" class="px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 font-bold text-xs rounded-full transition-all shadow-xs">
              Batal
            </button>
            <button type="submit" :disabled="isUpdatingProfile" class="px-6 py-2.5 btn-gold-shimmer text-white font-black text-xs uppercase tracking-wider rounded-full shadow-lg transition-all active:scale-95 flex items-center gap-2 cursor-pointer">
              <span v-if="isUpdatingProfile" class="animate-spin rounded-full h-3.5 w-3.5 border-2 border-white border-t-transparent"></span>
              <span>{{ isUpdatingProfile ? 'Memproses Perubahan...' : 'Simpan Perubahan' }}</span>
            </button>
          </div>

        </form>

      </div>
    </div>

    <!-- MODAL GANTI PASSWORD -->
    <div v-if="showModalPassword" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md overflow-y-auto">
      <div class="glass-pearl-card border border-amber-300/60 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden text-slate-800 my-8">
        
        <div class="bg-gradient-to-r from-amber-500/15 via-amber-400/10 to-yellow-500/15 p-5 flex items-center justify-between border-b border-amber-300/40">
          <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
            <i class="bi bi-key-fill text-amber-600"></i>
            <span>Ganti Password Peserta</span>
          </h3>
          <button @click="showModalPassword = false" class="text-slate-500 hover:text-rose-600 p-1.5 rounded-xl hover:bg-rose-50 transition-colors">
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>

        <form @submit.prevent="submitChangePassword" class="p-6 space-y-4">
          <div>
            <label class="block font-bold text-xs text-amber-900 mb-1 uppercase tracking-wider">Password Lama</label>
            <input v-model="formPassword.current_password" type="password" required placeholder="Masukkan password lama Anda" class="w-full px-3.5 py-2.5 bg-white/90 border border-amber-300/80 rounded-xl text-slate-900 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 shadow-xs" />
          </div>

          <div>
            <label class="block font-bold text-xs text-amber-900 mb-1 uppercase tracking-wider">Password Baru</label>
            <input v-model="formPassword.new_password" type="password" required minlength="6" placeholder="Minimal 6 karakter" class="w-full px-3.5 py-2.5 bg-white/90 border border-amber-300/80 rounded-xl text-slate-900 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 shadow-xs" />
          </div>

          <div>
            <label class="block font-bold text-xs text-amber-900 mb-1 uppercase tracking-wider">Konfirmasi Password Baru</label>
            <input v-model="formPassword.new_password_confirmation" type="password" required minlength="6" placeholder="Ulangi password baru" class="w-full px-3.5 py-2.5 bg-white/90 border border-amber-300/80 rounded-xl text-slate-900 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 shadow-xs" />
          </div>

          <div class="pt-3 flex items-center justify-end gap-2 border-t border-amber-200/60">
            <button type="button" @click="showModalPassword = false" class="px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 font-bold text-xs rounded-full transition-all shadow-xs">
              Batal
            </button>
            <button type="submit" :disabled="isChangingPassword" class="px-6 py-2.5 btn-gold-shimmer text-white font-black text-xs uppercase tracking-wider rounded-full shadow-lg transition-all active:scale-95 flex items-center gap-2 cursor-pointer">
              <span v-if="isChangingPassword" class="animate-spin rounded-full h-3.5 w-3.5 border-2 border-white border-t-transparent"></span>
              <span>{{ isChangingPassword ? 'Memproses...' : 'Simpan Password Baru' }}</span>
            </button>
          </div>
        </form>

      </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ['user'],
 data() {
    return {
      userData: null,
      tokenInput: '',
      showModalDetail: false,
      showModalReview: false,
      reviewData: null,

      // STATE EDIT PROFIL & BERKAS
      showModalEdit: false,
      isUpdatingProfile: false,
      formEdit: {
        nama: '',
        email: '',
        nik: '',
        jenis_kelamin: 'L',
        jabatan: '',
        no_hp: '',
        alamat: '',
        npwp: ''
      },

      // STATE GANTI PASSWORD
      showModalPassword: false,
      isChangingPassword: false,
      formPassword: {
        current_password: '',
        new_password: '',
        new_password_confirmation: ''
      }
    };
  },
  computed: {
    
    pasFotoUrl() {
      return 'https://ui-avatars.com/api/?name=' + encodeURIComponent(this.userData?.nama || 'Peserta') + '&background=0D8ABC&color=fff&size=256';
    },
    latestExam() {
      if (this.userData && this.userData.riwayat_ujian && this.userData.riwayat_ujian.length > 0) {
        return [...this.userData.riwayat_ujian].sort((a, b) => b.id - a.id)[0];
      }
      return null;
    },
    hasCompletedExam() {
      return this.latestExam && this.latestExam.status === 'selesai';
    }
  },
  mounted() {
    this.fetchProfile();
  },

  methods: {
      openModalEditProfile() {
      if (!this.userData) return;
      this.formEdit = {
        nama: this.userData.nama || '',
        email: this.userData.email || '',
        nik: this.userData.nik || '',
        jenis_kelamin: this.userData.jenis_kelamin || 'L',
        jabatan: this.userData.jabatan || '',
        no_hp: this.userData.no_hp || '',
        alamat: this.userData.alamat || '',
        npwp: this.userData.npwp || ''
      };

      this.showModalEdit = true;
    },
    submitEditProfile() {
      this.isUpdatingProfile = true;

      const formData = new FormData();
      Object.keys(this.formEdit).forEach(key => {
        formData.append(key, this.formEdit[key] || '');
      });

      axios.post(`/api/peserta/update-profile/${this.userData.id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then(res => {
        this.isUpdatingProfile = false;
        alert(res.data.message || 'Profil berhasil diperbarui!');
        this.userData = res.data.user;
        this.showModalEdit = false;
      }).catch(err => {
        this.isUpdatingProfile = false;
        alert('Gagal memperbarui profil: ' + (err.response?.data?.message || err.message));
      });
    },

    openModalChangePassword() {
      this.formPassword = {
        current_password: '',
        new_password: '',
        new_password_confirmation: ''
      };
      this.showModalPassword = true;
    },
    submitChangePassword() {
      if (this.formPassword.new_password !== this.formPassword.new_password_confirmation) {
        alert('Konfirmasi password baru tidak cocok!');
        return;
      }
      this.isChangingPassword = true;
      axios.post(`/api/change-password/${this.userData.id}`, this.formPassword).then(res => {
        this.isChangingPassword = false;
        alert(res.data.message || 'Password berhasil diperbarui!');
        this.showModalPassword = false;
      }).catch(err => {
        this.isChangingPassword = false;
        alert(err.response?.data?.message || 'Gagal memperbarui password.');
      });
    },

    fetchProfile() {
      axios.get(`/api/user-profile/${this.user.id}`).then(res => {
        this.userData = res.data;
      });
    },
  masukUjian() {
      axios.post('/api/ujian/mulai', {
        user_id: this.userData.id,
        token: this.tokenInput
      }).then(res => {
        if (res.data.tipe_token === 'absensi') {
          alert(res.data.message || 'Absensi berhasil dicatat untuk hari ini!');
          this.tokenInput = '';
          this.fetchProfile();
          return;
        }

        this.$emit('startExamNow', {
          riwayatId: res.data.riwayat_id,
          soal: res.data.soal,
          durasi_menit: res.data.durasi_menit || 60
        });
      }).catch(err => {
        alert(err.response?.data?.message || 'Terjadi kesalahan saat masuk ujian.');
      });
    },
    openModalReview() {
      axios.get(`/api/ujian/review/${this.latestExam.id}`).then(res => {
        this.reviewData = res.data.riwayat;
        this.showModalReview = true;
      });
    }
  }
};
</script>