<?php
/*$conn=mysqli_connect('localhost','root','','test');
$sql="SELECT * FROM tbl_folder";
$result=$conn->query($sql);
$array=array();
while($row=$result->fetch_assoc())
{
	$array[$row['id']]=array("name"=>$row['foldername']);
}*/
for($i=0;$i<30;$i++)
{
	$array[($i+100)]=array("name"=>'folder'.$i);
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script src="../js/jquery.js"></script>
<script>
var data=JSON.parse('<?php echo json_encode($array); ?>');
var tbl='';
var dataArr=[];
var pagObj={
		perPageRec:4,
		initial:0,
	}
$(function(){
	loadGrid();
	
	});
function getData()
{
	dataArr=[];
	for(i in data)
	{
		dataArr.push(data[i]);
	}
	pagObj.RecordLength=dataArr.length;
	//loadGrid();
}
function loadGrid()
{
	pagObj.limit=pagObj.perPageRec;
	var tbl="";
		tbl +="<div id='divTopPagging'></div>";
	tbl +="<table id='tbl'>";
	tbl+=render();
	
	tbl +="</table>";
	document.getElementById('jsonDiv').innerHTML=tbl;
	getPagging(pagObj.initial,pagObj.limit,pagObj.RecordLength);
}
function render()
{
	var tr='';	
	getData();//console.log(dataArr,pagObj);
	 for (var ii =pagObj.initial ; ii < pagObj.limit; ii++) 
	{
		if(dataArr[ii]){
		tr +="<tr>";
		tr +="<td>"+dataArr[ii]['name']+"</td>";
		tr +="</tr>";
		}
	}
	var totPages=Math.ceil(pagObj.RecordLength/pagObj.perPageRec);

	tr+="<tr><td><input type='button' id='btnPre' value='previous' onclick='previous()'><input type='button' id='btnNxt' value='next'  onclick='next()'></td></tr>";
	return tr;
}
function previous()
{
	var pre=pagObj.limit-(2*pagObj.perPageRec);
	if(pre>=0) {
	pagObj.limit = pagObj.limit-pagObj.perPageRec;
	pagObj.initial=pre;
	 gettable();
	}
}
function gettable()
{
	$("#tbl").empty();
		$("#tbl").html(render());
}
function next()
{
	var next=pagObj.limit;
	if(next<pagObj.RecordLength)
	{
		pagObj.limit =pagObj.limit+pagObj.perPageRec;
		pagObj.initial=next;
		 gettable();
	}
}
function paging(initStart)
{
	console.log(pagObj.initial,pagObj.limit,initStart,"before");
	/*if(pagObj.initial>initStart)
	{
			console.log("in1");
			pagObj.limit =pagObj.limit-(2*pagObj.perPageRec);
			pagObj.initial=initStart;
	}else*/
	{
		console.log("in2");
		pagObj.limit =pagObj.perPageRec+initStart;
		pagObj.initial=initStart;
	}
	console.log(pagObj.initial,pagObj.limit,initStart,"after");
	
	
	 gettable();
}
function getPagging(intCurrent, intLimit, intTotal)
{
	intPage = intCurrent / intLimit;
	intLastPage = Math.floor( parseFloat(intTotal) / parseFloat(intLimit));
	
	strResult = '<div id="pagger">';
//	if(intCurrent != 0) // Not First Page
	{
		strResult += '<a href="#" onclick="previous('+(intCurrent-intLimit)+')">';
		strResult += '<span class="previous_next">&laquo; Previous</span></a>';
	}
//	else
	{
		//strResult += '<a href="#" class="disabled"><span class="previous_next">&laquo; Previous</span></a>';
	}
	
	if(intLastPage < 6) // Total pages less then 7
	{
		for(i=0;i<intTotal;i+=intLimit)
		{
			if((i/intLimit) == intPage)
			{
				strResult += '<a href="#" class="selected">';
				strResult += ((i / intLimit)+1);
				strResult += '</a>';
			}
			else
			{
				strResult += '<a href="#" onclick="paging('+i+')">';
				strResult += ((i/intLimit)+1);
				strResult += '</a>';
			}
		}
	}
	else if(intPage < 6) // Current Page From First 6 Pages
	{
		for(i=0;i<((intPage+2)*intLimit);i+=intLimit)
		{
			if((i/intLimit) == intPage)
			{
				strResult += '<a href="#" class="selected">';
				strResult += ((i/intLimit)+1);
				strResult += '</a>';
			}
			else
			{
				strResult += '<a href="#" onclick="paging('+i+')">';
				strResult += ((i/intLimit)+1);
				strResult += '</a>';
			}
		}
		strResult += '<input id="searchtext1" type="text" ';
		strResult += 'onkeypress="return searchpage(this.value,'+intLimit+','+intLastPage+',event.keyCode);" />';
		for(i=((intLastPage*intLimit) - intLimit);i<=intTotal;i+=intLimit)
		{
			strResult += '<a href="#" onclick="paging('+i+')">';
			strResult += ((i/intLimit)+1);
			strResult += '</a>';
		}
	}
	else if(intPage > (intLastPage - 5)) // Current Page From Last 6 Pages
	{
		for(i=0;i<(2*intLimit);i+=intLimit)
		{
			if((i/intLimit) == intPage)
			{
				strResult += '<a href="#" class="selected">';
				strResult += ((i/intLimit)+1);
				strResult += '</a>';
			}
			else
			{
				strResult += '<a href="#" onclick="paging('+i+')">';
				strResult += ((i/intLimit)+1);
				strResult += '</a>';
			}
		}
		strResult += '<input id="searchtext1" type="text" ';
		strResult += 'onkeypress="return searchpage(this.value,'+intLimit+','+intLastPage+',event.keyCode);" />';
		for(i=((intPage-2)*intLimit);i<intTotal;i+=intLimit)
		{
			if((i/intLimit) == intPage)
			{
				strResult += '<a href="#" class="selected">';
				strResult += ((i/intLimit)+1);
				strResult += '</a>';
			}
			else
			{
				strResult += '<a href="#" onclick="paging('+i+')">';
				strResult += ((i/intLimit)+1);
				strResult += '</a>';
			}
		}
	}
	else if(intPage <= (intLastPage - 5)) // Current Page Between First 6 Pages and Last 6 Pages
	{
		for(i=0;i<(2*intLimit);i+=intLimit)
		{
			strResult += '<a href="#" onclick="paging('+i+')">';
			strResult += ((i/intLimit)+1);
			strResult += '</a>';
		}
		strResult += '<input id="searchtext1" type="text" ';
		strResult += 'onkeypress="return searchpage(this.value,'+intLimit+','+intLastPage+',event.keyCode);" />';
		for(i=((intPage-2)*intLimit);i<((intPage+2)*intLimit);i+=intLimit)
		{
			if((i/intLimit) == intPage)
			{
				strResult += '<a href="#" class="selected">';
				strResult += ((i/intLimit)+1);
				strResult += '</a>';
			}
			else
			{
				strResult += '<a href="#" onclick="paging('+i+')">';
				strResult += ((i/intLimit)+1);
				strResult += '</a>';
			}
		}
		strResult += '<input id="searchtext2" type="text" ';
		strResult += 'onkeypress="return searchpage(this.value,'+intLimit+','+intLastPage+',event.keyCode);" />';
		for(i=((intLastPage*intLimit) - intLimit);i<intTotal;i+=intLimit)
		{
			strResult += '<a href="#" onclick="paging('+i+')">';
			strResult += ((i/intLimit)+1);
			strResult += '</a>';
		}
	}
	
	if((intCurrent+intLimit) < intTotal) // Not First Page
	{
		strResult += '<a href="#" onclick="next('+(intCurrent+intLimit)+')">';
		strResult += '<span class="previous_next">Next &raquo;</span></a>';
	}
	else
	{
		strResult += '<a href="#" class="disabled"><span class="previous_next">Next &raquo;</span></a>';
	}
	strResult += '</div>';
	
	document.getElementById("divTopPagging").innerHTML = strResult;
	//document.getElementById("divBottomPagging").innerHTML = strResult;
}
</script>
</head>

<body>
<div id="jsonDiv"></div>
</body>
</html>