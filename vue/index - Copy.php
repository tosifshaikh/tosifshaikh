<!DOCTYPE html>
<html>
<head>
<style>
.error{
	border-color:red;
	background:#FDD;
}
input:focus{
	border-color:red;
}
select:focus{
	border-color:red;
}
textarea:focus{
	border-color:red;
}
</style>
<script src="vue.js"></script>
</head>
<body>
<div id="app">
<table border='1' width='80%' align="center">
<tr >
  <td v-for="(content, index) in headers">{{content.name}}</td>
</tr>
<tr >
  <td v-for="(colval, colindex) in columns"><input  v-if="colval.type==1" v-model="search" placeholder="Search">
  <select @change="onchange()" v-else = "colval.type==2" >
<option v-for="(statusval,statusIndex) in status" v-bind:value="statusIndex">{{statusval}}</option>

</select>
  </td>
</tr>
<tr v-for="(item, index) in contents" v-on:click="row(index)">

<td > 
<label v-if = "!editing[index]"> {{item.company_name}} </label>
 <input  v-if = "editing[index]" 
     @blur= "editTodo(index,'company_name',$event);" ref="input" :id="index+'_company_name'"  v-model="item.company_name">
</td>

<td > 
<label v-if = "!editing[index]"> {{item.city}} </label>
 <textarea  v-if = "editing[index]" 
      @blur= "editTodo(index,'city',$event);" ref="input" :id="index+'_city'"  v-model="item.city">
	  </textarea>
</td>
<td  > 
<label v-if = "!editing[index]"> {{item.turn_over}} </label>
 <input  v-if = "editing[index]" 
     @blur= "editTodo(index,'turn_over',$event);" ref="input" :id="index+'_turn_over'"  v-model="item.turn_over">
</td>
<td>
<label v-if = "!editing[index]"> {{status[item.status]}} </label>
<select @change="onchange()" v-if = "editing[index]" v-model="item.status">
<option v-for="(statusval,statusIndex) in status" v-bind:value="statusIndex">{{statusval}}</option>

</select>
</td>
<td>
<button @click="addAbove(index)"> add </button>
<span v-if = "!editing[index]"><button @click="edit(index)">Edit</button></span>
<span v-else="editing[index]"><button @click="save(index)">Save</button></span>
<button @click='deleteTableRow(index)' >Delete</button></td>
</tr>
</table>
<br>
<button @click='addTableRow()' >Add Last Row</button>
</div>
<script>
 new Vue({
  el: '#app',
   data () {
      return {
		  search:'1',
    headers:[{name:'company_name'},{name:'city'},{name:'turn_over'},{name:'status'},{name:'action'}],
	contents:[{ company_name: 'ABC Infotech', city: 'New Jersey', turn_over: 100,status:1,'edit':false },
        { company_name: 'Amazon Web Services', city: 'New York', turn_over: 400,status:2,'edit':false },
        { company_name: 'Digital Ocean', city: 'Washington', turn_over: 200,status:3,'edit':false },
		{ company_name: 'Digital Ocean1', city: 'Washington1', turn_over: 2020,status:4,'edit':false }
		],
	columns:{company_name:{validate:[''],filterid:'company_name',type:1},
	         city:{filterid:'city',type:1},turn_over:{filterid:'turn_over',type:1},status:{filterid:'status',type:2}
	}	
		,
	status:{0:'Select Status',1:"No Documents",2:"Documents Linked",3:"Documents Approved",4:"Partially Approved",5:"Not Linked"}
		,
    counter:4,
	newData:[],localData:[],editing:{}
   
   
   }
   
   },
   computed:{
	   custFilter:function(item){
		   console.log(item,'item');
		   
	   }
	   
   }
  ,
   methods:{
	   deleteTableRow: function (idx) { 
      this.counter--;
      this.contents.splice(idx, 1);      
      },
	  addTableRow: function () { 
      this.counter++;
      this.contents.unshift("Table Row "+this.counter);
      
   },
   row:function(indx){
	   if(!this.editing[indx]){
		this.editing={};
		this.$set(this.editing,indx,false);
	   }
   }
   ,
  editTodo: function(index,col,ev) {
	  
     
	 if(this.columns[col] && this.columns[col].validate)
	 {
		if(!this.columns[col].validate.includes(ev.target.value)) 
		{
			var str=index+'_'+col;
			this.$nextTick(function(){console.log('t',this.$refs.input);
				for(var i=0;i<this.$refs.input.length;i++)
				{
					if(this.$refs.input[i].id==str)
					{
						this.$refs.input[i].focus();
					}
				}
				
			});
		}
		//this.contents[index][col]=ev.target.value;
		 console.log('editTodo',col,ev.target.value,this.validationVal);
		// console.log('validation',this.validationVal,col);
	 //}else{
		 
	 }else{
		 
	 }
	 // this.contents[index][col]=ev.target.value;
	 
	 // if(!this.localData[index]){
	   // this.localData[index]={};
	 // }
		  
		 //  this.localData[index][col]=ev.target.value;
	  
	
	
	  
	   
	 // newData.push({col:this.editedValue});
		//save(this.contents[index]);
     //console.log(this.contents[index])
    },
	addAbove:function(index){
		 this.contents.splice(index, 0, {company_name:'Test',city:'',turn_over:'0',status:1})
	}
	,edit :function(indx){
			if(!this.editing[indx]){
				this.editing={};
				this.$set(this.editing,indx,true);
				this.$nextTick(function(){
					this.$refs.input[0].focus();
					console.log('edit',this.$refs.input,this.$refs,this.editing,indx);		
				});
			}
      },
      save : function(obj){
		  console.log(this.localData,'this.localData');
       // this.$set(obj, 'edit', false);
      }
	  
	  
	
   }
    
   
  });
	
</script>        
</body>
</html>