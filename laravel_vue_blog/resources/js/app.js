require('./bootstrap');
import Vue from 'vue';
import store from './store.js';
import App from './App.vue';
import router from './router.js';
import ViewUI from 'view-design';
import common from './common';
// import style
import 'view-design/dist/styles/iview.css';

Vue.use(ViewUI);
Vue.use(router);
Vue.mixin(common);
Vue.component('App', require('./App.vue').default);
 //window.addEventListener('load', function () {
    const app = new Vue({
        //el: "#app",

        router,store,
      //  render : h => h(App)
    }).$mount('#app')
//}) ;
/* const app = new Vue({
    el: "#app",
    router,
    render : h => h(App)
}) */
