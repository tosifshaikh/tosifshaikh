var colorObj = new Object();
var UserID = 0;
var user_name = "";
var dataObj = new Object();
var mainObj = new Object();
var clientObj = new Object();
var dataObj = new Object();
var dispObj = new Object();
var filterObj= new Object();
var isRemClObj= new Object();
var templateObj= new Object();
var FromDispId=0;
var isRemStatus = {0:"No Status",1:"Expiry Status",2:"Reminder Status",3:"Start Status",4:"Generate Date"};
var currentID = 0;

var BtnArr =new Array("addBtn","editBtn","saveBtn","deleteBtn");
/*var objHeader={0:"Client",1:"Status Title",2:"Status Colour",3:"Enable Statuses for Main Users",4:"Enable Statuses for Internal Users",5:"Disable Row Access for Internal Users",6:"Hide Status from Internal User",
			   7:"Disable Row Access for Client Users",8:"Hide Status from Client User",9:"Default Status",10:"Disable Row Access for URL",11:"Email Template",12:"Reminder/ Expiry Status",13:""};
		*/	   
var objHeader={0:"Client",1:"Status Title",2:"Status Colour",3:"Enable Statuses for Main Users",4:"Enable Statuses for Internal Users",5:"Disable Row Access for Internal Users",6:"Hide Status from Internal User",
			 9:"Default Status",13:""};			   

var objFilter={1:"status_name"};
var ObjElement = {1:1};
var tdwidthElem = {0:"5%"};
/*var fetchObj = {1:"status_name",2:"bg_color",3:{"elem_id":4,"col_name":"enable_status_mainClient"},4:{"elem_id":4,"col_name":"enable_status_internal"},5:{"elem_id":4,"col_name":"disable_row_internal"},6:{"elem_id":4,"col_name":"hide_status_internal"},
				7:{"col_name":"disable_row_client","elem_id":4},8:{"col_name":"hide_status_client","elem_id":4},9:{"col_name":"default_status","elem_id":5},10:{"col_name":"disableForURL","elem_id":4}				,11:"mail_template_id",12:"rem_exp_status"};*/
				
				var fetchObj = {1:"status_name",2:"bg_color",3:{"elem_id":4,"col_name":"enable_status_mainClient"},4:{"elem_id":4,"col_name":"enable_status_internal"},5:{"elem_id":4,"col_name":"disable_row_internal"},6:{"elem_id":4,"col_name":"hide_status_internal"},
				9:{"col_name":"default_status","elem_id":5}};
				
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


function getRemVal(clVal)
{
	try {
	var isRemStr = "";
	if(clVal!=0){
		if(isRemClObj[clVal] && isRemClObj[clVal]!=0){
			$("#trExpRem").css("display","");
			isRemStr = getRemExpLov(clVal);
			$("#tdExpRem").html(isRemStr);
		} else{
			$("#tdExpRem").html(isRemStr);
			$("#trExpRem").css("display","none");
		}
	} else{
		$("#tdExpRem").html(isRemStr);
		$("#trExpRem").css("display","none");
	}
		xajax_setForm(clVal);
	} catch(Error) { 		
		funError("getNewRow","Section : Airworthiness Centre => Manage Work Status List, Main page Js Error <br/> ",Error.toString(),Error);
	}
		
}

function filter(e,f_id)
{
	var filterVal= ($("#filter_"+objFilter[f_id]).length>0 && $("#filter_"+objFilter[f_id]).val()!="")?$("#filter_"+objFilter[f_id]).val():"";
	if(filterVal != ""){
		 filterObj[f_id] =filterVal;
	} else {
		if(filterObj[f_id])
		delete filterObj[f_id];
	}
	
	if(e.keyCode == 13){		
		renderGrid();
	}
	
}
function loadGrid()
{
	try{
		fnReset();
		if($("#template_id").val()==0 && $("#comp_main_id").val()==0){
			objHeader[12]="Reminder/ Expiry Status";
			objHeader[11]="Email Template";
			
			fetchObj[11]="mail_template_id";
			fetchObj[12]="rem_exp_status";
		}
		var sectionVal = $("#sectionVal").val();
		var sub_sectionVal= $("#sub_sectionVal").val();
		var type = $("#type").val();
		var master_flag = $("#master_flag").val();
		var template_id = $("#template_id").val();
		var comp_id = $("#comp_id").val();
		var comp_main_id = $("#comp_main_id").val();
		var client_id = $("#clientVal").val();
		
		
		
	    var param = "section="+sectionVal+"&sub_section="+sub_sectionVal+"&act=GRID&type="+type+"&master_flag="+master_flag+"&template_id="+template_id+"&client_id="+client_id;	
		if(comp_id!=0 && comp_main_id!=0 && client_id!=0)
		{
			param+="&comp_id="+comp_id+"&comp_main_id="+comp_main_id;
		}		
		$.ajax({url: "airworthiness_centre.php?t="+(new Date()), async:true,type:"POST",data:param,success: function(data){		  
		mainObj = eval("("+data+")");
		dataObj = mainObj.data;
		clientObj= mainObj.client;
		dispObj =mainObj.dispData;
		isRemClObj=mainObj.isRemClArr;
		templateObj= mainObj.templateDetail;
		renderGrid();	
		if(client_id!=0 && $("#template_id").val()==0 && $("#comp_main_id").val()==0){			
			getRemVal(client_id);
		}
		}});
	} catch(Error){		
		funError("loadGrid","Section : Airworthiness Centre => Manaage Work Status list , Main page Js Error <br/> ",Error.toString(),Error);		
	}
}

function renderGrid()
{
	try{		
		getLoadingCombo(1);
		var table='<table width="100%" cellspacing="1" cellpadding="3" border="0" class="tableContentBG" >';
		table +=getHeaderRow();
		table +=getFilterRow();
		table +=getGridData();
		table +='</table>';
		$("#divGrid").html(table);
		Chkinit();
		getLoadingCombo(0);
	}catch(Error){		
		funError("renderGrid","Section : Airworthiness Centre => Header, Main page Js Error <br/> ",Error.toString(),Error);		
	}
}

function getHeaderRow()
{
	var TempTable='';
	TempTable+='<tr>';
	for(var x in objHeader){
		var addWidth="";
		if(tdwidthElem[x]){
			addWidth=" width='"+tdwidthElem[x]+"'";
		}
		TempTable += '<td '+addWidth+' align="left" class="tableCotentTopBackground" nowrap="nowrap" >'+objHeader[x]+'</td>';
	}	
	TempTable+='</tr>';
	return TempTable;
}
function getFilterRow()
{
	var TempFilterTable='';
	TempFilterTable+='<tr>';
	for(y in objHeader){
		var chkkey = "";
		if(objFilter[y]!=""){
			chkkey = y;
		}
		TempFilterTable += '<td align="left" class="tableCotentTopBackground">'+getObjElement(chkkey)+'</td>';
	}	
	TempFilterTable+='</tr>';
	return TempFilterTable;
}
function getGridData()
{
	var k= 0;	
	var divtable = "";
	var cLColsp = Object.keys(objHeader).length;	
	for(a in clientObj){
		var clVal = a.replace("_","");
		var Class1 = (k%2==0)?"even":"odd";
		var cntRow = 0;
		k++;
		if(dispObj[clVal]!="" && dispObj[clVal]!=null){
			var cntRow = Object.keys(dispObj[clVal]).length;	
		}		
		var clStr ="";
		var tempTable='';
		if(cntRow>0){			
			clStr ="<tr class="+Class1+"><td colspan="+cLColsp+"><img border='0' style='cursor:pointer;' src='images/minus_active.jpg' id='imglog_0_"+clVal+"'>&nbsp;&nbsp;&nbsp;"+clientObj[a]+"</td></tr>";		
			for(b in dispObj[clVal]){
				var mainId = dispObj[clVal][b];
				var classNum = (k%2==0)?0:1;
				tempTable+=getRows(mainId,classNum);
				k++;
			}
		}
		if(tempTable!=""){
			divtable+=clStr+tempTable;  
		}
	}
	
	if(divtable==''){
		divtable+='<tr id="noRec">';//id is necessary here because when first row is added then need to replace.
		divtable+='<td align="center" colspan="'+cLColsp+'"><strong>No Records Found</strong></td>';
		divtable+='</tr>';
	}
	return divtable;
}
function getObjElement(EleType)
{
	try{
		var tempElemTable ='';
		var x =EleType;
		
		var FilterVal = ($("#filter_"+objFilter[x]).length>0 && $("#filter_"+objFilter[x]).val()!="")?$("#filter_"+objFilter[x]).val():"";
		if(ObjElement[x] && ObjElement[x]==""){
			return "&nbsp;";
		}  else if(ObjElement[x]==1) {			
			return  tempElemTable+='<input type="text" onkeydown="return filter(event,'+x+');" name="filter_'+objFilter[x]+'" id="filter_'+objFilter[x]+'" value="'+FilterVal+'">';
		} else {
			return "&nbsp;";
		}		
	}catch(Error){
		
		funError("getObjElement","Section : Airworthiness Centre => Header, Main page Js Error <br/> ",Error.toString(),Error);		
	}
}
function getRowElem(f_id,mainId)
{
	try{		
		var elemId = fetchObj[f_id]["elem_id"];
		var idVal =fetchObj[f_id]["col_name"];
		var cl_id =dataObj[mainId]["client_id"];
		var addStr = "";
		if(dataObj[mainId][idVal]==1){
			addStr = " checked='checked' ";
		}
		var Str="";
		if(elemId==4){
			return  Str='<input '+addStr+' type="checkbox" onclick="javascript:updateRowVal('+mainId+','+f_id+',this);" name="check_'+idVal+'_'+cl_id+'[]" id="check_'+idVal+'_'+mainId+'">'; 
		}
		if(elemId==5){
			return Str='<input type="radio" '+addStr+' onclick="javascript:updateRowVal('+mainId+','+f_id+',this);" name="check_'+idVal+'_'+cl_id+'[]" id="check_'+idVal+'_'+mainId+'">'; 
		}		
	}catch(Error){		
		funError("getObjElement","Section : Airworthiness Centre => Header, Main page Js Error <br/> ",Error.toString(),Error);		
	}
}

function updateRowFields(updObj)
{
	var idVal = updObj["whrObj"]["id"];
	var col_name = updObj["upObj"]["col_name"];
	var updateVal = updObj["upObj"]["update_val"];
	var clId = dataObj[idVal]["client_id"];	
	if(col_name=="default_status"){
		for(var y in dispObj[clId]){
			var df_idVal = dispObj[clId][y];
			dataObj[df_idVal]["default_status"] = 0;
		}
	}
	dataObj[idVal][col_name] =updateVal;
	renderGrid();
	fnReset();
	
}
function updateRowVal(mainDataId,fid_val,elemObj)
{
	try{
		var data_colname = fetchObj[fid_val]["col_name"];
		var ColNameVal = objHeader[fid_val];
		
		var updateVal = 0;
		var upObj = new Object();
		if($("#check_"+data_colname+"_"+mainDataId).attr("checked")==true){
			updateVal = 1;	
		}
		var StrVal = {0:"No",1:"Yes"};
		upObj["upObj"]={"col_name":data_colname,"update_val":updateVal};
		upObj["whrObj"]={"id":mainDataId};
		var clid = dataObj[mainDataId]["client_id"];
		var clientName = clientObj["_"+clid];
		var statusName = dataObj[mainDataId]["status_name"];
		var fixStr= clientName+"&nbsp;&raquo;&nbsp;"+statusName;
		
		var oldColVal =0;
		if(dataObj[mainDataId][data_colname])
		oldColVal = dataObj[mainDataId][data_colname];
		
		var oldVal =fixStr+"&nbsp;&raquo;&nbsp;"+StrVal[oldColVal];
		var newVal =fixStr+"&nbsp;&raquo;&nbsp;"+StrVal[updateVal];
		
		var auditObj = new Object();
		auditObj = getAuditObj();
		auditObj["field_title"] = ColNameVal;
		auditObj["action_id"] = 9;
		auditObj["old_value"] = oldVal;
		auditObj["new_value"] = newVal;
		auditObj["client_id"] = clid;		
		xajax_updateRowVal(upObj,auditObj);
	}catch(Error){
		funError("updateRowVal","Section : Airworthiness Centre => Manage Work Status List, Main page Js Error <br/> ",Error.toString(),Error);
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
	var cl_id = dataObj[ID]["client_id"];
	currentID="TRlog_"+cl_id+"_"+ID;
	$("#"+currentID).css({"backgroundColor":"#FFCC99","textAlign":"left","cursor":"pointer"});
	
	disableForm();
	enable_disable_buttons('1,3');
	$("#mainRowid").val(ID);
	setForm(ID);
}

function setForm(mainId)
{
	try{
	var cl_id = dataObj[mainId]["client_id"];
	var status_name= dataObj[mainId]["status_name"];
	var bg_color= dataObj[mainId]["bg_color"];	
	var mail_template_id= dataObj[mainId]["mail_template_id"];	
	var isRemStr = "";
	if(isRemClObj[cl_id] && isRemClObj[cl_id]!=0){
		$("#trExpRem").css("display","");
		isRemStr = getRemExpLov(cl_id);
		if(isRemStr!=""){
			$("#tdExpRem").html(isRemStr);
		}
	}	
	$("#clientVal").val(cl_id);
	$("#status_name").val(status_name);
	$("#color_type").val(bg_color);
	xajax_setForm(cl_id,mail_template_id);
	} catch(Error) { 
		alert(Error)
		funError("getNewRow","Section : Airworthiness Centre => Manage Work Status List, Main page Js Error <br/> ",Error.toString(),Error);
	}
	
	
}
function getRemExpLov(cl_id)
{
	var is_reminder_status= 0;
	var addDis="";
	if($("#mainRowid").val()!=""){
		var mainId = $("#mainRowid").val();
		is_reminder_status= dataObj[mainId]["rem_exp_status"];		
	}
	addDis=" disabled = 'disabled' ";
	var addStr = "";
	addStr="<select "+addDis+" class='selectauto' name='remExpStatus' id = 'remExpStatus'>";
	for(var y in isRemStatus){
		var selStr="";
		 if(is_reminder_status==y){
			 selStr+=" selected ='selected' ";
		}
		if(y==2){
			if(isRemClObj[cl_id] && isRemClObj[cl_id]==2){
				addStr+="<option "+selStr+" value='"+y+"'>"+isRemStatus[y]+"</option>";	
			}
		} else {
			addStr+="<option  "+selStr+" value='"+y+"'>"+isRemStatus[y]+"</option>";
		}		
	}
	addStr+="</select>";
	return addStr;
}

function getRows(dataId,clNum)
{
	var tempRowsTable = "";
	var tempTable = "";
	if(dataObj[dataId]){
		var flag =0;
		var valObj= dataObj[dataId];
		var statusName = valObj["status_name"];
		var FilterValObj={1:statusName};
		$.each(objFilter,function(index,val){	
			if($("#filter_"+val).length>0 && $("#filter_"+val).val()!=''){
				var srcStr = FilterValObj[index].toLowerCase();
				var Strsrch = $("#filter_"+val).val().toLowerCase();
				var result = srcStr.indexOf(Strsrch) >= 0;
				if(!result)
				flag=1;							
			}						
		});
		
		if(flag==0){
				
			var cl_id = valObj["client_id"];
			var Class2 = (clNum==0)?"even":"odd";
			tempRowsTable+='<tr id = "TRlog_'+cl_id+'_'+dataId+'" onmouseover="javascript:FileMouseOver1(this);"onmouseout="javascript:FileMouseOut1(this);" class='+Class2+' onClick="Edit('+dataId+');">';
			tempRowsTable+='<td>&nbsp;</td>';
			for( w in fetchObj){				
				var filedVal = "";
				var addStr = "";
				if(typeof(fetchObj[w])=="object"){
					var colName = fetchObj[w]["col_name"];
					var elemId = fetchObj[w]["elem_id"];
					addStr= getRowElem(w,dataId);
					
					if(valObj[colName])
					filedVal = valObj[colName];
					tempRowsTable+='<td align="center">'+addStr+'</td>';
					
				} else{
					var colName = fetchObj[w];
					filedVal = valObj[colName];
					if(w==2){
						filedVal = colorObj[valObj[colName]];
					}
					if(w==11){
						if(templateObj[valObj[colName]])
						filedVal = templateObj[valObj[colName]];
						else 
						filedVal = 'N/A';
					}
					
					if(w==12){
						filedVal = isRemStatus[valObj[colName]];
					}
					tempRowsTable+='<td>'+filedVal+'</td>';
				}
				
				
			}
			tempRowsTable += '<td onmouseup="return fileMouseUP2('+dataId+');" width="4%" id="td_img_'+dataId+'" style="width: 25px; margin-left: 10px; ';
			tempRowsTable += ' background-image: url(\'images/move.gif\');  background-repeat: no-repeat; background-position:center; margin-right: 10px;  ';
			tempRowsTable += ' cursor:move;height:25px;!important" onmousedown="return fileMouseDown2('+dataId+');">&nbsp;</td>';
		}			
	}
	return tempRowsTable;
}
function updateDeleterec(d_id)
{
	var clId = dataObj[d_id]["client_id"];	
	delete dataObj[d_id];
	for(var z in dispObj[clId]){
		if(dispObj[clId][z] == d_id){			
			delete dispObj[clId][z];
		}
	}	
	renderGrid();	
	fnReset();
}

function fnDelete()
{
	if(confirm("Are sure want to delete this record?")){
		var mainRowid = $("#mainRowid").val();
		var cl_id=dataObj[mainRowid]["client_id"];
		var clname = clientObj['_'+cl_id];
		var AuditObj = new Object(); 
		AuditObj = getAuditObj();
		AuditObj["old_value"] =clname+"&nbsp;&raquo;&nbsp;"+dataObj[mainRowid]["status_name"];
		AuditObj["client_id"] =dataObj[mainRowid]["client_id"];
		AuditObj["new_value"] ="";
		AuditObj["field_title"] = "Status Title";
		AuditObj["action_id"] = 10;
		xajax_Delete(mainRowid,AuditObj);
	}else{
		return false;
	}
	
}
function fnSave()
{
	try{
		var clientVal = $("#clientVal").val();
		var status_name = $.trim($("#status_name").val());
		var color_name = $("#color_type").val();
		var idRemVal =0;
		var mail_template_id =$("#mail_template_id").val();
		if($("#remExpStatus").length>0 && $("#remExpStatus").val()!=0){
			idRemVal =$("#remExpStatus").val();
		}
		if($("#template_id").val()!=0){
			template_id=$("#template_id").val();
		}
		
		if(clientVal==0){
			alert("Please Select Client.");
			$("#field_name").focus();
			return false;
		}
		if(status_name==""){
			alert("Please enter Status Title.");
			$("#status_name").focus();
			return false;
		}
		if(color_name==0){
			alert("Please select Status Colour.");
			$("#color_type").focus();
			return false;
		}
		var insObj = new Object();
		if(color_name == "#000000" || color_name == "#0358b7"){
			insObj["font_color"] = "#ffffff";
		}else{
			insObj["font_color"] = "#000000";
		}
		insObj['bg_color']=color_name;
		insObj['status_name']=status_name;
		insObj['rem_exp_status']=idRemVal;
		insObj['mail_template_id']=mail_template_id;
		insObj['template_id']=$("#template_id").val();
		insObj['master_flag']=$("#master_flag").val();
		if($("#comp_id").val()!=0 && $("#comp_main_id").val()!=0 && $("#client_id").val()!=0)
		{
			insObj['component_id']=$("#comp_id").val();
			insObj['comp_main_id']=$("#comp_main_id").val();
		}	
		if($("#act").val()=="ADD"){
			insObj["type"] =$("#type").val();
			insObj["client_id"] =clientVal;			
			var AuditObj = new Object(); 
			AuditObj = getAuditObj();
			AuditObj["field_title"] = "Status Title";
			AuditObj["action_id"] = 8;
			AuditObj["new_value"] = status_name;
			xajax_Save(insObj,AuditObj);
		} else if($("#act").val()!=""){
			updateData(insObj);
		}
	} catch(Error){
		alert(Error)
		funError("getNewRow","Section : Airworthiness Centre => Manage Work Status List, Main page Js Error <br/> ",Error.toString(),Error);
	}	
}

function updateGridData(updObj)
{
	var mainId = updObj["mainRowid"];
	for(var j in updObj["updata"]){		
		if(dataObj[mainId][j]!=updObj["updata"][j]){
			dataObj[mainId][j] = updObj["updata"][j];
		}
	}
	renderGrid();
	fnReset();
}

function updateData(insertObj)
{
	var oldDataObj = new Object();
	var mainRowid = $("#mainRowid").val();
	oldDataObj = dataObj[mainRowid];
	var cl_id =oldDataObj["client_id"];
	var auditObj = new Object();
	var k = 0;
	var tempUpObj = new Object();
	for(z in insertObj){
		if(oldDataObj[z]!=insertObj[z]){
			tempUpObj[z] = 	insertObj[z];
		}
		if(oldDataObj[z] && oldDataObj[z]!=insertObj[z] && z!="font_color"){
			
			var tempAudit = getAuditObj();
			auditObj[k] = tempAudit;
			auditObj[k]["action_id"] = 9;						
			var clName = clientObj['_'+cl_id];
			if(z=="status_name"){				
				auditObj[k]["field_title"]= "Status Title";
				auditObj[k]["old_value"] = clName+"&nbsp;&raquo;&nbsp;"+oldDataObj["status_name"];
				auditObj[k]["new_value"] = clName+"&nbsp;&raquo;&nbsp;"+insertObj["status_name"];
			}
			
			if(z=="bg_color"){
				auditObj[k]["field_title"]= "Status Colour";
				auditObj[k]["old_value"] = clName+"&nbsp;&raquo;&nbsp;"+oldDataObj["status_name"]+"&nbsp;&raquo;&nbsp;"+colorObj[oldDataObj["bg_color"]];
				auditObj[k]["new_value"] = clName+"&nbsp;&raquo;&nbsp;"+oldDataObj["status_name"]+"&nbsp;&raquo;&nbsp;"+colorObj[insertObj["bg_color"]];
			}
			if(z=="rem_exp_status"){
				auditObj[k]["field_title"]= "Reminder/ Expiry Status";
				auditObj[k]["old_value"] = clName+"&nbsp;&raquo;&nbsp;"+oldDataObj["status_name"]+"&nbsp;&raquo;&nbsp;"+isRemStatus[oldDataObj["rem_exp_status"]];;
				auditObj[k]["new_value"] = clName+"&nbsp;&raquo;&nbsp;"+oldDataObj["status_name"]+"&nbsp;&raquo;&nbsp;"+isRemStatus[insertObj["rem_exp_status"]];;
			}
			if(z=="mail_template_id"){
				auditObj[k]["field_title"]= "Email Template";
				var fixVal = clName+"&nbsp;&raquo;&nbsp;"+oldDataObj["status_name"]+"&nbsp;&raquo;&nbsp;N/A ";
				var oldTem =fixVal;
				var newTem = fixVal;
				if(templateObj[oldDataObj[z]] && templateObj[oldDataObj[z]]!=""){
					oldTem=clName+"&nbsp;&raquo;&nbsp;"+oldDataObj["status_name"]+"&nbsp;&raquo;&nbsp;"+templateObj[oldDataObj[z]];
				}
				if(templateObj[insertObj[z]] && templateObj[insertObj[z]]!=""){
					newTem=clName+"&nbsp;&raquo;&nbsp;"+oldDataObj["status_name"]+"&nbsp;&raquo;&nbsp;"+templateObj[insertObj[z]];
				}
				if(oldTem!=newTem){
					auditObj[k]["old_value"] = oldTem;
					auditObj[k]["new_value"] = newTem;
				}
			}
		}
	}
	if(!$.isEmptyObject(tempUpObj)){
		upObj = {"updata":tempUpObj,"client_id":cl_id,"status_name":insertObj["status_name"],"mainRowid":mainRowid};
	}
	if(!$.isEmptyObject(upObj)){		
		xajax_Update(upObj,auditObj);		
	}
	
	
}

function getNewRow(fobj)
{
	var f=0;
	var clId = 0;
	var addedId = 0;
	
	try{
		if(Object.keys(fobj).length>0){			
			clientObj = fobj['client_id'];
			clId = fobj['data']['client_id'];
			addedId = fobj["addedId"];
			dispOdr = fobj['data']['display_order'];
			if(!dispObj[clId]){
				dispObj[clId] = new Object();
			}
			if(!dispObj[clId][dispOdr]){
				dispObj[clId][dispOdr] = new Object();
			}			
			dispObj[clId][dispOdr] = addedId;
			dataObj[addedId]= fobj['data'];
			renderGrid();
			fnReset();
		}
	}catch(Error){
		funError("getNewRow","Section : Airworthiness Centre => Manage Work Status List, Main page Js Error <br/> ",Error.toString(),Error);
	}
	
}

function getAuditObj()
{
	var tempAudit = new Object();
	tempAudit={"user_id":UserID,"user_name":user_name,"section":$("#sectionVal").val(),"sub_section":$("#sub_sectionVal").val(),"type":$("#type").val(),"client_id":$("#clientVal").val()};	
	if($("#comp_main_id").val()!='' && $("#sectionVal").val()==3){
		tempAudit['comp_main_id'] = $("#comp_main_id").val();
	}
	return tempAudit;
}
function fnAdd()
{
	$("#act").val("ADD");
	$("#mainRowid").val("");
	clearForm();
	enableForm();
	enable_disable_buttons('2');
}
function fnEdit()
{
	$("#act").val("EDIT");
	enable_disable_buttons('2');
	enableForm();	
}

//index which is passed will enable and rest of button will disable
//you have to pass Key in String Foemat  due to if want to multiple button disable
//then pass it in string
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
		   }else if(this.name=='clientVal'){
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
			if(this.name=='clientVal'){}
			else
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
			if(this.name=='clientVal'){}
			else
		   	this.disabled="disabled";
	   }
	});
		
}
function fnReset()
{
	clearForm();
	disableForm();
	enable_disable_buttons('0');
	$("#act").val("");
	$("#mainRowid").val("");	
	
	//	$("#tdExpRem").html("");
	
	//$("#trExpRem").css("display","none");
	filterObj = new Object();
	return false;
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
function getLoadingCombo(flg)
{
	var elm =$("#LoadingDivCombo");
	if(flg ==1){
		elm.css({"width":"100%","height":"100%","display":""});
	} else {
		elm.css({"width":0,"height":0,"display":"none"});		
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

function fileMouseUP2(todid)
{	

	var tododr = dataObj[todid]["display_order"];	
	var toClId = dataObj[todid]["client_id"];
	var fromClId = dataObj[FromDispId]["client_id"];
	if(fromClId!=toClId){
		alert("You can't Reorder in different Client.");
		return false;
	}
	
	ToDispId = todid;
	ToDispodr = tododr;	
	var clName = clientObj["_"+fromClId];
	var AuditObj = new Object(); 	
	AuditObj = getAuditObj();
	AuditObj["field_title"] = "Status Title";
	AuditObj["old_value"] = clName+"&nbsp;&raquo;&nbsp;"+dataObj[FromDispId]["status_name"];
	AuditObj["new_value"] = clName+"&nbsp;&raquo;&nbsp;"+dataObj[todid]["status_name"];	
	AuditObj["action_id"] = 11;
	downIndex="-1";		
	if(FromDispodr!=ToDispodr){
		getLoadingCombo(1);
		var reorderObj = new Object();
		reorderObj = {"fromId":FromDispId,"toId":todid,"from_display":FromDispodr,"to_display":ToDispodr,"client_id":fromClId};
		xajax_reorder(reorderObj,AuditObj);		
	} 
}

function fileMouseDown2(fdid)
{
	FromDispId = fdid;
	var fdodr = dataObj[fdid]["display_order"];	
	FromDispodr = fdodr;
	downIndex=0;
	return false;
}
function updatereorderGrid(tempdispObj)
{
	for(var clId  in tempdispObj){
		dispObj[clId] = new Object();
		dispObj[clId] = tempdispObj[clId];
		for(var z in tempdispObj[clId]){
			var rowId = tempdispObj[clId][z];
			if(dataObj[rowId])
			dataObj[rowId]["display_order"] =z; 
		}
	}	
	renderGrid();
	getLoadingCombo(0);
}
function open_parent(flg)
{
	var type = $("#type").val();
	var section= $("#sectionVal").val();
	var master_flag= $("#master_flag").val();
	var client_id= $("#client_id").val();
	var comp_main_id= $("#comp_main_id").val();
	var comp_id= $("#comp_id").val();
		
	var tabObj = {1:50,2:51};
	var subsectionObj = {1:2,2:4};
	for(var j in tabObj){
		var subSectionVal = subsectionObj[flg];
		if(j==flg){
			$("#center"+tabObj[flg]).attr("class","SubTabbedPanelsTabCentercp1");			
		} else {
			$("#center"+tabObj[flg]).attr("class","SubTabbedPanelsTabCentercp");
		}
		var param = "";
		if(comp_id!=0 && comp_main_id!=0 && client_id!=0)
		{
			param+="&comp_id="+comp_id+"&comp_main_id="+comp_main_id+"&client_id="+client_id;
		}	
		if(master_flag==1)
		{
			param+="&client_id="+client_id;
		}
		if($("#template_id").val()!=0)
		{	
			window.open('airworthiness_centre.php?section='+section+'&sub_section='+subSectionVal+'&type='+type+'&master_flag='+master_flag+'&template_id='+$("#template_id").val()+param,'_self','height='+screenH+',width='+screenW+',scrollbars=yes,resizable=yes');
		}
		else
		{
			window.open('airworthiness_centre.php?section='+section+'&sub_section='+subSectionVal+'&type='+type+'&master_flag='+master_flag+param,'_self','height='+screenH+',width='+screenW+',scrollbars=yes,resizable=yes');
		}
		
	}
	
}
function Open_Status_Audit()
{
	var type = $("#type").val();	
	var	section= $("#sectionVal").val();	
	var	sub_section= $("#sub_sectionVal").val();
	var addStr = '';
	if($("#comp_main_id").val()!='' && section==3){
		addStr += '&comp_main_id='+$("#comp_main_id").val();
	}
	if($("#clientVal").length>0 && $("#clientVal").val()!=0 && $("#clientVal").val()!=''){
		addStr += '&client_id='+$("#clientVal").val();
	}	
	var statusAudit = window.open('airworthiness_centre_audit.php?section='+section+'&sub_section='+sub_section+'&type='+type+addStr,'statusAudit',
								 'height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	statusAudit.focus();
}
 
function reloadWin()
{
	if($("#comp_main_id").val()!=0){
		window.opener.loadGrid();
	}
}