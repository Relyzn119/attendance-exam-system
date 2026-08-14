<template>
  <div class="container my-4">
    <div class="card shadow-sm border-0">
      
      <!-- HEADER UJIAN -->
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Lembar Ujian Diklat RSU Bunda Thamrin</h5>
        <span class="badge bg-light text-primary fs-6 fw-bold">Soal {{ currentIndex + 1 }} dari {{ soalList.length }}</span>
      </div>

      <div class="card-body p-4">
        
        <!-- CARD PERTANYAAN -->
        <div v-if="currentSoal" class="card bg-light p-4 mb-4 border-0">
          <h5 class="fw-bold mb-3">{{ currentIndex + 1 }}. {{ currentSoal.soal }}</h5>

          <div class="d-flex flex-column gap-2">
            <div v-for="opt in ['A', 'B', 'C', 'D']" :key="opt" class="form-check p-3 rounded border bg-white">
              <input 
                type="radio" 
                :name="'soal-' + currentSoal.id" 
                :id="'opt-' + opt" 
                :value="opt" 
                v-model="answers[currentSoal.id]"
                class="form-check-input"
              />
              <label :for="'opt-' + opt" class="form-check-label w-100 fw-semibold cursor-pointer">
                <strong>{{ opt }}.</strong> {{ currentSoal['opsi_' + opt.toLowerCase()] }}
              </label>
            </div>
          </div>
        </div>

        <!-- NAVIGASI TOMBOL -->
        <div class="d-flex justify-content-between align-items-center">
          <button @click="prevSoal" :disabled="currentIndex === 0" class="btn btn-secondary px-4">
            <i class="bi bi-arrow-left me-1"></i> Sebelumnya
          </button>

          <button v-if="currentIndex < soalList.length - 1" @click="nextSoal" class="btn btn-primary px-4">
            Selanjutnya <i class="bi bi-arrow-right ms-1"></i>
          </button>

          <button v-else @click="submitAnswers" class="btn btn-success btn-lg px-5 fw-bold">
            <i class="bi bi-check-circle-fill me-1"></i> Selesaikan Ujian
          </button>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: ['riwayatId', 'soalData'],
  data() {
    return {
      soalList: this.soalData || [],
      currentIndex: 0,
      answers: {}
    };
  },
  computed: {
    currentSoal() {
      return this.soalList[this.currentIndex] || {};
    }
  },
  methods: {
    nextSoal() {
      if (this.currentIndex < this.soalList.length - 1) this.currentIndex++;
    },
    prevSoal() {
      if (this.currentIndex > 0) this.currentIndex--;
    },
    submitAnswers() {
      if (confirm('Apakah Anda yakin ingin menyelesaikan ujian ini? Jawaban tidak dapat diubah lagi.')) {
        const payload = this.soalList.map(s => ({
          soal_id: s.id,
          jawaban: this.answers[s.id] || null
        }));

        axios.post(`/api/ujian/submit/${this.riwayatId}`, { jawaban: payload }).then(res => {
          alert(res.data.message);
          this.$emit('examSubmitted');
        });
      }
    }
  }
};
</script>