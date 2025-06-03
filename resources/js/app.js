import '../css/app.css';
import './bootstrap';
import '../css/fonts.css';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createApp, h } from 'vue';
import axios from 'axios';

// Import VueApexCharts
import VueApexCharts from 'vue3-apexcharts';

// Set CSRF and credentials for all axios calls
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
axios.defaults.withCredentials = true;

const csrfToken = document.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
}

// Set axios Authorization header from localStorage token
const token = localStorage.getItem('access_token');
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

// ===== Patch canvas getContext globally =====
const originalGetContext = HTMLCanvasElement.prototype.getContext;
HTMLCanvasElement.prototype.getContext = function(type, options) {
  if (type === '2d') {
    options = options || {};
    options.willReadFrequently = true;
  }
  return originalGetContext.call(this, type, options);
};
// ============================================

createInertiaApp({
  title: (title) => title ? `${title} MPIOnlineSystem` : 'MPIOnlineSystem',
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue')
    ),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(VueApexCharts) // <-- Register VueApexCharts here
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
