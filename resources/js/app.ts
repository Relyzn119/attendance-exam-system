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
            examSession: null,
            mobileMenuOpen: false
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
            this.mobileMenuOpen = false;
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
        <div class="relative min-h-screen overflow-x-hidden bg-transparent font-sans text-slate-800 flex flex-col justify-between selection:bg-amber-400/30 selection:text-amber-900">
            
            <!-- FLOATING GOLDEN ORBS & BACKGROUND ATMOSPHERE (OPTION B LIGHT PEARL WHITE-GOLD) -->
            <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
                <!-- Golden Ambient Orbs -->
                <div class="absolute -top-32 -left-20 w-96 h-96 bg-gradient-to-br from-amber-300/30 via-yellow-200/20 to-transparent rounded-full blur-3xl animate-float-orb"></div>
                <div class="absolute top-1/3 -right-24 w-[28rem] h-[28rem] bg-gradient-to-bl from-amber-400/20 via-yellow-300/15 to-transparent rounded-full blur-3xl animate-float-orb-delayed"></div>
                <div class="absolute -bottom-32 left-1/4 w-[32rem] h-[32rem] bg-gradient-to-tr from-amber-200/25 via-amber-100/30 to-transparent rounded-full blur-3xl animate-float-orb"></div>
                
                <!-- Geometric Subtle Grid Overlay -->
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#f59e0b08_1px,transparent_1px),linear-gradient(to_bottom,#f59e0b08_1px,transparent_1px)] bg-[size:4rem_4rem]"></div>
            </div>

            <!-- CONTENT WRAPPER -->
            <div class="relative z-10 flex-grow flex flex-col max-w-[1600px] w-full mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                
                <!-- LIGHT PEARL GLASSMORPHISM NAVBAR -->
                <header class="w-full mb-6 relative z-50">
                    <nav class="w-full glass-pearl border border-amber-300/30 shadow-xl shadow-amber-900/5 rounded-2xl md:rounded-full px-5 py-3 flex items-center justify-between gap-4 transition-all">
                        
                        <!-- LOGO BRAND -->
                        <a href="#" @click.prevent="currentView = isAdmin() ? 'e-arsip' : 'dashboard-peserta'" class="flex items-center gap-3 group shrink-0">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-amber-400 via-amber-500 to-yellow-600 text-white flex items-center justify-center text-xl shadow-md shadow-amber-500/30 group-hover:scale-105 group-hover:shadow-amber-500/50 transition-all duration-300">
                                <i class="bi bi-hospital"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-black text-slate-900 tracking-tight text-sm md:text-base leading-none group-hover:text-amber-600 transition-colors">
                                    RSU BUNDA THAMRIN
                                </span>
                                <span class="text-[10px] text-amber-700 font-bold tracking-widest uppercase mt-0.5 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> HRD & E-Repository
                                </span>
                            </div>
                        </a>

                        <!-- NAVIGASI MENU UTAMA (DESKTOP ADMIN) -->
                        <div class="hidden md:flex items-center gap-1.5 bg-amber-50/60 p-1.5 rounded-full border border-amber-200/50 backdrop-blur-md" v-if="isAdmin()">
                            <button 
                                @click="currentView = 'dashboard-admin'"
                                :class="currentView === 'dashboard-admin' ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold shadow-md shadow-amber-500/20' : 'text-slate-700 hover:text-amber-800 hover:bg-amber-100/60 font-medium'"
                                class="px-5 py-2 rounded-full text-xs transition-all flex items-center gap-2"
                            >
                                <i class="bi bi-clipboard-check text-amber-200"></i>
                                <span>Absensi & Ujian</span>
                            </button>

                            <button 
                                @click="currentView = 'e-arsip'"
                                :class="currentView === 'e-arsip' ? 'bg-gradient-to-r from-amber-500 to-amber-600 text-white font-bold shadow-md shadow-amber-500/20' : 'text-slate-700 hover:text-amber-800 hover:bg-amber-100/60 font-medium'"
                                class="px-5 py-2 rounded-full text-xs transition-all flex items-center gap-2"
                            >
                                <i class="bi bi-folder-symlink text-amber-200"></i>
                                <span>E-Arsip Pegawai</span>
                            </button>
                        </div>

                        <!-- USER PROFILE & ACTIONS (DESKTOP) -->
                        <div class="hidden sm:flex items-center gap-3 shrink-0">
                            <!-- IF NOT LOGGED IN -->
                            <template v-if="!currentUser">
                                <button 
                                    @click="currentView = 'login'" 
                                    class="text-xs font-bold text-slate-700 hover:text-amber-700 px-4 py-2 transition-colors"
                                >
                                    Login
                                </button>
                                <button 
                                    @click="currentView = 'register'" 
                                    class="btn-gold-shimmer font-bold text-xs px-5 py-2.5 rounded-full shadow-md transition-all transform active:scale-95 flex items-center gap-1.5"
                                >
                                    <i class="bi bi-person-plus-fill"></i>
                                    <span>Daftar Peserta</span>
                                </button>
                            </template>

                            <!-- IF LOGGED IN -->
                            <template v-else>
                                <div class="flex flex-col items-end text-right mr-1">
                                    <span class="text-xs font-extrabold text-slate-900 leading-tight">
                                        {{ currentUser.nama || currentUser.nama_lengkap || 'Admin HRD' }}
                                    </span>
                                    <span class="text-[10px] font-bold text-amber-800 bg-amber-100/80 border border-amber-300/60 px-2.5 py-0.5 rounded-full mt-0.5 shadow-sm">
                                        ● {{ (currentUser.role || 'ADMIN').toUpperCase() }}
                                    </span>
                                </div>

                                <button 
                                    @click="handleLogout" 
                                    title="Keluar dari akun"
                                    class="bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/80 font-bold text-xs px-4 py-2 rounded-full transition-all flex items-center gap-1.5 active:scale-95 shadow-sm"
                                >
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </template>
                        </div>

                        <!-- MOBILE HAMBURGER BUTTON -->
                        <button 
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="sm:hidden w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 flex items-center justify-center text-lg shadow-sm"
                        >
                            <i :class="mobileMenuOpen ? 'bi bi-x-lg' : 'bi bi-list'"></i>
                        </button>
                    </nav>

                    <!-- MOBILE MENU DRAWER -->
                    <div 
                        v-if="mobileMenuOpen" 
                        class="sm:hidden mt-2 glass-pearl border border-amber-300/40 rounded-2xl p-4 space-y-3 shadow-xl transition-all"
                    >
                        <template v-if="isAdmin()">
                            <button 
                                @click="currentView = 'dashboard-admin'; mobileMenuOpen = false;"
                                class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2"
                                :class="currentView === 'dashboard-admin' ? 'bg-amber-500 text-white' : 'text-slate-800 hover:bg-amber-50'"
                            >
                                <i class="bi bi-clipboard-check"></i> Absensi & Ujian Admin
                            </button>
                            <button 
                                @click="currentView = 'e-arsip'; mobileMenuOpen = false;"
                                class="w-full text-left px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2"
                                :class="currentView === 'e-arsip' ? 'bg-amber-500 text-white' : 'text-slate-800 hover:bg-amber-50'"
                            >
                                <i class="bi bi-folder-symlink"></i> E-Arsip Pegawai
                            </button>
                        </template>

                        <div class="pt-2 border-t border-amber-200/50 flex flex-col gap-2">
                            <template v-if="!currentUser">
                                <button 
                                    @click="currentView = 'login'; mobileMenuOpen = false;" 
                                    class="w-full py-2.5 text-center font-bold text-xs text-slate-800 bg-amber-50 rounded-xl border border-amber-200"
                                >
                                    Login
                                </button>
                                <button 
                                    @click="currentView = 'register'; mobileMenuOpen = false;" 
                                    class="w-full py-2.5 text-center btn-gold-shimmer font-bold text-xs rounded-xl"
                                >
                                    Daftar Peserta
                                </button>
                            </template>
                            <template v-else>
                                <div class="px-2 py-1 flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-900">{{ currentUser.nama || currentUser.nama_lengkap }}</span>
                                    <span class="text-[10px] font-bold text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">● {{ (currentUser.role || 'USER').toUpperCase() }}</span>
                                </div>
                                <button 
                                    @click="handleLogout" 
                                    class="w-full py-2.5 text-center bg-rose-50 text-rose-700 border border-rose-200 font-bold text-xs rounded-xl flex items-center justify-center gap-2"
                                >
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </template>
                        </div>
                    </div>
                </header>

                <!-- MAIN CONTENT AREA -->
                <main class="flex-grow flex flex-col justify-center py-2">
                    <app-login v-if="currentView === 'login'" @loginSuccess="handleLoginSuccess" @switchView="currentView = $event"></app-login>
                    <app-register v-if="currentView === 'register'" @switchView="currentView = $event"></app-register>
                    
                    <dashboard-peserta v-if="currentView === 'dashboard-peserta'" :user="currentUser" @startExamNow="startExamNow"></dashboard-peserta>
                    <dashboard-admin v-if="currentView === 'dashboard-admin'"></dashboard-admin>
                    <e-arsip-index v-if="currentView === 'e-arsip'"></e-arsip-index>
                    
                    <lembar-ujian 
                        v-if="currentView === 'lembar-ujian'" 
                        :riwayatId="examSession?.riwayatId" 
                        :soalData="examSession?.soal" 
                        :durasiMenit="examSession?.durasi_menit || 60" 
                        @examSubmitted="onExamSubmitted"
                    ></lembar-ujian>
                </main>

                <!-- FOOTER MINIMALIS LIGHT PEARL GOLD -->
                <footer class="mt-8 text-center text-xs text-slate-500 font-medium py-4 border-t border-amber-200/40 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-amber-500 text-white flex items-center justify-center text-xs font-black shadow-sm">
                            <i class="bi bi-hospital"></i>
                        </div>
                        <span>© 2026 <strong class="text-slate-800">RSU Bunda Thamrin</strong> — Systems & Database Repository</span>
                    </div>
                    <div class="flex items-center gap-4 text-slate-600 text-[11px] font-semibold">
                        <a href="#" class="hover:text-amber-700 transition-colors">Privasi Karyawan</a>
                        <span>•</span>
                        <a href="#" class="hover:text-amber-700 transition-colors">Bantuan IT HRD</a>
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