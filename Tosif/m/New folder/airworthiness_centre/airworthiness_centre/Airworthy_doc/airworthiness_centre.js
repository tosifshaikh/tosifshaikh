// JavaScript Document 
var userTypeArr=new Array();
userTypeArr[3]="FLYdocs Users";
userTypeArr[1]="Main Client Users";
userTypeArr[5]="Clients Users";

var userComboArr=new Array();
userComboArr[3]="flydocs";
userComboArr[1]="airlines";
userComboArr[5]="client";

var flagArr=new Array();
flagArr[0]="internal";
flagArr[1]="client";
flagArr[2]="flydocs";

var Row_readOnly=0;
var pathLink = '';
var user_type=0;
var limit =30;
var list = 0;
var UserID=0;
var commentType = '';
var user_name='';
var user_level = 0;
var tab_name = '';
var fileStatusArrObj = new Object();
var fileStatusObj = new Object();
var MainHeaderObj= new Object();
var headerArrObj= new Object();
var filterValObj = new Object();
var headerObj= new Object();
var statusArrObj = new Object();
var statusObj= new Object();
var LovValueCheck =new Object();
var dataObj = new Object();
var docObj = new Object(); 
var lovObj = new Object();
var autoFilterObj = new Object();
var autoFilterValObj= new Object();
var groupObj = new Object();
var group_fileid_Obj = new Object();
var idValObj = new Object();
var fileObj = new Object();
var saveAllObj = new Object();
var AllFileObj = new Object();
var checkFileObj = new Object();
var saveAllStatusObj= new Object();
var fileImgCount = 0;
var docCount = 0;
var ComponentId =0;
var actDisAct = new Object();
Object.keys=Object.keys||function(o,k,r){r=[];for(k in o)r.hasOwnProperty.call(o,k)&&r.push(k);return r}
var xlsFileArr=['xls','xlsx','xlsm'];
var docFileArr=['doc','docx','DOC','DOCX','RTF','rtf'];
var jpgFileArr = ['jpg','JPG','jpeg','JPEG'];
var flydocFileArr= ['FLYDOC','flydoc'];
var classNameObj = {1:"Blue_round_arc",2:"Red_round_arc",3:"Yellow_round_arc",4:"Green_round_arc"};


SimpleContextMenu.setup({'preventDefault':true, 'preventForms':false});
SimpleContextMenu.attach('odd', 'contextmenu');
SimpleContextMenu.attach('even', 'contextmenu');
SimpleContextMenu.attach('flag_odd_row', 'contextmenu');
SimpleContextMenu.attach('flag_even_row', 'contextmenu');
SimpleContextMenu.attach('readonlyHeader', 'contextmenu_readonly');
SimpleContextMenu.attach('Inbox', 'contextmenu_inbox');
function loadGrid()
{
	
	fileStatusObj = eval(fileStatusArrObj);
	var StatusVal = $("#doc_search").val();
	var tab_id = $("#tab_id").val();
	var type = $("#type").val();
	var rec_id = $("#rec_id").val();
	var client_id=$("#client_id").val();
	var srNo = $("#srNo").val();
		
	var colObj = new Object();
	var tempObj1 = new Object();
	var tempObj2 = new Object();
	$("#act").val("");
	headerObj = eval(headerArrObj);	
	statusObj= eval(statusArrObj);	
		
	for(hid in MainHeaderObj){
		var hidArr = hid.split("_");		
		var HeaderID = hidArr[1];
		var ColVal = MainHeaderObj[hid]['column_id'];
		if(headerObj[hid]){
			colObj[hid]=ColVal;		
		}
		
		filterValObj[ColVal]=($("#fd_filter_"+ColVal).length>0 && $("#fd_filter_"+ColVal).val()!='')?$("#fd_filter_"+ColVal).val():"";	
		if(MainHeaderObj[hid]['filter_type']==2){
			tempObj1[HeaderID]=HeaderID;		
		}
		if(MainHeaderObj[hid]['filter_auto']==1){
			tempObj2[HeaderID]=ColVal;
		}
	}
	
	LovValueCheck={"lovCol":tempObj1,"lovFilterAutoCol":tempObj2};
	
	var params='';
	
	for(opt in loadObj){
	
		if(loadObj[opt]=='row'){			
		params="section=doc&tab_id="+tab_id+"&type="+type+"&rec_id="+rec_id+"&act=row&headerObj="+JSON.stringify(colObj);
		params+='&LovValueCheck='+JSON.stringify(LovValueCheck)+'&srNo='+srNo+'&client_id='+client_id;
		$.ajax({url: "compliance_matrix.php",async: false, type:"POST",data:params,success: function(data){			
		dataObj = eval("("+data+")");
		autoFilterValObj = dataObj.autofiletrVal;
			renderGrid();
				}
			});
		} else if(loadObj[opt]=='doc')	{
			
			var csLinkID= $("#csLinkID").val();
			params="section=4&tab_id="+tab_id+"&type="+type+"&rec_id="+rec_id+"&act=DOC&csLinkId="+csLinkID+"&client_id="+client_id+"&headerObj="+JSON.stringify(colObj)+'&LovValueCheck='+JSON.stringify(LovValueCheck);
			params+='&StatusVal='+StatusVal;
			getLoadingCombo(1);	
			$.ajax({url: "airworthiness_centre.php", async: false, type:"POST",data:params,success: function(gridData){			
			docObj = eval("("+gridData+")");
			dataObj =docObj.rowData;
			groupObj = docObj.groupVal;
			autoFilterObj = docObj.autofiletrVal;
			group_fileid_Obj = docObj.group_fileid_Val;
			idValObj= docObj.idValArr;
			fileObj = docObj.file_id_Val;
			renderGrid();
			renderDocGrid();
				}
			});
		}
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
		
	/*if(!$.isEmptyObject(autoFilterObj)){
		var temp = new Array();
		for(ww in autoFilterObj){
			lovObj['_'+ww]=	autoFilterObj[ww].split(",");			
			lovObj['_'+ww] = ArrUnique(lovObj['_'+ww]);						
		 }				
	}*/
	var headerCount =Object.keys(MainHeaderObj).length;
	var table = '';
	table+='<table width="100%" cellspacing="1" id="maintablewidth" cellpadding="3" border="0" class="tableContentBG">';
	table +='<tr onClick="doRow(\'\');" class="tableCotentTopBackgroundNew"><td colspan="'+headerCount+'" class="tableCotentTopBackgroundNew"></td></tr>';
	table +=getHeaderRow();	
	table +=getGridData();
	
	table +='</table>';
	$("#divRow").html(table);
	freezPane();
	getLoadingCombo(0);	
	} catch(e) {
		alert(e);
	}
}

function freezPane()
{
	 if($('#onImg').is(':checked')){
		changeFreez('F');
	}else if($('#offImg').is(':checked')){
		changeFreez('UF');
	}
}


function renderDocGrid()
{
	try{
		AllFileObj = new Object();
		fileImgCount =0;
		docCount  =0;
		AllFileObj = getAllFiles();
		var total = docObj.total;		
		var view = $("#hdnView").val();
		if(view == "thumb" || view == "list" ){
			getThumbListView();
		} else if (view == "mix") {
			getMixView();
		}
		getDocPagging(docCount);
		getGroups();
		if (Object.keys(AllFileObj).length > 0) {
			grpListval = "";
			for (w in groupObj) {
				grpListval += (grpListval == "") ? groupObj[w] : ","+ groupObj[w];
			}
			$("#grplist").val(grpListval);
		}
		
		getLoadingCombo(0);			
	} catch(e){
		alert(e);
	}
}
function getHeaderRow()
{
	var tempHeaderTable='';
	tempHeaderTable+='<tr onClick="doRow(\'\');" id="h_row2" class="tableWhiteBackground setinternalwidth">';
	
	for(hid in MainHeaderObj){
		if(MainHeaderObj[hid]['column_id']!='itemid')
			tempHeaderTable+='<td align="left" class="tableCotentTopBackgroundNew">'+MainHeaderObj[hid]['field_name']+'</td>';		
		else
			tempHeaderTable+='<td align="left" class="tableCotentTopBackgroundNew" style="width:40px;">'+MainHeaderObj[hid]['field_name']+'</td>';
	}
	
	return tempHeaderTable+='</tr>';
}
function getGridData()
{
	var tempGridTable ='';
	var headerCount =Object.keys(headerObj).length+4;
	var srNo = $("#srNo").val();
	for(row in dataObj)	{
		
			var tempRObj = new Object();
			tempRObj[row] = dataObj[row];		
			tempGridTable+=getRows(tempRObj,srNo);
			srNo++;
	}
	return tempGridTable;
}
function isReadonly(rid)
{
	
	var robj =dataObj[rid];
	var StatusVal = robj['work_status'];
	if(statusObj["_"+StatusVal] && statusObj["_"+StatusVal]['readOnly'])
	{
		return statusObj["_"+StatusVal]['readOnly'];;
	}
	return 0;
}
function getRows(robj,srNo)
{
	var temprowTable = '';
	var class1 = (srNo%2==0)?"even":"odd";
	if($("#act").val()=="EDIT"){
		class1 = class1+'edit';
	}
	
	
	for(rid in robj){
		var RowIDArr= rid.split("_");
		var RowID = RowIDArr[1];
		var deleteCellVal = robj[rid]['delete_cell_flag'];
		tab_id='';
		var deleteCellarr =[];
		if(robj[rid]['is_readonly']==1)
		{
			class1="readonlyHeader "+class1;
			
		}
		var Readonly=isReadonly(rid);
		if(Readonly==1)
		{
			class1="readonlyHeader "+class1;
		}
		if($("#inboxmod").val()==1)
		{
			class1 ='Inbox ' +class1;
		}

		// deleteCellVal.split(",");			
		var class2 = (robj[rid]['delete_flag']==1)?"strikeLine "+ class1:class1;
		temprowTable += '<tr class="'+class2+'" id="TR_'+RowID+'">';
		for(fid in MainHeaderObj){
			var ColID = MainHeaderObj[fid]['column_id'];
			var ColVal = robj[rid][MainHeaderObj[fid]['column_id']];
			
			var addStyleStr ='';
			if(ColID =='srNo' || ColID =='rec_id'){
				var StatusVal = robj[rid]['status'];
				addStyleStr = ' style="background-color:'+statusObj["_"+StatusVal]['bg_color']+';" ';				
			} else {
				addStyleStr = '';
			}	
			
			if(MainHeaderObj[fid]['edit_flag']==1){
				var celVal ='';
			} else {
				celVal=ColID;
			}
			if($.inArray(ColID,deleteCellarr)>=0){
				if(class2!=class1){
					var class3 = '';
				} else {
					var class3 = 'blockbg';
				}
				temprowTable += '<td id="cell_'+ColID+'" class="'+class3+'"></td>';
			}else{
					temprowTable += '<td '+addStyleStr+'>'+getObjElement(tab_id,fid,ColVal,rid);+'</td>'; 
			}
		}
		temprowTable += '</tr>';
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
function getStatusLov(rid)
{
	try{
	var statusLovStr = '';
	var StatusVal = '';	
	if(rid){
		if($("#statusLov"+rid).length>0 && $("#statusLov"+rid).val()!=""){
			StatusVal = $("#statusLov"+rid).val();
		} else {
			StatusVal = dataObj[rid]['status'];
		}
		statusLovStr='<select id="statusLov'+rid+'" name="statusLov'+rid+'" class="selectauto" style="background-color:'+statusObj["_"+StatusVal]['bg_color']+';">';		
	} else {
		StatusVal = filterValObj['work_status'];
		statusLovStr='<select id="statusLov" name="statusLov" onChange="loadGrid();"; class="selectauto">';
		statusLovStr+='<option value="">-Select Status-</option>';		
	}
	for(st_id in statusObj){
		var sel = '';
		var StIDArr = st_id.split("_");
		var StID= StIDArr[1];
		var isEnable=statusObj[st_id]['enable'];
			var disable='';
			if(isEnable==1)
			var disable='disabled="disabled"';
		if(StatusVal==StID){
			sel+='selected="selected"';
		}
		statusLovStr+='<option '+sel+' style="background-color:'+statusObj[st_id]['bg_color']+';" value="'+StID+'" '+disable+'>'+statusObj[st_id]['name']+'</option>';	
	}	
	statusLovStr+='</select>';
	return statusLovStr;
	}catch(e){
		alert(e);
	}
}
function getViewIcon(RowID)
{
	/*var iconStr='';
	iconStr += '<a onclick="openMatrixArea()" href="javascript:void(0)"><div style="float:left" class="view_active_icon"></div></a>';
	return iconStr;*/
	var iconSaveStr='';
	var RowIDArr = RowID.split("_");
	iconSaveStr += '<a onclick="return saveRow('+RowIDArr[1]+');" href="javascript:void(0)"><img border="0" title="Save" src="images/tickmark.png"></a>';
	return iconSaveStr;	
	
}
function getSaveIcon(RowID)
{
	var iconSaveStr='';
	var RowIDArr = RowID.split("_");
	iconSaveStr += '<a onclick="return saveRow('+RowIDArr[1]+');" href="javascript:void(0)"><img border="0" title="Save" src="images/tickmark.png"></a>';
	return iconSaveStr;	
}
function doRow(act)
{
	var EditID = $("#rec_id").val();
	if(act=="EditRow"){
		$("#act").val("EDIT");	
	}else{
		$("#act").val("");		
	}
	var tempObj=new Object();
	tempObj["_"+EditID] = dataObj["_"+EditID];
	var srNo =$("#srNo").val();
	var editStr=getRows(tempObj,srNo);
	$("#TR_"+EditID).replaceWith(editStr);		
	$("#TR_"+EditID).css({"backgroundColor":"#FFCC99","textAlign":"left","cursor":"pointer"});		
}
function getPriorityLov(tab_id,rid,statusValue,dpriorityVal)
{
	var rec_id=rid;
	var status_arr=new Array(0,1,3,12,23,25,33,38,40,46);	
	var priorityLovStr = '';
	var priorityVal='';
	var priority_html='';
	var bgcolor='';
	if(rid){	
		if(document.getElementById('priorityLov'+tab_id+rid) && dpriorityVal!=0 && $("#act").val()=="EDIT"){
			var priorityVal= document.getElementById('priorityLov'+tab_id+rid).value;			
		}
		else if(!arguments[2])
		var priorityVal=row_priority=dataObj[rid]['priority'];		
		var tab_idVal = tab_id;
		var rec_idVal = rid.replace("_","");
		var addfun='';
		var idstr="priorityLov";
		if(!priorityVal || priorityVal=='')
		priorityVal=3;
		
	} else {
		rid="_priority";
		var priorityVal=filterValObj['priority'];
		var idstr="fd_filter";
		var addfun='onchange="filterGrid(event,\''+tab_id+'\',1)"';
		priority_html+='<option value="" style="background-color:white;">-Select-</option>';
	}
		
	for(priority in priorityObj){
		var sel='';
		if(priority=="_"+priorityVal)
		{
			var sel='selected="selected"';
			var bgcolor=priorityObj[priority]['bg_color'];
		}		
		priority_html+='<option value="'+priority.replace('_','')+'" '+sel+' style="background-color:'+priorityObj[priority]['bg_color']+';">'+priorityObj[priority]['shortname']+'</option>';		
	}

	var priority_htmlSel='';
	priority_htmlSel += '<select class="selectauto" name="'+idstr+tab_id+rid+'" id="'+idstr+tab_id+rid+'"  style="background-color:'+bgcolor+';" ';
	priority_htmlSel += 'title="-H : High Priority &#13-M : Medium Priority&#13-L : Low Priority&#13-SIW : Still in Work" ';
	priority_htmlSel += ''+addfun+'>';
	
	priority_html+='</select>';
	return priority_htmlSel+priority_html;
	
	
}
function getStatusLov(tab_id,rid)
{
	try{
	var statusLovStr = '';
	var StatusVal = '';	
	
		if(rid){			
			if(document.getElementById('statusLov'+tab_id+rid) &&  $("#act").val()=="EDIT")
			StatusVal = document.getElementById('statusLov'+tab_id+rid).value;
			else
			StatusVal =dataObj[rid]['work_status'];
		
		var st='';
		
		if(StatusVal && statusObj["_"+StatusVal])
		{
			st='style="background-color:'+statusObj["_"+StatusVal]['bg_color']+';color:'+statusObj["_"+StatusVal]['font_color']+';"';
		}
		else if(StatusVal==0 && statusObj["_"+StatusVal])	// when doing "Deactivate Read Only"
		{
			st='style="background-color:'+statusObj["_"+StatusVal]['bg_color']+';color:'+statusObj["_"+StatusVal]['font_color']+';"';
		}
		var tab_idVal = tab_id;
		var rec_idVal = rid.replace("_","");
		var addfun='';
		statusLovStr='<select id="statusLov'+tab_id+rid+'" name="statusLov'+tab_id+rid+'" '+st+' class="selectauto" '+addfun+' >';		
	} else {
		StatusVal = filterValObj['work_status'];
		statusLovStr='<select id="fd_filter'+tab_id+'_work_status" name="statusLov" onChange="filterGrid(event,\''+tab_id+'\',1);"; class="selectauto">';
		statusLovStr+='<option value="">-Select Status-</option>';		
	}
	for(st_id in statusObj){
		var sel = '';
		var StIDArr = st_id.split("_");
		var StID= StIDArr[1];
		
		if(StatusVal==StID){
			sel+='selected="selected"';
		}
		statusLovStr+='<option '+sel+' style="background-color:'+statusObj[st_id]['bg_color']+';color:'+statusObj[st_id]['font_color']+' " value="'+StID+'">'+statusObj[st_id]['name']+'</option>';	
	}	
	statusLovStr+='</select>';
	return statusLovStr;
	}catch(e){
		alert(e);
	}
}
function getObjElement(tab_id,col_id,ColumnVal,rec_id)
{
	var tempElement = '';
	var autoFilter =  MainHeaderObj[col_id]['filter_auto'];
	var addStr='';

	if(ColumnVal){
		var selVal = ColumnVal;
	}else{
		var selVal ='';
	}
	var is_readonly=0;
	var mainRecID = 0;
	if(rec_id && rec_id!=0){
		
		mainRecID = rec_id;		
		var is_readonly= dataObj[rec_id]['is_readonly'];
		if(dataObj[rec_id]['Header_Row']==1)
		var is_readonly=1;
		
		var Readonly=isReadonly(rec_id);
		if(Readonly==1)
		{
			var is_readonly=1;
		}
		
		/*if(readOnlyGrid==1)
		var is_readonly=1;*/
	
	}
	
	
	var filterType = MainHeaderObj[col_id]['filter_type'];
	var IdStr='';
	var addFun='';
	var ColIdName = MainHeaderObj[col_id]['column_id'];
	var edit_flag= MainHeaderObj[col_id]['edit_flag'];
	var multipleSel = '';
	var is_multiple = MainHeaderObj[col_id]['is_multiple'];
	
	if($("#act").val()=="EDIT"){
		IdStr='editfield'+tab_id+'_';
		if(filterType!=2){
			addFun='';	
		} else {
			addFun='';	
			if(autoFilter==1){	
				addFun='onChange="setTextBox(this.value,this.id);"';	
				addStr='<option style="background-color:#CC99FF;" value="Enter Text">Enter Text</option>';
				IdStr='lov'+tab_id+'_';				
			}
		}
	
	}else {
		IdStr='fd_filter'+tab_id+'_';
		if(filterType!=2){
			addFun='onkeydown="return filterGrid(event,\''+tab_id+'\');"';	
		} else {	
			addFun='onChange="filterGrid(event,\''+tab_id+'\',1);"';	
		}
	}
	var IdVal = IdStr+ColIdName;
	
	if(isNaN(filterType) && is_readonly==1 && filterType!="view_icon")
		return '';
	
	
	
	if(filterType=="status" && is_readonly==0){
		return getStatusLov(tab_id,mainRecID);
	}
	else if(filterType=="view_icon" && is_readonly==0){

		return getViewIcon(mainRecID)
	}
	else if(filterType=="notes" && is_readonly==0 && edit_flag==0){
		
		if(mainRecID)
		{
			var IdVal =filterType+tab_id+mainRecID;
			
			if($("#act").val()=="EDIT"){
			return '<textarea name="'+IdVal+'" id="'+IdVal+'"  onkeyup="activeForNote(this,\''+tab_id+'\',\''+mainRecID+'\');" cols="35" rows="4" >'+selVal+'</textarea>';
			}
			
			return getNotes(tab_id,mainRecID)
		}
	}
	else if(filterType=="ManageIssues" && is_readonly==0){
		
		if(mainRecID)
			return getNotesIcon(tab_id,mainRecID)
	}
	else if(filterType=="responsibility" && is_readonly==0){
		
		if(mainRecID)
		{
			return getResUsers(tab_id,mainRecID);
		}
		else
		{
			var IdVal =ColIdName+tab_id+rec_id;
			return getRespCombo(tab_id,mainRecID,filterType);
		}
	}
	
	else if(filterType=="checkbox" && rec_id && rec_id!=0 && is_readonly==0)
	{
		var checked='';
		
		var IdVal =ColIdName+tab_id+rec_id;
		if(document.getElementById(IdVal) && $("#act").val()=="EDIT")
		{
			var selVal=(document.getElementById(IdVal).checked==true) ? 1 : 0;
		}
		
		
		if(selVal==1)
		{
			checked=' checked ';
		}
		var addStr= '';
		if(rec_id==0){
			addStr+=addFun;
		} else {
			var tab_idVal =tab_id;
			var rec_idVal =rec_id.replace("_","");
			addStr+=' onChange="setSaveAllValue();" ';
		}
		tempElement+='<input type="checkbox" name="'+IdVal+'" '+addStr+' id="'+IdVal+'" value="'+selVal+'" '+checked+' />';	
	}
	else if(filterType=="priority" && is_readonly==0){
		
		return getPriorityLov(tab_id,mainRecID)
	}
	else if(rec_id && rec_id!=0 && $("#act").val()!="EDIT" ){
		return selVal;
	}
	
	else if(rec_id && rec_id!=0 && $("#act").val()=="EDIT"   && edit_flag==1 ){
		return selVal;
	}
	
	if(filterType==0 || filterType==1){
		
		if($("#act").val()=="EDIT"){
                        selVal =  GetMappedTextAreaString(selVal);
			tempElement+='<textarea name="'+IdVal+'" id="'+IdVal+'">'+selVal+'</textarea>';	
		} else {
			
			if(MainHeaderObj[col_id]['column_id']!='itemid')
				tempElement+='<input type="text" name="'+IdVal+'" '+addFun+' id="'+IdVal+'" value="'+selVal+'" class="gridinput_cs">';
			else
				tempElement+='<input type="text" name="'+IdVal+'" '+addFun+' id="'+IdVal+'" value="'+selVal+'" class="gridinput_cs" style="width:90%;">';
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
			tempElement+='<textarea style="display:none;" onFocus="this.value=\'\'" name="editfield_'+ColIdName+'" id="editfield_'+ColIdName+'" cols="22" rows="4">Enter Text</textarea>';	
		}
		
		
		
	} else if(filterType==3) {
		tempElement+='<input type="text" class="date_input" id="'+IdVal+'" value="'+selVal+'" '+addFun+'  tabindex="10" onkeydown="return filter(this.id,event);" readonly="readonly"/>';
		tempElement+='<img border="0" onclick="displayCalendar(document.getElementById(\''+IdVal+'\'),\'dd-mm-yyyy\',this);return false;" ';
		tempElement+=' style="width:18px;height:17px; alignment-adjust:middle; border:0px solid transparent; cursor:pointer;" src="'+pathLink+'images/Calender.gif" alt="Date"> ';
	} 
	return tempElement;	
}

function getUserType(userLevel)
{
	
	var level=userLevel;
	if(level==5)
	{
		return "Client";
	}
	else if(level==1)
	{
		return "Main Client";
	}
	else 
	{
		return "FLYdocs";
	}
	
}
function getUserlevel(user_id)
{	
	return docObj.UserLevel[user_id];
}

function getResUsers(tab_id,rec_id)
{
	if(docObj.NoteData['resp_user'])
	var res_users=docObj.NoteData['resp_user'];
	else
	var res_users=new Object();
	
	var ResObj=new Object();
	var str='';
	
	if(res_users !='' && res_users!=null)
	{
		
		
		for(userId in res_users)
		{	
			
			//if(dataObj.NotesUser && dataObj.NotesUser['_'+userId])
			if(res_users[userId]==1)
			{ 	
				var resUserLevel=getUserlevel(userId);
				
				
				if(resUserLevel == '5' && allowClientNote == '0')
					continue;
				if(!ResObj[resUserLevel])
					ResObj[resUserLevel]=new Object();
				ResObj[resUserLevel][userId]=userId;
				
			}
			
		}
		
		
		str +='<div style="overflow:auto; max-height:150px;z-index:-1;max-width:200px;">';	
		
		for(var level in ResObj)
		{
			if(level==5)
				var resUserLevel=2;
			else if(level==1)
				var resUserLevel=1;
			else
				var resUserLevel=0;
			
			var userStr='';
			
			var resClass=getNoteClass(resUserLevel);
			str+='<div class="'+resClass+'">';
			str+='<strong>Assigned To '+getUserType(level)+' User :</strong><br>'
			for(var users in ResObj[level])
			{
				userStr+=(userStr=='') ? getUserName(users) : ","+getUserName(users);
			}
			str +=userStr+'</div>';	
		}
		 str +='</div>';	
		return str;
	}
	
	return str;
}
function getNoteClass(note_type)
{
	if(note_type==0 || note_type==3)
	{
		return "bluecomm_box";
	}
	else if(note_type==1)
	{
		return "purplecomm_box";
	}
	else if(note_type==2)
	{
		return "pinkcomm_box";
	}
}
function getNotesHeader(userId)
{
	return dataObj.NotesUser['_'+userId]['CompanyName'];
}
function saveRow(RecID)
{
	try{
	var UpdateObj = new Object();
	var tempauditObj = new Object();
	var tempUpObj = new Object();
	var ai=0;
	var StatusVal = $("#statusLov_"+RecID).val();
	var PriorityVal = $("#priorityLov_"+RecID).val();
	var addActionVal =(document.getElementById("add_action_"+RecID) && document.getElementById("add_action_"+RecID).checked==true) ? 1 :0; //$("#add_action_"+RecID).val();
	var denyAccessVal =(document.getElementById("deny_access_"+RecID) && document.getElementById("deny_access_"+RecID).checked==true) ? 1 :0;
	
	if($("#act").val()=="EDIT"){
		for(fid in headerObj){
			var ColID = headerObj[fid]['column_id'];
			var autoFilter = headerObj[fid]['filter_auto'];
			
			var oldVal = '';
			var currentColVal = '';
			if($("#editfield_"+ColID)){
				 currentColVal = $("#editfield_"+ColID).val();
			}
			if(dataObj["_"+RecID][ColID]){
				oldVal=dataObj["_"+RecID][ColID];
			}
			if(currentColVal!="Enter Text" && oldVal!=currentColVal && ColID!='itemid'){
				tempUpObj[ColID] = $("#editfield_"+ColID).val();
				/*audit obj*/
				tempauditObj[ai] = getAuditObj();
				tempauditObj[ai]['field_title']	=headerObj[fid]['field_name'];
				tempauditObj[ai]['old_value'] = dataObj["_"+RecID][ColID];
				tempauditObj[ai]['new_value'] = $("#editfield_"+ColID).val();		
				tempauditObj[ai]['action_id'] = 14;
				tempauditObj[ai]['main_id'] = RecID;
				/*end*/
				ai++;			
			}
		}
	}
	
	if(dataObj["_"+RecID]["work_status"]!=StatusVal){
		tempUpObj['work_status'] =StatusVal;
		/*audit obj*/
		var oldStatusID = dataObj["_"+RecID]["work_status"];
		tempauditObj[ai] = getAuditObj();
		tempauditObj[ai]['field_title']	="Work Status";
		tempauditObj[ai]['old_value'] = statusObj["_"+oldStatusID]['name']+','+statusObj["_"+oldStatusID]['bg_color'];		
		tempauditObj[ai]['new_value'] = statusObj["_"+StatusVal]['name']+','+statusObj["_"+StatusVal]['bg_color'];	
		tempauditObj[ai]['action_id'] = 15;
		tempauditObj[ai]['main_id'] = RecID;
		/*end*/
	}
	
	if(dataObj["_"+RecID]["priority"]!=PriorityVal){
		tempUpObj['priority'] =PriorityVal;
		/*audit obj*/
		
		var oldPriorityID = dataObj["_"+RecID]["priority"];
		
		tempauditObj[ai] = getAuditObj();
		tempauditObj[ai]['field_title']	="Priority";
		tempauditObj[ai]['old_value'] = priorityObj["_"+oldPriorityID]['name']+','+priorityObj["_"+oldPriorityID]['bg_color'];		
		tempauditObj[ai]['new_value'] = priorityObj["_"+PriorityVal]['name']+','+priorityObj["_"+PriorityVal]['bg_color'];	
		tempauditObj[ai]['action_id'] = 14;
		tempauditObj[ai]['main_id'] = RecID;
		/*end*/
	}
	
	if(dataObj["_"+RecID]["add_action"]!=addActionVal){
		tempUpObj['add_action'] =addActionVal;
		/*audit obj*/
		
		tempauditObj[ai] = getAuditObj();
		tempauditObj[ai]['field_title']	="Add to Action List";
		tempauditObj[ai]['old_value'] = dataObj["_"+RecID]["add_action"];		
		tempauditObj[ai]['new_value'] = addActionVal;	
		tempauditObj[ai]['action_id'] = 14;
		tempauditObj[ai]['main_id'] = RecID;
		/*end*/
	}
	
	
	if(dataObj["_"+RecID]["deny_access"]!=denyAccessVal){
		tempUpObj['deny_access'] =denyAccessVal;
		/*audit obj*/
		
		tempauditObj[ai] = getAuditObj();
		tempauditObj[ai]['field_title']	="Deny Access";
		tempauditObj[ai]['old_value'] = dataObj["_"+RecID]["deny_access"];		
		tempauditObj[ai]['new_value'] = denyAccessVal;	
		tempauditObj[ai]['action_id'] = 14;
		tempauditObj[ai]['main_id'] = RecID;
		/*end*/
	}
	
	
	$("#act").val("");
	if($.isEmptyObject(tempUpObj)){
		alert("Record Updated Successfully");
		doRow("");
	} else {
		var whrObj = new Object();
		whrObj={"id":RecID};
		UpdateObj['colVal'] = tempUpObj;
		UpdateObj['whrUpdate'] = whrObj;
		if(LovValueCheck==1){
			UpdateObj['auto_filter'] = 1;
		}
		var AuditObj = new Object();
		AuditObj =tempauditObj;		
		
		xajax_Update(UpdateObj,tempauditObj);
	}
	}catch(e){
		alert(e);
	}
}
function setSaveAllValue()
{

}
function updateRow(upObj)
{	
	try{		
			var upID= upObj["whrUpdate"]["id"];
			var cat_id =dataObj["_"+upID]["category_id"];	
			for(col_id in upObj['colVal']){		
				var colVal = upObj['colVal'][col_id];
				dataObj["_"+upID][col_id] = colVal;
			}
			if(!$.isEmptyObject(upObj['colVal']['work_status'])){
				dataObj["_"+upID]["work_status"] = upObj['colVal']['work_status'];
			}
			if(!$.isEmptyObject(upObj['colVal']['priority'])){
				dataObj["_"+upID]["priority"] = upObj['colVal']['priority'];
			}
			else if(!$.isEmptyObject(upObj['colVal']['add_action']=='add_action')){
						var ColID1='add_action';
						dataObj["_"+upID][ColID1]=upObj['colVal']['add_action'];
					}
			
			var valTempObj = new Object();
			if(upObj["auto_filter"] && upObj["auto_filter"]!=null){
				var tempAutoObj = new Object()
				actDisAct = new Object();
				tempAutoObj =upObj["auto_filter"];	
				if(!$.isEmptyObject(tempAutoObj)){
						var temp = new Array();
						for(aa in tempAutoObj){
							var tempNewArr =  tempAutoObj[aa].split(",");
							for(bb in tempNewArr){
								var tempLVal =tempNewArr[bb]; 
								if(tempNewArr[bb].indexOf("$#@@#$")>=0){
									var tempLarr = tempNewArr[bb].split("$#@@#$");
									var tempLVal =tempLarr[0]; 
									actDisAct[tempLVal]=tempLarr[1];
								}
								if(valTempObj[aa]){						
									valTempObj[aa] = valTempObj[aa]+','+tempLVal;
								} else {
									valTempObj[aa] = tempLVal;
								}
								
							}	
							
						 }				
				}
					
				
				
				for(lvIDVal in valTempObj){
						lvID='_'+lvIDVal; 
						lovObj[lvID] = valTempObj[lvIDVal].split(",");
						lovObj[lvID] = ArrUnique(lovObj[lvID]);
					}
			}
			
			var tempUpObj = new Object();
			tempUpObj["_"+upID] = dataObj["_"+upID];
			var srNo = $("#srNo").val();
			var upStr=getRows(tempUpObj,srNo);	
			$("#TR_"+upID).replaceWith(upStr);
			
			/*update Row of StatusSheet*/
			
			var currentObj = dataObj["_"+upID];
			var RowRecIDObj = window.opener.dataObj.rowData["_"+cat_id]["_"+upID];
			for(c_id in RowRecIDObj){
				if(currentObj[c_id]){
					RowRecIDObj[c_id] = currentObj[c_id];
				}
			}
			
		var cat_id ="_"+cat_id;
		var upID ="_"+upID;		
		window.opener.dataObj.rowData[cat_id][upID] =  RowRecIDObj;	
		var SrNo = window.opener.$("#TR"+cat_id+upID).find("td:first").html();		
		var upStr=window.opener.getRows(SrNo,cat_id,upID);
		//window.opener.ResetVal();
		window.opener.$("#TR"+cat_id+upID).replaceWith(upStr);
	}catch(e){
		alert(e)
	}
	/*end ----------- update Row of StatusSheet*/
	
}


function ArrUnique(arr)
{
	var a = [];
	for(i=0;i<arr.length;i++){
		if($.inArray(arr[i],a)<0){
			a.push(arr[i]);
		}
	}
	return a;	
}
function uploadDoc(upload_dir)
{
	var type = $("#type").val();
	var tab_id = $("#tab_id").val();
	var client_id = $("#client_id").val();
	var recId = $("#rec_id").val();
	var csLinkID= $("#csLinkID").val();
	var left = (screen.width / 2) - (600 / 2);
    var top = (screen.height / 2) - (325 / 2);
	var tabName = tab_name;
	
	compupload = window.open('uploaderpage.php?section_type=airworthiness&tab_id='+tab_id+'&ViewType='+type+'&recId='+recId+'&SectionFlag='+type+'&ComponentId='+ComponentId+'&client_id='+client_id,'compupload','height=360,width=600,top='+top+',left='+left+',resizable=yes,scrollbars=yes,left=50,replace=true');
	compupload.focus();
}

function getThumbListView()
{
	var table = '<div id="divTopPagging"></div>';
	table+='<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="tableWhiteBackground">';
	table+='<tr><td>';
	table+=getGroupHeader();		
	table+='</td></tr>';
	table+='</table><div id="divBottomPagging"></div>';
	$("#divGrid").html(table);
	display_notes();
}
function getMixView()
{
	var temp =  '';
	temp += getGroupHeader();
	var table = '<div id="divTopPagging"></div>';
	table+='<table width="100%" border="0" align="center" cellpadding="0" cellspacing="10" class="tableWhiteBackground">';
	table+='<tr><td width="25%" valign="top">';
	
	if(temp!=''){
		table+='<div style="overflow:auto; height:900px;">';
		table+=temp;	
		table+='</td></div>';
	}
	table+='<td width="75%" valign="top" height="100%" bgcolor="#EAEAEA">';
	if(temp!=''){
		table+='<div style="height:900px" id="bigImage"></div>'
	}
	table+='</td></tr>';
	table+='</table><div id="divBottomPagging"></div>';
	$("#divGrid").html(table);
	var fileObjeLength = Object.keys(fileObj).length;
	if(fileObjeLength>0){
		var firstImgID = Object.keys(fileObj)[0];
		var pathObj = getSmallBigImagePath(firstImgID);
		var img_big = pathObj['img_big'];
		var StatusVal=fileObj[firstImgID]['status'];
		var document_path = fileObj[firstImgID]['document_path'];
		if(classNameObj[StatusVal]){
				className = classNameObj[StatusVal];
			} else {
				className = 'Gray_round_arc';
			}
		if(temp!=''){
			getBigImage(img_big,document_path,className);
		}
	}
}

function getGroupHeader()
{
	var table='';
	for(g_id in AllFileObj){		
		if($("#group_search").val()!=''){
			if(g_id!=$("#group_search").val()){
				continue;
			}
		}
		var tempGrpTable ='';
		var grptable='';
		if(group_fileid_Obj[g_id] && group_fileid_Obj[g_id]!=''){ 
		tempGrpTable+='<table width="100%" cellspacing="0" cellpadding="5" border="0" style="margin-bottom:10px;" >';
		tempGrpTable+='<tr class="tableMainBackground"><td height="40" align="center" colspan="3" class="enginellps_leftheader">';
		tempGrpTable+=groupObj["_"+g_id];
		tempGrpTable+='</td></tr>';
			if($("#hdnView").val()=="thumb"){
				grptable+=getGroupDocs(g_id);
				if(grptable!=''){
					grptable+='</table>';
				}
			} else if($("#hdnView").val()=="list"){ 
				grptable+=getlistDocs(g_id);
				if(grptable!=''){
					grptable+='</table>';
				}
			} else if($("#hdnView").val()=="mix") {
				tempGrpTable+='</table>';
				grptable+=getMixDocs(g_id);
			}
			if(grptable!=''){
				table+=tempGrpTable+grptable;
			}		
		}
	}
	
	return table;
} 

function getGroupDocs(GrpID)
{
	var thumbDoctable='';
	var className = '';	
	var chkRowCount = 0;
	var chkImg = 3;
	var tempThumbtable ='<tr>';	
	for(orderNo in AllFileObj[GrpID]){
		if(chkRowCount==3){
			chkRowCount=0;
			thumbDoctable +='</tr>';
			thumbDoctable +='<tr>';
		}
		
		var cmpId = AllFileObj[GrpID][orderNo];
		var FileID = idValObj[cmpId]['file_id'];
		var StatusVal = idValObj[cmpId]['status'];
		
		if($("#doc_search").val()!=''){
			if(StatusVal!=$("#doc_search").val()){
				continue;				
			}
		}
		
		thumbDoctable+='<td width="30%" align="left">';
		var FileName = fileObj[FileID]['file_name'];
		
		var file_size = fileObj[FileID]['file_size'];
		var download_path = fileObj[FileID]['download_path'];
		var IsSign = fileObj[FileID]['issign'];
		if(classNameObj[StatusVal]){
			className = classNameObj[StatusVal];
		} else {
			className = 'Gray_round_arc';
		}
		
		chkRowCount++;
		thumbDoctable+= '<table width="100%" cellspacing="1" cellpadding="1" border="0" align="left" style="margin-bottom:10px;" id="'+className+'">';
		thumbDoctable += '<tr style="cursor:pointer">';
		thumbDoctable += '<td width="" align="center">';		
		thumbDoctable += getFileImgDiv(FileID,chkRowCount,IsSign);
		thumbDoctable += '</td></tr>';		
		thumbDoctable += '<tr>';
		thumbDoctable += '<td valign="middle" align="center">';
		thumbDoctable += getCheckBox(FileID,cmpId);
                var extarr = FileName.split(".");                
                var pos = extarr[extarr.length-1].indexOf("flydoc");
                if(pos == 0)
                {
                   download_path = getFLYdocPath(fileObj[FileID]);                    
                }
		thumbDoctable += '<a href="#" onClick="open_file(\''+download_path+'\'); return false;" >'+FileName+'('+file_size+' KB)</a>';
		thumbDoctable += '</td>';
		thumbDoctable += '</tr>';
		thumbDoctable += '<tr>';
		thumbDoctable += '<td height="25" align="center"><span class="text">';
		thumbDoctable += getDocStatus(FileID,cmpId);	
		thumbDoctable += '</span></td>';	
		thumbDoctable += '</tr>';
		thumbDoctable += '<tr>';
		thumbDoctable += '<td height="25" align="left">';
		var tempCnt =  parseInt(fileImgCount)+parseInt(chkRowCount);
		thumbDoctable +=getAllicon(FileID,tempCnt); 
		thumbDoctable += '</td>'; 
		thumbDoctable += '</tr>';
		thumbDoctable += '</table>';
		thumbDoctable +='</td>';
		fileImgCount++;									
	}
	var tdDiff= chkImg - chkRowCount;
	if(tdDiff!=0 && thumbDoctable!=''){
		for(i=0;i<tdDiff;i++){
			thumbDoctable +='<td width="30%"></td>';
			fileImgCount++;			
		}
	}
	if(thumbDoctable!=''){
		thumbDoctable+='</tr>';		
		return tempThumbtable+=thumbDoctable;
	}else {
		return '';
	}
}
function getlistDocs(GrpID)
{
	var listDoctable='';
	var className = 'Gray_round_arc';
	for(orderNo in AllFileObj[GrpID]){
		var cmpId = AllFileObj[GrpID][orderNo];
		var FileID = idValObj[cmpId]['file_id'];
		var FileName = fileObj[FileID]['file_name'];
		var document_path = fileObj[FileID]['document_path'];
		var file_size= fileObj[FileID]['file_size'];		
		var StatusVal = idValObj[cmpId]['status'];
		if($("#doc_search").val()!=''){
			if(StatusVal!=$("#doc_search").val()){
				continue;
			}
		}
		var download_path= fileObj[FileID]['download_path'];
		var imgCount=0;
		if(classNameObj[StatusVal]){
			className = classNameObj[StatusVal];
		} else {
			className = 'Gray_round_arc';
		}
		listDoctable+='<tr>';
		listDoctable+='<td width="100%" align="left">';
		listDoctable+= '<table width="100%" cellspacing="1" cellpadding="1" border="0" align="left" style="margin-bottom:10px;" id="'+className+'">';
		listDoctable += '<tr style="cursor:pointer">';
		listDoctable += '<td width="69%" align="left"><span class="imgBorder" style="cursor:pointer;background-color:#FFFF66"><a href="javascript://" style="text-decoration:none">';		
		listDoctable += document_path+'</a></span>';
		listDoctable += '</td>';
		listDoctable += '<td width="31%" align="right">'+getAllicon(FileID,fileImgCount)+'</td></tr>';		
		listDoctable += '<tr>';
		listDoctable += '<td valign="middle" align="left" colspan="2">';
		listDoctable += getCheckBox(FileID,cmpId);
                var extarr = FileName.split(".");                
                var pos = extarr[extarr.length-1].indexOf("flydoc");
                if(pos == 0)
                {
                   download_path = getFLYdocPath(fileObj[FileID]);                    
                }
		listDoctable += '<a href="#" onClick="open_file(\''+download_path+'\'); return false;" >'+FileName+'('+file_size+' KB)</a>';
		listDoctable += '</td>';
		listDoctable += '</tr>';
		listDoctable += '<tr>';
		listDoctable += '<td height="25" align="left"><span class="text">';
		listDoctable += getDocStatus(FileID,cmpId);	
		listDoctable += '</span></td>';	
		listDoctable += '</tr>';		
		listDoctable += '</table>';
		listDoctable +='</td>';
		fileImgCount++;			
		}
	return listDoctable;	
}

function getMixDocs(GrpID)
{
	var mixDocTable ='';
	for(orderNo in AllFileObj[GrpID]){	
		var cmpId = AllFileObj[GrpID][orderNo];	
		var FileID = idValObj[cmpId]['file_id'];
		var FileName = fileObj[FileID]['file_name'];
		var document_path = fileObj[FileID]['document_path'];
		var file_size= fileObj[FileID]['file_size'];
		var StatusVal = idValObj[cmpId]['status'];
		if($("#doc_search").val()!=''){
				if(StatusVal!=$("#doc_search").val()){
				continue;
			}
		}
	var IsSign = fileObj[FileID]['issign'];
        var pathObj = getSmallBigImagePath(FileID,IsSign);
	var img_big = pathObj['img_big'];
	var download_path= fileObj[FileID]['download_path'];
	var className = 'Gray_round_arc';
	if(classNameObj[StatusVal]){
			className = classNameObj[StatusVal];
		} else {
			className = 'Gray_round_arc';
		}
	var img_small = pathObj['img_small'];
	var img_big = pathObj['img_big'];
	mixDocTable+='<table width="100%" cellspacing="1" cellpadding="1" border="0" align="left" style="margin-bottom:10px;" id="'+className+'">';
	mixDocTable+='<tr style="cursor:pointer;" ><td width="" align="center">';
	mixDocTable += '<img  src="'+img_small+'" border="0" width="265" height="250" onclick="getBigImage(\''+img_big+'\',\''+document_path+'\',\''+className+'\')" />';
	mixDocTable+='</td>';
	mixDocTable+='<td width="12%">'+getAllicon(FileID,fileImgCount);+'</td></tr>';	
	mixDocTable+='<tr><td valign="middle" align="left" colspan="2">';
	mixDocTable += getCheckBox(FileID,cmpId);
        var extarr = FileName.split(".");                
        var pos = extarr[extarr.length-1].indexOf("flydoc");
        if(pos == 0)
        {
           download_path = getFLYdocPath(fileObj[FileID]);                    
        }
	mixDocTable += '<a href="#" onClick="open_file(\''+download_path+'\'); return false;" >'+FileName+'('+file_size+' KB)</a></td></tr>';
	mixDocTable += '<tr>';
	mixDocTable += '<td height="25" align="left" colspan="2"><span class="text">';
	mixDocTable += getDocStatus(FileID,cmpId);	
	mixDocTable += '</span></td>';	
	mixDocTable += '</tr>';
	mixDocTable+='</table>';
	mixDocTable+='<br clear="All" >';
	fileImgCount++;	
	}	
	return mixDocTable;
}

function getFLYdocPath(fileObj)
{
    var linkid = $("#hdn_merge_link_id").val();
    var rec_id = $("#rec_id").val();
    var client_id=$("#client_id").val();
    var tab_id=$("#tab_id").val();
    
    var TdrId = fileObj['tdrid'];
    var TdrgroupId = fileObj['tdrgroupid'];
    var IsSign = fileObj['issign'];
    var DocId = fileObj['doc_id'];
    var GroupId = fileObj['box_id'];
    
    var Flydocs_Path='';
    if(IsSign == 2)                        
    {
        Flydocs_Path = "flydocs_metadata.php?part=PDF_Doc";
    }
    else
    {
        Flydocs_Path = "flydocs_metadata.php?part=Show_Doc";                    
    }
    Flydocs_Path+= "&doc_id="+TdrId+"&link_id="+linkid+"&action=edit";
    Flydocs_Path+= "&rec_id="+rec_id+"&group_id="+GroupId+"&tab_id="+tab_id;
    Flydocs_Path+= "&fid="+DocId+"&tempgroup_id="+TdrgroupId+"&AirlinesId="+client_id;
    Flydocs_Path+="&viewtype=25";
    return Flydocs_Path;
}

function getFileImgDiv(file_id,imgSrNo,issign)
{	
	var file_name = fileObj[file_id]['file_name'];
	var extarr = file_name.split(".");
	var fileExt = extarr[extarr.length-1];
	var Container = fileObj[file_id]['container'];
	var fileImgTable = '';
	fileImgTable  +='<table border="0" cellspacing="0" cellpadding="3" align="center">';
	fileImgTable  += '<tr>';
	fileImgTable  += '<td width="100%" align="center">';
	fileImgTable  += '<table border="0" cellspacing="0" cellpadding="3" align="center">';
	fileImgTable  += '<tr style="cursor:pointer;">';
	fileImgTable  += '<td class="imgBorder" style="cursor:pointer" align="center">';
	var fileExt   = extarr[extarr.length-1];
	var ext = '';
	var pathObj = getSmallBigImagePath(file_id,issign);
	var img_small = pathObj['img_small'];
	var img_big = pathObj['img_big'];	
	var zoomflg = parseInt(fileImgCount)+parseInt(imgSrNo);
	fileImgTable += '<div  onmousemove="showZoom('+zoomflg+',event);" onmouseout="hideZoom('+zoomflg+');" style="position:relative;width:265px;">';
	fileImgTable += '<span class="imgBorder" style="cursor:pointer">';
	fileImgTable += '<a id="zLink'+zoomflg+'" style="height:250;display:block;" onClick="return false;" href="'+img_big+'" title="">';
	fileImgTable += '<img  src="'+img_small+'" border="0" width="265" height="250" id="myimage_'+zoomflg+'" />';
	fileImgTable += '<div id="zPreview'+zoomflg+'" class="jqZoomPup zoom-preview" style="top: 89px; left: 145px; visibility: hidden;">  </div></a></span></div>';
 	fileImgTable  += '</td>';		 
	fileImgTable  += '</tr>';
	fileImgTable  +='</table></td></tr></table>';	
	return fileImgTable;	
}

function getCheckBox(fileID,comp_id)
{
	var tempCheckTable ='';
	var container = fileObj[fileID]['container'];
	var DocId = comp_id.replace("_","");
	var file_name = fileObj[fileID]['file_name'];
	tempCheckTable+='<input type="checkbox" onclick="check_uncheck_file(this);" id="checkFile_box_'+DocId+'" name="checkFile_box[]" value="'+fileID+'"/>';
	tempCheckTable+='<input type="hidden" value="'+DocId+'" id="checkFile_id_'+DocId+'" name="checkFile_id[]" />';
	tempCheckTable+='<input type="hidden" value="'+file_name+'" id="checkFile_name_'+DocId+'" name="checkFile_name[]"/>';
	tempCheckTable+='<input type="hidden" name="checkFile_box_cn[]" value="'+container+'" id="checkFile_box_'+DocId+'_cn">';
	return tempCheckTable;
}

function getDocStatus(file_id,comp_id)
{
	var tempfStatus='';
	for(fst_id in fileStatusObj){
		if(fst_id!=3){
			var selchecked='';
			if(idValObj[comp_id]["status"]==fst_id){
				selchecked=' checked="checked" ';
			}
			var compMainId = comp_id.replace("_","");
			tempfStatus+='<input '+selchecked+' onClick="setFileDocStatus('+compMainId+',this);" type="radio" name="file_status'+comp_id+'" id="file_status_'+file_id+'_'+fst_id+'" value="'+fst_id+'"/>'+fileStatusObj[fst_id]+'&nbsp;';
		}
	}
	return tempfStatus;
}
function getAllicon(file_id,imgCount)
{
	var iconTable = '';
	var Container =fileObj[file_id]['container']; 
	var tempPath = fileObj[file_id]['document_path'];
	var file_path= fileObj[file_id]['file_path'];
	var docid= fileObj[file_id]['doc_id'];
	var tip_str = '';
	var file_name = fileObj[file_id]['file_name'];
	var extarr = file_name.split(".");
	var fileExt   = extarr[extarr.length-1];
	var extChk =1;
	if($.inArray(fileExt,xlsFileArr)<0 && $.inArray(fileExt,docFileArr)<0){
		extChk='';
	}
	 tempPath = tempPath.split("\\");
	for(n=0;n<(tempPath.length);n++) {
		tip_str += tempPath[n] + "</br>";
	}
	var clk = '';
	var antclk = '';
	if(extChk == ''){
		 clk = 'onClick="RotateImage_1(\''+file_id+'\',\''+Container+'\',\'clockwise\',\''+file_path+'\',\'comp_matrix\','+imgCount+',\''+docid+'\');"';
		 antclk = 'onClick="RotateImage_1(\''+file_id+'\',\''+Container+'\',\'anticlockwise\',\''+file_path+'\',\'comp_matrix\','+imgCount+',\''+docid+'\');"';
	}
	
	if($("#hdnView").val()=="thumb"){
		iconTable += '<table border="0" cellspacing="0" cellpadding="0" align="center">';
	} else  if($("#hdnView").val()=="list"){
		iconTable += '<table border="0" cellspacing="0" cellpadding="0" align="right">';
	}
	if($("#hdnView").val()!="mix"){
		iconTable += '<tr>';
		iconTable += '<td height="25" align="center">';
	}
	iconTable += '<div style="list-style:none; padding:10px 0; width:100%;"><ul style="list-style:none; margin:0; padding:0;">';
	iconTable += '<li style="float:left;" class="tooltip_icon" onMouseOver="Tip(\''+tip_str+'\')" onMouseOut="UnTip()"></li>';
	iconTable += '<li style="float:left;"  class="rotated_left" onMouseOver="Tip(\'Rotate Image Clockwise\');" onMouseOut="UnTip()" '+clk+' ></li>';
	iconTable += '<li style="float:left;" class="rotated_right" onMouseOver="Tip(\'Rotate Image Anti-clockwise\');" onMouseOut="UnTip()" '+antclk+' ></li>';
	iconTable +='</ul></div>';
	if($("#hdnView").val()!="mix"){
	iconTable +='</td></tr></table>';
	}
	return iconTable;
}
function getDocPagging(total)
{	
	var start = parseInt($("#hdnStart").val());
	var cLimit = ((start+limit) > total) ? total : (start+limit);	
	var table = '<table width="100%" border="0" cellspacing="0" cellpadding="3" >';
	table += '<tr class="tableCotentTopBackground">';
	table += '<td align="left" valign="bottom">';
	table += '<table align="center" border="0" width="99%" cellspacing="3">';
	table += '<tr><td  valign="middle" nowrap="nowrap">';
	table += '<strong>Select All Files:</strong> <input type="checkbox" class="checkAll1" name="checkAll1[]" id="current_page_check" value="CheckAll" onClick="Check_All(this)"> Current page <input type="checkbox" id="all_page_check" class="checkAll2" name="checkAll2[]" value="CheckAll" onClick="Check_All(this)"> All pages';
	table += '<a href="#" onMouseOver="Tip(\'Use this function only if you want to select all files to <br>';
	table += 'save them to your desktop or other similar function. <br> Using this option does not change the ';
	table += 'colour status of any file.\')" onMouseOut="UnTip()"><img width="18" height="18" border="0" style="cursor:pointer;" src="'+pathLink+'images/per_questionmark.png"></a>';
	table += '</td><td nowrap="nowrap">';
	var selstatus=$("#doc_search").val();
	table +='<span >Show documents marked as:</span>&nbsp;&nbsp;<select class="selectauto" onchange="selectDocstatus(this);" id="file_check_status" name="file_check_status">';
	table +='<option value="" '+((selstatus=="")? 'selected="selected"' : '')+'>All</option>';
	table +=getDocumentStatus(selstatus);
	table +='</select><a onmouseout="UnTip()" onmouseover="Tip(\'This option allows you to filter the screen to only show the documents &lt;br&gt; which are marked with your chosen selection, &lt;br&gt; e.g. you only see documents marked as Use.\')" href="#">&nbsp;<img width="18" height="18" border="0" style="cursor:pointer;" src="'+pathLink+'images/per_questionmark.png"></a></td><td nowrap="nowrap">';
	var selsALLtatus=$("#doc_sel_status").val();
	table +='<span >Mark all documents as:</span>&nbsp;&nbsp;<select class="selectauto" onchange="selectAllDocstatus(this);" id="file_check_status_for_doc" name="file_check_status_for_doc">';
	table +='<option value="" '+((selsALLtatus=="")? 'selected="selected"' : '')+'>Select</option>';
	table +=getDocumentStatus(selsALLtatus);	         
    table +='</select><a onmouseout="UnTip()" onmouseover="Tip(\'This option allows you to mark all documents with the same status, <br> e.g. all documents chosen would be marked as <br> Use so that you do not have to select them one at a time.\')" href="#">&nbsp;<img width="18" height="18" border="0" style="cursor:pointer;" src="'+pathLink+'images/per_questionmark.png"></a></td>';                                                        
	table += '<td align="center" width="37%" valign="bottom" nowrap="nowrap">';
	table += '<table width="100%" border="0" cellspacing="0" cellpadding="3"><tr>';	
	table += '<td align="left"  height="30"  valign="middle" >';
	if(start>0){
		table += '<a href="javascript:;" onClick="paging('+(start-limit)+')" style="text-decoration:none;"><b>&laquo; Previous</b></a>';
	}
	table += '&nbsp;</td>';
	if(total!=0){		
	table += '<td  align="center" valign="middle"><strong>'+(start+1)+' - '+cLimit+' of '+total+' Files Found.</strong></td>';
	} else	{
		table += '<td  align="center" valign="middle"><strong>No Files Found.</strong></td>';
	}
	table += '<td  align="left" valign="middle" >';
	table += '<select name="pagNo" id="pagNo" class="select_paging" onchange="paging(this.value)">';
	for(var k=0;k<(total/limit);k++){
		var selected = ((k*limit)==start)? 'selected="selected"' : "";
		table += '<option value="'+(k*limit)+'" '+selected+'>'+(k+1)+'</option>';
		list++;
	}
	if(list==0  || (total/limit)==0){
		table += '<option value="1"> 1</option>';
	}
	table += '</select>';
	table += '&nbsp;</td>';
	table += '<td align="left"  height="30" width="15%" valign="middle" >';	
	if((start+limit)<total)	{
		table += '<a href="javascript:;" style="text-decoration:none;" onClick="paging('+(start+limit)+')"><b>Next &raquo;</b></a>';
	}
	table+='</td><tr></table></td>';
	var v = $("#hdnView").val();
	table += '<td  width="15%" align="right" nowrap="nowrap">Select View Type : <select name="view" id="view"  onChange="chView(this.value)" class="selectauto">';
	table+='<option value="thumb" '+((v=="thumb")? 'selected="selected"' : '')+'  >Thumbnail View</option>';
	table+='<option value="list" '+((v=="list")? 'selected="selected"' : '')+' >List View</option>';
    table+='<option value="mix"  '+((v=="mix")? 'selected="selected"' : '')+'>Mixed View</option>';
	table+=' </select></td>';

	table+='</td></tr></table></td></tr></table>';
	$("#divTopPagging").html(table);
	$("#divBottomPagging").html(table);
}
function getDocumentStatus(selstatus)
{
	tm_table = '';
	for(w in fileStatusObj){
		var sel = '';
		if(selstatus==w){
			sel+=' selected="selected" ';
		}
		tm_table+='<option '+sel+' value="'+w+'" '+((selstatus==fileStatusObj[w])? 'selected="selected"' : '')+'>'+fileStatusObj[w]+'</option>';
	}
	return tm_table;
}

function chView(val)
{
	$("#hdnView").val(val);
	renderDocGrid();
}
function getSmallBigImagePath(file_id,issign)
{
	var file_name = fileObj[file_id]['file_name'];
	var extarr = file_name.split(".");
	var fileExt = extarr[extarr.length-1];
	var Container = fileObj[file_id]['container'];
	if($.inArray(fileExt,xlsFileArr)>=0){
		img_small = pathLink+"images/Excel2007Logo_small.png";
		img_big = pathLink+"images/Excel2007Logo.png";
	} else if($.inArray(fileExt,docFileArr)>=0) {
		img_small = pathLink+"images/DOCX-File_small.png";
		img_big = pathLink+"images/DOCX-File.png";
	} else if($.inArray(fileExt,flydocFileArr)>=0) {
                if(issign == 2)
                {
                    img_small = pathLink+"images/flydocs_template_selected_small.png";
                    img_big = pathLink+"images/flydocs_template_selected.png";
                }
                else
                {
                    img_small = pathLink+"images/flydocs_template_small.png";
                    img_big = pathLink+"images/flydocs_template.png";                    
                }
	} else if($.inArray(fileExt,jpgFileArr)>=0){
		img_small=pathLink+"cloud/getPrev.php?FileName="+file_name+"&FileID="+file_id+"&FileType=&Container="+Container;
		img_big=pathLink+"cloud/getPrev.php?FileName="+file_name+"&FileID="+file_id+"&FileType=&Container="+Container;
	} else {		
		img_small=pathLink+"cloud/getPrev.php?FileName="+file_name+"&FileID="+file_id+"&FileType=SmallPrev&Container="+Container;
		img_big=pathLink+"cloud/getPrev.php?FileName="+file_name+"&FileID="+file_id+"&FileType=BigPrev&Container="+Container;
	}
	return {"img_small":img_small,"img_big":img_big};
}

function getBigImage(img,docs,classname)
{
   var table='';
   var docarr=new Array();
   docarr=	docs.split("/"); 
   table+='<table width="100%" style="height:900px;"  border="0" cellspacing="0" cellpadding="0"><tr>';
   table += '<td align="center" valign="top"><table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" id="'+classname+'">';
   table += '<tr><td height="30" align="center" valign="middle" bgcolor="#CCCCCC">';
   table +=docarr[docarr.length-1];
   table += '</td></tr>';
   table += '<tr><td  align="center" valign="top">';
   table += '<img id="BigImg" src="'+img+'" alt="Photo"  width="800" height="800" />';
   table += '</td></tr></table></td> </tr></table>';
   $('#bigImage').html(table);  
}
function open_file(path)
{
	var CompDownloadWin = window.open(path,'CompDownloadWin','height='+screenH+',width='+screenW+',resizable=yes,scrollbars=yes,left=50,fullscreen=yes,replace=true');
	CompDownloadWin.focus();
}

function check_uncheck_file(obj)
{	
	var fileIdval = obj.id.replace('_box','_id');
	var docIdval = $("#"+fileIdval).val();
	if(obj.checked==false){		
		$("#all_page_check").attr("checked",false);
		$("#current_page_check").attr("checked",false);
		if(checkFileObj[docIdval]){
			delete checkFileObj[docIdval];
		}
	} else {
		checkFileObj[docIdval] = docIdval;
	}	
}
function Check_All(obj)
{
	if(obj.id=='all_page_check'){
		$("#current_page_check").attr("checked",false);	
	} else {
		$("#all_page_check").attr("checked",false);	
	}	
	var chkArr =$("[name=checkFile_box[]]");
	var fileVal = obj.value;

	$.each(chkArr,function(index,filecheckobj)	{
		
		var fileIdval = this.id.replace('_box','_id');
		var docIdval = $("#"+fileIdval).val();
		if(obj.checked==true){
			filecheckobj.checked=true;
			checkFileObj[docIdval]=docIdval;
		} else {
			filecheckobj.checked=false;
			if(checkFileObj[docIdval]){
				delete checkFileObj[docIdval];
			}
		}
	});	
}
function getGroups()
{
	try{
		var table='';
		if(Object.keys(groupObj).length>0)
		{
			var selval=$('#group_search').val();
			table+='<table cellspacing="1" cellpadding="2" border="0"><tr>';
			table+='<td onclick="filter_group(\'\')" '+(selval=='' ? 'class="button_bg_selected"' : 'class="button_bg"'  )+' ><strong>All Documents</strong></td>';
			for(grpId in groupObj)
			{
				var GrpIdsArr = grpId.split("_");
				var grp_id_val = GrpIdsArr[1];
				if(!group_fileid_Obj[grp_id_val]){
					var class1="class='button_bg_inactive'";
					var fun='';
				}
				else if(selval==grp_id_val){
					class1="class='button_bg_selected'";
					var fun='onclick="filter_group(\''+grp_id_val+'\')"';
				} else {
					class1="class='button_bg'";
					var fun='onclick="filter_group(\''+grp_id_val+'\')"';
				}
				table+='<td '+fun+'  '+class1+'><strong>'+groupObj[grpId]+'</strong></td>';				 
			}
			  table+='</tr></table>';
		}
		$('#allGroup').html(table);
	} catch(e){
		alert(e)
		error_on(e,"Issue in Groups.");
	}
}

function filter_group(grp_id)
{
	$("#group_search").val(grp_id);
	$("#hdnStart").val(0);
	$("#hdn_merge_groupID").val(grp_id);
	$("#hdn_select_all_groupID").val(grp_id);
	$("#hdn_group_search").val(grp_id);
	renderDocGrid()
}
function setFileDocStatus(compId,radioObj)
{
	saveAllStatusObj[compId]=	radioObj.value;
}

function saveAll()
{
	var tempObj = new Object();
	var tempauditObj= new Object();
	var g=0;
	var checkStatus = $("#file_check_status").val();
	var upVal = $("#file_check_status_for_doc").val();
	if(upVal!="" && checkStatus!=upVal){
		var upobj = new Object();
		upobj['up_status'] = upVal;
		if(checkStatus && checkStatus!='')
		upobj['whr_status'] =checkStatus;
		if($("#group_search").val()==''){
			var k =0;
			var tempObj1 = new Object();
			var affectedGrpArr = new Array();
			for(x in groupObj){
				var grpIdArr = x.split("_");
				var grp_id_val = grpIdArr[1];
				affectedGrpArr[k] = groupObj[x];
				tempObj1[grp_id_val]=grp_id_val;
				k++;				
			}
			upobj['group_id'] =tempObj1;	
			var affectedGrp = affectedGrpArr.join(",");			
		} else { 
				var tempObj1 = new Object();
				tempObj1[$("#group_search").val()] =$("#group_search").val();
				upobj['group_id'] =tempObj1;
				var affectedGrp = groupObj["_"+$("#group_search").val()];
		}
		tempObj["all_status"] = {"upObj":upobj};	
		var ai = 0;
		var old_value = (checkStatus!='')?fileStatusObj[checkStatus]+'[Affected Group List:'+affectedGrp+']':"-";			
		var new_value = fileStatusObj[upVal]+'[Affected Group List:'+affectedGrp+']';	
		tempauditObj[ai] = getAuditObj();
		tempauditObj[ai]['field_title']	="file Status";
		tempauditObj[ai]['old_value'] =old_value;
		tempauditObj[ai]['new_value'] = new_value;	
		tempauditObj[ai]['action_id'] = 56;
		tempauditObj[ai]['main_id'] = $("#rec_id").val();	
					
	} else {		
	var ai=0;
	if(!$.isEmptyObject(saveAllStatusObj)){
		for(compId in saveAllStatusObj){
				if(idValObj['_'+compId]['status'] != saveAllStatusObj[compId]){
				tempObj[compId] = {"upObj":{"column_name":"status","value":saveAllStatusObj[compId]},
								"whrUpdate":{"id":compId}};
				var StatusVal = saveAllStatusObj[compId];
				var oldStatusID = idValObj['_'+compId]['status'];
				var fileId = idValObj['_'+compId]['file_id'];
				tempauditObj[ai] = getAuditObj();
				tempauditObj[ai]['field_title']	="file Status";
				tempauditObj[ai]['old_value'] = fileStatusObj[oldStatusID];
				tempauditObj[ai]['new_value'] = fileStatusObj[StatusVal];
				tempauditObj[ai]['file_path'] = fileObj[fileId]['document_path']+'/'+fileObj[fileId]["file_name"];
				tempauditObj[ai]['action_id'] = 55;
				tempauditObj[ai]['main_id'] = $("#rec_id").val();	
				ai++;
			}
		}
		}
	}
	saveAllObj[0]=tempObj;
	saveAllObj[1]=tempauditObj;
	if(!$.isEmptyObject(saveAllObj[0]) && !$.isEmptyObject(saveAllObj[1]) ){	
	xajax_saveAll(saveAllObj);	
		resetVal();	
	} else {
		alert("The changes have been saved successfully.");
		return false;
	}
}


function updateGroupDocs(updateObj)
{
	try{		
		var loadObj = new Object();
			loadObj[1]  = 'doc';
			loadGrid(loadObj);
			resetVal();
	}  catch(e) { 
		alert(e);
	}
}
function selectDocstatus(obj)
{
	var oldVal = $("#doc_search").val();
	$("#doc_search").val(obj.value);
	$("#hdn_ghost").val(obj.value);
	$("#hdn_merge_ghost").val(obj.value);
	$("#hdn_select_all_ghost").val(obj.value);
	$("#hdnStart").val(0);
	var loadObj = new Object();
	loadObj[0] = 'doc';
	saveAllStatusObj = new Object();
	saveAllObj= new Object();	
	if(obj.value!=3){
		if(oldVal==3){
			loadGrid(loadObj);
		} else { 
			renderDocGrid();
		}
	} else {
		loadGrid(loadObj);
	}	
}
function resetVal()
{
	saveAllStatusObj = new Object();
	saveAllObj = new Object();
	checkFileObj= new Object();
	$("#file_check_status_for_doc").val("");
}
function selectAllDocstatus(obj)
{
	$("#doc_sel_status").val(obj.value);
}
function paging(intStart)
{
	$("#hdnStart").val(intStart);
	renderDocGrid();
}
function getAllFiles()
{
	var k=0;
	var StartF = $("#hdnStart").val();
	if(StartF!=0){
		var tempL = parseInt(StartF)+parseInt(limit);
	} else {
		var tempL = limit;
	}
	
	var tempObj = new Object();
	for(w in group_fileid_Obj){
		for(comp_id in group_fileid_Obj[w]){		
			if(!tempObj[w]){
				tempObj[w] = new Object();
			}
			var fileID = group_fileid_Obj[w][comp_id];
			var  boxId =idValObj[comp_id]['box_id']; 
			var fileStatus = idValObj[comp_id]['status'];
			if(($("#group_search").val()=="" || $("#group_search").val()== w ) && ($("#doc_search").val()=="" || $("#doc_search").val()==fileStatus)){
				tempObj[w][k] =	comp_id;
				//tempObj[w][comp_id]=	fileID;
				k++;
				docCount++;
			}
		}
	}	
	var tempObj2 = new Object();
	for(x in tempObj){
		for(y in tempObj[x]){			
		if(parseInt(y)>=parseInt(StartF) && parseInt(y)<parseInt(tempL)){
				if(!tempObj2[x]){
					tempObj2[x] = new Object();					
				}
			tempObj2[x][y]=	tempObj[x][y];
			}
		}
	}
	return tempObj2;
}

function RotateImage_1(fileid,container_id,direction,filepath,flag,i,doc_id)
{
	var temp_src= $('#myimage_'+i).attr('src');
	$('#myimage_'+i).attr('src','images/loading_rotate.gif');
	RotateImage_open(fileid,container_id,direction,filepath,flag,temp_src,i,doc_id);
}

function open_audit_trail()
{
	var type = $("#type").val();	
	var	section= $("#sectionVal").val();
	var	main_id= $("#rec_id").val();		
	var	sub_section= 0;
	var headerAudit = window.open('airworthiness_centre_audit.php?section='+section+'&sub_section='+sub_section+'&type='+type+'&main_id='+main_id,'headerAudit',
								 'height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	headerAudit.focus();

}
function gotoRow(gpFlag)
{
	var rec_id =$("#rec_id").val();
	/*var GotoObj=new Object();
	
	GotoObj['comp_main_id'] = $("#tab_id").val();
	GotoObj['component_id']= $("#hdn_merge_link_id").val();
	GotoObj['client_id']= $("#client_id").val();*/
	
	xajax_gotoRow(rec_id,gpFlag);	
}

function reorderDocument()
{	
	var type = $("#type").val();
	var rec_id = $("#rec_id").val();
	var client_id= $("#client_id").val();
	
	var reorderWin = window.open("airworthiness_centre.php?section=4&data_main_id="+rec_id+"&type="+type+"&client_id="+client_id+"&REORDER","reorderWin",
								  "height="+screenH+",width="+screenW+",scrollbars=yes,left=50,resizable=1,fullscreen=yes");
	reorderWin.focus();
}

function fnResetGrid()
{
	resetVal();
	var loadObj = new Object();
	loadObj[1]  = 'doc';
	loadGrid(loadObj);	
}

function openMatrixArea()
{
	var tab_id =$("#tab_id").val();
	var type = $("#type").val();
	var rec_id = $("#rec_id").val();
	var srNo = $("#srNo").val();
	var csLinkID= $("#csLinkID").val();
	var client_id = $("#client_id").val();	
	//var MatrixWin = window.open("compliance_matrix.php?section=matrix&tab_id="+tab_id+"&rec_id="+rec_id+"&type="+type+"&SectionFlag="+type+'&srNo='+srNo+'&client_id='+client_id+"&csLinkId="+csLinkID,							"MatrixWin", "height="+screenH+",width="+screenW+",scrollbars=yes,left=50,resizable=1,fullscreen=yes");
	MatrixWin.focus();
}

function setTextBox(txtVal,LovColID)
{
	var idStr = LovColID.replace("lov", "editfield");
	$("#"+idStr).html(txtVal);
	if(txtVal == "Enter Text"){
		$("#"+idStr).css("display","");
	}else{
		$("#"+idStr).css("display","none");		
	}
	return false;
}

function delete_files()
 {
	if ($("#all_page_check").attr("checked") == false) {
		
		var tempauditObj = new Object();
		var ai = 0;
		var saveAllObj = new Object();
		var tempObj = new Object();
		if (!$.isEmptyObject(checkFileObj)) {
			for (compId in checkFileObj) {
				if (idValObj['_' + compId]['status'] != 3) {
					tempObj[compId] = {"upObj" : {"column_name" : "status","value" : 3 },
						"whrUpdate" : {	"id" : compId } };
					var StatusVal = 3;
					var oldStatusID = idValObj['_' + compId]['status'];
					var fileId = idValObj['_' + compId]['file_id'];
					tempauditObj[ai] = getAuditObj();
					tempauditObj[ai]['field_title'] = "file Status";
					tempauditObj[ai]['old_value'] = fileStatusObj[oldStatusID];
					tempauditObj[ai]['new_value'] = fileStatusObj[StatusVal];
					tempauditObj[ai]['file_path'] = fileObj[fileId]['document_path']+'/'+fileObj[fileId]["file_name"];
					tempauditObj[ai]['action_id'] = 55;
					tempauditObj[ai]['main_id'] = $("#rec_id").val();
					ai++;
				}
			}
			saveAllObj[0] = tempObj;
			saveAllObj[1] = tempauditObj;
			if (!$.isEmptyObject(saveAllObj[0])	&& !$.isEmptyObject(saveAllObj[1])) {
				if(confirm('Are you sure you wants to delete this file(s)?')){
					xajax_saveAll(saveAllObj);
				}
				resetVal();
			} else {
				return false;
			}
		} else {
			alert("PLease Select File to Delete.");
			return false;
		}
	} else {
		
		var tempObj = new Object();
		var tempauditObj = new Object();
		var g = 0;
		var checkStatus = $("#file_check_status").val();
		var upVal = 3;
		var upobj = new Object();
			upobj['up_status'] = upVal;
			if(checkStatus!=""){
				upobj['whr_status'] = checkStatus;
			}
			if ($("#group_search").val() == '') {
				var k = 0;
				var tempObj1 = new Object();
				var affectedGrpArr = new Array();
				for (x in groupObj) {
					var grpIdArr = x.split("_");
					var grp_id_val = grpIdArr[1];
					affectedGrpArr[k] = groupObj[x];
					tempObj1[grp_id_val] = grp_id_val;
					k++;
				}
				upobj['box_id'] = tempObj1;
				var affectedGrp = affectedGrpArr.join(",");
			} else {
				var tempObj1 = new Object();
				tempObj1[$("#group_search").val()] = $("#group_search").val();
				upobj['box_id'] = tempObj1;
				var affectedGrp = groupObj["_" + $("#group_search").val()];
			}
			tempObj["all_status"] = {
				"upObj" : upobj
			};
			var ai = 0;
			var old_value = (checkStatus != '') ? fileStatusObj[checkStatus]
					+ '[Affected Group List:' + affectedGrp + ']' : "-";
			var new_value = fileStatusObj[upVal] + '[Affected Group List:'
					+ affectedGrp + ']';
			tempauditObj[ai] = getAuditObj();
			tempauditObj[ai]['field_title'] = "file Status";
			tempauditObj[ai]['old_value'] = old_value;
			tempauditObj[ai]['new_value'] = new_value;
			tempauditObj[ai]['action_id'] = 23;
			tempauditObj[ai]['rec_id'] = $("#rec_id").val();
			var deleteAllObj = new Object();
			deleteAllObj[0] = tempObj;
			deleteAllObj[1] = tempauditObj;
			if (!$.isEmptyObject(deleteAllObj[0]) && !$.isEmptyObject(deleteAllObj[1])) {
				if(confirm('Are you sure you wants to delete this file(s)?')){
					xajax_saveAll(deleteAllObj);
				}
				resetVal();
			}
	}
	
}

function funopenmerge()
{
	if(!setMergeOrderDiv())
	{
		alert("Please select a minimum of two Documents to merge");
		return false;
	}
	var pop = window.open("about:blank","move_dialog","modal=yes,alwaysRaised=yes,scrollbars=yes,resizable=1,fullscreen=yes");
	pop.focus();
	document.frm_merge.view = 'MERGE';
	document.frm_merge.action = "document_function.php?view=MERGE";
	document.frm_merge.target = "move_dialog"
	document.frm_merge.submit();
}

function setMergeOrderDiv()
{
	//try
	{
		var strChecked = "";
		var strCheckedDetail = '';
		var COMMA ='';
		var srtFileId ='';
		var srtFileName ='';
		var srtFileCn ='';
		var count = 0;

		var elm1 = document.getElementsByName("checkAll1[]");
		var elm2 = document.getElementsByName("checkAll2[]");
		
		if(elm1[0].checked == true || elm1[1].checked == true)
		{
			document.getElementById("hdn_select_all").value = '';
		}
		else if(elm2[0].checked == true || elm2[1].checked == true)
		{
			document.getElementById("hdn_select_all").value = 1;
		}
		else if(elm2[0].checked == false || elm2[1].checked == false)
		{
			document.getElementById("hdn_select_all").value = '';
		}
		
		if(document.getElementById("hdn_select_all").value == 1)
		{
			document.getElementById("hdn_merge_select_all").value = document.getElementById("hdn_select_all").value;
			var arrFiles = document.getElementsByName('checkFile_box[]');
			if(arrFiles.length > 1)
			{
				count = 2;
			}
			document.getElementById("hdn_MergeOrder").value = '';
			document.getElementById("hdnchecked").value = '';
			document.getElementById("hdn_merge_srtFileId").value = '';
			document.getElementById("hdn_merge_srtFileName").value = '';
			document.getElementById("hdn_merge_srtFileCn").value = '';
			/*document.getElementById("hdn_MergeOrder").value = strChecked;
			//document.getElementById("hdnchecked").value = strCheckedDetail;
			document.getElementById("hdn_merge_srtFileId").value = document.getElementById("merge_file_id").value;
			document.getElementById("hdn_merge_srtFileName").value = document.getElementById("merge_Path").value;
			document.getElementById("hdn_merge_srtFileCn").value = document.getElementById("merge_Container").value;*/
		}
		else
		{
			document.getElementById("hdn_merge_select_all").value = '';
			var arrFiles = document.getElementsByName('checkFile_box[]');
			
			for(var i=0; i<arrFiles.length;i++)
			{
				if(arrFiles[i].checked) {
					strChecked += COMMA+count;
					var chkId = arrFiles[i].id.split('checkFile_box');
					srtFileId += COMMA+arrFiles[i].value;
					srtFileName +=   COMMA+document.getElementById("checkFile_name"+chkId[1]).value;
					srtFileCn +=  COMMA+document.getElementById("checkFile_box"+chkId[1]+"_cn").value;
					COMMA = '#:#';
					count++;
				}
			}
			document.getElementById("hdn_MergeOrder").value = strChecked;
			document.getElementById("hdn_merge_srtFileId").value = srtFileId;
			document.getElementById("hdn_merge_srtFileName").value = srtFileName;
			document.getElementById("hdn_merge_srtFileCn").value = srtFileCn;
		}

		if(count >= 2)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	/*catch(e)
	{
		alert(e)
	}*/
}

function Split_Disp()
{
	 var fileId,folderPath,Container;
	 var elm = document.getElementsByName("checkFile_box[]");
	 var chkCnt = 0;
	 for(i=0;i<elm.length;i++)
	 {
		 if(elm[i].checked)
		 {
			var chkId = elm[i].id.split('checkFile_box');				
			fileId = elm[i].value;
			folderPath =   document.getElementById("checkFile_name"+chkId[1]).value;
			Container =  document.getElementById("checkFile_box"+chkId[1]+"_cn").value;	
			chkCnt ++;
		 }
	 }	
	if(chkCnt < 1)
	{
		alert("Please select file to Split");
		return false;
	}
	if(chkCnt > 1)
	{
		alert("Please select single file to Split");
		return false;
	}
	var pop = window.open("document_function.php?view=SPLIT&fileId="+fileId+"&folderPath="+escape(folderPath)+"&Container="+escape(Container)+"", "SPLIT_WINDOW_CS","modal=yes,alwaysRaised=yes,scrollbars=yes,resizable=1,fullscreen=yes");
	pop.focus();
}
function Extract_Disp()
{
	var fileId,folderPath,Container;
	var chkCnt = 0;
	var elm = document.getElementsByName("checkFile_box[]");
	for(i=0;i<elm.length;i++)
	{
		if(elm[i].checked)
		{
			var chkId = elm[i].id.split('checkFile_box');				
			fileId = elm[i].value;
			folderPath =   document.getElementById("checkFile_name"+chkId[1]).value;
			Container =  document.getElementById("checkFile_box"+chkId[1]+"_cn").value;		
			chkCnt++;
		}
	}
	if(chkCnt < 1)
	{
		alert("Please select file to Extract");
		return false;
	}
	if(chkCnt > 1)
	{
		alert("Please select single file to Extract");
		return false;
	}
	var pop = window.open("document_function.php?view=EXTRACT&fileId="+fileId+"&folderPath="+escape(folderPath)+"&Container="+escape(Container)+"", "EXTRACT_WINDOW_CS", "modal=yes,alwaysRaised=yes,scrollbars=yes,resizable=1,fullscreen=yes");
	pop.focus();
}


function download_to_pc()
{	
	var strFileName= "";
	var cnt=0;
	
	var elm1 = document.getElementsByName("checkAll1[]");
	var elm2 = document.getElementsByName("checkAll2[]");
	
	if(elm1[0].checked == true || elm1[1].checked == true)
	{
		document.getElementById("hdn_select_all").value = '';
	}
	else if(elm2[0].checked == true || elm2[1].checked == true)
	{
		document.getElementById("hdn_select_all").value = 1;
	}
	else if(elm2[0].checked == false || elm2[1].checked == false)
	{
		document.getElementById("hdn_select_all").value = '';
	}
	
	if(document.getElementById("hdn_select_all").value == 1)
	{
		var arrFiles = document.getElementsByName('checkFile_box[]');
		//strFileName = document.getElementById("fileid").value;
		document.getElementById("hdn_select_all_id").value = document.getElementById("hdn_select_all").value;
		if(arrFiles.length > 0)
		{
			cnt=1;
		}
	}
	else
	{
		document.getElementById("hdn_select_all_id").value = '';
		var arrFiles = document.getElementsByName('checkFile_box[]');
		for(i=0;i<arrFiles.length;i++)
		{	
			if(arrFiles[i].checked == true) 
			{
				strFileName += ((cnt==0)? "" : ",") + arrFiles[i].value;
				cnt++;
			}
		}
	}
	if(cnt > 0)
	{
		document.frm.act.value="DOWNLOAD";
		document.frm.files_names.value=strFileName;
		document.frm.submit();
		document.frm.act.value="";
	}
	else
	{
		alert("Please select at least one file to Download to PC.");
		return false;
	}
	
}

function upload_share_file()
{
	window.open("https://flydocs.sharefile.com","share_upload","width=980,height=600");
}	
function display_notes()
{
	try
	{
		if(docObj.NoteData['resp_user'])
		res_users=docObj.NoteData['resp_user'];		
		else
		res_users=new Object();
		var i = 0;
		
		
	var temp="";
	temp='<table width="100%" align="center" cellspacing="10" cellpadding="0" border="0" class="tableWhiteBackground">';
    temp+='<tr>';
	
	/* start 1st type of notes */
	if(IsClient!=1)
	{
	temp+='<td width="50%">';
	
	temp+='<form method="POST" action=""  id="internal_form" >';
	 temp+='<input type="hidden" name="private_note_hdn" id="private_note_hdn" value="0" />';
	temp+='<table width="100%" align="center" cellspacing="2" cellpadding="0" border="0"  class="tableMiddleBackground_roundedcorner">';
	temp+='<tr>';
	
	
	
	temp+='<td class="redfont_font">';

	
	temp+='Internal Notes';
	temp+='<table  width="100%" align="left" cellspacing="1" cellpadding="2" border="0">';
	temp+='<tbody>';
	temp+='<tr>';
	temp+='<td style="border:#f0f0f0 1px solid;">';
	temp+='<div style="overflow:auto; height:375px; z-index:-1;">';
	temp+='<table width="100%" align="left" cellspacing="1" cellpadding="2" border="0">';
	temp+='<tbody>';
	
	/* note list start */
	temp+=display_notes_list(0);
	/* note list end */
	
	temp+='</tbody>';
	temp+='</table>';	
	temp+='</div>';
	temp+='</td>';
	temp+='</tr>';
	temp+='</tbody>';
	temp+='</table>';
	temp+='</td>';
	temp+='</tr>';
	
	if(assign_note_flag==1 && Row_readOnly==0)
	{
		temp+='<tr><td class="redfont_font">Add Notes</td></tr>';
		temp+='<tr><td><strong>Please hold CTRL on your Keyboard to select/ deselect multiple users.</strong></td></tr>';
		
		temp+='<tr>';
		temp+='<td nowrap="nowrap">';
			
		
			
			temp+=createUserLov(0);
				
		temp+='</td>';
		temp+='</tr>';
		
		/* select all */
		 temp+='<tr><td>Select All&nbsp;';
		 temp+='<input type="checkbox" onclick="SelectAllReceiver(0);" value="All" id="selectallinternalreceiver" name="selectallintreceiver">';
		 temp+='<span id="internal_check"></span><script>display_resplonsible_combo(\'internal\');</script>';
		 temp+='&nbsp;&nbsp;&nbsp;&nbsp;';
		 temp+='<span id="private_note_check">';
		 if(send_private_note==1)
		 {
			 
			 temp+='Send Note Privately&nbsp;<input type="checkbox" onclick="selectPrivateNote();" value="0" id="internal_private_note_checkbox" name="internal_private_note_checkbox">';
		 }
		 temp+='</span>';
		 temp+='</td></tr>';
		/*end select all*/						  
		
		
		/* note text area */
		temp+='<tr>';
		temp+='<td><textarea style="width:100%; height:100px" id="internal_notes" name="internal_notes"></textarea></td>';
		temp+='</tr>';
		/* end note text area */
		
		/* save button */
		temp+='<tr>';
		temp+='<td height="45" align="left"><input width="175" type="button" height="32" class="button" onclick="save_note(0)" value="'+btTxtsave_internal_notes+'" name="Submit" src="images/saveInternalNotesButton.png">&nbsp;<input type="button" id="ResetFly1" name="ResetFly1" value="'+btTxtreset+'" onclick="resetCombo(0);" class="button"></td>';
		temp+='</tr>';
		/* end save button */
		
	}
	temp+='</table>';
	temp+='</form>';
	temp+='</td>';
	}
	/* end 1st type of notes */
	
	/* start 2nd type of notes*/
	
	
	if(IsClient!=1 && user_level!=1)
	{
		
		
		/* start 3rd type of notes*/
		temp+='<td >';
		
		temp+='<form method="POST" action=""  id="flydocs_form" >';
		temp+='<table width="100%" align="center" cellspacing="2" cellpadding="0" border="0"  class="tableMiddleBackground_roundedcorner">';
		temp+='<tr>';
		
		temp+='<td class="redfont_font">';
	
		temp+='FLYdocs Notes';
		temp+='<table  width="100%" align="left" cellspacing="1" cellpadding="2" border="0">';
		temp+='<tbody>';
		temp+='<tr>';
		temp+='<td style="border:#f0f0f0 1px solid;">';
		temp+='<div style="overflow:auto; height:375px; z-index:-1;">';
		temp+='<table width="100%" align="left" cellspacing="1" cellpadding="2" border="0">';
		temp+='<tbody>';
		
		/* note list start */
		temp+=display_notes_list(2);
		
		/* note list end */
		
		temp+='</tbody>';
		temp+='</table>';	
		temp+='</div>';
		temp+='</td>';
		temp+='</tr>';
		temp+='</tbody>';
		temp+='</table>';
		temp+='</td>';
		temp+='</tr>';
		if(assign_note_flag==1 && Row_readOnly==0)
		{
		temp+='<tr><td class="redfont_font">Add Notes</td></tr>';
		temp+='<tr><td><strong>Please hold CTRL on your Keyboard to select/ deselect multiple users.</strong></td></tr>';
		
		temp+='<tr>';
		temp+='<td nowrap="nowrap">';
		temp+=createUserLov(2);
		temp+='</td>';
		temp+='</tr>';
		
		/* select all */
		 temp+='<tr><td>Select All&nbsp;';
		 temp+='<input type="checkbox" onclick="SelectAllReceiver(2);" value="All" id="selectallflydocsreceiver" name="selectallflydocsreceiver">';
		 temp+='<span id="flydocs_check"></span><script>display_resplonsible_combo();</script>';
		 temp+='&nbsp;&nbsp;&nbsp;&nbsp;';
		 temp+='</td></tr>';
		/*end select all*/						  
		
		
		/* note text area */
		temp+='<tr>';
		temp+='<td><textarea style="width:100%; height:100px" id="flydocs_notes" name="flydocs_notes"></textarea></td>';
		temp+='</tr>';
		/* end note text area */
		
		/* save button */
		temp+='<tr>';
		temp+='<td height="45" align="left"><input width="175" type="button" height="32" class="button" onclick="save_note(2)" value="'+btTxtsave_flydocs_notes+'" name="Submit" src="images/saveflydocsNotesButton.png">&nbsp;<input type="button" id="ResetFly3" name="ResetFly3" value="'+btTxtreset+'" onclick="resetCombo(2);" class="button"></td>';
		temp+='</tr>';
		/* end save button */
		}
		temp+='</table>';
		temp+='</form>';
		temp+='</td>';
		/*end 3rd type of notes */
		
		temp+='</tr>';
	}
	temp+='</table>';
	document.getElementById("notes").innerHTML=temp;
		if(IsClient==1)
		{
				//getRespUser(1)
		}
		else
		{
			if(Row_readOnly==0){
				getRespUser(0);
				//getRespUser(1)
				if(IsClient!=1 && user_level!=1){
						getRespUser(2);
				}
			}
		}
	
}
catch(e)
{
	alert(e)
}
}
function createUserLov(flg)
{
	
	var table=''
	var MainUserArr=docObj.MainUserArr;
	
	
	for(userLevel in  userTypeArr)
	{
		if(userLevel=='unique' || userLevel=='indexOf')
			continue;
		 if(flg==0 && userLevel==5)
			continue;
		 if(flg==2 &&  (userLevel==1 || userLevel==5))
			continue;
		
		
		
		  table+='<select name="'+flagArr[flg]+'_'+userComboArr[userLevel]+'" id="'+flagArr[flg]+'_'+userComboArr[userLevel]+'" multiple="multiple" size="15" onChange="getRespUser('+flg+')" >';
		  table+=' <option value=""  disabled="disabled"  class="select1">Select '+userTypeArr[userLevel]+'</option>';
		
		if(MainUserArr && MainUserArr[userLevel])
		{
			
		  for(X in  MainUserArr[userLevel])
		  {
			if(X=='unique' || X=='indexOf')
			continue;
			
			  if(userLevel==1 || userLevel==5)
			  table+='<option disabled="disabled"><strong>'+X+'</strong></option>';
			  	if(userLevel!=1)
				{
				
						var userIds=MainUserArr[userLevel][X];
						
						var tempval=userIds.split(",");
						
						for(var Z in tempval)
						{
							if(Z=='unique' || Z=='indexOf')
							continue;
							
							var id=tempval[Z]
							var sel='';
							
							if(res_users[id] && res_users[id]!='')
								{
									var tempid=res_users[id];
									
									if(res_users[id])
									{
										var sel="selected='selected'"
									}
							}
							table+='<option '+sel+' value="'+id+'">'+getUserName(id)+'</option>';
						}
				}
				else
				{
				
					for(var Y in MainUserArr[userLevel][X])
					{
					
							if(Y!=0)
							table+='<option class="red_font" disabled="disabled">'+Y+'</option>';
							var userIds=MainUserArr[userLevel][X][Y];
						
							
							var tempval=userIds.split(",");
							for(var Z in tempval)
							{
								if(Z=='unique' || Z=='indexOf')
								continue;
								var id=tempval[Z];
								var sel='';
								if(res_users[id] && res_users[id]!='')
								{
									var tempid=res_users[id];
									
									if(res_users[id])
									{
										var sel="selected='selected'"
									}
								}
								table+='<option '+sel+' value="'+id+'">'+getUserName(id)+'</option>';
							}
						
						
					}
				}
		  }
		}
		  table+='</select>';
	}
	
		

	table+='<span  id="'+flagArr[flg]+'_responsible_div"> </span>';

	
	return table;
	
}
function getUserName(id)
{
	return docObj.username[id];
}

function getRespUser(flg)
{
	var Mainstr='';
	
	
	//res_users=new Object();
	for (x in userTypeArr)
	{
		
		if(x=='unique' || x=='indexOf')
		continue;
		var optstr='';
		
		var obj=document.getElementById(flagArr[flg]+"_"+userComboArr[x]);
		if(obj)
		{
			for(var i=0;i<obj.options.length;i++)
			{
				
				var id =obj.options[i].value;
				var sel=obj.options[i].selected;
				
				var tempid=Array();
				if(res_users && res_users[id] && res_users[id]!='')
				{
					var tempid=res_users[id];
				}
				
				if(tempid[1]==1 && sel==false)
				{
					
					delete res_users[id];
				}
				
				if(sel==true)
				{
					
					var sel='';
					if(tempid==1)
					{
						var sel='selected="selected"';
					}
					
					optstr+='<option '+sel+'  value="'+id+'">'+getUserName(id)+'</option>';
				}
			
			}
		}
		if(optstr!='')
		{
			Mainstr+='<option value=""  disabled="disabled"  class="select1">Select '+userTypeArr[x]+'</option>';
			Mainstr+=optstr;
		}
	}
	
	var table='<select name="responsible_'+flagArr[flg]+'" id="responsible_'+flagArr[flg]+'" multiple="multiple" size="15" >';
	table+='<option style="color:#FF0000;font-weight:bold" disabled="disabled">Assign Responsibility To:</option>';
	table+=Mainstr;
	table+='</select>'
	
	document.getElementById(flagArr[flg]+'_responsible_div').innerHTML=table;
	
}

function display_notes_list(note_type)
{ 
	var note_list_str="";
	note_id_arr = new Array();
	var note_arr=docObj.NoteData[note_type];
	for(note_id in note_arr)
	{
		
		note_id_arr = note_id.split("_");
		note_id_val = note_id_arr[1];
		var sender=note_arr[note_id]['sender'];
		notesender_name=getUserName(sender);
		
		
		note_list_str+='<tr class="pink_bottomborder" id="note_row_'+note_id_val+'">'
		note_list_str+='<td  valign="top" align="left"><strong>'+notesender_name+'</strong> [ '+note_arr[note_id]['date']+' ] <strong>:</strong> &nbsp; </td>';
		note_list_str+='<td align="left" valign="top" width="60%">';
		
		note_list_str+='<span id="mainnotes_'+note_id_val+'">'+note_arr[note_id]['notes'].replace(/\n/gi, "<br>")+'</span>';
		note_list_str+='<textarea id="notes_'+note_id_val+'" name="notes" style="display:none;height:auto;width:80%">'+note_arr[note_id]['notes']+'</textarea><br>';
		note_list_str+='<input type="button" onclick="update_note('+note_type+','+note_id_val+')" alt="update" class="button" value="'+btTxtupdate+'" style="display:none" id="update_'+note_id_val+'"><br><br>';
		note_list_str+='<td valign="top">';
		if(IsClient==1)
		{
			/*if(UserLevel==0 || UserID==notesender_arr[1] )
			{
			note_list_str+='<img border="0" onclick="edit_note('+note_id_val+')" title="Edit" src=".././images/Edit_dis.gif">&nbsp; <img border="0" onclick="delete_note('+note_id_val+')"'; 	
			note_list_str+='title="Delete" src=".././images/remove.gif">';
			}*/
		}
		else
		{
			if(Row_readOnly==0){
				if(UserLevel==0 || UserID==sender)
				{
					note_list_str+='<img border="0" onclick="edit_note('+note_id_val+')" title="Edit" src="./images/Edit_dis.gif">&nbsp; <img border="0" onclick="delete_note('+note_type+','+note_id_val+')"'; 	
					note_list_str+='title="Delete" src="./images/remove.gif">';
				}
			}
		}
		
		note_list_str+='</td>';
		note_list_str+='</tr>';
	}
	return note_list_str;
}
function delete_note(note_type,id)
{ 
	if(confirm("Are you sure you wish to delete this note?"))
	{
		//document.getElementById('deleteId').value = id;
		//document.getElementById('mode').value = 'delet';
		//document.staffnotes.submit();
	
		var rec_id = $("#rec_id").val();
		var type = $("#type").val();
		var noteVal=new Object();
		noteVal['data_main_id']=rec_id;
		
		var noteReciever=new Object();
		noteReciever['id']=id;
		
		
		var AuditObj=getAuditObj();
		AuditObj['old_value']=docObj.NoteData[note_type]["_"+id]['notes'];
		AuditObj['action_id']=37;
		AuditObj['main_id']=rec_id;
		AuditObj['type']=type;
		
		xajax_save_note(noteVal,noteReciever,AuditObj,3);
		
		
	}
}

function edit_note(id)
{ 
	//document.getElementById("mode").value = 'Edit';
	document.getElementById("notes_"+id).style.display = '';
	document.getElementById("update_"+id).style.display = '';
	document.getElementById("mainnotes_"+id).style.display = 'none';
}

function update_note(note_type,id)
{
	
		var rec_id = $("#rec_id").val();
		var type = $("#type").val();
		var noteText = $("#notes_"+id).val();
		var noteVal=new Object();
		noteVal['notes']=noteText;
		
		var noteReciever=new Object();
		noteReciever['id']=id;
		
		
		var AuditObj=getAuditObj();
		AuditObj['new_value']=noteText;
		AuditObj['old_value']=docObj.NoteData[note_type]["_"+id]['notes'];
		AuditObj['action_id']=38;
		AuditObj['main_id']=rec_id;
		AuditObj['type']=type;
		
		xajax_save_note(noteVal,noteReciever,AuditObj,2);
		docObj.NoteData[note_type]["_"+id]['notes']=noteText;
		display_notes();
		if(window.opener)
		{
			
			var obj=window.opener.document.getElementById('note_'+id);
			if(obj)
			obj.innerHTML=docObj.NoteData[note_type]["_"+id]['notes'];
		}
	
} 
function SelectAllReceiver(note_type)
{
	
 if(document.getElementById("selectall"+flagArr[note_type]+"receiver").checked)
 {
 
	if(document.getElementById(flagArr[note_type]+"_flydocs"))
	{
		 var elm = document.getElementById(flagArr[note_type]+"_flydocs");
		 for(i=1;i<elm.options.length;i++)
		 {
			  if(elm.options[i].disabled == false)
			  {
				elm.options[i].selected=true;
			  }
		 } 	
	}
	if(document.getElementById(flagArr[note_type]+"_airlines"))
	{
		 var elm = document.getElementById(flagArr[note_type]+"_airlines");
		 for(i=1;i<elm.options.length;i++)
		 {
			  if(elm.options[i].disabled == false)
			  {
				elm.options[i].selected=true;
			  }
		 } 	
	}
	/*if(document.getElementById(note_type+"_client"))
	{
		 var elm = document.getElementById(note_type+"_client");
		 for(i=1;i<elm.options.length;i++)
		 {
			  if(elm.options[i].disabled == false)
			  {
				elm.options[i].selected=true;
			  }
		 } 	
	}*/
 }
 else
 {
 	if(document.getElementById(flagArr[note_type]+"_flydocs"))
	{
		 var elm = document.getElementById(flagArr[note_type]+"_flydocs");
		 for(i=1;i<elm.options.length;i++)
		 {
			  if(elm.options[i].disabled == false)
			  {
				elm.options[i].selected=false;
			  }
		 } 	
	}
	if(document.getElementById(flagArr[note_type]+"_airlines"))
	{
		 var elm = document.getElementById(flagArr[note_type]+"_airlines");
		 for(i=1;i<elm.options.length;i++)
		 {
			  if(elm.options[i].disabled == false)
			  {
				elm.options[i].selected=false;
			  }
		 } 	
	}
	/*if(document.getElementById(note_type+"_client"))
	{
		 var elm = document.getElementById(note_type+"_client");
		 for(i=0;i<elm.options.length;i++)
		  {
			  if(elm.options[i].disabled == false)
			  {
			  	elm.options[i].selected=false;
			  }
		  } 
	}*/
 }
	
		  getRespUser(note_type)
	

}
function selectPrivateNote()
{
	if(document.getElementById("internal_private_note_checkbox") && document.getElementById("internal_private_note_checkbox").checked==true)
	{		
		
		
		
		document.getElementById("private_note_hdn").value=1;
		document.getElementById("responsible_internal").disabled='disabled';	
		
		var start='<select  disabled=disabled name="responsible_internal" id="responsible_internal" multiple="multiple" size="15" onchange=Check_User()>';
		var end='</select>';
		var aa='<option style="color:#FF0000;font-weight:bold" disabled="disabled">Assign Responsibility To:</option>';
		
		if(document.getElementById('responsible_internal'))
		{
			 var elm = document.getElementById('responsible_internal');
			 for(i=1;i<elm.options.length;i++)
			 {
				 /* if(elm.options[i].disabled == false)
				  {*/
					elm.options[i].style='display:none';
				 // }
			 } 	
		}
		
		
	}
	else
	{
		document.getElementById("responsible_internal").disabled='';
		document.getElementById("private_note_hdn").value=0;
		res_array=[];
		for (var i=0, iLen=res_users.length; i<iLen; i++) 
		{
			
				 res_array.push(parseInt(res_users[i]));
			
		}
		getRespUser(0);
	}	
}

function save_note(note_type)
{
	var noteBox=flagArr[note_type]+'_notes';
	var noteText=document.getElementById(noteBox).value;
	
	if(noteText.replace(/ /gi, "").length > 0)
	{
		var noteVal=new Object();
		if(note_type==0 && document.getElementById(flagArr[note_type]+"_private_note_checkbox") && document.getElementById(flagArr[note_type]+"_private_note_checkbox").checked==true )
		{
			if(!document.getElementById("responsible_"+flagArr[note_type]) || document.getElementById("rsponsible_"+flagArr[note_type])=='')
			{
				alert("You need to select a recipient for your "+flagArr[note_type]+" Note") ;
				return false;
			}
			else
			{
				noteVal['private_note']=1;
			}
		}
		else if( !document.getElementById("responsible_"+flagArr[note_type]) || document.getElementById("rsponsible_"+flagArr[note_type])=='')
		{
			alert("You need to select a recipient for your "+flagArr[note_type]+" Note") ;
			return false;
		}
		else if( document.getElementById("responsible_"+flagArr[note_type]) && document.getElementById("responsible_"+flagArr[note_type]).value=="" )
		{
			alert("Please Select Responsible Persons") ;
			return false;
		}
		
		var rec_id = $("#rec_id").val();
		var type = $("#type").val();
		noteVal['notes']=noteText;
		noteVal['admin_id']=UserID;
		noteVal['data_main_id']=rec_id;
		noteVal['doc_note_type']=(note_type==0) ? 0 : 2 ;
		noteVal['notes_type']=user_level;
		noteVal['type']=type;
		noteVal['comp_main_id']=$("#tab_id").val();
		noteVal['component_id']=ComponentId;
		noteVal['client_id']=$("#client_id").val();		
		
		var noteReciever=new Object();
		var i=0;
		$( "#responsible_"+flagArr[note_type]+" option" ).each(function(index, element) {
			
			if($(this).attr('disabled')==false)
			{
				noteReciever[i]=new Object();
				noteReciever[i]['receiver']=this.value;
				noteReciever[i]['data_man_id']=rec_id;
				
				if($(this).attr('selected'))
				{
					noteReciever[i]['responsblity']=1;
				}
				
				i++;
			}
        });
	
		var AuditObj=getAuditObj();
		AuditObj['new_value']=noteText;
		AuditObj['action_id']=36;
		AuditObj['main_id']=rec_id;
		AuditObj['field_title']=commentType;
		var recIdval = dataObj['_'+rec_id]['rec_id'];
		AuditObj['rec_id']=recIdval;				
		getLoadingCombo(1);	
		xajax_save_note(noteVal,noteReciever,AuditObj);
		
		
	}
	else
	{
		alert("Please Enter Notes") ;
		return false;
	}
	
}
function resetCombo(note_type)
{
		
	
		
		if(note_type == 0)
		{
			if(document.getElementById('selectallinternalreceiver').checked == true)
			{
				document.getElementById('selectallinternalreceiver').checked = false;
			}
			if(document.getElementById("internal_notes").value != '')
			{
				document.getElementById("internal_notes").value = '';
			}
			
			
			var elm = document.getElementById("internal_airlines");
			for(i=0; i<elm.options.length; i++)
			{
				elm.options[i].selected = false;
			}
			var elm = document.getElementById("internal_flydocs");
			for(i=0;i<elm.options.length;i++)
			{
				if(elm.options[i].disabled == false)
				{
					elm.options[i].selected = false;
				}
			}
			getRespUser(note_type);
		}
		
		else if(note_type == 2)
		{
			if(document.getElementById('selectallflydocsreceiver').checked == true)
			{
				document.getElementById('selectallflydocsreceiver').checked = false;
			}
			if(document.getElementById("flydocs_notes").value != '')
			{
				document.getElementById("flydocs_notes").value = '';
			}
			if(document.getElementById("flydocs_flydocs"))
			{
				var elm = document.getElementById("flydocs_flydocs");
				for(i=0; i<elm.options.length; i++)
				{
					elm.options[i].selected = false;
				}
			}
			
			getRespUser(note_type);
		}
	
}
function getAuditObj()
{
	var tempAudit = new Object();
	tempAudit = {"section":4,"user_id":UserID,"user_name":user_name,"type":$("#type").val(),"client_id":$("#client_id").val(),"tail_id":$("#comp_id").val()};	
	return tempAudit;
}
function updateNoteData(note_data)
{
	
	docObj.NoteData=new Object();
	docObj.NoteData=eval(note_data);
	
	display_notes();
	renderGrid();
}
function updateParentNotes(fArr)
{
	try{		
		
		if(window.opener && Object.keys(fArr).length>0){			
			var rec_id =$("#rec_id").val();			
			var flagVal=fArr["flagVal"];
			var rUser = new Object();
			if(fArr["notesObj"]["resp_user"] && fArr["notesObj"]["resp_user"][rec_id]){
				rUser=fArr["notesObj"]["resp_user"][rec_id];
			}
			var lastId = fArr["last_id"];
			var userDetObj =fArr["userDet"];
			var tempObj = new Object();
			
			if(!window.opener.dataObj.Notes[rec_id]){
				window.opener.dataObj.Notes[rec_id] = new Object();
			}			
			
			
			tempObj=fArr["notesObj"][rec_id];			
			window.opener.dataObj.Notes[rec_id]=tempObj;
			window.opener.dataObj.Notes["resp_user"][rec_id]= new Object();			
			
			for(var z in rUser){				
				window.opener.dataObj.Notes["resp_user"][rec_id][z]=rUser[z];
				window.opener.dataObj.Notes["users"][rUser[z]] =rUser[z];
				if(!window.opener.dataObj.NotesUser[rUser[z]]){
					window.opener.dataObj.NotesUser[rUser[z]]= new Object();
				}					
				if(userDetObj[rUser[z]]){
					window.opener.dataObj.NotesUser[rUser[z]] ={"CompanyName":userDetObj[rUser[z]]["CompanyName"],"UserLevel":userDetObj[rUser[z]]["UserLevel"],"username":userDetObj[rUser[z]]["username"]};
				}
				
			}
			
			if(!window.opener.dataObj.NotesUser[UserID] && userDetObj[UserID])
			{
				window.opener.dataObj.NotesUser[UserID] ={"CompanyName":userDetObj[UserID]["CompanyName"],"UserLevel":userDetObj[UserID]["UserLevel"],"username":userDetObj[UserID]["username"]};
			}
			
			var deletedId=fArr["deletedId"];
			if(window.opener.dataObj.Notes[rec_id] && deletedId!=null && deletedId!=0 && flagVal==3){
				if(window.opener.dataObj.Notes[rec_id]["_"+deletedId]){
					delete window.opener.dataObj.Notes[rec_id]["_"+deletedId];
				}
				if(window.opener.dataObj.Notes[rec_id] && Object.keys(window.opener.dataObj.Notes[rec_id]).length==0 && flagVal!=3){
					if(window.opener.dataObj.Notes["resp_user"][rec_id])
					delete window.opener.dataObj.Notes["resp_user"][rec_id];
				}
					
				
			}
			var cat_id =dataObj["_"+rec_id]["category_id"];		
			var cat_id ="_"+cat_id;
			var upID ="_"+rec_id;		
			var SrNo = window.opener.$("#TR"+cat_id+upID).find("td:first").html();		
			var upStr=window.opener.getRows(SrNo,cat_id,upID);		
			window.opener.$("#TR"+cat_id+upID).replaceWith(upStr);			
			
		}
		getLoadingCombo(0);
	}catch(e){
		alert(e)
	}
	
}

function OpenEdocTemplate()
{
	
	var type =25;	
	var tab_id= $("#tab_id").val();	
	var rec_id= $("#rec_id").val();
	var LinkID= $("#hdn_merge_link_id").val();
	var client_id= $("#client_id").val();
	
		
	var	sub_section= 0;
	
	
	var addFlydocs = window.open('flydocs_metadata.php?part=Add_Doc&viewtype='+type+'&tab_id='+tab_id+'&RecId='+rec_id+'&LinkID='+LinkID+'&AirlinesId='+client_id,'headerAudit',
								 'height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	addFlydocs.focus();
}

function CopyAirworthi(flg)
{
	try{	
	var k=0;	
	var auditObj = new Object();
	var actionid=0;
	if(flg=='COPY')
		actionid=57;
	else if(flg=='MOVE')
		actionid=60;
	if($("#all_page_check").attr("checked")==false){
		for(var y in checkFileObj){
			var tempAudit = new Object();	
			tempAudit = getAuditObj();
			tempAudit['field_title']="Airworthiness Review";
			tempAudit['tail_id']=$("#comp_id").val();
			tempAudit['action_id'] = actionid;
			var new_tail_id=0;
			var new_tail_idArr = new Array();			
			if($("#tab_1").length>0 && $("#tab_1").val()!='0'){
				new_tail_idArr = $("#tab_1").val().split("|");					
				new_tail_id = new_tail_idArr[1];
					
			}
			tempAudit['new_tail_id']=new_tail_id;
			tempAudit['type']=$("#type").val();
			var selAttachType= 0;
			if($("#selAttachType").length>0 && $("#selAttachType").val()!=0){
				selAttachType = $("#selAttachType").val();
			}
			tempAudit['new_type']=selAttachType;
			var tabName= HtmlEncode(tab_name+"&nbsp;&raquo;&nbsp;Airworthiness Review");
			tempAudit['tab_name']=tabName;
			var new_tab_name = '';
			if(selAttachType!=7 && selAttachType!=12){
				new_tab_name = $("#CSTabCombo_1 option:selected").text()+"&nbsp;&raquo;&nbsp;"+$("#CSTabCombo_2 option:selected").text();
			}
			var flId = idValObj['_'+y]["file_id"];
			tempAudit['new_tab_name']=HtmlEncode(new_tab_name);	
			tempAudit['main_id']=$("#rec_id").val();
			
			tempAudit['box_id']=idValObj['_'+y]["group_id"];
			tempAudit['new_box_id']=$("#Attachgroup").val();
			tempAudit['file_id']=flId;
			tempAudit['file_path']=fileObj[flId]["document_path"]+'\\'+fileObj[flId]["file_name"];	
			auditObj[k]=tempAudit;
			k++;
		}	
		$("#auditAirworthiObj").val(JSON.stringify(auditObj));		
	}
	attachDocument(flg);
	}catch(e){
		alert(e);
		return false;
	}
	
	
}
function HtmlEncode(s)
{
	
  var el = document.createElement("div"); 
  el.innerText = el.textContent = s;
  s = el.innerHTML;
  return s;
}
