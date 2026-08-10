<template>
  <div class="container my-4">
    <div class="card shadow-sm">
      <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Database Pegawai & Document Center RSU Bunda Thamrin</h4>
      </div>
      <div class="card-body">
        
        <!-- Filter Bar berdasarkan Tanggal Upload -->
        <div class="row mb-4 align-items-end">
          <div class="col-md-4">
            <label class="form-label font-weight-bold">Cari / Filter Tanggal Upload Data:</label>
            <input type="date" v-model="filterTanggal" class="form-control" @change="fetchPegawai" />
          </div>
          <div class="col-md-2">
            <button @click="resetFilter" class="btn btn-secondary w-100">Reset Filter</button>
          </div>
        </div>

        <!-- Tabel Daftar Seluruh Pegawai -->
        <div class="table-responsive">
          <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>NIK / NIP</th>
                <th>Nama Lengkap</th>
                <th>Status Pegawai</th>
                <th>Jabatan / Unit</th>
                <th>Tgl Upload Data</th>
                <th>Aksi Dokumen</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(pegawai, idx) in pegawaiList" :key="pegawai.id">
                <td>{{ idx + 1 }}</td>
                <td><strong>{{ pegawai.nik_nip }}</strong></td>
                <td>{{ pegawai.nama_lengkap }}</td>
                <td>
                  <span v-if="pegawai.jenis_pegawai === 'dokter'" class="badge bg-info text-dark">Dokter</span>
                  <span v-else-if="pegawai.jenis_pegawai === 'tetap'" class="badge bg-primary">Pegawai Tetap</span>
                  <span v-else class="badge bg-warning text-dark">Pegawai Kontrak</span>
                </td>
                <td>{{ pegawai.jabatan }} - {{ pegawai.unit_kerja }}</td>
                <td>{{ pegawai.tanggal_upload }}</td>
                <td>
                  <button @click="openDetailModal(pegawai)" class="btn btn-sm btn-primary">
                    Lihat Detail & Berkas PDF
                  </button>
                </td>
              </tr>
              <tr v-if="pegawaiList.length === 0">
                <td colspan="7" class="text-center py-3 text-muted">Data pegawai tidak ditemukan pada tanggal tersebut.</td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>

    <!-- MODAL DETAIL PEGAWAI & FILE PDF (STR, IJAZAH, TRANSKRIP) -->
    <div v-if="selectedPegawai" class="modal fade show d-block bg-dark bg-opacity-50">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Detail Berkas Digital: {{ selectedPegawai.nama_lengkap }}</h5>
            <button @click="selectedPegawai = null" class="btn-close btn-close-white"></button>
          </div>
          <div class="modal-body">
            
            <!-- Ringkasan Profil -->
            <div class="row mb-3 p-2 bg-light rounded border">
              <div class="col-md-6">
                <p class="mb-1"><strong>NIK/NIP:</strong> {{ selectedPegawai.nik_nip }}</p>
                <p class="mb-1"><strong>Jabatan:</strong> {{ selectedPegawai.jabatan }}</p>
              </div>
              <div class="col-md-6">
                <p class="mb-1"><strong>Unit Kerja:</strong> {{ selectedPegawai.unit_kerja }}</p>
                <p class="mb-1"><strong>Status:</strong> {{ selectedPegawai.jenis_pegawai.toUpperCase() }}</p>
              </div>
            </div>

            <!-- List Berkas PDF (STR, Transkrip, Ijazah) -->
            <h6>Dokumen PDF Terunggah:</h6>
            <ul class="list-group mb-4">
              <li v-for="berkas in selectedPegawai.berkas" :key="berkas.id" class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ berkas.jenis_berkas }}</strong> - {{ berkas.nama_file }}<br>
                  <small class="text-muted">Diunggah pada: {{ berkas.tanggal_upload }}</small>
                </div>
                <a :href="'/storage/' + berkas.file_path" target="_blank" class="btn btn-sm btn-outline-danger">
                  Buka / Preview PDF
                </a>
              </li>
              <li v-if="!selectedPegawai.berkas || selectedPegawai.berkas.length === 0" class="list-group-item text-muted text-center">
                Belum ada berkas PDF yang diunggah oleh HRD.
              </li>
            </ul>

            <!-- Form Upload Berkas PDF Baru (Sisi HRD) -->
            <div class="border-top pt-3">
              <h6>[HRD] Unggah Dokumen PDF Baru:</h6>
              <form @submit.prevent="uploadFile">
                <div class="row g-2">
                  <div class="col-md-5">
                    <select v-model="uploadForm.jenis_berkas" class="form-select" required>
                      <option value="">-- Pilih Jenis Berkas --</option>
                      <option value="Ijazah Profesi">Ijazah Profesi</option>
                      <option value="Transkrip Nilai">Transkrip Nilai</option>
                      <option value="STR (Surat Tanda Registrasi)">STR (Surat Tanda Registrasi)</option>
                      <option value="Sertifikat Pelatihan">Sertifikat Pelatihan</option>
                    </select>
                  </div>
                  <div class="col-md-5">
                    <input type="file" ref="fileInput" @change="handleFileChange" class="form-control" accept="application/pdf" required />
                  </div>
                  <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100">Upload</button>
                  </div>
                </div>
              </form>
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
  data() {
    return {
      pegawaiList: [],
      filterTanggal: '',
      selectedPegawai: null,
      uploadForm: {
        jenis_berkas: '',
        file: null
      }
    };
  },
  mounted() {
    this.fetchPegawai();
  },
  methods: {
    fetchPegawai() {
      axios.get('/api/pegawai', { params: { tanggal_upload: this.filterTanggal } }).then(res => {
        this.pegawaiList = res.data;
      });
    },
    resetFilter() {
      this.filterTanggal = '';
      this.fetchPegawai();
    },
    openDetailModal(pegawai) {
      axios.get(`/api/pegawai/${pegawai.id}`).then(res => {
        this.selectedPegawai = res.data;
      });
    },
    handleFileChange(e) {
      this.uploadForm.file = e.target.files[0];
    },
    uploadFile() {
      let formData = new FormData();
      formData.append('jenis_berkas', this.uploadForm.jenis_berkas);
      formData.append('file', this.uploadForm.file);

      axios.post(`/api/pegawai/${this.selectedPegawai.id}/upload-berkas`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      }).then(res => {
        alert(res.data.message);
        this.openDetailModal(this.selectedPegawai); // Reload modal
        this.uploadForm.jenis_berkas = '';
        this.$refs.fileInput.value = '';
      });
    }
  }
};
</script>