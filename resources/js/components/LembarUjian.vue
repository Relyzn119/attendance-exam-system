<template>
  <div class="w-full text-slate-100 font-sans pb-12 space-y-6">
    
    <!-- HEADER LEMBAR UJIAN & COUNTDOWN TIMER -->
    <div class="bg-slate-900/70 border border-white/15 rounded-3xl p-5 sm:p-6 backdrop-blur-xl shadow-2xl sticky top-4 z-40">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-500/10 border border-blue-400/30 rounded-full text-xs text-blue-300 font-semibold mb-1">
            <span>RSU Bunda Thamrin • Ujian Diklat</span>
          </div>
          <h2 class="text-xl sm:text-2xl font-black text-white flex items-center gap-2">
            <i class="bi bi-pencil-square text-blue-400"></i>
            <span>Lembar Pengerjaan Ujian</span>
          </h2>
        </div>

        <!-- COUNTDOWN TIMER BADGE DINAMIS -->
        <div class="flex items-center gap-3 self-start md:self-auto">
          <div 
            class="px-4 py-2.5 rounded-2xl border font-mono font-black text-sm sm:text-base flex items-center gap-2 shadow-lg transition-all"
            :class="remainingSeconds < 300 ? 'bg-rose-950/80 border-rose-500 text-rose-300 animate-pulse' : 'bg-slate-950/80 border-amber-500/50 text-amber-300'"
          >
            <i class="bi bi-clock-history text-lg"></i>
            <span>Sisa Waktu: {{ formattedTime }}</span>
          </div>

          <span class="px-3.5 py-2 bg-slate-800 border border-white/10 rounded-2xl text-xs font-mono font-bold text-slate-300">
            Soal {{ currentIndex + 1 }} / {{ soalList.length }}
          </span>
        </div>

      </div>
    </div>

    <!-- SOAL & OPSI JAWABAN CARD -->
    <div v-if="currentSoal" class="bg-slate-900/60 border border-white/15 rounded-3xl p-6 sm:p-8 backdrop-blur-xl shadow-2xl space-y-6">
      
      <!-- PERTANYAAN SOAL -->
      <div class="space-y-2">
        <span class="text-xs font-bold text-blue-400 uppercase tracking-wider block">Pertanyaan Nomor {{ currentIndex + 1 }}</span>
        <p class="text-base sm:text-lg font-bold text-white leading-relaxed">
          {{ currentSoal.soal }}
        </p>
      </div>

      <!-- OPSI PILIHAN JAWABAN (A, B, C, D) -->
      <div class="grid grid-cols-1 gap-3">
        <div 
          v-for="opt in ['A', 'B', 'C', 'D']" 
          :key="opt"
          @click="answers[currentSoal.id] = opt"
          class="p-4 rounded-2xl border transition-all cursor-pointer flex items-center gap-3.5 group"
          :class="answers[currentSoal.id] === opt ? 'bg-blue-600/20 border-blue-500 text-white shadow-lg' : 'bg-slate-950/50 border-white/10 text-slate-300 hover:bg-white/5 hover:border-white/20'"
        >
          <div 
            class="w-8 h-8 rounded-xl font-mono font-bold text-xs flex items-center justify-center shrink-0 transition-all"
            :class="answers[currentSoal.id] === opt ? 'bg-blue-600 text-white' : 'bg-slate-800 text-slate-400 group-hover:bg-slate-700 group-hover:text-white'"
          >
            {{ opt }}
          </div>
          <span class="text-sm font-medium leading-relaxed">
            {{ currentSoal['opsi_' + opt.toLowerCase()] }}
          </span>
        </div>
      </div>

      <!-- NAVIGASI TOMBOL SEBELUMNYA / SELANJUTNYA / SUBMIT -->
      <div class="pt-4 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3">
        <button 
          @click="prevSoal" 
          :disabled="currentIndex === 0" 
          class="w-full sm:w-auto px-5 py-2.5 bg-slate-800 hover:bg-slate-700 disabled:opacity-40 disabled:cursor-not-allowed text-slate-300 font-bold text-xs rounded-full transition-all inline-flex items-center justify-center gap-2"
        >
          <i class="bi bi-arrow-left"></i>
          <span>Soal Sebelumnya</span>
        </button>

        <div class="w-full sm:w-auto flex items-center gap-2">
          <button 
            v-if="currentIndex < soalList.length - 1" 
            @click="nextSoal" 
            class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-full shadow-lg transition-all inline-flex items-center justify-center gap-2"
          >
            <span>Soal Selanjutnya</span>
            <i class="bi bi-arrow-right"></i>
          </button>

          <button 
            v-else 
            @click="confirmSubmitAnswers" 
            class="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs uppercase tracking-wider rounded-full shadow-lg transition-all active:scale-95 inline-flex items-center justify-center gap-2"
          >
            <i class="bi bi-check-circle-fill"></i>
            <span>Selesaikan Ujian</span>
          </button>
        </div>
      </div>

    </div>

  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    riwayatId: [Number, String],
    soalData: Array,
    durasiMenit: {
      type: Number,
      default: 60
    }
  },
  data() {
    return {
      soalList: this.soalData || [],
      currentIndex: 0,
      answers: {},
      remainingSeconds: (this.durasiMenit || 60) * 60, // Konversi menit ke detik
      timerInterval: null
    };
  },
  computed: {
    currentSoal() {
      return this.soalList[this.currentIndex] || {};
    },
    formattedTime() {
      const hours = Math.floor(this.remainingSeconds / 3600);
      const minutes = Math.floor((this.remainingSeconds % 3600) / 60);
      const seconds = this.remainingSeconds % 60;

      const pad = (num) => String(num).padStart(2, '0');

      if (hours > 0) {
        return `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
      }
      return `${pad(minutes)}:${pad(seconds)}`;
    }
  },
  mounted() {
    this.startCountdownTimer();
  },
  beforeUnmount() {
    this.stopCountdownTimer();
  },
  methods: {
    startCountdownTimer() {
      this.stopCountdownTimer();
      this.timerInterval = setInterval(() => {
        if (this.remainingSeconds > 0) {
          this.remainingSeconds--;
        } else {
          this.stopCountdownTimer();
          alert('Waktu pengerjaan ujian telah habis! Jawaban Anda otomatis tersimpan.');
          this.submitAnswers(true); // Auto submit saat waktu habis
        }
      }, 1000);
    },
    stopCountdownTimer() {
      if (this.timerInterval) {
        clearInterval(this.timerInterval);
        this.timerInterval = null;
      }
    },
    nextSoal() {
      if (this.currentIndex < this.soalList.length - 1) this.currentIndex++;
    },
    prevSoal() {
      if (this.currentIndex > 0) this.currentIndex--;
    },
    confirmSubmitAnswers() {
      if (confirm('Apakah Anda yakin ingin menyelesaikan ujian ini? Jawaban yang sudah dikirim tidak dapat diubah lagi.')) {
        this.submitAnswers(false);
      }
    },
    submitAnswers(isAutoSubmit = false) {
      this.stopCountdownTimer();

      const payload = this.soalList.map(s => ({
        soal_id: s.id,
        jawaban: this.answers[s.id] || null
      }));

      axios.post(`/api/ujian/submit/${this.riwayatId}`, { jawaban: payload }).then(res => {
        if (!isAutoSubmit) {
          alert(res.data.message || 'Ujian berhasil diselesaikan!');
        }
        this.$emit('examSubmitted');
      }).catch(err => {
        alert('Terjadi kesalahan saat menyimpan jawaban: ' + (err.response?.data?.message || err.message));
      });
    }
  }
};
</script>