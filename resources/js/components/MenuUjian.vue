<template>
  <div class="container my-6 text-slate-800 font-sans relative z-20">
    <!-- ================= BAGIAN ATAS: FITUR UJIAN ================= -->
    <div class="glass-pearl-card hover-tilt-card rounded-3xl mb-8 overflow-hidden">
      <div class="bg-gradient-to-r from-amber-500/20 via-amber-500/10 to-yellow-500/20 p-5 sm:p-6 border-b border-amber-200/60 flex items-center justify-between">
        <h4 class="mb-0 font-black text-slate-900 flex items-center gap-2.5 text-lg sm:text-xl">
          <i class="bi bi-file-earmark-text-fill text-amber-600"></i>
          <span>Ujian Diklat Pegawai Kontrak RSU Bunda Thamrin</span>
        </h4>
      </div>
      <div class="p-6 sm:p-8">
        
        <!-- Mode 1: Admin Generate Token & Form Masuk Ujian -->
        <div v-if="!isExamStarted && !isExamFinished" class="grid grid-cols-1 md:grid-cols-12 gap-8">
          <!-- Kolom Admin Token -->
          <div class="md:col-span-5 border-b md:border-b-0 md:border-r border-amber-200/60 pb-6 md:pb-0 md:pr-8">
            <h5 class="font-extrabold text-slate-900 mb-3 flex items-center gap-2">
              <i class="bi bi-shield-lock-fill text-amber-500"></i>
              <span>[Admin] Generate Kode Ujian</span>
            </h5>
            <button @click="generateToken" class="btn-gold-shimmer font-extrabold text-xs px-5 py-3 rounded-xl shadow-md mb-4 flex items-center gap-2">
              <i class="bi bi-arrow-repeat"></i>
              <span>Generate Kode Token Baru</span>
            </button>
            <div v-if="generatedToken" class="bg-amber-50 border border-amber-300 p-4 rounded-2xl text-amber-900 font-bold text-sm shadow-xs animate-shake">
              Kode Kunci Ujian: <strong class="text-amber-700 font-mono text-lg ml-1">{{ generatedToken }}</strong>
            </div>
          </div>

          <!-- Kolom Masuk Ujian Pegawai -->
          <div class="md:col-span-7">
            <h5 class="font-extrabold text-slate-900 mb-4 flex items-center gap-2">
              <i class="bi bi-door-open-fill text-amber-500"></i>
              <span>Masuk Lembar Ujian</span>
            </h5>
            <form @submit.prevent="mulaiUjian" class="space-y-4">
              <div>
                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">NIK / NIP Pegawai Kontrak</label>
                <input v-model="formInput.nik_nip" type="text" class="w-full px-4 py-3 bg-white/90 border border-amber-200/80 rounded-xl text-slate-900 font-mono font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all shadow-xs" placeholder="Contoh: KONTRAK-001" required />
              </div>
              <div>
                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Kode Token Kunci Ujian</label>
                <input v-model="formInput.kode_token" type="text" class="w-full px-4 py-3 bg-white/90 border border-amber-200/80 rounded-xl text-slate-900 font-mono font-black text-center tracking-widest text-lg placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500 uppercase transition-all shadow-xs" placeholder="Masukkan 6 Digit Token" required />
              </div>
              <button type="submit" class="w-full py-3.5 btn-gold-shimmer font-black text-xs uppercase tracking-wider rounded-xl shadow-lg transition-all active:scale-95">Mulai Dikerjakan</button>
            </form>
          </div>
        </div>

        <!-- Mode 2: Lembar Pengerjaan Ujian (25 Soal Acak) -->
        <div v-if="isExamStarted && !isExamFinished" class="space-y-5">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-amber-50/80 p-4 rounded-2xl border border-amber-200">
            <h5 class="font-extrabold text-slate-900 mb-0">Soal No. {{ currentQuestionIndex + 1 }} dari 25</h5>
            <span class="px-3.5 py-1.5 bg-rose-100 text-rose-800 border border-rose-300 rounded-full text-xs font-black animate-pulse">
              Waktu Soal Ini: {{ currentQuestionTimer }} detik
            </span>
          </div>

          <div class="bg-white/90 p-6 rounded-2xl border border-amber-200 shadow-xs space-y-4">
            <p class="text-base sm:text-lg font-bold text-slate-900 leading-relaxed">{{ currentQuestion.soal }}</p>
            <div class="space-y-2.5">
              <div v-for="opt in ['A', 'B', 'C', 'D']" :key="opt" 
                @click="userAnswers[currentQuestion.id] = opt"
                class="p-3.5 rounded-xl border transition-all cursor-pointer flex items-center gap-3"
                :class="userAnswers[currentQuestion.id] === opt ? 'bg-amber-100 border-amber-400 text-amber-900 shadow-xs' : 'bg-white border-slate-200 hover:border-amber-300 text-slate-800'"
              >
                <div class="w-7 h-7 rounded-lg font-bold text-xs flex items-center justify-center shrink-0"
                     :class="userAnswers[currentQuestion.id] === opt ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600'">
                  {{ opt }}
                </div>
                <span class="text-sm font-semibold">{{ currentQuestion['opsi_' + opt.toLowerCase()] }}</span>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-between gap-3 pt-2">
            <button @click="prevSoal" :disabled="currentQuestionIndex === 0" class="px-5 py-2.5 bg-white hover:bg-slate-100 border border-slate-300 disabled:opacity-40 text-slate-800 font-bold text-xs rounded-xl transition-all">Kembali</button>
            <button v-if="currentQuestionIndex < 24" @click="nextSoal" class="px-6 py-2.5 btn-gold-shimmer font-bold text-xs rounded-xl shadow-md transition-all">Selanjutnya</button>
            <button v-else @click="submitUjian" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-md transition-all">Selesaikan Ujian</button>
          </div>
        </div>

        <!-- Mode 3: Ujian Selesai & Sertifikat Otomatis -->
        <div v-if="isExamFinished" class="text-center py-6 space-y-4">
          <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-3xl shadow-lg">
            <i class="bi bi-patch-check-fill"></i>
          </div>
          <h3 class="text-emerald-800 font-black text-2xl">Selamat! Ujian Telah Selesai</h3>
          <p class="text-xl font-bold text-slate-800">Nilai Akhir Anda: <strong class="text-emerald-600 font-mono text-3xl">{{ finalResult.nilai }}</strong></p>
          <a :href="'/api/ujian/sertifikat/' + riwayatId" target="_blank" class="inline-flex items-center gap-2 px-6 py-3.5 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg transition-all active:scale-95">
            <i class="bi bi-file-earmark-pdf-fill text-base"></i>
            <span>Download Sertifikat Hasil Ujian (PDF)</span>
          </a>
          <br>
          <button @click="resetForm" class="text-amber-700 hover:text-amber-900 font-bold text-xs underline mt-3">Kembali ke Halaman Utama Ujian</button>
        </div>

      </div>
    </div>

    <!-- ================= BAGIAN BAWAH: MONITORING & RIWAYAT (ADMIN) ================= -->
    <div class="glass-pearl-table shadow-2xl">
      <div class="p-5 sm:p-6 bg-gradient-to-r from-amber-500/15 via-amber-400/10 to-yellow-500/15 border-b border-amber-200/60">
        <h5 class="mb-0 font-black text-slate-900 flex items-center gap-2">
          <i class="bi bi-graph-up-arrow text-amber-600"></i>
          <span>Riwayat & Monitoring Progres Hasil Ujian Pegawai</span>
        </h5>
      </div>
      <div class="p-6">
        <div class="overflow-x-auto rounded-2xl border border-amber-300/40 bg-white/70">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-amber-100/80 border-b border-amber-300/40 text-xs font-black text-amber-950 uppercase tracking-wider">
                <th class="py-3.5 px-4 text-center">No</th>
                <th class="py-3.5 px-5">Pegawai</th>
                <th class="py-3.5 px-5">Jabatan / Unit</th>
                <th class="py-3.5 px-4 text-center">Nilai Akhir</th>
                <th class="py-3.5 px-5 text-center">Benar / Salah</th>
                <th class="py-3.5 px-5 text-center">Waktu Selesai</th>
                <th class="py-3.5 px-5 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-amber-200/40 text-xs sm:text-sm">
              <tr v-for="(item, index) in monitoringList" :key="item.id" class="hover:bg-amber-100/40 transition-colors">
                <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-600">{{ index + 1 }}</td>
                <td class="py-3.5 px-5">
                  <strong class="text-slate-900 block font-bold">{{ item.pegawai.nama_lengkap }}</strong>
                  <span class="text-[11px] text-slate-500 font-mono font-semibold">NIK: {{ item.pegawai.nik_nip }}</span>
                </td>
                <td class="py-3.5 px-5 text-slate-700 font-medium">{{ item.pegawai.jabatan }} ({{ item.pegawai.unit_kerja }})</td>
                <td class="py-3.5 px-4 text-center">
                  <span class="px-3 py-1 bg-emerald-100 text-emerald-900 border border-emerald-300 rounded-full font-mono font-black text-sm shadow-xs">{{ item.nilai_akhir }}</span>
                </td>
                <td class="py-3.5 px-5 text-center text-slate-700 font-bold">{{ item.jawaban_benar }} Benar / {{ item.jawaban_salah }} Salah</td>
                <td class="py-3.5 px-5 text-center font-mono text-slate-600 text-xs font-semibold">{{ formatTanggal(item.waktu_selesai) }}</td>
                <td class="py-3.5 px-5 text-center">
                  <button @click="openDetailAnalisis(item)" class="px-3 py-1.5 bg-amber-100 hover:bg-amber-500 text-amber-900 hover:text-white rounded-xl border border-amber-300 font-bold text-xs shadow-xs transition-all">
                    Detail Waktu & Jawaban
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- MODAL DETAIL ANALISIS WAKTU PER SOAL -->
    <div v-if="selectedAnalisis" class="modal fade show d-block bg-dark bg-opacity-50">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Analisis Pengerjaan: {{ selectedAnalisis.pegawai.nama_lengkap }}</h5>
            <button @click="selectedAnalisis = null" class="btn-close"></button>
          </div>
          <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Soal</th>
                  <th>Jawaban User</th>
                  <th>Status</th>
                  <th>Waktu Pengerjaan</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(detail, idx) in selectedAnalisis.detail_jawaban" :key="detail.id">
                  <td>{{ idx + 1 }}</td>
                  <td>{{ detail.soal.soal }}</td>
                  <td><strong>{{ detail.jawaban_user || '-' }}</strong></td>
                  <td>
                    <span v-if="detail.is_benar" class="badge bg-success">Benar</span>
                    <span v-else class="badge bg-danger">Salah</span>
                  </td>
                  <td>{{ detail.durasi_detik }} Detik</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      generatedToken: '',
      formInput: { nik_nip: '', kode_token: '' },
      isExamStarted: false,
      isExamFinished: false,
      riwayatId: null,
      questions: [],
      currentQuestionIndex: 0,
      userAnswers: {},
      questionDurations: {},
      timerInterval: null,
      currentQuestionTimer: 0,
      finalResult: {},
      monitoringList: [],
      selectedAnalisis: null
    };
  },
  computed: {
    currentQuestion() {
      return this.questions[this.currentQuestionIndex] || {};
    }
  },
  mounted() {
    this.fetchMonitoringData();
  },
  methods: {
    generateToken() {
      axios.post('/api/ujian/generate-token').then(res => {
        this.generatedToken = res.data.token;
      });
    },
    mulaiUjian() {
      axios.post('/api/ujian/mulai', this.formInput).then(res => {
        this.riwayatId = res.data.riwayat_id;
        this.questions = res.data.soal;
        this.isExamStarted = true;
        this.startTimerForQuestion();
      }).catch(err => {
        alert(err.response.data.message);
      });
    },
    startTimerForQuestion() {
      clearInterval(this.timerInterval);
      const qId = this.currentQuestion.id;
      if (!this.questionDurations[qId]) this.questionDurations[qId] = 0;
      
      this.currentQuestionTimer = this.questionDurations[qId];
      this.timerInterval = setInterval(() => {
        this.questionDurations[qId]++;
        this.currentQuestionTimer++;
      }, 1000);
    },
    nextSoal() {
      if (this.currentQuestionIndex < 24) {
        this.currentQuestionIndex++;
        this.startTimerForQuestion();
      }
    },
    prevSoal() {
      if (this.currentQuestionIndex > 0) {
        this.currentQuestionIndex--;
        this.startTimerForQuestion();
      }
    },
    submitUjian() {
      clearInterval(this.timerInterval);
      const payloadAnswers = this.questions.map(q => ({
        soal_id: q.id,
        jawaban: this.userAnswers[q.id] || null,
        durasi_detik: this.questionDurations[q.id] || 0
      }));

      axios.post(`/api/ujian/submit/${this.riwayatId}`, { jawaban: payloadAnswers }).then(res => {
        this.finalResult = res.data;
        this.isExamFinished = true;
        this.fetchMonitoringData(); // Refresh data monitoring di bawah
      });
    },
    fetchMonitoringData() {
      axios.get('/api/ujian/monitoring').then(res => {
        this.monitoringList = res.data;
      });
    },
    openDetailAnalisis(item) {
      this.selectedAnalisis = item;
    },
    resetForm() {
      this.isExamStarted = false;
      this.isExamFinished = false;
      this.formInput = { nik_nip: '', kode_token: '' };
      this.userAnswers = {};
      this.questionDurations = {};
      this.currentQuestionIndex = 0;
    },
    formatTanggal(datetime) {
      return new Date(datetime).toLocaleString('id-ID');
    }
  }
};
</script>