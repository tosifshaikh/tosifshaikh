<?php 
	$arr=array();
	$arr["vaa"][html_entity_decode("engineering&nbsp;&raquo;&nbsp;test")][]="task 1";
	$arr["vaa"]["engineering"][]="task 2";
	$arr["vaa"][html_entity_decode("engineering&nbsp;&raquo;&nbsp;test1&nbsp;&raquo;&nbsp;test3")][]="task 3";
	$arr["cathay"]["engineering1 engineering11"][]="task 1";
	$arr["cathay"]["engineering2"][]="task 6";
	$arr["jetstar"]["engineering3"][]="task 11";
	$arr["jetstar"]["engineering3"][]="task 1";
	
	//print "<pre>";
	//print_r($arr);
	//$arr1=json_encode($arr);
 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<style>
.expn
{
	background:url(Plus.png);
}
.cont
{ display: inline-block;
	background-image:url(Minus.png);
	height:30px;
	width:30px;
}
</style>
<script src="jquery.js"></script>
<script>
var obj=<?php echo json_encode($arr); ?>;
var header=["client","area","department"];
var filterArr=["fd_client","fd_area","fd_department"];

$(function(){
	loadGrid();
	
	});
function getHeader()
{
	var head='';
	head +="<tr>";
	for(h in header)
	{
		head +="<td>";
		head +=header[h];
		head +="</td>";
	}
	head +="</tr>";
	return head;
}
function getFilter()
{
	var filter='';
	filter +="<tr>";
	for(f in filterArr)
	{
		filter +="<td>";
		var val=(document.getElementById(filterArr[f]) && document.getElementById(filterArr[f]).value!="")?document.getElementById(filterArr[f]).value:'';
		filter +="<input type='text' id="+filterArr[f]+"  onkeydown='filter(event,\""+filterArr[f]+"\")'  value='"+val+"'>"  ;
		filter +="</td>";
	}
	filter +="</tr>";
	return filter;
}
var filterobj={}
function filter(e,id)
{
	if(e.keyCode==13)
	{
		//if(document.getElementById(id) && document.getElementById(id).value!="")
//		{
//		filterobj[id]=document.getElementById(id).value;
//		
//		}else
//		{
//		}
		loadGrid();
	}
	
}
function renderGrid()
{
	var tbl="",clientStr="";
	
	for(o in obj)
	{
		  var flg=1;
		  var client=(document.getElementById("fd_client") &&document.getElementById("fd_client").value!="")?document.getElementById("fd_client").value:""
		  if(client!="" && (o.toLowerCase().indexOf(client.toLowerCase())==-1))
		  {
			  flg=0;
		  }
		if(flg==1)
		{
				clientStr="";
				clientStr +="<tr>";
				clientStr +="<td><img src='Minus.png' width='20px' height='20px' id='imglog_"+o+"'>&nbsp;&nbsp;&nbsp;"+o+"</td>";
				clientStr +="</tr>";
				
				var table2="",areaStr="",a=0;
				for(i in obj[o])
				{
					var areaflg=1;
				
					var fd_area=(document.getElementById("fd_area")  && document.getElementById("fd_area").value!="")?document.getElementById("fd_area").value:""
				console.log(i.includes(fd_area),i,fd_area);
					if(fd_area!="" && (i.toLowerCase().indexOf(fd_area.toLowerCase())==-1))
					{
						areaflg=0;
					}
				if(areaflg==1)	
				{	
						areaStr="";
						areaStr +="<tr class='log_"+o+"_"+a+"'>";
						areaStr +="<td></td>";
						areaStr +="<td>"+i+"</td>";
						areaStr +="</tr>";
					
					var table3="",deptStr="",l=0;
					for(ii in obj[o][i])
					{
						var deptflg=1;
						
						var fd_department=(document.getElementById("fd_department") && document.getElementById("fd_department").value!="")? document.getElementById("fd_department").value:""
						if(fd_department!="" && (obj[o][i][ii].toLowerCase().indexOf(fd_department.toLowerCase())==-1))
						{
							deptflg=0;
						}
						if(deptflg==1)
						{
								deptStr="";
								deptStr +="<tr id='log_"+a+"_"+l+"'>";
								deptStr +="<td></td>";
								deptStr +="<td></td>";
								deptStr +="<td>"+obj[o][i][ii]+"</td>";
								deptStr +="</tr>";
								if(deptStr!="")
								{
								   table3 +=deptStr;
								  
								} l++;
						}
					}
				
					if(table3!="")
					{
						table2 +=areaStr+table3;
					}
						a++;
			    }	
			}
				if(table2!="")
			{
				tbl +=clientStr+table2;
			}
		}
	}
	
	return tbl;
}
function loadGrid()
{
	var table="";
	table +="<table border=1 width=100%>";
	table +=getHeader();
	table +=getFilter();
	table +=renderGrid();
	table +="</table>";
	document.getElementById('div').innerHTML=table;
	Chkinit();
}
var Chkinit=function(){
	
	$("[id ^=imglog_]").click(function(){
	hideshowcheck(this.id,'log');
});
}
function hideshowcheck(ele,str)
{
	var splitId=ele.split("_");
	var strId=str+"_"+splitId[1];console.log(splitId)
   if($("#"+strId).css("display")=='none')
   {
	$("[id ^="+strId+"]").show();
	$("#imglog_"+splitId[1]).attr("src","Minus.png");
   }
	else
	{
		$("[id ^="+strId+"]").hide();
		$("#imglog_"+splitId[1]).attr("src","Plus.png");
	}
	hideshowcheck(ele,str)
}
</script>
</head>

<body>
<div id="div"></div>
</body>
</html>