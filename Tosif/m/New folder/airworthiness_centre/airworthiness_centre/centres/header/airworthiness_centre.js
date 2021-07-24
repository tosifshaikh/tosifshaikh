var BtnArr =new Array("addBtn","editBtn","saveBtn","deleteBtn");
var objHeader = {0:"Client",1:"Column Title",2:"Column Field Type",3:"Column Field Values",4:"Auto Prepare List",5:"Read Only",6:""};
var objFilter = {0:"client",1:"column_title",2:"col_fl_type",3:"col_fl_val",4:"auto_prepare",5:"read_only",6:""};
var ObjElement = {0:"",1:1,2:{0:"None",1:"Free Text",2:"List of Values",3:"Date Picker",4:"Reference Type",5:"ARC Sequencing"},3:1,4:{0:"No",1:"Yes"},5:{0:"No",1:"Yes"},6:""};
var mainObj =new Object();
var  dataObj = new Object();
var clientObj= new Object();
var dispObj = new Object();
var currentID=0;;
var rowMainID = 0;
var lovObj = new Object();
var filterObj = new Object();
var fromDispId=0;
var toDispId = 0; 

Object.keys=Object.keys||function(o,k,r){r=[];for(k in o)r.hasOwnProperty.call(o,k)&&r.push(k);return r}

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


function filter(e,f_id)
{
	var filterVal= ($("#filter_"+objFilter[f_id]).length>0 && $("#filter_"+objFilter[f_id]).val()!="")?$("#filter_"+objFilter[f_id]).val():"";
	if(filterVal != ""){
		 filterObj[f_id] =filterVal;
	} else {
		if(filterObj[f_id])
		delete filterObj[f_id];
	}
	if(e!=""){
		if(e.keyCode == 13){		
			renderGrid();
		}
	} else {
		renderGrid();
	}
}

function loadGrid()
{
	try{
		fnReset();
		var sectionVal = $("#sectionVal").val();
		var sub_sectionVal= $("#sub_sectionVal").val();
		var type = $("#type").val();
		var master_flag = $("#master_flag").val();
		var template_id = $("#template_id").val();
		var comp_id = $("#comp_id").val();
		var comp_main_id = $("#comp_main_id").val();
		var client_id = $("#clientVal").val();		
	    var param = "section="+sectionVal+"&sub_section="+sub_sectionVal+"&act=GRID&type="+type+"&master_flag="+master_flag+"&template_id="+template_id+"&client_id="+client_id;		
		if(comp_id!=0 && comp_main_id!=0 )
		{
			param+="&comp_id="+comp_id+"&comp_main_id="+comp_main_id;
		}		
		$.ajax({url: "airworthiness_centre.php?t="+(new Date()), async:true,type:"POST",data:param,success: function(data){		  
		mainObj = eval("("+data+")");
		dataObj = mainObj.data;
		clientObj= mainObj.client;
		dispObj =mainObj.dispData; 
		lovObj=mainObj.lovArr;
		renderGrid();	
		}});
	} catch(Error){
		funError("loadGrid","Section : Airworthiness Centre => Header, Main page Js Error <br/> ",Error.toString(),Error);		
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
		alert(Error)
		funError("renderGrid","Section : Airworthiness Centre => Header, Main page Js Error <br/> ",Error.toString(),Error);		
	}
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
function getFilterRow()
{
	var TempFilterTable='';
	TempFilterTable+='<tr>';
	for(y in objFilter){
		TempFilterTable += '<td align="left" class="tableCotentTopBackground">'+getObjElement(y)+'</td>';
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

function getRows(dataId,clNum)
{
	var tempRowsTable = "";
	var tempTable = "";
	if(dataObj[dataId]){
		var flag=0;
		
			
			var Class2 = (clNum==0)?"even":"odd";
			var valObj= dataObj[dataId];
			var cl_id = valObj["client_id"];
			var headerName = valObj["header_name"];
			var refmaxVal = "";
			if(valObj["filter_type"]==4 && valObj["refMax_value"]!=""){
				var tempHeader = valObj["header_name"].split("RPL_COMMA");
				if(tempHeader[0])
				headerName =tempHeader[0];
				
				if(tempHeader[1])
				refmaxVal=tempHeader[1];
			}
			
			
			var FilterValObj={1:headerName,2:dataObj[dataId]["filter_type"],3:refmaxVal,4:dataObj[dataId]["filter_auto"],5:dataObj[dataId]["read_only"]};
			var flag=0;
			if(Object.keys(filterObj).length>0 && filterObj!=""){
				for(var f in filterObj){
					if(filterObj[f]!=""){						
						var srcStr = String(FilterValObj[f]).toLowerCase();
						var Strsrch = String(filterObj[f]).toLowerCase();
						var result = srcStr.indexOf(Strsrch) >= 0;
						if(!result)
						flag=1;							
					}			
				}
			}
			if(filterObj[3] && filterObj[3]!="" && lovObj[dataId] && valObj["filter_type"]==2){
				flag=1;
				for(var f in lovObj[dataId]){
					var lovVal = lovObj[dataId][f];					
					var srcStr = String(lovVal).toLowerCase();
					var Strsrch = String(filterObj[3]).toLowerCase();
					var result = srcStr.indexOf(Strsrch) >= 0;
					if(result>=0)
					flag=0;					
					break;
				}
			}
			
			if(flag==0){
			tempRowsTable+='<tr id = "TRlog_'+cl_id+'_'+dataId+'" onmouseover="javascript:FileMouseOver1(this);"onmouseout="javascript:FileMouseOut1(this);" class='+Class2+' onClick="Edit('+dataId+');">';
			tempRowsTable+='<td>&nbsp;</td>';
			tempRowsTable+='<td>'+headerName+'</td>';
			tempRowsTable+='<td>'+ObjElement[2][valObj["filter_type"]]+'</td>';
			tempRowsTable+='<td>'+refmaxVal+'</td>';
			tempRowsTable+='<td>'+ObjElement[4][valObj["filter_auto"]]+'</td>';
			tempRowsTable+='<td>'+ObjElement[5][valObj["read_only"]]+'</td>';	
			tempRowsTable += '<td onmouseup="return fileMouseUP2('+dataId+');" width="4%" id="td_img_'+dataId+'" style="width: 25px; margin-left: 10px; ';
			tempRowsTable += ' background-image: url(\'images/move.gif\');  background-repeat: no-repeat; background-position:center; margin-right: 10px;  ';
			tempRowsTable += ' cursor:move;height:25px;!important" onmousedown="return fileMouseDown2('+dataId+');">&nbsp;</td>';						
			tempRowsTable+='</tr>';
			var clNum2 = (clNum==0)?1:0;			 
			if(lovObj[dataId] && valObj["filter_type"]==2){
				var tempSubRowsTable= "";
				 tempSubRowsTable=renderSubGrid(dataId,clNum2);				 
				if(tempSubRowsTable!=""){
					tempTable +=tempRowsTable+tempSubRowsTable;
				}
			} else{
				tempTable +=tempRowsTable;
			}			
		}		
	}
	return tempTable;
}

function renderSubGrid(idVal,cln)
{
	
	var tempCl = cln;
	var tempSubGridTable  = "";
	var cl_id = dataObj[idVal]["client_id"];
	for(var f in lovObj[idVal]){
		var lovVal = lovObj[idVal][f];
		
		var lFlag=0;
		if(filterObj[3] && filterObj[3]!=""){
			var srcStr = String(lovVal).toLowerCase();
			var Strsrch = String(filterObj[3]).toLowerCase();
			var result = srcStr.indexOf(Strsrch) >= 0;
			if(!result)
			lFlag=1;				
		}
		if(lFlag==0){
			var Class3 = (tempCl%2==0)?"even":"odd";
			tempSubGridTable+='<tr id="TRlog_'+cl_id+'_'+f+'" class='+Class3+'>';
			tempSubGridTable+='<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';		
			tempSubGridTable+='<td>'+lovVal+'</td>';
			tempSubGridTable+='<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';								
			tempSubGridTable+='</tr>';
			tempCl++;
		}
	}
	return tempSubGridTable;
}

function getObjElement(EleType)
{
	try{		
		var tempElemTable ='';
		var x =EleType;		
		
		var FilterVal = ($("#filter_"+objFilter[x]).length>0 && $("#filter_"+objFilter[x]).val()!="")?$("#filter_"+objFilter[x]).val():"";
		if(ObjElement[x]==""){
			return "&nbsp;";
		}  else if(ObjElement[x]==1) {			
			return  tempElemTable+='<input type="text" onkeydown="return filter(event,'+x+');" name="filter_'+objFilter[x]+'" id="filter_'+objFilter[x]+'" value="'+FilterVal+'">';
		} else {
			tempElemTable+='<select onChange="return filter(\'\','+x+');" id="filter_'+objFilter[x]+'" name="filter_'+objFilter[x]+'">';
			tempElemTable+='<option value="">-Select-</option>';
			for(w in ObjElement[x]){
				var sel='';
				if(FilterVal==w){
					sel =' selected="selected" ';
				}
				tempElemTable+='<option '+sel+' value="'+w+'">'+ObjElement[x][w]+'</option>';
			}
			return tempElemTable+='</select>';
		}
	}catch(Error){
		funError("getObjElement","Section : Airworthiness Centre => Header, Main page Js Error <br/> ",Error.toString(),Error);		
	}
}

function fnEdit()
{
	$("#act").val("EDIT");
	enable_disable_buttons('2');
	enableForm();	
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
	rowMainID = ID;
	$("#mainRowid").val(ID);
	setForm(ID);
}
function setForm(id)
{		
	var headerName = $.trim(dataObj[id]["header_name"]);
	if(dataObj[id]["filter_type"]==4){
		var tempHeader = dataObj[id]["header_name"].split("RPL_COMMA");
		var refmaxVal = "";
		if(tempHeader[0])
		headerName =$.trim(tempHeader[0]);
		
		if(tempHeader[1])
		refmaxVal=$.trim(tempHeader[1]);		
		$("#valuesRef").css("display","");
		$("#RefTxt").css("display","none");
		$("#Ref_Auto").val(0);
		if(refmaxVal!=""){
			$("#Ref_Auto").val(1);
			$("#RefTxt").css("display","");
			$("#Ref_txt").val(refmaxVal);
		}
	}
	
	$("#clientVal").val(dataObj[id]["client_id"]);
	$("#field_name").val(headerName);
	$("#filter_type").val(dataObj[id]["filter_type"]);
	if(dataObj[id]["read_only"]==1){
		$("#read_only").attr("checked",true);
		$("#read_only").val(1);
	} else {
		$("#read_only").attr("checked",false);
		$("#read_only").val(0);
	}
	if(dataObj[id]["filter_type"]==2){
		$("#valuesTD").css("display","block");
		var temp_Obj = new Object();		
		if(lovObj[id]){
			for(x in lovObj[id]){
				var valtemp = lovObj[id][x];
				temp_Obj[x]=valtemp;	
			}
		}
		getLovDropDown(temp_Obj,1);
		if((dataObj[id]["filter_auto"]==1)){
			$("#filter_auto").attr("checked",true);
			$("#filter_auto").val(1);
		} else {
			$("#filter_auto").attr("checked",false);
			$("#filter_auto").val(0);
		}		
		
	}
	if(dataObj[id]["filter_type"]==3){
		$("#tdExpiry").css("display","");
		var is_remVal = dataObj[id]["is_reminder"];
		if(is_remVal!=0){		
			$("#tdExpiry").css("display","");
			$("#tdReminder").css("display","");
			$("#is_expiry").val(0);	
			if(is_remVal==1){
				$("#is_expiry").val(1);			
			}
			if(is_remVal==2){
				$("#is_expiry").val(1);			
				$("#is_rem").val(2);
			}
		}
	}
	
}

function fnSave()
{
	var insertObj = new Object();
	var field_val = $.trim($("#field_name").val());
	var clientVal = $("#clientVal").val();
	var filter_type = $("#filter_type").val();	
	
	if(clientVal==0){
		alert("Please Select Client.");
		$("#field_name").focus();
		return false;
	}
	
	if(field_val==""){ 
		alert("Please enter Column Name.");
		$("#field_name").focus();
		return false;		
	}
	var tempNumAdd = "";
	if(filter_type==4){
		if($("#Ref_Auto").val()==1){
			var refTxt = $.trim($("#Ref_txt").val());
			if(refTxt == ""){
				alert("Please enter Reference Text.");
				$("#Ref_txt").focus();
				return false;	
			}
			var refLen = parseInt(refTxt.length);
			var chkLastChar = refTxt[refLen-1];
			var numAdd= '';
			if(isNaN(chkLastChar)){
				alert("Please Add Numeric Character at the end of Reference Text.");
				return false;
			}
			for (var i = refLen - 1; i >= 0; i--){
				if(isNaN($.trim(refTxt[i]))|| $.trim(refTxt[i])==null || $.trim(refTxt[i])=="" || $.trim(refTxt[i])==''){					
					break;
				}
				numAdd= numAdd+refTxt[i];				
			}
		
			if(numAdd!=""){
				tempNumAdd = $.trim(numAdd.split("").reverse().join(""));
			}			
			
			field_val = $.trim($("#field_name").val())+"RPL_COMMA"+refTxt;
		}
		
	}

	var is_remVal = 0;
	if(filter_type==3){
		if($("#is_expiry").val() == 1){
			if($("#is_expiry").val()==1)
			is_remVal =($("#is_rem").length>0)?$("#is_rem").val():1;	
		}		
	}
	if(is_remVal!=0){		
		var expiryAddObj = {0:"Expiry Period",1:"Reminder Period",2:"Reminder Date",3:"Expiry Date"};
		insertObj['ExpiryVal'] = new Object();
		for(var w in expiryAddObj){
			var view_flag= 1;
			if(is_remVal==1){
				if(w==1 || w==2){
					 view_flag= 2;	
				}
			}
			insertObj['ExpiryVal'][w]= {"client_id":clientVal,"header_name":expiryAddObj[w],"filter_type":filter_type,"filter_auto":$("#filter_auto").val(),
					"master_flag":$("#master_flag").val(),"type":$("#type").val(),"is_reminder":0,"view_flag":view_flag};
		}
	}
	
	insertObj[0]= {"client_id":clientVal,"header_name":field_val,"filter_type":filter_type,"filter_auto":$("#filter_auto").val(),
				"master_flag":$("#master_flag").val(),"type":$("#type").val(),"read_only":$("#read_only").val(),"is_reminder":is_remVal,"refMax_value":tempNumAdd,"template_id":$("#template_id").val()};
	if($("#filter_type").val()==2){
		lovInsObj = getLovInsObj();
		
		if(!$.isEmptyObject(lovInsObj)){
			insertObj["lov_value"] = lovInsObj;
		}
	}			
	if($("#comp_id").val()!=0 && $("#comp_main_id").val()!=0 && $("#client_id").val()!=0)
	{
		insertObj[0]['component_id']=$("#comp_id").val();
		insertObj[0]['comp_main_id']=$("#comp_main_id").val();
	}	
	if($("#act").val()=="ADD"){
		var auditObj = getAuditObj();
		auditObj["field_title"] = "Column Name";
		auditObj["action_id"] = 1;
		auditObj["new_value"] = field_val;
		xajax_Save(insertObj,auditObj);
	} else {
		updateData(insertObj);
	}
}

function updateData(insObj)
{
	
	var auditObj = new Object();
	var mainInsobj = insObj[0];
	var mainRowid = $("#mainRowid").val();
	var oldDataobj= dataObj[mainRowid];
	var tempUpObj = new Object();
	var k = 0;	
	/*-------------------------------------------------------audit trail code---------------------------------------------*/
	var changeColumn = {"header_name":"Column Name","filter_type":"Column Field Type"};	
	for(var w in mainInsobj){
		if(oldDataobj[w]!=mainInsobj[w]){
			tempUpObj[w] = 	mainInsobj[w];
		}
		if(oldDataobj[w] && oldDataobj[w]!=mainInsobj[w]){			
			
			auditObj[k] = getAuditObj();
			auditObj[k]["action_id"] = 2;			
			if(w=="header_name"){				
				var headerName =  oldDataobj["header_name"];;
				var new_headerName=  mainInsobj["header_name"];;
				var refOldmaxVal=0;
				var refnewmaxVal=0;
				
				if(headerName.indexOf("RPL_COMMA")>=0){
					var tempHeader = headerName.split("RPL_COMMA");
					var refmaxVal = "";					
					
					if(tempHeader[0] && tempHeader[0]!="")
					headerName =$.trim(tempHeader[0]);					
					
					
					if(tempHeader[1] && tempHeader[1]!="")
					refOldmaxVal=$.trim(tempHeader[1]);
				}
				
				if(new_headerName.indexOf("RPL_COMMA")>=0){
					var tempNewHeader = new_headerName.split("RPL_COMMA");					
					if(tempNewHeader[0])
					new_headerName =$.trim(tempNewHeader[0]);
					
					if(tempNewHeader[1])
					refnewmaxVal=$.trim(tempNewHeader[1]);
				}
				var chk = 0;				
				if(headerName!=new_headerName){
					chk=1;					
					auditObj[k]["field_title"] = changeColumn[w];
					auditObj[k]["old_value"] = headerName;
					auditObj[k]["new_value"] = new_headerName;
				}
				
				if(refOldmaxVal!=refnewmaxVal){					
					if(chk==1){
						k++;
						auditObj[k] = getAuditObj();
						auditObj[k]["action_id"] = 2;
					}					
					auditObj[k]["field_title"] ="Reference Text";					
					auditObj[k]["old_value"] = new_headerName+"&nbsp;&raquo;&nbsp;"+refOldmaxVal;
					auditObj[k]["new_value"] = new_headerName+"&nbsp;&raquo;&nbsp;"+refnewmaxVal;
				}
				
			} else if(w=="filter_auto"){
					auditObj[k]["field_title"] = "Auto Prepare List";
					auditObj[k]["old_value"] = oldDataobj["header_name"]+"&nbsp;&raquo;&nbsp;"+ObjElement[4][oldDataobj["filter_auto"]];
					auditObj[k]["new_value"] = oldDataobj["header_name"]+"&nbsp;&raquo;&nbsp;"+ObjElement[4][mainInsobj["filter_auto"]];
				}
			else if(w=="filter_type"){
				auditObj[k]["field_title"] = changeColumn[w];
				var headerName = oldDataobj["header_name"];
				if(headerName.indexOf("RPL_COMMA")>=0){
					var tempHeader = headerName.split("RPL_COMMA");
					if(tempHeader[0] && tempHeader[0]!="")
					headerName =$.trim(tempHeader[0]);					
				}
				auditObj[k]["old_value"] =headerName+"&nbsp;&raquo;&nbsp;"+ObjElement[2][oldDataobj["filter_type"]];
				auditObj[k]["new_value"] = headerName+"&nbsp;&raquo;&nbsp;"+ObjElement[2][mainInsobj["filter_type"]];
			}
			else if(mainInsobj["filter_type"]==3){
				if(mainInsobj["is_reminder"]!=oldDataobj["is_reminder"]){
					var oldVal = "";
					var oldNewObj={0:"Requires Expiry&nbsp;&raquo;&nbsp;No",1:"Requires Expiry&nbsp;&raquo;&nbsp;Yes",2:"Remider&nbsp;&raquo;&nbsp;Yes"};
					var fieldTitleObj = {0:"Requires Expiry",1:"Requires Expiry",2:"Remider"};
					var is_rem_OldVal = oldDataobj["is_reminder"];
					var is_rem_Val = mainInsobj["is_reminder"];
					auditObj[k]["field_title"] = fieldTitleObj[is_rem_Val];
					auditObj[k]["old_value"] = mainInsobj["header_name"]+"&nbsp;&raquo;&nbsp;"+oldNewObj[is_rem_OldVal];
					auditObj[k]["new_value"] = mainInsobj["header_name"]+"&nbsp;&raquo;&nbsp;"+oldNewObj[is_rem_Val];
				}
			}
			if(!auditObj[k]["field_title"]){
				delete auditObj[k];
			}
			k++;
		}		
	}
	if(oldDataobj["filter_type"]!=mainInsobj["filter_type"]){
		if(oldDataobj["filter_type"]==2){
			insObj["delete_lov_value"] = mainRowid;
		}
	}
	//old_Value = 2 -- delete lov;
	/*-------------------------------------------------------audit trail code---------------------------------------------*/	
	insObj[0]= tempUpObj;
	if(!$.isEmptyObject(insObj)){
		insObj["mainRowid"]= mainRowid;
		insObj["display_order"]= dataObj[mainRowid]["display_order"];
		insObj["client_id"]= dataObj[mainRowid]["client_id"];
		insObj["header_name"]= $("#field_name").val();		
		xajax_Update(insObj,auditObj);
	}
	
}
	

function fnDelete()
{
	if(confirm("Are you sure you want to delete this record?")){
		var mainRowid = $("#mainRowid").val();
		var cl_id=dataObj[mainRowid]["client_id"];
		var clname = clientObj['_'+cl_id];
		var AuditObj = new Object(); 
		AuditObj = getAuditObj();
		AuditObj["old_value"] =clname+"&nbsp;&raquo;&nbsp;"+dataObj[mainRowid]["header_name"];
		AuditObj["client_id"] =dataObj[mainRowid]["client_id"];
		AuditObj["new_value"] ="";
		AuditObj["field_title"] = "Column Name";
		AuditObj["action_id"] = 3;
		xajax_Delete(mainRowid,AuditObj);
	}else{
		return false;
	}
	
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
	delete lovObj[d_id];
	renderGrid();
	fnReset();	
}

function fnAdd()
{
	$("#act").val("ADD");
	$("#mainRowid").val("");
	clearForm();
	enableForm();
	enable_disable_buttons('2');
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
		   }else if(this.name=='clientVal')
		   {
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
function getRemLov(exVal)
{
	if(exVal==1){
		$("#tdReminder").css("display","");	
	} else {
		$("#tdReminder").css("display","none");
	}
}
function SetRefTxt(rmVal)
{
	if(rmVal==1){
		$("#RefTxt").css("display","");	
	} else {
		$("#RefTxt").css("display","none");
	}
}
function showTextBox(val)
{
	$("#filter_auto").attr('checked','');
	$("#filter_auto").val(0);
	$("#valuesTD").css("display","none");
	$("#tdReminder").css("display","none");	
	$("#tdExpiry").css("display","none");
	$("#valuesRef").css("display","none");
	$("#tdReadOnly").css("display","none");
	getLovDropDown();	
	if(val==3){
		if($("#template_id").val()==0 && $("#comp_main_id").val()==0){		
			$("#tdExpiry").css("display","block");		
		}
		$("#tdReadOnly").css("display","");
		
	} else if(val==2){				
		$("#valuesTD").css("display","block");		
	} else if(val==4){
		$("#valuesRef").css("display","");
	}
	
}
function set_lov_values(val,id)
{
	if(val == "Enter Text"){
		$("#filter_values").css("display",'inline');
		$("#insert_lov_value_button").css("display",'inline');
		$("#insert_lov_value_tooltip").css("display",'inline');
	} else {
		$("#filter_values").css("display",'none');
		$("#insert_lov_value_button").css("display",'none');
		$("#insert_lov_value_tooltip").css("display",'none');
	}
	return false;
}

function save_option()
{
	var k =0;	
	var temp_Obj = new Object();
	var tempChkArr =new Array();
	try{
		if($.trim($("#filter_values").val())==""){
			alert("Please Insert Values");					
		} else {
			var val =$.trim($("#filter_values").val());			
			$("#lov option").each(function(){
				if($(this).val()!='' && $(this).val()!="Enter Text"){
					temp_Obj[k]=$(this).text();	
					tempChkArr[k] = $(this).text();	
					k++;
				}
			});			
			if($.inArray(val,tempChkArr)<0){			
				temp_Obj[k]=val;
				getLovDropDown(temp_Obj);
			} else {
				alert("Value Already Exists");
			}	
		}
	}
	catch(e){		
		funError("save_option","Section : Airworthiness Centre => Center => header, Main page Js Error <br/> ",Error.toString(),Error);
	}
}
function getLovDropDown(Obj,disflag)
{
	try{
		var temp ='';
		var dis='';
		if(disflag==1){
			dis+=' disabled="disabled" ';
		}
		temp='<select '+dis+' id="lov" name="lov" align="center" valign="middle" nowrap="nowrap" onchange="set_lov_values(this.value,this.id)">';
		temp+='<option value="">-- Select --</option>';
		temp+='<option style="background-color:#CC99FF;" value="Enter Text">Enter Text</option>'; 
		
		if(!$.isEmptyObject(Obj)){
			for(x in Obj){
				if(Obj[x]!="")
				temp+='<option value="'+x+'">'+Obj[x]+'</option>';
			}                                                   
		}
		temp+=' </select>';
		temp+=' <input type="text" style="display:none;"  name="filter_values" id="filter_values"/>	';
		temp+=' <img border="0"  style="display:none;" onclick="save_option();"  name="insert_lov_value_button" id="insert_lov_value_button" title="Save" src="images/tickmark.png"/> ';
		temp+=' <img width="18" id="insert_lov_value_tooltip"  height="18" onmouseover="Tip(\'Please enter each value one at a time and press the `green tick` after entering each value. <br>';
		temp+=' This will build your List of Values quickly - once everything is input please remember <br> to save the final entry using the Save button on the right.\')" ';
		temp+=' onmouseout="UnTip()" border="0" src="images/per_questionmark.png" style="cursor:pointer;display:none;">';
		$("#lov_div").html(temp);
	}catch(Error){
		funError("getLovDropDown","Section : Airworthiness Centre => Header, Main page Js Error <br/> ",Error.toString(),Error);		
	}
	
}
function fnReset()
{
	clearForm();
	disableForm();
	enable_disable_buttons('0');
	$("#valuesTD").css("display","none");
	$("#valuesRef").css("display","none");	
	$("#tdReminder").css("display","none");
	$("#tdExpiry").css("display","none");
	$("#RefTxt").css("display","none");
	$("#tdReadOnly").css("display","none");
	$("#act").val("");
	$("#mainRowid").val("");
	getLovDropDown();
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

function getAuditObj()
{
	var tempAudit = new Object();
	tempAudit={"user_id":UserID,"user_name":user_name,"section":$("#sectionVal").val(),"sub_section":$("#sub_sectionVal").val(),"type":$("#type").val(),"client_id":$("#clientVal").val()};	
	if($("#comp_main_id").val()!='' && $("#sectionVal").val()==3){
		tempAudit['comp_main_id'] = $("#comp_main_id").val();
	}
	return tempAudit;
}
function getLovInsObj()
{
	var tempLovObj = new Object();
	var tempArr = new Array();
	var EditId = $("#mainRowid").val();
	if(lovObj[EditId] && $("#act").val()=="EDIT"){
		for(var t in lovObj[EditId]){
			tempArr[t]= lovObj[EditId][t];
		}
	}
	
	$("#lov option").each(function(){		
		if($(this).val()!="" && $(this).val()!="Enter Text" && $.inArray($(this).text(),tempArr)<0){					
				tempLovObj[$(this).val()] = {"lov_value":$(this).text(),"client_id":$("#clientVal").val(),"type":$("#type").val()};			
		}
	});	
	return tempLovObj;
}
function changeCheckVal(elm)
{
	if(elm.checked==true){
		elm.value=1;
	} else {
		elm.value=0;
	}	
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

function updateRow(fobj)
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
			if(Object.keys(fobj["lovArr"]).length>0){
				lovObj = $.extend(lovObj,fobj["lovArr"]);
			}
			dispObj[clId][dispOdr] = addedId;
			dataObj[addedId]= fobj['data'];
			renderGrid();
			fnReset();
		}
	}catch(Error){
		funError("updateRow","Section : Airworthiness Centre => Header, Main page Js Error <br/> ",Error.toString(),Error);
	}
	
}

function updateAddedRow(ufobj)
{	
	var addedId = 0;	
	try{		
		if(Object.keys(ufobj).length>0){			
			addedId = ufobj["addedId"];
			if(ufobj["lovArr"]!="" && Object.keys(ufobj["lovArr"]).length>0){
				lovObj = $.extend(lovObj,ufobj["lovArr"]);
			}
			for(var k in ufobj['data']){
				if(dataObj[addedId][k]!=ufobj['data'][k]){					
					dataObj[addedId][k]= ufobj['data'][k];
				}
			}			
			renderGrid();
			fnReset();
		}
	}catch(Error){
		funError("getLovDropDown","Section : Airworthiness Centre => Header, Main page Js Error <br/> ",Error.toString(),Error);
	}
	
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
	AuditObj["field_title"] = "Column Name";
	AuditObj["old_value"] = clName+"&nbsp;&raquo;&nbsp;"+dataObj[FromDispId]["header_name"];
	AuditObj["new_value"] = clName+"&nbsp;&raquo;&nbsp;"+dataObj[todid]["header_name"];	
	AuditObj["action_id"] = 4;
	AuditObj["client_id"] = fromClId;
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
function fnOpenLovTab()
{
	var type = $("#type").val();
	var section= $("#sectionVal").val();
	var clientVal= $("#clientVal").val();
	var template_id= $("#template_id").val();
	var comp_id= $("#comp_id").val();
	var comp_main_id= $("#comp_main_id").val();
	var addStr='';
	if(clientVal!=0){
		addStr+='&client_id='+clientVal;
	}
	if(template_id!=0){
		addStr+='&template_id='+template_id;
	}
	if(comp_id!=0){
		addStr+='&comp_id='+comp_id;
	}
	if(comp_main_id!=0){
		addStr+='&comp_main_id='+comp_main_id;
	}
	var airworthireorder = window.open('airworthiness_centre.php?section='+section+'&type='+type+'&sub_section=3'+addStr,
										'airworthireorder','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes')
	airworthireorder.focus();
}

function open_parent(flg)
{
	var type = $("#type").val();
	var section= $("#sectionVal").val();
	var master_flag= $("#master_flag").val();
	var client_id= $("#client_id").val();
	var comp_id= $("#comp_id").val();
	var comp_main_id= $("#comp_main_id").val();
	var client_id= $("#client_id").val();
	
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
function Open_Header_Audit()
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
	var headerAudit = window.open('airworthiness_centre_audit.php?section='+section+'&sub_section='+sub_section+'&type='+type+addStr,'headerAudit',
								 'height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	headerAudit.focus();
}

function reloadWin()
{	
	if($("#comp_main_id").val()!=0){
		window.opener.location.reload();
	}
}