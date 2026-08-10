import './bootstrap';
import { createApp } from 'vue';

// Import Komponen Vue
import MenuUjian from './components/MenuUjian.vue';
import MenuPegawai from './components/MenuPegawai.vue';

const app = createApp({
    data() {
        return {
            activeMenu: 'menu1'
        }
    },
    template: `
        <div>
            <!-- NAVBAR RSU BUNDA THAMRIN -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm mb-4">
                <div class="container">
                    <a class="navbar-brand fw-bold" href="#">
                        <i class="bi bi-hospital me-2"></i>RSU BUNDA THAMRIN
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link text-white fw-semibold" :class="{ 'active text-decoration-underline': activeMenu === 'menu1' }" href="#" @click.prevent="activeMenu = 'menu1'">
                                    <i class="bi bi-pencil-square me-1"></i> Ujian Diklat & Monitoring
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white fw-semibold ms-lg-3" :class="{ 'active text-decoration-underline': activeMenu === 'menu2' }" href="#" @click.prevent="activeMenu = 'menu2'">
                                    <i class="bi bi-folder-symlink me-1"></i> Database & Berkas Pegawai
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- KOMPONEN VUE -->
            <main>
                <menu-ujian v-if="activeMenu === 'menu1'"></menu-ujian>
                <menu-pegawai v-if="activeMenu === 'menu2'"></menu-pegawai>
            </main>
        </div>
    `
});

app.component('menu-ujian', MenuUjian);
app.component('menu-pegawai', MenuPegawai);

app.mount('#app');