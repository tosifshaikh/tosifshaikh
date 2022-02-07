// JavaScript Document
var obj,txt,flg=0,th_id;
var temp=[];
var s=[],b;	
var x = new Object();
var url="XML/student.xml";
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
			   // x=obj.responseXML;
				dataSort();
			}
		}
	
	obj.open("GET",url,true);
	obj.send();
}
	function dataSort(id)
	{
		temp=[];
		var name=[],physics=[],english=[],student=[];
		x=obj.responseXML.getElementsByTagName("student");
		document.getElementById.innerHTML="";
	     
		/*
		for(var i=0;i<student.length;i++)
		{
			
			var arry=[]
			
		 var name=student[i].getAttribute("name");  	    
		 var physics=student[i].getElementsByTagName("physics")[0].firstChild.nodeValue;
		 var maths=student[i].getElementsByTagName("maths")[0].firstChild.nodeValue;
		 var english=student[i].getElementsByTagName("english")[0].firstChild.nodeValue;
		arry['name']=name;
		arry['physics']=physics;
		arry['english']= english;
		arry['maths']=maths;
     	temp.push(arry);
		
		}*/
		    
		
			for(var i=0;i<x.length;i++)
		 {
			  var name=x[i].getAttribute("name"); 
			  var physics=x[i].getElementsByTagName("physics")[0].firstChild.nodeValue;
			  var maths=x[i].getElementsByTagName("maths")[0].firstChild.nodeValue;
			  var english=x[i].getElementsByTagName("english")[0].firstChild.nodeValue
			  var Total=parseInt(physics)+parseInt(maths)+parseInt(english);
			  var avg=Total/x.length;
			 
			  if(id && id!="")
			 {    
				temp.push(eval(id)+"#"+i);
				
			 }
			 else
			 {
				 temp.push("#"+i);
				 
			 }
		 }		
		 
		 if(flg==0)
		{
			flg=1;
		}
		
		if(id!=th_id)
		{
			
			temp.sort();
			flg=2;
			
		}
		else
		{
			if(flg==1)
			{
				
				temp.sort();
				flg=2;
				th_id=id;
			}
			else if(flg==2)
			{
				temp.sort();
				temp.reverse();
				flg=1;
				th_id=id;
			}
		}
		
			tempSplit();	      
	}
	function tempSplit()
	{   
	
	 txt="<table border='1' ><tr><th  id='name' onclick='dataSort(id)'>Name</th><th id='physics' onclick='dataSort(id)'>physics</th><th id='maths' onclick='dataSort(id)'>maths</th><th id='english' onclick='dataSort(id)'>english</th><th id='Total' onclick='dataSort(id)'>Total</th> <th id='avg' onclick='dataSort(id)' >Avg</th></tr>";
	
		for(var k=0;k<temp.length;k++)
				  {
					var s=temp[k].split("#");
					var b=s[1];
				    var name=x[b].getAttribute("name");
					var physics=x[b].getElementsByTagName("physics")[0].firstChild.nodeValue;
					var maths=x[b].getElementsByTagName("maths")[0].firstChild.nodeValue;
					var english=x[b].getElementsByTagName("english")[0].firstChild.nodeValue;
					var  total=parseInt(physics)+parseInt(maths)+parseInt(english);
			        var avg=total/temp.length;
					txt+="<tr>";
					txt+="<td>"+name+"</td><td>"+physics+"</td><td>"+maths+"</td><td>"+english+"</td></td><td>"+ total+"</td><td>"+avg+"</td>"; 
					txt+="</tr>";
				   }	
				   txt+="</table>";
				   document.getElementById('mydiv').innerHTML=txt; 
	}