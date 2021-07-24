
// JavaScript Document
var xmlDoc ='';
var current_filter = "";
var arrHeader = new Array();
var arrFilter = new Array();
var arrFilterVal = new Array();



function loadGrid()
{
	
	try
	{
	arrHeader[0] = "Short Name";
	arrHeader[1] = "Description";	
	
	arrFilter[0] = "short_name";
	arrFilter[1] = "description";
	
	var filter = "";
	for(var i=0;i<arrFilter.length;i++)
	{
		var fd_filter='#fd_filter_'+arrFilter[i];
		
		if($(fd_filter).attr("value")!=undefined && $(fd_filter).attr("value")!="")
		{
			
			filter += "&"+arrFilter[i]+"="+$(fd_filter).attr("value");
		}
		arrFilterVal[i] = (($(fd_filter).attr("value")!=undefined) ? $(fd_filter).attr("value") : "");
	}
	
	if(filter != null)
	{
		var params = "section=8&type=1&sub_section=0&act=GRID"+filter;
	}
	else
	{
		var params = "section=8&type=1&sub_section=0&act=GRID";	
	}	

      
      $.getJSON("airworthiness_master.php",params,function( data ) {
    	xmlDoc=data;
		renderGrid(arrHeader,arrFilter,xmlDoc,"divGrid",arrFilterVal);
		setFilter(arrFilterVal);
	});


	
	}
	catch(err) {
		exc_error("loadGrid","section : Avitation Authority  <br/> ",err.toString(),err);
	}
}


function renderGrid(arrHeader,arrFilter,xmlDoc,target,arrFilterVal)
{
	try
	{
		for(i=0; i<arrFilter.length;i++)
		{
			var fd_filter="#fd_filter_"+arrFilter[i];
			arrFilterVal[i] = (($(fd_filter).attr("value")!=undefined) ? $(fd_filter).attr("value") : "");
		}
			$("#divGrid").html("");
		var tabl=$("<table/>").attr({ width:"100%", cellspacing:"1", cellpadding:"3", border:"0", class:"tableContentBG"});
		
		$("#divGrid").append(tabl);
	     var row=$("<tr/>");
	
		for(i=0; i<arrHeader.length;i++)
		{
		row.append($("<td/>").attr({align:"left", class:"tableCotentTopBackground"}).text(arrHeader[i]));
		}
		
		tabl.append(row);
		   var row1=$("<tr/>");
		for(i=0; i<arrFilter.length;i++)
    	{
		
		var fd_filter="fd_filter_"+arrFilter[i];
		row1.append($("<td/>").attr({align:"left", class:"tableCotentTopBackground"}).append($("<input/>").attr({type:"text" ,Value:arrFilterVal[i] ,name:fd_filter, id:fd_filter, onKeyup:"return filter(this.id,event);", class:"gridinput"})));
		
	}
	
    
	 tabl.append(row1);
	 
	 var Parent_id,Parent_etype,Parent_emanuf,Parent_airtype;
	 	var i=0;
		  var iFlg=1;
		for(id in xmlDoc.data)
		{
			
			var flags = 0;
			
			if(i%2 == 0){
				var clas = "odd";
			} else {
				var clas = "even";
			}
			i++;
			var csplit= id.split('_');
			fid=csplit[1];
			Parent_id = fid;
			
			short_name = xmlDoc.data[id].short_name;
			description =xmlDoc.data[id].description;
			
			if($("#fd_filter_short_name").attr("value")!=undefined || $("#fd_filter_description").attr("value")!=undefined){
				
				
				if((short_name).toLowerCase().indexOf(($("#fd_filter_short_name").val()).toLowerCase()) == -1){
					flags = 1;
				}
				
				if((description).toLowerCase().indexOf(($("#fd_filter_description").val()).toLowerCase()) == -1){
					flags = 1;
				}
			}
			if(flags == 0){
			var row=$("<tr/>").attr({class:clas, align:"left", onMouseover:"javascript:MouseOver(this.id);", onMousedown:"javascript:Edit("+Parent_id+",'"+Parent_id+"');" ,onMouseout:"javascript:MouseOut(this.id,"+Parent_id+");" ,onClick:"javascript:if(this.onmousedown){} else Edit("+Parent_id+",'"+Parent_id+"');", id:Parent_id});
				
				row.append($("<td/>").attr({width:"33%", height:"25"}).append("&nbsp;&nbsp;"+short_name));
				row.append($("<td/>").attr({width:"34%", height:"25"}).append("&nbsp;&nbsp;"+description));
				tabl.append(row);
				iFlg=0;
			
 		}
			
		}
		 if(iFlg == 1)
     	{
			var row=$("<tr/>").attr({class:"", align:"left", style:"cursor:pointer"});
		    row.append($("<td/>").attr({colspan:"13", align:"center"}).html("<strong>No Records Found</strong>"));
		    tabl.append(row);
	     }
	 
		$("#divGrid").html(tabl);
	}
	catch(err) {
		exc_error("renderGrid","section : ATA code <br/> ",err.toString(),err);
	}
}

function setFilter(val)
{
	try
	{
		if(current_filter != "")
		{
			var ctrl = $("#"+current_filter);
			var pos = ctrl.val().length;
			
			if(pos==0)
			{
				ctrl.focus();
			}
			else
			{
				ctrl.focus();
				ctrl.select();
			}
		}
	}
	catch(err) {
		exc_error("setFilter","section : ATA Codes  <br/> ",err.toString(),err);
	}
}
var currentId;

function clearForm()
{
	$("#short_name").val("");
	$("#description").val("");

}
function enableForm()
{
	$("#short_name").removeAttr("disabled");
	$("#description").removeAttr("disabled");
	
}
function disableForm()
{
	
	$("#short_name").attr("disabled","disabled");
	$("#description").attr("disabled","disabled");
}


function Edit(rid,ctrlId) 
{
	try
	{
   	
	 
	 recids=rid;
	 
	 $("#addBtn").attr("class","button");
	$("#addBtn").removeAttr("disabled");
	$("#editBtn").attr("class","button");
	$("#editBtn").removeAttr("disabled");
	$("#deleteBtn").attr("class","button");
	$("#deleteBtn").removeAttr("disabled");
	
	$("#saveBtn").attr("class","disbutton");
	$("#saveBtn").attr("disabled","disabled");
	 clearForm();
	 disableForm();	
	$("#id").val(rid);	
 
	if(currentId!="")
	{
		
		$("#"+currentId).css("background-color","#FFFFFF");
	}	
	if(elm.id != "") 
	{		
		elm.style.backgroundColor = "#FFCC99";
		elm.style.textAlign = "left";
		elm.style.cursor = "pointer";
	}
	currentId=ctrlId;
	xajax_SetForm(rid);
	}
	catch(err) {
		exc_error("Edit","section :  ATA code <br/> ",err.toString(),err);
	}
	
}

function fnAdd()
{
	clearForm();
	enableForm();
	
	$("#addBtn").attr("class","disbutton");
	$("#addBtn").attr("disabled","disabled");
	$("#editBtn").attr("class","disbutton");
	$("#editBtn").attr("disabled","disabled");
	
	$("#saveBtn").attr("class","button");
	$("#saveBtn").removeAttr("disabled");
	$("#act").val("ADD");
	return false;
}

function fnEdit()
{
	enableForm();
	
	$("#addBtn").attr("class","disbutton");
	$("#addBtn").attr("disabled","disabled");
	$("#editBtn").attr("class","disbutton");
	$("#editBtn").attr("disabled","disabled");
	$("#deleteBtn").attr("class","disbutton");
	$("#deleteBtn").attr("disabled","disabled");
	
	$("#saveBtn").attr("class","button");
	$("#saveBtn").removeAttr("disabled");
	$("#act").val("EDIT");
	return false;
}
function fnDelete()
{
	$("#deleteBtn").val("DELETE");
	if(confirm("Are you sure you want to delete this Aviation Authority?"))
	{
		xajax_Delete(xajax.getFormValues('frm'));
	}
	return false;
}
function fnSave()
{
	try
	{
	if($("#short_name").val() == '')
	{		
		alert('Please Enter Short Name.');
		$("#short_name").focus();
		return false;
	}
	
	var shortName = $("#short_name").val();
	var shortName_n = spaceTrim(shortName);
	if(shortName_n == '')
	{
		alert('Only Spaces are not allowed in Short Name. Please Enter Short Name. ');
		$("#short_name").focus();
		return false;
	}
	if($("#description").val() == '')
	{
		alert('Please Enter Description.');
		$("#description").focus();
		return false;
	}
	
	var description = $("#description").val();
	var description_n = spaceTrim(description);
	if(description_n == '')
	{
		alert('Only Spaces are not allowed in Description. Please Enter Description. ');
		$("#description").focus();
		return false;
	}
	
	if($("#act").val() == "ADD")
	{
		xajax_Save(xajax.getFormValues('frm'));
	}
	else if($("#act").val() == "EDIT")
	{
		xajax_Update(xajax.getFormValues('frm'));
	}
	
	return false;
	}
	catch(err) {
		exc_error("fnSave","section : ATA code  <br/> ",err.toString(),err);
	}
}
function fnReset()
{
	clearForm();
	disableForm();
	
	$("#addBtn").attr("class","button");
	$("#addBtn").removeAttr("disabled");
	$("#editBtn").attr("class","disbutton");
	$("#editBtn").attr("disabled","disabled");
	$("#deleteBtn").attr("class","disbutton");
	$("#deleteBtn").attr("disabled","disabled");
	$("#saveBtn").attr("class","disbutton");
	$("#saveBtn").attr("disabled","disabled");
	
	$("#resetBtn").attr("class","button");
	$("#act").val("");
	$("#id").val("");
	return false;
}
function filter(fid,e)
{
	current_filter=fid;
	if(e.keyCode == 13)
	{
		loadGrid();
	
		return false;
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
	
	var Avitation_win1 = window.open("manage_audit_trail.php?adt=MASTER_ADT&sublinkId=10279&title=Avitation Authority",'Avitation_win1','height='+winHeight+',width='+winWidth+',scrollbars=yes,resizable=yes,left=50,fullscreen=yes');
	Avitation_win1.focus();
}
function getLoadingCombo(flg)
{
    var elm = $("#LoadingDivCombo");
	  
		if(flg==1)
		{
			elm.css("width",'100%');
			elm.css("height",'100%');
			elm.show();
		}
		else
		{
			elm.css("width",'0');
			elm.css("height",'0');
			elm.hide();
		}
}

function exc_error(FunctionName,msg,ErrorString,ErrorArray)
{
	xajax_excJsError(FunctionName,msg,ErrorString,ErrorArray);
	
	var tabl=$("<table/>").attr({width:"100%", cellspacing:"1", cellpadding:"3", border:"0", class:"tableContentBG"});
	var row=$("<tr/>");
	row.append($("<td/>").attr({colspan:"11", align:"center"}).html("<strong>There is an issue in fetching record. Please Contact Administrator for further assistance.</strong>"));
	tabl.append(row);
	$("#"+target).html(tabl);
}

function spaceTrim(name)
{return name.replace(/^\s+|\s+$/g, '');};