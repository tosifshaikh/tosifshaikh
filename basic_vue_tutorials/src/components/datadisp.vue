<template>
<div>
  <p v-html="rowHTML"></p>
  <h3 v-once>{{name}}</h3>
  <h3>{{name}}</h3>
  <h3>{{ok? "Yes" : "No"}}</h3>
  <h3>{{a+b}}</h3>
  <button v-bind:disabled="disableBtn" v-on:click="updateTex()">Click Me</button>
</div>
</template>

<script>
export default {
  name: "Home2",data() {
    return {
      name : 'Tos',
      a:10,
      b:20,
      ok:true,
      rowHTML : '<h1>Component in vue js</h1>',
      disableBtn: true
    }
  },methods : {
    updateTex() {
      this.name= 'peter';
      this.ok=!this.ok;

    }
  }
}
</script>

<style scoped>

</style>