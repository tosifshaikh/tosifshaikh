import Vue from 'vue'
import App from './App.vue'
import Axios from 'axios'
import VueRouter from 'vue-router'
import EmployeeList from './components/EmployeeList.vue'
import Home from './components/Home.vue'
Vue.use(VueRouter)
const routes= [
  {path : "/", component : Home},
  {path : "/list", component : EmployeeList}
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

