<?php 
$recId = isset($_REQUEST['recId'])?$_REQUEST['recId']:'';
?>
<html>
   <head>
      <title>VueJs Instance</title>
      <script type = "text/javascript" src = "../design/js/vue.js"></script>
	   <script type = "text/javascript" src = "../design/js/axios.min.js"></script>
	   <link rel="stylesheet" href="../design/css/style.css">
	   
       <script>
	   		var rec_Id=<?php echo $recId;?>;
	   </script>
	   
   </head>
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
			
			<input name="file" type="file" id="file"/>
              <button class="modal-default-button" @click="uploadForm">
                Upload
              </button>
              <button class="modal-default-button" @click="$emit('close')">
                Close
              </button>
			
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</script>

      <style>
         #databinding{
            padding: 20px 15px 15px 15px;
            margin: 0 0 25px 0;
            width: auto;
         }
         span, option, input {
            font-size:20px;
         }
         .Table{
            display: table;
            width:80%;
         }
         .Title{
            display: table-caption;
            text-align: center;
            font-weight: bold;
            font-size: larger;
         }
         .Heading{
            display: table-row;
            font-weight: bold;
            text-align: center;
         }
         .Row{
            display: table-row;
         }
      </style>
        <div id = "databinding" style = "">
             <h1>Manage Doc Page</h1>
             <button v-on:click = "showModal = true">Upload Document</button>
			 <!-- use the modal component, pass in the prop -->
			  <modal v-if="showModal" @close="showModal = false">
				<!--
				  you can use custom content here to overwrite
				  default content
				-->
				<h3 slot="header">custom header</h3>
			  </modal>
             <button v-on:click = "saveStatus">Save Status</button>
                <div class = "Table" v-for="(file,index) in fileObj" style="margin:10px;">
                    <div class = "Row">
                        <img :src="getImageurl(index)" v-bind:alt="file.filename" width = "300" height = "250" >
                        <span><p>{{file.filename}}</p></span>
                    </div>
             </div>
        </div>
      
      <script type = "text/javascript">
         var vm = new Vue({
            el: '#databinding',
            data: {
               fname:'',
               lname:'',
			   fileObj:{},
			   recId:rec_Id,
			   showModal: false,
            },
            methods :{
				showdata : function() {
					axios.get('../ajax/manageDoc.php?act=getDoc',{
							params:{
								recId:this.recId,
							}
						}).then(function(response){
						console.log(response.data);
						vm.fileObj=response.data;
					});
				},
				getImageurl:function(index){
					return '../../BackEnd/public/images/'+vm.fileObj[index].filename;
				},
				UpDoc :function(){
					
				},
				saveStatus:function(){
					
				}
            }
         });
		 vm.showdata();
		 
		  Vue.component('modal', {
			  template: '#modal-template',
			  methods:{
			  uploadForm : function(e){
					var formData = new FormData();
					var imagefile = document.querySelector('#file');		
					formData.append("file", imagefile.files[0]);
					formData.append("rec_id", rec_Id);
					axios.post('../ajax/uploadimg.php?act=upload', formData, {
						headers: {
						  'Content-Type': 'multipart/form-data'
						}
					}).then(function(response){
						console.log(response.data);
						vm.showModal=false;
						vm.showdata();						
					})
				  }
			  }
			})
      </script>
   </body>
</html>