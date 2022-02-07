<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<script src="../js/jquery.js"></script>
<script>
var currentId=0;
/*{
		1:"C:\\xampp\\htdocs\\textchanger",
		2:"F:\\test\\SIT",
		3:"F:\\test\\UAT",
		4:"F:\\test\\LIVE",
		}*/
var loadComb={
	combArr:["LOCAL","SIT","UAT","LIVE"],
	selIdArr:["comb1","comb2"],
	mainHeader:"<font><strong>Path Changer</strong></font>",
	assignPath:function()
	{
	document.getElementById('selpath').length=0;
			for(c in this.pathObj)
		{
			var opt=document.createElement('option');
			opt.text=this.pathObj[c];
			opt.value=c;
			document.getElementById('selpath').appendChild(opt);
		}
	},
	dynamicComb:function()
	{
		this.headerFunction();console.log("test")
		for(var i=0,sellen=this.selIdArr.length;i<sellen;i++)
		{
			var sel=document.createElement('select');
			sel.options.length=0;
			sel.id="sel"+i;
			sel.name="sel"+i;
			if(i==1)
			{
				sel.setAttribute("onchange", "changer()");
			}
			var opt=document.createElement('option');
				opt.text="[SELECT]";
				opt.value=0;
				sel.appendChild(opt);
			for(var j=0,len=this.combArr.length;j<len;j++)
			{
				
				var opt=document.createElement('option');
				opt.text=this.combArr[j];
				opt.value=(j+1);
				sel.appendChild(opt);
			}
			
			document.getElementById(this.selIdArr[i]).appendChild(sel);
		}
		this.assignPath();
	},
	headerFunction:function()
	{
		document.getElementById('mainHead').innerHTML=this.mainHeader;
	}
	};


$(function(){
	resetFn();
	loadGrid();
		
		
	});
function changer()
{
	var selval1=loadComb.pathObj[document.getElementById('sel1').value];
	var selval0=loadComb.pathObj[document.getElementById('sel0').value];
	var txtarea=document.getElementById('txtarea');
	if(txtarea.value){
			replaceString(txtarea.value,selval0,selval1);
	}
}//C:\xampp\htdocs\textchanger\test1\test.txt
function replaceString(str,findText,replaceWith)
{
	var findStr=str.split(findText).join(replaceWith);
	document.getElementById('txtarea').value=findStr;
}
function removeDuplicate()
{
	var txtarea=document.getElementById('txtarea');
	var msg="No String To Remove";
	var textVal=$.trim(txtarea.value);
	if(textVal)
	{
		//var re = /^(.*)(\r?\n\1)+$/gm;
		//var s = txtarea.value.replace(re, "$1");
		var tempArr=[];
		var s=txtarea.value.split("\n");
		//s=$.unique(s);	console.log(s)
		for(var i=0,len=s.length;i<len;i++)
		{
				if(s[i]!="" && $.inArray(s[i].trim(),tempArr)==-1){
				tempArr.push(s[i].trim());
				}
		}console.log(tempArr);
		txtarea.value=tempArr.join("\n");
		msg="Duplicate String Removed Successfully."
	}
	document.getElementById('msg').innerHTML=msg;
}
function getZip()
{
	if(document.getElementById('txtarea').value=="")
	{
		alert("Please Enter Text");
		return false;
	}
	if(document.getElementById('sel0').value=="0")
	{
		alert("Please Select From Path");
		return false;
	}
	if(document.getElementById("act"))
	{
		document.getElementById("act").value="";
	}
	var frm=document.createElement("form");
	frm.action="controller.php";
	frm.method="post";
	frm.id="frm";
	frm.name="frm";
	document.body.appendChild(frm)
	var hdn=document.createElement('input');
	hdn.id="act";
	hdn.name="act";
	hdn.value="getZip";
	frm.appendChild(hdn);
	var hdn=document.createElement('input');
	hdn.id="sel0";
	hdn.name="sel0";
	hdn.value=document.getElementById('sel0').value;
	frm.appendChild(hdn);
	var hdn=document.createElement('input');
	hdn.id="workitem";
	hdn.name="workitem";
	hdn.value=document.getElementById('workitem').value;
	frm.appendChild(hdn);
	var hdn=document.createElement('input');
	hdn.id="txtarea";
	hdn.name="txtarea";
	hdn.value=document.getElementById('txtarea').value;
	frm.appendChild(hdn);
	
	frm.submit();
}
function resetFn()
{
	document.getElementById('txtpath').value='';
	document.getElementById('btnAdd').disabled=false;
	document.getElementById('btnsave').disabled=true;
	document.getElementById('btnedit').disabled=true;
	document.getElementById('btndel').disabled=true;
	document.getElementById('txtpath').disabled=true;
}
function loadGrid()
{
	$.ajax({type:'POST',url:'controller.php',data:{act:"getdata"},dataType:"json",success: function(data){
		loadComb.pathObj=data;
		loadComb.dynamicComb();
		}});
}
function savePath()
{
		
		var tempArr={};
		tempArr["oldpath"]="";
		tempArr["newpath"]="";
		if(document.getElementById('selpath').value!="")
		{
		tempArr["act"]="EDIT";
		tempArr["selIndex"]=document.getElementById('selpath').value;
		tempArr["newpath"]=document.getElementById('txtpath').value;
		tempArr["oldpath"]=document.getElementById('selpath').options[document.getElementById('selpath').selectedIndex].text;
		}
		else
		{
			tempArr["act"]="ADD";
			tempArr["newpath"]=document.getElementById('txtpath').value;
		}
		if(tempArr["oldpath"]!=tempArr["newpath"])
		{
		$.ajax({type:'POST',url:'controller.php',data:{act:"data",data:tempArr},dataType:"json",success: function(data){
		loadComb.pathObj=data.path;
		loadComb.assignPath();
		}});
		}
	resetFn();	
	document.getElementById('btnAdd').disabled=false;
	document.getElementById('btnsave').disabled=true;
	document.getElementById('btndel').disabled=true;
	document.getElementById('btnedit').disabled=true;
}
var globalVal=0;
function pathVal()
{
		globalVal=0;
		document.getElementById('txtpath').disabled=true;
		document.getElementById('btnedit').disabled=false;
		document.getElementById('btnAdd').disabled=true;
		document.getElementById('btnsave').disabled=true;
		document.getElementById('btndel').disabled=false;
		document.getElementById('txtpath').value=document.getElementById('selpath').options[document.getElementById('selpath').selectedIndex].text;
	globalVal=document.getElementById('selpath').value;
		{
		}
}
function addPath()
{
	document.getElementById('txtpath').disabled=false;
	document.getElementById('btnAdd').disabled=true;
	document.getElementById('btnsave').disabled=false;
	document.getElementById('btndel').disabled=true;
}
function edit()
{
	document.getElementById('txtpath').disabled=false;
	document.getElementById('btnedit').disabled=true;
	document.getElementById('btnsave').disabled=false;
	document.getElementById('btndel').disabled=false;
}
function del()
{
	if(globalVal)
	{
		$.ajax({type:'POST',url:'controller.php',data:{act:"del",id:globalVal},dataType:"json",success: function(data){
		loadComb.pathObj=data.path;
		loadComb.assignPath();
		}});
		resetFn();	
		document.getElementById('btnAdd').disabled=false;
		document.getElementById('btnsave').disabled=true;
		document.getElementById('btndel').disabled=true;
		document.getElementById('btnedit').disabled=true;
	}
}
</script>
</head>

<body>
<table align="center" width="50%" border="0">

<tr><td id="mainHead" align="center" colspan="2"></td></tr>
<tr>
<td colspan="2"> 
	<table  cellspacing="5" cellpadding="5">
    <tr>
        <td align="left">From :</td>
        <td id="comb1" align="left"></td>
        <td align="left">To :</td>
          <td id="comb2" align="left"></td>
            <td align="left"><input type="text" name="workitem" id="workitem" ></td>
            <td align="left"><input type="button" value="Zip folder" id="getzip" onClick="getZip()" name="btnSub" ></td>
             <td align="left"><input type="button" value="Remove Duplicate" id="btnDuplicate" onClick="removeDuplicate()"></td>
                <td align="left"><input type="button" value="Reset" id="btnReset" onClick="resetFn()"></td>
    </tr>
    </table>
</td>

</tr>
<tr>
<td colspan="2"><table><tr><td>Enter Path:</td><td align="left"><input type="text" name="txtpath" id="txtpath" style="width:320px" disabled></td><td><input  id="btnAdd" name="btnAdd" type="button" onClick="addPath()" value="Add"></td><td><input  id="btnedit" name="btnedit" type="button" onClick="edit()" value="Edit" disabled></td><td><input  id="btnsave" name="btnsave" type="button" onClick="savePath()" value="Save" disabled></td><td><input  id="btndel" name="btndel" type="button" onClick="del()" value="Delete" disabled></td></tr></table></td>
</tr>
<tr>
<td valign="top"><textarea id="txtarea" name="txtarea" cols="100" rows="15" placeholder="Enter Text Here" onFocus="document.getElementById('txtarea').value.trim()"></textarea></td>
<td valign="top"><select id="selpath" name="selpath"  size ="14" style="height:100%;width:300px; overflow:hidden;" onChange="pathVal()"></select></td>
</tr>
<tr><td colspan="2" id="msg"></td></tr>

</table>
</body>

</html>