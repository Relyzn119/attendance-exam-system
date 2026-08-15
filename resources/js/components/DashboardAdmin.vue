<template>
    <div class="container my-4">

        <!-- HEADER ADMIN -->
        <div class="card bg-dark text-white shadow-sm mb-4">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-1"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin Diklat</h3>
                    <p class="mb-0 text-white-50">Kelola Absensi, Token Ujian, & Bank Soal RSU Bunda Thamrin</p>
                </div>
                <span class="badge bg-danger fs-6 px-3 py-2">Role: Admin</span>
            </div>
        </div>

        <!-- TABEL DATA PESERTA UJIAN (MAKS 10 PER HALAMAN) -->
        <div class="card shadow-sm mb-5">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Daftar Peserta Ujian & Absensi</h5>
                <div>
                    <span class="badge bg-light text-success fw-bold me-2">Total: {{ pagination.total }} Peserta</span>
                    <a href="/api/admin/export-absensi" target="_blank" class="btn btn-light btn-sm text-success fw-bold">
                        <i class="bi bi-file-pdf-fill me-1"></i> Export PDF Absensi
                    </a>
                </div>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="25%">Nama Peserta</th>
                                <th width="15%" class="text-center">Jenis Kelamin</th>
                                <th width="20%">No. HP / WA</th>
                                <th width="20%">Email</th>
                                <th width="15%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(p, index) in pesertaList" :key="p.id">
                                <td class="text-center">{{ (pagination.current_page - 1) * 10 + index + 1 }}</td>
                                <td>
                                    <strong>{{ p.nama }}</strong><br>
                                    <small class="text-muted">NIK: {{ p.nik }}</small>
                                </td>
                                <td class="text-center">
                                    <span v-if="p.jenis_kelamin === 'L'" class="badge bg-info text-dark">Laki-Laki</span>
                                    <span v-else class="badge bg-warning text-dark">Perempuan</span>
                                </td>
                                <td>{{ p.no_hp }}</td>
                                <td>{{ p.email }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button @click="openModalDetail(p)" class="btn btn-sm btn-outline-primary" title="Lihat Detail Berkas & Data">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                        <button @click="openModalToken(p)" class="btn btn-sm btn-outline-warning" title="Generate Token">
                                            <i class="bi bi-key-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="pesertaList.length === 0">
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada peserta yang mendaftar.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div v-if="pagination.last_page > 1" class="d-flex justify-content-between align-items-center mt-4">
                    <small class="text-muted">Menampilkan {{ pagination.from }} - {{ pagination.to }} dari {{ pagination.total }} peserta</small>
                    <nav>
                        <ul class="pagination pagination-sm mb-0">
                            <li class="page-item" :class="{ disabled: pagination.current_page === 1 }">
                                <button class="page-link" @click="fetchPeserta(pagination.current_page - 1)">Previous</button>
                            </li>
                            <li v-for="page in pagination.last_page" :key="page" class="page-item" :class="{ active: pagination.current_page === page }">
                                <button class="page-link" @click="fetchPeserta(page)">{{ page }}</button>
                            </li>
                            <li class="page-item" :class="{ disabled: pagination.current_page === pagination.last_page }">
                                <button class="page-link" @click="fetchPeserta(pagination.current_page + 1)">Next</button>
                            </li>
                        </ul>
                    </nav>
                </div>

            </div>
        </div>

        <!-- ================= BAGIAN BANK SOAL & PILIH 25 SOAL UJIAN ================= -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-journal-check me-2"></i>Bank Soal & Pengaturan Soal Ujian</h5>
                <div>
                    <button @click="showModalAddSoal = true" class="btn btn-light btn-sm text-primary fw-bold me-2">
                        <i class="bi bi-plus-circle-fill me-1"></i> Tambah Soal Baru
                    </button>
                    <button @click="saveSelectedSoal" class="btn btn-warning btn-sm fw-bold">
                        <i class="bi bi-check-circle-fill me-1"></i> Simpan Set Soal Ujian
                    </button>
                </div>
            </div>
            <div class="card-body">

                <!-- COUNTER SOAL TERPILIH -->
                <div class="alert alert-info d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <strong>Mekanisme Soal Ujian:</strong> Centang soal di bawah ini untuk dijadikan soal ujian bagi peserta.
                    </div>
                    <span class="badge bg-primary fs-6">Soal Terpilih: {{ selectedSoalIds.length }} / 25</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%" class="text-center">Pilih</th>
                                <th width="5%" class="text-center">No</th>
                                <th width="45%">Pertanyaan / Soal</th>
                                <th width="30%">Opsi Jawaban</th>
                                <th width="10%" class="text-center">Kunci</th>
                                <th width="5%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(soal, index) in bankSoalList" :key="soal.id" :class="{ 'table-warning': selectedSoalIds.includes(soal.id) }">
                                <td class="text-center">
                                    <input type="checkbox" :value="soal.id" v-model="selectedSoalIds" class="form-check-input" />
                                </td>
                                <td class="text-center">{{ index + 1 }}</td>
                                <td>{{ soal.soal }}</td>
                                <td class="small">
                                    <div><strong>A.</strong> {{ soal.opsi_a }}</div>
                                    <div><strong>B.</strong> {{ soal.opsi_b }}</div>
                                    <div><strong>C.</strong> {{ soal.opsi_c }}</div>
                                    <div><strong>D.</strong> {{ soal.opsi_d }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success fs-6">{{ soal.kunci_jawaban }}</span>
                                </td>
                                <td class="text-center">
                                    <button @click="deleteSoal(soal.id)" class="btn btn-sm btn-outline-danger" title="Hapus Soal">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="bankSoalList.length === 0">
                                <td colspan="6" class="text-center py-4 text-muted">Bank Soal masih kosong. Silakan tambah soal baru.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- MODAL 1: DETAIL PESERTA (SESI 5) -->
        <div v-if="selectedPesertaDetail" class="modal fade show d-block bg-dark bg-opacity-50">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold">Detail Registrasi: {{ selectedPesertaDetail.nama }}</h5>
                        <button @click="selectedPesertaDetail = null" class="btn-close btn-close-white"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="fw-bold text-primary border-bottom pb-2">1. Data Diri Lengkap</h6>
                        <div class="row g-2 mb-3 small">
                            <div class="col-md-6"><strong>Nama:</strong> {{ selectedPesertaDetail.nama }}</div>
                            <div class="col-md-6"><strong>NIK:</strong> {{ selectedPesertaDetail.nik }}</div>
                            <div class="col-md-6"><strong>Email:</strong> {{ selectedPesertaDetail.email }}</div>
                            <div class="col-md-6"><strong>No HP:</strong> {{ selectedPesertaDetail.no_hp }}</div>
                            <div class="col-md-12"><strong>Alamat:</strong> {{ selectedPesertaDetail.alamat }}</div>
                        </div>
                        <h6 class="fw-bold text-primary border-bottom pb-2">2. Berkas Terunggah</h6>
                        <ul class="list-group list-group-flush small">
                            <li v-for="b in selectedPesertaDetail.berkas" :key="b.id" class="list-group-item d-flex justify-content-between">
                                <span>{{ b.jenis_berkas }}</span>
                                <a :href="'/storage/' + b.file_path" target="_blank" class="btn btn-xs btn-outline-danger btn-sm">Buka PDF</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL 2: GENERATE TOKEN (SESI 5) -->
        <div v-if="selectedPesertaToken" class="modal fade show d-block bg-dark bg-opacity-50">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title fw-bold">Generate Token Absensi</h5>
                        <button @click="selectedPesertaToken = null" class="btn-close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <h5>{{ selectedPesertaToken.nama }}</h5>
                        <div v-if="activeToken" class="card bg-light border-warning p-3 mb-3">
                            <h2 class="display-5 fw-bold text-dark mb-2">{{ activeToken }}</h2>
                            <button @click="copyToken" class="btn btn-sm btn-outline-dark">{{ isCopied ? 'Berhasil Disalin!' : 'Salin Kode Token' }}</button>
                        </div>
                        <button @click="processGenerateToken" class="btn btn-warning fw-bold btn-lg w-100">Generate Token Baru</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL 3: TAMBAH SOAL BARU -->
        <div v-if="showModalAddSoal" class="modal fade show d-block bg-dark bg-opacity-50">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Soal ke Bank Soal</h5>
                        <button @click="showModalAddSoal = false" class="btn-close btn-close-white"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="submitSoalBaru">
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Pertanyaan / Pertanyaan Ujian</label>
                                <textarea v-model="formSoal.soal" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label font-weight-bold">Opsi A</label>
                                    <input v-model="formSoal.opsi_a" type="text" class="form-control" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label font-weight-bold">Opsi B</label>
                                    <input v-model="formSoal.opsi_b" type="text" class="form-control" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label font-weight-bold">Opsi C</label>
                                    <input v-model="formSoal.opsi_c" type="text" class="form-control" required />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label font-weight-bold">Opsi D</label>
                                    <input v-model="formSoal.opsi_d" type="text" class="form-control" required />
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label font-weight-bold">Kunci Jawaban Benar</label>
                                <select v-model="formSoal.kunci_jawaban" class="form-select" required>
                                    <option value="">-- Pilih Kunci Jawaban --</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 btn-lg">Simpan Soal ke Bank Soal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script lang="js">
import axios from 'axios';

export default {
    data() {
        return {
            pesertaList: [],
            pagination: { current_page: 1, last_page: 1, total: 0, from: 0, to: 0 },
            selectedPesertaDetail: null,
            selectedPesertaToken: null,
            activeToken: '',
            isCopied: false,

            // Bank Soal
            bankSoalList: [],
            selectedSoalIds: [],
            showModalAddSoal: false,
            formSoal: { soal: '', opsi_a: '', opsi_b: '', opsi_c: '', opsi_d: '', kunci_jawaban: '' }
        };
    },
    mounted() {
        this.fetchPeserta(1);
        this.fetchBankSoal();
    },
    methods: {
        fetchPeserta(page = 1) {
            axios.get(`/api/admin/peserta?page=${page}`).then(res => {
                this.pesertaList = res.data.data;
                this.pagination = { current_page: res.data.current_page, last_page: res.data.last_page, total: res.data.total, from: res.data.from, to: res.data.to };
            });
        },
        fetchBankSoal() {
            axios.get('/api/admin/bank-soal').then(res => {
                this.bankSoalList = res.data;
                this.selectedSoalIds = res.data.filter(s => s.is_selected).map(s => s.id);
            });
        },
        submitSoalBaru() {
            axios.post('/api/admin/bank-soal', this.formSoal).then(res => {
                alert(res.data.message);
                this.showModalAddSoal = false;
                this.formSoal = { soal: '', opsi_a: '', opsi_b: '', opsi_c: '', opsi_d: '', kunci_jawaban: '' };
                this.fetchBankSoal();
            });
        },
        saveSelectedSoal() {
            axios.post('/api/admin/bank-soal/pilih', { selected_ids: this.selectedSoalIds }).then(res => {
                alert(res.data.message);
                this.fetchBankSoal();
            });
        },
        deleteSoal(id) {
            if (confirm('Apakah Anda yakin ingin menghapus soal ini?')) {
                // Mengubah res => menjadi () => agar tidak ada error 'res' unused
                axios.delete(`/api/admin/bank-soal/${id}`).then(() => {
                    this.fetchBankSoal();
                });
            }
        },
        openModalDetail(peserta) { this.selectedPesertaDetail = peserta; },
        openModalToken(peserta) { this.selectedPesertaToken = peserta; this.activeToken = peserta.token ? peserta.token.kode_token : ''; this.isCopied = false; },
        processGenerateToken() {
            axios.post(`/api/admin/generate-token/${this.selectedPesertaToken.id}`).then(res => {
                this.activeToken = res.data.token;
                this.fetchPeserta(this.pagination.current_page);
            });
        },
        copyToken() {
            navigator.clipboard.writeText(this.activeToken);
            this.isCopied = true;
            setTimeout(() => { this.isCopied = false; }, 2000);
        }
    }
};
</script>