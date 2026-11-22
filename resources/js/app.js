import jquery from 'jquery'
window.jQuery = window.jquery = jquery
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { InertiaProgress } from '@inertiajs/progress';
import moment from 'moment';
import VueApexCharts from "vue3-apexcharts";

createInertiaApp({
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      //set mixins
      .mixin({
        methods: {
            examTimeRangeChecker: function (start_time, end_time) {
                return new Date() >= new Date(start_time) && new Date() <= new Date(end_time)
            },

            examTimeStartChecker: function (start_time) {
                return new Date() < new Date(start_time)
            },

            examTimeEndChecker: function (end_time) {
                return new Date() > new Date(end_time)
            },
            formatDateWithTime: function (date) {
                return moment(date).format('DD MMMM YYYY H:mm:ss');
            },
            formatDate: function (date) {
                return moment(date).format('DD MMMM YYYY');
            },
            formatWaLink: function (waInput) {
                if (!waInput) return 'https://wa.me/';
                if (waInput.startsWith('http://') || waInput.startsWith('https://')) return waInput;
                if (waInput.startsWith('@')) return 'https://wa.me/' + waInput.substring(1);
                var clean = waInput.replace(/[^0-9]/g, '');
                if (clean.startsWith('0')) clean = '62' + clean.substring(1);
                return 'https://wa.me/' + clean;
            }
        },
        computed: {
            dynamicWa() {
                return this.$page?.props?.app?.admin_wa || this.wa;
            },
            dynamicEmail() {
                return this.$page?.props?.app?.admin_email || this.email;
            },
            dynamicAlamat() {
                return this.$page?.props?.app?.admin_address || this.alamat;
            },
            dynamicAppName() {
                return this.$page?.props?.app?.name || this.appName;
            }
        },
        data() {
            return { 
              appName   : "e-TOEFL Ma'had Aly Tebuireng", 
              wa        : "@mahadalytbi", 
              copyright : "© " + new Date().getFullYear() + " e-TOEFL Ma'had Aly Hasyim Asy'ari Tebuireng",
              email     : "mahadaly@tebuireng.ac.id",
              alamat    : "Jl. Irian Jaya No. 10, Tebuireng, Cukir, Kec. Diwek, Kabupaten Jombang, Jawa Timur 61471" 
            }
        },
      })
      .use(plugin)
      .use(VueApexCharts)
      .mount(el)
  },
});

InertiaProgress.init();
