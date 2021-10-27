import { createApp } from 'vue'
import App from './App.vue'
createApp(App).filter('ucase',function(val){
return val.toUpperCase();
})
createApp(App).mount('#app')
