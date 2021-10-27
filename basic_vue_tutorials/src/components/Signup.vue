<template>
<div>
  <input type="text" name="username" id="username" placeholder="Enter your name" v-model=Signup.username> <br /><br />
  <input type="password" name="pass" id="pass" placeholder="Enter your password" v-model=Signup.pass> <br /><br />
  <p>Choose Hobbies</p>
  <input type="checkbox" name="cricket" id="cricket" value="Cricket" v-model=Signup.hobbies>
  <label for="cricket">Cricket</label>
  <input type="checkbox" name="football" id="football" value="Football" v-model=Signup.hobbies>
  <label for="football">Football</label>
  <br /><br />
  <p>Choose Gender</p>
  <input type="radio" name="male" id="male" value="Male" v-model=Signup.gender>
  <label for="male">Male</label>
  <input type="radio" name="female" id="female" value="Female" v-model=Signup.gender>
  <label for="female">Female</label>  <br /><br />
 <button v-on:click="signupUser">Sign up</button>
</div>
</template>

<script>
export default {
  name: "Signup"
  ,data() {
    return {
      Signup : {
        username : null,
           pass : null,
            hobbies : [],
            gender : null
      }
    }
  },
  methods :{
    signupUser() {
      console.warn(this.Signup);
    }
  }
}
</script>

<style scoped>

</style>