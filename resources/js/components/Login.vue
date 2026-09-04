<template>
  <div class="max-w-md mx-auto py-6 sm:py-10 px-4 text-slate-800 font-sans relative z-20">
    
    <!-- MAIN LIGHT PEARL GLASS CARD -->
    <div class="glass-pearl-card hover-tilt-card rounded-3xl overflow-hidden relative p-7 sm:p-9">
      
      <!-- DECORATIVE CORNER GLOWS -->
      <div class="absolute -top-12 -right-12 w-28 h-28 bg-amber-400/20 rounded-full blur-2xl pointer-events-none"></div>
      <div class="absolute -bottom-12 -left-12 w-28 h-28 bg-yellow-300/30 rounded-full blur-2xl pointer-events-none"></div>

      <!-- HEADER BRANDING & LOGO -->
      <div class="text-center space-y-3.5 mb-8 relative z-10">
        <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-amber-400 via-amber-500 to-yellow-600 text-white flex items-center justify-center text-3xl shadow-xl shadow-amber-500/30 border border-white/60 transform hover:rotate-6 transition-transform duration-300">
          <i class="bi bi-hospital"></i>
        </div>
        
        <div>
          <span class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-amber-100/90 border border-amber-300/60 rounded-full text-[11px] text-amber-900 font-extrabold tracking-wider uppercase mb-2 shadow-xs">
            <i class="bi bi-shield-lock-fill text-amber-600"></i> Portal Diklat & Staff Login
          </span>
          <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight uppercase">
            Login <span class="text-gold-gradient">RSU Bunda Thamrin</span>
          </h2>
          <p class="text-xs text-slate-600 mt-1.5 font-medium">
            Masuk untuk Mengakses Sistem Ujian & E-Arsip Digital
          </p>
        </div>
      </div>

      <!-- ERROR ALERT -->
      <div 
        v-if="errorMsg" 
        class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-2xl text-xs sm:text-sm flex items-center gap-3 mb-6 shadow-sm animate-shake"
      >
        <i class="bi bi-exclamation-triangle-fill text-rose-500 text-lg shrink-0"></i>
        <span class="font-semibold">{{ errorMsg }}</span>
      </div>

      <!-- LOGIN FORM -->
      <form @submit.prevent="submitLogin" class="space-y-5 relative z-10">
        <div>
          <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
            <i class="bi bi-envelope-fill text-amber-500"></i> Alamat Email
          </label>
          <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-amber-600 transition-colors">
              <i class="bi bi-envelope"></i>
            </div>
            <input 
              v-model="form.email" 
              type="email" 
              placeholder="email@bundathamrin.com" 
              required 
              class="w-full pl-10 pr-4 py-3.5 bg-white/80 border border-amber-200/80 rounded-xl text-sm text-slate-900 font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all shadow-xs" 
            />
          </div>
        </div>

        <div>
          <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
            <i class="bi bi-key-fill text-amber-500"></i> Kata Sandi
          </label>
          <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-amber-600 transition-colors">
              <i class="bi bi-lock"></i>
            </div>
            <input 
              v-model="form.password" 
              type="password" 
              placeholder="Masukkan kata sandi Anda" 
              required 
              class="w-full pl-10 pr-4 py-3.5 bg-white/80 border border-amber-200/80 rounded-xl text-sm text-slate-900 font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all shadow-xs" 
            />
          </div>
        </div>

        <!-- SUBMIT BUTTON -->
        <div class="pt-3">
          <button 
            type="submit" 
            :disabled="isLoading" 
            class="w-full btn-gold-shimmer disabled:opacity-70 font-black text-xs sm:text-sm uppercase tracking-wider py-4 rounded-xl shadow-lg transition-all transform active:scale-98 flex items-center justify-center gap-2 cursor-pointer"
          >
            <span v-if="isLoading" class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></span>
            <i v-else class="bi bi-box-arrow-in-right text-base"></i>
            <span>{{ isLoading ? 'Memproses Login...' : 'Masuk Ke Sistem' }}</span>
          </button>
        </div>

        <!-- FOOTER LINK -->
        <div class="text-center pt-3 border-t border-amber-200/40 mt-4">
          <p class="text-xs text-slate-600 font-medium">
            Belum punya akun peserta? 
            <a 
              href="#" 
              @click.prevent="$emit('switchView', 'register')" 
              class="text-amber-700 hover:text-amber-900 font-extrabold hover:underline transition-colors ml-1 inline-flex items-center gap-1"
            >
              Daftar Sekarang <i class="bi bi-arrow-right-short"></i>
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