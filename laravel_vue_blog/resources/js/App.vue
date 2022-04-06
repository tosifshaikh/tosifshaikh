<template>
  <div id="app">
      <TheLoader v-if="showLoading"></TheLoader>
      <div v-if="!isAuthenticated && this.$router.currentRoute.name == 'login' ">
          <router-view :key="$router.fullPath"></router-view>
<!--      <div v-if="$store.state.user == false && this.$router.currentRoute.name == 'login' ">
      <router-view :key="$route.fullPath"></router-view>-->
    </div>

  <div v-if="isAuthenticated">
      <div class="loader-bg">
        <div class="loader-track">
          <div class="loader-fill"></div>
        </div>
      </div>
      <Navbar />
      <Header />
      <div class="pcoded-main-container">
        <div class="pcoded-content">
          <router-view :key="$router.fullPath"></router-view>
        </div>
      </div>
    </div>

<!--      <div v-if="$store.state.user == false && this.$router.currentRoute.name == 'login' ">
      <router-view :key="$route.fullPath"></router-view>-->

  </div>
</template>
<script>
import Navbar from "./components/navbar.vue";
import Header from "./components/header.vue";
import TheLoader from "./components/TheLoader.vue";
import { mapState,mapGetters } from "vuex";
import { AUTO_LOGIN_ACTION, GET_USER_TOKEN_GETTER, IS_USER_AUTHENTICATE_GETTER } from './store/storeconstants';
export default {
  name: "App",
  computed : {
      ...mapState({
          showLoading : (state)  => state.showLoading
      }),
       ...mapGetters('auth', {
              isAuthenticated : IS_USER_AUTHENTICATE_GETTER,
              tokenData : GET_USER_TOKEN_GETTER
          }),
  },

  components: {
    Header,
    Navbar,TheLoader
  },
  //props: ["user",'permission'],
  data() {
    return {
     // isLoggedIn: false,
    };
  },
  mounted() {
     /*  if(!this.isAuthenticated && this.$router.currentRoute.name != 'login') {
           location.reload= '/login';
      } */
       console.log(this.isAuthenticated,'isAuthenticatedmounted',this.tokenData);
      /* this.$nextTick(() => {
     var script = document.createElement('script');
          script.setAttribute('src','/js/vendor-all.min.js');

          document.head.appendChild(script);
          var script = document.createElement('script');
          script.setAttribute('src','/js/bootstrap.min.js');
          document.head.appendChild(script);
          var script = document.createElement('script');
          script.setAttribute('src','/js/pcoded.min.js');
          document.head.appendChild(script);
    });*/
  },
  created() {
    //  this.$store.dispatch(`auth/${AUTO_LOGIN_ACTION}`);
      console.log(this.isAuthenticated,'isAuthenticated333',this.tokenData)
     /* this.$store.commit('updateUser',this.user);
      this.$store.commit('SetUserPermission',this.permission);
     console.log(this.permission,this.user);*/
  },
};
</script>
