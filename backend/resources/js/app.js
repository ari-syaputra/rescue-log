import './bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import localforage from 'localforage';

window.Alpine = Alpine;
window.localforage = localforage;

Alpine.plugin(persist);

Alpine.start();