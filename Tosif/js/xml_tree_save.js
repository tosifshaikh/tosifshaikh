
var obj,data,x,txt,l=0,val,z=1,num,cid,flag=1,xx,n,flg,name,par,key;
var url="XML/tree2.xml";
var folder_Arr=[];
var temp=[];
var i;
var nodeId,folder,val;
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

  txt="<table border='1' cellspacing='5'  align='center' >";
  txt+="<tr><td><input type='text' id='nodeVal' style='visibility:hidden;'></td><td><input type='button' id='0' value='Add Root' onclick='addNodes(0,id)'><input type='button' id='save' value='save' disabled='disable' onclick='saveData(flg)'></td></tr>";
  dataPrint(0,1);//recursion function initalize id=0;
  txt+="</table>";
  document.getElementById('myDiv').innerHTML=txt;	
}
function dataPrint(id,z)
{
	txt+="<tr>";
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
				txt+="<td ></td>";		
			}
			nodeId=temp[k].id;
			nm=temp[k].name;
			txt+="<td>"+num+" "+temp[k].name+" &nbsp;&nbsp;&nbsp;&nbsp;"+"<input type='button' name='add' id='add"+k+"' value='Add' onclick='addNodes("+nodeId+",this.id)' ><input type='button' id='edit"+k+"' value='Edit' onclick='editNodes(this.id,"+nodeId+")'><input type='button' id="+nodeId+" value='Delete' onclick='del(id,\""+temp[k].name+"\")'></td>";
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
	txt+="</tr>";
}
function addNodes(nodeId,id)
{
   document.getElementById('nodeVal').style.visibility="visible";
   document.getElementById("save").disabled = false;
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
	var y=confirm("Are you sure you want to Delete "+name+" ?");
	if(y==true)
	{
		delNodes(nodeId);
	 }
	    dataGrid();	
} 
 
function delNodes(nodeId,name)
{
	   
	 /*  if(flag==1)
	   {
		   var y=confirm("Are you sure you want to Delete "+name+" ?");
		   if(y==true)
	      {
			flag=2;
		  }
	   }
	   if(flag==2)
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
	 */
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
   document.getElementById("save").disabled = false;
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