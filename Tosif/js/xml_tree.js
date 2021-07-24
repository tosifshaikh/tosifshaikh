var obj,data,x,txt,l=0,val,z=1,num,cid,flag=1,xx,delid;
var url="XML/tree2.xml";
var folder_Arr=[];
var temp=[];
var i;
var nodeId,folder;
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
  txt="<table border='1' cellspacing='5'  align='center' colspan='2'>";
  txt+="<tr><td><input type='text' id='nodeVal' style='visibility:hidden;'></td><td><input type='button' id='0' value='Add Root' onclick='addNodes(0,id)'></td></tr>";
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
			var nodeId=temp[k].id;
			
			txt+="<td>"+num+" "+temp[k].name+" &nbsp;&nbsp;&nbsp;&nbsp;"+"<input type='button' id='add"+k+"' value='Add' onclick='addNodes("+nodeId+",this.id)'><input type='button' id='edit"+k+"' value='Edit' onclick='editNodes("+temp[k].parent+",this.id,"+k+")'><input type='button' id="+nodeId+" value='Delete' onclick='delNodes(id)'></td>";
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
	var val=document.getElementById('nodeVal');
	
	if(id!=id)
	{
		cid=id;
		 document.getElementById(id).value="Edit";
		flag=1;
	}
	else
	{ 
			if(flag==1)
			{
			document.getElementById(id).value="Save";
			val.style.backgroundColor="#E6E6E6";
			val.focus();
			flag=2;
			cid=id;
			}
	else if(flag==2)
	{
		var data=validate(val.value.trim(),nodeId);
		
		if(data==false)
		{
			document.getElementById('nodeVal').focus(); 
		}
	  	else
	 	{
			if(nodeId<=0)
			{
				temp.push({id:temp.length+1,name:val.value,parent:0});
			}
			else
			{
				temp.push({id:temp.length+1,name:val.value,parent:nodeId});
			}
			
			alert("new node "+val.value+" added")	;	  
			document.getElementById(id).value="Add";
			flag=1;
			cid=id;
			dataGrid();
	 	}
	}
  }
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
 dataGrid();
}

function editNodes(id,editId,nm)
{
		//alert(nm)
		document.getElementById('nodeVal').style.visibility="visible";
		//document.getElementById('nodeVal').value=temp[id].name;
		if(id!=id)
		{   document.getElementById(editId).value="Edit";
		   	
			cid=id;
			flag=1;
		}				
		if(flag==1)
		{	
			document.getElementById('nodeVal').value=temp[nm].name;
			document.getElementById(editId).value="Save";
			cid=id;
			flag=2;
		}
		else if(flag==2)
	    {
		   var newName=document.getElementById('nodeVal').value;
		  
		   
		    if(temp[nm].name==newName)
			{
				 dataGrid();	 
			}
			else
			{
				 var data=validate(newName.trim(),id);
 				 
				 
				 if(data==false)
				 {
					document.getElementById('nodeVal').focus(); 
					document.getElementById(editId).value="Save";
				 }
				 else
				 {
					temp[id].name=newName;
					alert("New Name is changed:"+temp[nm].name);	
					document.getElementById(editId).value="Edit"; 
				 }
			}
			cid=id;
			flag=1;
			dataGrid();	 	
	   }
}

function validate(val,id)
{
	
	
       if(val=="")
	   {
		   alert("Blank Not Allowed");
		   val="";
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
						//console.log(temp[id].id,temp[k].parent)
						alert("Duplicate value not alllowed");
						val="";
						return false;
					}
					
			  }/*else if(temp[k].parent==0)
			  {
				  if(temp[k].name.toLowerCase()==val.toLowerCase())
					{
						console.log(temp[id].id,temp[k].parent)
						alert("Duplicate value not alllowed1");
						val="";
						return false;
					}
			  }*/
			 
		  
			
		  }		  
	   }
	   
	  
}