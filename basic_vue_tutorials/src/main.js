import Vue from 'vue'
import App from './App.vue'
import Axios from 'axios'
import VueRouter from 'vue-router'
import EmployeeList from './components/EmployeeList'
import Home from './components/Home'
import Users from './components/Users'
Vue.use(VueRouter)
const routes= [
  {path : "/", component : Home},
  {path : "/list", component : EmployeeList},
  {path : "/user/:id", component : Users}
]
const router = new VueRouter({routes});
Vue.config.productionTip = false
Vue.filter("ucase",function(val){
  return val.toUpperCase();
})
Vue.prototype.$http = Axios;
new Vue({
  router :  router,
  render: h => h(App),
}).$mount('#app')

