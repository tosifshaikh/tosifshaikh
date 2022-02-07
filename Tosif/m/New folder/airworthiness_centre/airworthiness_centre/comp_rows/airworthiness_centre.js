var mainHeaderObj = new Object();
var headerObj= new Object();
var UserID = 0;
var UserLevel = 0;
var startLimit=0;
var Total =0;
var user_name = '';
var tab_name= '';
var filterValObj = new Object();
var LovValueCheck =  new Object();
var dataObj = new Object();
var statusObj= new Object();
var autoFilterObj = new Object();
var lovObj = new Object();
var actDisObj = new Object();
var currentID = 0;
var expRemObj = new Object();
var is_reminderVal=0;
var saveStatusAllObj= new Object();
var expremColumnObj = new Object();
var arcsequncearr = new Object();
var excludeColummArr = new Array("Expiry Period","Reminder Period","Expiry Date","Reminder Date");
var notesObj= new Object();
var monthNames = ["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"];
var noteClassObj={0:"bluecomm_box",1:"greencomm_box",3:"bluecomm_box",5:"pinkcomm_box"};
var checklistObj={0:"No",1:"Yes"};
var firstDataObj = new Object();
var HideVal = "HD";
var monthWeekObj = {d:"Day",m:"Month",w:"Week"};
var compDetailObj = new Object();
var widthObj = {"cs_history":"10%","View":"1%"};
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

SimpleContextMenu.setup({'preventDefault':true, 'preventForms':false});
SimpleContextMenu.attach('add_row', 'contextmenu_Add');
SimpleContextMenu.attach('odd', 'contextmenu');
SimpleContextMenu.attach('even', 'contextmenu');
SimpleContextMenu.attach('strikeLine', 'contextmenu_delete');
SimpleContextMenu.attach('blockbg', 'contextmenu_deleteCell');
SimpleContextMenu.attach('Inbox', 'contextmenu_inbox');
Object.keys=Object.keys||function(o,k,r){r=[];for(k in o)r.hasOwnProperty.call(o,k)&&r.push(k);return r}

function getLoadingCombo(flg)
{
	var elm =$("#LoadingDivCombo");
	if(flg ==1){
		elm.css({"width":"100%","height":"100%","display":""});
	} else {
		elm.css({"width":0,"height":0,"display":"none"});		
	}
}

function loadGrid()
{
	try{
		$("#act").val("");	
		var HeaderLength = Object.keys(headerObj).length;
		var colObj = new Object();
		var tempObj1 = new Object();
		var tempObj2 = new Object();
		for(hid in mainHeaderObj){
			var hidArr = hid.split("_");		
			var HeaderID = hidArr[1];
			var ColVal = mainHeaderObj[hid]['column_id'];
			if(headerObj[hid]){
				colObj[hid]=ColVal;		
			}
			
			filterValObj[ColVal]=($("#fd_filter_"+ColVal).length>0 && $("#fd_filter_"+ColVal).val()!='')?$("#fd_filter_"+ColVal).val():"";	
			if(mainHeaderObj[hid]['filter_type']==2){
				tempObj1[HeaderID]=HeaderID;		
			}
			if(mainHeaderObj[hid]['filter_auto']==1){
				tempObj2[HeaderID]=ColVal;
			}
		}
		
		LovValueCheck={"lovCol":tempObj1,"lovFilterAutoCol":tempObj2};
		var client_id = $("#client_id").val();	
		var comp_id = $("#comp_id").val();	
		var type = $("#type").val();	
		var section = $("#sectionVal").val();
		var sub_section = $("#sub_sectionVal").val();	
		var params='';
		params="section="+section+"&sub_section="+sub_section+"&startLimit="+startLimit+"&type="+type+"&act=GRID&comp_id="+comp_id+"&headerObj="+JSON.stringify(colObj);
		params+='&LovValueCheck='+JSON.stringify(LovValueCheck)+'&client_id='+client_id+'&isdelVal='+HideVal;
		if($("#inboxmod").val()==1)
			params+="&inboxmod=1&UID="+$("#UID").val();
		var searchObj = new Object();
		if(!$.isEmptyObject(filterValObj)){
			for(s_id in filterValObj){			
				if(filterValObj[s_id]!=""){
					searchObj[s_id]=filterValObj[s_id];
				}
			}
			if(!$.isEmptyObject(searchObj)){
				params+='&searchObj='+JSON.stringify(searchObj);
			}
		}
		if(HeaderLength>0){
		getLoadingCombo(1);	
		$.ajax({url: "airworthiness_centre.php", async:true,type:"POST",data:params,success: function(data){
			dataObj = eval("("+data+")");
			autoFilterObj = dataObj.autofiletrVal;
			notesObj= dataObj.notesArr;
			renderGrid();}});
		} else { 
			$("#divGird").html("<strong>No Records Found.</strong>");
		}
	} catch(Error){
		funError("loadGrid","Section : Airworthiness Centre => Component, Main page Js Error <br/> ",Error.toString(),Error);		
	}
}
function renderGrid()
{
	try{
		
	if(!$.isEmptyObject(autoFilterObj)){
		for(aa in autoFilterObj){
			var temopObj = new Object();
			temopObj = autoFilterObj[aa].split(",");
			if(!$.isEmptyObject(temopObj)){
				if(!lovObj[aa]){
					lovObj[aa] = new Object();
				}
				for(bb in temopObj){
					if(temopObj[bb].indexOf("$#@@#$")>=0){
						var tempLarr = temopObj[bb].split("$#@@#$");
						var tempLVal =tempLarr[0];
						lovObj[aa]['_'+tempLVal]=tempLarr[1];
					} else {
						if(!lovObj[aa]['_'+temopObj[bb]]){
							lovObj[aa]['_'+temopObj[bb]]=0;
						}
					}
					
				}
			}			
		}		
	}
		var headerCount =Object.keys(mainHeaderObj).length;
		Total =dataObj.totalRows;
		var table = '<div  id="maintablewidth1"><table width="100%" cellspacing="1" cellpadding="3" border="0" class="tableContentBG" id="m_tablewidth" ></table></div>';
		table+='<table width="100%" cellspacing="1" id="maintablewidth" cellpadding="3" border="0" class="tableContentBG">';
		if($("#inboxmod").val()!=1)		
		{
			table+='<tr id="h_row1"><td align="right" class="tableCotentTopBackground" height="35" id="divTopPagging" colspan="'+headerCount+'"></td></tr>';			
		}
		else
		{
			$("#total").html(Total);			
		}
		table +='<tr id="h_row2"><td  class="tableCotentTopBackgroundNew" colspan="'+headerCount+'"></td></tr>';
		table +=getHeaderRow();	
		table +=getFilterRow();	
		table +=getGridData();
		table +='</table>';
		table+='<table width="100%" cellspacing="1" cellpadding="3" border="0"><tr><td align="right" height="45" id="divBottomPagging"></td></tr></table>';
		$("#divGird").html(table);
		getLoadingCombo(0);
		getPagging(startLimit,100,Total);	
		freezPane();
		var fsId = firstDataObj['firstId'].replace("_",""); 
		updateParentWindow(fsId);		
	} catch(Error){			
		funError("renderGrid","Section : Airworthiness Centre => Component, Main page Js Error <br/> ",Error.toString(),Error);		
	}
}


function freezPane()
{
	if($('#onImg').is(':checked')){
			changeFreez_ultra_dmc('F');
	}else if($('#offImg').is(':checked')){
		changeFreez_ultra_dmc('UF');
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

function getHeaderRow()
{
	var tempHeaderTable='';	
	tempHeaderTable+='<tr id="h_row3" class="tableWhiteBackground setinternalwidth">';
	for(hid in mainHeaderObj){
		var headerName = mainHeaderObj[hid]['header_name'];		
		var widthStr = "";
		if(widthObj[hid]){
			widthStr+=" width='"+widthObj[hid]+"' ";
		}
		tempHeaderTable+='<td '+widthStr+' align="left" class="tableCotentTopBackgroundNew">'+headerName+'</td>';	
	}	
	return tempHeaderTable+='</tr>';
}

function getFilterRow()
{
	var tempFilterTable = '';
	tempFilterTable+='<tr id="h_row4">';	
	for(fid in mainHeaderObj){
		var Colid = mainHeaderObj[fid]['column_id'];
		var fType = mainHeaderObj[fid]['filter_type'];
		var addStr = '';
		if(fType==3){
			addStr+=' nowrap="nowrap" ';
		}
		var colVal = (filterValObj[Colid] && filterValObj[Colid]!='')?filterValObj[Colid]:"";
		tempFilterTable +='<td '+addStr+' align="left" class="tableCotentTopBackgrounddark">'+getObjElement(fid,colVal,0)+'</td>';	
	}
	return tempFilterTable +='</tr>';
}


function getStatusLov(rid)
{
	try {
		var statusLovStr = '';
		var StatusVal = '';	
		if(rid){
			StatusVal = dataObj.rowData[rid]['work_status'];
			
			var StatusBackClrStr = '';
			if(statusObj["_"+StatusVal]){
				StatusBackClrStr = statusObj["_"+StatusVal]['bg_color'];
			} else{
				StatusVal = '';	
			}
			
			var RecIDArr = rid.split("_");
			RecId = RecIDArr[1];
			statusLovStr='<select  onchange="changeRowVal(\'work_status\',this.value,'+RecId+');" id="work_status'+rid+'" name="work_status'+rid+'" class="selectauto" style="background-color:'+StatusBackClrStr+';">';		
		} else {
			StatusVal = filterValObj['work_status'];
			statusLovStr='<select id="fd_filter_work_status" name="fd_filter_work_status" onChange="loadGrid();" class="selectauto">';
			statusLovStr+='<option value="">-Select Status-</option>';		
		}
		for(st_id in statusObj){
			var sel = '';
			var StIDArr = st_id.split("_");
			var StID= StIDArr[1];
			
			var isEnable=statusObj[st_id]['enable'];
			var disable='';
			if(isEnable==1 && rid!=0)
			var disable='disabled="disabled"';
			
			if(StatusVal==StID){
				sel+='selected="selected"';
			}
			statusLovStr+='<option '+sel+' style="background-color:'+statusObj[st_id]['bg_color']+';" value="'+StID+'" '+disable+' >'+statusObj[st_id]['status_name']+'</option>';	
		}	
		statusLovStr+='</select>';
		return statusLovStr;
	}catch(Error){
		funError("getStatusLov","Section : Airworthiness Centre => Component, Main page Js Error <br/> ",Error.toString(),Error);		
	}
}

function getViewIcon(RowID)
{
	var iconStr='';
	var Readonly = 0;
	if(RowID!=0)
	Readonly=isReadonly(RowID);
	
	
	
	
	
	if($("#act").val()=="EDIT"){
		var RowIDArr = RowID.split("_");
		iconStr += '<a onclick="return saveRow('+RowIDArr[1]+');" href="javascript:void(0)"><img border="0" title="Save" src="images/tickmark.png"></a>';
	} else {
		if(RowID!=0 && Readonly!=1){
			var classname="view_active_icon";
			if(dataObj.rowData[RowID]['template_id']==0){			
				classname="view_active_icon_disable";
			}
			iconStr += '<a onclick="openViewArea()" href="javascript:void(0)"><div style="float:left" class="'+classname+'"></div></a>';
		}
	}
	return iconStr;
}
function getchecklist(rid)
{
	try {
		var checklistLovStr = '';
		var checklistval = '';	
		if(rid){
			checklistval = dataObj.rowData[rid]['check_list'];
			
			var RecIDArr = rid.split("_");
			RecId = RecIDArr[1];
			statusLovStr='<select  onchange="changeRowVal(\'check_list\',this.value,'+RecId+');" id="check_list'+rid+'" name="check_list'+rid+'" class="selectauto" >';		
		} else {
			checklistval = filterValObj['check_list'];
			statusLovStr='<select id="fd_filter_check_list" name="fd_filter_check_list" onChange="loadGrid();" class="selectauto">';
			statusLovStr+='<option value="">-Select Check List-</option>';		
		}
		for(check_id in checklistObj){
			var sel = '';	
			
			if(checklistval==check_id){
				sel+='selected="selected"';
			}
			statusLovStr+='<option '+sel+'" value="'+check_id+'">'+checklistObj[check_id]+'</option>';	
		}	
		statusLovStr+='</select>';
		return statusLovStr;
	}catch(Error){
		funError("getStatusLov","Section : Airworthiness Centre => Component, Main page Js Error <br/> ",Error.toString(),Error);		
	}
}
function getObjElement(col_id,ColumnVal,rec_id)
{
	var autoFilter =  mainHeaderObj[col_id]['filter_auto'];
	var filterType = mainHeaderObj[col_id]['filter_type'];
	var ColIdName = mainHeaderObj[col_id]['column_id'];
	var is_reminder = mainHeaderObj[col_id]['is_reminder'];
	var ColName = mainHeaderObj[col_id]['header_name'];
	var refMax_value = mainHeaderObj[col_id]['refMax_value'];
	var read_only = mainHeaderObj[col_id]['read_only'];
	var edit_flag= mainHeaderObj[col_id]['edit_flag'];
	
	var tempElement = '';
	var addStr='';
	var selVal ='';
	
	if(ColumnVal){
		selVal = ColumnVal;
	}
	
	var mainRecID = 0;
	if(rec_id && rec_id!=0){
		mainRecID = rec_id;		
	}	
	
	var IdStr='';
	var addFun='';
	
	var addStyle = '';	
	if(ColIdName=='rec_id'){
		addStyle=' size = "3" ';
	}

	if($("#act").val()=="EDIT"){
		IdStr='editfield'+mainRecID+'_';
		if(filterType!=2){
			addFun='';	
		} else {
			addFun='';	
			if(autoFilter==1){	
				addFun='onChange="setTextBox(this.value,this.id,\''+rec_id+'\');"';	
				addStr='<option style="background-color:#CC99FF;" value="Enter Text">Enter Text</option>';
				IdStr='lov_';				
			}
		}
	
	}else {
		IdStr='fd_filter_';
		if(filterType!=2){
			addFun='onkeydown="return filterGrid(event);"';	
		} else {
			addFun='onChange="loadGrid();"';	
		}
	}
	var IdVal = IdStr+ColIdName;	
	if(col_id=="work_status"){		
		return getStatusLov(mainRecID);
	}
	if(col_id=="check_list"){		
		return getchecklist(mainRecID);
	}
	if(col_id=="View"){
		return getViewIcon(mainRecID)
	}
	if(rec_id && rec_id!=0 && $("#act").val()!="EDIT"){		
		if(ColName=="Expiry Period" || ColName=="Reminder Period" ){
			var RemVal = "";
			if(selVal.length>0){
				var monthWeekVal = selVal.substr(-1);
				var strLen = parseInt(selVal.length)-1;
				var numVal = selVal.substr(0,strLen);
				RemVal = numVal+' '+monthWeekObj[monthWeekVal];
				if(parseInt(numVal)>1){
					RemVal= numVal+' '+monthWeekObj[monthWeekVal]+'s';
				}
			}
			return RemVal;
		} 
		else if(col_id=="cs_history"){
				return getNotesData(rec_id);
		} 	else {
			return selVal;
		}
	}
	if(rec_id && rec_id!=0 && $("#act").val()=="EDIT"){
		if(edit_flag==1){			
			return selVal;		
		}
	}
	if(is_reminder==1 || is_reminder==2){
		is_reminderVal = ColIdName;
	}
	if(ColName=="Expiry Date" || ColName=="Reminder Date" ){
		expremColumnObj[ColName]=ColIdName;	
	}
	if(ColName=="Expiry Period" || ColName=="Reminder Period" ){
		expremColumnObj[ColName]=ColIdName;		
		return getRemExpLov(ColName,selVal,mainRecID,ColIdName);
	}	
	if(col_id=="cs_history"){
		return "&nbsp";		 
	}
	
	if(filterType==''){
		return '&nbsp;'			
	}  
	if(filterType==4 && refMax_value!=""){
		return selVal;
	}
	if(filterType==0 || filterType==1 || filterType==5 || (filterType==4 && refMax_value=="")){
		if($("#act").val()=="EDIT"){
			tempElement+='<textarea name="'+IdVal+'" id="'+IdVal+'">'+selVal+'</textarea>';	
		} else {
			tempElement+='<input '+addStyle+' type="text" name="'+IdVal+'" '+addFun+' id="'+IdVal+'" value="'+selVal+'">';
		}
	} else if(filterType==2) {
		tempElement+='<select  class="selectauto" style="overflow:scroll;" '+addFun+' id="'+IdVal+'" name="'+IdVal+'">';
		tempElement+='<option value="">-Select-</option>';
		tempElement+=addStr;		
		var mainIdVal = col_id.replace("_","");
		if(lovObj[mainIdVal]){
			for(lid in lovObj[mainIdVal]){
				var sel='';
				var lovval = lid.replace("_","");
				var enbDisStr = ''; 				
					if(lovObj[mainIdVal][lid]==1 && rec_id!=0){
						enbDisStr=' disabled="disabled" ';	
					}
				
				if(selVal==lovval){
					sel='selected="selected"';
				}
				tempElement+='<option '+sel+' '+enbDisStr+' value="'+lovval+'">'+lovval+'</option>';						
			}			
		}
		tempElement+='</select>';
		if($("#act").val()=="EDIT" && autoFilter==1){
			tempElement+='<textarea style="display:none;" onFocus="this.value=\'\'" name="editfield'+mainRecID+'_'+ColIdName+'" id="editfield'+mainRecID+'_'+ColIdName+'" cols="22" rows="4">Enter Text</textarea>';	
		}
	} else if(filterType==3) {
		
		var addReadStr = '';
		if(read_only==1){
			addReadStr='readonly="readonly" ';
		}
		if($("#act").val()=="EDIT" && (ColName=="Expiry Date" || ColName=="Reminder Date")){
				tempElement+='<input readonly="readonly" type="text" name="'+IdVal+'" id="'+IdVal+'" value="'+selVal+'">';
		} else {			
			tempElement+='<input '+addReadStr+' type="text"  id="'+IdVal+'" value="'+selVal+'" '+addFun+'  class="date_input" tabindex="10" '+addFun+' />';
			tempElement+='&nbsp;<img border="0" onclick="displayCalendar(document.getElementById(\''+IdVal+'\'),\'dd-mm-yyyy\',this);return false;" ';
			tempElement+=' style="width:18px;height:17px; alignment-adjust:middle; border:0px solid transparent; cursor:pointer;" src="images/Calender.gif" alt="Date"> ';
		}
	} 
	return tempElement;	
}

function getGridData()
{
	var tempGridTable ='';
	var headerCount =Object.keys(headerObj).length+5;	
	if(dataObj.totalRows>0){
		var srNo = 1;
		for(var  j in dataObj.rowData)	{			
			var clN = ((srNo%2)==0)?0:1;				
			tempGridTable+=getRows(j,clN);
			srNo++;
		}
	}
	return tempGridTable;
}
function isReadonly(mainIdVal)
{
	
	var robj =dataObj.rowData[mainIdVal];
	var StatusVal = robj['work_status'];
	return statusObj["_"+StatusVal]['readOnly'];
}
function getRows(mainIdVal,srNo)
{
	try{		
		var robj = dataObj.rowData[mainIdVal];
		if(Object.keys(firstDataObj).length==0){
			firstDataObj['firstId'] = mainIdVal;
			firstDataObj['work_status'] = robj["work_status"];
		}
		
		var temprowTable = '';
		var class1 = (srNo==0)?"even":"odd";
		if($("#act").val()=="EDIT"){
			class1 = class1+'edit';
		}	
		
			var Readonly=isReadonly(mainIdVal);
			if(Readonly==1)
			{
				class1 ="readonly";
			}
		
		if($("#inboxmod").val()==1)
		{
			class1 ='Inbox ' +class1;
		}
			var mainIdValArr= mainIdVal.split("_");
			var mainID = mainIdValArr[1];
			var deleteCellVal = robj['delete_cell_flag'];			
			var deleteCellarr = deleteCellVal.split(",");			
			var class2 = (robj['delete_flag']==1)?"strikeLine "+ class1:class1;
			
			temprowTable += '<tr class="'+class2+'" id="TR_'+mainID+'" onMouseDown="edit('+mainID+',this);">';				
			for(fid in mainHeaderObj){
				var ColID = mainHeaderObj[fid]['column_id'];
				var ColVal = robj[ColID];
				var addStyleStr ='';
				if(ColID =='srNo' || ColID =='rec_id'){
					var StatusVal = robj['work_status'];
					if(statusObj["_"+StatusVal]){
						addStyleStr = ' style="background-color:'+statusObj["_"+StatusVal]['bg_color']+';" ';
					}
				}
				var header_name = mainHeaderObj[fid]["header_name"];
				var celVal =''; 				
				if(mainHeaderObj[fid]['edit_flag']==1){
					celVal ='';
				} else if($.inArray(header_name,excludeColummArr)>=0){					
					celVal ='';
				} else {
					celVal=ColID;
				}	
				var addSpaceStr = '&nbsp;&nbsp;';
				if(fid=="cs_history" || $("#act").val()=="EDIT"){
					addSpaceStr='';
				}
				var filterType = mainHeaderObj[fid]['filter_type'];	
				var AddTdStr = '';
				if(filterType==3){
					AddTdStr='nowrap="nowrap"';
				}
				if($.inArray(ColID,deleteCellarr)>=0){
					
					if(class2!=class1){
						var class3 = '';
					} else {
						var class3 = 'blockbg';
					}
					temprowTable += '<td '+AddTdStr+' '+addStyleStr+' id="cell_'+ColID+'" class="'+class3+'" onmouseDown="setCellValue(\''+ColID+'\');"></td>';
				}else{										
						temprowTable += '<td '+AddTdStr+' '+addStyleStr+' onmouseDown="setCellValue(\''+celVal+'\');">'+addSpaceStr+getObjElement(fid,ColVal,mainIdVal);+'</td>'; 						
				}
			}
			temprowTable += '</tr>';
	} catch(Error){
		alert(Error)
		funError("getRows","Section : Airworthiness Centre => Component, Main page Js Error <br/> ",Error.toString(),Error);				
	}
	
	return temprowTable;
}
function manageSubMenuHOver_R()
{
	$("#manageSubMenu_R").css('position','absolute');
	$("#manageSubMenu_R").css('display','block');
}

function manageSubMenuOut_R()
{
	$("#manageSubMenu_R").css('display','none');
}
function fnAddrow(flg,upFlg)
{	
	var noteObj= new Object();
	var AuditObj=new Object();
	var tempMailObj= new Object();
	var eRobj = new Object();
	var tempInsertObj = new Object();
	var mailFlag=0;
	var erChk = 0;
	var seqnce_order=1;
	var total_sequence=Object.keys(arcsequncearr).length;
	var num_row=(parseInt(Total) + 1);
	if(total_sequence<=0)
	{
		alert("Please add ARC Sequence.");
		return false;
	}
	if(num_row<=total_sequence)
		seqnce_order=num_row;	
	else
	{			
		if((num_row%total_sequence)==0)
			seqnce_order=total_sequence;
		else
			seqnce_order=(num_row%total_sequence);
	}	
	AuditObj[1] = getAuditObj();
	noteObj[1]=getNotesObj();
	
	for(hid in headerObj){
		var ColID = headerObj[hid]['column_id'];
		var is_reminder= headerObj[hid]['is_reminder'];	
		var filter_type= headerObj[hid]['filter_type'];	
		if(filter_type=="5")
		{		
			tempInsertObj[ColID]=arcsequncearr[seqnce_order]["header_name"];
			if(expremColumnObj["Expiry Period"])
				tempInsertObj[expremColumnObj["Expiry Period"]]=arcsequncearr[seqnce_order]["expiry_period"];
			if(expremColumnObj["Reminder Period"])				
				tempInsertObj[expremColumnObj["Reminder Period"]]=arcsequncearr[seqnce_order]["reminder_period"];
		}
		if((is_reminder==2 || is_reminder==1) && erChk==0){	
			//eRobj = getExpRemDateVal(ColID,1);
			is_reminderVal	=ColID;													
			
			erChk=1;
			
			var whrStatus=0;
			whrStatus=3;
	
			if(whrStatus!=0){
				mailFlag=1;		
			}
			tempMailObj["mailFlag"]=mailFlag;
			tempMailObj["whrMailStatus"]=whrStatus;				
		}
		//tempInsertObj[headerObj[hid]['column_id']] = $("#field_"+i+"_"+headerObj[hid]['column_id']).val();
	}
//	alert(JSON.stringify(tempInsertObj));
	xajax_AddRow(xajax.getFormValues('frm'),noteObj,AuditObj,tempMailObj,tempInsertObj,arcsequncearr[seqnce_order]);
	/*var client_id = $("#client_id").val();
	var type = $("#type").val();
	var comp_id = $("#comp_id").val();
	var section = $("#sectionVal").val();
	var sub_section = $("#sub_sectionVal").val();
	if(upFlg!=1){
		var rec_id = ($("#mainRowid").val()!='')?$("#mainRowid").val():"";
		addStr='';
	} else {
		var rec_id = '';
		addStr = '&addAirWorRow=1'
	}
	var compAddRow = window.open('airworthiness_centre.php?section='+section+'&sub_section='+sub_section+'&add_section=add_row&comp_id='+comp_id+'&type='+type+'&client_id='+client_id+'&rec_id='+rec_id+'&pos='+flg+addStr,
							'compAddRow','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes')
	compAddRow.focus();*/
}
function getAuditObj()
{
	var tempAudit = new Object();
	tempAudit = {"user_id":UserID,"user_name":user_name,"type":$("#type").val(),"tab_name":tab_name,"client_id":$("#client_id").val(),"section":$("#sectionVal").val(),"sub_section":$("#sub_sectionVal").val(),
				"tail_id":$("#comp_id").val()};	
	return tempAudit;
}
function getNotesObj()
{
	var tempNotes = new Object();
	tempAudit = {"admin_id":UserID,"type":$("#type").val(),"client_id":$("#client_id").val(),"component_id":$("#comp_id").val(),"notes_type":UserLevel,"doc_note_type":6,"comp_main_id":$("#mainRowid").val()};	
	return tempAudit;
}

function getPagging(intCurrent, intLimit, intTotal)
{
	intPage = intCurrent / intLimit;
	intLastPage = Math.floor( parseFloat(intTotal) / parseFloat(intLimit));	
	var start=intCurrent;
	var cLimit = ((start+intLimit) > intTotal) ? intTotal : (start+intLimit);	
	strResult = '<div style="width:60%;float:right;"><div style="width:auto;float:left;" >';
	if(Total==0){
		strResult+='<strong> NO Result(s) Found.</strong>';
	}else{
	strResult+='<strong>'+(intCurrent+1)+' - '+cLimit+' of '+intTotal+'  Result(s) Found.</strong>';
	}
	strResult+='</div><div id="pagger" style="margin-right:5px;">';
	//strResult+='<span><strong>Show Rows:</strong></span><span id="ShowHideLov"></span>&nbsp;&nbsp;&nbsp;'+GetShowHideLov();
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
	  loadGrid();	
}
function GetShowHideLov()
{
	var hideShowLov = '';
	var HSLovObj={"SA":"Show All","HD":"Hide Deleted","SD":"Show Deleted"};
	hideShowLov = '<select class="selectauto"  onChange="showHideRow(this.value);" id="showRowLov" name="showRowLov">';
	for(hsId in HSLovObj){
		var sel ='';
		if(HideVal == hsId){
			sel+='selected="selected"'; 
		}		
		hideShowLov+='<option '+sel+' value="'+hsId+'">'+HSLovObj[hsId]+'</option>';
	}	
	hideShowLov +='</select>';
	return hideShowLov;
}
function showHideRow(val)
{
	HideVal = val;
	$("#act").val("");
	startLimit=0;
	loadGrid();	
}
function setCellValue(cellVal)
{
	currentCell=cellVal;
}
function setTextBox(txtVal,LovColID,mainRcId)
{
	var idStr = LovColID.replace("lov", "editfield"+mainRcId);
	$("#"+idStr).html(txtVal);
	if(txtVal == "Enter Text"){
		$("#"+idStr).css("display","");
	}else{
		$("#"+idStr).css("display","none");		
	}
	return false;
}
function edit(rid,elm)
{	
try{
	if(rid){
	$("#mainRowid").val(rid);		
	} else {
		$("#mainRowid").val("");		
	}
	if($("#act").val()=="EDIT" && currentID!=rid){
		$("#mainRowid").val(currentID);
		if(confirm("Do you want to save your changes?")){
			saveRow(currentID);
		} else {
			doRow("unsetRow");
		}
	}	
	if(currentID!=""){
		if($("#TR_"+currentID)){
			$("#TR_"+currentID).css("backgroundColor","");
		}
	}
	$("#TR_"+rid).css({"backgroundColor":"#FFCC99","textAlign":"left","cursor":"pointer"});
	currentID= rid;	
	} catch(Error){
		funError("edit","Section : Airworthiness Centre => Component, Main page Js Error <br/> ",Error.toString(),Error);		
	}	
}
function doRow(act)
{
	if(act=="EditRow"){
		$("#act").val("EDIT");		
		var EditID = $("#mainRowid").val();		
	}else{
		$("#act").val("");
		var EditID = currentID;	
	}
	var tempObj=new Object();
	var mainIdVal = "_"+EditID;
	tempObj["_"+EditID] = dataObj.rowData["_"+EditID];
	var SrNo = $("#TR_"+EditID).find("td:first").html().replace('&nbsp;&nbsp;','');
	var cln = (SrNo%2==0)?0:1;	
	var editStr=getRows(mainIdVal,cln);
	$("#TR_"+EditID).replaceWith(editStr);		
	$("#TR_"+EditID).css({"backgroundColor":"#FFCC99","textAlign":"left","cursor":"pointer"});		
	
	if(document.getElementById('onImg').checked==true)
		changeFreez_cs('F');
		else
		changeFreez_cs('UF');
}
function getRemExpLov(ClName,slVal,mainRcID,ClIdName){
	
	var expRemStr= '';	
	var idVal = '';
	if(mainRcID==0){
		idVal = "fd_filter_"+ClIdName;
		slVal = ($("#fd_filter_"+ClIdName).length>0 && $("#fd_filter_"+ClIdName).val()!='')?$("#fd_filter_"+ClIdName).val():"";			
	} else {
		idVal = "editfield"+mainRcID+"_"+ClIdName;
	}	
	var addFun = "";
	if(ClName=="Expiry Period" && mainRcID!=0){
		addFun=' onChange="getRemCombo(this.value,\''+mainRcID+'\');" ';
	} else {
		if(mainRcID==0)
		addFun=' onChange="loadGrid();" ';
	}
	expRemStr+='<select '+addFun+' class="selectauto" id='+idVal+' name='+idVal+'>';
	if(ClName=="Expiry Period"){		
		expRemStr+='<option value="">Select Expiry Period</option>';
		for(var w in expRemObj){
			var monthWeekVal = w.substr(-1);
			var strLen = parseInt(w.length)-1;
			var numVal = w.substr(0,strLen);
			var expRemVal = numVal+' '+monthWeekObj[monthWeekVal];
			if(parseInt(numVal)>0){
				expRemVal= numVal+' '+monthWeekObj[monthWeekVal]+'s';
			}
			var selStr= "";
			if(slVal==w){
				selStr = " selected = 'selected' ";
			}
			
			expRemStr+='<option '+selStr+' value='+w+'>'+expRemVal+'</option>';
		}
	} else {
		expRemStr+='<option value="">Select Reminder Period</option>';
		var tempObj = new Object();
		var	exDbVal ="";
		var remColumnId = expremColumnObj["Reminder Period"];
		var expColumnId = expremColumnObj["Expiry Period"];
		if(dataObj.rowData[mainRcID] && dataObj.rowData[mainRcID][expColumnId]!=""){
				exDbVal = dataObj.rowData[mainRcID][expColumnId];				
		}
		if(dataObj.rowData[mainRcID] && dataObj.rowData[mainRcID][remColumnId]!=""){
				slVal = dataObj.rowData[mainRcID][remColumnId];				
		}
		if(mainRcID==0){			
			for(var w in expRemObj){
				for(var k in expRemObj[w]){
					tempObj[k]=k;
				}
			}
		} else{				
			if(exDbVal!=""){				
				tempObj = expRemObj[exDbVal];
			}
		}
		for(var y in tempObj){
			var monthWeekVal = y.substr(-1);
			var strLen = parseInt(y.length)-1;
			var numVal = y.substr(0,strLen);
			var expRemVal = numVal+' '+monthWeekObj[monthWeekVal];
			if(parseInt(numVal)>1){
				expRemVal= numVal+' '+monthWeekObj[monthWeekVal]+'s';
			}
			var selStr = "";
			
			if(slVal==y){
				selStr = " selected = 'selected' ";
			}
			expRemStr+='<option '+selStr+' value='+y+'>'+expRemVal+'</option>';
		}						
		
	}
	expRemStr+='</select>';	
	return expRemStr;
}

function getRemCombo(expVal,mainrec_id)
{
	var remStr = '';
	var remColumnId = expremColumnObj["Reminder Period"];	
	var slVal = dataObj.rowData[mainrec_id][remColumnId];	
	var	idVal = "editfield"+mainrec_id+"_"+remColumnId;
	remStr+='<select class="selectauto" id='+idVal+' name='+idVal+'>';
	remStr+='<option value="">Select Reminder Period</option>';
	for(var rv in expRemObj[expVal]){
		var monthWeekVal = rv.substr(-1);
			var strLen = parseInt(rv.length)-1;
			var numVal = rv.substr(0,strLen);
			var RemVal = numVal+' '+monthWeekObj[monthWeekVal];
			if(parseInt(numVal)>1){
				RemVal= numVal+' '+monthWeekObj[monthWeekVal]+'s';
			}
			var selStr  = "";
			if(slVal==rv){
				selStr = " selected = 'selected' ";
			}
		remStr+='<option value='+rv+'>'+RemVal+'</option>';
	}
	remStr+='</select>';	
	$("#"+idVal).replaceWith(remStr);
}

function saveRow(mainID)
{
	try{
		var mailFlag=0;
		$("#act").val("");
		var UpdateObj = new Object();
		var tempauditObj = new Object();
		var tempUpObj = new Object();
		var ai=0;
		var ni = 0;
		var dateVal = new Date();
		var dayVal = dateVal.getDate()+GetDaySuffix(dateVal.getDate());
		var MonthVal = monthNames[dateVal.getMonth()];
		var yearVal = dateVal.getFullYear();
		var auditDateVal = dayVal+" "+MonthVal+" "+yearVal;		
		var oldchecklistId = dataObj.rowData["_"+mainID]["check_list"];
		var oldStatusID = dataObj.rowData["_"+mainID]["work_status"];
		var deleteCellVal = dataObj.rowData["_"+mainID]['delete_cell_flag'];			
		var deleteCellarr = deleteCellVal.split(",");	
		
		var noteObj= new Object();	
		var StatusVal = $("#work_status_"+mainID).val();
		var checklistval=$("#check_list_"+mainID).val();
		var erChk = 0;
		var template_act_flg=0;
		for(fid in headerObj){
			var ColID = headerObj[fid]['column_id'];
			
			var chkDel = 0;
			if($.inArray(ColID,deleteCellarr)>=0){
				chkDel=1;
			}
			if(chkDel==0){
				var autoFilter = headerObj[fid]['filter_auto'];
				var header_name = headerObj[fid]['header_name'];
				var is_reminder= headerObj[fid]['is_reminder'];
				var view_flag= headerObj[fid]['view_flag'];
				var filter_type= headerObj[fid]['filter_type'];
				var refMax_value= headerObj[fid]['refMax_value'];					
				var currentColVal = $("#editfield_"+mainID+"_"+ColID).val();
				if(is_reminderVal!=0 && erChk==0){	
					var eRobj = new Object();		
					if(statusObj["_"+StatusVal]["rem_exp_status"]==4)		
					{
						var today = new Date();
						var d = today.getDate();
						var m = today.getMonth();
						var y = today.getFullYear();
						var setdate=new Date(y, m, d);
						var strdateVal=setdate.getDate()+"-"+(setdate.getMonth()+1)+"-"+setdate.getFullYear();							
						if($("#editfield_"+mainID+"_"+is_reminderVal).val()=='')
						{
							$("#editfield_"+mainID+"_"+is_reminderVal).val(strdateVal);
						}
					}					
					eRobj = getExpRemDateVal(mainID,0);																											
					 var expDateCol = expremColumnObj["Expiry Date"];
					 var RemDateCol = expremColumnObj["Reminder Date"];
					 if( $("#editfield_"+mainID+"_"+expDateCol).length>0)					
					 $("#editfield_"+mainID+"_"+expDateCol).val(eRobj["expVal"]);
					 
					 if( $("#editfield_"+mainID+"_"+RemDateCol).length>0)					
					 $("#editfield_"+mainID+"_"+RemDateCol).val(eRobj["remVal"]);
					 erChk=1;
					
				}
				if(expremColumnObj[header_name] && expremColumnObj[header_name]!=""){
					ColID =expremColumnObj[header_name];
				}
							
				if($("#editfield_"+mainID+"_"+ColID).length > 0 && $("#editfield_"+mainID+"_"+ColID)!='')
				var currentColVal = $("#editfield_"+mainID+"_"+ColID).val();
				
				
				var dataval = $.trim(dataObj.rowData["_"+mainID][ColID]);
				if(currentColVal==null || (filter_type==4 && refMax_value!="")){
					currentColVal = '';
				}
				if(dataval==null || (filter_type==4 && refMax_value!="")){
					dataval = '';
				}
					
				if((currentColVal!="Enter Text" && dataval!=$.trim(currentColVal))){
					tempUpObj[ColID] = $("#editfield_"+mainID+"_"+ColID).val();
					/*audit obj*/
					tempauditObj[ai] = getAuditObj();
					tempauditObj[ai]['field_title']	=header_name;
					var OldValue = dataObj.rowData["_"+mainID][ColID];
					var NewValue =currentColVal;
					if(header_name=="Expiry Period" || header_name=="Reminder Period"){						
						if(OldValue!=""){
							var monthWeekVal = OldValue.substr(-1);
							var strLen = parseInt(OldValue.length)-1;
							var numVal = OldValue.substr(0,strLen);
				
							if(monthWeekObj[monthWeekVal] && monthWeekVal[monthWeekVal]!=""){
								OldValue = numVal+" "+monthWeekObj[monthWeekVal];
								if(parseInt(numVal)>1){
										OldValue= numVal+' '+monthWeekObj[monthWeekVal]+'s';
								}
							}
						}
					
						if(NewValue!=""){
							var monthWeekVal = NewValue.substr(-1);
							var strLen = parseInt(NewValue.length)-1;
							var numVal = NewValue.substr(0,strLen);
				
							if(monthWeekObj[monthWeekVal] && monthWeekVal[monthWeekVal]!=""){
								NewValue = numVal+" "+monthWeekObj[monthWeekVal];
								if(parseInt(numVal)>1){
										NewValue= numVal+' '+monthWeekObj[monthWeekVal]+'s';
								}
							}
						}
						
					}
					tempauditObj[ai]['old_value'] = OldValue;										
					tempauditObj[ai]['new_value'] = NewValue;		
					tempauditObj[ai]['action_id'] = 14;
					tempauditObj[ai]['rec_id'] =  dataObj.rowData["_"+mainID]['rec_id'];
					tempauditObj[ai]['main_id'] = mainID;
					/*end*/
					ai++;			
				}	
			}
		}
		if(is_reminderVal!=0){			
			var curDate= new Date();
			var a = new Date(curDate).getTime();
			a=Math.abs(a);				
			
			var whrStatus=0;
			var b=gettimestampofdate($("#editfield_"+mainID+"_"+is_reminderVal).val());
			if(b<=a)
			{
				whrStatus=3;
			}
			if(eRobj["remVal"]!='')
			{
				var b=gettimestampofdate(eRobj["remVal"]);
				if(b<=a)
				{
					whrStatus=2;
				}
			}				
			if(eRobj["expVal"]!='')
			{
				var b=gettimestampofdate(eRobj["expVal"]);
				if(b<=a)
				{
					whrStatus=1;
				}
			}	
			
			if(whrStatus!=0)
			{
				var expDateCol = expremColumnObj["Expiry Date"];
				var RemDateCol = expremColumnObj["Reminder Date"];
				var expPeriodCol = expremColumnObj["Expiry Period"];
				var RemPeriodCol = expremColumnObj["Reminder Period"];
				if($("#editfield_"+mainID+"_"+is_reminderVal).val()!=dataObj.rowData["_"+mainID][is_reminderVal] || $("#editfield_"+mainID+"_"+expDateCol).val()!=dataObj.rowData["_"+mainID][expDateCol] || $("#editfield_"+mainID+"_"+RemDateCol).val()!=dataObj.rowData["_"+mainID][RemDateCol])
				{	
					var oldStatusID = dataObj.rowData["_"+mainID]["work_status"];	
					var client_id=$("#client_id").val();
					mailFlag=1;		
					//xajax_send_expiry_rowMail($("#comp_id").val(),dataObj.rowData["_"+mainID]["rec_id"],mainID,whrStatus,oldStatusID,statusObj,client_id);
				}
			}
		}
		if(dataObj.rowData["_"+mainID]["check_list"]!=checklistval){
			tempUpObj['check_list'] =checklistval;
			if(checklistval==1 && statusObj["_"+StatusVal]["rem_exp_status"]==4 && dataObj.rowData["_"+mainID]["template_id"]==0)
			{
				template_act_flg=1;
			}
			/*audit obj*/
			var oldchecklistId = dataObj.rowData["_"+mainID]["check_list"];
			tempauditObj[ai] = getAuditObj();
			tempauditObj[ai]['rec_id'] =  dataObj.rowData["_"+mainID]['rec_id'];
			tempauditObj[ai]['field_title']	="Check List";
			var OldSValue= "";
			var NewSValue = "";
			if(checklistObj[oldchecklistId]){
				OldSValue = checklistObj[oldchecklistId];
			}			
			if(checklistObj[checklistval]){
				NewSValue = checklistObj[checklistval];
			}			
			
			tempauditObj[ai]['old_value'] = OldSValue;										
			tempauditObj[ai]['new_value'] = NewSValue;		
			tempauditObj[ai]['action_id'] = 14;			
			tempauditObj[ai]['main_id'] = mainID;		
			ai++;

			/*end*/
		}
		if(dataObj.rowData["_"+mainID]["work_status"]!=StatusVal){
			tempUpObj['work_status'] =StatusVal;
			/*audit obj*/
			var oldStatusID = dataObj.rowData["_"+mainID]["work_status"];
			tempauditObj[ai] = getAuditObj();
			tempauditObj[ai]['rec_id'] =  dataObj.rowData["_"+mainID]['rec_id'];
			tempauditObj[ai]['field_title']	="Work Status";
			var OldSValue= "";
			var NewSValue = "";
			if(statusObj["_"+oldStatusID]){
				OldSValue = statusObj["_"+oldStatusID]['status_name']+','+statusObj["_"+oldStatusID]['bg_color'];
			}
			tempauditObj[ai]['old_value'] = OldSValue;
			var newSname= statusObj["_"+StatusVal]['status_name'];
			if(statusObj["_"+StatusVal]){
				NewSValue = newSname+','+statusObj["_"+StatusVal]['bg_color'];
			}
			tempauditObj[ai]['new_value'] = NewSValue;
			tempauditObj[ai]['action_id'] = 15;
			tempauditObj[ai]['main_id'] = mainID;
			
			noteObj[ni]= getNotesObj();
			noteObj[ni]["notes"] = newSname+" by "+user_name +" on "+auditDateVal;
			ni++;
			/*end*/
		}
		if($.isEmptyObject(tempUpObj)){
			alert("Record Updated Successfully");			
			doRow("");
		} else {
			noteObj[ni]= getNotesObj();			
			noteObj[ni]["notes"] = tab_name+" Updated by "+user_name +" on "+auditDateVal;
			
			var whrObj = new Object();
			whrObj={"id":$("#mainRowid").val()};
			UpdateObj['colVal'] = tempUpObj;
			UpdateObj['whrUpdate'] = whrObj;
			if(!$.isEmptyObject(LovValueCheck['lovCol'])){
				UpdateObj['auto_filter'] = 1;
				UpdateObj['LovValueCheck'] = LovValueCheck;
			}
			UpdateObj["notes"] = noteObj;
			UpdateObj["mailFlag"] = mailFlag;
			UpdateObj["rec_id"] =dataObj.rowData["_"+mainID]['rec_id'];
			UpdateObj["upMailStatus"] =whrStatus;
			UpdateObj["oldStatusID"] =oldStatusID;
			UpdateObj["template_act_flg"] = template_act_flg;
			
			if(template_act_flg==1)
			{
				var tempalte_det = new Object();
				tempalte_det["client_id"]=$("#client_id").val();
				var num_row=dataObj.rowData["_"+mainID]["display_order"]
				var total_sequence=Object.keys(arcsequncearr).length;
				if(num_row<=total_sequence)
					seqnce_order=num_row;	
				else
				{			
					if((num_row%total_sequence)==0)
						seqnce_order=total_sequence;
					else
						seqnce_order=(num_row%total_sequence);
				}	
				tempalte_det["template_id"]=arcsequncearr[seqnce_order]["template_id"];
				tempalte_det["comp_id"]=$("#comp_id").val();
				tempalte_det["mainRowid"]=mainID;
				UpdateObj["template_act_details"] = tempalte_det;
			}
			var AuditObj = new Object();
			AuditObj =tempauditObj;		
			xajax_Update(UpdateObj,tempauditObj);
			
			}
	} catch(Error) {				
		funError("saveRow","Section : Airworthiness Centre => Component, Main page Js Error <br/> ",Error.toString(),Error);		
		}	
}
function updateRow(upObj)
{	
	try{
	var upID= upObj["whrUpdate"]["id"];
	for(col_id in upObj['colVal']){		
		var colVal = upObj['colVal'][col_id];
		dataObj.rowData["_"+upID][col_id] = colVal;
	}
	if(upObj['template_act_details']){
		if(upObj['template_act_details']['template_id']){
			dataObj.rowData["_"+upID]["template_id"] = upObj['template_act_details']['template_id'];
		}
	}
	if(upObj['colVal']['work_status']){
		dataObj.rowData["_"+upID]["work_status"] = upObj['colVal']['work_status'];
	}
	var valTempObj = new Object();
	var autoFilterObj= new Object();
	autoFilterObj = upObj["auto_filter"];
		if(!$.isEmptyObject(autoFilterObj)){
		lovObj = new Object();
		for(aa in autoFilterObj){
			var temopObj = new Object();
			temopObj = autoFilterObj[aa].split(",");
			if(!$.isEmptyObject(temopObj)){
				if(!lovObj[aa]){
					lovObj[aa] = new Object();
				}
				for(bb in temopObj){
					if(temopObj[bb].indexOf("$#@@#$")>=0){
						var tempLarr = temopObj[bb].split("$#@@#$");
						var tempLVal =tempLarr[0];
						lovObj[aa]['_'+tempLVal]=tempLarr[1];
					} else {
						if(!lovObj[aa]['_'+temopObj[bb]]){
							lovObj[aa]['_'+temopObj[bb]]=0;
						}
					}
					
				}
			}			
		}		
	
		//upObj["auto_filter"] = tempAutoObj;
		for(lvIDVal in lovObj){
			lvID='_'+lvIDVal; 
			//for if auto filter is 1 then need to update filetr combbo - (NOT IN CS)
			if(mainHeaderObj[lvID]){			
				var ElemStr = getObjElement(lvID,'',0);
				var ColVal = headerObj[lvID]['column_id'];
				if(ElemStr!=''){
					$("#fd_filter_"+ColVal).replaceWith(ElemStr);
				}
			}
		}		
	}
	if(upObj["notes"]){
		if(notesObj[upID]){
			notesObj[upID] = $.extend(upObj["notes"][upID],notesObj[upID]);
		} else{
			notesObj[upID] = new Object();
			notesObj[upID] = upObj["notes"][upID];
		}
	}
	var upIdVal ="_"+upID;
	var SrNo = $("#TR_"+upID).find("td:first").html().replace('&nbsp;&nbsp;','');
	var cln = (SrNo%2==0)?0:1;		
	var upStr=getRows(upIdVal,cln);	
	$("#TR_"+upID).replaceWith(upStr);
	
	if(SrNo==1){		
		updateParentWindow(upID);
	}
	
	ResetVal();
	}catch(e){
		funError("updateRow","Section : Airworthiness Centre => Component, Main page Js Error <br/> ",Error.toString(),Error);	
	}
}

function updateParentWindow(firstId)
{
	try{		
		firstDataObj = new Object();
		firstDataObj["work_status"] = 0;
		firstDataObj["ls_rev_date"]= '';
		firstDataObj["next_rev_date"]= '';
		firstDataObj["work_status"]= dataObj.rowData["_"+firstId]['work_status'];
		if(is_reminderVal!=0 && dataObj.rowData["_"+firstId][is_reminderVal]!=""){
			firstDataObj["ls_rev_date"]= dataObj.rowData["_"+firstId][is_reminderVal];
		}
		if(expremColumnObj["Expiry Date"]){
			var colIdVal = expremColumnObj["Expiry Date"];
			firstDataObj["next_rev_date"]= dataObj.rowData["_"+firstId][colIdVal];
		}
		var fsws = firstDataObj['work_status'];
		var mainCompId = compDetailObj['mainId'];
		var comp_type = compDetailObj['comp_type'];
		var comp_type='_'+comp_type;
		if(window.opener){
			if(window.opener.dataObj['data'][comp_type] && window.opener.dataObj['data'][comp_type]['_'+mainCompId]){
				window.opener.dataObj['data'][comp_type]['_'+mainCompId]["Work Status"]=firstDataObj['work_status'];	
				window.opener.dataObj['data'][comp_type]['_'+mainCompId]["Last Review Date"]=firstDataObj['ls_rev_date'];
				window.opener.dataObj['data'][comp_type]['_'+mainCompId]["Next Review Date"]=firstDataObj['next_rev_date'];	
				if(window.opener.dataObj['Work_statusArr'] && !window.opener.dataObj['Work_statusArr'][fsws]){
					window.opener.dataObj['Work_statusArr'][fsws]=statusObj['_'+fsws];	
				}
			}
			window.opener.renderGrid();	
		}
			
		
	}catch(e){
		alert(e)
	}
}

function filterGrid(e)
{
	if(e.keyCode==13){
		loadGrid();
	}
}
function addSubtractDays(theDate,days,flg) {
	if(flg==1){
	    return new Date(theDate.getTime() + days*24*60*60*1000);
	} else {		
		return new Date(theDate.getTime() - days*24*60*60*1000);
	}
}

function addSubtractMonth(theDate,month,flg) {
	if(flg==1){
	   return new Date(theDate.setMonth(theDate.getMonth() + parseInt(month)));
	} else {
		return new Date(theDate.setMonth(theDate.getMonth() - parseInt(month)));
	}
}

function getExpRemDateVal(mainID,defFlag)
{
	try{
		if(is_reminderVal!=0){	
		var expColumnId = expremColumnObj["Expiry Period"];
		var remColumnId = expremColumnObj["Reminder Period"];
		var addRemVal='';
			if(defFlag==1)
			{
				var today = new Date();
				var d = today.getDate();
				var m = today.getMonth();
				var y = today.getFullYear();
				var setdate=new Date(y, m, d);
				var dateVal=setdate.getDate()+"-"+(setdate.getMonth()+1)+"-"+setdate.getFullYear();					
				var addexpVal = dataObj.rowData['_'+mainID][expColumnId];
				var addRemVal = dataObj.rowData['_'+mainID][remColumnId];
			}
			else
			{
				var dateVal =$("#editfield_"+mainID+"_"+is_reminderVal).val();			
				var addexpVal = $("#editfield_"+mainID+"_"+expColumnId).val();			
			
				if($("#editfield_"+mainID+"_"+remColumnId).length>0)
				{
					var addRemVal = $("#editfield_"+mainID+"_"+remColumnId).val();
					var remColumnId = expremColumnObj["Reminder Period"];
				}
			}
			var ExpVal = "";
			var RemVal = "";
			if(addexpVal!="" && dateVal!=""){					
				var tempDate = dateVal.split("-");
				var adddate = new Date(parseInt(tempDate[2]),parseInt(tempDate[1]- 1),parseInt(tempDate[0])); 
				var monthWeekVal = addexpVal.substr(-1);
				var strLen = parseInt(addexpVal.length)-1;
				var numVal = addexpVal.substr(0,strLen);
				var ExpDate = "";
				if(monthWeekVal=="d"){
					ExpDate = addSubtractDays(adddate,numVal,1);
				} 
				if(monthWeekVal=="m"){												
					ExpDate = addSubtractMonth(adddate,numVal,1);							
				}						
				if(ExpDate!=""){
					var tempExpDate = ExpDate.getDate();
					if(tempExpDate.toString().length==1){
						tempExpDate="0"+tempExpDate;
					}
					var tempExpmonth = ExpDate.getMonth()+1;
					if(tempExpmonth.toString().length==1){
						tempExpmonth="0"+tempExpmonth;
					}
					ExpVal = tempExpDate+"-"+(tempExpmonth)+"-"+ExpDate.getFullYear();							
				}
				
				
				if(addRemVal!="" && is_reminderVal!=""){
					var RemDate = "";
					var monthWeekVal = addRemVal.substr(-1);
					var strLen = parseInt(addRemVal.length)-1;
					var numVal = addRemVal.substr(0,strLen);
				
					if(monthWeekVal=="w"){
						numVal= 7*numVal;
					}
					
					if(monthWeekVal=="d" || monthWeekVal=="w"){
						RemDate = addSubtractDays(ExpDate,numVal,2);
					} 
					if(monthWeekVal=="m"){												
						RemDate = addSubtractMonth(ExpDate,numVal,2);							
					}
					if(RemDate!=""){
						var tempRemDate = RemDate.getDate();
						if(tempRemDate.toString().length==1){
							tempRemDate="0"+tempRemDate;
						}
						var tempRemmonth = RemDate.getMonth()+1;
						if(tempRemmonth.toString().length==1){
							tempRemmonth="0"+tempRemmonth;
						}
						RemVal = tempRemDate+"-"+(tempRemmonth)+"-"+RemDate.getFullYear();								
					}
				}		
			}
		}
		var finalobj = {"expVal":ExpVal,"remVal":RemVal};							
		return finalobj;
	}catch(Error){
		funError("getExpRemDateVal","Section : Airworthiness Centre => Component, Main page Js Error <br/> ",Error.toString(),Error);		
	}
}

function  GetDaySuffix(number) {
	var d = number % 10;
	return (~~ (number % 100 / 10) === 1) ? 'th' :
                (d === 1) ? 'st' :
                (d === 2) ? 'nd' :
                (d === 3) ? 'rd' : 'th';
}
function getNotesData(mainRecid)
{
	var notesStr= "";
	try{	
		var mainidVal =mainRecid.replace("_","");		
		if(notesObj[mainidVal] && notesObj[mainidVal]!=""){
			notesStr+='<div style="overflow:auto; max-height:150px;z-index:-1;width:250px;">';
			for(var nt in notesObj[mainidVal]){	
				var noteType= notesObj[mainidVal][nt]["notes_type"];
				var notes= notesObj[mainidVal][nt]["notes"];
				//var doc_note_type = noteClassObj[noteType];	
				//var className  = noteClassObj[noteType];	
				//if(doc_note_type==6){
				className = "greencomm_box";
				//}
				
				notesStr+='<div class='+className+' id="noteid'+nt+'"><strong>'+notes+'</strong></div>';
			}
			notesStr+='</div>';
		}
	}catch(e){
		alert(e);
	}
	return notesStr;
}

function DeleteActiveRow(DeleteActiveFlag)
{
	try{
		$("#act").val("");
		var CurID= $("#mainRowid").val();
		var tempUpObj = new Object();
		tempaudObj = getAuditObj();
		var upObj = new Object();
		var DelFlg = 0;
		if(DeleteActiveFlag==1){
			var msg ="Are you sure you want to delete this row?";
			tempaudObj['action_id'] = 16;
			upObj['updateObj'] = {"delete_flag":1};
			upObj['alertMsg'] = "The row has been deleted successfully.";
			DelFlg = 1;		
		} else {
			var msg = " Are you sure you want to activate this row?";
			tempaudObj['action_id'] = 17;
			upObj['updateObj'] = {"delete_flag":0};
			upObj['alertMsg'] = "The row has been activated successfully.";	
			DelFlg = 0;		
		}	
		if(confirm(msg)){	
			tempaudObj['main_id'] = CurID;
			tempaudObj['rec_id'] =  dataObj.rowData["_"+CurID]['rec_id'];
			upObj['whrUpdate'] = {"id":$("#mainRowid").val()};
			upObj['row_delete_flag'] = 1;	
			xajax_updateRowValues(upObj,tempaudObj);		
									
		}else
		{
			return false;
		}
	}catch(Error){
		funError("getExpRemDateVal","Section : Airworthiness Centre => Component, Main page Js Error <br/> ",Error.toString(),Error);
	}
}
function DeleteActiveCell(deleteActiveflg)
{
	$("#act").val("");
	if(currentCell=='' && deleteActiveflg==1){
	alert("This cell cannot be deleted.");
	return false;	
	}
	
	var curID = $("#mainRowid").val();
	var OldValue = '';
	var tempUpObj = new Object();
	var upObj = new Object();
	tempaudObj = getAuditObj();
	for(xx in headerObj){
		if(headerObj[xx]['column_id']==currentCell){
			OldValue=headerObj[xx]['header_name'];
		}		
	}
	
	var deleteCellValArr = dataObj.rowData["_"+curID]['delete_cell_flag'];
	var deleteCellVal = deleteCellValArr.split(",");
	if(deleteActiveflg==1)
	{
		deleteCellVal.push(currentCell);
		if(deleteCellVal[0]==''){
			deleteCellVal = deleteCellVal.slice(1);
		}
      	var addVal = '';
		 if(deleteCellVal!=''){
			 addVal = deleteCellVal.join(',');
		 }
		 tempaudObj['action_id'] = 18;
		 upObj['alertMsg'] = "The cell has been deleted successfully.";
	}else
	{
		var tempArr = new Array()
		var k = 0;
		if(deleteCellVal!=''){
		for(ds in deleteCellVal){
				if(deleteCellVal[ds]!=currentCell){
				tempArr[k]= deleteCellVal[ds];
				}
			}
		}
		var addVal = '';
		if(tempArr!=''){
			 addVal = tempArr.join(',');
		 }
		tempaudObj['action_id'] = 19;
	 	upObj['alertMsg'] = "The cell has been activated successfully.";
	}
	
	tempaudObj['main_id'] = curID;
	tempaudObj['old_value'] = OldValue;	
	tempaudObj['field_title'] = 'Column Name';	
	tempaudObj['rec_id'] =  dataObj.rowData["_"+curID]['rec_id'];
	upObj['whrUpdate'] = {"id":curID};
	upObj['updateObj'] = {"delete_cell_flag":addVal};
	upObj['row_delete_cell_flag'] = 1;	
	xajax_updateRowValues(upObj,tempaudObj);
}

function updateDeleteRec(deleteObj)
{
	var CurID = deleteObj['whrUpdate']['id'];
	if(deleteObj['row_delete_flag']==1){
		var DelFlg = deleteObj['updateObj']['delete_flag'];
		dataObj.rowData["_"+CurID]['delete_flag']=DelFlg;
	} else if(deleteObj['row_delete_cell_flag']==1){		
		var DelCellFlg = deleteObj['updateObj']['delete_cell_flag'];
		dataObj.rowData["_"+CurID]['delete_cell_flag']=DelCellFlg;
	}
	var SrNo = $("#TR_"+CurID).find("td:first").html().replace('&nbsp;&nbsp;','');
	var upIdVal  = "_"+CurID;
	var cln = (SrNo%2==0)?0:1;		
	var upStr=getRows(upIdVal,cln);	
	$("#TR_"+CurID).replaceWith(upStr);
	ResetVal();	
}
function changeRowVal(columnName,rowVal,rec_id)
{
	if(columnName == 'work_status'){
		if(saveStatusAllObj[rec_id] && saveStatusAllObj[rec_id]!=rowVal){
			saveStatusAllObj[rec_id] = rowVal;
		} else {
			if(dataObj.rowData['_'+rec_id]['status']!=rowVal){
				saveStatusAllObj[rec_id] = rowVal;
			}
		}
	}
}

function ResetVal()
{
	saveStatusAllObj= new Object();
	firstDataObj= new Object();
}

function saveAll()
{
	var tempObj = new Object();
	var tempauditObj = new Object();
	var noteObj = new Object();
	var arcdateObj = new Object();
	var checklistup=new Object();
	var ai = 0;	
	var dateVal = new Date();
	var dayVal = dateVal.getDate()+GetDaySuffix(dateVal.getDate());
	var MonthVal = monthNames[dateVal.getMonth()];
	var yearVal = dateVal.getFullYear();
	var auditDateVal = dayVal+" "+MonthVal+" "+yearVal;	
	var tempalte_det = new Object();
	var total_sequence=Object.keys(arcsequncearr).length;
	
	for (mainID in saveStatusAllObj){		
		if(dataObj.rowData['_'+mainID]['work_status']!=saveStatusAllObj[mainID]){
			tempObj[mainID] ={'column_name':'work_status','value':saveStatusAllObj[mainID],'mainId':mainID};
			
			var StatusVal = saveStatusAllObj[mainID];			
			var newSname = statusObj["_"+StatusVal]['status_name'];
			var oldStatusID = dataObj.rowData["_"+mainID]["work_status"];
			if(statusObj["_"+StatusVal]["rem_exp_status"]==4 && is_reminderVal!="")		
			{
				var today = new Date();
				var d = today.getDate();
				var m = today.getMonth();
				var y = today.getFullYear();
				var setdate=new Date(y, m, d);
				var strdateVal=setdate.getDate()+"-"+(setdate.getMonth()+1)+"-"+setdate.getFullYear();		
				arcdateObj[mainID]=new Object();									
				arcdateObj[mainID][is_reminderVal]=strdateVal;				
				eRobj = getExpRemDateVal(mainID,1);																											
				var expDateCol = expremColumnObj["Expiry Date"];
				arcdateObj[mainID][expDateCol] =eRobj["expVal"];
				if(expremColumnObj["Reminder Date"])
				{
					var RemDateCol = expremColumnObj["Reminder Date"];					 
					arcdateObj[mainID][RemDateCol] =eRobj["remVal"];
				}								
				if($("#check_list_"+mainID).val()==1 && dataObj.rowData["_"+mainID]["template_id"]==0)
				{
					var num_row=dataObj.rowData["_"+mainID]["display_order"]
					if(num_row<=total_sequence)
						seqnce_order=num_row;	
					else
					{			
						if((num_row%total_sequence)==0)
							seqnce_order=total_sequence;
						else
							seqnce_order=(num_row%total_sequence);
					}	
					tempalte_det[mainID]=new Object();		
					tempalte_det[mainID]["client_id"]=$("#client_id").val();
					tempalte_det[mainID]["template_id"]=arcsequncearr[seqnce_order]["template_id"];
					tempalte_det[mainID]["comp_id"]=$("#comp_id").val();				
				}
			}	
			noteObj[mainID]= getNotesObj();
			noteObj[mainID]["comp_main_id"] = mainID;
			noteObj[mainID]["notes"] = newSname+" by "+user_name +" on "+auditDateVal;
			
			tempauditObj[ai] = getAuditObj();
			tempauditObj[ai]['field_title']	="Work Status";
			tempauditObj[ai]['old_value'] = statusObj["_"+oldStatusID]['status_name']+','+statusObj["_"+oldStatusID]['bg_color'];		
			tempauditObj[ai]['new_value'] = newSname+','+statusObj["_"+StatusVal]['bg_color'];	
			tempauditObj[ai]['action_id'] = 15;
			tempauditObj[ai]['main_id'] = mainID;	
			tempauditObj[ai]['rec_id'] =  dataObj.rowData["_"+mainID]['rec_id'];
			ai++;			
		}			
	}	
	for (mainID in dataObj.rowData){		
	if( dataObj.rowData[mainID] && dataObj.rowData[mainID]['check_list']!=$("#check_list"+mainID).val()){
			var idarr=mainID.split("_");		
			checklistup[idarr[1]]=new Object();	
			checklistup[idarr[1]]=$("#check_list"+mainID).val();
		}	
	}	
	
	var saveAllObj = new Object();
	if((!$.isEmptyObject(tempObj) && !$.isEmptyObject(tempauditObj)) || !$.isEmptyObject(checklistup)){
		saveAllObj[0]  ={'work_status':tempObj};
		saveAllObj[1] = {"statusAudObj":tempauditObj};
		saveAllObj[2] = {"notesObj":noteObj};	
		saveAllObj[3] = {"arc_date":arcdateObj};					
		saveAllObj[4] = {"check_list":checklistup};
		saveAllObj[5] = {"template_act_details": tempalte_det};
		xajax_saveAll(saveAllObj);
	}		
}
function updateAllRow(upObj)
{
	var firstId = 0;	
	if(upObj["up_checklist"]){
		for(upID in upObj['up_checklist']["check_list"]){									
			if(upObj["up_checklist"]["check_list"][upID]){
				dataObj.rowData["_"+upID]["check_list"]=upObj["up_checklist"]["check_list"][upID];				
			}
		}
	}
	if(upObj['saveAll']){
		for(obj in upObj['saveAll']){
			for(upID in upObj['saveAll'][obj]){
					if(obj == "work_status"){						
						dataObj.rowData["_"+upID]["work_status"] = upObj['saveAll'][obj][upID]['value'];
						if(upObj['template_act_details'][upID]){
							dataObj.rowData["_"+upID]["template_id"] = upObj['template_act_details'][upID]['template_id'];
						}
					}
					if(upObj["up_date"]){
						if(notesObj[upID]){
							if(statusObj["_"+upObj['saveAll'][obj][upID]['value']]["rem_exp_status"]==4)		
							{
								if(is_reminderVal)
									dataObj.rowData["_"+upID][is_reminderVal] = upObj['up_date']["arc_date"][upID][is_reminderVal];
								var expDateCol = expremColumnObj["Expiry Date"];							
								dataObj.rowData["_"+upID][expDateCol] = upObj['up_date']["arc_date"][upID][expDateCol];
								if(expremColumnObj["Reminder Date"]);
								{
									var RemDateCol = expremColumnObj["Reminder Date"];	
									dataObj.rowData["_"+upID][RemDateCol] = upObj['up_date']["arc_date"][upID][RemDateCol];					
								}
							}
						}
					}
					
					if(upObj["notes"]){
						if(notesObj[upID]){
							notesObj[upID] = $.extend(upObj["notes"][upID],notesObj[upID]);
						} else{
							notesObj[upID] = new Object();
							notesObj[upID] = upObj["notes"][upID];
						}
					}
					
					var tempUpObj = new Object();
					var SrNo = $("#TR_"+upID).find("td:first").html().replace('&nbsp;&nbsp;','');
					if(SrNo==1){
						firstId =upID;
					}
					var upIdVal  = "_"+upID;
					var cln = (SrNo%2==0)?0:1;		
					var upStr=getRows(upIdVal,cln);	
					$("#TR_"+upID).replaceWith(upStr);
					
					
				}		
		}
	}

	if(firstId!=0){
		updateParentWindow(firstId);
	}
	ResetVal();
}

function openViewArea()
{
	var type = $("#type").val();
	var client_id = $("#client_id").val();	
	var comp_id = $("#comp_id").val();	
	var mainRowid = $("#mainRowid").val();	
	var param = '';
	if($("#inboxmod").val()==1)
		param = '&UID='+$("#UID").val()+'&inboxmod';			
	var airworthDoc = window.open('airworthiness_centre.php?section=3&sub_section=1&type='+type+'&comp_id='+comp_id+'&client_id='+client_id+'&mainRowid='+mainRowid+''+param,
						  'airworthDoc','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	airworthDoc.focus();
}

function openAuditTrail()
{
	var type = $("#type").val();	
	var	section= $("#sectionVal").val();	
	var	sub_section= $("#sub_sectionVal").val();
	var	comp_id= $("#comp_id").val();
	var inbparams='';	
	if($("#inboxmod").val()==1)
	{
		inbparams+='&inboxmod=1';
	}
	var headerAudit = window.open('airworthiness_centre_audit.php?section='+section+'&sub_section='+sub_section+'&type='+type+'&comp_id='+comp_id+inbparams,'headerAudit',
								 'height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	headerAudit.focus();
}
function gettimestampofdate(fielddate)
{
	var ndate=fielddate;		
	var n=ndate.split("-");
	var newdate=n[2]+"-"+n[1]+"-"+n[0];			
	var NextDate= new Date(n[2],n[1]-1,n[0]);					
	var b = new Date(NextDate).getTime();					
	return Math.abs(b);
}
