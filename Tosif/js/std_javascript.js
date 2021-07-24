// JavaScript Document
var obj,txt,flg=1,cid;
var temp=[];
var s=[],b;	
var x ;
var z=[];
var url="XML/stud.xml";

function call()
{
	if(window.XMLHttpRequest)
	{
		obj=new XMLHttpRequest();
	}
	obj.onreadystatechange=function()
	{
		if(obj.readyState==4 && obj.status==200)
		{
			x=obj.responseXML;
			dataSort();
		}
	}
	obj.open("GET",url,true);
	obj.send();
}
function getData()
{
		temp=[];
		var student=x.getElementsByTagName("student");	
		for(var i=0;i<student.length;i++)
		{
			var xx=student[i].children; 
			var a=[];
			for(var j=0;j<xx.length;j++)
			{
			  a[xx[j].nodeName]=xx[j].firstChild.nodeValue;		
			}
		    temp[i]=a;
		}
		for(var p=0;p<temp.length;p++)
		{		
			temp[p]['total']=parseInt(temp[p]['physics'])+parseInt(temp[p]['maths'])+parseInt(temp[p]['english']);
			temp[p]['avg']=temp[p]['total']/temp.length;
		}		
}
function dataSort(id)
{
		z=[];
		getData();
		for(var k=0;k<temp.length;k++)
		{  
			if(id && id!="")
			{  
			    z.push(temp[k][id]+"#"+k);
			}
			else
		   {
			  z.push("#"+k);
		   }
		}
		 if(id!=cid)
		 {
			 z.sort();
			 cid=id;
			 flg=2;
		 }
		 else
		 {
			 if(flg==1)
			 {
				z.sort();
				cid=id;
				flg=2;
			}
			else if(flg==2)
			{	   
			 	z.sort();
				z.reverse();
				cid=id;
				flg=1;
			 }
		 }
			 tempSplit();	      
}
function tempSplit()
{   
	 	txt="<table border='1' ><tr><th id='name' onclick='dataSort(id)'>Name</th><th id='physics' onclick='dataSort(id)'>physics</th><th id='maths' onclick='dataSort(id)'>maths</th><th id='english' onclick='dataSort(id)'>english</th><th id='total' onclick='dataSort(id)'>Total</th> <th id='avg' onclick='dataSort(id)' >Avg</th></tr>";    
		for(var k=0;k<z.length;k++)
	   {
			var s=z[k].split("#");
			var b=s[1];
			txt+="<tr>";
			txt+="<td>"+temp[b]['name']+"</td><td>"+temp[b]['physics']+"</td><td>"+temp[b]['maths']+"</td><td>"+temp[b]['english']+"</td><td>"+ temp[b]['total']+"</td><td>"+temp[b]['avg']+"</td>"; 
			txt+="</tr>";
	   }	
	   txt+="</table>";
	   document.getElementById('mytab').innerHTML=txt; 
}