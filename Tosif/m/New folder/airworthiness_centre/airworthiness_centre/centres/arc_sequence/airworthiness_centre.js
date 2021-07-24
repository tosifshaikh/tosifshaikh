var BtnArr =new Array("addBtn","editBtn","saveBtn","deleteBtn");
var objHeader = {0:"Client",1:"Cycle Number",2:"ARC Type Description",3:"Expiry Period",4:"Reminder Period",5:"Check List",6:"Select Template"};
var objFilter = {0:"Client",1:"Cycle_Number",2:"ARC_Type_Description",3:"Expiry_Period",4:"Reminder_Period",5:"Check_List",6:"Select_Template"};
var ObjElement = {0:"",1:1,2:1,3:1,4:1,5:{0:"No",1:"Yes"},6:1};
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
var ValidateArr = new Object();
var TemplateArr= new Object();

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
function Gettemaplatelov()
{
	var strtempcombo='<select disabled="disabled" tabindex="2" id="ddl_template" name="ddl_template">';
	strtempcombo+='<option value="">Select Template</option>';
	var client_id=$("#clientVal").val();
	if(client_id!=0){
		for(tid in TemplateArr[client_id]){		
			strtempcombo+='<option value='+tid+'>'+TemplateArr[client_id][tid]+'</option>';
		}
	}
	strtempcombo+='</select>';
	$("#airworthi_template").html(strtempcombo);
}
function loadGrid(client_id)
{
	try{	
		fnReset();	
		var sectionVal = $("#sectionVal").val();
		var sub_sectionVal= $("#sub_sectionVal").val();
		var type = $("#type").val();		
		Gettemaplatelov(client_id)
	    var param = "section="+sectionVal+"&sub_section="+sub_sectionVal+"&act=GRID&type="+type+"&client_id="+client_id;		
		$.ajax({url: "airworthiness_centre.php?t="+(new Date()), async:true,type:"POST",data:param,success: function(data){		  
		mainObj = eval("("+data+")");
		dataObj = mainObj.data;
		clientObj= mainObj.client;
		dispObj =mainObj.dispData; 
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
		//Chkinit();
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
	
	//alert(JSON.stringify(clientObj));
	
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
			var header_name = valObj["header_name"];	
			var temp_arr=TemplateArr[cl_id];			
			
			var FilterValObj={1:dataObj[dataId]["display_order"],2:header_name,3:dataObj[dataId]["expiry_period"],4:dataObj[dataId]["reminder_period"],5:dataObj[dataId]["check_list"],6:TemplateArr[dataObj[dataId]["client_id"]][dataObj[dataId]["template_id"]]};
			//var FilterValObj={1:headerName,2:dataObj[dataId]["filter_type"],3:refmaxVal,4:dataObj[dataId]["filter_auto"],5:dataObj[dataId]["read_only"]};
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
			if(filterObj[5] && filterObj[5]!=""){
				flag=1;				
				if(valObj["check_list"]==filterObj[5]){
					flag=0;		
				}				
			}		
		
			if(flag==0){
			tempRowsTable+='<tr id = "TRlog_'+cl_id+'_'+dataId+'" onmouseover="javascript:FileMouseOver1(this);"onmouseout="javascript:FileMouseOut1(this);" class='+Class2+' onClick="Edit('+dataId+');">';
			tempRowsTable+='<td>&nbsp;</td>';			
			tempRowsTable+='<td>'+valObj["display_order"]+'</td>';
			tempRowsTable+='<td>'+header_name+'</td>';
			tempRowsTable+='<td>'+valObj["expiry_period"]+'</td>';
			tempRowsTable+='<td>'+valObj["reminder_period"]+'</td>';
			tempRowsTable+='<td>'+ObjElement[5][valObj["check_list"]]+'</td>';				
			tempRowsTable+='<td>'+temp_arr[valObj["template_id"]]+'</td>';	
			tempRowsTable+='</tr>';						
		}		
	}
	return tempRowsTable;
}
function set_dislay_combo(clVal)
{
	var display_combo="<select disabled='disabled' id='cycle_number' name='cycle_number'><option value='0'>Select Cycle Number</option>";
	var cntRow=0;
	if(dispObj[clVal]!="" && dispObj[clVal]!=null){
		cntRow = Object.keys(dispObj[clVal]).length;	
	}
	if($("#act").val()=="ADD")
		cntRow=cntRow+1;
	for(var i=1; i<=cntRow;i++){
		display_combo+="<option value="+i+">"+i+"</option>";
	}
	display_combo+="</select>";
	$("#tdcyclenum").html(display_combo);	
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
	var header_name = dataObj[id]["header_name"];
	//$("#clientVal").val(dataObj[id]["client_id"]);
	set_dislay_combo(dataObj[id]["client_id"])
	$("#arc_type_description").val(header_name);
	$("#ExpirePrdDDL").val(dataObj[id]["expiry_period"]);
	getReminderCombo(0,'ExpirePrdDDL',dataObj[id]["expiry_period"],'ReminderPrdDDL',1) 
	$("#ReminderPrdDDL").val(dataObj[id]["reminder_period"]);
	$("#ddl_template").val(dataObj[id]["template_id"]);
	$("#cycle_number").val(dataObj[id]["display_order"]);
	$("#check_list").val(dataObj[id]["check_list"]);	
}

function fnSave()
{
	var insertObj = new Object();
	var header_name = $.trim($("#arc_type_description").val());
	var clientVal = $("#clientVal").val();	
	
	if(clientVal==0){
		alert("Please Select Client.");
		$("#clientVal").focus();
		return false;
	}	
	if(header_name==""){ 
		alert("Please Enter Description.");
		$("#arc_type_description").focus();
		return false;		
	}
	if($("#ExpirePrdDDL").val()==0){ 
		alert("Please Select Expiry Period.");
		$("#ExpirePrdDDL").focus();
		return false;		
	}
	if($("#ReminderPrdDDL").val()==0){ 
		alert("Please Select Reminder Period.");
		$("#ReminderPrdDDL").focus();
		return false;		
	}
	if($("#ddl_template").val()==0){ 
		alert("Please Select Template.");
		$("#ddl_template").focus();
		return false;		
	}
	if($("#cycle_number").val()==0){ 
		alert("Please Select Cycle Number.");
		$("#cycle_number").focus();
		return false;		
	}
	
	insertObj[0]= {"header_name":header_name,"expiry_period":$("#ExpirePrdDDL").val(),"reminder_period":$("#ReminderPrdDDL").val(),
				"template_id":$("#ddl_template").val(),"type":$("#type").val(),"display_order":$("#cycle_number").val(),"client_id":clientVal,"check_list":$("#check_list").val()};	
	
	if($("#act").val()=="ADD"){
		var auditObj = getAuditObj();
		auditObj["field_title"] = "ARC Type Description";
		auditObj["action_id"] = 64;
		auditObj["new_value"] = header_name;
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
	var changeColumn = {"Cycle_Number":"Cycle Number","header_name":"ARC Type Description","expiry_period":"Expiry Period","reminder_period":"Reminder Period","check_list":"Check List","template_id":"Select Template"};	
	for(var w in mainInsobj){
		if(oldDataobj[w]!=mainInsobj[w]){
			tempUpObj[w] = 	mainInsobj[w];
		}
		if(oldDataobj[w] && oldDataobj[w]!=mainInsobj[w]){						
			auditObj[k] = getAuditObj();
			auditObj[k]["action_id"] = 66;	
	
			if(w=="header_name"){				
				var header_name =  oldDataobj["header_name"];;
				var new_header_name=  mainInsobj["header_name"];;			
				
				if(header_name!=new_header_name){				
					auditObj[k]["field_title"] = changeColumn[w];
					auditObj[k]["old_value"] = header_name;
					auditObj[k]["new_value"] = new_header_name;
				}				
				
			} else if(w=="expiry_period"){
				if(oldDataobj["expiry_period"]!=mainInsobj["expiry_period"])
				{
					auditObj[k]["field_title"] = changeColumn[w];
					auditObj[k]["old_value"] = oldDataobj["header_name"]+"&nbsp;&raquo;&nbsp;"+oldDataobj["expiry_period"];
					auditObj[k]["new_value"] = oldDataobj["header_name"]+"&nbsp;&raquo;&nbsp;"+mainInsobj["expiry_period"];
				}
			}
			else if(w=="reminder_period"){
				if(oldDataobj["reminder_period"]!=mainInsobj["reminder_period"])
				{
					auditObj[k]["field_title"] = changeColumn[w];
					auditObj[k]["old_value"] = oldDataobj["header_name"]+"&nbsp;&raquo;&nbsp;"+oldDataobj["reminder_period"];
					auditObj[k]["new_value"] = oldDataobj["header_name"]+"&nbsp;&raquo;&nbsp;"+mainInsobj["reminder_period"];
				}
			}
			else if(w=="template_id"){
				if(oldDataobj["template_id"]!=mainInsobj["template_id"])
				{
					auditObj[k]["field_title"] = changeColumn[w];
					auditObj[k]["old_value"] = oldDataobj["header_name"]+"&nbsp;&raquo;&nbsp;"+TemplateArr[oldDataobj["client_id"]][oldDataobj["template_id"]];
					auditObj[k]["new_value"] = oldDataobj["header_name"]+"&nbsp;&raquo;&nbsp;"+TemplateArr[oldDataobj["client_id"]][mainInsobj["template_id"]];
				}
			}
			else if(w=="check_list"){
				if(oldDataobj["check_list"]!=mainInsobj["check_list"])
				{
					auditObj[k]["field_title"] = changeColumn[w];
					auditObj[k]["old_value"] = oldDataobj["header_name"]+"&nbsp;&raquo;&nbsp;"+ObjElement[5][oldDataobj["check_list"]];
					auditObj[k]["new_value"] = oldDataobj["header_name"]+"&nbsp;&raquo;&nbsp;"+ObjElement[5][mainInsobj["check_list"]];
				}
			}
			if(!auditObj[k]["field_title"]){
				delete auditObj[k];
			}
			k++;
		}		
	}	
	//old_Value = 2 -- delete lov;
	/*-------------------------------------------------------audit trail code---------------------------------------------*/	
	insObj[0]= tempUpObj;
	if(!$.isEmptyObject(insObj)){
		insObj["mainRowid"]= mainRowid;
		insObj["display_order"]= dataObj[mainRowid]["display_order"];
		insObj["client_id"]= dataObj[mainRowid]["client_id"];
		insObj["header_name"]= $("#arc_type_description").val();		
		xajax_Update(insObj,auditObj,oldDataobj);
	}
}
	

function fnDelete()
{
	if(confirm("Are you sure you want to delete this record?")){
		var mainRowid = $("#mainRowid").val();
		var cl_id=dataObj[mainRowid]["client_id"];
		var clname = clientObj['_'+cl_id];
		var deleteobj = new Object();
		deleteobj["mainRowid"]=mainRowid;
		deleteobj["client_id"]=cl_id;		
		deleteobj["del_disp"]=dataObj[mainRowid]["display_order"];
		var AuditObj = new Object(); 
		AuditObj = getAuditObj();
		AuditObj["old_value"] =clname+"&nbsp;&raquo;&nbsp;"+dataObj[mainRowid]["header_name"];
		AuditObj["client_id"] =dataObj[mainRowid]["client_id"];
		AuditObj["new_value"] ="";
		AuditObj["field_title"] = "ARC Type Description";
		AuditObj["action_id"] = 65;
		xajax_Delete(deleteobj,AuditObj);
	}else{
		return false;
	}
	
}
function updateDeleterec(fobj)
{
	dispObj[clId]=fobj["disp"];
	var d_id = fobj["delete_id"];
	var clId = dataObj[d_id]["client_id"];		
	delete dataObj[d_id];
	if(!dispObj[clId]){
		dispObj[clId] = new Object();
	}
	dispObj[clId]=fobj["disp"];
	for(var d in dispObj[clId]){	
		dataObj[dispObj[clId][d]]["display_order"]=d;
	}	
	renderGrid();
	fnReset();	
}

function fnAdd()
{
	$("#act").val("ADD");
	$("#mainRowid").val("");
	if(clientObj!="" && clientObj!=null){
		for(a in clientObj){
			var clVal = a.replace("_","");
			set_dislay_combo(clVal);
		}
	}
	else
	{
		var clent_obj=new Object();
		clent_obj["_"+$("#clientVal").val()]=$("[name='clientVal'] option:selected").text();
		clientObj=clent_obj;
		set_dislay_combo($("#clientVal").val());
	}	
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
	$("#act").val("");
	$("#mainRowid").val("");
	
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
			clId = fobj['data']['client_id'];			
			addedId = fobj["addedId"];
			dispOdr = fobj['data']['display_order'];
			if(!dispObj[clId]){
				dispObj[clId] = new Object();
			}
			dispObj[clId]=fobj["disp"];
			
			dataObj[addedId]= fobj['data'];		
			for(var d in dispObj[clId]){	
				dataObj[dispObj[clId][d]]["display_order"]=d;
			}
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
function fnaudit()
{
	var type = $("#type").val();	
	var	section= $("#sectionVal").val();	
	var	sub_section= $("#sub_sectionVal").val();	
	var addStr = '';
	
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
function getValidateExpCombo(flg,recID,selVal,IsDi)
{
	var validateObj = eval("("+ValidateArr+")");

	var Str='';
	var idVal='';
	var DayArr=new Array();
	var MonthArr=new Array();
	
	Str+='onChange="getReminderCombo(0,\''+recID+'\',this.value,\''+selVal+'\') "';
	idVal=recID;
	var isDisab = (IsDi)?'disabled="disabled"':'';
	var ValidateCombo='<select id='+idVal+' name='+idVal+' '+Str+' '+isDisab+' style="width:60%">';
	ValidateCombo+='<option value="0">[Select Expiry Period]</option>';
	if(validateObj.parent!="")
	{
		for(x in validateObj.parent)
		{
			var Sel='';
			if(selVal!='' && selVal==x)
			{
				Sel='selected="selected"';
			}
			var Key =x.substr(0,x.length-1);
			if(x.slice(-1)=='d')
			DayArr[Key]='<option '+Sel+' value="'+x+'">'+validateObj.parent[x]+'</option>';
			else if(x.slice(-1)=='m')
			MonthArr[Key]='<option '+Sel+' value="'+x+'">'+validateObj.parent[x]+'</option>';
			//ValidateCombo+='<option value="'+x+'" '+Sel+'>'+validateObj.parent[x]+'</option>';
		}
	}
	if(MonthArr!="" || DayArr!="")
	{
		ValidateCombo+=DayArr.join("")+MonthArr.join("");
	}
	ValidateCombo+='</select>';
	document.getElementById("expiry_combo").innerHTML= ValidateCombo;	
}
function getReminderCombo(SelVal,source_,MonthVal,destination_,IsDi)
{
	var remTable= ''; 	
	var ExpObj = eval("("+ExpArr+")");
	
	var remStr='';
	var remidVal='';
	var	WeeekArr =new Array();
	var	DayArr =new Array();
	var	MonthArr =new Array();
	var ChkArr = new Array();
	
	remStr+='onChange=" "';
	remidVal=destination_;
	var isDisab = (IsDi)?'disabled="disabled"':'';	
	remTable+='<select id='+remidVal+' name='+remidVal+' '+remStr+' '+isDisab+' style="width:60%">';
	remTable+='<option value="0">[Select Reminder Period]</option>';
	
	var k=0;
	
	for(z in ExpObj.parent)
	{
		var RemPeriod =ExpObj.parent[MonthVal];
		for(z in RemPeriod)
		{				
			var Sel	='';
			if(SelVal==z)
			{
				var Sel ="selected=selected";
			}
			var Key =z.substr(0,z.length-1);
			if(z.slice(-1)=='d')
			DayArr[Key]='<option '+Sel+' value="'+z+'">'+RemPeriod[z]+'</option>';
			else if(z.slice(-1)=='w')
			WeeekArr[Key]='<option '+Sel+' value="'+z+'">'+RemPeriod[z]+'</option>';
			else if(z.slice(-1)=='m')
			MonthArr[Key]='<option '+Sel+' value="'+z+'">'+RemPeriod[z]+'</option>';
		}		
	}
	
	remTable+=DayArr.join("")+WeeekArr.join("")+MonthArr.join("");
	remTable+='</select>';
	document.getElementById("remider_combo").innerHTML = remTable;
}
function getairworthitemplate(client_id,type,template_type)
{
	xajax_get_airwothi_template(client_id,type,template_type);
}
function gettemparr(temparr)
{
	TemplateArr=JSON.parse(temparr);
}