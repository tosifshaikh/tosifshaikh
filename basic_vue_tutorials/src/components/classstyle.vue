<template>
<div>
  <h1 class="homeStyle">Class and Styling</h1>
  <h1 v-bind:style="{color:'blue'}">Style Binding</h1>
  <h1 v-bind:style="{color:colorkey}">Style Binding</h1>
  <h1 v-bind:class="{display:show, displayColor:show}">Style Binding with object</h1>
  <h1 v-bind:class="[aclass,'aclass']">Style Binding with Array</h1>
  <h1 v-bind:class="[show ? 'yesShow' : 'noShow']">Style Binding with Array2</h1>
</div>
</template>

<script>
export default {
  name: "Home3",
  data() {
    return {
      show:true,
      aclass : 'arrayClass',
      colorkey : 'red'
    }
  }
}
</script>

<style scoped>
.homeStyle {
  color: orange;
}
.display {
  background-color: brown;
}
.arrayClass {
  background-color: cyan;
}
</style>