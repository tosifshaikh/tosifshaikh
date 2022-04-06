import Vue from "vue";
import Router from "vue-router";
import adminusers from "./admin/pages/AdminUsers.vue";
import login from "./components/login.vue";
import role from "./admin/pages/role.vue";
import assignrole from "./admin/pages/AssignRoles.vue";
import editblog from "./admin/pages/editBlog.vue";
import notfound from "./admin/pages/notfound.vue";
import store from "./store/store";
import { GET_AUTH_DATA, IS_USER_AUTHENTICATE_GETTER } from "./store/storeconstants";
Vue.use(Router);
const routes = [
    {
        path: "/",
        redirect: "/login",
    },
    {
        path: "/dashboard",
        name: "dashboard",
        component: () => import("./components/dashboard.vue"),
        meta: { auth: true },
    },
    {
        path: "/tags",
        name: "tags",
        component: () => import("./admin/pages/tags.vue"),
        meta: { auth: true },
    },
    {
        path: "/category",
        name: "category",
        component: () => import("./admin/pages/category.vue"),
        meta: { auth: true },
    },
    {
        path: "/admin-users",
        name: "adminusers",
        component: adminusers,
        meta: { auth: true },
    },
    {
        path: "/login",
        name: "login",
        component: login,
        meta: { auth: false },
    },
    {
        path: "/role-management",
        name: "role-management",
        component: role,
        meta: { auth: true },
    },
    {
        path: "/assign-roles",
        name: "assign-roles",
        component: assignrole,
        meta: { auth: true },
    },
    {
        path: "/create-blog",
        name: "create-blog",
        component: () => import("./admin/pages/createBlog.vue"),
        meta: { auth: true },
    },
    {
        path: "/blogs",
        name: "blogs",
        component: () => import("./admin/pages/Blogs.vue"),
        meta: { auth: true },
    },
    {
        path: "/editblogs/:id",
        name: "editblogs",
        component: editblog,
        meta: { auth: true },
    },
    {
        path: "*",
        name: "*",
        component: () => import("./admin/pages/notfound.vue"),
        meta: { auth: false },
        /*beforeEnter(to, from ,next) {

        }*/
    },
];

const router = new Router({
    //to remove # from URL
    //before : http://127.0.0.1:8000/#/home
    //after : http://127.0.0.1:8000/home
    mode: "history",
    routes: routes,
    linkActiveClass: "active",
});

 router.beforeEach((to, from, next) => {
    console.log(store.getters[`auth/${IS_USER_AUTHENTICATE_GETTER}`],'store.restored',to.meta,to.meta.auth)
    if (
        "auth" in to.meta &&
        to.meta.auth &&
        !store.getters[`auth/${IS_USER_AUTHENTICATE_GETTER}`]
    ) {
        console.log('1')
        return next("/login");
    } else if (
        "auth" in to.meta &&
        !to.meta.auth &&
        store.getters[`auth/${IS_USER_AUTHENTICATE_GETTER}`]
    ) {
        console.log('111')
        return next("/dashboard");
    } else {
        console.log('122211')
        //window.location.reload();
        return next();
    }
});
export default router;
