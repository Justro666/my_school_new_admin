// import "./bootstrap";
import "../css/app.css";
import "../css/nav.css";
import "../css/customcheckbox.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import {usePage } from "@inertiajs/inertia-vue3";

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Ex;braiN";

createInertiaApp({
    title: (title) => `${title} - Ex;braiN`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
});



InertiaProgress.init({ 
    color: "#4B5563",
    showSpinner: true
});

localStorage.setItem('pageLimitFour', true);

