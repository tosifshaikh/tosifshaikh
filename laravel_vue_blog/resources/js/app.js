require('./bootstrap');
import Vue from 'vue';

import App from './App.vue';
import router from './router.js';
import ViewUI from 'view-design';
import common from './common';
import jsonToHTML from './jsonToHTML';
import store from './store/store';
import  GlobalMixin  from './mixins/GlobalMixin';
import Editor from 'vue-editor-js/src/index'
// import style
import 'view-design/dist/styles/iview.css';

Vue.component('App', App.default);
Vue.use(ViewUI);
Vue.use(router);
Vue.mixin(common);
Vue.mixin(jsonToHTML);
Vue.mixin(GlobalMixin);

//import Axios from 'axios';
Vue.use(Editor)
//Axios.interceptors.request.use((config) => {
//     let params = new URLSearchParams();
//     params.append('hi', 'hello');
//     config.params = params;
//     return config;
// });
const app = new Vue({
    router,store,
    render : h => h(App),
})

app.$mount('#app');
 //window.addEventListener('load', function () {
  
//}) ;
/* const app = new Vue({
    el: "#app",
    router,
    render : h => h(App)
}) */
