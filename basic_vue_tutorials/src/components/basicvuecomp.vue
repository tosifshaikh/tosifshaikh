<template>
  <div>
    <h1>Inside the component</h1>
    <h1>{{ data }}</h1>
    <h1>{{ msg }}</h1>
    <button v-on:click="hello" >Call hello function</button>
  </div>
</template>

<script>
export default {
  name: "basicvuecomp",
  props : {
    data : String,
    msg : String,
  },
  methods :{
    hello() {
      console.log('hello world');
    }
  }
}
</script>
