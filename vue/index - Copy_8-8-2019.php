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
</style>
<script src="vue.js"></script>
</head>
<body>
<div id="app">
<table border='1' width='80%' align="center">
<tr >
  <td v-for="(content, index) in headers">{{content.name}}</td>
</tr>
<tr v-for="(item, index) in contents">



<td > 
<label v-if = "!editing[index]"> {{item.company_name}} </label>
 <input  v-if = "editing[index]" 
     @blur= "hideInput(index);" ref="input" :id="index+'_company_name'"  :value="item.company_name">
</td>

<td > 
<label v-show = "!editing[index]"> {{item.city}} </label>
 <input  v-if = "editing[index]" 
      @blur= "hideInput(index);" ref="input" :id="index+'_city'"  :value="item.city">
</td>

<!--
<td > 
<label v-if = "editedTodo != index" @click="labclick(index+'_company_name')"> {{item.company_name}} </label>
 <input  v-if = "editedTodo == index" v-model = "item.company_name"
     @blur= "editTodo(index,'company_name',$event); $emit('update')" :ref="index+'_company_name'" :id="index+'_company_name'" >
</td>
<td > 
<label v-show = "editedTodo != index"> {{item.city}} </label>
 <input  v-if = "editedTodo == index" v-model = "item.city" 
      @blur= "editTodo(index,'city',$event); $emit('update')">
</td>
<td  > 
<label v-show = "editedTodo != index"> {{item.turn_over}} </label>
 <input  v-if = "editedTodo == index" v-model = "item.turn_over"
     @blur= "editTodo(index,'turn_over',$event); $emit('update')">
</td>-->

<!--
<td v-on:dblclick="editbox(index+'_company_name')" v-on:click="t1(index+'_company_name')"> 
<label v-show = "editedTodo != index+'_company_name'"> {{item.company_name}} </label>
 <input  v-if = "editedTodo == index+'_company_name'" v-model = "editedValue"
      v-on:blur= "editTodo(index,'company_name'); $emit('update')">
</td>
<td v-on:dblclick="editbox(index+'_city')" v-on:click="t1(index+'_city')"> 
<label v-show = "editedTodo != index+'_city'"> {{item.city}} </label>
 <input  v-if = "editedTodo == index+'_city'" v-model = "editedValue" 
      v-on:blur= "editTodo(index,'city'); $emit('update')">
</td>
<td v-on:dblclick="editbox(index+'_turn_over')" v-on:click="t1(index+'_turn_over')"> 
<label v-show = "editedTodo != index+'_turn_over'"> {{item.turn_over}} </label>
 <input  v-if = "editedTodo == index+'_turn_over'" v-model = "editedValue"
      v-on:blur= "editTodo(index,'turn_over'); $emit('update')">
</td>
-->


<!--
<td v-on:dblclick="editedTodo=index;editedValue=item.company_name"> 
<label v-show = "editedTodo != index"> {{item.company_name}} </label>
 <input  v-if = "editedTodo == index" v-model = "editedValue" ref="index"
      v-on:blur= "editTodo(index); $emit('update')">
</td>


<td v-on:dblclick="dbclick1(item,this)"><span v-if="item.edit" ><input v-model="item.company_name" /></span>
<span v-else>{{item.company_name}}</span>
</td>
<td><span v-if="!editing">{{item.company_name}}</span><div v-else><input v-model="item.company_name"></div></td>
<td><div v-show = "item.edit == false">
        <label @dblclick = "item.edit = true"> {{item.company_name}} </label>
      </div>
	   <input v-show = "item.edit == true" v-model = "item.company_name"
      v-on:blur= "item.edit=false; $emit('update')"
      @keyup.enter = "item.edit=false; $emit('update')">
	  </td>
<td >{{item.company_name}}</td>
<td >{{item.city}}</td>
<td >{{item.turn_over}}</td>-->
<td><button @click="addAbove(index)"> add </button><span v-if = "!editing[index]"><button @click="edit(index)">Edit</button></span>
<span v-if="editing[index]"><button @click="save(item)">Save</button></span>
<!--<span v-else="editing[index]"><button disabled="disabled">Save</button></span>-->
<button @click='deleteTableRow(index)' >Delete</button></td>
</tr>
</table>
<br>
<button @click='addTableRow()' >Add New Row</button>
</div>
<script>
Vue.directive('focus',{inserted:function(el){el.focus();console.log('focus',el)}})
 new Vue({
  el: '#app',
   data () {
      return {
    headers:[{name:'company_name'},{name:'city'},{name:'turn_over'},{name:'action'}],
	contents:[{ company_name: 'ABC Infotech', city: 'New Jersey', turn_over: 100,'edit':false },
        { company_name: 'Amazon Web Services', city: 'New York', turn_over: 400,'edit':false },
        { company_name: 'Digital Ocean', city: 'Washington', turn_over: 200,'edit':false },
		{ company_name: 'Digital Ocean1', city: 'Washington1', turn_over: 2020,'edit':false }
		],
    counter:4,
	editedTodo: null,tempValue: null,  editedValue:null,isSave:null,newData:[],localData:[],editing:{}
   , validationVal:{
	  
	  company_name:['']
       }
   
   }
   
   },
  
   methods:{
	   deleteTableRow: function (idx) { 
      this.counter--;
      this.contents.splice(idx, 1);      
      },
	  addTableRow: function () { 
      this.counter++;
      this.contents.unshift("Table Row "+this.counter);
      
   },showInput(indx){
	   this.$set(this.editing,indx,true);
	    this.$nextTick(function(){
			
		  console.log('labclick',this.$refs.input,this.$refs,this.editing,indx);
		 // this.$refs.input[1].focus()
	  });
   },hideInput(indx){
	   
	   console.log(this.editing,'this.editing',indx,this.$refs.input);
	   if(!this.editing[indx]){
	   this.editing[indx]=false;
	   }
   }
   
   ,
   labclick(el){
		 this.$nextTick(function(){
		 // console.log('labclick',el,this.$refs['0_company_name'][0],this.$refs,this);
	  });
	  
   }
   ,
  editTodo: function(index,col,ev) {
	  
     
	// if(this.validationVal[col].includes(ev.target.value)) 
	 {
		 console.log('validation',this.validationVal,col);
	 }
	  this.contents[index][col]=ev.target.value;
	 
	  if(!this.localData[index]){
	    this.localData[index]={};
	  }
		  
		   this.localData[index][col]=ev.target.value;
	  
	
	
	  
	    console.log(this.editedValue,'editTodo',col,this.localData,ev.target.value);
	 // newData.push({col:this.editedValue});
		//save(this.contents[index]);
     //console.log(this.contents[index])
    },
	enableEditing: function(){
     // this.tempValue = this.value;
     // this.editing = true;
    },saveEdit: function(ev, number){
		
		//this.toggleEdit(ev, number);
		//console.log(ev, number,'saveEdit')
		
	},
	addAbove:function(index){
		 this.contents.splice(index, 0, {company_name:'1',city:'1',turn_over:'1','edit':true})
		// this.isSave=index;
		// this.editedTodo=index;
	},
		toggleEdit: function(ev, number){
			//console.log('toggleEdit',ev, number);
			//number.$set('edit', !number.edit);
			//Vue.set(number,'edit', !number.edit);
			// Focus input field
			if(number.edit){
				Vue.nextTick(function() {
					//console.log(this.focus());
					//ev.$$.input.focus();
				})   
			}
		},edit :function(indx){
			
			if(!this.editing[indx]){
				this.editing={};
				this.$set(this.editing,indx,true);
				this.$nextTick(function(){
					this.$refs.input[0].focus();
					console.log('edit',this.$refs.input,this.$refs,this.editing,indx);
					
				});
			}
				//this.editing[indx]=false;
			 
       // this.$set(obj, 'edit', true);
      },
      save : function(obj){
		   this.editedTodo=null;
		  this.isSave=null;
		  console.log(this.localData,'this.localData');
       // this.$set(obj, 'edit', false);
      },
	  dbclick1: function(obj,el){
		 // console.log(el.$refs,'dbclick1');
		// this.$set(obj, 'edit', true);
	  },db2:function(indx){
		 if(indx!=this.editedTodo){
		 this.editedTodo=null;
		  this.isSave=null;
		 }
		 // this.$set(obj, 'edit', false);
	  },
	  db:function(indx){
		  if(indx!=this.editedTodo){
		  this.editedTodo=indx;
		   this.isSave=indx;
		    this.contents[indx]['edit']=true;
		
		  }
		 // this.$set(obj, 'edit', false);
	  },editbox:function(indx){
		  
		 /*  if(indx!=this.editedTodo){
		  this.editedTodo=indx;
		  this.editedValue=this.contents[indx]['company_name'];
		  this.contents[indx]['edit']=true;
		   }*/
		   var splitVal=indx.replace(/_/,' ').split(' ');
		      if(indx!=this.editedTodo){
		  this.editedTodo=splitVal[0];
		  this.editedValue=this.contents[splitVal[0]][splitVal[1]];
		  this.contents[splitVal[0]]['edit']=true;
		  this.isSave=[splitVal[0]];
		  }
		   
		
		//this.$nextTick(this.$refs.sea.$el.focus());
	  },t1:function(indx){
		  console.log('in',indx, this.editedTodo);
		 if(indx!=this.editedTodo){
		 this.editedTodo=null;
		  this.isSave=null;
		 }
	  }
	  
	
   }
    
   
  });
	
</script>        
</body>
</html>