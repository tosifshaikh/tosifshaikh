<template>
  <div class="users">
    <h1>Users Component</h1>
    <h2>User Id is {{userId}}</h2>
  </div>
</template>

<script>
export default {
  name: "Users",
  data(){
    return {
      userId : this.$route.params.id
    }
  }
}
</script>

<style scoped>
.users{
  background-color: antiquewhite;
  height: 100px;
  width: 300px;
  padding: 40px;
  margin: 20px;
}
</style>