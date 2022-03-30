import Vue from "vue";
import Router from "vue-router";
import adminusers from "./admin/pages/AdminUsers.vue";
import login from "./components/login.vue";
import role from "./admin/pages/role.vue";
import assignrole from "./admin/pages/AssignRoles.vue";
import editblog from "./admin/pages/editBlog.vue";
import notfound from "./admin/pages/notfound.vue";
import store from './store/store';
Vue.use(Router);
const routes = [
      {
         path: "/",
         redirect : "/login"
     }
     ,
    {
        path: "/dashboard",
        name: "dashboard",
        component: () => import("./components/dashboard.vue"),
    },
    {
        path: "/tags",
        name: "tags",
        component: () => import("./admin/pages/tags.vue"),
    },
    {
        path: "/category",
        name: "category",
        component: () => import("./admin/pages/category.vue"),
    },
    {
        path: "/admin-users",
        name: "adminusers",
        component: adminusers,
    },
    {
        path: "/login",
        name: "login",
        component: login,
    },
    {
        path: "/role-management",
        name: "role-management",
        component: role,
    },
    {
        path: "/assign-roles",
        name: "assign-roles",
        component: assignrole,
    },
    {
        path: "/create-blog",
        name: "create-blog",
        component: () => import("./admin/pages/createBlog.vue"),
    },
    {
        path: "/blogs",
        name: "blogs",
        component: () => import("./admin/pages/Blogs.vue"),
    },
    {
        path: "/editblogs/:id",
        name: "editblogs",
        component: editblog,
    },
    {
        path: "*",
        name: "*",
        component: () => import('./admin/pages/notfound.vue'),
        /*beforeEnter(to, from ,next) {

        }*/
    },
];

const router = new Router({
    //to remove # from URL
    //before : http://127.0.0.1:8000/#/home
    //fter : http://127.0.0.1:8000/home
    mode: "history",
    routes: routes,
    linkActiveClass: "active",
});
/* router.beforeEach(() => {
console.log(store.state.auth,'store')
}); */
export default router;
