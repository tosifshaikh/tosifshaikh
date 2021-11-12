import Vue from 'vue';
import Router from 'vue-router';
import Welcome from './views/Welcome';
Vue.use(Router);
const routes = [
    {path: '/', name: 'welcome', component: Welcome},
    {path: '/categories', name: 'categories', component: () => import('./views/Categories')},

];
const router = new Router({
    routes : routes,
    linkActiveClass : 'active'
})
export default router
