import Vue from 'vue';
import App from './App.vue';
import router from './router.js';
import store from './store';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap';
import BootstrapVue from 'bootstrap-vue';
import FlashMessage from '@smartweb/vue-flash-message';
import Constants from '../js/src/plugins/constants';
import i18n from '../../src/i18n';
import moment from 'moment';
//import VueCryptojs from 'vue-cryptojs';

Vue.use(BootstrapVue);
Vue.use(FlashMessage);
Vue.use(Constants);
Vue.use(moment);
Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('MMM-D')
    }
});
Vue.filter('translate', function(str) {
    if (i18n.messages[i18n.locale] == null) {
        return str;
    }

    const value = i18n.messages[i18n.locale][str];
    return  value == null ? str : value;
});
Vue.mixin({
    methods: {
        translate: function (str) {
            return this.$options.filters.translate(str)
        },
        formatDate : function (str) {
            return this.$options.filters.formatDate(str)
        },
    },
})
new Vue({
    el: '#app',
    router,
    store,
    render : h => h(App)
});
