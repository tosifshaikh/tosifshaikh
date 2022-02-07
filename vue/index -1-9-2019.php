<!DOCTYPE html>
<html>
<head>
<style>
.dirty {
  border-color: #red;
  background: #F00;
  border:solid red;
}

.dirty:focus {
  outline-color: #F00;
}

.error {
  border-color: red;
  background: #FDD;
}

.error:focus {
  outline-color: #F99;
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
.labelClass{
	margin:0;
	display:inline-flex;;
}

</style>
<script src="vue.js"></script>
</head>
<body>
<div id="app">
<form method='post' novalidate="true">
<table border='1' width='80%' align="center" id="itemTable"><tbody>
<tr><td :colspan="headers.length"><button @click='addTableRow()'   type='button'>Add Last Row</button><button @click='saveAll()' type='button'>Save All</button><button @click='reselAll()'   type='button'>Reset All</button></td></tr>
<tr >
  <td v-for="(content, index) in headers">{{content.name}}</td>
</tr>
<tr >
<td><!--<input v-model="search" placeholder="Search" v-on:key="enter"></td>-->
  <td v-for="(colval, colindex) in columns">
  <input  v-if="colval.filtertype==1" :key="colval.filterid" v-model.lazy.trim="colval.filterVal" placeholder="Search" v-on:keyup.enter="addFilter(colval,$event)"  >
  
  <!--<input  v-if="colval.filtertype==1" :key="colval.filterid" v-model="filterArr[colval.filterid]" placeholder="Search" v-on:keyup.enter="addFilter(colval.filterid,$event)">-->
  
  <select @change="addFilter(colval,$event)" v-else = "colval.filtertype==2" v-model.lazy.trim="colval.filterVal">
<option v-for="(statusval,statusIndex) in status" v-bind:value="statusIndex">{{statusval}}</option>

</select>
  </td>
</tr>
<tr v-for="(item, index) in filtered " :key='index'  v-on:click='tes88(index,$event,contents)' >
<td v-on:click="resetCheck(index)"><label>{{parseInt(index)+1}}</label></td>
<td v-for="(cols, colkey) in columns"> 

<label v-if = "!copyObject[index].edit && cols.inputType==3"> {{status[item[colkey]]}} </label>
<label v-if = "!copyObject[index].edit && cols.inputType!=3"> {{item[colkey]}} </label>
 <input  v-if = "copyObject[index].edit && cols.inputType==1" 
     @blur= "editTodo(index,colkey,$event);" :ref='index' :key="index+'_'+colkey" v-model.lazy.trim="item[colkey]" v-bind:class="{dirty:(validateobj!=null && validateobj[index] && validateobj[index].includes(colkey)==true)?true:false}" >
	
	 <textarea  v-if = "copyObject[index].edit && cols.inputType==2" 
      @blur= "editTodo(index,colkey,$event);" :ref='index' :key="index+'_'+colkey"  v-model.lazy.trim="item[colkey]"  v-bind:class="{dirty:(validateobj!=null && validateobj[index] && validateobj[index].includes(colkey)==true)?true:false}">
	  </textarea>
	 <select @change="editTodo(index,colkey,$event);" v-if = "copyObject[index].edit  && cols.inputType==3" v-model.trim="item.status" :ref='index'  v-bind:class="{dirty:(validateobj!=null && validateobj[index] && validateobj[index].includes(colkey)==true)?true:false}">
     <option v-for="(statusval,statusIndex) in status" v-bind:value="statusIndex">{{statusval}}</option>
	</select><br />
	
<span v-show="validateobj!=null && validateobj[index] && validateobj[index].includes(colkey)==true" class="labelClass"> This field is required. </span>	 
	
</td>
<td>
<button @click="addAbove(index)" type='button'  :disabled="(copyObject[index].edit)?true:false"> add </button>
<span v-if = "!copyObject[index].edit" ><button @click="edit(index)" type='button'>Edit</button></span>
<span v-else="copyObject[index].edit"><button @click="save(index)" type='button' :disabled="(validateobj!=null && validateobj[index])?true:false">Save</button></span>
<span v-if="copyObject[index].edit"><button @click="resetCheck(index)" type='button'>Reset</button></span>
<button @click='deleteTableRow(index)' type='button'>Delete</button>
</td>
</tr>
<tr><td v-if='noData==true' :colspan="headers.length" align="center">No Data</td></tr>
<tr><td :colspan="headers.length"><button @click='addTableRow()'   type='button'>Add Last Row</button><button @click='saveAll()' type='button'>Save All</button><button @click='reselAll()'   type='button'>Reset All</button></td></tr></tbody>
</table>
<br>

</form>
{{contents[0]}}
{{copyObject[0]}}
</div>
<script src="jquery.js"></script>
 <script src="jquery-ui.js"></script>
<script>


</script>
<script>
//$(document).ready(function () {
	
	 $(function () {
        $("#itemTable tbody").sortable({
            update: function (event, ui) {
                mainVue.updateOrders();
            }
        });
    });
//});	
var mainVue=Vue.extend({
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
	         city:{filterid:'city',filtertype:1,inputType:2,filterVal:''},turn_over:{filterid:'turn_over',filtertype:1,inputType:1,filterVal:'',validate:['']},status:{filterid:'status',filtertype:2,inputType:3,filterVal:0,validate:['0']}
	}
		,
	status:{0:'Select Status',1:"No Documents",2:"Documents Linked",3:"Documents Approved",4:"Partially Approved",5:"Not Linked"}
		,
    counter:4,dragging:-1,
	newData:[],localData:[],editing:{},error:'',clicks:0,result:'',delay:200,rows:[],validateobj:[],isSave:0
   ,resetArr:[],copyObject:[],items:[]
   
   }
   
   }
	
});
 new mainVue({
  el: '#app'
   ,mounted(){
	  console.log('mounted',this.$options.data().contents);
	     this.copyObject=Object.assign([],this.$options.data().contents);
   }
   
   ,computed:{
   filtered(){
	   var filtered=this.copyObject;
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
	    this.activeFilters={};
	   return filtered;
	   
   }
	   
   }
   ,
   methods:{
	   deleteTableRow: function (idx) { 
      this.counter--;
      this.copyObject.splice(idx, 1);      
      },
	  addTableRow: function () { 
      this.counter++;
       this.copyObject.push({company_name:'',city:'',turn_over:'0',status:1});
      
   },
    updateOrders: function () {
                var newOrder = $('#itemTable tbody').sortable("toArray");
                $("#itemTable tbody").sortable("cancel");
                var newItemList = [];
                for (var i = 0; i < newOrder.length; i++) {
                    if (this.items[parseInt(newOrder[i].replace("item-", ''))] != null) {
                        newItemList.push({
                            "order": i,
                            "name": this.items[parseInt(newOrder[i].replace("item-", ''))].name,
                        });
                    }
                }
                this.items = newItemList;
            }
   ,
   resetCheck(index){
	 console.log('resetCheck');
	 //this.copyObject=Object.assign({},this.copyObject);
	 if( this.copyObject[index])
	 {
		 this.$delete(  this.validateobj,index);
		 Object.assign(this.copyObject[index],this.$options.data().contents[index]);
	 }
   }
   ,
    dragStart(indx,ev){
	 //  console.log(ev,indx,'dragEnter');
   }
   ,
   dragEnter(ev){
	 //  console.log(ev,'dragEnter');
   }
   ,
   dragLeave(ev){
	  // console.log(ev,'dragLeave');
   }
   ,
   dragFinish(index,ev){
	 // console.log(ev,index,'dragFinish');  
   },
   dragEnd(ev){
	  //  console.log(ev,'dragEnd');  
   }
   ,reselAll:function(){
	 for(var ii in this.editing)
	 {
		   this.$set(  this.copyObject[ii],'edit',false);
		   this.$delete(  this.validateobj,ii);
		 Object.assign(this.copyObject[ii],this.$options.data().contents[ii]);
	 }

   }
   
   
   ,
   tes88:function(indx,ev,obj){
	   this.clicks++;
	   if(this.clicks==1)
	   {
		   var self=this;
		   console.log('mm',this.timer, this.clicks,this.result,ev.type);
		   this.timer=setTimeout(function(){
			self.result='click';  
			self.clicks=0	
		   },this.delay);
	   }else{
		   
		   clearTimeout(this.timer);
		   this.result='dbclick';
		   this.clicks=0;
		   
		    this.$set(this.editing,indx,true); 
			 this.$set(  this.copyObject[indx],'edit',true); 
			console.log('mm11',this.timer, this.clicks,this.result);
	   }
	  
   }
   ,
   addFilter(colval,ev){
	 if(ev.keyCode==13){
	 this.editing={};
	if(colval.filtertype==2 && ev.target.value==0)
	{
		this.isFilter=false;
	}else{
	this.activeFilters[colval.filterid]=ev.target.value;
	this.isFilter=(this.activeFilters[colval.filterid])?true:false;
	}
	 }
	console.log('addFilter',this.activeFilters,this.isFilter)
   }
   ,
   row:function(indx){
	   if(!this.editing[indx]){
		//this.editing={};
		///this.$set(this.editing,indx,false);
	   }
	   console.log('single click');
   }
   ,
  editTodo: function(index,col,ev) {
  
  console.log('editTodo',ev.target,this.copyObject);
     var str=index+'_'+col;
	if(this.columns[col] && this.columns[col].validate)
	 { console.log('in666',col,ev.target.value,this.columns[col].validate.includes(ev.target.value))
		if(this.columns[col].validate.includes(ev.target.value)) 
		{ 
				// this.$set(this.validateobj,str,false); 
				if(!this.validateobj[index])
				{
					this.validateobj[index]=[];
				}
				this.validateobj[index].push(col);
				// this.validateobj[index][0].push(str);
				
			
				
			
			 // this.$set(this.validateobj,index,{colVal:str}); 
			
			// this.$nextTick(function(){
				//for(var i=0;i<this.$refs[index].length;i++)
				{
					//if(this.$refs[index][i].id==str)
					{//console.log('in',str,i,this.$refs.input,this.$refs['input'+0]);
						//alert('can not empty');
						//this.errors.push('Company name can not be empty.');
						//console.log('t666',this.$refs[index][i]  );
						//this.$refs[index][i].focus();
						//this.placeholder="Company";
						//this.$refs[index][0].autofocus="autofocus";
						//this.error=true;
					//	break;
						//return this.$refs.input[i].focus();
						
					//}else{
						
					}
				}
				
			//});
		}else{
		 console.log(this.validateobj,'before');
		 
		if(this.validateobj && this.validateobj[index] && this.validateobj[index].includes(col)==true)
		{
			// delete this.validateobj[index][str];
			 var delIndex=this.validateobj[index].indexOf(col);
			 this.validateobj[index].splice(delIndex, 1);
		 }
		 if(this.validateobj!=null && this.validateobj[index] && this.validateobj[index].length==0)
		 {
			  this.validateobj.splice(index, 1);
		 }
					 
		 //if(this.validateobj[index])
		 {
			// this.$delete(this.validateobj,str); 
		 }//console.log(this.validateobj,'after');
	 }
		//this.contents[index][col]=ev.target.value;
		
		// console.log('validation',this.validationVal,col);
	 //}else{
		//console.log(this.errors); 
	 }
	 
	
	 
	 // this.contents[index][col]=ev.target.value;
	 
	 // if(!this.localData[index]){
	   // this.localData[index]={};
	 // }
		  
		 //  this.localData[index][col]=ev.target.value;
	  
	console.log(this.validateobj,'after',this.resetArr);
	
	 
	   
	 // newData.push({col:this.editedValue});
		//save(this.contents[index]);
     //console.log(this.contents[index])
    },
	addAbove:function(index){
		 this.copyObject.splice(index, 0, {company_name:'',city:'',turn_over:'0',status:1,edit:false});
		 this.$set(this.editing,index,true); 
		 this.$nextTick(function(){
					//this.$refs[index][0].focus();
					//console.log('edit',this.$refs.input,this.$refs,this.editing,indx);		
				});
	}
	,edit :function(indx){
		
		this.$set(this.copyObject[indx], 'edit', true);
		console.log(this.editing,'this.editing',this.copyObject);
		//this.contents[indx].edit=true;
		//console.log(this.editing,indx,'edit',this.columns)
			//if(!this.editing[indx]){
				//this.editing={};
				//this.$set(this.editing,indx,true);
				this.$nextTick(function(){
					console.log(this.$refs,'rr');
				//	this.$refs[indx][0].focus();
					//console.log('edit',this.$refs.input,this.$refs,this.editing,indx);		
				});
			//}
      },
      save : function(obj){
		 // this.isSave=1;
		 

		 console.log(this.validateobj,'validateobj');
		  for(var d in this.validateobj)
		  {
			
			
		  }
		  console.log(this.localData,'this.localData');
		  console.log(this.validateobj,'validateobj');
       // this.$set(obj, 'edit', false);
      }
	  
	  
	
   }
    
   
  });

</script>        
</body>
</html>