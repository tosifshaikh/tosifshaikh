<template>
<div>
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
       <router-view name="dashboard"></router-view>
     
    </div></div>
 
  

</div>
</template>

<script>
import Navbar from "../components/navbar.vue"
import Header from "../components/header.vue"
//import dashboard from "../components/dashboard.vue"

export default {
    name: "Home",
     components :{
    Header,
    Navbar,//dashboard
     },

}
</script>