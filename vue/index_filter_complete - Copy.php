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
<tr>
<!--<td colspan =3><input v-model="search" placeholder="Search" ></td>-->
</tr>
<tr >
  <td v-for="(content, index) in headers">{{content.name}}</td>
</tr>
<tr >
<td><!--<input v-model="search" placeholder="Search" v-on:key="enter"></td>-->
  <td v-for="(colval, colindex) in columns">
  <input  v-if="colval.filtertype==1" :key="colval.filterid" v-model="colval.filterVal" placeholder="Search" v-on:keyup.enter="addFilter(colval,$event)">
  <!--<input  v-if="colval.filtertype==1" :key="colval.filterid" v-model="filterArr[colval.filterid]" placeholder="Search" v-on:keyup.enter="addFilter(colval.filterid,$event)">-->
  
  <select @change="addFilter(colval,$event)" v-else = "colval.filtertype==2" v-model="colval.filterVal">
<option v-for="(statusval,statusIndex) in status" v-bind:value="statusIndex">{{statusval}}</option>

</select>
  </td>
</tr>
<tr v-for="(item, index) in filtered " v-on:click="row(index)" :key='index'>
<td><label>{{index+1}}</label></td>
<td v-for="(cols, colkey) in columns"> 

<label v-if = "!editing[index] && cols.inputType==3"> {{status[item[colkey]]}} </label>
<label v-if = "!editing[index] && cols.inputType!=3"> {{item[colkey]}} </label>
 <input  v-if = "editing[index] && cols.inputType==1" 
     @blur= "editTodo(index,colkey,$event);" ref="input" :id="index+'_'+colkey" v-model="item[colkey]" class="">
	 
	 <textarea  v-if = "editing[index] && cols.inputType==2" 
      @blur= "editTodo(index,colkey,$event);" ref="input" :id="index+'_'+colkey"  v-model="item[colkey]">
	  </textarea>
	 <select @change="onchange()" v-if = "editing[index]  && cols.inputType==3" v-model="item.status">
     <option v-for="(statusval,statusIndex) in status" v-bind:value="statusIndex">{{statusval}}</option>
</td>
<td>
<button @click="addAbove(index)"> add </button>
<span v-if = "!editing[index]"><button @click="edit(index)">Edit</button></span>
<span v-else="editing[index]"><button @click="save(index)">Save</button></span>
<button @click='deleteTableRow(index)' >Delete</button></td>
</tr>
<tr><td v-if='noData==true' :colspan="headers.length" align="center">No Data</td></tr>
</table>
<br>
<button @click='addTableRow()' >Add Last Row</button>
</div>
<script>

 new Vue({
  el: '#app',
   data () {
      return {
		  filteredProperty:'',
		   query: '',
        activeFilters: {},
		  search:'',isFilter:false,noData:false,
    headers:[{name:'Sr No.'},{name:'company_name'},{name:'city'},{name:'turn_over'},{name:'status'},{name:'action'}],
	contents:[{ company_name: 'ABC Infotech', city: 'New Jersey', turn_over: 100,status:1,'edit':false },
        { company_name: 'Amazon Web Services', city: 'New York', turn_over: 400,status:2,'edit':false },
        { company_name: 'Digital Ocean', city: 'Washington', turn_over: 200,status:3,'edit':false },
		{ company_name: 'Digital Ocean1', city: 'Washington1', turn_over: 2020,status:4,'edit':false }
		],
	columns:{company_name:{validate:[''],filterid:'company_name',filtertype:1,inputType:1,filterVal:''},
	         city:{filterid:'city',filtertype:1,inputType:2,filterVal:''},turn_over:{filterid:'turn_over',filtertype:1,inputType:1,filterVal:''},status:{filterid:'status',filtertype:2,inputType:3,filterVal:0}
	}
		,
	status:{0:'Select Status',1:"No Documents",2:"Documents Linked",3:"Documents Approved",4:"Partially Approved",5:"Not Linked"}
		,
    counter:4,
	newData:[],localData:[],editing:{}
   
   
   }
   
   },computed:{
   filtered(){
	   var filtered=this.contents;
	   this.noData=false;
	   if(this.isFilter)
	   {
		  for(filterkey in this.activeFilters)
		  {
			  var filterValue=this.activeFilters[filterkey];
			 filtered= filtered.filter(function(record){
				 return new RegExp(filterValue, 'i').test(record[filterkey])
				 
			  });
		  }
		  if(filtered.length==0)
		  {
			  this.noData=true;
		  }
	   }
	   return filtered;
	   
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
      this.contents.push({company_name:'Test',city:'',turn_over:'0',status:1});
      
   }
   ,
   addFilter(colval,ev){
	 this.editing={};
	if(colval.filtertype==2 && ev.target.value==0)
	{
		this.isFilter=false;
	}else{
	this.activeFilters[colval.filterid]=ev.target.value;
	this.isFilter=(this.activeFilters[colval.filterid])?true:false;
	}
   }
   ,
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
		if(this.columns[col].validate.includes(ev.target.value)) 
		{
			var str=index+'_'+col;
			this.$nextTick(function(){console.log('t',this.$refs.input);
				for(var i=0;i<this.$refs.input.length;i++)
				{
					if(this.$refs.input[i].id==str)
					{console.log('in',str,i);
						alert('can not empty');
						this.$refs.input[i].focus();
						return false;
						//return this.$refs.input[i].focus();
						
					}
				}
				
			});
		}
		//this.contents[index][col]=ev.target.value;
		
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
		console.log(this.editing,indx,'edit')
			if(!this.editing[indx]){
				this.editing={};
				this.$set(this.editing,indx,true);
				this.$nextTick(function(){
					this.$refs.input[0].focus();
					//console.log('edit',this.$refs.input,this.$refs,this.editing,indx);		
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