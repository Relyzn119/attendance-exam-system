import '../css/app.css';
import './bootstrap';
import { createApp } from 'vue';

// Import Semua Komponen Sesi 1 - 10
import Register from './components/Register.vue';
import Login from './components/Login.vue';
import DashboardPeserta from './components/DashboardPeserta.vue';
import DashboardAdmin from './components/DashboardAdmin.vue';
import LembarUjian from './components/LembarUjian.vue';
import EArsipIndex from './components/EArsipIndex.vue';

const app = createApp({
    data() {
        return {
            currentView: 'login',
            currentUser: null,
            examSession: null
        }
    },
    mounted() {
        const savedUser = localStorage.getItem('user');
        if (savedUser) {
            this.currentUser = JSON.parse(savedUser);
            // Penanganan role huruf besar/kecil (ADMIN / admin)
            const role = (this.currentUser.role || '').toLowerCase();
            this.currentView = role === 'admin' ? 'e-arsip' : 'dashboard-peserta';
        }
    },
    methods: {
        handleLoginSuccess(user: any) {
            this.currentUser = user;
            const role = (user.role || '').toLowerCase();
            this.currentView = role === 'admin' ? 'e-arsip' : 'dashboard-peserta';
        },
        handleLogout() {
            localStorage.removeItem('user');
            this.currentUser = null;
            this.currentView = 'login';
        },
        startExamNow(data: any) {
            this.examSession = data;
            this.currentView = 'lembar-ujian';
        },
        onExamSubmitted() {
            this.currentView = 'dashboard-peserta';
        },
        isAdmin() {
            return this.currentUser && (this.currentUser.role || '').toLowerCase() === 'admin';
        }
    },
    template: `
        <div>
            <!-- NAVBAR GLOBAL (DISEMPURNAKAN AGAR SELALU MUNCUL) -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm mb-4">
                <div class="container-fluid px-4 d-flex align-items-center justify-content-between">
                    
                    <!-- LOGO & BRAND -->
                    <a class="navbar-brand fw-bold d-flex align-items-center gap-2 me-4" href="#">
                        <i class="bi bi-hospital"></i> RSU BUNDA THAMRIN
                    </a>

                    <!-- MENU ADMIN & AUTH USER -->
                    <div class="d-flex align-items-center justify-content-between flex-grow-1">
                        
                        <!-- MENU NAVIGASI KHUSUS ADMIN (Absensi & E-Arsip) -->
                        <ul class="navbar-nav me-auto d-flex flex-row gap-3 mb-0" v-if="isAdmin()">
                            <li class="nav-item">
                                <a 
                                    class="nav-link text-white font-semibold d-flex align-items-center gap-1.5" 
                                    :class="{ 'active fw-bold text-warning border-bottom border-2 border-warning': currentView === 'dashboard-admin' }" 
                                    href="#" 
                                    @click.prevent="currentView = 'dashboard-admin'"
                                >
                                    <i class="bi bi-clipboard-check"></i> Absensi & Ujian
                                </a>
                            </li>
                            <li class="nav-item">
                                <a 
                                    class="nav-link text-white font-semibold d-flex align-items-center gap-1.5" 
                                    :class="{ 'active fw-bold text-warning border-bottom border-2 border-warning': currentView === 'e-arsip' }" 
                                    href="#" 
                                    @click.prevent="currentView = 'e-arsip'"
                                >
                                    <i class="bi bi-folder-symlink"></i> E-Arsip Pegawai
                                </a>
                            </li>
                        </ul>

                        <!-- USER INFO & LOGOUT -->
                        <ul class="navbar-nav ms-auto d-flex flex-row align-items-center mb-0">
                            <template v-if="!currentUser">
                                <li class="nav-item">
                                    <a class="nav-link text-white fw-semibold" href="#" @click.prevent="currentView = 'login'">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-light text-success fw-bold ms-2" href="#" @click.prevent="currentView = 'register'">Daftar Peserta</a>
                                </li>
                            </template>

                            <template v-else>
                                <li class="nav-item text-white me-3 text-sm">
                                    Halo, <strong>{{ currentUser.nama || currentUser.nama_lengkap || 'Admin' }}</strong> ({{ (currentUser.role || 'ADMIN').toUpperCase() }})
                                </li>
                                <li class="nav-item">
                                    <button @click="handleLogout" class="btn btn-outline-light btn-sm fw-bold d-flex align-items-center gap-1">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </li>
                            </template>
                        </ul>

                    </div>
                </div>
            </nav>

            <!-- MAIN CONTENT SWITCHER -->
            <main>
                <app-login v-if="currentView === 'login'" @loginSuccess="handleLoginSuccess" @switchView="currentView = $event"></app-login>
                <app-register v-if="currentView === 'register'"></app-register>
                
                <dashboard-peserta v-if="currentView === 'dashboard-peserta'" :user="currentUser" @startExamNow="startExamNow"></dashboard-peserta>
                <dashboard-admin v-if="currentView === 'dashboard-admin'"></dashboard-admin>
                <e-arsip-index v-if="currentView === 'e-arsip'"></e-arsip-index>
                
                <lembar-ujian v-if="currentView === 'lembar-ujian'" :riwayatId="examSession?.riwayatId" :soalData="examSession?.soal" @examSubmitted="onExamSubmitted"></lembar-ujian>
            </main>
        </div>
    `
});

app.component('app-login', Login);
app.component('app-register', Register);
app.component('dashboard-peserta', DashboardPeserta);
app.component('dashboard-admin', DashboardAdmin);
app.component('lembar-ujian', LembarUjian);
app.component('e-arsip-index', EArsipIndex);

app.mount('#app');