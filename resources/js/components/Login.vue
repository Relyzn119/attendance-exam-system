<template>
  <div class="max-w-md mx-auto py-8 sm:py-12 px-4 text-slate-100 font-sans">
    
    <!-- MAIN GLASS CARD -->
    <div class="bg-slate-900/60 border border-white/15 rounded-3xl backdrop-blur-xl shadow-2xl overflow-hidden relative p-6 sm:p-8">
      
      <!-- HEADER BRANDING & LOGO -->
      <div class="text-center space-y-3 mb-8">
        <div class="w-14 h-14 mx-auto rounded-2xl bg-gradient-to-br from-blue-600/30 to-indigo-600/30 border border-blue-400/30 flex items-center justify-center text-blue-400 text-2xl shadow-inner">
          <i class="bi bi-hospital"></i>
        </div>
        
        <div>
          <span class="inline-block px-3 py-1 bg-blue-500/10 border border-blue-400/30 rounded-full text-[11px] text-blue-300 font-semibold mb-2">
            Portal Portal Diklat & Staff Login
          </span>
          <h2 class="text-2xl font-black text-white tracking-tight uppercase">
            Login RSU Bunda Thamrin
          </h2>
          <p class="text-xs text-slate-400 mt-1">
            Masuk untuk Mengakses Sistem Ujian & E-Arsip Digital
          </p>
        </div>
      </div>

      <!-- ERROR ALERT -->
      <div 
        v-if="errorMsg" 
        class="bg-rose-950/80 border border-rose-500/40 text-rose-300 p-4 rounded-2xl text-xs sm:text-sm flex items-center gap-3 mb-6 shadow-lg backdrop-blur-md"
      >
        <i class="bi bi-exclamation-triangle-fill text-rose-400 text-lg shrink-0"></i>
        <span>{{ errorMsg }}</span>
      </div>

      <!-- LOGIN FORM -->
      <form @submit.prevent="submitLogin" class="space-y-5">
        <div>
          <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Email</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
              <i class="bi bi-envelope"></i>
            </div>
            <input 
              v-model="form.email" 
              type="email" 
              placeholder="email@bundathamrin.com" 
              required 
              class="w-full pl-10 pr-4 py-3 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
            />
          </div>
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Password</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
              <i class="bi bi-lock"></i>
            </div>
            <input 
              v-model="form.password" 
              type="password" 
              placeholder="Masukkan password Anda" 
              required 
              class="w-full pl-10 pr-4 py-3 bg-slate-950/70 border border-white/15 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
            />
          </div>
        </div>

        <!-- SUBMIT BUTTON -->
        <div class="pt-2">
          <button 
            type="submit" 
            :disabled="isLoading" 
            class="w-full bg-white hover:bg-slate-100 disabled:bg-slate-700 text-slate-950 font-black text-xs sm:text-sm uppercase tracking-wider py-3.5 rounded-full shadow-2xl transition-all transform active:scale-95 flex items-center justify-center gap-2"
          >
            <span v-if="isLoading" class="animate-spin rounded-full h-4 w-4 border-2 border-slate-950 border-t-transparent"></span>
            <span>{{ isLoading ? 'Memproses Login...' : 'Masuk Ke Sistem' }}</span>
          </button>
        </div>

        <!-- FOOTER LINK -->
        <div class="text-center pt-2">
          <p class="text-xs text-slate-400">
            Belum punya akun peserta? 
            <a 
              href="#" 
              @click.prevent="$emit('switchView', 'register')" 
              class="text-blue-400 hover:text-blue-300 font-bold hover:underline transition-colors ml-1"
            >
              Daftar Sekarang
            </a>
          </p>
        </div>
      </form>

    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      form: { email: '', password: '' },
      isLoading: false,
      errorMsg: ''
    };
  },
  methods: {
    submitLogin() {
      this.isLoading = true;
      this.errorMsg = '';

      axios.post('/api/login', this.form).then(res => {
        this.isLoading = false;
        const user = res.data.user;
        // Simpan session user ke localStorage
        localStorage.setItem('user', JSON.stringify(user));
        
        // Emit event login sukses ke parent
        this.$emit('loginSuccess', user);
      }).catch(err => {
        this.isLoading = false;
        if (err.response && err.response.data && err.response.data.message) {
          this.errorMsg = err.response.data.message;
        } else {
          this.errorMsg = 'Gagal melakukan login. Periksa koneksi internet Anda.';
        }
      });
    }
  }
};
</script>