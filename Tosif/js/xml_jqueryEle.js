
// JavaScript Document
var data,x,txt,l=0,val,z=1,num,cid,flag=1,xx,n,flg,name,par,key;
var url="XML/tree2.xml";
var folder_Arr=[];
var temp=[],btn_arry=[];
var i;
var nodeId,folder;
var table,tr,td1,txt1,td2,str;
$(document).ready(function() {
	
	$.ajax({
	        type:'GET',
			url:url,
			dataType:"xml",
			success:function(dt){
			data=dt;	
			fetchData();	
				
			}//success function end
	
	});//ajax end
});//ready function end
function fetchData()
{ 
  /*
     change the index of each loop for diff each loop in jquery
  */
   folder=$(data).find('folder');
	$.each(folder,function(index,element) {    	
	folder_Arr=[]
	xx=folder[index].children;
    $.each (xx,function(index,element) { 
	 folder_Arr[xx[index].nodeName]=$(xx[index]).text(); 
});
	temp.push(folder_Arr);
 });    
dataGrid();
		
}
function dataGrid()
{
	$('#mydiv').html(''); //for stop append when datgrid()called each time
	table=$(document.createElement("table"));
	$(table).attr({"border":"1","align":"center"});
	tr=$(document.createElement("tr"));
	txt1=$(document.createElement('input'));
	$(txt1).attr({"id":"nodeVal","type":"text"});
	$(txt1).css("visibility","hidden");
	td1=$(document.createElement("td"));
	td2=$(document.createElement("td"));
	td1.append(txt1);
	tr.append(td1);
	var btn_arry=['Add_Root','save']; 
	$.each(btn_arry,function(k){
		   var nam=btn_arry[k];
		if(btn_arry[k]=='Add_Root')
		  {
			  str='addNodes(0,this.id)';
		  }
		  if(btn_arry[k]=='save')
		  {
			  str='saveData(flg)';
		  }
			btn_arry[k]=$(document.createElement('input'));
			$(btn_arry[k]).attr({"value":nam,"type":"button","id":nam,"onclick":str});
			if(nam=='save')
			{	
			  $(btn_arry[k]).attr("disabled",true); 
			}
			td2.append(btn_arry[k]);
			tr.append(td2);
		});//btn Array for each     
	
    table.append(tr);
    dataPrint(0,1);//recursion function initalize id=0;  
  $('#mydiv').append(table);
}
function dataPrint(id,z)
{ 
   tr1=$(document.createElement("tr"));
	var p=1;
	//$.each(temp,function(k){  
		for(var k in temp)
		{
		if(temp[k].parent==id)
		{ 
			if(temp[k].parent==0)
			{ 
				num=z;
			}
			else
			{
				num=z+"."+p; 
			}
			for(var j=0;j<l;j++)
			{
				var td1=$(document.createElement("td"));
				td1.append(document.createTextNode(' '));
				tr1.append(td1);
			}
			nodeId=temp[k].id;
			nm=temp[k].name;
		    var td2=$(document.createElement("td"));
			var btn_arry=['add','edit','del'];
			td2.append(document.createTextNode(num+" "+temp[k].name+"    "));
			$.each(btn_arry,function(i){
				var nam=btn_arry[i];
				if(btn_arry[i]=='add')
				{
					str='addNodes('+nodeId+',this.id)';
				}
				if(btn_arry[i]=='edit')
				{
					str='editNodes(this.id,'+nodeId+')';
				}
				if(btn_arry[i]=='del')
				{  
				   str='del('+nodeId+',\''+nm+'\')';
				}
				btn_arry[i]=$(document.createElement('input'));
				$(btn_arry[i]).attr({"value":nam,"type":"button","id":nam+k,"onclick":str});
				/*$(btn_arry[i]).attr("value",nam);
				$(btn_arry[i]).attr("type","button");
				$(btn_arry[i]).attr("id",nam+k);
				$(btn_arry[i]).attr("onclick",str);	*/
				td2.append(btn_arry[i]);
				tr1.append(td2);
				});//btn_arry loop end
			
	        table.append(tr1);
			l++;
			p++;
			dataPrint(temp[k].id,num);
			l--; 	 
			if(temp[k].parent==0)
			{    
				l=0;
				z++;
			}
		}
		
		}//});//each loop of temp
}
function addNodes(nodeId,id)
{ 
	$('#nodeVal').css("visibility","visible");
	$('#save').prop("disabled",false);
	n=nodeId;
	flg=1;
  if(flag==1)
   {
		$("#"+id).attr("disabled",true);
		cid=id;
		flag=2;		
   }
	else if(flag==2)
	{
	    $("#"+id).attr("disabled",true)
		$("#"+cid).attr("disabled",false)
		 cid=id;
	}
 }
function del(nodeId,name)
{	
	$('#nodeVal').val("");
	var y=confirm("Are you sure you want to Delete "+name+" ?");
	
	if(y==true)
	{
		delNodes(nodeId);
	 }
	    dataGrid();	
} 
function delNodes(nodeId)
{
		for(var i in temp)
		{    	
		if(temp[i].id==nodeId || nodeId==temp[i].parent)
		{ 
			var delid=temp[i].id;
			delete temp[i];
			delNodes(delid);
			}
		}
		}
function editNodes(editId,nodeId)
{  
   for (k in temp)
   {
	   if(temp[k].id==nodeId)
	   {
		   name=temp[k].name;
		   par=temp[k].parent
		   key=k;
        }
   }
  $('#nodeVal').css("visibility","visible");
  $('#save').attr("disabled",false);
  $('#nodeVal').val(name);
   n=nodeId;
  if(flag==1)
   {
		$("#"+editId).attr("disabled",true);
		cid=editId;
		flag=2;
   }
	else if(flag==2)
	{
	     $("#"+editId).attr("disabled",true);
	     $("#"+cid).attr("disabled",false);
		 cid=editId;	
	}
	flg=2;
}
function validate(val,id)
{
       if(val=="")
	   {
		   alert("Blank Not Allowed");
		   return false;
	   }
	   else if( /[^a-zA-Z0-9\-\_\/]/.test(val))
	   {
		alert("special characters are not allowed")
		return false;
	   }
	   else 
	   {
		   for(var k in temp)
		  {
			if(temp[k].parent==id )
			  { 
					if(temp[k].name.toLowerCase()==val.toLowerCase())
					{
						alert("Duplicate value not alllowed");
						return false;
					}
			  }
		  }		  
	   }
}
function saveData(flg)
{
	val=$('#nodeVal').val().trim();
	if(flg==1)
	{
		 var data=validate(val,n);
		 if(data==false)
		{		
			$('#nodeVal').focus(); 
			val="";
		} 
	  	else
	 	{
			temp.push({id:temp.length+1,name:val,parent:n});
			alert("new node "+val+" added");
        }
	}	
	if(flg==2)
	{	  
		if(name==val)
		{
			 dataGrid();	 
		}
		else
		{
			 var data=validate(val,par);
			 if(data==false)
			 {
				$('#nodeVal').focus(); 
			 }
			 else
			 {	
				temp[key].name=val;
				alert("New Name is changed:"+val);	
			 }
		}
	}
   dataGrid();
}