import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import axios from 'axios';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faHeart } from '@fortawesome/free-solid-svg-icons';
import { faHeart as faHeartOutline } from '@fortawesome/free-regular-svg-icons';
import { faThumbsDown } from '@fortawesome/free-solid-svg-icons';

library.add(faHeart, faHeartOutline, faThumbsDown);

createInertiaApp({
    resolve: name => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.component('font-awesome-icon', FontAwesomeIcon);
        // Add axios to global properties
        app.config.globalProperties.$axios = axios;

        // Use Inertia plugin and mount the app
        app.use(plugin).mount(el);
        
        
    }
});
