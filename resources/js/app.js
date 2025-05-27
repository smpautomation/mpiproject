import '../css/app.css';
import './bootstrap';
import '../css/fonts.css';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createApp, h } from 'vue';

// Import VueApexCharts
import VueApexCharts from 'vue3-apexcharts';

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

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

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
