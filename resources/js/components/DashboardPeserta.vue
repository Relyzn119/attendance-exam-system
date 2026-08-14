<template>
  <div class="container my-4" v-if="userData">
    
    <!-- WELCOME CARD -->
    <div class="card bg-success text-white shadow-sm mb-4">
      <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap">
        <div>
          <h3 class="fw-bold mb-1">Selamat Datang, {{ userData.nama }}!</h3>
          <p class="mb-0">NIK: {{ userData.nik }} | Email: {{ userData.email }} | No. HP: {{ userData.no_hp }}</p>
        </div>
        <button @click="showModalDetail = true" class="btn btn-light text-success fw-bold mt-2 mt-md-0">
          <i class="bi bi-person-lines-fill me-1"></i> Lihat Detail Data Registrasi
        </button>
      </div>
    </div>

    <!-- RINGKASAN HASIL UJIAN (JIKA SUDAH SELESAI UJIAN) -->
    <div v-if="hasCompletedExam" class="card shadow-sm border-success mb-4">
      <div class="card-header bg-success text-white fw-bold d-flex justify-content-between align-items-center">
        <span><i class="bi bi-trophy-fill me-2"></i>Hasil Akhir Ujian Diklat Anda</span>
        <span class="badge bg-light text-success fs-6">Status: Lulus / Selesai</span>
      </div>
      <div class="card-body p-4">
        <div class="row align-items-center text-center">
          
          <div class="col-md-4 border-end">
            <small class="text-muted fw-bold">SKOR NILAI UJIAN</small>
            <h1 class="display-3 fw-bold text-success mb-0">{{ latestExam.nilai_akhir }}</h1>
          </div>

          <div class="col-md-4 border-end">
            <div class="mb-2">
              <span class="badge bg-success fs-6 me-2"><i class="bi bi-check-circle me-1"></i>Benar: {{ latestExam.jawaban_benar }}</span>
              <span class="badge bg-danger fs-6"><i class="bi bi-x-circle me-1"></i>Salah: {{ latestExam.jawaban_salah }}</span>
            </div>
            <small class="text-muted">Total Soal: {{ latestExam.total_soal }} Soal</small>
          </div>

          <div class="col-md-4 mt-3 mt-md-0">
            <button @click="openModalReview" class="btn btn-outline-primary btn-lg w-100 fw-bold">
              <i class="bi bi-journal-text me-1"></i> Lihat Review Jawaban
            </button>
          </div>

        </div>
      </div>
    </div>

    <div class="row g-4">
      <!-- KOTAK 1: ABSENSI & INPUT TOKEN UJIAN -->
      <div class="col-md-6">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-primary text-white fw-bold">
            <i class="bi bi-key-fill me-2"></i>Akses Absensi & Ujian Diklat
          </div>
          <div class="card-body">
            <p class="text-muted small">
              Silakan minta <strong>Kode Token Absensi</strong> kepada Admin. Token tersebut digunakan untuk membuka akses lembar ujian Anda.
            </p>
            
            <form @submit.prevent="masukUjian">
              <div class="mb-3">
                <label class="form-label font-weight-bold">Kode Token Kunci Ujian</label>
                <input v-model="tokenInput" type="text" class="form-control form-control-lg text-uppercase fw-bold" placeholder="Contoh: A7X9K2" :disabled="hasCompletedExam" required />
              </div>

              <button type="submit" class="btn btn-primary w-100 btn-lg" :disabled="hasCompletedExam">
                {{ hasCompletedExam ? 'Anda Sudah Menyelesaikan Ujian' : 'Masuk Lembar Ujian' }}
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- KOTAK 2: STATUS SERTIFIKAT HASIL UJIAN -->
      <div class="col-md-6">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-dark text-white fw-bold">
            <i class="bi bi-award-fill me-2"></i>Sertifikat Hasil Ujian Diklat
          </div>
          <div class="card-body text-center d-flex flex-column justify-content-center align-items-center">
            
            <div v-if="hasCompletedExam" class="py-2">
              <i class="bi bi-patch-check-fill text-success display-3"></i>
              <h5 class="fw-bold text-success mt-2">Sertifikat Anda Sudah Terbit!</h5>
              <p class="text-muted small mb-3">Nomor: <strong>{{ latestExam.nomor_sertifikat }}</strong></p>
              
              <a :href="'/api/ujian/sertifikat/' + latestExam.id" target="_blank" class="btn btn-danger btn-lg">
                <i class="bi bi-file-pdf me-1"></i> Cetak / Download Sertifikat (PDF)
              </a>
            </div>

            <div v-else class="py-2 text-muted">
              <i class="bi bi-lock-fill display-3 text-secondary"></i>
              <h5 class="fw-bold mt-2 text-secondary">Sertifikat Masih Terkunci</h5>
              <p class="small">Sertifikat otomatis terbuka dan bisa dicetak setelah Anda menyelesaikan ujian diklat.</p>
              <button class="btn btn-secondary disabled opacity-50" disabled>
                Cetak Sertifikat (Terkunci)
              </button>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- MODAL 1: DETAIL DATA REGISTRASI (SESI 3) -->
    <div v-if="showModalDetail" class="modal fade show d-block bg-dark bg-opacity-50">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title fw-bold">Data Registrasi & Berkas Upload</h5>
            <button @click="showModalDetail = false" class="btn-close btn-close-white"></button>
          </div>
          <div class="modal-body">
            <h6 class="fw-bold text-success border-bottom pb-2">1. Data Diri Peserta</h6>
            <div class="row g-2 mb-3 small">
              <div class="col-6"><strong>Nama:</strong> {{ userData.nama }}</div>
              <div class="col-6"><strong>NIK:</strong> {{ userData.nik }}</div>
              <div class="col-6"><strong>Email:</strong> {{ userData.email }}</div>
              <div class="col-6"><strong>No HP:</strong> {{ userData.no_hp }}</div>
              <div class="col-12"><strong>Alamat:</strong> {{ userData.alamat }}</div>
            </div>
            <h6 class="fw-bold text-success border-bottom pb-2">2. Berkas Dokumen Terunggah</h6>
            <ul class="list-group list-group-flush small">
              <li v-for="b in userData.berkas" :key="b.id" class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-earmark-pdf text-danger me-2"></i>{{ b.jenis_berkas }}</span>
                <a :href="'/storage/' + b.file_path" target="_blank" class="btn btn-sm btn-outline-danger">Lihat File PDF</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL 2: REVIEW JAWABAN (SESI 8) -->
    <div v-if="showModalReview && reviewData" class="modal fade show d-block bg-dark bg-opacity-50">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title fw-bold"><i class="bi bi-journal-check me-2"></i>Review Jawaban Ujian Diklat</h5>
            <button @click="showModalReview = false" class="btn-close btn-close-white"></button>
          </div>
          <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
            
            <div v-for="(detail, index) in reviewData.detail_jawaban" :key="detail.id" class="card mb-3 border">
              <div class="card-header d-flex justify-content-between align-items-center" :class="detail.is_benar ? 'bg-success-subtle' : 'bg-danger-subtle'">
                <span class="fw-bold">Soal No. {{ index + 1 }}</span>
                <span v-if="detail.is_benar" class="badge bg-success"><i class="bi bi-check-circle me-1"></i>BENAR</span>
                <span v-else class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>SALAH</span>
              </div>
              <div class="card-body">
                <p class="fw-semibold mb-2">{{ detail.soal.soal }}</p>
                <div class="small mb-2">
                  <div :class="{ 'fw-bold text-success': detail.soal.kunci_jawaban === 'A' }">A. {{ detail.soal.opsi_a }}</div>
                  <div :class="{ 'fw-bold text-success': detail.soal.kunci_jawaban === 'B' }">B. {{ detail.soal.opsi_b }}</div>
                  <div :class="{ 'fw-bold text-success': detail.soal.kunci_jawaban === 'C' }">C. {{ detail.soal.opsi_c }}</div>
                  <div :class="{ 'fw-bold text-success': detail.soal.kunci_jawaban === 'D' }">D. {{ detail.soal.opsi_d }}</div>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center small">
                  <span>Jawaban Anda: <strong :class="detail.is_benar ? 'text-success' : 'text-danger'">{{ detail.jawaban_user || 'Tidak Dijawab' }}</strong></span>
                  <span>Kunci Jawaban Benar: <strong class="text-success">{{ detail.soal.kunci_jawaban }}</strong></span>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
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
      reviewData: null
    };
  },
  computed: {
    latestExam() {
      if (this.userData && this.userData.riwayat_ujian && this.userData.riwayat_ujian.length > 0) {
        return this.userData.riwayat_ujian[0];
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
        this.$emit('startExamNow', {
          riwayatId: res.data.riwayat_id,
          soal: res.data.soal
        });
      }).catch(err => {
        alert(err.response.data.message);
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