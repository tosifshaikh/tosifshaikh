<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script>

function loadGrid()
{
	var amt=document.getElementById('txtVal').value;
	var table="";
	table +="<table>";
	var New_Val=0;
	var  value=parseInt(amt)/1000;
	if(value!=0)
	{
		New_Val=parseInt(value)*1000;
	}
	amt=amt-New_Val;
	table +="<tr>";
	table +="<td>1000</td><td>X</td><td>"+parseInt(value)+"</td><td>=</td><td>"+New_Val+"</td>";	
	table +="</tr>";
	
	var New_Val=0;
	var value=parseInt(amt)/500;
	if(value!=0)
	{
		New_Val=parseInt(value)*500;
	}
	amt=amt-New_Val;
	table +="<tr>";
	table +="<td>500</td><td>X</td><td>"+parseInt(value)+"</td><td>=</td><td>"+New_Val+"</td>";	
	table +="</tr>";
	var New_Val=0;
	var value=parseInt(amt)/100;
	if(value!=0)
	{
		New_Val=parseInt(value)*100;
	}
	amt=amt-New_Val;
	table +="<tr>";
	table +="<td>100</td><td>X</td><td>"+parseInt(value)+"</td><td>=</td><td>"+New_Val+"</td>";	
	table +="</tr>";
	var New_Val=0;
	var value=parseInt(amt)/50;
	if(value!=0)
	{
		New_Val=parseInt(value)*50;
	}
	amt=amt-New_Val;
	
	table +="<tr>";
	table +="<td>50</td><td>X</td><td>"+parseInt(value)+"</td><td>=</td><td>"+New_Val+"</td>";	
	table +="</tr>";
	var New_Val=0;
	var value=parseInt(amt)/20;
	if(value!=0)
	{
		New_Val=parseInt(value)*20;
	}
	amt=amt-New_Val;
	table +="<tr>";
	table +="<td>20</td><td>X</td><td>"+parseInt(value)+"</td><td>=</td><td>"+New_Val+"</td>";
	table +="</tr>";
	var New_Val=0;
	var value=parseInt(amt)/10;
	if(value!=0)
	{
		New_Val=parseInt(value)*10;
	}
	amt=amt-New_Val;
	table +="<tr>";
	table +="<td>10</td><td>X</td><td>"+parseInt(value)+"</td><td>=</td><td>"+New_Val+"</td>";
	table +="</tr>";
	var New_Val=0;
	var value=parseInt(amt)/5;
	if(value!=0)
	{
		New_Val=parseInt(value)*5;
	}
	amt=amt-New_Val;
	table +="<tr>";
	table +="<td>5</td><td>X</td><td>"+parseInt(value)+"</td><td>=</td><td>"+New_Val+"</td>";
	table +="</tr>";
		var New_Val=0;
	var value=parseInt(amt)/2;
	if(value!=0)
	{
		New_Val=parseInt(value)*2;
	}
	amt=amt-New_Val;
	table +="<tr>";
	table +="<td>2</td><td>X</td><td>"+parseInt(value)+"</td><td>=</td><td>"+New_Val+"</td>";
	table +="</tr>";
		var New_Val=0;
	var value=parseInt(amt)/1;
	if(value!=0)
	{
		New_Val=parseInt(value)*1;
	}
	amt=amt-New_Val;
	table +="<tr>";
	table +="<td>1</td><td>X</td><td>"+parseInt(value)+"</td><td>=</td><td>"+New_Val+"</td>";
	table +="</tr>";
	table +="<tr>";
	table +="<td>-</td><td>-</td><td>-</td>";	
	table +="</tr>";
	table +="<tr>";
	table +="<td></td><td></td><td>Total:"+document.getElementById('txtVal').value+"</td>";	
	table +="</tr>";
	table +="</table>";
	document.getElementById("divGrid").innerHTML=table;
}
</script>
</head>

<body onLoad="loadGrid()">
<input  type="text" id="txtVal" name="txtVal">
<input type="button" onClick="loadGrid()" value="Count">
<div id="divGrid"></div>
</body>
</html>