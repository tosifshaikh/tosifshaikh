<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script src="jquery.js"></script>
<script>
var temp=[];
temp[0]={name:"def",physics:"210",maths:"11",english:'9',date:'30-10-2017'};
temp[1]={name:"abc",physics:"30",maths:"165",english:'79',date:'3-1-2017'};
temp[2]={name:"yre",physics:"65",maths:"23",english:'134',date:'13-10-2016'};
temp[3]={name:"tos",physics:"10",maths:"15",english:'98',date:'13-11-2016'};
temp[4]={name:"tos123",physics:"100",maths:"150",english:'9800',date:'20-2-2015'};
temp[5]={name:"123dos123",physics:"1000000",maths:"150",english:'9800',date:'8-1-2018'};
temp[6]={name:"Tos",physics:"10",maths:"15",english:'98',date:'13-10-2017'};
var headerArr=["name","physics","maths","english","Average","Total","date"];

var settingObj={
	initialSort:'asc',
	allowSort:1,
	sortType:'asc',
	sortArr:[]
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
		tbl +="<td id="+headerArr[i]+" class='sortclass'>"+headerArr[i]+"</td>";
	}
		tbl +="</tr>";
	return tbl;
}
//var sortArr=[];
var cid;
function getSort(id)
{
	
	
//	$("#divGrid").wrap("<div id='divGrid' class='abc1'></div>");
	doOperation();
	settingObj.sortArr=[];
	for(var i=0,len=temp.length;i<len;i++)
	{
		if(id && id!="" && settingObj.allowSort)
		{
			settingObj.sortArr.push(temp[i][id]+"##"+i);
		}else
		{
			settingObj.sortArr.push("##"+i);
		}
	}	
	if(settingObj.allowSort){
	if(id!=cid)
	{
		/*settingObj.sortArr.sort(function(a,b){ return doSort(a,b)});
		cid=id;
	    settingObj.sortType='desc';*/
	   settingObj.sortType=settingObj.initialSort;
		//getSorting(id);
	}
	else{
			/*if(settingObj.sortType=='asc')
			{
				settingObj.sortArr.sort(function(a,b){ return doSort(a,b)});
				 cid=id;
				 settingObj.sortType='desc';	
			}else if(settingObj.sortType=='desc')
			{
				settingObj.sortArr.sort(function(a,b){return doSort(a,b)});
				settingObj.sortArr.reverse();
				cid=id;
				settingObj.sortType='asc';
					
			}*/
			//getSorting(id);
	}	
	getSorting(id);
	}
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
	chk();
}
function getSorting(id)
{
	
	settingObj.sortArr.sort(function(a,b){ return doSort(a,b)});
	cid=id;
	switch(settingObj.sortType)
	{
		case 'asc':	
		 		settingObj.sortType='desc';
		break;
		case 'desc':
				settingObj.sortArr.reverse();
				settingObj.sortType='asc';
		break;
	}
	/*if(settingObj.sortType=='asc')
	{
		settingObj.sortArr.sort(function(a,b){ return doSort(a,b)});
		 cid=id;
		 settingObj.sortType='desc';	
	}else if(settingObj.sortType=='desc')
	{
		settingObj.sortArr.sort(function(a,b){return doSort(a,b)});
		settingObj.sortArr.reverse();
		cid=id;
		settingObj.sortType='asc';
			
	}*/
	
}
function doSort(a,b)
{
	a=a.split("##")[0];
	b=b.split("##")[0];
	
	/*
	for sorting dates
	var aa=a.split("-"),bb=b.split("-");
	return  new Date(bb[2]+"-"+bb[1]+"-"+bb[0]) - new Date(aa[2]+"-"+aa[1]+"-"+aa[0])
	*/;

	if(!isNaN(a))
		{
			a=parseInt(a),b=parseInt(b);
		}else{
			a=String(a).toLowerCase(),b=String(b).toLowerCase();
		}	
	return a > b? 1 : a< b ? -1 : 0;
	
/*	 if (date1 > date2) return 1;
  if (date1 < date2) return -1;
  return 0;*/
}
function displayGrid()
{
	var tbl="";
	for(var i=0,len=settingObj.sortArr.length;i<len;i++)
	{
		var p=settingObj.sortArr[i].split("##");
	    var id=p[1];
		tbl +="<tr>";
		tbl +="<td>"+temp[id].name+"</td>";
		tbl +="<td>"+temp[id].physics+"</td>";
		tbl +="<td>"+temp[id].maths+"</td>";
		tbl +="<td>"+temp[id].english+"</td>";
		tbl +="<td>"+temp[id].Average+"</td>";
		tbl +="<td>"+temp[id].Total+"</td>";
		tbl +="<td>"+temp[id].date+"</td>";
		tbl +="</tr>";
	}
	return tbl;
}
function chk()
{
	$(".sortclass").click(function(){
		alert(1)
		getSort(this.id)
		});
}
</script>
</head>

<body onLoad="getSort()">
<div id="divGrid" class='abc1'></div>
</body>
</html>