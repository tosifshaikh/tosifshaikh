import Vue from 'vue';
import Router from 'vue-router';
import Home from './views/Home';
import * as auth from './Services/auth_service';
Vue.use(Router);
const routes = [

    {path: '/home', component: Home,

    children : [
        {path: '', name: 'dashboard', component: () => import('./views/Dashboard')},
        {path: 'categories', name: 'categories', component: () => import('./views/Categories')},
    ],
        beforeEnter(to, from ,next) {
            if (!auth.isLoggedIn()) {
                next('/login');
            } else {
                next();
            }
        }
    },
    {path: '/register', name: 'register', component: () => import('./views/authentication/Register')},

    {path: '/login', name: 'login', component: () => import('./views/authentication/Login'),
        beforeEnter(to, from ,next) {
            if (!auth.isLoggedIn()) {
                next();
            } else {
                next('/home');
            }
        }},

    {path: '/reset-password', name: 'reset-password', component: () => import('./views/authentication/ResetPassword')},

];
const router = new Router({
    //to remove # from URL
    //before : http://127.0.0.1:8000/#/home
    //fter : http://127.0.0.1:8000/home
    mode : 'history',
    routes : routes,
    linkActiveClass : 'active'
})
export default router
