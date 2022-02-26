import Vue from 'vue';
import Router from 'vue-router';
import dashboard from '../js/components/dashboard.vue';

Vue.use(Router)
const routes = [
    {
        path: "/",
        name: "dashboard",
        components: {
            default: import('./pages/home.vue'),
            dashboard: import('../js/components/dashboard.vue')
            
        },
       // component: () => import('./pages/home.vue'),
        meta: {
          layout : 'dashboard'  
        },
        children: [
            {
                path: "/tags", name: "tags",
                component: () => import('./pages/tags.vue'),
                meta: {
                    layout : 'tags'  
                  }
            },
            
        ]
    },
   
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