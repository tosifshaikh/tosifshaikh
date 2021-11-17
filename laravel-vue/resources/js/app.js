import Vue from 'vue';
import App from './App.vue';
import router from './router.js';
import store from './store';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap';
import BootstrapVue from 'bootstrap-vue';
import FlashMessage from '@smartweb/vue-flash-message';
import VueCryptojs from 'vue-cryptojs';

Vue.use(BootstrapVue);
Vue.use(FlashMessage);
Vue.use(VueCryptojs);

new Vue({
    el: '#app',
    router,
    store,
    render : h => h(App)
});
