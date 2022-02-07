<template>
<div>
  <div v-if="error.length" >
    <b>Please correct the following</b>
      <ul>
      <li v-for="e in error" v-bind:key="e.id">
        {{e}}
      </li>
      </ul>
  </div>

  <form @submit="login">
  <input type="text" name="username" v-model=loginForm.username id="username" placeholder="Enter your name"> <br /><br />
  <input type="password" name="pass" v-model=loginForm.password id="pass" placeholder="Enter your password" > <br /><br />
  <button v-on:click="loginData">Login</button>
  <button type="submit">Submit</button>
  </form>
</div>
</template>

<script>
export default {
  name: "Login",
  data() {
    return {
      error: [],
      loginForm : {
        username: null,
        password : null,

      }
    }
  },
  methods : {
    loginData() {
      console.log('login data',this.loginForm)
    },
    login(e) {
      this.error = [];
      if (this.loginForm.username && this.loginForm.password) {
        console.log('No Error');
      }
      if (!this.loginForm.username) {
        this.error.push('User name is required.');
      }
      if (!this.loginForm.password) {
        this.error.push('password is required.');
      }
      console.log('login',this.error)
      e.preventDefault();
    }

  }
}
</script>

<style scoped>

</style>