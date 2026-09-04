<template>
  <div class="w-full text-slate-800 font-sans pb-12 space-y-6 relative z-20">
    
    <!-- HEADER LEMBAR UJIAN & COUNTDOWN TIMER GLOW PULSE -->
    <div class="glass-pearl-card rounded-3xl p-5 sm:p-7 sticky top-4 z-40 shadow-xl border border-amber-300">
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        
        <div>
          <div class="inline-flex items-center gap-2 px-3.5 py-1 bg-amber-100/90 border border-amber-300/60 rounded-full text-xs text-amber-900 font-extrabold uppercase mb-1 shadow-xs">
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            <span>RSU Bunda Thamrin • Ujian Diklat Medis</span>
          </div>
          <h2 class="text-xl sm:text-2xl font-black text-slate-900 flex items-center gap-2 tracking-tight">
            <i class="bi bi-pencil-square text-amber-600"></i>
            <span>Lembar Pengerjaan Ujian</span>
          </h2>
        </div>

        <!-- COUNTDOWN TIMER BADGE DINAMIS GLOW PULSE -->
        <div class="flex items-center gap-3 self-start md:self-auto">
          <div 
            class="px-5 py-2.5 rounded-2xl border font-mono font-black text-sm sm:text-base flex items-center gap-2.5 shadow-md transition-all duration-300"
            :class="remainingSeconds < 600 ? 'bg-rose-50 border-rose-400 text-rose-700 animate-pulse ring-4 ring-rose-300/50' : 'bg-white/90 border-amber-300 text-amber-800 ring-2 ring-amber-400/20'"
          >
            <i class="bi bi-clock-history text-xl" :class="remainingSeconds < 600 ? 'text-rose-600 animate-spin' : 'text-amber-500'"></i>
            <span>Sisa Waktu: {{ formattedTime }}</span>
          </div>

          <span class="px-4 py-2.5 bg-amber-50 border border-amber-200 rounded-2xl text-xs font-mono font-black text-amber-900 shadow-xs">
            Soal {{ currentIndex + 1 }} / {{ soalList.length }}
          </span>
        </div>

      </div>
    </div>

    <!-- MAIN EXAM CONTAINER WITH QUESTION SHEET & NAVIGATOR GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

      <!-- COLUMN 1: QUESTION & OPTIONS SHEET -->
      <div v-if="currentSoal" class="lg:col-span-8 glass-pearl-card hover-tilt-card rounded-3xl p-6 sm:p-8 space-y-6">
        
        <!-- PERTANYAAN SOAL -->
        <div class="space-y-2.5">
          <div class="flex items-center justify-between border-b border-amber-200/60 pb-3">
            <span class="text-xs font-extrabold text-amber-800 uppercase tracking-wider block">Pertanyaan Soal Nomor {{ currentIndex + 1 }}</span>
            <span v-if="answers[currentSoal.id]" class="text-[11px] font-extrabold text-emerald-700 bg-emerald-100 border border-emerald-300 px-3 py-1 rounded-full flex items-center gap-1">
              <i class="bi bi-check-circle-fill"></i> Sudah Dijawab ({{ answers[currentSoal.id] }})
            </span>
            <span v-else class="text-[11px] font-extrabold text-amber-800 bg-amber-100 border border-amber-300 px-3 py-1 rounded-full">
              Belum Dijawab
            </span>
          </div>

          <p class="text-base sm:text-lg font-bold text-slate-900 leading-relaxed pt-2">
            {{ currentSoal.soal }}
          </p>
        </div>

        <!-- OPSI PILIHAN JAWABAN (A, B, C, D) -->
        <div class="grid grid-cols-1 gap-3.5 pt-2">
          <div 
            v-for="opt in ['A', 'B', 'C', 'D']" 
            :key="opt"
            @click="answers[currentSoal.id] = opt"
            class="p-4 rounded-2xl border transition-all duration-200 cursor-pointer flex items-center gap-4 group"
            :class="answers[currentSoal.id] === opt ? 'bg-amber-100/90 border-amber-400 text-amber-950 ring-2 ring-amber-400/50 shadow-md' : 'bg-white/80 border-amber-200/80 text-slate-800 hover:bg-amber-50/60 hover:border-amber-300'"
          >
            <div 
              class="w-9 h-9 rounded-xl font-mono font-black text-sm flex items-center justify-center shrink-0 transition-all"
              :class="answers[currentSoal.id] === opt ? 'bg-gradient-to-br from-amber-400 to-amber-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 group-hover:bg-amber-200 group-hover:text-amber-900'"
            >
              {{ opt }}
            </div>
            <span class="text-sm font-semibold leading-relaxed">
              {{ currentSoal['opsi_' + opt.toLowerCase()] }}
            </span>
          </div>
        </div>

        <!-- NAVIGASI TOMBOL SEBELUMNYA / SELANJUTNYA / SUBMIT -->
        <div class="pt-6 border-t border-amber-200/60 flex flex-col sm:flex-row items-center justify-between gap-3">
          <button 
            @click="prevSoal" 
            :disabled="currentIndex === 0" 
            class="w-full sm:w-auto px-5 py-3 bg-white hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed text-slate-700 font-bold text-xs rounded-xl border border-slate-300 transition-all inline-flex items-center justify-center gap-2 shadow-xs"
          >
            <i class="bi bi-arrow-left"></i>
            <span>Soal Sebelumnya</span>
          </button>

          <div class="w-full sm:w-auto flex items-center gap-3">
            <button 
              v-if="currentIndex < soalList.length - 1" 
              @click="nextSoal" 
              class="w-full sm:w-auto px-6 py-3 btn-gold-shimmer font-bold text-xs rounded-xl shadow-md transition-all inline-flex items-center justify-center gap-2 cursor-pointer"
            >
              <span>Soal Selanjutnya</span>
              <i class="bi bi-arrow-right"></i>
            </button>

            <button 
              v-else 
              @click="confirmSubmitAnswers" 
              class="w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg transition-all active:scale-95 inline-flex items-center justify-center gap-2 cursor-pointer"
            >
              <i class="bi bi-check-circle-fill"></i>
              <span>Selesaikan Ujian</span>
            </button>
          </div>
        </div>

      </div>

      <!-- COLUMN 2: QUESTION NAVIGATOR GRID SIDEBAR -->
      <div class="lg:col-span-4 glass-pearl-card rounded-3xl p-6 space-y-5 h-fit">
        <div class="border-b border-amber-200/60 pb-3 flex items-center justify-between">
          <h3 class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
            <i class="bi bi-grid-3x3-gap-fill text-amber-500"></i>
            <span>Navigasi Nomor Soal</span>
          </h3>
          <span class="text-xs font-mono font-bold text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-full">
            {{ Object.keys(answers).length }} / {{ soalList.length }} Terjawab
          </span>
        </div>

        <!-- GRID BUTTONS -->
        <div class="grid grid-cols-5 gap-2.5">
          <button 
            v-for="(s, index) in soalList" 
            :key="s.id"
            @click="currentIndex = index"
            class="aspect-square rounded-xl font-mono font-bold text-xs transition-all flex flex-col items-center justify-center relative cursor-pointer"
            :class="{
              'ring-2 ring-amber-500 ring-offset-2 scale-105 z-10': currentIndex === index,
              'bg-gradient-to-br from-emerald-400 to-emerald-600 text-white shadow-xs font-black': answers[s.id],
              'bg-white border border-amber-200 text-slate-700 hover:bg-amber-50': !answers[s.id]
            }"
          >
            <span>{{ index + 1 }}</span>
            <span v-if="answers[s.id]" class="text-[9px] font-black leading-none uppercase mt-0.5">{{ answers[s.id] }}</span>
          </button>
        </div>

        <!-- LEGEND STATUS -->
        <div class="pt-3 border-t border-amber-200/60 text-xs space-y-2">
          <div class="flex items-center gap-2">
            <span class="w-3.5 h-3.5 rounded-md bg-emerald-500 inline-block shadow-xs"></span>
            <span class="font-semibold text-slate-700">Sudah Dijawab</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-3.5 h-3.5 rounded-md bg-white border border-amber-300 inline-block shadow-xs"></span>
            <span class="font-semibold text-slate-700">Belum Dijawab</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="w-3.5 h-3.5 rounded-md bg-white border-2 border-amber-500 inline-block shadow-xs"></span>
            <span class="font-semibold text-slate-700">Soal Aktif Ditampilkan</span>
          </div>
        </div>

        <button 
          @click="confirmSubmitAnswers" 
          class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-md transition-all active:scale-95 flex items-center justify-center gap-2 mt-4 cursor-pointer"
        >
          <i class="bi bi-check2-square text-base"></i>
          <span>Kirimkan Semua Jawaban</span>
        </button>
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