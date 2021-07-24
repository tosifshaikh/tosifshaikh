// JavaScript Document
xajax.config.defaultMode = 'synchronous';
var HeaderObj= new Object();
var FilterObj= new Object();
var FilterValObj= new Object();

var arrsubHeader = new Array();
var arrsubFilter = new Array();
var arrsubFilterVal = new Array();
var dataObj  = new Object();
var target = 'divGird';
var currentID=0;



var section='';

var startLimit=0;

var screenW = 640, screenH = 480;

if (parseInt(navigator.appVersion) > 3) {
 screenW = screen.width;
 screenH = screen.height;
}
else if (navigator.appName == "Netscape" 
    && parseInt(navigator.appVersion)==3
    && navigator.javaEnabled()
   )
{
 var jToolkit = java.awt.Toolkit.getDefaultToolkit();
 var jScreenSize = jToolkit.getScreenSize();
 screenW = jScreenSize.width;
 screenH = jScreenSize.height;
}


function spaceTrim(name)	//for prevent only blank space in input [-mec]
{return name.replace(/^\s+|\s+$/g, '');};

function objInit()
{
	var objField = new Array();
	objField['client'] = document.getElementById("selClients");
	objField['Add'] = document.getElementById("addBtn");
	objField['Edit'] = document.getElementById("editBtn");
  	objField['Delete'] = document.getElementById("deleteBtn");
  	objField['Save'] = document.getElementById("saveBtn");
 	objField['Reset'] = document.getElementById("resetBtn");
	objField['Act'] = document.getElementById("act");
	objField['ID'] = document.getElementById("id");
	objField['CAT_NAME'] = document.getElementById("category_name");		
	return objField;
}

function fnAdd()
{	
	var Field =  new Array();
	Field = objInit();
	if(Field['Add'])
	{
		Field['Add'].className = "disbutton";
		Field['Add'].disabled = 'disabled';
	}
	if(Field['Save'])
	{
		Field['Save'].className = "button";
		Field['Save'].disabled = '';
	}
	if(Field['client'] && Field['CAT_NAME'])
	{		
		Field['client'].disabled = '';
		Field['CAT_NAME'].value='';
		Field['CAT_NAME'].disabled = '';
	}
	Field['Act'].value = "Add";
	return false;	
}
function fnReset ()
{	
	var Field =  new Array();
	Field = objInit();
	if(Field['Add'])
	{
		Field['Add'].className = "button";
		Field['Add'].disabled = '';
	}
	if(Field['Save'])
	{
		Field['Save'].className = "disbutton";
		Field['Save'].disabled = 'disabled';
	}
	if(Field['Edit'])
	{	
		Field['Edit'].className = "disbutton";
		Field['Edit'].disabled = 'disabled';
	}
	if(Field['Delete'])
	{
		Field['Delete'].className = "disbutton";
		Field['Delete'].disabled = 'disabled';
	}
	if(Field['CAT_NAME'])
	{		
		Field['CAT_NAME'].value='';
		Field['CAT_NAME'].disabled = 'disabled';
	}
	if(Field['client'])
	{		
		Field['client'].disabled = '';		
	}		
	Field['Act'].value = "";
	Field['ID'].value = "";
	return false;	
}

function fnEdit()
{
	var Field =  new Array();
	Field = objInit();
	Field['Act'].value = "Edit";	
	if(Field['Add'])
	{
		Field['Add'].className = "disbutton";
		Field['Add'].disabled = 'disabled';
	}
	if(Field['Save'])
	{
		Field['Save'].className = "button";
		Field['Save'].disabled = '';
	}
	if(Field['Edit'])
	{	
		Field['Edit'].className = "disbutton";
		Field['Edit'].disabled = 'disabled';
	}
	if(Field['Delete'])
	{
		Field['Delete'].className = "disbutton";
		Field['Delete'].disabled = 'disabled';
	}
	if(Field['client'] && Field['CAT_NAME'])
	{		
		Field['client'].disabled = 'disabled';
		Field['CAT_NAME'].disabled = '';
	}
	Field['Act'].value = "Edit";	
	return false;
}
function fnDelete()
{
	var Field =  new Array();
	Field = objInit();
	
	if(confirm("Are you sure you want to delete this record?"))
	{
		xajax_DeleteCategory(Field['ID'].value);	
		fnReset();		
	}	
	return false;
}
function fnSave()
{	
	var Field =  new Array();
	Field = objInit();
	if(Field['client'].value == "0")
	{
		alert("Please Select Client.");
		return false;
	}
	if(Field['CAT_NAME'].value == "")
	{
		alert("Please Enter Category Name.");
		return false;
	}
	
	if(Field['Act'].value == "Add")
	{
		xajax_InsertCategory(xajax.getFormValues('frm'));
	}
	else if(Field['Act'].value == "Edit")
	{		
		xajax_UpdateCategory(xajax.getFormValues('frm'));			
	}	
	return true;
}
function Edit(rid,ctrlId)
{
	var Field =  new Array();
	Field = objInit();
	if(Field['Add'])
	{
		Field['Add'].className = "button";
		Field['Add'].disabled = '';
	}
	if(Field['Edit'])
	{
		Field['Edit'].className = "button";
		Field['Edit'].disabled = '';
	}
	if(Field['Delete'])
	{
		Field['Delete'].className = "button";
		Field['Delete'].disabled = '';
	}
	if(Field['Save'])
	{
		Field['Save'].className = "disbutton";
		Field['Save'].disabled = 'disabled';
	} 
	if(Field['client'])
	{		
		Field['client'].disabled = '';		
	}	
	document.getElementById("id").value = rid;			
	if(currentID!=""){
		if($("#TR_"+currentID)){
			$("#TR_"+currentID).css("backgroundColor","");
		}
	}
	$("#TR_"+rid).css({"backgroundColor":"#FFCC99","textAlign":"left","cursor":"pointer"});
	currentID= rid;	
	if($("#selClients"))
		$("#selClients").val(dataObj.RowData["_"+rid]['client_id']);
	if($("#category_name"))
		$("#category_name").val(dataObj.RowData["_"+rid]['category_name']);
}
function changeCombo(airId,flg)
{
	loadgrid(airId);		
}
function loadgrid(airId)
{
	HeaderObj= new Object();
	HeaderObj['Client_Name']="Client Name";
	HeaderObj['Category_Name']="Category Name";
	
	var selClients;
	if(document.getElementById('selClients').value == '')
	{
		selClients = airId;
	} 
	else 
	{
		selClients = document.getElementById('selClients').value;
	}
	for(hid in HeaderObj){
		FilterValObj[hid]=($("#fd_filter_"+hid).length>0 && $("#fd_filter_"+hid).val()!='')?$("#fd_filter_"+hid).val():"";	
	}
	var params="section=7&type=1&sub_section=1&act=GRID&client_id="+selClients;
	getLoadingCombo(1);	
	$.ajax({url: "airworthiness_master.php", async:false,type:"POST",data:params,success: function(data){
		dataObj = eval("("+data+")");		
		renderGrid();}});
}
function renderGrid()
{
	try
	{		
		var table = '<div  id="maintablewidth1"><table width="100%" cellspacing="1" cellpadding="3" border="0" class="tableContentBG" id="m_tablewidth" ></table></div>';
		table+='<table class="tableContentBG" width="100%" cellspacing="1" cellpadding="3" border="0">';
		
		table +=getHeaderRow();	
		table +=getFilterRow();	
		table +=getGridData();
		table +='</table>';
		table+='<table width="100%" cellspacing="1" cellpadding="3" border="0"><tr><td align="right" height="45" id="divBottomPagging"></td></tr></table>';
		$("#divGird").html(table);
		
		getLoadingCombo(0);
		//getPagging(startLimit,100,dataObj.TotalRow);	
	} catch(e) {
		alert(e);
	}
}
function getHeaderRow()
{
	var tempHeaderTable='';	
	tempHeaderTable+='<tr>';
	for(hid in HeaderObj){
		tempHeaderTable+='<td align="left" class="tableCotentTopBackground">'+HeaderObj[hid]+'</td>';	
	}	
	return tempHeaderTable+='</tr>';
}
function getFilterRow()
{
	var tempFilterTable = '';
	tempFilterTable+='<tr id="h_row4">';	
	for(hid in HeaderObj){
		var filterval
		tempFilterTable +='<td align="left" class="tableCotentTopBackground"><input type="text" class="gridinput" onkeyup="return filterGrid(event);" id=fd_filter_'+hid+' name=fd_filter_'+hid+' value="'+FilterValObj[hid]+'"></td>';			
	}
	return tempFilterTable +='</tr>';
}
function getGridData()
{
	var temprowTable = '';
	var i=0;
	var temp_client='';
	for(rid in dataObj.RowData){	
		if(FilterValObj['Client_Name']!='' && (dataObj.Client_Array[dataObj.RowData[rid]['client_id']].toLowerCase()).indexOf(FilterValObj['Client_Name'].toLowerCase())<0)
		{					
			continue;
		}
		if(FilterValObj['Category_Name']!='' && (dataObj.RowData[rid]['category_name'].toLowerCase()).indexOf(FilterValObj['Category_Name'].toLowerCase())<0)
		{					
			continue;
		}
		var class1 = (i%2==0)?"even":"odd";	
		
		if(temp_client=='' || dataObj.RowData[rid]['client_id']!=temp_client)
		{
			temprowTable += '<tr class="even">';
			temprowTable += '<td>'+dataObj.Client_Array[dataObj.RowData[rid]['client_id']]+'</td>';
			temprowTable += '<td></td>';
			temprowTable += '</tr>';
		}		
		temprowTable += '<tr class="'+class1+'" id="TR'+rid+'" onMouseDown="Edit('+rid.split("_")[1]+',this);" >';
		temprowTable += '<td></td>';
		temp_client=dataObj.RowData[rid]['client_id'];
		temprowTable += '<td>'+dataObj.RowData[rid]['category_name']+'</td>';	
		temprowTable += '</tr>';
		i++;		
	}
	return temprowTable;
}
function getLoadingCombo(flg)
{
	var elm =$("#LoadingDivCombo");
	if(flg ==1){
		elm.css({"width":"100%","height":"100%","display":""});
	} else {
		elm.css({"width":0,"height":0,"display":"none"});		
	}
}
function fnaudit()
{ 
	winWidth = 800; 
	winheight = 800;
	
	if (screen){ 
	   winWidth = screen.width;
	   winHeight = screen.height;
	}
	
	var Category_win1 = window.open("manage_audit_trail.php?adt=MASTER_ADT&sublinkId=62&title=Category Master",'Category_win1','height='+winHeight+',width='+winWidth+',scrollbars=yes,resizable=yes,left=50,fullscreen=yes');
	Category_win1.focus();
}
function getPagging(intCurrent, intLimit, intTotal)
{
	intPage = intCurrent / intLimit;
	intLastPage = Math.floor( parseFloat(intTotal) / parseFloat(intLimit));	
	var start=intCurrent;
	var cLimit = ((start+intLimit) > intTotal) ? intTotal : (start+intLimit);	
	strResult = '<div style="width:60%;float:right;"><div style="width:auto;float:left;" >';
	if(intTotal==0){
		strResult+='<strong> NO Result(s) Found.</strong>';
	}else{
	strResult+='<strong>'+(intCurrent+1)+' - '+cLimit+' of '+intTotal+'  Result(s) Found.</strong>';
	}
	strResult+='</div><div id="pagger" style="margin-right:5px;">';
	strResult+='<span><strong>Show Rows:</strong></span><span id="ShowHideLov"></span>&nbsp;&nbsp;&nbsp;';
	if(intCurrent != 0) // Not First Page
	{
		strResult += '<a href="#" onclick="paging('+(intCurrent-intLimit)+')">';
		strResult += '<span class="previous_next">&laquo; Previous</span></a>';
	}
	else
	{
		strResult += '<a href="#" class="disabled"><span class="previous_next">&laquo; Previous</span></a>';
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
		strResult += '<a href="#" onclick="paging('+(intCurrent+intLimit)+')">';
		strResult += '<span class="previous_next">Next &raquo;</span></a>';
	}
	else
	{
		strResult += '<a href="#" class="disabled"><span class="previous_next">Next &raquo;</span></a>';
	}
	strResult += '</div></div>';	
	document.getElementById("divTopPagging").innerHTML = strResult;	
	document.getElementById("divBottomPagging").innerHTML = strResult;	
}

function searchpage(page,limit,total,key)
{
	if(key==13)
	{
		page = page -1;
		if(page <= total)
		{
			paging(page*limit);
		}
		else
		{
			alert("Page limit exceeded..!!");
		}
		return false;
	}
 }
function paging(intStart)
{
		$("#act").val("");
	  startLimit = 	intStart;
	  loadgrid();	
}
function filterGrid(e)
{
	if(e.keyCode==13){
		loadgrid();
	}
}
