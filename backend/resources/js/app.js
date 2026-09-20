import './bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist'; // 1. Import plugin persist

window.Alpine = Alpine;

Alpine.plugin(persist); // 2. Daftarkan plugin ke Alpine

Alpine.start();