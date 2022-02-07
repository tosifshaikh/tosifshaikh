<template>



<div>
  <div>
    <form action="" @submit="submitData" method="post">
      <input type="text" name="name" v-model="post.name"> <br /> <br />
      <input type="text" name="job" v-model="post.job"> <br /> <br />
    <button type="submit">Post</button>
    </form>
  </div>
  <h1>Employee list</h1>
  <div>
    <table>
      <tr>
        <td>ID</td>
        <td>Name</td>
        <td>Email</td>
        <td>Action</td>
      </tr>
      <tr v-for="item in list" v-bind:key="item.id">
        <td>{{item.id}}</td>
        <td>{{item.first_name + ' ' + item.last_name}}</td>
        <td>{{item.email}}</td>
        <td><button v-on:click="deleteUser(item.id)">Delete</button></td>
      </tr>
    </table>
  </div>
</div>
</template>

<script>

export default {
  name: "EmployeeList",
  data(){
    return {
      list : undefined,
      post: {
        name: null,
        job: null
      }
    }
  },
  mounted() {
    this.getData()
  },
  methods : {
    deleteUser(id){  console.log(id);
      this.$http.delete('https://reqres.in/api/users/'+ id)
          .then(result => {
            this.list = result.data.data
            console.log(result);
            this.getData()
          })
    },
    getData() {
      this.$http.get('https://reqres.in/api/users')
          .then(result => {
            this.list = result.data.data
            console.log(result.data.data);
          })
    },
    submitData(e) {
      this.$http.post('https://reqres.in/api/users',this.post).then((result)=>
      {
        console.warn(result)

      });
      e.preventDefault();
    }
  }
}
</script>

<style scoped>

</style>