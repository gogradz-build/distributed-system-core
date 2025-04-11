import './bootstrap';
import '../css/app.css';
import 'admin-lte/plugins/jquery/jquery.min.js';
import 'admin-lte/plugins/jquery-ui/jquery-ui.min.js';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import 'admin-lte/plugins/chart.js/Chart.min.js';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import 'bootstrap-fileinput/css/fileinput.min.css';
import 'bootstrap-fileinput/js/fileinput.min.js';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('barChart').getContext('2d');
    // Create the bar chart
    new Chart(ctx, {
        type: 'bar',

        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'products',
                backgroundColor: 'rgba(60,141,188,0.9)',
                borderColor: 'rgba(60,141,188,0.8)',
                data: [65, 59, 80, 81, 56, 55, 40]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    // Initialize Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');

    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Hammer', 'Mallet', 'Handsaw', "Mallet"],
            datasets: [{
                data: [300, 50, 100, 250],
                backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#f39c12'],
                hoverBackgroundColor: ['#f56954', '#00a65a', '#f39c12', '#f39c12']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const path = name.startsWith('Admin')
            ? `./Pages/Admin/${name.replace('Admin/', '')}.vue`
            : `./Pages/FrontEnd/${name.replace('FrontEnd/', '')}.vue`;
        return resolvePageComponent(path, import.meta.glob('./Pages/**/*.vue'));
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Toast, { // Add Toast here TOAST
                timeout: 3000, // Customize options as needed TOAST
                transition: "Vue-Toastification__fade", // Custom transition class
                position: 'top-right', //TOAST
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});


