import Vue from "vue";
import Router from "vue-router";
//import adminusers from "./admin/pages/AdminUsers.vue";
import login from "./components/login.vue";
//import role from "./admin/pages/role.vue";
//import assignrole from "./admin/pages/AssignRoles.vue";
//import editblog from "./admin/pages/editBlog.vue";
//import notfound from "./admin/pages/notfound.vue";
import store from "./store/store";
import { GET_AUTH_DATA, IS_USER_AUTHENTICATE_GETTER } from "./store/storeconstants";

// Lazy Load components dynamically when required

//webpackChunkName: "Login  will display custom name of js.
//const login = () => import(/* webpackChunkName: "Login" */"./components/login.vue");
const dashboard = () => import(/* webpackChunkName: "dashboard" */"./components/dashboard.vue");
const tags = () => import(/* webpackChunkName: "tags" */"./admin/pages/tags.vue");
const category = () => import(/* webpackChunkName: "category" */"./admin/pages/category.vue");
const adminusers = () => import(/* webpackChunkName: "AdminUsers" */"./admin/pages/AdminUsers.vue");
const role = () => import(/* webpackChunkName: "role" */"./admin/pages/role.vue");
const assignrole = () => import(/* webpackChunkName: "AssignRoles" */"./admin/pages/AssignRoles.vue");
const createBlog = () => import(/* webpackChunkName: "createBlog" */"./admin/pages/createBlog.vue");
const blogs = () => import(/* webpackChunkName: "Blogs" */"./admin/pages/Blogs.vue");
const editblog = () => import(/* webpackChunkName: "editBlog" */"./admin/pages/editBlog.vue");
const logout = () => import(/* webpackChunkName: "logout" */"./components/logout.vue");
const notfound = () => import(/* webpackChunkName: "notfound" */"./admin/pages/notfound.vue");
const menu = () => import(/* webpackChunkName: "MenuMaster" */"./admin/pages/MenuMaster.vue");
const menucategory = () => import(/* webpackChunkName: "MenuCategory" */"./admin/pages/MenuCategory.vue");
Vue.use(Router);
const routes = [
    {
        path: "/",
        redirect: "/login",
    },
    {
        path: "/dashboard",
        name: "dashboard",
        component: dashboard,
        meta: { auth: true },
    },
    {
        path: "/tags",
        name: "tags",
        component: tags,
        meta: { auth: true },
    },
    {
        path: "/category",
        name: "category",
        component: category,
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
        component: createBlog,
        meta: { auth: true },
    },
    {
        path: "/blogs",
        name: "blogs",
        component: blogs,
        meta: { auth: true },
    },
    {
        path: "/editblogs/:id",
        name: "editblogs",
        component: editblog,
        meta: { auth: true },
    },{
        path: "/logout",
        name: "logout",
        component: logout,
        meta: { auth: true },
    },
    {
        path: "/menu-master",
        name: "menu-master",
        component: menu,
        meta: { auth: true },
    },{
        path: "/menu-category",
        name: "menu-category",
        component: menucategory,
        meta: { auth: true },
    },

    {
        path: "*",
        name: "*",
        component: notfound,
        meta: { auth: false },
        /*beforeEnter(to, from ,next) {

        }*/
    },
];

const router = new Router({
    //to remove # from URL
    //before : http://127.0.0.1:8000/#/home
    //after : http://127.0.0.1:8000/home
    history: true,
    mode: "history",
    routes: routes,
    linkActiveClass: "active",
});

 router.beforeEach(async(to, from, next) => {
    //console.log(store.getters[`auth/${IS_USER_AUTHENTICATE_GETTER}`],'store.restored',to.meta,to.meta.auth)
    if (
        "auth" in to.meta &&
        to.meta.auth &&
        !store.getters[`auth/${IS_USER_AUTHENTICATE_GETTER}`]
    ) {
       // console.log('1',to)
         next({
            path: '/login',
            replace: true
        });
    } else if (
        "auth" in to.meta &&
        !to.meta.auth &&
        store.getters[`auth/${IS_USER_AUTHENTICATE_GETTER}`]
    ) {
        //console.log('111',to)
         next({ path: "/dashboard",replace: true});
    }
    // console.log('122211')
     //window.location.reload();
     next();
});
export default router;
