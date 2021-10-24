<template>
<div>
<h1>Bind</h1>
<h1 style="white-space: pre-line">{{ message }}</h1>
 <!-- <input type="text" v-model="message"> -->
  <textarea name="txt" id="" cols="30" rows="10" v-model="message"></textarea>
</div>
</template>

<script>
export default {
  name: "databinding",
  data() {
    return {
      message: "Hi"
    }
  }
}
</script>

<style scoped>

</style>