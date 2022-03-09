
<template>
<div id="app">
    <div v-if="isLoggedIn">
  <div class="loader-bg">
		<div class="loader-track">
			<div class="loader-fill"></div>
		</div>
	</div>
  <Navbar />
  <Header/>
<div class="pcoded-main-container">
    <div class="pcoded-content">

       <router-view ></router-view>
        </div>


    </div>


    </div>
     <router-view ></router-view>
    <div v-if="isLoggedIn==false">
 <router-view ></router-view>
    </div>




</div>

</template>
<script>
import Navbar from "./components/navbar.vue"
import Header from "./components/header.vue"
export default {
    name: "App",
    components :{
    Header,
    Navbar,
     },
     data() {
         return {
                isLoggedIn :false
         }
     },
    mounted() {
         this.$nextTick(()=>{
         /*  var script = document.createElement('script');
          script.setAttribute('src','/js/vendor-all.min.js');

          document.head.appendChild(script);
          var script = document.createElement('script');
          script.setAttribute('src','/js/bootstrap.min.js');
          document.head.appendChild(script);
          var script = document.createElement('script');
          script.setAttribute('src','/js/pcoded.min.js');
          document.head.appendChild(script);  */
       });
    },
}
</script>
