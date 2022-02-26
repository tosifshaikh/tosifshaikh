require('./bootstrap');
import Vue from 'vue';
import App from './App.vue';
import router from './router.js';
import ViewUI from 'view-design';
//import '../../public/js/vendor-all.min.js'
//import '../../public/js/bootstrap.min'
//import '../../public/js/pcoded.min.js'
//import '../../public/js/pcoded.min.js'
// import style
import 'view-design/dist/styles/iview.css';

Vue.use(ViewUI);
window.addEventListener('load', function () {
    const app = new Vue({
        el: "#app",
        router,
        render : h => h(App)
    }) 
});
/* const app = new Vue({
    el: "#app",
    router,
    render : h => h(App)
}) */