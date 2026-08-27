<template>
   <div class="w-full text-slate-100 font-sans pb-12 space-y-8" v-if="userData">
    
    <!-- 1. WELCOME HERO CARD -->
    <div class="bg-slate-900/60 border border-white/15 rounded-3xl p-6 sm:p-8 backdrop-blur-xl shadow-2xl relative overflow-hidden">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 border border-emerald-400/30 rounded-full text-xs text-emerald-300 font-semibold mb-2">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Peserta Diklat Terdaftar • RSU Bunda Thamrin</span>
          </div>
          <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight">
            Selamat Datang, {{ userData.nama }}!
          </h1>
         
        </div>
         

        <button 
          @click="showModalDetail = true" 
          class="inline-flex items-center gap-2 bg-white hover:bg-slate-100 text-slate-950 font-extrabold text-xs px-5 py-3 rounded-full shadow-lg transition-all active:scale-95 shrink-0"
        >
          <i class="bi bi-person-vcard-fill text-blue-600 text-base"></i>
          <span>Detail Registrasi</span>
        </button>
          
       
      </div>
    </div>

    <!-- 2. CARD PROFIL DATA DIRI PESERTA -->
    <div class="bg-slate-900/60 border border-white/15 rounded-3xl p-6 sm:p-8 backdrop-blur-xl shadow-2xl">
      <div class="border-b border-white/10 pb-4 mb-5 flex flex-wrap items-center justify-between gap-2">
        <h3 class="text-2xl font-black text-white">{{ userData.nama }}</h3>
        <span v-if="userData.jenis_kelamin === 'L'" class="px-3 py-1 bg-sky-500/20 text-sky-300 border border-sky-500/30 rounded-full text-xs font-bold">Laki-Laki</span>
        <span v-else class="px-3 py-1 bg-pink-500/20 text-pink-300 border border-pink-500/30 rounded-full text-xs font-bold">Perempuan</span>
      </div>
      <p class="text-xs sm:text-sm text-blue-400 font-mono font-bold mb-5">NIK: {{ userData.nik }}</p>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
        <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-white/10">
          <span class="text-slate-400 block text-xs uppercase font-bold mb-1">Email Resmi</span>
          <span class="text-white font-semibold break-all">{{ userData.email }}</span>
        </div>
        <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-white/10">
          <span class="text-slate-400 block text-xs uppercase font-bold mb-1">Jabatan</span>
          <span class="text-white font-semibold">{{ userData.jabatan || '-' }}</span>
        </div>
        <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-white/10">
          <span class="text-slate-400 block text-xs uppercase font-bold mb-1">No. HP / WhatsApp</span>
          <span class="text-white font-mono font-semibold">{{ userData.no_hp }}</span>
        </div>
        <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-white/10 sm:col-span-2">
          <span class="text-slate-400 block text-xs uppercase font-bold mb-1">Alamat Lengkap</span>
          <span class="text-slate-200 leading-relaxed">{{ userData.alamat }}</span>
        </div>
      </div>

      <div class="pt-5 flex flex-wrap items-center gap-3">
        <button
          @click="openModalEditProfile"
          class="inline-flex items-center gap-2 bg-white hover:bg-slate-100 text-slate-950 font-extrabold text-xs px-5 py-3 rounded-full shadow-lg transition-all active:scale-95 shrink-0"
        >
          <i class="bi bi-pencil-square"></i>
          <span>Edit Data Diri</span>
        </button>
      </div>
    </div>

    <!-- 2. RINGKASAN HASIL UJIAN (JIKA SUDAH SELESAI UJIAN) -->
    <div v-if="hasCompletedExam"
      class="bg-slate-900/60 border border-emerald-500/30 rounded-3xl overflow-hidden backdrop-blur-xl shadow-2xl">
      <div
        class="p-4 sm:p-5 bg-emerald-950/60 border-b border-emerald-500/20 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div class="flex items-center gap-2 text-emerald-300 font-bold text-sm">
          <i class="bi bi-trophy-fill text-amber-400 text-lg"></i>
          <span>Hasil Akhir Ujian Diklat Anda</span>
        </div>
        <span
          class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full text-xs font-bold shrink-0">
          ● Status: Lulus / Selesai
        </span>
      </div>

      <div class="p-6 sm:p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center text-center">

          <!-- SKOR UJIAN -->
          <div class="md:border-r border-white/10 p-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">SKOR NILAI UJIAN</span>
            <div class="text-5xl sm:text-6xl font-black font-mono text-emerald-400 tracking-tight">
              {{ latestExam.nilai_akhir }}
            </div>
          </div>

          <!-- BENAR & SALAH STATS -->
          <div class="md:border-r border-white/10 p-2 space-y-2">
            <div class="flex items-center justify-center gap-3">
              <span
                class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-lg text-xs font-bold">
                <i class="bi bi-check-circle-fill me-1"></i>Benar: {{ latestExam.jawaban_benar }}
              </span>
              <span
                class="px-3 py-1 bg-rose-500/20 text-rose-300 border border-rose-500/30 rounded-lg text-xs font-bold">
                <i class="bi bi-x-circle-fill me-1"></i>Salah: {{ latestExam.jawaban_salah }}
              </span>
            </div>
            <p class="text-xs text-slate-400">Total Soal Dijawab: {{ latestExam.total_soal }} Soal</p>
          </div>

          <!-- REVIEW ACTION -->
          <div class="p-2">
            <button @click="openModalReview"
              class="w-full py-3 bg-blue-600 hover:bg-blue-500 text-white font-extrabold text-xs uppercase tracking-wider rounded-full shadow-lg transition-all active:scale-95 inline-flex items-center justify-center gap-2">
              <i class="bi bi-journal-text text-base"></i>
              <span>Lihat Review Jawaban</span>
            </button>
          </div>

        </div>
      </div>
    </div>

    <!-- 3. GRID LAYOUT: TOKEN INPUT & SERTIFIKAT -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      <!-- KOTAK 1: ABSENSI & INPUT TOKEN UJIAN -->
      <div
        class="bg-slate-900/60 border border-white/15 rounded-3xl p-6 sm:p-8 backdrop-blur-xl shadow-2xl flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-2.5 text-blue-400 font-bold text-base mb-3 border-b border-white/10 pb-3">
            <i class="bi bi-key-fill text-xl"></i>
            <span>Akses Absensi & Ujian Diklat</span>
          </div>

          <p class="text-xs text-slate-300 leading-relaxed mb-6">
            Silakan minta <strong class="text-white">Kode Token Absensi</strong> kepada Admin Diklat. Token tersebut
            digunakan untuk membuka akses lembar ujian Anda.
          </p>

          <form @submit.prevent="masukUjian" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Kode Token Kunci
                Ujian</label>
              <input v-model="tokenInput" type="text" placeholder="Contoh: A7X9K2" :disabled="hasCompletedExam" required
                class="w-full px-4 py-3 bg-slate-950/70 border border-white/15 rounded-xl text-center text-lg sm:text-xl font-mono font-black tracking-widest text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase transition-all disabled:opacity-50 disabled:cursor-not-allowed" />
            </div>

            <button type="submit" :disabled="hasCompletedExam"
              class="w-full py-3.5 bg-blue-600 hover:bg-blue-500 disabled:bg-slate-800 disabled:text-slate-500 text-white font-extrabold text-xs uppercase tracking-wider rounded-full shadow-lg transition-all active:scale-95">
              {{ hasCompletedExam ? 'Ujian Telah Selesai' : 'Masuk Lembar Ujian' }}
            </button>
          </form>
        </div>
      </div>

      <!-- KOTAK 2: STATUS SERTIFIKAT HASIL UJIAN -->
      <div
        class="bg-slate-900/60 border border-white/15 rounded-3xl p-6 sm:p-8 backdrop-blur-xl shadow-2xl flex flex-col justify-between">
        <div>
          <div class="flex items-center gap-2.5 text-amber-400 font-bold text-base mb-3 border-b border-white/10 pb-3">
            <i class="bi bi-award-fill text-xl"></i>
            <span>Sertifikat Hasil Ujian Diklat</span>
          </div>

          <!-- IF COMPLETED -->
          <div v-if="hasCompletedExam" class="text-center py-4 space-y-3">
            <div
              class="w-16 h-16 mx-auto rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-3xl">
              <i class="bi bi-patch-check-fill"></i>
            </div>

            <div>
              <h4 class="font-extrabold text-emerald-400 text-lg">Sertifikat Anda Sudah Terbit!</h4>
              <p class="text-xs font-mono text-slate-300 mt-0.5">Nomor: {{ latestExam.nomor_sertifikat }}</p>
            </div>

            <a :href="'/api/ujian/sertifikat/' + latestExam.id" target="_blank"
              class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-500 text-white font-extrabold text-xs uppercase tracking-wider px-6 py-3 rounded-full shadow-lg transition-all active:scale-95 mt-2">
              <i class="bi bi-file-earmark-pdf-fill text-base"></i>
              <span>Cetak / Download Sertifikat (PDF)</span>
            </a>
          </div>

          <!-- IF LOCKED -->
          <div v-else class="text-center py-4 space-y-3">
            <div
              class="w-16 h-16 mx-auto rounded-full bg-slate-800 border border-white/10 flex items-center justify-center text-slate-500 text-3xl">
              <i class="bi bi-lock-fill"></i>
            </div>

            <div>
              <h4 class="font-bold text-slate-400 text-base">Sertifikat Masih Terkunci</h4>
              <p class="text-xs text-slate-500 max-w-xs mx-auto mt-1">Sertifikat otomatis terbuka dan bisa dicetak
                setelah Anda menyelesaikan ujian diklat.</p>
            </div>

            <button disabled
              class="px-6 py-2.5 bg-slate-800 text-slate-500 text-xs font-bold rounded-full cursor-not-allowed">
              Cetak Sertifikat (Terkunci)
            </button>
          </div>
        </div>
      </div>

    </div>

    <!-- MODAL 1: DETAIL DATA REGISTRASI -->
    <div v-if="showModalDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md overflow-y-auto">
      <div class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden text-slate-100 my-8">
        
        <div class="bg-slate-950/80 p-5 flex items-center justify-between border-b border-white/10">
          <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <i class="bi bi-person-lines-fill text-blue-400"></i>
            <span>Data Registrasi Peserta</span>
          </h3>
          <button @click="showModalDetail = false" class="text-slate-400 hover:text-white">
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>

        <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
          <h4 class="text-xs font-bold text-blue-400 uppercase tracking-wider mb-3">Data Diri Peserta</h4>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs bg-slate-950/60 p-4 rounded-2xl border border-white/10">
            <div><strong class="text-slate-400">Nama:</strong> <span class="text-white font-semibold">{{ userData.nama }}</span></div>
            <div><strong class="text-slate-400">NIK:</strong> <span class="text-white font-mono">{{ userData.nik }}</span></div>
            <div><strong class="text-slate-400">Email:</strong> <span class="text-white">{{ userData.email }}</span></div>
            <div><strong class="text-slate-400">Jabatan:</strong> <span class="text-white">{{ userData.jabatan || '-' }}</span></div>
            <div><strong class="text-slate-400">No HP:</strong> <span class="text-white font-mono">{{ userData.no_hp }}</span></div>
            <div><strong class="text-slate-400">Jenis Kelamin:</strong> <span class="text-white">{{ userData.jenis_kelamin === 'L' ? 'Laki-Laki' : 'Perempuan' }}</span></div>
            <div><strong class="text-slate-400">NPWP:</strong> <span class="text-white font-mono">{{ userData.npwp || '-' }}</span></div>
            <div class="sm:col-span-2"><strong class="text-slate-400">Alamat:</strong> <span class="text-white">{{ userData.alamat }}</span></div>
          </div>
        </div>

        <div class="p-4 bg-slate-950/80 border-t border-white/10 text-right">
          <button @click="showModalDetail = false" class="px-5 py-2 bg-white text-slate-950 font-bold text-xs rounded-full hover:bg-slate-200 transition-all">
            Tutup
          </button>
        </div>

      </div>
    </div>

  </div>

    <!-- MODAL 2: REVIEW JAWABAN UJIAN -->
    <div v-if="showModalReview && reviewData"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md overflow-y-auto">
      <div
        class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-3xl overflow-hidden text-slate-100 flex flex-col max-h-[85vh]">

        <div class="bg-slate-950/80 p-5 flex items-center justify-between border-b border-white/10 shrink-0">
          <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <i class="bi bi-journal-check text-blue-400"></i>
            <span>Review Jawaban Ujian Diklat</span>
          </h3>
          <button @click="showModalReview = false" class="text-slate-400 hover:text-white">
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>

        <div class="p-6 space-y-4 overflow-y-auto flex-grow">
          <div v-for="(detail, index) in reviewData.detail_jawaban" :key="detail.id"
            class="bg-slate-950/60 border rounded-2xl overflow-hidden"
            :class="detail.is_benar ? 'border-emerald-500/30' : 'border-rose-500/30'">
            <!-- Question Header -->
            <div class="p-3.5 px-5 flex items-center justify-between text-xs font-bold"
              :class="detail.is_benar ? 'bg-emerald-950/40 text-emerald-300' : 'bg-rose-950/40 text-rose-300'">
              <span>Soal No. {{ index + 1 }}</span>
              <span v-if="detail.is_benar"
                class="px-2.5 py-0.5 bg-emerald-500/20 border border-emerald-500/30 rounded-full flex items-center gap-1">
                <i class="bi bi-check-circle-fill"></i> BENAR
              </span>
              <span v-else
                class="px-2.5 py-0.5 bg-rose-500/20 border border-rose-500/30 rounded-full flex items-center gap-1">
                <i class="bi bi-x-circle-fill"></i> SALAH
              </span>
            </div>

            <!-- Question Body -->
            <div class="p-5 space-y-3">
              <p class="font-bold text-white text-sm leading-relaxed">{{ detail.soal.soal }}</p>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                <div
                  :class="{ 'font-bold text-emerald-400 bg-emerald-950/40 p-2 rounded-lg border border-emerald-500/30': detail.soal.kunci_jawaban === 'A', 'text-slate-300 p-2': detail.soal.kunci_jawaban !== 'A' }">
                  <strong>A.</strong> {{ detail.soal.opsi_a }}
                </div>
                <div
                  :class="{ 'font-bold text-emerald-400 bg-emerald-950/40 p-2 rounded-lg border border-emerald-500/30': detail.soal.kunci_jawaban === 'B', 'text-slate-300 p-2': detail.soal.kunci_jawaban !== 'B' }">
                  <strong>B.</strong> {{ detail.soal.opsi_b }}
                </div>
                <div
                  :class="{ 'font-bold text-emerald-400 bg-emerald-950/40 p-2 rounded-lg border border-emerald-500/30': detail.soal.kunci_jawaban === 'C', 'text-slate-300 p-2': detail.soal.kunci_jawaban !== 'C' }">
                  <strong>C.</strong> {{ detail.soal.opsi_c }}
                </div>
                <div
                  :class="{ 'font-bold text-emerald-400 bg-emerald-950/40 p-2 rounded-lg border border-emerald-500/30': detail.soal.kunci_jawaban === 'D', 'text-slate-300 p-2': detail.soal.kunci_jawaban !== 'D' }">
                  <strong>D.</strong> {{ detail.soal.opsi_d }}
                </div>
              </div>

              <div
                class="pt-3 border-t border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                <div>
                  Jawaban Anda:
                  <strong :class="detail.is_benar ? 'text-emerald-400' : 'text-rose-400'"
                    class="font-mono font-bold text-sm ml-1">
                    {{ detail.jawaban_user || 'Tidak Dijawab' }}
                  </strong>
                </div>
                <div>
                  Kunci Jawaban Benar:
                  <strong class="text-emerald-400 font-mono font-bold text-sm ml-1">
                    {{ detail.soal.kunci_jawaban }}
                  </strong>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="p-4 bg-slate-950/80 border-t border-white/10 text-right shrink-0">
          <button @click="showModalReview = false"
            class="px-6 py-2 bg-white text-slate-950 font-bold text-xs rounded-full">
            Tutup Review
          </button>
        </div>

      </div>
    </div>

    <!-- MODAL EDIT DATA PESERTA -->
    <div v-if="showModalEdit" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md overflow-y-auto">
      <div class="bg-slate-900 border border-white/20 rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden text-slate-100 my-8">
        
        <div class="bg-slate-950/80 p-5 flex items-center justify-between border-b border-white/10">
          <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <i class="bi bi-pencil-square text-amber-400"></i>
            <span>Edit Data Diri Peserta</span>
          </h3>
          <button @click="showModalEdit = false" class="text-slate-400 hover:text-white">
            <i class="bi bi-x-lg text-lg"></i>
          </button>
        </div>

        <form @submit.prevent="submitEditProfile" class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
          
          <div class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
              <div>
                <label class="block font-bold text-slate-300 mb-1">Nama Lengkap</label>
                <input v-model="formEdit.nama" type="text" required class="w-full px-3.5 py-2 bg-slate-950/70 border border-white/15 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
              </div>

              <div>
                <label class="block font-bold text-slate-300 mb-1">NIK (16 Digit)</label>
                <input v-model="formEdit.nik" type="text" required class="w-full px-3.5 py-2 bg-slate-950/70 border border-white/15 rounded-xl text-white font-mono focus:outline-none focus:ring-2 focus:ring-amber-500" />
              </div>

              <div>
                <label class="block font-bold text-slate-300 mb-1">Email Resmi</label>
                <input v-model="formEdit.email" type="email" required class="w-full px-3.5 py-2 bg-slate-950/70 border border-white/15 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
              </div>

              <div>
                <label class="block font-bold text-slate-300 mb-1">Jenis Kelamin</label>
                <select v-model="formEdit.jenis_kelamin" required class="w-full px-3.5 py-2 bg-slate-950/70 border border-white/15 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-amber-500">
                  <option class="bg-slate-900" value="L">Laki-Laki</option>
                  <option class="bg-slate-900" value="P">Perempuan</option>
                </select>
              </div>

              <div>
                <label class="block font-bold text-slate-300 mb-1">No. HP / WhatsApp</label>
                <input v-model="formEdit.no_hp" type="text" required class="w-full px-3.5 py-2 bg-slate-950/70 border border-white/15 rounded-xl text-white font-mono focus:outline-none focus:ring-2 focus:ring-amber-500" />
              </div>

              <div>
                <label class="block font-bold text-slate-300 mb-1">Jabatan</label>
                <input v-model="formEdit.jabatan" type="text" required class="w-full px-3.5 py-2 bg-slate-950/70 border border-white/15 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-amber-500" />
              </div>

              <div class="sm:col-span-2">
                <label class="block font-bold text-slate-300 mb-1">NPWP <span class="text-slate-500 font-normal">(Opsional)</span></label>
                <input v-model="formEdit.npwp" type="text" class="w-full px-3.5 py-2 bg-slate-950/70 border border-white/15 rounded-xl text-white font-mono focus:outline-none focus:ring-2 focus:ring-amber-500" />
              </div>

              <div class="sm:col-span-2">
                <label class="block font-bold text-slate-300 mb-1">Alamat Lengkap</label>
                <textarea v-model="formEdit.alamat" rows="2" required class="w-full px-3.5 py-2 bg-slate-950/70 border border-white/15 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
              </div>
            </div>
          </div>

          <div class="pt-2 flex items-center justify-end gap-2">
            <button type="button" @click="showModalEdit = false" class="px-5 py-2.5 bg-slate-800 text-slate-300 font-bold text-xs rounded-full hover:bg-slate-700 transition-all">
              Batal
            </button>
            <button type="submit" :disabled="isUpdatingProfile" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-400 disabled:bg-slate-800 text-slate-950 font-extrabold text-xs uppercase tracking-wider rounded-full shadow-lg transition-all active:scale-95 flex items-center gap-2">
              <span v-if="isUpdatingProfile" class="animate-spin rounded-full h-3.5 w-3.5 border-2 border-slate-950 border-t-transparent"></span>
              <span>{{ isUpdatingProfile ? 'Memproses Perubahan...' : 'Simpan Perubahan' }}</span>
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