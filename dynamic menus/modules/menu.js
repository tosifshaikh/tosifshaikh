var tbl='',act='';
var dataArr=[];
var lf=0,pidVal=0,num=0,z=1;
$(function(){
	loadGrid();
});
function loadGrid()
{
	var table='';
	table +="<table width='100%' border=1 id=tbl>";
	renderGrid(0,1);
	table +=tbl;
	table +="</table>";
	document.getElementById('divGrid').innerHTML=table;
}

function renderGrid(id,z)
{
	var p=1;
	for(d in dataArr)
	{
		if(id==dataArr[d].pid)
		{
			if(dataArr[d].pid==0)
			{
				num=z;
			}else
			{
				num=z+"."+p;
			}
			tbl +='<tr id="tr'+dataArr[d].id+'">';
	
			for(var j=0;j<lf;j++)
			{
				tbl +="<td></td>";
			}
			tbl +=renderNode(d,num)
			tbl +='</tr>';	
			p++;
			lf++;
			renderGrid(dataArr[d].id,num);
			lf--;
			if(dataArr[d].pid==0)
			{
				z++;
				lf=0;
			}
					
		}
	}
}
function renderNode(indx,SrNo)
{
	var td="";
	var newValue=dataArr[indx].newValue,
	value=dataArr[indx].name,
	id=dataArr[indx].id;
	
	if(newValue==1)
	{
		dataArr[indx].srno=SrNo;
		td="<td>";
		td+="<span id='spn"+id+"'>"+SrNo+".&nbsp;&nbsp;<input  type='text' id='textName' value='"+value+"'></span>";
		td+="<input  type='button' id='btnDel"+id+"' value='DELETE'  onclick='deleteNode(this)'>";
		td+="<input  type='button' id='btnSave"+id+"' value='SAVE' onclick='saveNode()'>";		
		td+="</td>";
	}else
	{
		td="<td align='left'>";
		td+="<span id='spn"+id+"'>"+dataArr[indx].srno+".&nbsp;&nbsp;"+dataArr[indx].name+"</span>";
		td +="<input  type='button' id='btnadd"+id+"' value='ADD CHILD'  onclick='fnAddNode("+id+",this)' class='addBtn'>";
		//td +="<table width=100%>";
		//td +="<tr>";
	//	td +="<td align='left'>"+dataArr[indx].srno+"</td>";
		//td +="<td align='left'>"+dataArr[indx].name+"</td>";
		//td +="<td align='right'><input  type='button' id='btnadd"+id+"' value='ADD CHILD'  onclick='fnAddNode("+id+",this)' class='addBtn'></td>";
		//td +="</tr>";
		//td +="</table>";	
		td+="</td>";	
	}
		return td;
}
function deleteNode(ele)
{
	var value=ele.id.replace("btnDel","");
	for(d in dataArr)
	{
		if(dataArr[d].pid==value || dataArr[d].id==value)
		{
			delete dataArr[d];
		}
	}
	tbl='';
	lf=0,num=0,z=1;
	renderGrid(0,1);
	$("#tbl").html(tbl);
	/*if(document.getElementById('btnaddRoot').disabled){
	document.getElementById('btnaddRoot').disabled=false;
	}*/
	//document.getElementsByClassName('addBtn')[0].disabled=false;
	enabledisablebutton(false);
}
function saveNode()
{
	var nodename=document.getElementById('textName');
	if(nodename.value=='')
	{
		alert("Please enter Value.");
		return false;
	}
	updateNode(nodename.value,0);
	
	
}
function updateNode(name,newvalue)
{
	var lastindex=(dataArr.length-1);
	dataArr[lastindex].name=name;
	dataArr[lastindex].newValue=newvalue;
	tbl='';
		lf=0,num=0;
	//renderGrid(dataArr[lastindex].pid,dataArr[lastindex].srno);
	renderGrid(0,1);
	$("#tbl").html(tbl);
	//document.getElementById('btnSave'+dataArr[lastindex].id).disabled=false;
	//if(dataArr[lastindex].pid==0)
	{
		//document.getElementById('btnaddRoot').disabled=false;
	//	document.getElementsByClassName('addBtn')[0].disabled=false;
		enabledisablebutton(false)
	}
}
function addData(name,newvalue)
{
	//dataArr.push();
	dataArr[dataArr.length]={id:dataArr.length+1,name:name,pid:pidVal,newValue:newvalue}

}
var lastId='',currenid='';
function fnAddNode(pid,ele)
{
	//if(ele.id=='btnaddRoot')
	{
		
//}else
	//{
	}
	currenid=ele.id;
	
	act="ADD";
	pidVal=pid;
	addData('',1);
	tbl='';
		lf=0,num=0,z=1;
	renderGrid(0,1);
	$("#tbl").html(tbl);

enabledisablebutton(true)
	/*if(lastId!=currenid){
	if(document.getElementById(lastId)){
	document.getElementById(lastId).disabled=true;
	}
	if(document.getElementById(currenid)){
	document.getElementById(currenid).disabled=true;
	}
	}*/	lastId=ele.id

}
function enabledisablebutton(flg)
{
	var addBtnele=document.getElementsByClassName('addBtn'),len=addBtnele.length;
	for(var i=0;i<len;i++)
	{
		addBtnele[i].disabled=flg;
	}
	
}