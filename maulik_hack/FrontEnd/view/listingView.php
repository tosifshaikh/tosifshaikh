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
<link rel="stylesheet" href="../design/css/bootstrap.css" type="text/css">
<style>
.dirty {
	border-color: #a94442;
 //background: #F00;
	box-shadow:inset 0 1px 1px rgba(0,0,0,.075);
	-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);
}
.dirty:focus {
	outline-color: #a94442;
	
}
.erroFont{
display:block;
color:#a94442;
	margin-top:5px;
	margin-bottom:10px;
}

a.disabled {
  pointer-events: none;
  cursor: default;
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
<!--
<script src="axios.js"></script>
<script src="vue.js"></script>-->
 <script type = "text/javascript" src = "../design/js/vue.js"></script>
	   <script type = "text/javascript" src = "../design/js/axios.min.js"></script>
</head>
<body>
<div id="app" class="container">
  <form method='post' id='frm'>
    <table id="itemTable" align="center"  class=" table table-lg table-striped table-hover" cellspacing="0" cellpadding="0">
      <tbody>
        <tr>
          <td :colspan="headers.length"><a href="#" @click="addAbove(-1)" title="Add Row" > 
		  <image src="add.png" width="20px" height="20px">
		  </a>
		  
		   <a href="#" @click="save(0)" title="Save All" v-if='noData==false'>
		  <image src="save.png" width="20px" height="20px">
		  </a>
            
            <!--  <button @click='reselAll()'   type='button'>Reset All</button></td> -->
        </tr>
        <tr >
          <th v-for="(content, index) in headers" scope="col" :width="(content.width)?content.width:''" >{{content.name}}</th>
        </tr>
        <tr class="filters" >
          <td></td>
          <td></td>
          <td v-for="(colval, colindex) in columns">
		  
		  <input  v-if="colval.filtertype==1" :key="colval.filterid" v-model.trim="colval.filterVal" placeholder="Search" v-on:keyup.enter="addFilter(colval,$event)" class="form-control form-control-sm" >
		  
          <select @change="addFilter(colval,$event)" v-if = "colval.filtertype==2" v-model.trim="colval.filterVal" class="form-control form-control-sm">
			<option v-for="(statusval,statusIndex) in status" v-bind:value="statusIndex">{{statusval}}</option>
			</select>
			
			  <input type="date" class="form-control form-controlform-control-sm" v-if ="colval.filtertype==4" v-model.trim="colval.filterVal" >
			  
			</td>
          <td></td>
        </tr>
        <tr v-for="(item, index) in filtered " :key='index'   v-on:dblclick='edit(index,item.id)' 	draggable="true"   @mouseup="mouseUp(index,item.id)"  @mouseover="mouseOver(index,item.id)" @mouseout="mouseOut(index,item.id)"  v-bind:style="(dragStyleObj!=null && dragStyleObj[index]!=null)?dragStyleObj[index]:'' "  scope="row">
          <td v-on:click="resetCheck(index,item.id)"  class="text-center" scope="col"><label >{{parseInt(index)+1}}</label></td>
          <td  v-bind:class='{moveClass:(!copyObject[index].isNew)?true:false}' @mousedown="mouseDown(index,item.id)"></td>
          <td v-for="(cols, colkey) in columns"><label v-if = "!editingObj[item.id] && cols.inputType==3"> {{status[item[colkey]]}} </label>
            <label v-if = "!editingObj[item.id] && cols.inputType!=3"> {{item[colkey]}} </label>
            <input  v-if = "editingObj[item.id] && cols.inputType==1" 
    :ref='item.id' :key="index" :id="item.id+'_'+colkey"  @blur= "editTodo(index,colkey,$event,item.id);" v-model.trim="item[colkey]" 
	v-bind:class="{dirty:(validateobj!=null && validateobj[item.id] && validateobj[item.id].includes(colkey)==true)?true:false}" 
	 @keyup.esc="resetCheck(index,item.id)" class="form-control form-control-sm" autofocus >
	<textarea  v-if = "editingObj[item.id] && cols.inputType==2" :ref='item.id'
	@blur= "editTodo(index,colkey,$event,item.id);" :key='index' :id="item.id+'_'+colkey"  v-model.trim="item[colkey]"  
	v-bind:class="{dirty:(validateobj!=null && validateobj[item.id] && validateobj[item.id].includes(colkey)==true)?true:false}" class="form-control form-control-sm">
	</textarea>
	<select @change="editTodo(index,colkey,$event,item.id);" :key='index' :id="item.id+'_'+colkey" v-if = "editingObj[item.id]  && cols.inputType==3" 
	v-model.trim="item.Work_status" :ref='item.id' 
	v-bind:class="{dirty:(validateobj!=null && validateobj[item.id] && validateobj[item.id].includes(colkey)==true)?true:false}"
	@keyup.esc="resetCheck(index,item.id)" class="form-control form-control-sm">
	  <option v-for="(statusval,statusIndex) in status" v-bind:value="statusIndex">{{statusval}}</option>
	</select>

	 <input type="date" class="form-control form-controlform-control-sm" @change="editTodo(index,colkey,$event,item.id);" :key='index' :id="item.id+'_'+colkey" v-if = "editingObj[item.id]  && cols.inputType==4" v-model.trim="item[colkey]" :ref='item.id'
	 v-bind:class="{dirty:(validateobj!=null && validateobj[item.id] && validateobj[item.id].includes(colkey)==true)?true:false}">
            
            <span v-show="validateobj!=null && validateobj[item.id] && validateobj[item.id].includes(colkey)==true" class="erroFont">  Required. </span></td>
          <td>
		  
		 <a href="#" @click="addAbove(index,item.id)" :class="{disabled:(editingObj[item.id])?true:false}">
		  <image src="add.png" width="20px" height="20px">
		  </a>
		 
		<span v-if = "!editingObj[item.id]" >		 
		  <a href="#" @click="edit(index,item.id)" title='Add'>
		  <image src="edit.png" width="20px" height="20px">
		  </a>
		  </span>
		   <span v-else="editingObj[item.id]">	 
		  <a href="#" @click="save(item.id)" title='Save'>
		  <image src="save.png" width="20px" height="20px">
		  </a>
		  </span>
		  
		  <span v-if="editingObj[item.id]">	 
		  <a href="#" @click="resetCheck(index,item.id)" title="Undo">
		  <image src="undo.png" width="20px" height="20px">
		  </a>
		  </span>
		   <a href="#" @click="deleteTableRow(index,item.id)" title="Delete">
		  <image src="delete.png" width="20px" height="20px">
		  </a>
		
		<a href="#" @click="docpage(item.id)" title="Document Page">
		  <image src="doc.png" width="20px" height="20px">
		  </a>
        
		</td>
        </tr>
        <tr>
          <td v-if='noData==true' :colspan="headers.length" align="center">No Data</td>
        </tr>
        <tr>
          <td :colspan="headers.length">
		  <a href="#" @click="addAbove(-1)" title="Add Row" >
		  <image src="add.png" width="20px" height="20px">
		  </a>

            <a href="#" @click="save(0)" title="Save All" v-if='noData==false'>
		  <image src="save.png" width="20px" height="20px">
		  </a>
			  <a @click="setPage(currentPageIndex-1)">Prev</a>
    <a v-for="i in pages" @click="setPage(i)" v-bind:class="{'current':currentPageIndex === i}">{{i+1}}</a>
    <a @click="setPage(currentPageIndex+1)">Next</a>
         <!--   <button @click='reselAll()'   type='button'>Reset All</button>
            <div style='float:right'>
              <ul>
                <li v-for="pageNumber in totalPages" v-if="Math.abs(pageNumber - currentPage) < 3 || pageNumber == totalPages - 1 || pageNumber == 0"> <a href="#" @click="setPage(pageNumber)"  :class="{current: currentPage === pageNumber, last: (pageNumber == totalPages - 1 && Math.abs(pageNumber - currentPage) > 3), first:(pageNumber == 1 && Math.abs(pageNumber - currentPage) > 3)}">{{ pageNumber }}</a> </li>
              </ul> -->
            </div></td>
         
        </tr>
      </tbody>
    </table>
    <br>
  </form>
 </div>
<script src="../design/js/jquery.js"></script> 

<script>

 var vm=new Vue({
  el: '#app',
   data(){
	   return {
		   result:[],
	headers:[],contents:[],columns:[],_beforeEditingCache :[],temp:{},localObj:[],
	dispOrder:{},addLastOrder:{},counter:0,
	updateOrder:{},
	noData:false,
	status:{0:'Select Status',1:"No Documents",2:"Documents Linked",3:"Documents Approved",4:"Partially Approved",5:"Not Linked"},
	activeFilters: {},
		   isFilter:false,
		   resultCount:8,
	editing:{},validateobj:[],editingObj:{}
   ,copyObject:[],dragStyleObj:{},downIndex:-2,originalobj:[],downID:'',currentPage:0,itemsPerPage:2,currentPageIndex: 0,beforeEditingCache1:{}
	   }
   },
   mounted(){
	console.log('mounted 2nd method called',this.headers)
   }
   ,created(){
	this.getPost({ data:{p:1,act:'grid'}});
	console.log('created','1st method called')
   }
   
   ,computed:{
	  pages: function() {
		  console.log(Math.ceil(this.resultCount / this.itemsPerPage),'totalPages');
		  return Math.ceil(this.resultCount / this.itemsPerPage)
	  },
	  offset: function() {
        console.log(this.currentPageIndex, this.perpage);
        return this.currentPageIndex * this.perpage;
      }
	  ,
	    
   filtered(){
	console.log('computed');
	  var filtered=[];
	   for(var j in this.dispOrder)
	   {
		 for(var i in this.copyObject)
		 {
			if(this.dispOrder[j]==this.copyObject[i].id)
			{
				
				filtered.push(this.copyObject[i]);
			}
		 }
	   }
	   this.copyObject=filtered;

	  // filtered=this.sortData(filtered);
	  
	   console.log('filtered',filtered,this.isFilter,this.activeFilters,this.copyObject,this.dispOrder);
	   this.noData=false;
	   if(this.isFilter)
	   {
		  this.isFilter=false;
		  axios({method:'post', url:'data.php',data:{act:'filter',data:this.activeFilters},responseType:'json'}).then(function(response){
			console.log('filter reponse',response);
			vm.columns=response.data.columns;
			vm.dispOrder=response.data.disporder;
			vm.copyObject=Object.assign([],Object.values(response.data.contents));
		  });
		  
		  /*for(filterkey in this.activeFilters)
		  {
			  var filterValue=this.activeFilters[filterkey];
			 filtered= filtered.filter(function(record){
				 console.log(new RegExp(filterValue, 'i').test(record[filterkey]),filterValue,record[filterkey]);
				 return new RegExp(filterValue, 'i').test(record[filterkey])
				 
			  });
		  }*/
		  
		 
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
	   setPage: function(index) {
        if (index >= 0 && index < this.pages) {
          this.currentPageIndex = index;   
        }        
      }
	   ,
	   docpage(id){
	   window.open("https://www.w3schools.com",'New window','fullscreen=yes,resizable=yes').focus();
	   },
	   getPost(params){
		   var that=this;
		params=$.extend({ method:'post', url:'data.php',data:params,responseType:'json'},params);
		 axios(params).then(function(response){
		//	if(response.data.total>0){
			vm.resultCount=response.data.total;
			vm.headers=response.data.headers;
			vm.contents = response.data.contents;
			vm.columns=response.data.columns;
			vm.dispOrder=response.data.disporder;
			vm.copyObject=Object.assign([],Object.values(response.data.contents));
			vm.originalData=JSON.stringify(response.data.contents);
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
	  var that=this,deleId=0;
	  for(var j=0;j<this.copyObject.length;j++)
	  {
		if(this.copyObject[j].id==id)
		{
			this.$delete(this.editingObj,id);
			
			for(var tt in this.dispOrder)
			{
				if(this.dispOrder[tt]==id)
				{
					this.$delete(this.dispOrder,id);
					break;
				}
			}
			
			
			if($.isEmptyObject(this.updateOrder)==false)
			{
				for(var t in this.updateOrder)
				{
					if(this.updateOrder[t]==id)
					{
						this.$delete(this.updateOrder,t);
						break;
					}
				}
			}
			
			if(this.copyObject[j].isNew==1)
			{
				this.addLastOrder[id]=id;
			}else{
				//this.$delete(that.copyObject,j);
				//this.addLastOrder[editId]=editId;
				deleId=id;
				
			}
			this.$delete(that.copyObject,j);
			break;
			
		}

	  }
	  if(deleId!=0){
	  axios({method:'post', url:'data.php',data:{id:deleId,act:'delete'},responseType:'json'}).then(function(response){
			console.log('deleteTableRow',response);
		  });
	  }
	 /* this.copyObject.filter(function(record,k){
		console.log('deleteTableRow',record,k);
		if(record.id==id)
		{
			that.$delete(that.copyObject,k);
			axios({method:'post', url:'data.php',data:{id:id},responseType:'json'}).then(function(response){
			console.log('deleteTableRow');
		  });
			 
		}
	  }); */   
      },
   setValues(obj,index,value){
	this.$set(obj,index,value);
   }
   ,
	addAbove:function(index,rowID){
		console.log('addAbov');
		var editId='new'+ (++this.counter);
		var defaultData={Tail:'',MSN_Number:'',Check_name:'0',Received_Date:'',Work_status:1,id:editId,isNew:1,Defect:0};
		if(index=='-1'){
			vm.copyObject.push(defaultData);
			vm.dispOrder[Object.keys(vm.dispOrder).length+1]=editId;
			vm.addLastOrder[editId]=editId;
		}
		else
		 {
			this.copyObject.splice(index, 0, defaultData);
			this.dispOrder.splice(index, 0,editId);
			this.updateOrder[index]=editId;
		 }
		 this.$set(vm.editingObj,editId,true);
		
		console.log('addAbove');
	},edit:function(indx,id){
		var tempObj=JSON.parse(this.originalData);
	this.$set(this.beforeEditingCache1,id,tempObj['_'+id]);
	console.log('edit data',this.beforeEditingCache1,id,tempObj);	
	this.setValues(this.editingObj, id, true);
      }
    ,
   resetCheck(index,id){
	 console.log('resetCheck',this.editingObj,this.beforeEditingCache1);
	// this.$set(this._beforeEditingCache,id,JSON.parse(this.originalData)['_'+id]);
	if($.isEmptyObject(this.updateOrder)==false)
	{
		for(var t in this.updateOrder)
		{
			if(this.updateOrder[t]==id)
			{
				this.$delete(this.updateOrder,t);
				break;
			}
		}
	}
	 that=this;
		this.copyObject.filter(function(record,k){
		console.log(record.id,id,index,k,'yyyy');
			vm.$delete(that.editingObj,id);
			if(record.id==id && !record.isNew && that.beforeEditingCache1[record.id])
			{
				if(that.validateobj[record.id]){
				//	delete that.validateobj[k];
				 // that.$delete(that.validateobj,k);
				  that.$delete(that.validateobj,record.id);
				}	
				console.log('ttgg	',that.copyObject,k,that.beforeEditingCache1[record.id])
				that.setValues(that.copyObject,k,that.beforeEditingCache1[record.id]);
			    
				that.$delete(that.beforeEditingCache1,record.id);
				//delete that.editingObj[id];
				return true;
			}else{
				console.log('else part out');
				if(record.isNew==1 && record.id==id){
					console.log('else part',record.id,record.isNew);
					if(that.validateobj[record.id]){
					that.$delete(that.validateobj,record.id);
					}
			
					for(var tt in vm.dispOrder)
					{
						if(that.dispOrder[tt]==record.id)
						{
							that.$delete(that.dispOrder,tt);
							break;
						}
					}
					
					if(that.addLastOrder[record.id])
					{
						that.$delete(that.addLastOrder,record.id);
					}
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
		
	   console.log('mouseDown');
	  
	   this.downIndex=ind;
	   this.downID=id;
	   this.$set(this.dragStyleObj,ind,{
                    cursor:'move'
	   });
	   
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
                if(this.downID!=id && this.downIndex!=-2)
                {

                var fromId= this.downID,ToID=id,fromOrder=null,ToOrder=null;
				// this.copyObject.filter(function(record,k){
				//  	if(that.copyObject[k].id==fromId)
				//  	{
				// 		fromOrder=parseInt(that.copyObject[k].recid); 
						
				// 		that.downIndex=k;
				// 		cnt++;
				//  	}
				// 	if(that.copyObject[k].id==ToID)
				// 	{
				// 		ToOrder=parseInt(that.copyObject[k].recid);
				// 		ind=k;
				// 		cnt++;
				// 	}
				// 	if(fromOrder!=null)
				// 		{
				// 			that.$set(that.copyObject[ind],'recid', (fromOrder));
				// 			//	this.$set(this.originalobj[ind],'recid',fromOrder);
				// 		}
				// 		if(ToOrder!=null)
				// 		{
				// 			that.$set(that.copyObject[that.downIndex],'recid',(ToOrder));
				// 			//this.$set(this.originalobj[this.downIndex],'recid',ToOrder);
				// 		}
				// 	console.log(cnt,'cnt',that.downIndex,ind,fromOrder,ToOrder);
					
					
				//  });
				var tempObj=[];
				var fromDispOrder=0,toDispOrder=0;
				//console.log(this.dispOrder,this.dispOrder.indexOf(fromId),this.dispOrder.indexOf(ToID));
				for(var tt in this.dispOrder)
				{
					if(this.dispOrder[tt]==fromId)
					{
						fromDispOrder=tt;
						break;
					}
					if(this.toDispOrder[tt]==ToID)
					{
						toDispOrder=tt;
						break;
					}
				}
				console.log(fromDispOrder,toDispOrder,'order');
				//var fromDispOrder=this.dispOrder.indexOf(fromId),toDispOrder=this.dispOrder.indexOf(ToID);
				
				this.$set(this.dispOrder,fromDispOrder, (ToID));
			    this.$set(this.dispOrder,toDispOrder, (fromId));
				
				tempObj.push({id:fromId,fromOrder:fromDispOrder,toDispOrder:toDispOrder});
				tempObj.push({id:ToID,fromOrder:toDispOrder,toDispOrder:fromDispOrder});
				/*
			 	if(this.copyObject[this.downIndex].id==fromId){
				 var fromOrder=(this.copyObject[this.downIndex]);
			 	}
			 	if(this.copyObject[ind].id==ToID){
			 	ToOrder=(this.copyObject[ind]);
			 	}
			 	if(fromOrder!=null){
			 	this.$set(this.copyObject,ind, (fromOrder));
			    } 	
			 	if(ToOrder!=null){
			 	this.$set(this.copyObject,this.downIndex,(ToOrder));
			 	}*/
				
				//tempObj.push({id:ind,fromOrder:fromOrder});
				//tempObj.push({id:ind,this.downIndex:fromOrder});
				
				axios({method:'post', url:'data.php',data:{data:tempObj,act:'reorder'},responseType:'json'}).then(function(response){
			console.log('reorder',response);
		  });
				
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
	console.log('addFilter',this.activeFilters,this.isFilter)
   }
   ,
  editTodo1: function(index,col,ev) {
  
  console.log('editTodo',ev.target,this.copyObject);
     var str=index+'_'+col;
	 var value=ev.target.value;
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
	
	 editTodo: function(index,col,ev,id) {
  
	/* if(this.downIndex!=-2)
	 {
		 return false;
	 }*/
     //var str=index+'_'+col;
	
	 var value=(index=='-1')?ev:ev.target.value; console.log('editTodo',value+'==',id,col,ev);
	if(this.columns[col] && this.columns[col].validate)
	 { console.log('in666',col,value,this.columns[col].validate.includes(value))
		//validation check
		if(this.columns[col].validate.includes(value)) 
		{ 
				if(!this.validateobj[id])
				{
					this.validateobj[id]=[];
				}
				this.validateobj[id].push(col);
				var unique = this.validateobj[id].filter((v, i, a) => a.indexOf(v) === i);
				this.validateobj[id]=unique;
				this.validateobj=$.extend({},this.validateobj);
		}else{
		 console.log('before');
		 //remove from validation if user enters value
		if(this.validateobj && this.validateobj[id] && this.validateobj[id].includes(col)==true)
		{
			console.log('validateobj',this.validateobj);
			 var delIndex=this.validateobj[id].indexOf(col);
			 this.validateobj[id].splice(delIndex, 1);
		 }
		 if(this.validateobj!=null && this.validateobj[id] && Object.keys(this.validateobj[id]).length==0)
		 {
			  console.log('validateobj2',this.validateobj);
			  //this.validateobj.splice(id, 1);
			  delete this.validateobj[id];
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
					var expVal=this.$refs[i][ii].id.split(/_(.*)/),value=this.$refs[i][ii].value;
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
					var expVal=this.$refs[id][ii].id.split(/_(.*)/),value=this.$refs[id][ii].value;
					
					this.editTodo('-1',expVal[1],value,expVal[0]);
					console.log(this.validateobj,'in save');
					if(this.validateobj[expVal[0]]){
						//this.$set(this.validateobj,expVal[0],[]);
						return false;
					}
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

</script>
</body>
</html>
