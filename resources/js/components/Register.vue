<template>
  <div class="max-w-4xl mx-auto py-6 sm:py-10 px-4 text-slate-100 font-sans">
    
    <!-- MAIN GLASS CARD -->
    <div class="bg-slate-900/60 border border-white/15 rounded-3xl backdrop-blur-xl shadow-2xl overflow-hidden relative">
      
      <!-- HEADER BANNER -->
      <div class="bg-slate-950/80 p-6 sm:p-8 border-b border-white/10 text-center relative overflow-hidden">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-blue-500/10 border border-blue-400/30 rounded-full text-xs text-blue-300 font-semibold mb-3">
          <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
          <span>Pendaftaran Peserta Diklat Resmi • RSU Bunda Thamrin</span>
        </div>
        
        <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight uppercase">
          Form Pendaftaran Peserta Diklat
        </h2>
        <p class="text-xs sm:text-sm text-slate-400 mt-1 max-w-lg mx-auto">
          Lengkapi data diri & berkas persyaratan Anda untuk mendaftar ujian/diklat medis.
        </p>
      </div>

      <!-- FORM BODY -->
      <div class="p-6 sm:p-8 space-y-8">
        
        <!-- ALERT MESSAGE -->
        <div 
          v-if="alert.message" 
          :class="alert.type === 'success' ? 'bg-emerald-950/80 border-emerald-500/40 text-emerald-300' : 'bg-rose-950/80 border-rose-500/40 text-rose-300'"
          class="p-4 rounded-2xl border backdrop-blur-md text-xs sm:text-sm flex items-center gap-3 shadow-lg"
        >
          <i :class="alert.type === 'success' ? 'bi bi-check-circle-fill text-emerald-400 text-lg' : 'bi bi-exclamation-triangle-fill text-rose-400 text-lg'"></i>
          <span>{{ alert.message }}</span>
        </div>

        <form @submit.prevent="submitRegister" enctype="multipart/form-data" class="space-y-8">
          
          <!-- SECTION 1: DATA DIRI -->
          <div>
            <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-5">
              <div class="w-8 h-8 rounded-lg bg-blue-600/30 border border-blue-500/40 flex items-center justify-center text-blue-400 font-bold text-sm">
                1
              </div>
              <h3 class="text-base font-extrabold text-white uppercase tracking-wider">
                Data Diri Peserta
              </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs sm:text-sm">
              <div>
                <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1.5">Nama Lengkap <span class="text-rose-400">*</span></label>
                <input v-model="form.nama" type="text" placeholder="Sesuai KTP" required class="w-full px-4 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" />
              </div>

              <div>
                <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1.5">NIK (16 Digit) <span class="text-rose-400">*</span></label>
                <input v-model="form.nik" type="text" placeholder="16 Digit NIK KTP" required class="w-full px-4 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono transition-all" />
              </div>

              <div>
                <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1.5">Email Resmi <span class="text-rose-400">*</span></label>
                <input v-model="form.email" type="email" placeholder="email@contoh.com" required class="w-full px-4 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" />
              </div>

              <div>
                <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1.5">Password <span class="text-rose-400">*</span></label>
                <input v-model="form.password" type="password" placeholder="Minimal 6 karakter" required class="w-full px-4 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" />
              </div>

              <div>
                <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1.5">Jenis Kelamin <span class="text-rose-400">*</span></label>
                <select v-model="form.jenis_kelamin" required class="w-full px-4 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                  <option class="bg-slate-900" value="">-- Pilih Jenis Kelamin --</option>
                  <option class="bg-slate-900" value="L">Laki-Laki</option>
                  <option class="bg-slate-900" value="P">Perempuan</option>
                </select>
              </div>

              <div>
                <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1.5">No. HP / WhatsApp <span class="text-rose-400">*</span></label>
                <input v-model="form.no_hp" type="text" placeholder="Contoh: 081234567890" required class="w-full px-4 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono transition-all" />
              </div>

              <div class="md:col-span-2">
                <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1.5">NPWP <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input v-model="form.npwp" type="text" placeholder="Nomor NPWP jika ada" class="w-full px-4 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono transition-all" />
              </div>

              <div class="md:col-span-2">
                <label class="block font-bold text-slate-300 uppercase tracking-wider mb-1.5">Alamat Lengkap <span class="text-rose-400">*</span></label>
                <textarea v-model="form.alamat" rows="3" placeholder="Alamat Sesuai KTP" required class="w-full px-4 py-2.5 bg-slate-950/70 border border-white/15 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"></textarea>
              </div>
            </div>
          </div>

          <!-- SECTION 2: UPLOAD DOKUMEN -->
          <div>
            <div class="flex items-center gap-2 border-b border-white/10 pb-3 mb-2">
              <div class="w-8 h-8 rounded-lg bg-emerald-600/30 border border-emerald-500/40 flex items-center justify-center text-emerald-400 font-bold text-sm">
                2
              </div>
              <h3 class="text-base font-extrabold text-white uppercase tracking-wider">
                Upload Berkas & Dokumen Pendukung
              </h3>
            </div>
            <p class="text-xs text-slate-400 italic mb-5">Format yang diperbolehkan: PDF, JPG, JPEG, PNG (Maksimal 5MB per file).</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
              <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-white/10">
                <label class="block font-bold text-slate-200 mb-1.5">Kartu Keluarga (KK) <span class="text-rose-400">*</span></label>
                <input type="file" @change="handleFileUpload($event, 'file_kk')" accept=".pdf,.jpg,.jpeg,.png" required class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50 cursor-pointer" />
              </div>

              <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-white/10">
                <label class="block font-bold text-slate-200 mb-1.5">KTP <span class="text-rose-400">*</span></label>
                <input type="file" @change="handleFileUpload($event, 'file_ktp')" accept=".pdf,.jpg,.jpeg,.png" required class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50 cursor-pointer" />
              </div>

              <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-white/10">
                <label class="block font-bold text-slate-200 mb-1.5">Ijazah <span class="text-rose-400">*</span></label>
                <input type="file" @change="handleFileUpload($event, 'file_ijazah')" accept=".pdf,.jpg,.jpeg,.png" required class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50 cursor-pointer" />
              </div>

              <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-white/10">
                <label class="block font-bold text-slate-200 mb-1.5">Transkrip Nilai <span class="text-rose-400">*</span></label>
                <input type="file" @change="handleFileUpload($event, 'file_transkrip')" accept=".pdf,.jpg,.jpeg,.png" required class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50 cursor-pointer" />
              </div>

              <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-white/10">
                <label class="block font-bold text-slate-200 mb-1.5">Curriculum Vitae (CV) <span class="text-rose-400">*</span></label>
                <input type="file" @change="handleFileUpload($event, 'file_cv')" accept=".pdf,.jpg,.jpeg,.png" required class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50 cursor-pointer" />
              </div>

              <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-white/10">
                <label class="block font-bold text-slate-200 mb-1.5">Surat Lamaran <span class="text-rose-400">*</span></label>
                <input type="file" @change="handleFileUpload($event, 'file_surat_lamaran')" accept=".pdf,.jpg,.jpeg,.png" required class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50 cursor-pointer" />
              </div>

              <div class="bg-slate-950/60 p-3.5 rounded-2xl border border-white/10 md:col-span-2">
                <label class="block font-bold text-slate-200 mb-1.5">Berkas Pendukung Lainnya <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input type="file" @change="handleFileUpload($event, 'file_berkas_lain')" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-600/30 file:text-blue-300 hover:file:bg-blue-600/50 cursor-pointer" />
              </div>
            </div>
          </div>

          <!-- SUBMIT BUTTON -->
          <div class="pt-4">
            <button 
              type="submit" 
              :disabled="isLoading" 
              class="w-full bg-white hover:bg-slate-100 disabled:bg-slate-700 text-slate-950 font-black text-sm uppercase tracking-wider py-4 rounded-full shadow-2xl transition-all transform active:scale-95 flex items-center justify-center gap-2"
            >
              <span v-if="isLoading" class="animate-spin rounded-full h-4 w-4 border-2 border-slate-950 border-t-transparent"></span>
              <span>{{ isLoading ? 'Sedang Memproses Pendaftaran...' : 'Daftar Sekarang' }}</span>
            </button>
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
        alamat: '',
        no_hp: '',
        nik: '',
        npwp: '',
      },
      files: {
        file_kk: null,
        file_ktp: null,
        file_ijazah: null,
        file_transkrip: null,
        file_cv: null,
        file_surat_lamaran: null,
        file_berkas_lain: null,
      },
      isLoading: false,
      alert: { type: '', message: '' }
    };
  },
  methods: {
    handleFileUpload(event, key) {
      this.files[key] = event.target.files[0];
    },
    submitRegister() {
      this.isLoading = true;
      this.alert = { type: '', message: '' };

      const formData = new FormData();
      
      // Append text fields
      Object.keys(this.form).forEach(key => {
        formData.append(key, this.form[key] || '');
      });

      // Append file fields
      Object.keys(this.files).forEach(key => {
        if (this.files[key]) {
          formData.append(key, this.files[key]);
        }
      });

      axios.post('/api/register', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then(res => {
        this.isLoading = false;
        this.alert = { type: 'success', message: res.data.message };
        // Reset form jika sukses
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
      this.form = { nama: '', email: '', password: '', jenis_kelamin: '', alamat: '', no_hp: '', nik: '', npwp: '' };
      this.files = { file_kk: null, file_ktp: null, file_ijazah: null, file_transkrip: null, file_cv: null, file_surat_lamaran: null, file_berkas_lain: null };
    }
  }
};
</script>