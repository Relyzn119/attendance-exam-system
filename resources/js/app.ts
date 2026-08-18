import '../css/app.css';
import './bootstrap';
import { createApp } from 'vue';

// Import Komponen Sesi
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
        <div class="relative min-h-screen overflow-x-hidden bg-slate-950 font-sans text-slate-100 flex flex-col justify-between">
            
            <!-- BACKGROUND IMAGE CONTAINER (Gambar RSU Bunda Thamrin dengan Overlay Atmosferik) -->
            <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
                <!-- Gambar Latar RSU Bunda Thamrin -->
                <div 
                    class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-all duration-700 scale-105"
                    style="background-image: url('/images/bg-rsu-bunda-thamrin.png'), url('/images/bg-rsu-bunda-thamrin.jpg');"
                ></div>
                <!-- Overlay Gradasi Gelap Transparan untuk Memastikan Kontras Teks Maksimal -->
                <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-slate-900/60 to-slate-950/90 backdrop-blur-[2px]"></div>
            </div>

            <!-- CONTENT WRAPPER -->
            <div class="relative z-10 flex-grow flex flex-col max-w-[1600px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                
                <!-- GLOBAL GLASSMORPHISM NAVBAR (INSPIRASI GAMBAR 1) -->
                <header class="w-full mb-6">
                    <nav class="w-full backdrop-blur-xl bg-slate-900/40 border border-white/15 shadow-2xl rounded-2xl md:rounded-full px-5 py-3 flex items-center justify-between gap-4 transition-all">
                        
                        <!-- LOGO BRAND -->
                        <a href="#" @click.prevent="currentView = isAdmin() ? 'e-arsip' : 'dashboard-peserta'" class="flex items-center gap-3 group shrink-0">
                            <div class="w-10 h-10 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-inner">
                                <i class="bi bi-hospital text-lg"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-extrabold text-white tracking-wider text-sm md:text-base leading-none group-hover:text-blue-300 transition-colors">
                                    RSU BUNDA THAMRIN
                                </span>
                                <span class="text-[10px] text-slate-300 font-medium tracking-widest uppercase mt-0.5">
                                    HRD & E-Repository
                                </span>
                            </div>
                        </a>

                        <!-- NAVIGASI MENU UTAMA (ADMIN / PESERTA) -->
                        <div class="hidden md:flex items-center gap-1 bg-slate-950/30 p-1.5 rounded-full border border-white/10" v-if="isAdmin()">
                            <button 
                                @click="currentView = 'dashboard-admin'"
                                :class="currentView === 'dashboard-admin' ? 'bg-white/20 text-white font-bold shadow-md border border-white/30' : 'text-slate-300 hover:text-white hover:bg-white/10 font-medium'"
                                class="px-4 py-2 rounded-full text-xs transition-all flex items-center gap-2"
                            >
                                <i class="bi bi-clipboard-check text-blue-400"></i>
                                <span>Absensi & Ujian</span>
                            </button>

                            <button 
                                @click="currentView = 'e-arsip'"
                                :class="currentView === 'e-arsip' ? 'bg-white/20 text-white font-bold shadow-md border border-white/30' : 'text-slate-300 hover:text-white hover:bg-white/10 font-medium'"
                                class="px-4 py-2 rounded-full text-xs transition-all flex items-center gap-2"
                            >
                                <i class="bi bi-folder-symlink text-emerald-400"></i>
                                <span>E-Arsip Pegawai</span>
                            </button>
                        </div>

                        <!-- USER PROFILE & ACTIONS (PILL BUTTON GAMBAR 1) -->
                        <div class="flex items-center gap-3 shrink-0">
                            <!-- IF NOT LOGGED IN -->
                            <template v-if="!currentUser">
                                <button 
                                    @click="currentView = 'login'" 
                                    class="text-xs font-semibold text-slate-200 hover:text-white px-3 py-2 transition-colors"
                                >
                                    Login Staff
                                </button>
                                <button 
                                    @click="currentView = 'register'" 
                                    class="bg-white hover:bg-slate-100 text-slate-900 font-bold text-xs px-5 py-2.5 rounded-full shadow-lg transition-all transform active:scale-95"
                                >
                                    Daftar Peserta
                                </button>
                            </template>

                            <!-- IF LOGGED IN -->
                            <template v-else>
                                <div class="hidden sm:flex flex-col items-end text-right mr-1">
                                    <span class="text-xs font-bold text-white leading-tight">
                                        {{ currentUser.nama || currentUser.nama_lengkap || 'Admin HRD' }}
                                    </span>
                                    <span class="text-[10px] font-mono text-emerald-400 bg-emerald-950/60 border border-emerald-500/30 px-2 py-0.5 rounded-full mt-0.5">
                                        ● {{ (currentUser.role || 'ADMIN').toUpperCase() }}
                                    </span>
                                </div>

                                <button 
                                    @click="handleLogout" 
                                    title="Keluar dari akun"
                                    class="bg-rose-500/20 hover:bg-rose-600 text-rose-200 hover:text-white border border-rose-500/30 font-semibold text-xs px-4 py-2 rounded-full backdrop-blur-md transition-all flex items-center gap-1.5 active:scale-95"
                                >
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span class="hidden sm:inline">Logout</span>
                                </button>
                            </template>
                        </div>

                    </nav>
                </header>

                <!-- MAIN CONTENT AREA -->
                <main class="flex-grow flex flex-col justify-center">
                    <app-login v-if="currentView === 'login'" @loginSuccess="handleLoginSuccess" @switchView="currentView = $event"></app-login>
                    <app-register v-if="currentView === 'register'"></app-register>
                    
                    <dashboard-peserta v-if="currentView === 'dashboard-peserta'" :user="currentUser" @startExamNow="startExamNow"></dashboard-peserta>
                    <dashboard-admin v-if="currentView === 'dashboard-admin'"></dashboard-admin>
                    <e-arsip-index v-if="currentView === 'e-arsip'"></e-arsip-index>
                    
                    <lembar-ujian v-if="currentView === 'lembar-ujian'" :riwayatId="examSession?.riwayatId" :soalData="examSession?.soal" @examSubmitted="onExamSubmitted"></lembar-ujian>
                </main>

                <!-- FOOTER MINIMALIS -->
                <footer class="mt-8 text-center text-xs text-slate-400/80 font-medium py-3 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-2">
                    <div>
                        © 2026 <span class="text-slate-200 font-bold">RSU Bunda Thamrin</span> — Systems & Database Repository
                    </div>
                    <div class="flex items-center gap-4 text-slate-300 text-[11px]">
                        <span>Privasi Karyawan</span>
                        <span>•</span>
                        <span>Bantuan IT HRD</span>
                    </div>
                </footer>

            </div>
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