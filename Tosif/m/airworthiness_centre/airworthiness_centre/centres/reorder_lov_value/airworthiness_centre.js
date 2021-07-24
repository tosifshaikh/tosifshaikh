// JavaScript Document
var UserID=0;
var user_name='';
var tab_name = '';
var lovObj = new Object();
var dataObj = new Object();
var clientObj = new Object();
var dispObj = new Object();
var BtnArr =new Array("editBtn","saveBtn","deleteBtn");
var objHeader = {0:"Client",1:'Column Title',2:'Column Field Values',3:'Status',4:""};
var actIncobj = {0:"Active",1:"Inactive"};
var fromDispId=0;
var toDispId = 0;
var currentID = 0;
var screenW = 640, screenH = 480;
if (parseInt(navigator.appVersion)>3) {
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


function fnSave()
{
	var field_name = $.trim($("#TxtColumnVal").val());
	var is_active = $("#selStatus").val();
	if(field_name==""){
		alert("Please enter Column Field Name.")
		$("#TxtColumnVal").focus();
		return false;
	}
	var mainId = $("#mainRowid").val();
	var parent_id = lovObj[mainId]["parent_id"];
	var clId =  lovObj[mainId]["client_id"];
	var tempupdateObj = new Object();
	var updateObj = new Object();
	tempupdateObj["lov_value"] = field_name;
	tempupdateObj["is_active"] = is_active;
	
	var k=0;
	var auditObj = new Object();
	for(var y in tempupdateObj){
		if(lovObj[mainId][y] && lovObj[mainId][y]!=tempupdateObj[y]){
			var AuditObj = new Object();
			AuditObj = getAuditObj();		
			AuditObj["action_id"] = 7;		
			var colName = dataObj[clId]['_'+parent_id];
			var oldVal = lovObj[mainId]["lov_value"];
			var client_name = clientObj['_'+clId];
			var old_isActive= lovObj[mainId]["is_active"];
			
			if(y=="lov_value"){
				AuditObj["field_title"] = "Column Field Name";				
				
				AuditObj["old_value"] = client_name+"&nbsp;&raquo;&nbsp;"+colName+'&nbsp;&raquo;&nbsp;'+oldVal;
				AuditObj["new_value"] = client_name+"&nbsp;&raquo;&nbsp;"+colName+'&nbsp;&raquo;&nbsp;'+field_name;
			} else {
				AuditObj["field_title"] = "Status";
				AuditObj["old_value"] = client_name+"&nbsp;&raquo;&nbsp;"+colName+'&nbsp;&raquo;&nbsp;'+field_name+'&nbsp;&raquo;&nbsp;'+actIncobj[old_isActive];
				AuditObj["new_value"] = client_name+"&nbsp;&raquo;&nbsp;"+colName+'&nbsp;&raquo;&nbsp;'+field_name+'&nbsp;&raquo;&nbsp;'+actIncobj[is_active];
			}
			auditObj[k]=AuditObj;
			k++;		
		}
	}
	if(!$.isEmptyObject(auditObj)){
	updateObj['upObj'] = tempupdateObj;
	updateObj['whrObj'] = {"id":mainId};
	updateObj["parent_id"] = parent_id;
	updateObj["main_id"] = mainId;
	xajax_Update(updateObj,auditObj);
	} else {	
		alert("Record Updated Successfully.")
		fnReset();
	}	
}

function loadGrid()
{
	try{
		var sectionVal = $("#sectionVal").val();
		var sub_sectionVal= $("#sub_sectionVal").val();
		var type = $("#type").val();
		var master_flag = $("#master_flag").val();
	    var param = "section="+sectionVal+"&sub_section="+sub_sectionVal+"&act=GRID&type="+type+"&master_flag="+master_flag;	
		$.ajax({url: "airworthiness_centre.php?t="+(new Date()), async:true,type:"POST",data:param,success: function(data){		  
		mainObj = eval("("+data+")");		
		clientObj= mainObj.client;
		dataObj =mainObj.dataArr; 
		lovObj=mainObj.lovArr;
		dispObj=mainObj.dispArr;
		renderGrid();	
		}});
	} catch(Error){
		funError("loadGrid","Section : Airworthiness Centre => Header => Edit /Reorder Lov, Main page Js Error <br/> ",Error.toString(),Error);		
	}
}
function renderGrid()
{
	try{
		getLoadingCombo(1);
		var table='<table width="100%" cellspacing="1" cellpadding="3" border="0" class="tableContentBG" >';
		table +=getHeaderRow();
		table +=getGridData();
		table +='</table>';
		$("#divGrid").html(table);
		Chkinit();
		getLoadingCombo(0);
	}catch(e){
		alert(e);
	}
}
function Edit(ID)
{
	fnReset();
	if(currentID!=""){
		if($("#"+currentID)){
			$("#"+currentID).css("backgroundColor","");
		}
	}
	var pr_id = lovObj[ID]["parent_id"];
	currentID="TRlog_b"+pr_id+"_c"+ID;
	$("#"+currentID).css({"backgroundColor":"#FFCC99","textAlign":"left","cursor":"pointer"});
	
	disableForm();
	enable_disable_buttons('0,2');	
	rowMainID = ID;
	$("#mainRowid").val(ID);
	setForm(ID);
}
function setForm(id)
{
	try{
		$("#TxtColumnVal").val(lovObj[id]['lov_value']);
		$("#selStatus").val(lovObj[id]['is_active']);
	}catch(e){
		funError("setForm","Section : Airworthiness Centre => Center => header => Reorder Lov value, Main page Js Error <br/> ",Error.toString(),Error);
	}
}

function fnEdit()
{
	$("#act").val("EDIT");
	enable_disable_buttons('1');
	enableForm();
}

function fnReset()
{
	clearForm();
	disableForm();
	enable_disable_buttons('');	
	$("#act").val("");
	$("#mainRowid").val("");
	return false;
}
function enable_disable_buttons(btnIndexStr)
{
	var Arr = btnIndexStr.split(",");
	$.each(BtnArr, function( index, value ){
		//here index makes as string due to When we pass like '1,3',it check value in array in string format
		if($.inArray(String(index),Arr)>=0){
			$("#"+value).attr("class","button");
			$("#"+value).attr("disabled", false);
		} else {
			$("#"+value).attr("class","disbutton");
			$("#"+value).attr("disabled", true);
		}
	});
}


function clearForm()
{
	var someForm = $('#frm');
	 var chkarray  = new Array("text","select-one","textbox","checkbox");//not using radio button
	$.each(someForm[0].elements, function(index, elem){	
	if($.inArray(this.type,chkarray)>=0){
		   if(this.type=="checkbox"){
			 this.checked=false;
			 this.value=0;
		   } else if(this.type!="select-one"){
			   this.value='';
		   } else {
			   this.value = this.options[0].value;
		   }   
	   }
	});	
}
function enableForm()
{
	var someForm = $('#frm');
	 var chkarray  = new Array("text","select-one","textbox","checkbox");
	$.each(someForm[0].elements, function(index, elem){
		 if($.inArray(this.type,chkarray)>=0){
		   this.disabled="";
	   }
	});
}
function disableForm()
{
	var someForm = $('#frm');
	 var chkarray  = new Array("text","select-one","textbox","checkbox");
	$.each(someForm[0].elements, function(index, elem){
		if($.inArray(this.type,chkarray)>=0){
		   this.disabled="disabled";
	   }
	});
		
}
function funError(FunctionName,msg,ErrorString,ErrorArray)
{
	xajax_jsError(FunctionName,msg,ErrorString,ErrorArray);
	var table = '<table width="100%" cellspacing="1" cellpadding="3" border="0" class="tableContentBG" >';
	table += '<tr>';
	table += '<td colspan="11" align="center"><strong>There is an issue in fetching record. Please Contact Administrator for further assistance.</strong></td>';
	table += '</tr>';
	table += "</table>";
	$("#divGrid").html(table);
}

function getAuditObj()
{
	var tempAudit = new Object();
	tempAudit={"user_id":UserID,"user_name":user_name,"section":$("#sectionVal").val(),"sub_section":$("#sub_sectionVal").val(),"type":$("#type").val(),"client_id":$("#clientVal").val()};	
	return tempAudit;
}
function getHeaderRow()
{
	var TempTable='';
	TempTable+='<tr>';
	for(x in objHeader){
		TempTable += '<td align="left" class="tableCotentTopBackground" nowrap="nowrap" >'+objHeader[x]+'</td>';
	}	
	TempTable+='</tr>';
	return TempTable;
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

function getGridData()
{
	var k= 0;	
	var divtable = "";
	var cLColsp = Object.keys(objHeader).length;
	var tempTable='';
	for(var a in clientObj){
		var clVal = a.replace("_","");
		var Class1 = (k%2==0)?"even":"odd";
		var cntRow = 0;
		k++;
		if(dataObj[clVal]!="" && dataObj[clVal]!=null){
			var cntRow = Object.keys(dataObj[clVal]).length;	
		}		
		if(cntRow>0){			
			tempTable+="<tr class="+Class1+"><td colspan="+cLColsp+"><img border='0' style='cursor:pointer;' src='images/minus_active.jpg' id='imglog_0_a"+clVal+"'>&nbsp;&nbsp;&nbsp;"+clientObj[a]+"</td></tr>";		
			for(var b in dataObj[clVal]){
				var Class2 = (k%2==0)?"even":"odd";
				k++;
				var columnId= b.replace("_","");
				var rowCSpan = parseInt(cLColsp)-1;
				tempTable+="<tr id='TRlog_a"+clVal+"_b"+columnId+"' class="+Class2+"><td></td><td colspan="+rowCSpan+"><img border='0' style='cursor:pointer;' src='images/minus_active.jpg' id='imglog_a"+clVal+"_b"+columnId+"'>&nbsp;&nbsp;&nbsp;"+dataObj[clVal][b]+"</td></tr>";
				for(var c in dispObj[columnId]){
				var mainId = dispObj[columnId][c];				
				if(lovObj[mainId]){				
					var classNum = (k%2==0)?0:1;
					tempTable+=getRows(mainId,classNum);					
					k++;					
					}
				}
			}
		}		
	}
	if(tempTable==''){
		tempTable+='<tr id="noRec">';//id is necessary here because when first row is added then need to replace.
		tempTable+='<td align="center" colspan="'+cLColsp+'"><strong>No Records Found</strong></td>';
		tempTable+='</tr>';
	}
	return tempTable;
}

function getRows(dataId,clNum)
{	
	var tempRowsTable = "";	
	if(lovObj[dataId]){
		var parentId = lovObj[dataId]["parent_id"];
		var Class2 = (clNum==0)?"even":"odd";
		var valObj= lovObj [dataId];
		tempRowsTable+='<tr id = "TRlog_b'+parentId+'_c'+dataId+'" onmouseover="javascript:FileMouseOver1(this);"onmouseout="javascript:FileMouseOut1(this);" class='+Class2+' onClick="Edit('+dataId+');">';
		tempRowsTable+='<td>&nbsp;</td><td>&nbsp;</td>';
		tempRowsTable+='<td>'+valObj["lov_value"]+'</td>';
		tempRowsTable+='<td>'+actIncobj[valObj["is_active"]]+'</td>';
		tempRowsTable += '<td onmouseup="return fileMouseUP2('+dataId+');" width="4%" id="td_img_'+x+'" style="width: 25px; margin-left: 10px; ';
		tempRowsTable += ' background-image: url(\'images/move.gif\');  background-repeat: no-repeat; background-position:center; margin-right: 10px;  ';
		tempRowsTable += ' cursor:move;height:25px;!important" onmousedown="return fileMouseDown2('+dataId+');">&nbsp;</td>';						
		tempRowsTable+='</tr>';					
	}
	return tempRowsTable;
}

function updateRow(dataObj)
{
	var mainId =dataObj["main_id"]
	lovObj[mainId]["lov_value"] =dataObj["upObj"]["lov_value"];
	lovObj[mainId]["is_active"] =dataObj["upObj"]["is_active"];
	renderGrid();
	fnReset();
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

function fnDelete()
{
	if(confirm("Are sure want to delete this record?")){
		var mainRowid = $("#mainRowid").val();
		var clId =  lovObj[mainRowid]["client_id"];
		var parent_id = lovObj[mainRowid]["parent_id"];
		
		var client_name = clientObj['_'+clId];		
		var colName = dataObj[clId]['_'+parent_id];		
		var AuditObj = new Object(); 
		AuditObj = getAuditObj();
		AuditObj["old_value"] =client_name+"&nbsp;&raquo;&nbsp;"+colName+'&nbsp;&raquo;&nbsp;'+lovObj[mainRowid]["lov_value"];
		AuditObj["client_id"] =clId;
		AuditObj["new_value"] ="";
		AuditObj["field_title"] = "Column Field Name";
		AuditObj["action_id"] = 6;
		var upObj = new Object();
		upObj["upObj"]={"delete_flag":1};
		upObj["whrObj"]={"id":mainRowid};
		xajax_Delete(upObj,AuditObj);
	}else{
		return false;
	}	
}

function updateDeleterec(delete_id)
{
	try{
		var parent_id = lovObj[delete_id]["parent_id"];
		var display_order= lovObj[delete_id]["display_order"];
		var clId =  lovObj[delete_id]["client_id"];
		delete lovObj[delete_id];	
		delete dispObj[parent_id][display_order];
		
		var cntRow = 0;
		if(dispObj[parent_id]!="" && dispObj[parent_id]!=null){
			var cntRow = Object.keys(dispObj[parent_id]).length;	
		}
		if(cntRow==0){
			delete dataObj[clId]['_'+parent_id];
		}
		renderGrid();
		fnReset();
	}catch(e){
		alert(e);
	}
}


function fileMouseUP2(todid)
{	
	var tododr = lovObj[todid]["display_order"];
	var toParent = lovObj[todid]["parent_id"];	
	var toClId = lovObj[todid]["client_id"];
	
	var froParent = lovObj[FromDispId]["parent_id"];
	var fromClId = lovObj[FromDispId]["client_id"];
	
	if(froParent!=toParent){
		alert("You can't Reorder this Field to in different Column Field");
		return false;
	}	
	var clname = clientObj['_'+toClId];
	var col_name = dataObj[toClId]["_"+toParent];
	ToDispodr = tododr;	
	var AuditObj = new Object(); 	
	AuditObj = getAuditObj();
	AuditObj["field_title"] = "Column Name";
	AuditObj["old_value"] = clname+"&nbsp;&raquo;&nbsp;"+col_name+'&nbsp;&raquo;&nbsp;'+lovObj[FromDispId]["lov_value"];
	AuditObj["new_value"] = clname+"&nbsp;&raquo;&nbsp;"+col_name+'&nbsp;&raquo;&nbsp;'+lovObj[todid]["lov_value"];;
	AuditObj["action_id"] = 5;
	downIndex="-1";	
	if(FromDispodr!=ToDispodr){
		getLoadingCombo(1);
		var reorderObj = new Object();
		reorderObj = {"fromId":FromDispId,"toId":todid,"from_display":FromDispodr,"to_display":ToDispodr,"parent_id":toParent};
		xajax_reorder(reorderObj,AuditObj);		
	} 
}

function fileMouseDown2(fdid)
{
	FromDispId = fdid;
	var fdodr = lovObj[fdid]["display_order"];	
	FromDispodr = fdodr;
	downIndex=0;
	return false;
}

function updatereorderGrid(tempdispObj)
{
	for(var col_id  in tempdispObj){
		dispObj[col_id] = new Object();
		dispObj[col_id] = tempdispObj[col_id];
		for(var z in tempdispObj[col_id]){
			var rowId = tempdispObj[col_id][z];
			if(lovObj[rowId])
				lovObj[rowId]["display_order"] =z; 
		}
	}	
	renderGrid();
	getLoadingCombo(0);
}



var Chkinit=function(){
	
	$("[id^=imglog_]").click( function()
	{
		FlagPrnt=0;
		hideshowcheck(this,'log','')
	});
	
	
	
};


function hideshowcheck(obj,str,style)
{
	var ObjID=obj.id;
	
	var fval=obj.id.split('_');
	
	var pid=fval[2];
	var id=fval[1];
	
	if(!fval[2])
	{
		pid=0;
	}
	
	if(!fval[1])
	{
		id=0;
	}
	
	$("[id^=TR"+str+"_"+pid+"_]").each(function(index, element) {
		
		
		if(style=='')
		{
			style=$(this).css("display");
		}
		
		if(style=='none')
		{
			$(this).show();
			style='none';
			
			$("#img"+str+"_"+id+"_"+pid).attr('src','images/minus_active.jpg');
		}
		else
		{
			$(this).hide();
			style='table-row';
		
			$("#img"+str+"_"+id+"_"+pid).attr('src','images/plus_active.jpg')
			
		}
		
		hideshowcheck(this,str,style)
	});
}

