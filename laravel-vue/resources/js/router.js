import Vue from 'vue';
import Router from 'vue-router';
import Home from './views/Home';
import * as auth from './Services/auth_service';
Vue.use(Router);
const routes = [
<<<<<<< HEAD
    {path: '/home', name: 'home', component: Home,
=======
    {path: '/home', component: Home,
>>>>>>> laravel-vue
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
<<<<<<< HEAD
    {path: '/login', name: 'login', component: () => import('./views/authentication/Login')},
=======
    {path: '/login', name: 'login', component: () => import('./views/authentication/Login'),
        beforeEnter(to, from ,next) {
            if (!auth.isLoggedIn()) {
                next();
            } else {
                next('/home');
            }
        }},
>>>>>>> laravel-vue
    {path: '/reset-password', name: 'reset-password', component: () => import('./views/authentication/ResetPassword')},

];
const router = new Router({
   // mode : 'history',
    routes : routes,
    linkActiveClass : 'active'
})
export default router
