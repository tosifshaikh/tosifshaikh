import Vue from 'vue';
import Router from 'vue-router';
import adminusers from './admin/pages/AdminUsers.vue';
Vue.use(Router)
const routes = [
    {
        path: "/",
        redirect : "/dashboard"
    }
    ,
    {
        path: "/dashboard",
        name : "dashboard",
        component: () => import('./components/dashboard.vue'),

    },
    {
        path: "/tags",
            name : "tags",
            component: () => import('./admin/pages/tags.vue'),
    },
    {
        path: "/category",
            name : "category",
            component: () => import('./admin/pages/category.vue'),
    },
    {
        path: "/admin-users",
            name : "adminusers",
            component: adminusers,
    }


]

const router = new Router({
    //to remove # from URL
    //before : http://127.0.0.1:8000/#/home
    //fter : http://127.0.0.1:8000/home
    mode : 'history',
    routes: routes,
    linkActiveClass : 'active'
})
export default router
