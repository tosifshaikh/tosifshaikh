<template>
<div>
  <h1>Home Component</h1>
 <p><router-link to="user/1">Home 1</router-link></p>
  <p><router-link to="user/2">Home 2</router-link></p>
  <p><router-link to="user/3">Home 3</router-link></p>
  <Users />
  <Products />
  <Products />
</div>
</template>

<script>
import Users from './Users.vue';
import Products from './Products.vue';
export default {
  name: "Home",
  components : {
    Users, Products
  }
}
</script>

<style scoped>
div{
  background-color: #dde5ec;
  height: 500px;
}
</style>