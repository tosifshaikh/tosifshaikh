
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script src="jquery.js"></script>
<script type="text/javascript">
var obj={},xmlObj=[];
var l=0;
var tbl="";
$(function(){
requestXml();
})
function requestXml(){
	$.ajax({type:'POST',dataType:"xml",url:"xml.php",success: function(data){
	getXmlObj(data);
}});
}
function renderGrid(){
	tbl +="<table border=1 width='100%'>";
	dataPrint();
	tbl +="</table>";
	document.getElementById('div').innerHTML=tbl;
}
function dataPrint(indx){
	var indx=indx || 0;
	for(x in xmlObj){
		if(xmlObj[x].parent==indx){
			tbl +="<tr>";
			for(var i=0;i<l;i++){
				tbl +="<td></td>";
			}
			tbl +="<td>"+xmlObj[x].name+"</td>";
			tbl +="</tr>";
			l++;
			dataPrint(xmlObj[x].id);
			l--;
		}	
	}
}
function getXmlObj(xml){
	  var xmldoc=xml.getElementsByTagName('row');
	  var xx="";
	  for(var i=0,len=xmldoc.length;i<len;i++){
		  xx=xmldoc[i].children;
		  obj=[];
		   for(var j=0,l=xx.length;j<l;j++){
				obj[ xx[j].nodeName]= xx[j].firstChild.nodeValue;
			}
			xmlObj.push(obj);
	  }
		renderGrid();
}
</script>
</head>
<body>
<div id="div"></div>
</body>
</html>