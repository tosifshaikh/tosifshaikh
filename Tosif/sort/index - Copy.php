<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script>
var temp=[];
temp[0]={name:"def",physics:"210",maths:"11",english:'9'};
temp[1]={name:"abc",physics:"30",maths:"165",english:'79'};
temp[2]={name:"yre",physics:"65",maths:"23",english:'134'};
temp[3]={name:"tos",physics:"10",maths:"15",english:'98'};
temp[4]={name:"tos123",physics:"100",maths:"150",english:'9800'};
temp[5]={name:"123dos123",physics:"1000000",maths:"150",english:'9800'};
temp[6]={name:"Tos",physics:"10",maths:"15",english:'98'};
var headerArr=["name","physics","maths","english","Average","Total"];

var allSettingObj={
	initialSort:'asc',
	allowSort:1
	
	}
function doOperation()
{
	for(var i=0,len=temp.length;i<len;i++)
	{
		
		temp[i]['Total']=(parseInt(temp[i].physics)+parseInt(temp[i].maths)+parseInt(temp[i].english));
		temp[i]['Average']=((temp[i]['Total'])/3).toFixed(2);
	}
}
function renderHeader()
{
	var tbl="";
	tbl +="<tr>";
   for(var i=0,len=headerArr.length;i<len;i++)
	{
		tbl +="<td id="+headerArr[i]+" onclick=getSort(this.id)>"+headerArr[i]+"</td>";
	}
		tbl +="</tr>";
	return tbl;
}
var t=[];
var cid;
 var flg=1;
function getSort(id)
{
	doOperation();
	//var id=ele || 0;
   //  id=id;
	  t=[];
		
		var val='';
	for(var i=0,len=temp.length;i<len;i++)
	{
		if(id && id!="")
		{
			
			t.push(temp[i][id]+"##"+i);
		}else
		{
			t.push("##"+i);
		}
	}	
	
	if(id!=cid)
	{
		t.sort(function(a,b){ 
		 doSort(a,b);
		/*a=a.split("##")[0];
		b=b.split("##")[0];

		if(!isNaN(a[0]))
		{
			a=parseInt(a),b=parseInt(b);
		}else{
			a=String(a).toLowerCase(),b=String(b).toLowerCase();
		}
		return a > b? 1 : a< b ? -1 : 0*/
		});
		 cid=id;
		flg=2;
	
	}
	else{
			if(flg==1)
			{
				t.sort(function(a,b){ 
					 doSort(a,b);
		/*	a=a.split("##")[0];
			b=b.split("##")[0];
			
			if(!isNaN(a[0]))
		{
			a=parseInt(a),b=parseInt(b);
		}else{
			a=String(a).toLowerCase(),b=String(b).toLowerCase();
		}	
				return a > b? 1 : a< b ? -1 : 0*/
				});
				 cid=id;
				flg=2;
					
			}else if(flg==2)
			{
				t.sort(function(a,b){
					 doSort(a,b);
			/*a=a.split("##")[0];
			b=b.split("##")[0];
					
		if(!isNaN(a[0]))
		{
			a=parseInt(a),b=parseInt(b);
		}else{
			a=String(a).toLowerCase(),b=String(b).toLowerCase();
		}	
					 return a > b? 1 : a< b ? -1 : 0*/
					 
					 });
				t.reverse();
				cid=id;
				flg=1;
					
			}
	}	
	//cid=id;
	
	 renderGrid();
}
function renderGrid()
{
	var tbl="";
	tbl +="<table align='center' width='100%' border=1>";
	tbl +=renderHeader();
	tbl +=displayGrid();
	tbl +="</table>";
	document.getElementById('divGrid').innerHTML=tbl;
}
function doSort(a,b)
{
	a=a.split("##")[0];
	b=b.split("##")[0];
	if(!isNaN(a[0]))
		{
			a=parseInt(a),b=parseInt(b);
		}else{
			a=String(a).toLowerCase(),b=String(b).toLowerCase();
		}	
	return a > b? 1 : a< b ? -1 : 0;
}
function displayGrid()
{
	var tbl="";
	for(var i=0,len=t.length;i<len;i++)
	{
		var p=t[i].split("##");
	    var id=p[1];
		tbl +="<tr>";
		tbl +="<td>"+temp[id].name+"</td>";
		tbl +="<td>"+temp[id].physics+"</td>";
		tbl +="<td>"+temp[id].maths+"</td>";
		tbl +="<td>"+temp[id].english+"</td>";
		tbl +="<td>"+temp[id].Average+"</td>";
		tbl +="<td>"+temp[id].Total+"</td>";
		tbl +="</tr>";
	}
	return tbl;
}
</script>
</head>

<body onLoad="getSort()">
<div id="divGrid"></div>
</body>
</html>