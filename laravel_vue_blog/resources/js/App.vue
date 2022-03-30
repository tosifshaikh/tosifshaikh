<template>
  <div id="app">
<!--    <div v-if="$store.state.user">
      <div class="loader-bg">
        <div class="loader-track">
          <div class="loader-fill"></div>
        </div>
      </div>
      <Navbar :permission="permission"/>
      <Header />
      <div class="pcoded-main-container">
        <div class="pcoded-content">
          <router-view :key="$route.fullPath"></router-view>
        </div>
      </div>
    </div>-->
      <div >
          <router-view ></router-view>
<!--      <div v-if="$store.state.user == false && this.$router.currentRoute.name == 'login' ">
      <router-view :key="$route.fullPath"></router-view>-->
    </div>
  </div>
</template>
<script>
import Navbar from "./components/navbar.vue";
import Header from "./components/header.vue";
export default {
  name: "App",
  components: {
    Header,
    Navbar,
  },
  //props: ["user",'permission'],
  data() {
    return {
     // isLoggedIn: false,
    };
  },
  mounted() {
    //console.log(this.userData)
    this.$nextTick(() => {
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
  created() {
     /* this.$store.commit('updateUser',this.user);
      this.$store.commit('SetUserPermission',this.permission);
     console.log(this.permission,this.user);*/
  },
};
</script>
