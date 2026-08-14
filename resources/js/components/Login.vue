<template>
  <div class="container my-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-success text-white py-3 text-center">
            <h4 class="mb-0 fw-bold">Login RSU Bunda Thamrin</h4>
            <small>Masuk ke Sistem Ujian Diklat</small>
          </div>
          <div class="card-body p-4">
            
            <div v-if="errorMsg" class="alert alert-danger alert-dismissible fade show">
              {{ errorMsg }}
            </div>

            <form @submit.prevent="submitLogin">
              <div class="mb-3">
                <label class="form-label font-weight-bold">Email</label>
                <input v-model="form.email" type="email" class="form-control" placeholder="Masukkan Email Anda" required />
              </div>

              <div class="mb-4">
                <label class="form-label font-weight-bold">Password</label>
                <input v-model="form.password" type="password" class="form-control" placeholder="Masukkan Password" required />
              </div>

              <button type="submit" :disabled="isLoading" class="btn btn-success w-100 btn-lg mb-3">
                <span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>
                {{ isLoading ? 'Memproses Login...' : 'Masuk' }}
              </button>

              <div class="text-center">
                <p class="mb-0 text-muted">Belum punya akun peserta? 
                  <a href="#" @click.prevent="$emit('switchView', 'register')" class="text-success fw-bold text-decoration-none">Daftar Sekarang</a>
                </p>
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