<template>
  <div class="max-w-4xl mx-auto py-6 sm:py-10 px-4 text-slate-800 font-sans relative z-20">
    
    <!-- MAIN LIGHT PEARL GLASS CARD -->
    <div class="glass-pearl-card hover-tilt-card rounded-3xl overflow-hidden relative">
      
      <!-- HEADER BANNER LIGHT PEARL GOLD -->
      <div class="bg-gradient-to-r from-amber-500/10 via-yellow-500/15 to-amber-600/10 p-6 sm:p-8 border-b border-amber-200/50 text-center relative overflow-hidden">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-100/90 border border-amber-300/60 rounded-full text-xs text-amber-900 font-extrabold tracking-wider uppercase mb-3 shadow-xs">
          <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
          <span>Pendaftaran Peserta Diklat Resmi • RSU Bunda Thamrin</span>
        </div>
        
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight uppercase">
          Form Pendaftaran <span class="text-gold-gradient">Peserta Diklat</span>
        </h2>
        <p class="text-xs sm:text-sm text-slate-600 mt-1.5 max-w-lg mx-auto font-medium">
          Lengkapi data diri Anda untuk mendaftar sebagai peserta ujian/diklat medis RSU Bunda Thamrin.
        </p>
      </div>

      <!-- FORM BODY -->
      <div class="p-6 sm:p-9 space-y-8">
        
        <!-- ALERT MESSAGE -->
        <div 
          v-if="alert.message" 
          :class="alert.type === 'success' ? 'bg-emerald-50 border-emerald-300 text-emerald-900' : 'bg-rose-50 border-rose-300 text-rose-900'"
          class="p-4 rounded-2xl border text-xs sm:text-sm flex items-center gap-3 shadow-sm font-semibold"
        >
          <i :class="alert.type === 'success' ? 'bi bi-check-circle-fill text-emerald-600 text-xl' : 'bi bi-exclamation-triangle-fill text-rose-600 text-xl'"></i>
          <span>{{ alert.message }}</span>
        </div>

        <form @submit.prevent="submitRegister" enctype="multipart/form-data" class="space-y-8">
          
          <!-- SECTION 1: DATA DIRI -->
          <div>
            <div class="flex items-center gap-3 border-b border-amber-200/60 pb-3 mb-6">
              <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 text-white font-black text-base flex items-center justify-center shadow-md shadow-amber-500/30">
                1
              </div>
              <div>
                <h3 class="text-base font-extrabold text-slate-900 uppercase tracking-wider">
                  Data Diri & Identitas Peserta
                </h3>
                <p class="text-[11px] text-slate-500">Pastikan informasi diisi dengan akurat sesuai KTP/Dokumen Resmi</p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-xs sm:text-sm">
              <div>
                <label class="block font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input v-model="form.nama" type="text" placeholder="Sesuai KTP" required class="w-full px-4 py-3 bg-white/80 border border-amber-200/80 rounded-xl text-slate-900 font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all shadow-xs" />
              </div>

              <div>
                <label class="block font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">NIK (16 Digit) <span class="text-rose-500">*</span></label>
                <input v-model="form.nik" type="text" placeholder="16 Digit NIK KTP" required class="w-full px-4 py-3 bg-white/80 border border-amber-200/80 rounded-xl text-slate-900 font-mono font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all shadow-xs" />
              </div>

              <div>
                <label class="block font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Email Resmi <span class="text-rose-500">*</span></label>
                <input v-model="form.email" type="email" placeholder="email@contoh.com" required class="w-full px-4 py-3 bg-white/80 border border-amber-200/80 rounded-xl text-slate-900 font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all shadow-xs" />
              </div>

              <div>
                <label class="block font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Password <span class="text-rose-500">*</span></label>
                <input v-model="form.password" type="password" placeholder="Minimal 6 karakter" required class="w-full px-4 py-3 bg-white/80 border border-amber-200/80 rounded-xl text-slate-900 font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all shadow-xs" />
              </div>

              <div>
                <label class="block font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                <select v-model="form.jenis_kelamin" required class="w-full px-4 py-3 bg-white/80 border border-amber-200/80 rounded-xl text-slate-900 font-semibold focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all shadow-xs">
                  <option value="">-- Pilih Jenis Kelamin --</option>
                  <option value="L">Laki-Laki</option>
                  <option value="P">Perempuan</option>
                </select>
              </div>

              <div>
                <label class="block font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">No. HP / WhatsApp <span class="text-rose-500">*</span></label>
                <input v-model="form.no_hp" type="text" placeholder="Contoh: 081234567890" required class="w-full px-4 py-3 bg-white/80 border border-amber-200/80 rounded-xl text-slate-900 font-mono font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all shadow-xs" />
              </div>

              <div class="md:col-span-2">
                <label class="block font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Jabatan / Unit Work <span class="text-rose-500">*</span></label>
                <input v-model="form.jabatan" type="text" placeholder="Contoh: Perawat Medis / Staf Farmasi / Dokter" required class="w-full px-4 py-3 bg-white/80 border border-amber-200/80 rounded-xl text-slate-900 font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all shadow-xs" />
              </div>

              <div class="md:col-span-2">
                <label class="block font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">NPWP <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input v-model="form.npwp" type="text" placeholder="Nomor NPWP jika ada" class="w-full px-4 py-3 bg-white/80 border border-amber-200/80 rounded-xl text-slate-900 font-mono font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all shadow-xs" />
              </div>

              <div class="md:col-span-2">
                <label class="block font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Lengkap <span class="text-rose-500">*</span></label>
                <textarea v-model="form.alamat" rows="3" placeholder="Alamat Lengkap Sesuai KTP" required class="w-full px-4 py-3 bg-white/80 border border-amber-200/80 rounded-xl text-slate-900 font-semibold placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 transition-all shadow-xs"></textarea>
              </div>
            </div>
          </div>

          <!-- SUBMIT BUTTON -->
          <div class="pt-4 border-t border-amber-200/40">
            <button 
              type="submit" 
              :disabled="isLoading" 
              class="w-full btn-gold-shimmer disabled:opacity-70 font-black text-sm uppercase tracking-wider py-4 rounded-xl shadow-lg transition-all transform active:scale-98 flex items-center justify-center gap-2 cursor-pointer"
            >
              <span v-if="isLoading" class="animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent"></span>
              <i v-else class="bi bi-person-check-fill text-lg"></i>
              <span>{{ isLoading ? 'Sedang Memproses Pendaftaran...' : 'Daftar Sebagai Peserta Now' }}</span>
            </button>

            <!-- LOGIN LINK -->
            <div class="text-center mt-4">
              <p class="text-xs text-slate-600 font-medium">
                Sudah memiliki akun peserta? 
                <a 
                  href="#" 
                  @click.prevent="$emit('switchView', 'login')" 
                  class="text-amber-700 hover:text-amber-900 font-extrabold hover:underline transition-colors ml-1"
                >
                  Masuk Ke Akun Anda
                </a>
              </p>
            </div>
          </div>

        </form>

      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      form: {
        nama: '',
        email: '',
        password: '',
        jenis_kelamin: '',
        jabatan: '',
        alamat: '',
        no_hp: '',
        nik: '',
        npwp: '',
      },
      isLoading: false,
      alert: { type: '', message: '' }
    };
  },
  methods: {
    submitRegister() {
      this.isLoading = true;
      this.alert = { type: '', message: '' };

      const formData = new FormData();
      
      // Append text fields only
      Object.keys(this.form).forEach(key => {
        formData.append(key, this.form[key] || '');
      });

      axios.post('/api/register', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then(res => {
        this.isLoading = false;
        this.alert = { type: 'success', message: res.data.message };
        this.resetForm();
      }).catch(err => {
        this.isLoading = false;
        let errorMsg = 'Gagal mendaftar. Silakan periksa kembali data Anda.';
        if (err.response && err.response.data && err.response.data.message) {
          errorMsg = err.response.data.message;
        }
        this.alert = { type: 'danger', message: errorMsg };
      });
    },
    resetForm() {
      this.form = { nama: '', email: '', password: '', jenis_kelamin: '', jabatan: '', alamat: '', no_hp: '', nik: '', npwp: '' };
    }
  }
};
</script>