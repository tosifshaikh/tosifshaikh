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
	border: solid red;
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
input:focus {
	border-color: red;
}
select:focus {
	border-color: red;
}
textarea:focus {
	border-color: red;
}
.labelClass {
	margin: 0;
	display: inline-flex;
	;
}
.dragClass {
	outline: solid #f00;
	background: #FFCC99;
}
.moveClass {
	background-image: url('move.gif');
	cursor: move;
	height: 40px;
!important;
	background-repeat: no-repeat;
	background-position: center;
	width: 30px;
}
/* pagging*/
a {
	color: #999;
}
.current {
	color: red;
}
ul {
	padding: 0;
	list-style-type: none;
}
li {
	display: inline;
	margin: 5px 5px;
}
a.first::after {
	content: '...'
}
a.last::before {
	content: '...'
}
</style>
<script src="axios.js"></script>
<script src="vue.js"></script>
</head>
<body>
<div id="app" class="table-responsive">
  <form method='post' id='frm'>
    <table border='1' width='50%' align="center" id="itemTable" class="table table-striped table-hover table-sm">
      <tbody>
        <tr>
          <td :colspan="headers.length"><button @click='addAbove(-1)'   type='button' >Add Row</button>
            <button @click='save(0)' type='button'>Save All</button>
            <button @click='reselAll()'   type='button'>Reset All</button></td>
        </tr>
        <tr >
          <th v-for="(content, index) in headers" >{{content.name}}</th>
        </tr>
        <tr class="filters" >
          <td></td>
          <td></td>
          <td v-for="(colval, colindex) in columns"><input  v-if="colval.filtertype==1" :key="colval.filterid" v-model.lazy.trim="colval.filterVal" placeholder="Search" v-on:keyup.enter="addFilter(colval,$event)" class="form-control" >
            <select @change="addFilter(colval,$event)" v-else = "colval.filtertype==2" v-model.lazy.trim="colval.filterVal" class="form-control">
              <option v-for="(statusval,statusIndex) in status" v-bind:value="statusIndex">{{statusval}}</option>
            </select></td>
          <td></td>
        </tr>
        <tr v-for="(item, index) in filtered " :key='index'   v-on:dblclick='edit(index,item.id)' 	draggable="true"   @mouseup="mouseUp(index,item.id)"  @mouseover="mouseOver(index,item.id)" @mouseout="mouseOut(index,item.id)"  v-bind:style="(dragStyleObj!=null && dragStyleObj[index]!=null)?dragStyleObj[index]:'' "  scope="row">
          <td v-on:click="cancelEv(index,item.id,item[colkey])"  class="text-center"><label >{{parseInt(index)+1}}</label></td>
          <td  class='moveClass' @mousedown="mouseDown(index,item.id)"></td>
          <td v-for="(cols, colkey) in columns"><label v-if = "!editingObj[item.id] && cols.inputType==3"> {{status[item[colkey]]}} </label>
            <label v-if = "!editingObj[item.id] && cols.inputType!=3"> {{item[colkey]}} </label>
            <input  v-if = "editingObj[item.id] && cols.inputType==1" 
    :ref='item.id' :key="index" :id="item.id+'_'+colkey"  @blur= "editTodo(index,colkey,$event);" v-model.lazy.trim="item[colkey]" 
	v-bind:class="{dirty:(validateobj!=null && validateobj[index] && validateobj[index].includes(colkey)==true)?true:false}" 
	v-todo-focus="item[colkey] == editing" @keyup.esc="resetCheck(index,item.id)">
            <textarea  v-if = "editingObj[item.id] && cols.inputType==2" :ref='item.id'
      @blur= "editTodo(index,colkey,$event);" :key='index' :id="item.id+'_'+colkey"  v-model.lazy.trim="item[colkey]"  
	  v-bind:class="{dirty:(validateobj!=null && validateobj[index] && validateobj[index].includes(colkey)==true)?true:false}"
	  v-todo-focus="item[colkey] == editing" >
	  </textarea>
            <select @change="editTodo(index,colkey,$event);" :key='index' :id="item.id+'_'+colkey" v-if = "editingObj[item.id]  && cols.inputType==3" 
			v-model.trim="item.status" :ref='item.id' 
	  v-bind:class="{dirty:(validateobj!=null && validateobj[index] && validateobj[index].includes(colkey)==true)?true:false}"
	  v-todo-focus="item[colkey] == editing" @keyup.esc="resetCheck(index,item.id)">
              <option v-for="(statusval,statusIndex) in status" v-bind:value="statusIndex">{{statusval}}</option>
            </select>
            <br />
            <span v-show="validateobj!=null && validateobj[index] && validateobj[index].includes(colkey)==true" class="invalid"> This field is required. </span></td>
          <td><button @click="addAbove(index,item.id)" type='button'  :disabled="(editingObj[item.id])?true:false"> add </button>
            <span v-if = "!editingObj[item.id]" >
            <button @click="edit(index,item.id)" type='button'>Edit</button>
            </span> <span v-else="editingObj[item.id]">
            <button @click="save(item.id)" type='button' >Save</button>
            </span><!--:disabled="(validateobj!=null && validateobj[index])?true:false" --> 
            <span v-if="editingObj[item.id]">
            <button @click="resetCheck(index,item.id)" type='button'>Cancel</button>
            </span>
            <button @click='deleteTableRow(index,item.id)' type='button'>Delete</button></td>
        </tr>
        <tr>
          <td v-if='noData==true' :colspan="headers.length" align="center">No Data</td>
        </tr>
        <tr>
          <td :colspan="headers.length"><button @click='addAbove(-1)'   type='button'>Add Row</button>
            <button @click='save(0)' type='button'>Save All</button>
            <button @click='reselAll()'   type='button'>Reset All</button>
            <div style='float:right'>
              <ul>
                <li v-for="pageNumber in totalPages" v-if="Math.abs(pageNumber - currentPage) < 3 || pageNumber == totalPages - 1 || pageNumber == 0"> <a href="#" @click="setPage(pageNumber)"  :class="{current: currentPage === pageNumber, last: (pageNumber == totalPages - 1 && Math.abs(pageNumber - currentPage) > 3), first:(pageNumber == 1 && Math.abs(pageNumber - currentPage) > 3)}">{{ pageNumber }}</a> </li>
              </ul>
            </div></td>
          <td></td>
        </tr>
      </tbody>
    </table>
    <br>
  </form>
  {{contents}}
  {{copyObject[0]}} </div>
<script src="jquery.js"></script> 
<script src="jquery-ui.js"></script> 
<script>
/*	
var mainVue=Vue.extend({
	data () {
      return initialState();
   
   }
	
});*/
 var d=new Vue({
  el: '#app',
   data(){
	   return {
		   result:[],
	headers:[],contents:[],columns:[],_beforeEditingCache :[],beforeEditingCache1:{},_editing1:{},temp:{},
	dispOrder:[],addLastOrder:{},
	updateOrder:{},
	noData:false,
	status:{0:'Select Status',1:"No Documents",2:"Documents Linked",3:"Documents Approved",4:"Partially Approved",5:"Not Linked"},
	activeFilters: {},
		   isFilter:false,
		   resultCount:8,
	localData:[],editing:{},validateobj:[],editingObj:{}
   ,copyObject:[],dragStyleObj:{},downIndex:-2,originalobj:[],downID:'',recIds:[],currentPage:0,itemsPerPage:2
	   }
   },
   mounted(){
	console.log('mounted 2nd method called',this.headers)
   }
   ,watch:{
	   deep:true,
	   copyObject(o,p){
			//console.log('watch',o,p);
		}

   },created(){
	this.getPost({ data:{p:1,act:'grid'}});
	console.log('created','1st method called')
   }
   
   ,computed:{
	  totalPages: function() {
		  console.log(Math.ceil(this.resultCount / this.itemsPerPage),'totalPages');
		  return Math.ceil(this.resultCount / this.itemsPerPage)
	  },
	    
   filtered(){
	console.log('computed',this.contents);
	   var filtered=this.copyObject;
	  var filtered=[];
	   for(var j=0;j<this.dispOrder.length;j++)
	   {
		 for(var i=0;i<this.copyObject.length;i++)
		 {
			if(this.dispOrder[j]==this.copyObject[i].id)
			{
				filtered.push(this.copyObject[i]);
			}
		 }
	   }
	   this.copyObject=filtered;

	  // filtered=this.sortData(filtered);
	  
	   console.log('filtered',filtered,this.isFilter,this.activeFilters,this.copyObject);
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
	   if(filtered && filtered.length==0)
		{
			this.noData=true;
		}
		
		
		//filtered=this.paginate(filtered);
		console.log('paginate filter');
	   // this.activeFilters={};
	   return filtered;
	   
   }
	   
   }
   ,
   filters:{
	 
   }
   ,
   methods:{
	   getPost(params){
		   var that=this;
		params=$.extend({ method:'post', url:'data.php',data:params,responseType:'json'},params);
		axios(params).then(function(response){
			//that.result= response.data;
		//	if(response.data.total>0){
			that.headers=response.data.headers;
			that.contents = response.data.contents;
			that.columns=response.data.columns;
			that.dispOrder=response.data.disporder;
			that.copyObject=Object.assign([],Object.values(response.data.contents));
			that.originalData=JSON.stringify(response.data.contents);
			//}
		  });
   },
	   setPage: function(pageNumber) {
		  // 	console.log('setPage',pageNumber);
          this.currentPage = pageNumber;
        },
		 paginate: function(list) {
			 
           
			//this.totalPages=Math.ceil(this.resultCount / this.itemsPerPage);
			//this.totalPages=[10];
            if (this.currentPage >= this.totalPages) {
              this.currentPage = this.totalPages-1;
            }
			//if(this.totalPages>0 && this.currentPage==0)
			{
				//this.currentPage=1;
			}
            var index = this.currentPage * this.itemsPerPage
			console.log('pagginate',index,this.currentPage,this.resultCount,this.itemsPerPage);
			return list.slice(index, this.itemsPerPage);
			
           // return list.slice(index, index + this.itemsPerPage)
        }//,
		//totalPages(){
		//console.log(Math.ceil(this.resultCount / this.itemsPerPage),'totalPages');
		//return Math.ceil(this.resultCount / this.itemsPerPage)
	//}
		,
	   deleteTableRow: function (index,id) { 
      this.counter--;
	  var that=this;
	  for(var j=0;j<this.copyObject.length;j++)
	  {
		if(this.copyObject[j].id==id)
		{
			this.$delete(that.copyObject,k);
			axios({method:'post', url:'data.php',data:{id:id,act:'delete'},responseType:'json'}).then(function(response){
			console.log('deleteTableRow',response);
		  });
			 
		}

	  }
	  this.copyObject.filter(function(record,k){
		console.log('deleteTableRow',record,k);
		if(record.id==id)
		{
			that.$delete(that.copyObject,k);
			axios({method:'post', url:'data.php',data:{id:id},responseType:'json'}).then(function(response){
			console.log('deleteTableRow');
		  });
			 
		}
	  });

	  
      //this.copyObject.splice(idx, 1);      
      },
	  addTableRow: function () { 
      this.counter++;
	  var editId='new'+ (this.copyObject.length+1);
	  var rec_id='new'+ (this.copyObject.length+1);;
      this.copyObject.push({company_name:'',city:'',turn_over:'0',status:1,id:editId,isNew:1,rec_id:rec_id});
	 this.setValues(this.editingObj,editId,true);
	 //this.newdispOrder[]
	  console.log(this.copyObject,'addTableRow',this.copyObject,this.dispOrder);
      
   },
   setValues(obj,index,value){
	this.$set(obj,index,value);
   }
   ,
	addAbove:function(index,rowID){
		console.log(index,'addAbov');
		var editId='new'+ (this.copyObject.length+1);
		var rec_id='new'+ (this.copyObject.length+1);
		var defaultData={company_name:'',city:'',turn_over:'0',status:1,id:editId,isNew:1,rec_id:rec_id};
		if(index=='-1'){
			this.copyObject.push(defaultData);
			this.dispOrder.push(editId);
			this.addLastOrder[editId]=editId;
		}
		else
		 {
			this.copyObject.splice(index, 0, defaultData);
			this.dispOrder.splice(index, 0,editId);
			this.updateOrder[index]=editId;
		 }
		 this.$set(this.editingObj,editId,true);
		 
		 //this.newdispOrder[index]=editId;
		console.log('addAbove',this.dispOrder,this.addLastOrder,this.updateOrder,this.editingObj,this.copyObject);
	},edit:function(indx,id){
		//var localColl = this.contents;
		//this._beforeEditingCache =  Object.assign([], this.copyObject);
		//this.originalobj
		var tempObj=JSON.parse(this.originalData);
	this.$set(this.beforeEditingCache1,id,tempObj['_'+id]);
	//this._beforeEditingCache[id]=JSON.parse(this.originalData)['_'+id];
	console.log('edit data',this.beforeEditingCache1,id,tempObj);	
	this.setValues(this.editingObj, id, true);
		//this.$set(this.editing, id, true);
	//	this.temp[indx]={};
			//this.temp[indx]=this.copyObject[indx];
		//console.log('this.editing',this.beforeEditingCache1);
			//	this.$nextTick(function(){
					//console.log(this.$refs,'rr');
		
				//});
			//}
      },sortData(obj,sortKey){
	sortKey=sortKey || this.currentOrder;
	obj.sort((a, b) => {
	  if (a[sortKey] < b[sortKey]) return -1
	  if (a[sortKey] > b[sortKey]) return 1
	  return 0
	});
	return obj;
   }
    ,
   resetCheck(index,id){
	 console.log('resetCheck',this.editingObj,this.beforeEditingCache1);
	// this.$set(this._beforeEditingCache,id,JSON.parse(this.originalData)['_'+id]);
	
	 that=this;
		this.copyObject.filter(function(record,k){
		console.log(record.id,id,index,k,'yyyy')
			if(that.editingObj && that.editingObj[record.id] && record.id==id && !record.isNew && that.beforeEditingCache1[record.id])
			{
				if(that.validateobj[k]){
				//	delete that.validateobj[k];
				  that.$delete(that.validateobj,k);
				}	console.log('ttgg	',that.copyObject,k,that.beforeEditingCache1[record.id])
				that.setValues(that.copyObject,k,that.beforeEditingCache1[record.id]);
			    that.$delete(that.editingObj,id);
				that.$delete(that.beforeEditingCache1,record.id);
				//delete that.editingObj[id];
				return true;
			}else{
				
				if(record.isNew==1 && record.id==id && that.editingObj[record.id]){
					console.log('else part',record.id,record.isNew);
				that.$delete(that.copyObject,index);
				}
				console.log(that.copyObject,'resetCheck end')
				//that.$set(that.copyObject[index],'edit',false);
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
	   console.log(this.downIndex,id,ind,this.downID,'mousup');
                if(this.downIndex!=ind && this.downIndex!=-2)
                {

                var fromId= this.downID,ToID=id,fromOrder=null,ToOrder=null;
				// this.copyObject.filter(function(record,k){
				//  	if(that.copyObject[k].id==fromId)
				//  	{
				// 		fromOrder=parseInt(that.copyObject[k].rec_id); 
						
				// 		that.downIndex=k;
				// 		cnt++;
				//  	}
				// 	if(that.copyObject[k].id==ToID)
				// 	{
				// 		ToOrder=parseInt(that.copyObject[k].rec_id);
				// 		ind=k;
				// 		cnt++;
				// 	}
				// 	if(fromOrder!=null)
				// 		{
				// 			that.$set(that.copyObject[ind],'rec_id', (fromOrder));
				// 			//	this.$set(this.originalobj[ind],'rec_id',fromOrder);
				// 		}
				// 		if(ToOrder!=null)
				// 		{
				// 			that.$set(that.copyObject[that.downIndex],'rec_id',(ToOrder));
				// 			//this.$set(this.originalobj[this.downIndex],'rec_id',ToOrder);
				// 		}
				// 	console.log(cnt,'cnt',that.downIndex,ind,fromOrder,ToOrder);
					
					
				//  });
				
			 	if(this.copyObject[this.downIndex].id==fromId){
				 var fromOrder=(this.copyObject[this.downIndex]);
			 	}
			 	if(this.copyObject[ind].id==ToID){
			 	ToOrder=(this.copyObject[ind]);
			 	}
			 	if(fromOrder!=null){
			 	this.$set(this.copyObject,ind, (fromOrder));
			// //	this.$set(this.originalobj[ind],'rec_id',fromOrder);
			} 	
			 	if(ToOrder!=null){
			 	this.$set(this.copyObject,this.downIndex,(ToOrder));
			 	//this.$set(this.originalobj[this.downIndex],'rec_id',ToOrder);
			 	}
				console.log('mouseUp',ind,this.downIndex+' this.downIndex',(fromOrder),ToOrder,this.copyObject);
				this.dragStyleObj={};
				this.downIndex=-2;
				this.downID=null;

                }
		 
   }
   ,reselAll:function(){
	   console.log('reselAll');
	   var that=this;
	   this.copyObject.filter(function(record,k){
			if(that.editingObj[record.id] && that.copyObject[k].id==record.id)
			{
				// this.$delete(  this.validateobj,ii);
				that.$delete( that.editingObj,record.id);
				if(record.isNew==1)
				{
					that.$delete( that.copyObject,k);
				}else{
				Object.assign(that.copyObject[k],that.$options.data().contents["_"+record.id]);
				}
			}

	   });

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
    }
	,
      save : function(id){
		 var localData={},expData={},insertObj={},updateObj={},insertLastOrder={};

		 if(id==0)
		 {
			for(var i in this.$refs)
		    {
				for(var ii in this.$refs[i])
				{
					var expVal=this.$refs[i][ii].id.split('_'),value=this.$refs[i][ii].value;
					this.$delete(this.editingObj,expVal[0]);
					if(isNaN(expVal[0]))
					{
						if(!insertObj[expVal[0]]){
							insertObj[expVal[0]]={}
						}
						insertObj[expVal[0]][expVal[1]]=this.$refs[i][ii].value;
						if(this.addLastOrder[expVal[0]])
						{
							insertLastOrder[expVal[0]]=expVal[0];
						}
					}else{
						if(!updateObj[expVal[0]]){
							updateObj[expVal[0]]={}
						}
						updateObj[expVal[0]][expVal[1]]=this.$refs[i][ii].value;
					}
					//localData[expVal[1]]=this.$refs[i][ii].value;
					//console.log(this.$refs,this.$refs[i][ii].value,'yyy',this.$refs[i][ii].id,insertObj,expVal[0],isNaN(expVal[0]));
				}
			}
		 }else{
			for(var ii in this.$refs[id])
				{
					var expVal=this.$refs[id][ii].id.split('_'),value=this.$refs[id][ii].value;
					this.$delete(this.editingObj,expVal[0]);
					if(isNaN(expVal[0]))
					{
						if(!insertObj[expVal[0]]){
							insertObj[expVal[0]]={}
						}
						insertObj[expVal[0]][expVal[1]]=this.$refs[id][ii].value;
						if(this.addLastOrder[expVal[0]])
						{
							insertLastOrder[expVal[0]]=expVal[0];
						}
						
					}else{
						if(!updateObj[expVal[0]]){
							updateObj[expVal[0]]={}
						}
						updateObj[expVal[0]][expVal[1]]=this.$refs[id][ii].value;
					}
					//localData[expVal[1]]=this.$refs[i][ii].value;
					//console.log(this.$refs,this.$refs[i][ii].value,'yyy',this.$refs[i][ii].id,insertObj,expVal[0],isNaN(expVal[0]));
				}
		 }
			//localData={};
			
		var finalObj={act:'save'};
			if($.isEmptyObject(insertObj)==false)
			{
				finalObj['insert']=insertObj;
			}
			if($.isEmptyObject(updateObj)==false)
			{
				finalObj['update']=updateObj;
			}
			if($.isEmptyObject(insertLastOrder)==false)
			{
				finalObj['insertlast']=insertLastOrder;
			}
			if($.isEmptyObject(this.updateOrder)==false)
			{
				finalObj['updateorder']=this.updateOrder;
			}
			//this.addLastOrder,this.updateOrder
		  console.log(expData,'save',this.newdispOrder,finalObj);
		  var that=this;
		 // params=$.extend({ method:'post', url:'data.php',data:params,responseType:'json'},params);
		axios({ method:'post', url:'data.php',data:finalObj,responseType:'json'}).then(function(response){
			console.log(response,'responsesave');
			//that.result= response.data;
		//	that.headers=response.data.headers;
			$.extend( that.contents,response.data.contents);
			$.extend( that.dispOrder,response.data.dispOrder);
			$.extend( that.copyObject,Object.assign([],Object.values(response.data.contents)));
			$.extend( that.originalData,response.data.contents);
			
		  });
      }
	  ,resetfields(){
		this.validateobj={};
		var that=this;
			this.copyObject.filter(function(record,k){
				if(that.editingObj && that.editingObj[record.id])
				{
					if(record.isNew==1){
						 that.$delete(that.copyObject,k);
					}else{
						that.$delete(that.editingObj,record.id);
					}
				}

			});
			//for(var i in this.$refs)
		{
			console.log('resetfields');
		}
	  }
	
   }, directives: {
    'todo-focus': function(value) {
		//console.log(value,'value');
      if(!value) {
		  
        return;
      }
     // var el = this.el;
     // setTimeout(function() {
       // el.focus();
     // }, 0);
    }
  }
    
   
  });
d.originalData=[];
</script>
</body>
</html>
