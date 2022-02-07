<template>
  <div>
    <h1>Inside the component</h1>
    <h1>{{ data }}</h1>
    <h1>{{ msg }}</h1>
    <h1 v-if="show">Conditional statement show</h1>
    <h1 v-else>Conditional statement hide</h1>
    <button v-on:click="hello('alert call')" >Call hello function</button>
    <button v-on:click="display" >ToggleButton</button>
    <table border="1px">
      <tr>
        <td>Id</td>
        <td>Name</td>
        <td>Email</td>
      </tr>
      <tr v-for="user in users" :key="user.id">
        <td>{{user.id}}</td>
        <td>{{user.name}}</td>
        <td>{{user.email}}</td>

      </tr>
    </table>
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
    hello(msg) {
      alert(msg);
      console.log('hello world');
    },
    display() {
      this.show=!this.show;
    }
  },data() {
    return {
      users : [
        {id:1, name: 'abc1', email : 'abc1@gmail.com'},
        {id:2, name: 'abc2', email : 'abc2@gmail.com'},
        {id:3, name: 'abc3', email : 'abc3@gmail.com'},
        {id:4, name: 'abc4', email : 'abc4@gmail.com'}
      ]
    }
  }
}
</script>
