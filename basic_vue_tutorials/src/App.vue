<template>
  <div>
    <img alt="Vue logo" src="./assets/logo.png">
    <!--<HelloWorld msg="Welcome to Your Vue.js App"/> -->
    <!-- <basicvuecomp data="this is passed data in vue" msg = "another data pass"/> -->
    <!-- <Home /> -->
    <!-- <Home2 /> -->
    <!-- <Home3 /> -->
    <!-- <databinding />
    <h1>{{title}}</h1>-->
    <h2>{{name | ucase}}</h2>
    <!--<Child v-bind:name=userName  v-bind:users=users v-on:ChangeTitle="updateTitle($event)"/>
  <Signup />
    <Login />
    <EmployeeList />-->
    <nav></nav>
    <ul>
      <li>
        <router-link to="/">Home</router-link>
      </li>
      <li><router-link to="/list">List</router-link></li>
    </ul>
<router-view />
  </div>

</template>

<script>
//import HelloWorld from './components/HelloWorld.vue'
//import basicvuecomp from './components/basicvuecomp.vue'
//import Home from './components/Home.vue'
//import Home2 from './components/datadisp.vue'
//import Home3 from './components/classstyle.vue'
//import databinding from './components/databinding.vue'
//import Child from './components/Child.vue'
//import Signup from './components/Signup.vue'
//import Login from './components/Login.vue'
//import EmployeeList from './components/EmployeeList.vue'
export default {
  name: 'App',
  components: {
    // HelloWorld,
//  basicvuecomp,
    // Home,
    //Home2,
    // Home3,
    //databinding,
    // Child,
    // Signup,
    //Login,
   // EmployeeList,
  }, data() {
    return {
      name : 'Tosif shaikh',
      // userName: "Tos",title :"Props tutorial",
      /*users:[
          {name : "T20", email: "abc@1"},
          {name : "T201", email: "abc@2"},
          {name : "T202", email: "abc@3"}
      ]*/
    }
  },
  methods : {
    /*updateTitle(title) {
      this.title =title;
    }*/
  }
}
</script>

<style>

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
