var xmlDoc='';
var editId='';
var editPId='';
var addeditFlag=0;
var xmlhttp='';
var space,str,pos,cnt=0,counter=0;
				
function loadGrid()
{
	try
	{               	
		getGrid();
	}
	catch(err) 
	{
	}
}
function getGrid()
{
	try
	{
		$(document).ready(function() 
		{
			xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange=function()
			{
				if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
				{
					xmlDoc=JSON.parse(xmlhttp.responseText);
					renderGrid();
				}
			}
			xmlhttp.open("GET","treeJson.php",false);
			xmlhttp.send();
		});
	}
	catch(err) 
	{
	}
}

function renderGrid()
{
	try
	{
		k=0;
		var inp=$("<td>").append($("<input>").attr("id","addIn").attr("disabled","disabled"));
		var sav=$("<td>").append($("<button>").attr("onClick","saveData()").text("SAVE").attr("disabled","disabled").attr("id","save"));
		var add=$("<td>").append($("<button>").attr("onClick","addData("+0+","+0+","+0+","+0+")").text("ROOT_ADD"));
		$("table").append($("<tr>").append(inp,sav,add).attr("id",0));
		autocall(0,1);
	}
	catch(err) 
	{
	}
}

function autocall(id,m)
{
	var n=1;
	for(i in xmlDoc[id])
	{
		var tb='';
		a=genNum(id,m,n);
		for(var j=0;j<=k;j++)
		{
			if(j==k)
			{
				createRow(i,id,(j+1),a,tb,cnt);
			}
			else
			{
				tb+="<td></td>";
			}
		}
		k++;
		n++;
		cnt=i;
		counter++;
		autocall(i,a);
		if(id==0)
		{
			m++;
		}
		k--;
	}
}
function addData(val,rval,spc,a)
{
	editId=val;
	editPId=rval;
	space=spc;
	str=a;
	
	document.getElementById('addIn').disabled=false;
	document.getElementById('save').disabled=false;
	addeditFlag=1;	
}
function deleteData(val,pid)
{
	autoDelete(val,pid);
	delete xmlDoc[pid][val];
	$("#"+val).remove();
	otherRow(pid);
}
function autoDelete(val,pid)
{
	for(var x in xmlDoc[val])
  	{
		delete xmlDoc[val];
		$("#"+x).remove();
		autoDelete(x,val)
	}
}

function editData(val,pid)
{
	addeditFlag=2;
	document.getElementById('addIn').disabled=false;
	document.getElementById('save').disabled=false;
	for(var x in xmlDoc)
  	{
		if(pid==x)
		{
			editId=val;
			editPId=pid;
			document.getElementById('addIn').value=xmlDoc[pid][val];	
		}
	}
}

function saveData()
{
	var temp=document.getElementById('addIn').value;
	if(document.getElementById('addIn').value=='')
	{
		alert("Please Enter Value");	
		return false;
	}
	if(addeditFlag==1)
	{
		counter=counter+1;
		var tempobj=new Object();
		
		if(xmlDoc[editId]==undefined)
		{
			createRow(counter,editId,space,str,0,cnt);	
			tempobj[counter]=temp;
			xmlDoc[editId]=tempobj;		
		}
		else
		{
			for(i in xmlDoc[editId])
			{
				if(checkValid(editId,i)==false)
				{
					return false;	
				}
			}
			createRow(counter,editId,space,str,0,cnt);
			xmlDoc[editId][counter]=temp;
		}
	}
	if(addeditFlag==2)
	{
		for(var x in xmlDoc[editPId])
		{
			if(editPId!=x)
			{
				if(checkValid(editPId,x)==false)
				{
					return false;	
				}
			}
			if(editPId==x)
			{
				xmlDoc[editPId][x]=temp;	
			}
		}
		$("#nm_"+editId).text(temp);
	}
	document.getElementById('addIn').value='';
	document.getElementById('addIn').disabled=true;
	document.getElementById('save').disabled=true;	
}

function checkValid(pid,id)
{
	var temp=document.getElementById('addIn').value;
	
	if((xmlDoc[pid][id]).toLowerCase()==temp.toLowerCase())
	{
		alert("Please Enter Different Name");
		return false;
	}
}

function createRow(id,pid,space,num,tab,cnt)
{
	
	var sp='';
	for(var i=0;i<space;i++)
	{
		sp+="<td></td>";
	}
	var temp=document.getElementById('addIn').value;
	
	if(temp!='')
	{
		var name=temp;
		
		if(editId==0)
		{
			var cntt=lastRow(editId,0);
		}
		else
		{
			var cntt=lastRow(editId,$("#i"+(editId)).text());
		}
		cnt=cntt[0];
		tab=sp;
		space++;
	}
	else
	{
		var cntt=[pid,num];
		var name=xmlDoc[cntt[0]][id];
	}
	var sr=$("<td>").append(cntt[1]).attr("id","i"+id);
	var nm=$("<td>").append(name).attr("id","nm_"+id);

	var add=$("<td>").append($("<button>").attr("onClick","addData("+id+","+cntt[0]+","+space+",\""+cntt[1]+"\")").text("CHILD_ADD"));
	
	var del=$("<td>").append($("<button>").attr("onClick","deleteData("+id+","+pid+")").text("DELETE"));
	var edt=$("<td>").append($("<button>").attr("onClick","editData("+id+","+pid+")").text("EDIT"));

	$("<tr>").append(tab,sr,nm,add,del,edt).insertAfter("#"+(cnt)).attr("id",id);
}
function lastRow(Id,m)
{
	var n=1;
	pos=Id;
	for(var i in xmlDoc[Id])
	{	
		pos=i;
		n++;
		lastRow(i,a);
	}
	a=genNum(Id,m,n);
	return [pos,a];
}
function genNum(id,m,n)
{
	if(id==0)
	{
		 var a=n;	
	}
	else
	{
		 var a=m+"."+n;
	}
	return a; 	
}
function otherRow(pid)
{
	var n=1;
	for(var i in xmlDoc[pid])
	{
		a=genNum(pid,$("#i"+pid).text(),n);
		n++;
		$("#i"+i).text(a);
		otherRow(i);
	}
}