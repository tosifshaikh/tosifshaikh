
var obj,i,b,th_id;
var data,txt,k,flg=0;
var temp2=[];
var temp=[];
var url="PHP/student2.php";		 
function myFun()
{

	if(window.XMLHttpRequest)
	{
	obj=new XMLHttpRequest();
	}
	else
	{
	obj=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	obj.onreadystatechange=function()
	{
	
		if(obj.readyState==4 && obj.status==200)
		{
			data=JSON.parse(obj.responseText);
			
			getData();
		}
	}

	obj.open("GET",url,true);
	obj.send();	
}
		 
function getData(id)
{ 						
			
		temp =new Array();
		var i=0;
		 
		for(k in data)
		{	  
			var name=k;
			var marks1=data[k][0];
			var marks2=data[k][1];
			var marks3=data[k][2];
			var sum=parseInt(marks1)+parseInt(marks2)+parseInt(marks3);
			var avg=sum/3;
			
			if(id && id!='')
			{
				temp.push(eval(id)+"#"+name);
				
			}
			else
			{
			 temp[i++]=i+"#"+k;
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
			th_id=id;
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

    arr_Sort();
					        
}
function arr_Sort()
{
							
	 cnt1=1;
	 document.getElementById("mytab").innerHTML='';
	 var txt='';
	  txt="<table border='1'> <tr> <th id='name' onclick='getData(id)' >Name</th> <th id='marks1' onclick='getData(id)' > Marks 1</th> <th id='marks2' onclick='getData(id)'   > Marks 2</th> <th id='marks3' onclick='getData(id)' > Marks 3</th><th id='sum' onclick='getData(id)'>Sum</th><th  id='avg' onclick='getData(id)' >Avg</th> </tr>";
	  for(h in temp)
	  {

			var temp2=temp[h].split("#");
			var  b=temp2[1];
			var name=b;
			var sum=0;
			txt=txt+"<tr><td>"+b+"</td>";
			for(mark in data[b])
			{
				sum=sum+parseInt(data[b][mark]);
				txt=txt+"<td>"+data[b][mark]+"</td>";
			}
		
		  var avg=sum/3;
		  txt=txt+"<td>"+sum+"</td><td>"+avg.toPrecision(5)+"</td></tr>";
		  cnt1++;
	  }	
    txt=txt+"</table>";
	document.getElementById("mytab").innerHTML=txt;
	  
}
						 