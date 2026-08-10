<template>
  <div class="container my-4">
    <!-- ================= BAGIAN ATAS: FITUR UJIAN ================= -->
    <div class="card mb-5 shadow-sm">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Ujian Diklat Pegawai Kontrak RSU Bunda Thamrin</h4>
      </div>
      <div class="card-body">
        
        <!-- Mode 1: Admin Generate Token & Form Masuk Ujian -->
        <div v-if="!isExamStarted && !isExamFinished" class="row">
          <!-- Kolom Admin Token -->
          <div class="col-md-5 border-end">
            <h5>[Admin] Generate Kode Ujian</h5>
            <button @click="generateToken" class="btn btn-warning fw-bold mb-3">
              Generate Kode Token Baru
            </button>
            <div v-if="generatedToken" class="alert alert-success">
              Kode Kunci Ujian: <strong>{{ generatedToken }}</strong>
            </div>
          </div>

          <!-- Kolom Masuk Ujian Pegawai -->
          <div class="col-md-7">
            <h5>Masuk Lembar Ujian</h5>
            <form @submit.prevent="mulaiUjian">
              <div class="mb-3">
                <label>NIK / NIP Pegawai Kontrak</label>
                <input v-model="formInput.nik_nip" type="text" class="form-control" placeholder="Contoh: KONTRAK-001" required />
              </div>
              <div class="mb-3">
                <label>Kode Token Kunci Ujian</label>
                <input v-model="formInput.kode_token" type="text" class="form-control" placeholder="Masukkan 6 Digit Token" required />
              </div>
              <button type="submit" class="btn btn-primary w-100">Mulai Dikerjakan</button>
            </form>
          </div>
        </div>

        <!-- Mode 2: Lembar Pengerjaan Ujian (25 Soal Acak) -->
        <div v-if="isExamStarted && !isExamFinished">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>Soal No. {{ currentQuestionIndex + 1 }} dari 25</h5>
            <span class="badge bg-danger">Waktu Soal Ini: {{ currentQuestionTimer }} detik</span>
          </div>

          <div class="card bg-light p-3 mb-3">
            <p class="fs-5">{{ currentQuestion.soal }}</p>
            <div v-for="opt in ['A', 'B', 'C', 'D']" :key="opt" class="form-check mb-2">
              <input 
                type="radio" 
                :name="'soal-' + currentQuestion.id" 
                :id="'opt-' + opt" 
                :value="opt" 
                v-model="userAnswers[currentQuestion.id]"
                class="form-check-input"
              />
              <label :for="'opt-' + opt" class="form-check-label">
                <strong>{{ opt }}.</strong> {{ currentQuestion['opsi_' + opt.toLowerCase()] }}
              </label>
            </div>
          </div>

          <div class="d-flex justify-content-between">
            <button @click="prevSoal" :disabled="currentQuestionIndex === 0" class="btn btn-secondary">Kembali</button>
            <button v-if="currentQuestionIndex < 24" @click="nextSoal" class="btn btn-primary">Selanjutnya</button>
            <button v-else @click="submitUjian" class="btn btn-success">Selesaikan Ujian</button>
          </div>
        </div>

        <!-- Mode 3: Ujian Selesai & Sertifikat Otomatis -->
        <div v-if="isExamFinished" class="text-center py-4">
          <h3 class="text-success">Selamat! Ujian Telah Selesai</h3>
          <p class="fs-4">Nilai Akhir Anda: <strong>{{ finalResult.nilai }}</strong></p>
          <a :href="'/api/ujian/sertifikat/' + riwayatId" target="_blank" class="btn btn-danger btn-lg mt-2">
            <i class="bi bi-file-pdf"></i> Download Sertifikat Hasil Ujian (PDF)
          </a>
          <br>
          <button @click="resetForm" class="btn btn-link mt-3">Kembali ke Halaman Utama Ujian</button>
        </div>

      </div>
    </div>

    <!-- ================= BAGIAN BAWAH: MONITORING & RIWAYAT (ADMIN) ================= -->
    <div class="card shadow-sm">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Riwayat & Monitoring Progres Hasil Ujian Pegawai</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead>
              <tr>
                <th>No</th>
                <th>Pegawai</th>
                <th>Jabatan / Unit</th>
                <th>Nilai Akhir</th>
                <th>Benar / Salah</th>
                <th>Waktu Selesai</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in monitoringList" :key="item.id">
                <td>{{ index + 1 }}</td>
                <td>
                  <strong>{{ item.pegawai.nama_lengkap }}</strong><br>
                  <small class="text-muted">NIK: {{ item.pegawai.nik_nip }}</small>
                </td>
                <td>{{ item.pegawai.jabatan }} ({{ item.pegawai.unit_kerja }})</td>
                <td><span class="badge bg-success fs-6">{{ item.nilai_akhir }}</span></td>
                <td>{{ item.jawaban_benar }} Benar / {{ item.jawaban_salah }} Salah</td>
                <td>{{ formatTanggal(item.waktu_selesai) }}</td>
                <td>
                  <button @click="openDetailAnalisis(item)" class="btn btn-sm btn-info text-white">
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