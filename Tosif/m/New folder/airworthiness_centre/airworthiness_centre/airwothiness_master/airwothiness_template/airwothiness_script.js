// JavaScript Document
xajax.config.defaultMode = 'synchronous';

var HeaderObj= new Object();
var FilterObj= new Object();
var FilterValObj= new Object();

var target = 'divGrid';
var buttonShow = 0;
var xmlDoc ='';
var from = '';
var checkTemp = 0;
var template_id = 0;
var checkCat = 0;
var category_id=0;
var section='';


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


function changeCombo(airId)
{
	if($("#SelTemType"))
	{
		$("#SelTemType").val(0);
		$("#SelTemType").attr('disabled','disabled');		
	}
	if($("#tab_name") && $("#tab_name").val()!=0)
	{
		$("#tab_name").val(0);
		$("#tab_name").attr('disabled','disabled');		
	}
	if(airId!=0)
	{
		if($("#tempTypeID"))
			$("#tempTypeID").attr('disabled','');		
		if($("#SelTemType"))	
			$("#SelTemType").attr('disabled','');			
	}
	else
	{
		if($("#SelTemType"))
			$("#SelTemType").attr('disabled','disabled');
	}	
	loadgrid();
	if($("#ddl_template"))
	{
		$("#ddl_template").val(0);
		$("#ddl_template").attr('disabled','disabled');
	}
	fnReset();
}
function GetData(TempType,flg,template_id)
{
	//showHideButtons(true);
	if(TempType!=0)
	{	
		if($("#SelTemType").val()!=1)
		{
			if($("#btnMngStatusList").length>0){
				$("#btnMngStatusList").attr('class','disbutton');
				$("#btnMngStatusList").attr('disabled','disabled');
			}
			if($("#btnMngStatusWorkList").length>0){
					$("#btnMngStatusWorkList").attr('class','disbutton');
					$("#btnMngStatusWorkList").attr('disabled','disabled');
			}
			if($("#btnReorderBtn").length>0){
					$("#btnReorderBtn").attr('class','disbutton');
					$("#btnReorderBtn").attr('disabled','disabled');
			}
		}
		if($("#fd_filter_title"))
			$("#fd_filter_title").val('');
		if($("#tdTemplate"))	
			$("#tdTemplate").attr('style','display:;');
			
		var airId=$("#selClients").val();	
		xajax_set_ddlManufacturer(airId,TempType,template_id); 
		//if($("#SelTemType") && $("#SelTemType")==1)
			xajax_set_Category(airId,'');
		loadgrid();
	}
	else
	{
		var StrLov = '';
		StrLov += '<strong>Select/ Create Template:<b class="red_font_small">*</b>&nbsp;</strong>';
		StrLov += '<select disabled="disabled" tabindex="2" id="ddl_template" name="ddl_template">';
		StrLov += '<option value="0">[Select Template]</option>';
		StrLov += '</select>';
		$("#tdTemplate").html(StrLov);
		fnReset();
	}
}
function fn_template_change(templateVal)
{
	resetCSCombo(0);
	if($("#hdn_template_id"))
		$("#hdn_template_id").val(templateVal);	
	if($("#template_title"))
		$("#template_title").val('');	
		
	if($("#btnMngStatusList").length>0){
			$("#btnMngStatusList").attr('class','disbutton');
			$("#btnMngStatusList").attr('disabled','disabled');
	}
	if($("#btnMngStatusWorkList").length>0){
		$("#btnMngStatusWorkList").attr('class','disbutton');
		$("#btnMngStatusWorkList").attr('disabled','disabled');
	}
	if($("#btnReorderBtn").length>0){
		$("#btnReorderBtn").attr('class','disbutton');
		$("#btnReorderBtn").attr('disabled','disabled');
	}
	if(templateVal=='add_temp')
	{
		$("#act").val('');			
		if($("#addBtn"))
		{
			$("#addBtn").attr('disabled','disabled');
			$("#addBtn").attr('class','disbutton');			
		}
		if($("#comboDDiv"))
			$("#comboDDiv").attr('style','display:none;');			
		if($("#divTemplateText"))
			$("#divTemplateText").attr('style','display:;');	
			
		if($("#saveBtn"))
		{
			$("#saveBtn").attr('disabled','disabled');
			$("#saveBtn").attr('class','disbutton');			
		}		
		$("#ddl_template").val(templateVal);			
		DisableEnable(1);
	}
	else
	{
		$("#divTemplateText").attr('style','display:none;');			
		if($("#saveBtn"))
		{
			if($("#act").val()=='Add' || $("#act").val()=='Edit')
			{
				$("#saveBtn").attr('disabled','disabled');
				$("#saveBtn").attr('class','disbutton');	
				if($("#act").val()=='Edit')
					loadgrid();
			}
			else
			{				
				loadgrid();
			}
		}
		if($("#SelTemType").val()==1)
		{
			if($("#btnMngStatusList") && templateVal!=0)
			{
				$("#btnMngStatusList").attr('disabled','');
				$("#btnMngStatusList").attr('class','button');
			}
			if($("#btnMngStatusWorkList") && templateVal!=0)
			{
				$("#btnMngStatusWorkList").attr('disabled','');
				$("#btnMngStatusWorkList").attr('class','button');
			}
			if($("#btnReorderBtn") && templateVal!=0)
			{
				$("#btnReorderBtn").attr('disabled','');
				$("#btnReorderBtn").attr('class','button');
			}
		}
		renderGrid();
		DisableEnable(1);
		if($("#ddl_template").val()==0)
		{
			if($("#addBtn"))
			{
				$("#addBtn").attr('disabled','disabled');
				$("#addBtn").attr('class','disbutton');					
			}
		}
		else
		{
			if($("#addBtn"))
			{
				$("#addBtn").attr('disabled','');
				$("#addBtn").attr('class','button');			
			}
			if($("#deleteBtn"))
			{
				$("#deleteBtn").attr('disabled','');
				$("#deleteBtn").attr('class','button');				
			}
		}
	}
}
function fnSaveTemplate()
{
	if($("#btnMngStatusList").length>0){
			$("#btnMngStatusList").attr('class','disbutton');
			$("#btnMngStatusList").attr('disabled','disabled');
	}
		
	if($("#btnMngStatusWorkList").length>0){
		$("#btnMngStatusWorkList").attr('class','disbutton');
		$("#btnMngStatusWorkList").attr('disabled','disabled');
	}
		
	var clientid = $("#selClients").val();
	var TemplateType= $("#SelTemType").val();
	var templateIext = $("#template_title").val();
	if(clientid==0)
	{
		alert('Please Select Client.');
		return false;
	}
	
	if(spaceTrim(templateIext)=='')
	{
		alert('Please Enter Template Name.');
		return false;
	}
	xajax_SaveTemplate(clientid,templateIext,1,TemplateType);	
}
function spaceTrim(name)	//for prevent only blank space in input [-mec]
{return name.replace(/^\s+|\s+$/g, '');};

function fnAdd()
{
	try{
	fnReset();
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
	Field['Act'].value = "Add";
	DisableEnable(0);
		}
catch(err) {
	alert(err.message);
}
	return true;	
}

function fnEdit()
{
	var Field =  new Array();
	Field = objInit();
	Field['Act'].value = "Edit";
	DisableEnable(0);
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
	
	Field['Act'].value = "Edit";
	enabledisableCSCombo(0);	
	
	return false;
}
function fnDelete()
{
	var Field =  new Array();
	Field = objInit();
	var chktemp=currentId.split('_');
	
	if(chktemp[0]=="#TM")
	{
		if(confirm("Deleting a template will remove all records, are you sure you want to continue?"))
		{
			xajax_deleteTemplate($("#hdn_template_id").val(),$("#selClients").val());
		}
	}
	else if(chktemp[0]=="#TR")
	{
		if(confirm("Are you sure you want to delete this record?"))
		{
			xajax_DeleteAirworthines(xajax.getFormValues('frm'));		
			delete dataObj.RowData["_"+$("#id").val()];	
			fnReset();
			renderGrid();		
		}
	}
	else if(chktemp[0]=="#TC")
	{
		if(confirm("Deleting a category will remove all records, are you sure you want to continue?"))
		{
			xajax_deleteCategory($("#hdn_cat_id").val(),$("#hdn_template_id").val(),$("#selClients").val());
		}
	}
	return false;
}
// for saving records
function fnSave()
{
	
	var Field =  new Array();
	Field = objInit();	
	var catVal;
	if($("#SelTemType") && $("#SelTemType").val()==2)
	{
		catVal=1;
	}
	else
	{
		catVal=$("#tab_name").val();
	}
	
		if($("#ddl_template"))
		{
			if($("#ddl_template").val()==0)
			{
				alert('Please Select Template.');
				$("#ddl_template").focus();
				return false;
			}
			if($("#ddl_template").val()=='add_temp')
			{
				alert('Please Select Template.');
				$("#ddl_template").focus();
				return false;
			}
		}
		if(Field['client'].value=="0")
		{
			alert("Please Select Client.");
			return false;
		}
		else if(catVal != "0")
		{
			if(spaceTrim(Field['Link'].value) == "")
			{
				alert("Please enter description.");
				return false;
			}
			if(Field['Status'].value == "")
			{
				alert("Please Select Status.");
				return false;
			}
			if(Field['read_only'].value=="")
			{
				alert("Please Select Read Only Option.");
				return false;
			}
			if(Field['Manufacturer'].value == "-1")
			{
				alert("Please Select Manufacturer.");
				return false;
			}
			
			var ddlLink = $("#chk_HyperLink");
			var CSLink = $("#hdn_hyperlink_value");
			var centre_id = $("#centre_id");
			var attach_flydoc = $("#attach_flydoc");
			if(ddlLink.val() == 0 && attach_flydoc.val() == null)
			{
				alert("Please select Attach FLYdoc Template.");
				return false;
			}
			if(ddlLink.val()==1 && centre_id.val()==0)
			{
				alert("Please select section.");
				return false;
			}
			else if(ddlLink.val() == '1' &&  CSLink.val() == '0')
			{
				if(centre_id.val()==2)
				{
					alert("Please select a Maintenance History tab to link this record to.");
						return false;
				}
				else 
				{
					var retValue = validateCSCombo();
					if(retValue==false)
					{
						alert("Please select a Current Status tab to link this record to.");
						return false;
					}
					else if(retValue==true || (retValue!=true && retValue!=false))
					{
						$("#hdn_hyperlink_value").val(retValue);
					}
				}
			}
			if(attach_flydoc.val()!=null)
			{
				if($("#flydoc_id").length>0 && $("#flydoc_id").attr("value")!=undefined && $("#flydoc_id").val()==0)
				{
						alert("Please select FLYdoc template.");
						return false;
				}
				if($("#template_type").length>0 && $("#template_type").attr("value")!=undefined && $("#template_type").val()==0)
				{
						alert("Please select FLYdoc template type.");
						return false;
				}
			}			
			if(Field['Act'].value == "Add")
			{	
				xajax_SaveAirworthines(xajax.getFormValues('frm'));
			}
			else if(Field['Act'].value == "Edit")
			{					
				xajax_UpdateAirworthines(xajax.getFormValues('frm'));
			}
		}
		else
		{
			alert("Please Select Category.");
			return false;
		}
		return false;
}
function objInit()
{
	try{
	var objField = new Array();	
	objField['client'] = document.getElementById("selClients");
	objField['Add'] =  document.getElementById("addBtn");
	objField['Edit'] = document.getElementById("editBtn");
  	objField['Delete'] = document.getElementById("deleteBtn");
  	objField['Save'] =  document.getElementById("saveBtn");
 	objField['Reset'] =  document.getElementById("resetBtn");
	
	objField['ID'] =  document.getElementById("id");
	objField['CAT_NAME'] = document.getElementById("category_name");
	
	if(document.getElementById("tab_name"))
		objField['Tab'] = document.getElementById("tab_name");
	objField['Link'] = document.getElementById("link_title");
	objField['Status'] =  document.getElementById("status");
	objField['Manufacturer'] = document.getElementById("Manufacturer");
	objField['Act'] =  document.getElementById("act");
	objField['Id'] =  document.getElementById("id");
	objField['TabOld'] = document.getElementById("oldTab");
	objField['read_only'] =  document.getElementById("read_only");
	}
catch(err) {
	alert(err.message);
}
	return objField;
}

function fnReset()
{
	var Field =  new Array();	
	Field = objInit();
	
	var clientId = $("#selClients").val();
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
	if(Field['Act'])
		Field['Act'].value = "";	
	if(Field['ID'])
		Field['ID'].value = "";
	DisableEnable(1);
	if($("#tab_name"))
		$("#tab_name").val(0);
	if($("#link_title"))
		$("#link_title").val("");
	if($("#status"))
		$("#status").val("");
	if($("#read_only"))
		$("#read_only").val(0);		
	if($("#Manufacturer"))
		$("#Manufacturer").val(-1);			
	if($("#chk_HyperLink"))
		$("#chk_HyperLink").val(0);
	if($("#attach_flydoc"))
		$("#attach_flydoc").val(0);
	if($("#btnMngStatusList").length>0){
			$("#btnMngStatusList").attr('disabled','disabled');
			$("#btnMngStatusList").attr('class','disbutton');
	}
	if($("#btnMngStatusWorkList").length>0){
			$("#btnMngStatusWorkList").attr('class','disbutton');
			$("#btnMngStatusWorkList").attr('disabled','disabled');
	}
	if($("#btnReorderBtn").length>0){
			$("#btnReorderBtn").attr('class','disbutton');
			$("#btnReorderBtn").attr('disabled','disabled');
	}
	show_centre(0);
	resetCSCombo(0);
	if($("#flydoc_div"))
		$("#flydoc_div").html('');
	if($("#template_type_div"))
		$("#template_type_div").html('');
	if($("#attach_flydoc"))
		$("#attach_flydoc").attr('disabled','disabled');
	if($("#ddl_template").length>0 && $("#ddl_template").val()!="add_temp"){	
		if($("#divTemplateText")){
				$("#divTemplateText").attr('style','display:none;');
		}
	}
	return false;
}
function DisableEnable(flg)
{
	var strdis='';
	if(flg)
	{
		strdis='disabled';
	}
	
		if($("#tab_name") && $("#SelTemType").val()==1)
			$("#tab_name").attr('disabled',strdis);
		if($("#link_title"))
			$("#link_title").attr('disabled',strdis);
		if($("#status"))
			$("#status").attr('disabled',strdis);
		if($("#read_only"))
			$("#read_only").attr('disabled',strdis);		
		if($("#Manufacturer"))
			$("#Manufacturer").attr('disabled',strdis);
		if($("#chk_HyperLink") && $("#attach_flydoc").val()==0)
			$("#chk_HyperLink").attr('disabled',strdis);
		if($("#attach_flydoc") && $("#chk_HyperLink").val()==0)
		{
			$("#attach_flydoc").attr('disabled',strdis);
			if($("#template_type"))
				$("#template_type").attr('disabled',strdis);
			if($("#flydoc_id"))
				$("#flydoc_id").attr('disabled',strdis);
		}
		if($("#chk_HyperLink").val()==1)
		{		
			if($("#centre_id"))
				$("#centre_id").attr('disabled',strdis);
			if($("#type_id"))
				$("#type_id").attr('disabled',strdis);
			if($("#view_ddl"))
				$("#view_ddl").attr('disabled',strdis);
			if($("#ddl_maincs"))
				$("#ddl_maincs").attr('disabled',strdis);	
			if($("#position_id"))
				$("#position_id").attr('disabled',strdis);	
		}
	
}
function loadgrid()
{
	HeaderObj= new Object();
	HeaderObj['Template_Title']="Template Title";
	if($("#SelTemType").val()==1)
	{
		HeaderObj['Category']="Category";
		HeaderObj['ItemId']="ItemId";
	}
	HeaderObj['Description']="Description";
	HeaderObj['Hyperlink_Option']="Hyperlink Option";
	HeaderObj['Hyperlink_Value']="Hyperlink Value";
	HeaderObj['Template_Name']="Template Name";
	HeaderObj['Read_Only']="Read Only";
	HeaderObj['Status']="Status";
	HeaderObj['Manufacturer']="Manufacturer";
	HeaderObj['icon']="";
	
	for(hid in HeaderObj){
		FilterValObj[hid]=($("#fd_filter_"+hid).length>0 && $("#fd_filter_"+hid).val()!='')?$("#fd_filter_"+hid).val():"";	
	}
	
	var client_id=$("#selClients").val();
	var params="section=6&act=GRID&type=1&sub_section=1&client_id="+client_id+"&templet_type="+$("#SelTemType").val();
		
	if($("#ddl_template") && $("#ddl_template").val() !='0' && $("#ddl_template").val()!="add_temp")
	{
		params+="&templet_id="+$("#ddl_template").val();
	}
	getLoadingCombo(1);	
	$.ajax({url: "airworthiness_master.php", async:false,type:"POST",data:params,success: function(data){
		dataObj = eval("("+data+")");	
		TotalRow=dataObj.TotalRow
		RowData=dataObj.RowData		
		DisplayOrd=dataObj.DisplayOrd
		Category=dataObj.Category
		Template=dataObj.Template
		renderGrid();}});
}
function renderGrid()
{
	try
	{		
		var table = '<div  id="maintablewidth1"><table width="100%" cellspacing="1" cellpadding="3" border="0" class="tableContentBG" id="m_tablewidth" ></table></div>';
		table+='<table class="tableContentBG" width="100%" cellspacing="1" cellpadding="3" border="0">';
		table+='<tr id="h_row1"><td align="right" class="tableCotentTopBackground" height="35" id="divTopPagging" colspan="11"></td></tr>';
		//table +='<tr id="h_row2"><td  class="tableCotentTopBackgroundNew"></td></tr>';
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
		if(hid!='Description')
			tempFilterTable +='<td align="left" class="tableCotentTopBackground"></td>';
		else
			tempFilterTable +='<td align="left" class="tableCotentTopBackground"><input type="text" class="gridinput" onkeyup="return filterGrid(event);" id=fd_filter_'+hid+' name=fd_filter_'+hid+' value="'+FilterValObj[hid]+'"></td>';			
	}
	
	return tempFilterTable +='</tr>';
}
function getGridData()
{
	var temprowTable = '';
	var i=0;
	var j=0;	
	var tid='';
	var temp_cat='';	
	var strDocId = "";	
	
	
		for(rid in RowData){			
					
			if(FilterValObj['Description']!='' && (dataObj.RowData[rid]['temp_description'].toLowerCase()).indexOf(FilterValObj['Description'].toLowerCase())<0)
			{					
				continue;
			}
			var Temp_id=dataObj.RowData[rid]['template_id'];
			if(tid=='' || tid!=Temp_id)
			{
				temprowTable += '<tr class="even" id="TM_'+Temp_id+'" onclick="javascript:Edit('+Temp_id+','+0+',\'T\');">';
				temprowTable += '<td valign="top" align="left"  nowrap="nowrap" >';
				temprowTable += '<span onclick="Showhide('+Temp_id+');" id="ImgTypeIdP'+Temp_id+'" class="minusicon"></span>';
				temprowTable += '&nbsp;&nbsp;'+dataObj.RowData[rid]['template_name']+'</td><td colspan="10"></td></tr>';
			}
			
			if($("#SelTemType").val()==1)
			{
				if(temp_cat=='' || dataObj.RowData[rid]['category_id']!=temp_cat  || tid!=Temp_id)
				{
					j=0;
					temprowTable += '<tr class="even" id="TC_'+dataObj.RowData[rid]['category_id']+'" onclick="javascript:Edit('+Temp_id+','+dataObj.RowData[rid]['category_id']+',\'C\');">';
					temprowTable += '<td></td>';	
					temprowTable += '<td valign="top" align="left"  nowrap="nowrap" >';
					temprowTable += '<span onclick="Showhide_child('+dataObj.RowData[rid]['category_id']+');" id="ImgCat_id'+dataObj.RowData[rid]['category_id']+'" class="minusicon"></span>';
					temprowTable += '&nbsp;&nbsp;'+dataObj.RowData[rid]['category_name']+'</td>';
					temprowTable += '<td>'+dataObj.RowData[rid]['ItemID']+'</td>';
					temprowTable += '<td colspan="8" ></td>';				
					temprowTable += '</tr>';															
				}
				temp_cat=dataObj.RowData[rid]['category_id'];
			}
			tid=Temp_id;
						
			var class1 = (i%2==0)?"even":"odd";	
			var Parent_id=dataObj.RowData[rid]['category_id'];
			var rid=rid.split("_")[1];
			
			temprowTable += '<tr class="'+class1+'" id="TR_'+rid+'" onclick="javascript:Edit('+Temp_id+','+rid+',\'R\');" onmouseover="javascript:MouseOver(this.id);fileMouseOver5('+Parent_id+','+j+','+Temp_id+');" onmouseout="javascript:MouseOut(this.id,'+rid+');fileMouseOut5('+Parent_id+','+j+','+Temp_id+');"  onmouseup="fileMouseUp5('+Parent_id+','+j+','+Temp_id+');" >';
									
			temprowTable += '<td id="temp_'+Temp_id+'_'+Parent_id+'_'+j+'"></td>';
			if($("#SelTemType").val()==1)
			{
				temprowTable += '<td id="cat_'+Temp_id+'_'+Parent_id+'_'+j+'"></td>';
				temprowTable += '<td id="catitem_'+Temp_id+'_'+Parent_id+'_'+j+'"></td>';
			}
			temprowTable += '<td id="desc_'+Temp_id+'_'+Parent_id+'_'+j+'">'+dataObj.RowData["_"+rid]['temp_description']+'</td>';	
			if(dataObj.RowData["_"+rid]['hyperlink_option']==1)
				temprowTable += '<td id="hyperopt_'+Temp_id+'_'+Parent_id+'_'+j+'">Yes</td>';					
			else				
				temprowTable += '<td id="hyperopt_'+Temp_id+'_'+Parent_id+'_'+j+'">No</td>';	
			temprowTable += '<td id="hyperval_'+Temp_id+'_'+Parent_id+'_'+j+'">'+dataObj.RowData["_"+rid]['hyperlink_value']+'</td>';	
			if(dataObj.RowData["_"+rid]['flydocs_Template_name']!='')
				temprowTable += '<td id="flydocs_'+Temp_id+'_'+Parent_id+'_'+j+'">'+dataObj.RowData["_"+rid]['flydocs_Template_name']+'</td>';	
			else
				temprowTable += '<td id="flydocs_'+Temp_id+'_'+Parent_id+'_'+j+'">-</td>';	
			if(dataObj.RowData["_"+rid]['read_only']==1)
				temprowTable += '<td id="read_'+Temp_id+'_'+Parent_id+'_'+j+'">Yes</td>';					
			else				
				temprowTable += '<td id="read_'+Temp_id+'_'+Parent_id+'_'+j+'">No</td>';		
			if(dataObj.RowData["_"+rid]['status']==1)
				temprowTable += '<td id="active_'+Temp_id+'_'+Parent_id+'_'+j+'">Active</td>';	
			else
				temprowTable += '<td id="active_'+Temp_id+'_'+Parent_id+'_'+j+'">Inactive</td>';	
			temprowTable += '<td id="manu_'+Temp_id+'_'+Parent_id+'_'+j+'">'+dataObj.RowData["_"+rid]['manufacturer']+'</td>';				
			
			temprowTable += '<td id="ChildImg_'+Temp_id+'_'+Parent_id+'_'+j+'" onmousedown="return fileMouseDown5('+Parent_id+','+j+','+Temp_id+','+rid+');" style="width: 25px; margin-left: 10px; background-image: url(\'images/move.gif\');  background-repeat: no-repeat; background-position:center; margin-right: 10px; cursor:move;height:20px;!important" >';
			temprowTable += '<input id="ROW_'+Parent_id+'_'+j+'" type="hidden" value="'+j+'" name="ROW_'+Parent_id+'_'+j+'"></td>';	
			
			temprowTable += '</tr>';		
			i++;
			j++;
		}
	

	return temprowTable;
}
function Showhide_child(catid)
{
	if($("#ImgCat_id"+catid) && $("#ImgCat_id"+catid).hasClass('minusicon'))	
	{	
		$("#ImgCat_id"+catid).attr('class', "plusicon");
		for(rid in RowData){	
			if(RowData[rid]['category_id']==catid)
			{							
				if($("#TR"+rid))
					$("#TR"+rid).attr('style','display:none;');
			}			
		}
	}
	else
	{
		$("#ImgCat_id"+catid).attr('class', "minusicon");
		for(rid in RowData){	
			if(RowData[rid]['category_id']==catid)
			{	
				if($("#TR"+rid))
					$("#TR"+rid).attr('style','display:;');
			}
		}
	}	
}
function Showhide(tempId)
{
	if($("#ImgTypeIdP"+tempId) && $("#ImgTypeIdP"+tempId).hasClass('minusicon'))	
	{	
		$("#ImgTypeIdP"+tempId).attr('class', "plusicon");
		for(rid in RowData){	
			if(RowData[rid]['template_id']==tempId)
			{							
				if($("#TC_"+RowData[rid]['category_id']))	
					$("#TC_"+RowData[rid]['category_id']).attr('style','display:none;');
				Showhide_child(RowData[rid]['category_id']);
			}			
		}
	}
	else
	{
		$("#ImgTypeIdP"+tempId).attr('class', "minusicon");
		for(rid in RowData){	
			if(RowData[rid]['template_id']==tempId)
			{	
				if($("#TC_"+RowData[rid]['category_id']))	
					$("#TC_"+RowData[rid]['category_id']).attr('style','display:;');
				Showhide_child(RowData[rid]['category_id']);
			}
		}
	}	
}
function fileMouseDown5(groupId,docIndex,tempID,RowID)
{
	rowIndex = document.getElementById("ROW_"+groupId+"_"+docIndex).value;
	downGroup =groupId;
	downIndex =docIndex;
	downRow = rowIndex;
	mainTempID = tempID;
	RowValID = RowID;
	return false;
}
document.onmouseup=function()
{
	downGroup ="-1";
	downIndex ="-1";
	downRow ="-1";
};

//************************************   REORDER  **********************************

var arrGroup = new Array();
var downGroup ="-1";
var downIndex ="-1";
var mainTempID=0;
var RowValID=0;
//var tempId=0;

function fileMouseOver5(groupId,docIndex,maingroupId)
{
	if(downGroup != "-1" && downIndex != "-1")
	{
		if(document.getElementById('temp_'+maingroupId+'_'+groupId+'_'+docIndex))
		var template = document.getElementById('temp_'+maingroupId+'_'+groupId+'_'+docIndex);
		if(document.getElementById('cat_'+maingroupId+'_'+groupId+'_'+docIndex))
		var id = document.getElementById('cat_'+maingroupId+'_'+groupId+'_'+docIndex);
		var title = document.getElementById('desc_'+maingroupId+'_'+groupId+'_'+docIndex);		
		if(document.getElementById('catitem_'+maingroupId+'_'+groupId+'_'+docIndex))
		var itemid = document.getElementById('catitem_'+maingroupId+'_'+groupId+'_'+docIndex);
		var hlOpt = document.getElementById('hyperopt_'+maingroupId+'_'+groupId+'_'+docIndex);
		var hlVal = document.getElementById('hyperval_'+maingroupId+'_'+groupId+'_'+docIndex);
		var flydocs = document.getElementById('flydocs_'+maingroupId+'_'+groupId+'_'+docIndex);
		var active = document.getElementById('active_'+maingroupId+'_'+groupId+'_'+docIndex);
		var manu = document.getElementById('manu_'+maingroupId+'_'+groupId+'_'+docIndex);
		if(document.getElementById('read_'+maingroupId+'_'+groupId+'_'+docIndex))
		var read_only = document.getElementById('read_'+maingroupId+'_'+groupId+'_'+docIndex);
		
		
		if(downGroup==groupId)
		{
			if(template)
			{
				template.style.borderLeft = "1px solid #F00";
				template.style.borderTop = "1px solid #F00";
				template.style.borderBottom = "1px solid #F00";
			}
			if(id)
			{
				id.style.borderTop = "1px solid #F00";
				id.style.borderBottom = "1px solid #F00";
			}
			title.style.borderBottom = "1px solid #F00";
			title.style.borderTop = "1px solid #F00";
			if(itemid)
			{
				itemid.style.borderBottom = "1px solid #F00";
				itemid.style.borderTop = "1px solid #F00";
			}
			hlOpt.style.borderBottom = "1px solid #F00";
			hlOpt.style.borderTop = "1px solid #F00";
			hlVal.style.borderBottom = "1px solid #F00";
			hlVal.style.borderTop = "1px solid #F00";
			flydocs.style.borderBottom = "1px solid #F00";
			flydocs.style.borderTop = "1px solid #F00";
			active.style.borderBottom = "1px solid #F00";
			active.style.borderTop = "1px solid #F00";
			manu.style.borderBottom = "1px solid #F00";
			manu.style.borderTop = "1px solid #F00";
			manu.style.borderRight = "1px solid #F00";
			if(read_only)
			{
				read_only.style.borderBottom = "1px solid #F00";
				read_only.style.borderTop = "1px solid #F00";
				
			}
		}
		else
		{
			if(template)
			{
				template.style.borderLeft = "1px solid #F00";
				template.style.borderTop = "1px solid #F00";
				template.style.borderBottom = "1px solid #F00";
			}
			if(id)
			{
				id.style.borderTop = "1px solid #F00";
				id.style.borderBottom = "1px solid #F00";
			}
			title.style.borderBottom = "1px solid #F00";
			title.style.borderTop = "1px solid #F00";
			if(itemid)
			{
				itemid.style.borderBottom = "1px solid #F00";
				itemid.style.borderTop = "1px solid #F00";
			}
			
			hlOpt.style.borderBottom = "1px solid #F00";
			hlOpt.style.borderTop = "1px solid #F00";
			hlVal.style.borderBottom = "1px solid #F00";
			hlVal.style.borderTop = "1px solid #F00";
			flydocs.style.borderBottom = "1px solid #F00";
			flydocs.style.borderTop = "1px solid #F00";
			active.style.borderBottom = "1px solid #F00";
			active.style.borderTop = "1px solid #F00";
			manu.style.borderBottom = "1px solid #F00";
			manu.style.borderTop = "1px solid #F00";
			manu.style.borderRight = "1px solid #F00";
			if(read_only)
			{
				read_only.style.borderBottom = "1px solid #F00";
				read_only.style.borderTop = "1px solid #F00";
				
			}
		}
	}
}

function fileMouseOut5(groupId,docIndex,maingroupId)
{
	if(document.getElementById('temp_'+maingroupId+'_'+groupId+'_'+docIndex))
	var template = document.getElementById('temp_'+maingroupId+'_'+groupId+'_'+docIndex);
	if(document.getElementById('cat_'+maingroupId+'_'+groupId+'_'+docIndex))
	var id = document.getElementById('cat_'+maingroupId+'_'+groupId+'_'+docIndex);
	var title = document.getElementById('desc_'+maingroupId+'_'+groupId+'_'+docIndex);
	if(document.getElementById('catitem_'+maingroupId+'_'+groupId+'_'+docIndex))
	var itemid = document.getElementById('catitem_'+maingroupId+'_'+groupId+'_'+docIndex);
	var hlOpt = document.getElementById('hyperopt_'+maingroupId+'_'+groupId+'_'+docIndex);
	var hlVal = document.getElementById('hyperval_'+maingroupId+'_'+groupId+'_'+docIndex);
	var flydocs = document.getElementById('flydocs_'+maingroupId+'_'+groupId+'_'+docIndex);
	var active = document.getElementById('active_'+maingroupId+'_'+groupId+'_'+docIndex);
	var manu = document.getElementById('manu_'+maingroupId+'_'+groupId+'_'+docIndex);
	if(document.getElementById('read_'+maingroupId+'_'+groupId+'_'+docIndex))
	var read_only = document.getElementById('read_'+maingroupId+'_'+groupId+'_'+docIndex);
	
	if(template)
	{
		template.style.borderColor = "#CCCCCC";
		template.style.borderTop = "";
	}
	if(id)
	{
		id.style.borderColor = "#CCCCCC";
		id.style.borderTop = "";
	}
	
	
	
	title.style.borderColor = "#CCCCCC";
	title.style.borderTop = "";
	if(itemid)
	{
		itemid.style.borderBottom = "#CCCCCC";
		itemid.style.borderTop = "";
	}
	hlOpt.style.borderColor = "#CCCCCC";
	hlOpt.style.borderTop = "";
	hlVal.style.borderColor = "#CCCCCC";
	hlVal.style.borderTop = "";
	flydocs.style.borderColor = "#CCCCCC";
	flydocs.style.borderTop = "";
	active.style.borderColor = "#CCCCCC";
	active.style.borderTop = "";
	manu.style.borderColor = "#CCCCCC";
	manu.style.borderTop = "";
	if(read_only)
	{
		read_only.style.borderColor = "#CCCCCC";
		read_only.style.borderTop = "";
	}
}

function fileMouseUp5(groupId,docIndex,maingroupId)
{
	
	if(downGroup != "-1" && downIndex !="-1")
	{
		if(downGroup==groupId && mainTempID == maingroupId)
		{
			// Same Group
			var strDocIds='';
			var rowIndex = document.getElementById("ROW_"+groupId+"_"+docIndex).value;
			for(rid in RowData)
			{
				if(RowData[rid]['template_id']==maingroupId && RowData[rid]['category_id']==groupId)
				strDocIds += ((strDocIds=="")? "" : "/")+rid.split("_")[1];	
			}			
			
			var arrDocIds = strDocIds.split("/");
			
			if(docIndex>downIndex)
			{
				tempId = arrDocIds[downIndex];				
				
				for(var i=downIndex;i<docIndex;i++)
				{
					arrDocIds[i] = arrDocIds[i+1];					
				}				
				arrDocIds[docIndex] = tempId;				
			}
			else
			{
				tempId = arrDocIds[downIndex];				
				
				for(var i=downIndex;i>docIndex;i--)
				{
					arrDocIds[i] = arrDocIds[i-1];
				}
				arrDocIds[docIndex] = tempId;
			}
			strDocIds = "";
						
			for(var i=0;i<arrDocIds.length;i++)
			{
				strDocIds += ((strDocIds=="")? "" : "/") + arrDocIds[i];			
			}
			var upRow = document.getElementById("ROW_"+downGroup+"_"+docIndex).value;			
			if(downRow != upRow)
			{
				var arrReq = Array();
				arrReq['Id'] = strDocIds;
				arrReq['Group'] = "";
				saveInternal(arrReq);
			}
			else
			{
				return false;
			}
		}
		else
		{
			var strDownDocIds="";
			if(downGroup!=groupId && mainTempID != maingroupId)
			{
				for(rid in RowData)
				{
					if(RowData[rid]['template_id']==mainTempID && RowData[rid]['category_id']==downGroup)
					strDownDocIds += ((strDownDocIds=="")? "" : "/")+rid.split("_")[1];	
				}						
			}else
			{
				for(rid in RowData)
				{
					if(RowData[rid]['template_id']==maingroupId && RowData[rid]['category_id']==downGroup)
					strDownDocIds += ((strDownDocIds=="")? "" : "/")+rid.split("_")[1];	
				}					
			}
			
			
			var arrDownDocIds = strDownDocIds.split("/");
			var strTemp = strDownDocIds;			
			
			var strUpDocIds ='';
			for(rid in RowData)
			{
				if(RowData[rid]['template_id']==maingroupId && RowData[rid]['category_id']==groupId)
				{
					strUpDocIds += ((strUpDocIds=="")? "" : "/")+rid.split("_")[1];						
				}
			}	
			var arrUpDocIds = strUpDocIds.split("/");
						
			strDownDocIds="";
			
			if(arrDownDocIds!="")
			{			
				for(var i=0;i<arrDownDocIds.length;i++)
				{
					if(i!=downIndex)
					{
						strDownDocIds += ((strDownDocIds=="")? "" : "/") + arrDownDocIds[i];
					}
				}
			}
			
			strUpDocIds="";
						
			for(var i=0;i<arrUpDocIds.length;i++)
			{
				if(i==docIndex)
				{
					strUpDocIds += ((strUpDocIds=="")? "" : "/") + arrDownDocIds[downIndex];
										
					if(arrUpDocIds[i] != "")
					{
						strUpDocIds += ((strUpDocIds=="")? "" : "/") + arrUpDocIds[i];
					}
				}
				else
				{
					strUpDocIds += ((strUpDocIds=="")? "" : "/") + arrUpDocIds[i];
				}
			}
			if(mainTempID!=maingroupId)
			{
				strUpDocIds +="/"+RowValID;				
				var msgPrompt = confirm("Are you sure you want to reorder the selected row to another category?");
				var updatTemplateID = maingroupId;				
			}
			else
			{
				var msgPrompt = confirm("Are you sure you want to reorder the selected row to another category?");
				var updatTemplateID = 0 ;	
			}			
			if(msgPrompt)
			{
				var arrReq = Array();
				arrReq['Id'] = strUpDocIds;
				arrReq['Group'] = groupId;
				saveExternal(arrReq,updatTemplateID);
			}
			else
			{				
				return false;
			}
		}
	}
	downGroup ="-1";
	downIndex ="-1";
	downRow ="-1";
	mainTempID=0;
	RowValID=0;
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
function show_centre(val)
{
	resetCSCombo(0);
	if(val==1)
	{
		$("#centre_div").attr('style','display:;');
		$("#flydoc_div").html('');
		$("#template_type_div").html('');
		$("#attach_flydoc").val(0)
		$("#attach_flydoc").attr('disabled','disabled');
		$("#centre_id").attr('disabled','');
		$("#SelTemType").attr('disabled','');
		$("#comboDDiv").html('<div id="ComboDiv"></div>');				
	}
	else
	{
		$("#centre_div").attr('style','display:none;');
		$("#centre_id").val("");
		$("#attach_flydoc").attr('disabled','');		
		hide_mh_combo();
		$("#hdn_hyperlink_value").val("0");
	}
}

function HyperLinkChanged(value)
{
	try
	{
		if($("#comboDDiv"))
			$("#comboDDiv").attr('style','display:;');
		if(value==1 || value==0)
		{
			GetCSLOV(0,0,0,value);
		}
		if($("#hdn_hyperlink_value"))
			$("#hdn_hyperlink_value").val("0");
		if($("#tdMainCS"))
			$("#tdMainCS").html('');	
		if($("#tdSubCS"))
			$("#tdSubCS").html('');	
		if($("#tdChildCS"))
			$("#tdChildCS").html('');	
		
		if (value == 2)
		{
			if($("#cmdtd"))
				$("#cmdtd").attr('style','display:none;');
			resetCSCombo(0);		
			show_type(1);
		}
		else
		{
			if($("#cmdtd"))
				$("#cmdtd").attr('style','display:;');
			hide_mh_combo();
			if($("#tdMainCS"))
				$("#tdMainCS").html('');			
			if($("#tdSubCS"))
				$("#tdSubCS").html('');
			if($("#tdChildCS"))
				$("#tdChildCS").html('');			
		}
	}
	catch(e)
	{
		alert(e)
	}
}
function hide_mh_combo()
{
	if($("#hdn_hyperlink_value"))
		$("#hdn_hyperlink_value").val("0");
	if($("#type_div"))
		$("#type_div").attr('style','display:none;');
	if($("#type_id"))
		$("#type_id").val("");
	if($("#position_div"))
		$("#position_div").html('');	
	if($("#view_Td"))
		$("#view_Td").attr('style','display:none;');
	if($("#tdMainCS"))
		$("#tdMainCS").attr('style','display:none;"');	
}

function show_type(val)
{	
	if(val==1)
	{
		var strdis= '';
		if($("#centre_id"))
				$("#centre_id").attr('disabled',strdis);
			if($("#type_id"))
				$("#type_id").attr('disabled',strdis);
			if($("#view_ddl"))
				$("#view_ddl").attr('disabled',strdis);
			if($("#ddl_maincs"))
				$("#ddl_maincs").attr('disabled',strdis);	
			if($("#position_id"))
				$("#position_id").attr('disabled',strdis);
		
		if($("#type_div"))
			$("#type_div").attr('style','display:;');
	}
	else
	{
		if($("#type_div"))
			$("#type_div").attr('style','display:none;');
		if($("#type_id"))
			$("#type_id").val("");
		if($("#hdn_hyperlink_value"))
			$("#hdn_hyperlink_value").val("0");
		if($("#tdMainCS"))
			$("#tdMainCS").html('&nbsp;')
		if($("#tdSubCS"))
			$("#tdSubCS").html('&nbsp;')
		if($("#tdChildCS"))
			$("#tdChildCS").html('&nbsp;')		
	}
	
}
function show_template_type(val)
{
	if(val==1)
	{
		var strCheck = '<select onchange="show_flydoc_template(this.value);" id="template_type" name="template_type">';
			strCheck += '<option value="0">Select Template Type</option>';
			strCheck += '<option value="1">Select Template Group</option>';
			strCheck += '<option value="2">Select Template</option>';
            strCheck += '</select>';
			if($("#template_type_div"))
				$("#template_type_div").html(strCheck)      
			if($("#chk_HyperLink"))
			{
				$("#chk_HyperLink").attr('disabled','disabled');  
				$("#chk_HyperLink").val(0);  
			}
	}
	else
	{
		if($("#flydoc_div"))
			$("#flydoc_div").html('');
		if($("#template_type_div"))
			$("#template_type_div").html('');
		if($("#chk_HyperLink"))
			$("#chk_HyperLink").attr('disabled','');  
	}	
}
function show_flydoc_template(val)
{
	var client_id=$("#selClients").val();
	if(val==1)
	{
		xajax_show_flydocs_template_group(client_id);
	}
	else if(val==2)
	{
		xajax_show_flydocs_template(client_id);
	}
	else
	{
		if($("#flydoc_div"))
			$("#flydoc_div").html('');
		if($("#flydoc_id"))
			$("#flydoc_id").val("");
	}
}
function show_View_type(val)
{
	if(val==1 || val==3 || val==5)
	{		
		if($("#position_div"))
			$("#position_div").attr('style','display:none;');
		var pos = '';
		var type_id = '';
		if($("#position_id"))
			pos =$("#position_id").val();
		if($("#type_id"))
			type_id =$("#type_id").val();		
		xajax_getViewType($("#selClients").val(),pos,type_id);		
	}
	else if(val==2 || val==4)
	{
		if($("#view_Td"))
			$("#view_Td").attr('style','display:none;');		
		if(val!='')
		{
			show_position(1);
			if($("#position_div"))
				$("#position_div").attr('style','display:;');
		}
	}
	else
	{
		if($("#view_Td"))
			$("#view_Td").attr('style','display:none;');		
	}
	
	var centre_id = $("#centre_id").val();
	if($("#view_ddl"))
		$("#view_ddl").val("");
	
	if(centre_id==2)
	{
		$("#tdMainCS").attr('style','display:none;');
		$("#tdSubCS").attr('style','display:none;');		
	}
	if($("#type_id").val()=='')
		$("#position_div").attr('style','display:none;');			
}
function show_sub_type(valVal)
{
	$("#hdn_hyperlink_value").val("0")
	if(valVal==1)		//Year View
	{
		var val = $("#type_id").val();	
		$("#hdn_hyperlink_value").val("0");
		$("#tdMainCS").html('&nbsp;');
		$("#tdMainCS").html('&nbsp;')
		$("#tdSubCS").html('&nbsp;')
		$("#tdChildCS").html('&nbsp;')
		
		xajax_getMainMhTab("",$("#selClients").val(),val);

	}
	else if(valVal==2)	//Delivery Bible View
	{
		if($("#tdMainCS"))
			$("#tdMainCS").attr('style','display:none;');
		if($("#tdSubCS"))
			$("#tdSubCS").attr('style','display:none;');		
		if($("#hdn_hyperlink_value"))
			$("#hdn_hyperlink_value").val(-1);
	}
	else
	{
		if($("#tdMainCS"))
			$("#tdMainCS").attr('style','display:none;');
		if($("#tdSubCS"))
			$("#tdSubCS").attr('style','display:none;');			
	}	
}
function getSubMhTab(value)
{
	if(value!=0)
	{
		if($("#hdn_hyperlink_value"))
			$("#hdn_hyperlink_value").val('0');
		xajax_getSubMhTab(value,$("#selClients").val());
	}
	else
	{
		$("#tdSubCS").html('&nbsp;');
		$("#tdChildCS").html('&nbsp;');
		if($("#hdn_hyperlink_value"))
			$("#hdn_hyperlink_value").val('0');
	}

}
function show_position(val)
{
	if($("#hdn_hyperlink_value"))
		$("#hdn_hyperlink_value").val('0');
	if(val=="")
	{
		$("#position_div").html("");
		$("#tdMainCS").html("");
		return false;
	}
	if($("#position_div"))
		$("#position_div").attr('style','display:;');
	xajax_show_position($("#type_id").val());
	
}
function show_sub_position(val)
{
	if($("#hdn_hyperlink_value"))
		$("#hdn_hyperlink_value").val("0");
	$("#tdMainCS").html('');
	$("#tdSubCS").html('')
	$("#tdChildCS").html('')
	var pos = '';
	var type_id = '';
	if($("#position_id"))
		pos = $("#position_id").val();
	if($("#type_id"))
		type_id = $("#type_id").val();
	
	if(type_id!=1 && type_id!=3 && pos!='')
		xajax_getViewType($("#selClients").val(),pos,type_id);
	
	if(val=="")
	{
		if($("#view_ddl"))
		{
			$("#view_ddl").val('');
			if($("#view_Td"))
				$("#view_Td").attr('style','display:none;');	
		}
		return false;
	}
	else
	{
		if($("#view_ddl"))
			$("#view_ddl").val('');		
	}
}
function Edit(temp_id,rid,flg)
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
	var strID='#TR_'+currentId;
	var rectID='#TR_'+rid;
	$("#id").val(rid);
	if(flg=='T')
	{
		strID='#TM_'+currentId;
		rectID='#TM_'+temp_id;
		$("#id").val('');
	}
	else if(flg=='C')
	{
		strID='#TC_'+currentId;
		rectID='#TC_'+rid;
		$("#id").val('');
	}
	

	if(currentId!="" && $(currentId))
	{
		$(currentId).attr('style','backgroundColor:;');
	}	
	$(rectID).css({"backgroundColor":"#FFCC99","textAlign":"left","cursor":"pointer"});

	DisableEnable(1);
	currentId=rectID;	
	getLoadingCombo(1);
	if($("#btnMngStatusList").length>0){
			$("#btnMngStatusList").attr('class','disbutton');
			$("#btnMngStatusList").attr('disabled','disabled');
	}
	if($("#btnMngStatusWorkList").length>0){
			$("#btnMngStatusWorkList").attr('class','disbutton');
			$("#btnMngStatusWorkList").attr('disabled','disabled');
	}
	if($("#btnReorderBtn").length>0){
			$("#btnReorderBtn").attr('class','disbutton');
			$("#btnReorderBtn").attr('disabled','disabled');
	}
	if(flg=='R')
	{		
		xajax_SetFormAirworthi(rid,$("#selClients").val());
	}
	else if(flg=='T')
	{
		if($("#ddl_template"))
		{
			$("#ddl_template").val(temp_id);
			$("#hdn_template_id").val(temp_id);
			fnReset();
			if( $("#SelTemType").val()==1)
			{
				if($("#btnMngStatusList") && temp_id!=0)
				{
					$("#btnMngStatusList").attr('disabled','');
					$("#btnMngStatusList").attr('class','button');
				}
				if($("#btnMngStatusWorkList") && temp_id!=0)
				{
					$("#btnMngStatusWorkList").attr('disabled','');
					$("#btnMngStatusWorkList").attr('class','button');
				}
				if($("#btnReorderBtn") && temp_id!=0)
				{
					$("#btnReorderBtn").attr('disabled','');
					$("#btnReorderBtn").attr('class','button');
				}
			}
			if($("#deleteBtn"))
			{
				$("#deleteBtn").attr('disabled','');
				$("#deleteBtn").attr('class','button');				
			}
		}
	}
	else if(flg=='C')
	{
		fnReset();
		$("#tab_name").val(rid);
		$("#hdn_cat_id").val(rid);
		$("#ddl_template").val(temp_id);
		$("#hdn_template_id").val(temp_id);
		if($("#deleteBtn"))
		{
			$("#deleteBtn").attr('disabled','');
			$("#deleteBtn").attr('class','button');				
		}
	}
}

function show_position_str(type,selected)
{
	var str='';
	if(type==2)
	{
		str+='<select name="position_id" id="position_id"  class="selectauto" onchange="show_sub_position(this.value);"> <option value="">[Select Position]</option> <option value="1">Engine 1 </option><option value="2">Engine 2</option><option value="3">Engine 3 </option><option value="4">Engine 4</option>					<option value="5">Engine 5 </option><option value="6">Engine 6</option>	</select>';
		
	}else if(type==4)
	{
		str+='<select name="position_id" id="position_id" class="selectauto" onchange="show_sub_position(this.value);"> <option value="">[Select Position]</option> <option value="NLG">NLG</option><option value="RHMLG">RHMLG</option> <option value="LHMLG">LHMLG</option> <option value="CTMLG">CTMLG</option>				  <option value="LHBG">LHBG</option> <option value="RHBG">RHBG</option>	</select>';
	}
	return str;
}
function filterGrid(e)
{
	if(e.keyCode==13){
		loadgrid();
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
	
	window.open("manage_audit_trail.php?adt=MASTER_ADT&sublinkId=61&title=Airworthiness Review Templates",'','toolbar=no,location=no,scrollbars=yes,resizable=yes,width='+winWidth+',height='+winHeight+',left=0,top=0');
}

function openStatuslist()
{
	var type = $("#type").val();
	var section = $("#sectionVal").val();
	var template_id = $("#ddl_template").val();
	var client_id = $("#selClients").val();
	var CentreHeader = window.open('airworthiness_centre.php?section='+section+'&type='+type+'&sub_section=2&master_flag=1&template_id='+template_id+'&client_id='+client_id,'CentreHeader','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes')
	CentreHeader.focus();
}
function openWorkStatuslist()
{
	var type = $("#type").val();
	var section = $("#sectionVal").val();
	var template_id = $("#ddl_template").val();
	var client_id = $("#selClients").val();
	var CentreHeader = window.open('airworthiness_centre.php?section='+section+'&type='+type+'&sub_section=4&master_flag=1&template_id='+template_id+'&client_id='+client_id,'CentreHeader','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes')
	CentreHeader.focus();
}
function updatereorderGrid(tempdispObj)
{
	for(cat_id in Category){
		if(tempdispObj[cat_id]!='' && tempdispObj[cat_id]!=null)
			DisplayOrd[cat_id]=tempdispObj[cat_id];
		for(var z in tempdispObj[cat_id]){
			RowData["_"+tempdispObj[cat_id][z]]['display_order']=z;
		}
	}	
	
	renderGrid();
	//getLoadingCombo(0);
}
function openCategories()
{
	var templateId = $("#hdn_template_id").val();
	var type = $("#type").val();
	var section= $("#sectionVal").val();
	var subSectionVal= $("#sub_sectionVal").val();
	
	if(templateId!=0)
	{
//		var winReorder = window.open('reorder_category.php?templateId='+templateId+'&type=aircraft','winReorder','scrollbars=1,resizable=1,fullscreen=yes');
		var winReorder=window.open('airworthiness_master.php?section='+section+'&sub_section='+subSectionVal+'&type='+type+'&templateId='+templateId+'&REORDERCAT','winReorder','height='+screenH+',width='+screenW+',scrollbars=yes,resizable=yes');
		winReorder.focus();
	}
}
function saveExternal(req,updatTemplateID)
{
	var chkTempType = document.getElementById("SelTemType").value;
	xajax_reorderSublinks(req,chkTempType,updatTemplateID);
}
function saveInternal(req)
{
	var chkTempType = document.getElementById("SelTemType").value;
	xajax_reorderSublinks(req,chkTempType);
}
