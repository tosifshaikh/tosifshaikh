// JavaScript Document
function GetData(airId)
{
	xajax_set_ddlManufacturer(airId,1); 	
}
function fn_template_change(templateVal)
{
	if(templateVal!=0){
		loadgrid();
	} else {
		document.getElementById('divGrid').innerHTML = '';
	}
}
function loadgrid()
{
	HeaderObj= new Object();
	HeaderObj['Template_Title']="Template Title";
	HeaderObj['Category']="Category";
	HeaderObj['ItemId']="ItemId";
	HeaderObj['Description']="Description";
	HeaderObj['Hyperlink_Option']="Hyperlink Option";
	HeaderObj['Hyperlink_Value']="Hyperlink Value";
	HeaderObj['Template_Name']="Template Name";
	HeaderObj['Read_Only']="Read Only";
	HeaderObj['Status']="Status";
	HeaderObj['Manufacturer']="Manufacturer";
			
	var client_id=$("#client_lov").val();
	var manufacturer = $("#manufacturer").val();
	var params="section=6&act=GRID&type=1&sub_section=1&client_id="+client_id+"&templet_type=1&templet_id="+$("#ddl_template").val()+"&manufacturer="+manufacturer;		
	
	//getLoadingCombo(1);	
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
		//table +=getFilterRow();	
		table +=getGridData();
		table +='</table>';
		table+='<table width="100%" cellspacing="1" cellpadding="3" border="0"><tr><td align="right" height="45" id="divBottomPagging"></td></tr></table>';
		$("#divGrid").html(table);
		
		getLoadingCombo(0);
		//getPagging(startLimit,100,dataObj.TotalRow);	
	} catch(e) {
		alert(e);
	}
}
function getGridData()
{
	var temprowTable = '';
	var i=0;	
	var tid='';
	var temp_cat='';	

	for(rid in RowData){
			//rid="_"+DisplayOrd[cat_id][dis_id];			
			var Temp_id=dataObj.RowData[rid]['template_id'];
			if(tid=='' || tid!=Temp_id)
			{
				temprowTable += '<tr class="even" id="TM_'+Temp_id+'" >';
				temprowTable += '<td valign="top" align="left"  nowrap="nowrap" >';
				temprowTable += '<span onclick="Showhide('+Temp_id+');" id="ImgTypeIdP'+Temp_id+'" class="minusicon"></span>';
				temprowTable += '&nbsp;&nbsp;'+dataObj.RowData[rid]['template_name']+'</td><td colspan="10"></td></tr>';
			}
			
			
				if(temp_cat=='' || dataObj.RowData[rid]['category_id']!=temp_cat  || tid!=Temp_id)
				{
					temprowTable += '<tr class="even" id="TC_'+dataObj.RowData[rid]['category_id']+'" style="display:" >';
					temprowTable += '<td></td>';	
					temprowTable += '<td valign="top" align="left"  nowrap="nowrap" >';
					temprowTable += '<span onclick="Showhide_child('+dataObj.RowData[rid]['category_id']+');" id="ImgCat_id'+dataObj.RowData[rid]['category_id']+'" class="minusicon"></span>';
					temprowTable += '&nbsp;&nbsp;'+dataObj.RowData[rid]['category_name']+'</td>';
					temprowTable += '<td>'+dataObj.RowData[rid]['ItemID']+'</td>';
					temprowTable += '<td colspan="8" ></td>';				
					temprowTable += '</tr>';										
				}
				temp_cat=dataObj.RowData[rid]['category_id'];
			
			tid=Temp_id;
			var class1 = (i%2==0)?"even":"odd";	
			temprowTable += '<tr class="'+class1+'" id="TR'+rid+'" onmouseover="javascript:FileMouseOver1(this);"onmouseout="javascript:FileMouseOut1(this);" >';
			temprowTable += '<td id="temp'+rid+'_'+i+'"></td>';
			temprowTable += '<td id="cat'+rid+'_'+i+'"></td>';
			temprowTable += '<td id="catitem'+rid+'_'+i+'"></td>';			
			temprowTable += '<td id="desc'+rid+'_'+i+'">'+dataObj.RowData[rid]['temp_description']+'</td>';	
			if(dataObj.RowData[rid]['hyperlink_option']==1)
				temprowTable += '<td id="hyperopt'+rid+'_'+i+'">YES</td>';					
			else				
				temprowTable += '<td id="hyperopt'+rid+'_'+i+'">NO</td>';	
			temprowTable += '<td>'+dataObj.RowData[rid]['hyperlink_value']+'</td>';	
			if(dataObj.RowData[rid]['flydocs_Template_name']!='')
				temprowTable += '<td id="hyperval'+rid+'_'+i+'">'+dataObj.RowData[rid]['flydocs_Template_name']+'</td>';	
			else
				temprowTable += '<td>-</td>';	
			if(dataObj.RowData[rid]['read_only']==1)
				temprowTable += '<td id="read'+rid+'_'+i+'">YES</td>';					
			else				
				temprowTable += '<td id="read'+rid+'_'+i+'">NO</td>';		
			if(dataObj.RowData[rid]['status']==1)
				temprowTable += '<td id="active'+rid+'_'+i+'">Active</td>';	
			else
				temprowTable += '<td id="active'+rid+'_'+i+'">Inactive</td>';	
			temprowTable += '<td id="manu'+rid+'_'+i+'">'+dataObj.RowData[rid]['manufacturer']+'</td>';						
				
			temprowTable += '</tr>';
	
			i++;
		
	}
	if(i==0)
	{
		if($("#Activate"))
			$("#Activate").attr('style','display:none');
		temprowTable +='<tr>';
		temprowTable +='<td align="center" colspan="9">No Records Found</td>';
		temprowTable +='</tr>';	
	}
	else
	{
		if($("#Activate"))
			$("#Activate").attr('style','display:');
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
function getHeaderRow()
{	
	
	var tempHeaderTable='';	
	tempHeaderTable+='<tr>';
	for(hid in HeaderObj){
		tempHeaderTable+='<td align="left" class="tableCotentTopBackground">'+HeaderObj[hid]+'</td>';	
	}	
	return tempHeaderTable+='</tr>';
}

function fileMouseDown_1(rid,order,temp_cat,temp_id)
{
	if($("#oldTab_Order"))
		$("#oldTab_Order").val(rid+'_'+order+'_'+temp_cat+'_'+temp_id);
}
function fileMouseDown(groupId,docIndex)
{
	rowIndex = $("#ROW_"+groupId+"_"+docIndex).val();
	downGroup =groupId;
	downIndex =docIndex;
	downRow = rowIndex;
	return false;
}
document.onmouseup=function()
{
	downGroup ="-1";
	downIndex ="-1";
	downRow ="-1";
};
function fileMouseUp(rid,order,temp_cat,temp_id)
{
	var oldrect='';
	if($("#oldTab_Order"))
		oldrect=$("#oldTab_Order").val();
	var newrect=rid+'_'+order+'_'+temp_cat+'_'+temp_id;
	if(oldrect!='' && newrect!='')
	{
		xajax_Reordering(oldrect,newrect);
		loadgrid();
		if($("#oldTab_Order"))
			$("#oldTab_Order").val('');
	}
}
function FileMouseOver1(elm)
{
	if(downIndex!="-1"){
		$(elm).css("outline","1px solid #F00");
	}
}
function FileMouseOut1(elm)
{
	$(elm).css("outline","1px solid transparent");
}
function fileMouseUP2(todid)
{	
	var tododr = dataObj.RowData["_"+todid]["display_order"];	
	var toClId = dataObj.RowData["_"+todid]["category_id"];
	var fromClId = dataObj.RowData["_"+FromDispId]["category_id"];
	if(fromClId!=toClId){
		alert("You can't Reorder in different Category.");
		return false;
	}
	
	ToDispId = todid;
	ToDispodr = tododr;	
	var AuditObj = new Object(); 	
	//AuditObj = getAuditObj();
	AuditObj["field_title"] = "Column Name";
	AuditObj["action_id"] = 4;
	downIndex="-1";	
	if(FromDispodr!=ToDispodr){
		getLoadingCombo(1);
		xajax_reorder(FromDispId,todid,AuditObj);	

	} 
}

function fileMouseDown2(fdid)
{
	FromDispId = fdid;
	var fdodr = dataObj.RowData["_"+fdid]["display_order"];	
	FromDispodr = fdodr;
	downIndex=0;
	return false;
}

function activate()
{
	var cliet_id = $("#client_lov").val();
	var template_id = $("#ddl_template").val();
	var comp_id = $("#comp_id").val();
	var mainRowid = $("#mainRowid").val();
	
	if(cliet_id==0)
	{
		alert('Please Select Client to Proceed.');
		return false;
	}
	
	if(template_id==0)
	{
		alert('Please Select Template to Proceed.');
		return false;
	}
	getLoadingCombo(1,'');
	xajax_ActivateAirworthiness(cliet_id,template_id,comp_id,mainRowid);
}

function getLoadingCombo(flg,msg)
{
	var elm = document.getElementById("LoadingDiv");
	if(flg==1)
	{
		if(msg!="") 
		{
			document.getElementById("loading_msg").innerHTML = "<strong>"+msg+"</strong>" ; 
		}
		elm.style.width='100%';
		elm.style.height='100%';
		elm.style.display="";
	}
	else
	{
		elm.style.width=0;
		elm.style.height=0;
		elm.style.display="none";
	}
}

function setNewGrid(value)
{
	document.getElementById('Activate').style.display = 'none';
	if(value!=0)
	{
		loadGrid();
	}
	else
	{
		document.getElementById('divGrid').innerHTML = 'No Records Found.';
	}
}