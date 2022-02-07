<!--
      1)   //this.contents[index]=this.$options.data().contents[index];
             Object.assign(this.contents[index],this.$options.data().contents[index]);
             //use assign if you assign directly than getters and setters will not reflect in object
        2) v-model - is two way binding so if you use it, change will be reflected in object.
        3) v-value - is one way yo need to mannually do the things
        4) :key - use this otheriwise dom will be refreshed very time on typing or changing somthing
        -->

<!DOCTYPE html>
<html>
<head>
<link href="css\bootstrap.min.css" rel="stylesheet" type="text/css">
<style>
.dirty {
  border-color: #red;
  //background: #F00;
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
.dragClass{
	outline:solid #f00;
	background:#FFCC99;
}
.moveClass{
	background-image: url('move.gif'); 
	cursor:move;
	height:40px;!important;
	background-repeat: no-repeat; 
	background-position:center;
	width:30px;
}

</style>
<script src="vue.js"></script>
</head>
<body>
<div id="app" class="table-responsive">
<form method='post' id='frm'>

<table border='1' width='50%' align="center" id="itemTable" class="table table-striped table-hover table-sm"><tbody>
<tr><td :colspan="headers.length"><button @click='addTableRow()'   type='button' >Add Last Row</button><button @click='saveAll()' type='button'>Save All</button><button @click='reselAll()'   type='button'>Reset All</button></td></tr>
<tr >
  <th v-for="(content, index) in headers" >{{content.name}}</th>
</tr>
<tr class="filters" >
<td></td><td></td>
  <td v-for="(colval, colindex) in columns">
  <input  v-if="colval.filtertype==1" :key="colval.filterid" v-model.lazy.trim="colval.filterVal" placeholder="Search" v-on:keyup.enter="addFilter(colval,$event)" class="form-control" > 
  <select @change="addFilter(colval,$event)" v-else = "colval.filtertype==2" v-model.lazy.trim="colval.filterVal" class="form-control">
<option v-for="(statusval,statusIndex) in status" v-bind:value="statusIndex">{{statusval}}</option>

</select>
  </td>
  <td></td>
</tr>
<tr v-for="(item, index) in filtered " :key='index'   v-on:dblclick='tes88(index,$event)' 	draggable="true"   @mouseup="mouseUp(index,item.id)"  @mouseover="mouseOver(index,item.id)" @mouseout="mouseOut(index,item.id)"  v-bind:style="(dragStyleObj!=null && dragStyleObj[index]!=null)?dragStyleObj[index]:'' "  scope="row">

<td v-on:click="resetCheck(index,item.id)"  class="text-center"><label >{{parseInt(index)+1}}</label></td>
<td  class='moveClass' @mousedown="mouseDown(index,item.id)"></td>
<td v-for="(cols, colkey) in columns"> 

<label v-if = "!copyObject[index].edit && cols.inputType==3"> {{status[item[colkey]]}} </label>
<label v-if = "!copyObject[index].edit && cols.inputType!=3"> {{item[colkey]}} </label>
 <input  v-if = "copyObject[index].edit && cols.inputType==1" 
    :ref='index' :key="index" :id="index+'_'+colkey"  @blur= "editTodo(index,colkey,$event);" v-model.lazy.trim="item[colkey]" v-bind:class="{dirty:(validateobj!=null && validateobj[index] && validateobj[index].includes(colkey)==true)?true:false}" >
	
	 <textarea  v-if = "copyObject[index].edit && cols.inputType==2" :ref='index'
      @blur= "editTodo(index,colkey,$event);" :key='index' :id="index+'_'+colkey"  v-model.lazy.trim="item[colkey]"  v-bind:class="{dirty:(validateobj!=null && validateobj[index] && validateobj[index].includes(colkey)==true)?true:false}">
	  </textarea>
	 <select @change="editTodo(index,colkey,$event);" :key='index' :id="index+'_'+colkey" v-if = "copyObject[index].edit  && cols.inputType==3" v-model.trim="item.status" :ref='index'  v-bind:class="{dirty:(validateobj!=null && validateobj[index] && validateobj[index].includes(colkey)==true)?true:false}">
     <option v-for="(statusval,statusIndex) in status" v-bind:value="statusIndex">{{statusval}}</option>
	</select><br />
	
<span v-show="validateobj!=null && validateobj[index] && validateobj[index].includes(colkey)==true" class="invalid"> This field is required. </span>	 
	
</td>
<td>
<button @click="addAbove(index)" type='button'  :disabled="(copyObject[index].edit)?true:false"> add </button>
<span v-if = "!copyObject[index].edit" ><button @click="edit(item.id,index)" type='button'>Edit</button></span>
<span v-else="copyObject[index].edit"><button @click="save()" type='button' >Save</button></span><!--:disabled="(validateobj!=null && validateobj[index])?true:false" -->
<span v-if="copyObject[index].edit"><button @click="resetCheck(index,item.id)" type='button'>Cancel</button></span>
<button @click='deleteTableRow(index)' type='button'>Delete</button>
</td>
</tr>
<tr><td v-if='noData==true' :colspan="headers.length" align="center">No Data</td></tr>
<tr><td :colspan="headers.length"><button @click='addTableRow()'   type='button'>Add Last Row</button><button @click='save()' type='button'>Save All</button><button @click='reselAll()'   type='button'>Reset All</button></td></tr></tbody>
</table>
<br>

</form>
{{contents}}
{{copyObject[0]}}

</div>
<script src="jquery.js"></script>
 <script src="jquery-ui.js"></script>
<script>


</script>
<script>
function initialState()
{
	return{
		headers:[{name:'Sr No.'},{name:''},{name:'company_name'},{name:'city'},{name:'turn_over'},{name:'status'},{name:'action'}],
		contents:[
	{ company_name: 'Amazon Web Services', city: 'New York', turn_over: 400,status:2,'edit':false,rec_id:1,id:200 },
	{ company_name: 'ABC Infotech', city: 'New Jersey', turn_over: 100,status:1,'edit':false,rec_id:3,id:100 },
	{ company_name: 'Digital Ocean', city: 'Washington', turn_over: 200,status:3,'edit':false,rec_id:4,id:500 },
	{ company_name: 'Digital Ocean1', city: 'Washington1', turn_over: 2020,status:4,'edit':false,rec_id:2,id:22 } 
		],
		columns:{company_name:{validate:[''],filterid:'company_name',filtertype:1,inputType:1,filterVal:''},
	         city:{filterid:'city',filtertype:1,inputType:2,filterVal:''},
			 turn_over:{filterid:'turn_over',filtertype:1,inputType:1,filterVal:'',validate:['']},
			 status:{filterid:'status',filtertype:2,inputType:3,filterVal:0,validate:['0']}
	       },
		status:{0:'Select Status',1:"No Documents",2:"Documents Linked",3:"Documents Approved",4:"Partially Approved",5:"Not Linked"},
		   activeFilters: {},
		   isFilter:false,noData:false,
		   counter:4,
	localData:[],editing:{},validateobj:[]
   ,copyObject:[],dragStyleObj:{},downIndex:-2,originalobj:[],downID:''
	}
}
/*	
var mainVue=Vue.extend({
	data () {
      return initialState();
   
   }
	
});*/
 new Vue({
  el: '#app',
   data () {
      return initialState();
   },
   mounted(){
	   console.log('mounted');
   }
   ,watch:{
	   deep:true,
	   copyObject(o,p){
			//console.log('watch',o,p);
		}

   },created(){
	   var t=[];
	   t[parseInt(88)]=6;
	   t[parseInt(5)]=4;
	   t[parseInt(66)]=7;
	console.log('created',this.$data.contents,t,t.length)
	this.copyObject=Object.assign([],this.contents);
	this.originalobj=initialState().contents;
	console.log('createdee	',this.copyObject,this.originalobj)
   }
   
   ,computed:{
   filtered(){
	   var filtered=this.copyObject;
	   filtered=this.sortData(filtered);
	   this.originalobj=this.sortData(this.originalobj);
	   console.log('filtered',filtered,this.isFilter,this.activeFilters);
	   this.noData=false;
	   if(this.isFilter)
	   {
		  for(filterkey in this.activeFilters)
		  {
			  var filterValue=this.activeFilters[filterkey];
			 filtered= filtered.filter(function(record){
				 console.log(new RegExp(filterValue, 'i').test(record[filterkey]),filterValue,record[filterkey]);
				 return new RegExp(filterValue, 'i').test(record[filterkey])
				 
			  });
		  }
		  
		 
	   }
	   if(filtered.length==0)
		{
			this.noData=true;
		}
	   // this.activeFilters={};
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

      this.copyObject.push({company_name:'',city:'',turn_over:'0',status:1,edit:false});
      
   },sortData(obj){
	obj.sort((a, b) => {
	  if (a.rec_id < b.rec_id) return -1
	  if (a.rec_id > b.rec_id) return 1
	  return 0
	});
	return obj;
   }
    ,
   resetCheck(index,id){
	 console.log('resetCheck',initialState(),this.editing);
	 that=this;
		this.copyObject.filter(function(record,k){
		console.log(record.id,id,index,k,'yyyy')
			if(record.id && record.id==id && index==k)
			{
				if(that.validateobj[k]){
				that.$delete(that.validateobj,k);
				}	console.log('ttgg	',that.copyObject,k,that.originalobj[k])
				that.$set(that.copyObject,k,that.originalobj[k]);
				that.$delete(that.editing,k);
				return true;
			}else{
				that.$set(that.copyObject[index],'edit',false);
				return true;
			}
		});
	 
   }
   ,
    mouseDown(ind,id){
	   console.log('mouseDown',ind,this.dragStyleObj);
	  this.$set(this.dragStyleObj,ind,{
                    cursor:'move'
	   });
	   this.downIndex=ind;
	   this.downID=id;
   }
   ,
   mouseOver(ind,id)
   {
	   if(this.downIndex!=-2 && this.downIndex!=ind )
	   {
		this.$set(this.dragStyleObj,ind,{
			outline:'1px solid #f00',  
		});
	   }
   }
   ,
   mouseOut(ind,id){
	    if(this.downIndex!=-2 && this.downIndex!=ind )
		{
			this.$set(this.dragStyleObj,ind,{
			outline:'1px solid #000',
		   });
	   }
   },
   mouseUp(ind,id){
                if(this.downIndex!=ind && this.downIndex!=-2)
                {
                var fromId= this.downID,ToID=id,fromOrder=null,ToOrder=null;
				if(this.copyObject[this.downIndex].id==fromId){
				 var fromOrder=parseInt(this.copyObject[this.downIndex].rec_id);
				}
				if(this.copyObject[ind].id==ToID){
				ToOrder=parseInt(this.copyObject[ind].rec_id);
				}
				if(fromOrder!=null){
				this.$set(this.copyObject[ind],'rec_id', (fromOrder));
				this.$set(this.originalobj[ind],'rec_id',fromOrder);
				} 	
				if(ToOrder!=null){
				this.$set(this.copyObject[this.downIndex],'rec_id',(ToOrder));
				this.$set(this.originalobj[this.downIndex],'rec_id',ToOrder);
				}
				console.log('mouseUp',ind,this.downIndex+' this.downIndex',(fromOrder),ToOrder,this.copyObject,this.originalobj);
				this.dragStyleObj={};
				this.downIndex=-2;
				this.downID=null;

                }
		 
   }
   ,reselAll:function(){
	   console.log('reselAll');
	 for(var ii in this.editing)
	 {
		   this.$set(  this.copyObject[ii],'edit',false);
		   this.$delete(  this.validateobj,ii);
		 Object.assign(this.copyObject[ii],this.$options.data().contents[ii]);
	 }

   }
   
   
   ,
   tes88:function(indx,ev){
	    this.$set(this.editing,indx,true); 
		this.$set(this.copyObject[indx],'edit',true); 
			console.log('mm11');
	  
   }
   ,
   addFilter(colval,ev)
   {console.log(ev,'$event',ev.target.value);
    this.resetfields();
	if(colval.filtertype==2 && ev.target.value==0)
	{
		this.isFilter=false;
	}else 
	{	
		this.activeFilters[colval.filterid]=ev.target.value;
		this.isFilter=(this.activeFilters[colval.filterid])?true:false;
		this.$set(this.columns[colval.filterid],'filterVal',ev.target.value);
	} 
	console.log('addFilter',this.activeFilters,this.isFilter,this.columns)
   }
   ,
  editTodo: function(index,col,ev) {
  
  console.log('editTodo',ev.target,this.copyObject);
     var str=index+'_'+col;
	if(this.columns[col] && this.columns[col].validate)
	 { console.log('in666',col,ev.target.value,this.columns[col].validate.includes(ev.target.value))
		if(this.columns[col].validate.includes(ev.target.value)) 
		{ 
				if(!this.validateobj[index])
				{
					this.validateobj[index]=[];
				}
				this.validateobj[index].push(col);
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
		}
	 }
	console.log(this.validateobj,'after');
    },
	addAbove:function(index){
		console.log(index,'addAbove');
		 this.copyObject.splice(index, 0, {company_name:'',city:'',turn_over:'0',status:1,edit:false});
		 this.$set(this.editing,index,true); 
		 this.$nextTick(function(){
					//this.$refs[index][0].focus();
					//console.log('edit',this.$refs.input,this.$refs,this.editing,indx);		
				});
	}
	,edit :function(id,indx){
		this.$set(this.copyObject[indx], 'edit', true);
		this.$set(this.editing, id, true);
		console.log('this.editing');
				this.$nextTick(function(){
					console.log(this.$refs,'rr');
		
				});
			//}
      },
      save : function(obj){
		 var localData={},expData=[];
		for(var i in this.$refs)
		{
			localData={};
			for(var ii in this.$refs[i])
			{
				var expVal=this.$refs[i][ii].id.split('_');
				localData[expVal[1]]=this.$refs[i][ii].value;
				console.log(this.$refs,this.$refs[i][ii].value,'yyy',this.$refs[i][ii].id,localData);
			}
			expData.push(localData);
		}
		  console.log(expData,'save');
       
      }
	  ,resetfields(){
		this.validateobj={},this.editing={};
			//for(var i in this.$refs)
		{
			console.log(this.$refs,'resetfields');
		}
	  }
	
   }
    
   
  });

</script>        
</body>
</html>