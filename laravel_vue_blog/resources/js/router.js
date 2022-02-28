import Vue from 'vue';
import Router from 'vue-router';

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