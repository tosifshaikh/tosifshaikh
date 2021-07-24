var obj,data,x,txt,l=0,val,z=1,num,cid,flag=1,xx,n,flg,name,par,key;
var url="XML/tree2.xml";
var folder_Arr=[];
var temp=[],btn_arry=[];
var i;
var nodeId,folder;
var table,tr,td1,txt1,td2,str;
function loadFile()
{
	 if(window.XMLHttpRequest)
	 {
		 obj=new XMLHttpRequest();
	 }
	 else
	 {
		 obj=new ActiveXObeject("Microsoft.XMLHTTP");
	 }
	 obj.onreadystatechange=function()
	 {
		if(obj.readyState==4 &&obj.status==200) 
		{
			x=obj.responseXML;
			fetchData();
		}	 
	  }
	 obj.open("GET",url,true);
	 obj.send();
}
function fetchData()
{ 
   folder=x.getElementsByTagName('folder');
   for(var i=0;i<folder.length;i++)
   {   
        folder_Arr=[];
	    xx=folder[i].children;
		for(var j=0;j<xx.length;j++)
		{
		  folder_Arr[xx[j].nodeName]= xx[j].firstChild.nodeValue;		  
		}
		temp.push(folder_Arr);
   }  
   dataGrid(); 
}
function dataGrid()
{
   document.getElementById('mydiv').innerHTML="";//for stop append when datgrid()called each time
  table=document.createElement("table");
  table.setAttribute("border",1);
  table.setAttribute("align","center");
  tr=document.createElement("tr");
   txt1=document.createElement('input');
   txt1.id="nodeVal";
   txt1.type="text";
   txt1.style='visibility:hidden'
   td1=document.createElement("td");
   td2=document.createElement("td");
   td1.appendChild(txt1);
   tr.appendChild(td1);
	var btn_arry=new Array('Add_Root','save');      
	  for(var i in btn_arry)
	 {
		  if(btn_arry[i]=='Add_Root')
		  {
			  var nam=btn_arry[i];
			  str='addNodes(0,this.id)';
		  }
		  if(btn_arry[i]=='save')
		  {
			  var nam=btn_arry[i];
			  str='saveData(flg)';
		  }
		   btn_arry[i]=document.createElement('input');
		   btn_arry[i].value=nam;
		   btn_arry[i].type="button"
		   btn_arry[i].id=nam;
		   btn_arry[i].onclick=new Function(str);
		   td2.appendChild(btn_arry[i]);
		   tr.appendChild(td2);
	  }
    tr.appendChild(td2);
    table.appendChild(tr);
   dataPrint(0,1);//recursion function initalize id=0;  
  document.getElementById('mydiv').appendChild(table);
}
function dataPrint(id,z)
{ 
   
   tr1=document.createElement("tr");
	var p=1;
	for(var k in temp)
	{	  
		if(id==temp[k].parent)
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
				var td1=document.createElement("td");
				td1.appendChild(document.createTextNode(' '));
				tr1.appendChild(td1);
			}
			nodeId=temp[k].id;
			nm=temp[k].name;
		    var td2=document.createElement("td");
			var btn_arry=new Array('add','edit','del');
			td2.appendChild(document.createTextNode(num+" "+temp[k].name+"    "));
			for(var i in btn_arry)
			{
				if(btn_arry[i]=='add')
				{
					var nam=btn_arry[i];
					str='addNodes('+nodeId+',this.id)';
				}
				if(btn_arry[i]=='edit')
				{
					var nam=btn_arry[i];
					str='editNodes(this.id,'+nodeId+')';
				}
				if(btn_arry[i]=='del')
				{  
				   var nam=btn_arry[i];
				   str='del('+nodeId+',\''+nm+'\')';
				}
				btn_arry[i]=document.createElement('input');
				btn_arry[i].value=nam;
				btn_arry[i].type="button";
				btn_arry[i].id=nam+k;
				btn_arry[i].onclick=new Function(str);
				td2.appendChild(btn_arry[i]);
				tr1.appendChild(td2);
			}
	        table.appendChild(tr1);
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
	}
}
function addNodes(nodeId,id)
{ 
	
	document.getElementById('nodeVal').style.visibility="visible";
	document.getElementById('save').disabled=false;
	n=nodeId;
	flg=1;
  if(flag==1)
   {
		document.getElementById(id).disabled=true;
		cid=id;
		flag=2;		
   }
	else if(flag==2)
	{
	     document.getElementById(id).disabled=true;
	     document.getElementById(cid).disabled=false;
		 cid=id;
	}
 }
function del(nodeId,name)
{	
	document.getElementById('nodeVal').value='';
	var y=confirm("Are you sure you want to Delete "+name+" ?");
	
	if(y==true)
	{
		delNodes(nodeId);
	 }
	    dataGrid();	
} 
function delNodes(nodeId,name)
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
   document.getElementById('nodeVal').style.visibility="visible";
  document.getElementById('save').disabled = false;
   document.getElementById('nodeVal').value=name;
   n=nodeId;
  if(flag==1)
   {
		document.getElementById(editId).disabled=true;
		cid=editId;
		flag=2;
   }
	else if(flag==2)
	{
	     document.getElementById(editId).disabled=true;
	     document.getElementById(cid).disabled=false;
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
	val=document.getElementById('nodeVal').value.trim();
	if(flg==1)
	{
		 var data=validate(val,n);
		 if(data==false)
		{		
			document.getElementById('nodeVal').focus(); 
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
					document.getElementById('nodeVal').focus(); 
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