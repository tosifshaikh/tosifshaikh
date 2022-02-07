<template>
<div>
  <h1>Child Component</h1>
  <h3>{{ name }}</h3>
  <h3 v-for="user in users" :key="user.name">
    {{user.name}} -- {{user.email}}
  </h3>
  <button v-on:click="updateTitle">Update Parent Title</button>
</div>
</template>

<script>
export default {
  name: "Child",
  props :["name",'users'],
 methods : {
   updateTitle(){
      console.warn('Hello from console');
      this.$emit('ChangeTitle','Parent Updated');
   }
 },data() {
    return {}
  },
  created() {
    console.warn('created');
  },beforeCreate() {
    console.warn('beforeCreate');
  },beforeMount() {
    console.warn('beforeMount');
  },mounted() {
    console.warn('mounted');
  }

}
</script>

<style scoped>

</style>