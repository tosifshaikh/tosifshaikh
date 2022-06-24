
<html>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<link rel="stylesheet" href="style.css">


<body>


<script type="text/x-template" id="modal-template">
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">

          <div class="modal-header">
            <slot name="header">
              default header
            </slot>
          </div>

          

          <div class="modal-footer">
            <slot name="footer">
              default footer
              <button class="modal-default-button" @click="$emit('close')">
                OK
              </button>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</script>



<div id="app">
  <button id="show-modal" @click="showModal = true">Show Modal</button>
  <!-- use the modal component, pass in the prop -->
  <modal v-if="showModal" @close="showModal = false">
    <!--
      you can use custom content here to overwrite
      default content
    -->
    <h3 slot="header">custom header</h3>
  </modal>
</div>



<div id="loginDiv">
  <form id="loginForm"  >
  <p v-if="errors.length">
    <b>Please correct the following error(s):</b>
    <ul><li v-for="error in errors">{{ error }}</li></ul>
  </p>
  <p><label for="lblUser">Username:</label><input id="txtUser" v-model="dataUserName" type="text" name="txtUser"></p>
  <p><label for="lblPass">Password:</label><input type="password" id="txtPass" v-model="dataPassWord" type="text" name="txtPass"></p>
  <p><input type="button" v-on:click="checkForm" value="Submit"></p>
  <input name="file" type="file" id="file"/>

	<input type="button" v-on:click="uploadForm" value="Upload">
  </form>
  
</div>

<script>
 const app = new Vue({
  el: '#loginForm',
  data: {
    errors: [],
    dataUserName: null,
    dataPassWord: null,
  } ,
   
  methods:{
    checkForm: function (e) {
      /*if (this.dataPassWord && this.dataPassWord) {
        return true;
      } */
      this.errors = [];
      if (!this.dataUserName) {
        this.errors.push('Username required.');
      }
      if (!this.dataPassWord) {
        this.errors.push('Password required.');
      }
      //e.preventDefault();
      ///dev/custom/BackEnd/login/checklogin/
      if(this.errors.length==0){
     	// GET /someUrl
  		axios.get('ajax/manageLogin.php?act=checkLogin',{
  			params:{
  				Username:this.dataUserName,
  				password:this.dataPassWord
  			}
  		}).then(function(response){
        	console.log(response.data);	
        });

  		} 	
  
	  },
	  uploadForm : function(e){
	  	var formData = new FormData();
		var imagefile = document.querySelector('#file');
		formData.append("file", imagefile.files[0]);
		axios.post('ajax/uploadimg.php?act=upload', formData, {
			headers: {
			  'Content-Type': 'multipart/form-data'
			}
		})
	  }
	 }
	
});

 Vue.component('modal', {
  template: '#modal-template'
})

// start app
new Vue({
  el: '#app',
  data: {
    showModal: false
  }
})
</script>
</html>