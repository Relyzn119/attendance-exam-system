<template>
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-9">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-success text-white py-3">
            <h4 class="mb-0 fw-bold text-center">Form Pendaftaran Peserta Diklat RSU Bunda Thamrin</h4>
          </div>
          <div class="card-body p-4">
            
            <div v-if="alert.message" :class="'alert alert-' + alert.type" class="alert-dismissible fade show">
              {{ alert.message }}
            </div>

            <form @submit.prevent="submitRegister" enctype="multipart/form-data">
              
              <!-- SECTION 1: DATA DIRI -->
              <h5 class="text-success border-bottom pb-2 mb-3"><i class="bi bi-person-lines-fill me-2"></i>1. Data Diri Peserta</h5>
              
              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                  <input v-model="form.nama" type="text" class="form-control" placeholder="Sesuai KTP" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label font-weight-bold">NIK (Nomor Induk Kependudukan) <span class="text-danger">*</span></label>
                  <input v-model="form.nik" type="text" class="form-control" placeholder="16 Digit NIK" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label font-weight-bold">Email <span class="text-danger">*</span></label>
                  <input v-model="form.email" type="email" class="form-control" placeholder="email@contoh.com" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label font-weight-bold">Password <span class="text-danger">*</span></label>
                  <input v-model="form.password" type="password" class="form-control" placeholder="Minimal 6 karakter" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label font-weight-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                  <select v-model="form.jenis_kelamin" class="form-select" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L">Laki-Laki</option>
                    <option value="P">Perempuan</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="form-label font-weight-bold">No. HP / WhatsApp <span class="text-danger">*</span></label>
                  <input v-model="form.no_hp" type="text" class="form-control" placeholder="Contoh: 081234567890" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label font-weight-bold">NPWP <span class="text-muted">(Opsional)</span></label>
                  <input v-model="form.npwp" type="text" class="form-control" placeholder="Nomor NPWP jika ada" />
                </div>

                <div class="col-md-12">
                  <label class="form-label font-weight-bold">Alamat Lengkap <span class="text-danger">*</span></label>
                  <textarea v-model="form.alamat" class="form-control" rows="2" placeholder="Alamat Sesuai KTP" required></textarea>
                </div>
              </div>

              <!-- SECTION 2: UPLOAD DOKUMEN -->
              <h5 class="text-success border-bottom pb-2 mb-3"><i class="bi bi-file-earmark-arrow-up-fill me-2"></i>2. Upload Berkas & Dokumen Pendukung</h5>
              <p class="text-muted small mb-3">Format yang diperbolehkan: PDF, JPG, JPEG, PNG (Maksimal 5MB per file).</p>

              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label">Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                  <input type="file" @change="handleFileUpload($event, 'file_kk')" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label">KTP <span class="text-danger">*</span></label>
                  <input type="file" @change="handleFileUpload($event, 'file_ktp')" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label">Ijazah <span class="text-danger">*</span></label>
                  <input type="file" @change="handleFileUpload($event, 'file_ijazah')" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label">Transkrip Nilai <span class="text-danger">*</span></label>
                  <input type="file" @change="handleFileUpload($event, 'file_transkrip')" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label">Curriculum Vitae (CV) <span class="text-danger">*</span></label>
                  <input type="file" @change="handleFileUpload($event, 'file_cv')" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required />
                </div>

                <div class="col-md-6">
                  <label class="form-label">Surat Lamaran <span class="text-danger">*</span></label>
                  <input type="file" @change="handleFileUpload($event, 'file_surat_lamaran')" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required />
                </div>

                <div class="col-md-12">
                  <label class="form-label">Berkas Pendukung Lainnya <span class="text-muted">(Opsional)</span></label>
                  <input type="file" @change="handleFileUpload($event, 'file_berkas_lain')" class="form-control" accept=".pdf,.jpg,.jpeg,.png" />
                </div>
              </div>

              <!-- BUTTON SUBMIT -->
              <div class="d-grid gap-2">
                <button type="submit" :disabled="isLoading" class="btn btn-success btn-lg">
                  <span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>
                  {{ isLoading ? 'Sedang Memproses Pendaftaran...' : 'Daftar Sekarang' }}
                </button>
              </div>

            </form>

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

      let formData = new FormData();
      
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