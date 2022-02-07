// JavaScript Document
var UserID=0;
var user_level=0;
var user_name='';
var tab_name = '';
var headerArrObj= new Object();
var headerObj= new Object();
var lovObj = new Object();
var statusArrObj = new Object();
var priorityArrObj = new Object();
var statusObj= new Object();
var priorityObj= new Object();
var dataObj  = new Object();
var NotesObj=new Object();
var autoFilterObj= new Object();
var currentID=0;
var startLimit=0;
var Total=0;
var HideVal = "HD";
var screenW = 640, screenH = 480;
var currentCell=0;
var LovValueCheck = new Object();
var categoryRecIds= new Object();
var filterValObj = new Object();
var MainHeaderObj= new Object();
var readOnlyGrid=0;
var respUserObj = new Object();
var SectionFlag='';
var saveAllDataObj = new Object();
var auditInboxObj =new Object();
var recidArr=[];
var is_bible = 1;
var checkedHObj = new Object();
var total_today=0;
var total_unread=0;
var totalhyperlinkRows = 0;
var commentType = '';
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
SimpleContextMenu.attach('odd', 'contextmenu');
SimpleContextMenu.attach('even', 'contextmenu');
SimpleContextMenu.attach('Header', 'contextmenu_Header');
SimpleContextMenu.attach('subHeader', 'contextmenu_subHeader');
SimpleContextMenu.attach('blockbg', 'contextmenu_deleteCell');
SimpleContextMenu.attach('strikeLine', 'contextmenu_delete');
SimpleContextMenu.attach('readonly', 'contextmenu_readonly');
SimpleContextMenu.attach('readonlyHeader', 'contextmenu_readonlyHeader');
SimpleContextMenu.attach('Inbox', 'contextmenu_inbox');
Object.keys=Object.keys||function(o,k,r){r=[];for(k in o)r.hasOwnProperty.call(o,k)&&r.push(k);return r}

function loadGrid()
{
	$("#act").val("");
	headerObj = eval(headerArrObj);	
	var HeaderLength = Object.keys(headerObj).length;
	statusObj = eval(statusArrObj);	
	priorityObj = eval(priorityArrObj);	
	var colObj = new Object();
	var tempObj1 = new Object();
	var tempObj2 = new Object();
	/*for(hid in headerObj){
		var hidArr = hid.split("_");
		var HeaderID = hidArr[1];
		var ColVal = headerObj[hid]['column_id'];
		colObj[HeaderID]=ColVal;		
		
		if(headerObj[hid]['filter_type']==2){
			LovValueCheck=1;
		}
	}*/
	
	var comp_main_id = $("#comp_main_id").val();
	var section = $("#sectionVal").val();
	var sub_section = $("#sub_sectionVal").val();
	var type = $("#type").val();	
	var aircraft_id = $("#link_id").val();
	var client_id = $("#client_id").val();
	var template_id = $("#template_id").val();
	
	
	//
	
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
		
	//
	var params='';
	params="section="+section+"&sub_section="+sub_section+"&link_id="+aircraft_id+"&comp_main_id="+comp_main_id+"&startLimit="+startLimit+"&type="+type+"&act=GRID&headerObj="+JSON.stringify(colObj);
	params+='&isdelVal='+HideVal+'&LovValueCheck='+JSON.stringify(LovValueCheck)+'&client_id='+client_id+"&template_id="+template_id;
	if($("#inboxmod").val()==1)
			params+="&inboxmod=1&UID="+$("#UID").val();
	if(HeaderLength>0){
	getLoadingCombo(1);	
	$.ajax({url: "airworthiness_centre.php", type:"POST",data:params,success: function(data){
		dataObj = eval("("+data+")");
		autoFilterObj = dataObj.autofiletrVal;
		renderGrid();}});
	} else { 
		$("#divGird").html("<strong>No Records Found.</strong>");
	}
}
function renderGrid(tab_id)
{	
	try{	
	var oldtabid='';
	recidArr = [];	
	var headerCount =Object.keys(MainHeaderObj).length;
	Total =dataObj.totalRows;

	var table ='';
	var mtable ='';
	if(dataObj.totalRows>0){
		
		
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
		tabid=0;
		var srNo = 1;
		
		mtable += '<div  id="maintablewidth1"><table width="100%" cellspacing="1" cellpadding="3" border="0" class="tableContentBG" id="m_tablewidth" ></table></div>';
		mtable +='<table width="100%" cellspacing="1"  style="" cellpadding="3" border="0" class="tableContentBG"  id="maintablewidth">';
		
		mtable +=getHeaderRow();	
		mtable +=getFilterRow(tabid);	
		for(tabid in dataObj.rowData){						
				if(oldtabid!=tabid){					
					var TempTable ='';	
					TempTable =getGridData(tabid);					
					if(TempTable!=''){
						
						table +='<tr id="trTab+'+tabid+'" name="trTab+'+tabid+'" class="tableCotentTopBackground"  ><td   align="left"  colspan="'+(headerCount)+'" style="padding:12px;"><div style="float:left;"  class="divlerybibFont ">'+dataObj['category'][tabid]+'</div><div style="float:right">';
					table +='<input type="hidden" value="1" id="rowid'+tabid+'" name="rowid'+tabid+'">';
					table +='<input type="button" id="aText'+tabid+'" onclick="toggle_visibility('+tabid.replace('_','')+');" value="'+btnTextClose+'" class="button"></td></tr>';	
						table+=TempTable;
					}
				}
				oldtabid=tabid;
			
		}
		
		
		
		if(table=='')
		{
			table += '<tr align="center"><td colspan="'+(headerCount)+'" >';
			table += '<strong>No Records Found.</strong>';
			table += '</td></tr>';
		}
	}
	else
	{
		table += '<div class="add_row" align="center" id="0" style="padding:10px; text-align:center;">';
		table += '<strong>No Records Found.</strong>';
		table += '</div>';
	}
	table =mtable+table+'</table>';
	$("#divGird").html(table);
	if($("#inboxmod").val()==1)		
	{
		$("#total").html(Total);
		$("#ttlnnt_today").html(total_today);
		$("#ttlnnt").html(total_unread);		
	}
	freezePaneOnOff();
	getLoadingCombo(0);
	
	} catch(e) {
		alert(e);
	}
}

function getHeaderRow()
{
	var tempHeaderTable='';
	tempHeaderTable+='<tr id="h_row2" class="tableWhiteBackground setinternalwidth">';
	
	for(hid in MainHeaderObj){
		if(MainHeaderObj[hid]['column_id']!='itemid')
			tempHeaderTable+='<td align="left" class="tableCotentTopBackgroundNew">'+MainHeaderObj[hid]['field_name']+'</td>';		
		else
			tempHeaderTable+='<td align="left" class="tableCotentTopBackgroundNew" style="width:40px;">'+MainHeaderObj[hid]['field_name']+'</td>';
	}
	
	return tempHeaderTable+='</tr>';
}

function getFilterRow(tab_id)
{
	var tempFilterTable='';
	tempFilterTable+='<tr id="h_row3" >';	
	tab_id='';
	for(fid in MainHeaderObj){
		
		var colVal = '';
		var ColID = MainHeaderObj[fid]['column_id'];
		
		
		if(filterValObj[ColID]){
			colVal = filterValObj[ColID];
		}
		
		tempFilterTable+='<td align="left" class="tableCotentTopBackgrounddark" nowrap>'+getObjElement('',fid,colVal)+'</td>';
	}
	
	return tempFilterTable+='</tr>';	
}

function getGridData(tabid)
{
	var tempGridTable ='';
	total_today=0;
	total_unread=0;
	var headerCount =Object.keys(MainHeaderObj).length;
	if(dataObj.totalRows>0){
		var srNo = 1;
			for(row in dataObj.rowData[tabid])	{
				tempGridTable+=getRows(srNo,tabid,row);
				srNo++;
			}
		}
	/*if(tempGridTable==''){
		tempGridTable += '<tr class="add_row" align="left" id="0" onmouseover="this.style.backgroundColor=\'#CCFFCC\';"';
		tempGridTable += ' onmouseout="this.style.backgroundColor=\'#EEEEEE\';">';
		tempGridTable += '<td colspan="'+(headerCount)+'" align="center"><strong>No Records to Display..</strong></td>';
		tempGridTable += '</tr>';
	}*/	
	return tempGridTable;
}
function edit(rid,elm)
{	
try{
	if(rid){
	$("#currentID").val(rid);		
	} else {
		$("#currentID").val("");		
	}
	
	if($("#act").val()=="EDIT" && currentID!=rid){
		if(confirm("Do you want to save your changes?")){
			
			var tempVar=currentID.split('_');
			
			var tab_id="_"+tempVar[1];
			var rec_id="_"+tempVar[2];
			
			if(!saveRow(tab_id,rec_id))
			return false;
			
			
		} else {
			
			doRow("unsetRow");
			validateNotes(0,tab_id,rec_id);
		}
	}	
	if(currentID!=""){
		if($("#TR"+currentID)){
			$("#TR"+currentID).css("backgroundColor","");
		}
	}
	$("#TR"+rid).css({"backgroundColor":"#FFCC99","textAlign":"left","cursor":"pointer"});
	currentID= rid;	
	} catch(e){
		alert(e);
	}	
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
	
	if(dataObj.NotesUser[userId])
	return dataObj.NotesUser[userId]['CompanyName'];
	else 
	return '';
}
function getNotesIcon(tab_id,rec_idVal)
{
	var tempTable = '<div class="chat_maintenace_disable" style="float:left" title="No Comments for this Row"></div><span id="userTD'+tab_id+rec_idVal+'"><div class="select_user_dis" style="float:left" title="Enter Notes to enable"></div></span>'; 
		var rec_id = rec_idVal.replace("_",""); 		
		if(dataObj.Notes && dataObj.Notes[rec_id] && Object.keys(dataObj.Notes[rec_id]).length>0)
		{
			var tempTable = '<div class="chat_maintenace" style="float:left" title="View Comments" onClick="open_List_comment(\''+rec_id+'\')"></div><span id="userTD'+tab_id+rec_idVal+'"><div class="select_user_dis" style="float:left" title="Enter Notes to enable"></div></span>'; 
		}
		else
		{
			var tempTable = '<div class="chat_maintenace_disable" style="float:left" title="No Comments for this Row"></div><span id="userTD'+tab_id+rec_idVal+'"><div class="select_user_dis" style="float:left" title="Enter Notes to enable"></div></span>'; 	
		} 
	
	return tempTable;
}

function getRespCombo(tab_id,rec_id,columnId)
{
	var res_users='';
	var ResObj=new Object();
	var str='';
	
	/*if(tab_id!='')
	{
		res_users=dataObj.rowData[tab_id][rec_id]['res_users'];
	}
	else
	{
		var coma = '';
		for(tbId in dataObj.rowData)
		{
			for(rcId in respUserObj[tbId])
			{
				if(dataObj.rowData[tbId][rcId]['res_users']!=null && dataObj.rowData[tbId][rcId]['Header_Row']!='1')
				{
					res_users+=coma+dataObj.rowData[tbId][rcId]['res_users'];
					coma=',';
				}
			}
		}
	}*/
	for(tbId in dataObj.rowData){
		for(rcId in dataObj.rowData[tbId]){
		var recidVal = rcId.replace("_","");		
		if(dataObj.rowData[tbId][rcId]['Header_Row']==0){			
			if(dataObj.Notes['resp_user'][recidVal] && dataObj.Notes['resp_user'][recidVal]!=""){		
				for(z  in dataObj.Notes['resp_user'][recidVal]){
					var userId= dataObj.Notes['resp_user'][recidVal][z];			
					respUserObj[userId]=userId;
				}
				}
				
			}
		}
	}
	
	if(!$.isEmptyObject(respUserObj) && respUserObj!=null)
	{
		var ResArr=res_users.split(',');
		for(y in respUserObj)
		{
			//if(ResArr[i])
			//{
				//var userId=ResArr[i];
				/*if(dataObj.NotesUser && dataObj.NotesUser['_'+userId])
				{*/
					var resUserLevel=getUserlevel(y);
					if(!ResObj[resUserLevel])
						ResObj[resUserLevel]=new Object();
					ResObj[resUserLevel][y]=y;
				//}
			//}
		}
	}
	
	var selectRespo = '';
	if(document.getElementById("fd_filter_"+columnId) && (document.getElementById("fd_filter_"+columnId).value!='' || document.getElementById("fd_filter_"+columnId).value!=0))
	{
		selectRespo = document.getElementById("fd_filter_"+columnId).value;
	}
	str +='<select class="selectauto" style="overflow:scroll;" id="fd_filter_'+columnId+'" name="fd_filter_'+columnId+'" onChange="filterGrid(event,\''+tab_id+'\',1);" >';
	str +='<option value="">-Select-</option>';
	
	var innerStr = '';
	
	for(var level in ResObj)
	{
		if(level==5)
		{
			if(allowClientNote == '0')
				continue;
			var resUserLevel=2;
			var backStyle = ' style="background:#FFE5F6;" ';
		}
		else if(level==1)
		{
			var resUserLevel=1;
			var backStyle = ' style="background:#DED7FD;" ';
		}
		else
		{
			var resUserLevel=0;
			var backStyle = ' style="background:#DDFFFE;" ';
		}
		
		var userStr='';
		
		innerStr+='<optgroup label="'+getUserType(level)+' User" '+backStyle+'>';
		
		for(var users in ResObj[level])
		{
			if(users==selectRespo)
			{
				userStr+='<option value="'+users+'" selected>'+getUserFullname(users)+'</option>';
			}
			else
			{
				userStr+='<option value="'+users+'">'+getUserFullname(users)+'</option>';
			}
			
		}
		innerStr +=userStr+'</optgroup>';
	}
	
	if(innerStr=='')		// In case of no users in responsible combo.
	{
		innerStr += '<optgroup label="Client User" style="background:#FFE5F6;"  >';
		innerStr += '<optgroup label="Main Client User" style="background:#DED7FD;">';
		innerStr += '<optgroup label="FLYdocs User"  style="background:#DDFFFE;">';
	}
	return str+=innerStr+'</select>';
}

function getResUsers(tab_id,rec_id)
{
	rec_id=rec_id.replace('_','');
	if(!$.isEmptyObject(dataObj.Notes['resp_user'])){
		
		var res_users=dataObj.Notes['resp_user'][rec_id];	
	} else {
		
		res_users=new Object();
	}
	
	
	var ResObj=new Object();
	var str='';
	
	if(res_users !='' && res_users!=null)
	{
		
		
		for(userId in res_users)
		{	
			var userId=res_users[userId]
			
			
			//respUserObj[userId]=userId;
			//if(dataObj.NotesUser && dataObj.NotesUser['_'+userId])
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
				
				userStr+=(userStr=='') ? getUserFullname(users) : ","+getUserFullname(users);
			}
			
			str +=userStr+'</div>';	
		}
		 str +='</div>';	
		return str;
	}
	
	return str;
}
function getUserType(userLevel)
{
	//user_id = user_id.replace('_','');
	var level=userLevel;//dataObj.NotesUser['_'+user_id]['level'];
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
	if(dataObj.NotesUser[user_id])	
	return dataObj.NotesUser[user_id]['UserLevel'];
	else 
	return  '';
}
function getUserFullname(user_id)
{
	if(dataObj.NotesUser[user_id])	
	return dataObj.NotesUser[user_id]['username'];
	else
	return "";
}
function getNotes(tab_id,rec_id)
{
	rec_id=rec_id.replace('_','')
	
	if(dataObj.Notes && dataObj.Notes[rec_id])
	{
		var notesObj=dataObj.Notes[rec_id];
		var str='';
		if(notesObj)
		{	str +='<div style="overflow:auto; max-height:150px;z-index:-1;width:250px;">';
			for(Notes in notesObj)
			{
					
				if(notesObj[Notes]['notes'])
				{
					var UserLevel= getUserlevel(notesObj[Notes]['sender']);
					var Noteclass=getNoteClass(UserLevel);
					var NoteHeader=getNotesHeader(notesObj[Notes]['sender']);
					
					str+='<div class="'+Noteclass+'" >';
					str+='<strong>'+NoteHeader+' Comments Added By '+getUserFullname(notesObj[Notes]['sender'])+' On '+notesObj[Notes]['date']+' </strong>';
					str+='<br>';
					str+='<span id="note'+Notes+'">'+notesObj[Notes]['notes'].replace(/\n/gi, "<br>")+'</span>';
					str+='</div>';
					
				}
				
			}
			str+='</div>';
		}
		return str;
	}
	else
	return '<div style="overflow:auto; max-height:150px;z-index:-1;width:250px;"></div>';
	
	
}
function getPrivateNoteFlag(tab_id,rec_id)
{
	rec_id=rec_id.replace('_','')
	if(dataObj.Notes && dataObj.Notes[rec_id])
	{
		var notesObj=dataObj.Notes[rec_id];
		var str='';
		var private_note_flag=0;
		if(notesObj)
		{	
			for(Notes in notesObj)
			{				
				if(notesObj[Notes]['notes'])
				{					
					private_note_flag=notesObj[Notes]['private_note_flag'];
				}				
			}

		}
		if(private_note_flag==0)
			return 'No';
		else
			return 'Yes';

	}
}
function getInboxNotes(tab_id,rec_id)
{
	rec_id=rec_id.replace('_','')
	
	
	if(dataObj.Notes && dataObj.Notes[rec_id])
	{
		var notesObj=dataObj.Notes[rec_id];
		var tempTable='';
		if(notesObj)
		{	tempTable +='<div style="overflow:auto; max-height:150px;z-index:-1;width:250px;">';
			for(Notes in notesObj)
			{				
				if(notesObj[Notes]['notes'] && notesObj[Notes]['view_type']==0)
				{					
					var date=notesObj[Notes]['inboxDate'];
					var view_flag=notesObj[Notes]['view_flag'];
					var private_note_flag=notesObj[Notes]['private_note_flag'];
					
					var src="todaymsg_ico_red";
					var curDate= new Date();
					var ndate=date.split(" ");	
					var n=ndate[0].split("-");
					var newdate=n[2]+"-"+n[1]+"-"+n[0];
					var NextDate= new Date(n[2],n[1]-1,n[0]);
					var a = new Date(curDate).getTime();
     				var b = new Date(NextDate).getTime();
					if(b<=a && view_flag==0)
					{
						src="todaymsg_ico_green";
						total_today++;
						total_unread++;
					}
					else if(view_flag==0)
					{
						src="todaymsg_ico_red";
						total_unread++;
					}
					else
					{
						src="todaymsg_ico_gray";
						total_unread++;
					}
					
					tempTable += '<table width="100%" border="0" cellspacing="2" cellpadding="2">';
					tempTable += '<tr><td valign="top" align="left" width="2%">';
					var notesID=Notes.split("_")[1];
					tempTable += '<div  id="icon_'+notesID+'" class='+src+' onMouseOver="showDiv2('+notesID+',1,event,'+notesID+')" onMouseOut="showDiv2('+notesID+',0,event)"></div>';
					   
					tempTable += '<div id="view_recc'+notesID+'" style="display:none;  margin-top:-20px; *margin-top:-8px;" onMouseOver="showDiv2('+notesID+',1,event,'+notesID+')" onMouseOut="showDiv2('+notesID+',0,event)">';
					tempTable += '<table><tr>';
							
					tempTable += '<td><strong>'+getUserFullname(notesObj[Notes]['sender'])+'</strong> ['+date+'] <strong>: </strong> '+notesObj[Notes]['notes']+'</td>';
					tempTable += ' </tr> </table></div> </td> <td align="left">';

					tempTable += date+'('+getUserFullname(notesObj[Notes]['sender'])+')';
					tempTable +='</td></tr></table>';
					
				}
				
			}
			tempTable+='</div>';
		}
		return tempTable;
	}
	else
	return '<div style="overflow:auto; max-height:150px;z-index:-1;width:250px;"></div>';
	
	
}
function isReadonly(tab_id,rid)
{
	
	var robj =dataObj.rowData[tab_id][rid];
	var StatusVal = robj['work_status'];
	if(statusObj["_"+StatusVal] && statusObj["_"+StatusVal]['readOnly'])
	{
		return statusObj["_"+StatusVal]['readOnly'];;
	}
	return 0;
}
function getRows(srNo,tab_id,rid)
{
	var temprowTable = '';
	
	var robj=new Object();
	if($.isEmptyObject(dataObj.NotesUser))
	{
		dataObj.NotesUser=new Object();
	}
	if($.isEmptyObject(dataObj.Notes["resp_user"]))
	{
		dataObj.Notes["resp_user"]=new Object();
	}
	if($.isEmptyObject(dataObj.Notes["users"]))
	{
		dataObj.Notes["users"]=new Object();
	}
	robj[rid]=dataObj.rowData[tab_id][rid];
	for(rid in robj){
		
		var RowIDArr= tab_id+rid;
		var RowID = tab_id+rid;
		var  deleteCellVal;
		robj[rid]['delete_cell_flag']=  deleteCellVal =( robj[rid]['delete_cell_flag']== null ) ?' ':robj[rid]['delete_cell_flag'];
		
		var cat_name=  robj[rid]['cat_name'];
		var deleteCellarr = deleteCellVal.split(",");
		
		var rClass = (robj[rid]['delete_flag']==1)? "strikeLine " : '';
		var cl="tableContentBG";
		var strBack='';
		var groupStr='';
		var parentArr=new Object();
			
		
		
		if(robj[rid]['is_readonly']==1)
		{
			rClass="readonlyHeader "+rClass;
			
		}
		
		if(readOnlyGrid==1)
			rClass="readonlyHeader "+rClass;
		
		
		
		
		if(robj[rid]['Header_Row']==1)
		{
				rClass+="Header ";
				strBack = ' style="background-color:#acf5fb;"';
		}
		else
		{
			
			groupStr='rel="group_'+robj[rid]['category_id']+'_'+robj[rid]['PrntId']+'"';
			
			if(robj[rid]['PrntId']!=0)
			rClass+="subHeader ";  
		}
		var class1 = (srNo%2==0)?" even":" odd";
		var Readonly=isReadonly(tab_id,rid);
		if(Readonly==1)
		{
			rClass="readonly";
		}
		if($("#inboxmod").val()==1)
		{
			rClass="Inbox";
		}
		
		rClass=rClass+class1;
		temprowTable += '<tr class="'+rClass+'" id="TR'+RowID+'"  '+groupStr+' onMouseDown="edit(\''+RowID+'\',this);">';
		
		var flg=1;
		
		
		for(fid in MainHeaderObj){
			
			
			var ColID = MainHeaderObj[fid]['column_id'];
			var colType = MainHeaderObj[fid]['filter_type'];
			
			var ColVal ='';
			
			if(ColID && ColID!=null && ColID!='')
			var ColVal = robj[rid][ColID];
					
			if(ColID)
			{
				if(filterValObj && filterValObj[ColID])
				{
					var searchStr=String(ColVal).toLowerCase();
					
					if(ColID=='responsibility')
					{
						
						var recVal = rid.replace("_","");
						var tempflg=0;
						if(dataObj.Notes['resp_user'][recVal] && dataObj.Notes['resp_user'][recVal]!=""){
							for(var z  in dataObj.Notes['resp_user'][recVal]){
								var tempVal =dataObj.Notes['resp_user'][recVal][z];
								var tempuserVal = filterValObj[ColID];
								if(tempuserVal==tempVal){
									flg=1;
									tempflg=0;
									break;
								} else {
									tempflg=1;
								}						
							}
							
							if(tempflg==1){
								return '';
							}
						} else {
							return '';
						}
					}
					else {
						if(ColVal==null ||  ColVal=='')
							return '';
						
						if(colType==2 || colType=='status')
						{
							if(filterValObj[ColID].toLowerCase()!=searchStr)
							{
								flg=0;
								return '';
							}
						}
						else if(searchStr.indexOf(filterValObj[ColID].toLowerCase()) < 0 && filterValObj[ColID]!='' )
						{
							flg=0;
							return '';
						}
						
						if((colType=='status' || colType=='priority') && robj[rid]['Header_Row']==1)
						{
							flg=0;
							return '';
						}
					}
				}
			}
			
			if(flg==1)
			{
				
				if($.inArray(ColID,deleteCellarr)>=0){

					/*
					if(class2!=class1){
						var class3 = '';
					} else {
						var class3 = 'blockbg';
					}
					*/
					
					var class3 = class1;
					
					temprowTable += '<td id="cell_'+ColID+'" class="'+class3+'" onmouseDown="setCellValue(\''+ColID+'\');"></td>';
					
				}else{	
					var pad='';
					var tdId = '';
					
					if(ColID=='itemid')tdId = ' id = "srTd_'+srNo+'" ';
					
					if(ColID=='itemid' && robj[rid]['Header_Row']==0 && robj[rid]['PrntId']!=0)
					{
						pad ='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					}
					else if(ColID=='itemid' && robj[rid]['Header_Row']==1)
					{
						
						if(dataObj.countChild[robj[rid]['id']]>0)
						{
							
							pad='<img  id="img_'+robj[rid]['category_id']+'_'+robj[rid]['id']+'" src="'+pathLink+'images/minus_active.jpg" onclick="show_hide(\''+robj[rid]['category_id']+'\',\''+robj[rid]['id']+'\')" >&nbsp;';
					
					
						}
						else 
						{
							pad='<img  id="img_'+robj[rid]['tab_id']+'_'+robj[rid]['id']+'" src="'+pathLink+'images/plus_inactive.jpg"  >&nbsp;';
						}
								
							
					}
					
					temprowTable += '<td onmouseDown="setCellValue(\''+ColID+'\');" '+tdId+'>'+pad+getObjElement(tab_id,fid,ColVal,rid);+'</td>';
				}
			}
		}
		
		if(flg==1)
		{
			/*changes goto next previous row*/				
			var recTabid_page = tab_id.replace("_","");
			var recid_page = rid.replace("_","");
			var valRec = recid_page+'-'+recTabid_page;
			if($.inArray(valRec,recidArr)<0){						
			if(dataObj.rowData[tab_id][rid]['Header_Row']==0 && dataObj.rowData[tab_id][rid]['hyperlink_value']==0){
				recidArr[recidArr.length] =valRec;
				}
			}
			/*changes End goto next previous row*/
		}
		temprowTable += '</tr>';
	}
	return temprowTable;
}
function activeForNote(Obj,tab_id,rec_id)
{
	var id=Obj.id;
	var id=id.replace('notes','');	
	var link_id=document.getElementById('link_id').value
	var Type=document.getElementById('type').value
	if(Obj.value!='')
	{
		document.getElementById('userTD'+id).innerHTML='<div class="select_user_act" onclick="ShowModalForNotesUsers();" style="float:left" title="Enter Notes to enable"></div>';
	}
	else
	{
		document.getElementById('userTD'+id).innerHTML='<div class="select_user_dis" style="float:left" title="Enter Notes to enable"></div>';
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
		var is_readonly= dataObj.rowData[tab_id][rec_id]['is_readonly'];
		if(dataObj.rowData[tab_id][rec_id]['Header_Row']==1)
		var is_readonly=1;
		
		if(readOnlyGrid==1)
		var is_readonly=1;
	
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
	else if(filterType=="view_icon"){

		return getViewIcon(tab_id,mainRecID)
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
	else if(filterType=="privatenote" ){
		
		if(mainRecID)
		{
			return getPrivateNoteFlag(tab_id,mainRecID)
		}
	}
	else if(filterType=="notesdetails" ){
		
		if(mainRecID)
		{			
			return getInboxNotes(tab_id,mainRecID)
		}
	}
	else if(filterType=="ManageIssues" && is_readonly==0){
		
		if(mainRecID)
			return getNotesIcon(tab_id,mainRecID)
	}
	else if(filterType=="responsibility" && is_readonly==0){
		
		if(mainRecID)
			return getResUsers(tab_id,mainRecID);
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
			var tab_idVal =tab_id.replace("_","");
			var rec_idVal =rec_id.replace("_","");
			addStr+=' onChange="setSaveAllValue(\''+ColIdName+'\','+tab_idVal+','+rec_idVal+',this);" ';
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
		
		if($("#act").val()=="EDIT"){
			if(is_multiple==1)
				multipleSel = ' multiple '
		}
		
		tempElement+='<select  class="selectauto" style="overflow:scroll;" '+addFun+multipleSel+' id="'+IdVal+'" name="'+IdVal+'">';
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
		tempElement+='<input type="text" class="date_input" id="'+IdVal+'" value="'+selVal+'" '+addFun+'  tabindex="10" onkeydown="return filter(this.id,event);" readonly="readonly"/>';
		tempElement+='<img border="0" onclick="displayCalendar(document.getElementById(\''+IdVal+'\'),\'dd-mm-yyyy\',this);return false;" ';
		tempElement+=' style="width:18px;height:17px; alignment-adjust:middle; border:0px solid transparent; cursor:pointer;" src="'+pathLink+'images/Calender.gif" alt="Date"> ';
	} 
	return tempElement;	
}
function getViewIcon(tab_id,RowID)
{
	var iconStr='';
	if($("#act").val()=="EDIT"){
		var RowIDArr = RowID.split("_");
		var tab_idVal= tab_id.replace("_","");
		var RowIDVal= RowID.replace("_","");
		iconStr += '<a onclick="return saveRow(\''+tab_id+'\',\''+RowID+'\');" href="javascript:void(0)"><img border="0" title="Save" src="'+pathLink+'images/tickmark.png"></a>';
	} else {
		if(RowID!=0){
			
			if(dataObj.rowData[tab_id] && dataObj.rowData[tab_id][RowID] && dataObj.rowData[tab_id][RowID]['Header_Row'] && dataObj.rowData[tab_id][RowID]['Header_Row']==1){				return '';				}
			
			var iconClass='view_active_icon_disable';
			var ffllaagg = 0;
			if(dataObj.Links && dataObj.Links[tab_id])
			{
				if(dataObj.Links[tab_id][RowID])
				{
					var tipfun ='';
					var tip_txt =dataObj.Links[tab_id][RowID]['tiptext'];
					if(tip_txt!='' && tip_txt!=null)
					{
						var tipfun ="onmouseover='Tip(\""+tip_txt+"\");'  onmouseout='UnTip();'";
					}
					ffllaagg=1;
					iconClass='view_active_icon';
				}
			}
			
			if(ffllaagg==0)
			{
				if(dataObj.Docs && dataObj.Docs[RowID]>0)
				{
				 iconClass='view_active_icon';
				}
			}
		
			iconStr += '<a onclick="openViewArea(\''+tab_id+'\',\''+RowID+'\')" href="javascript:void(0)"><div '+tipfun+' style="float:left" class="'+iconClass+'"></div></a>';
			
		}
	}
	return iconStr;
}

function filterGrid(e,tab_id)
{
	var searchFlag=0;
	if(arguments[2])
	{
		searchFlag=1;
	}
	else if(e.keyCode==13){
		searchFlag=1;	
	}
	
	if(searchFlag==1)
	{
		$("#act").val("");
		var colObj = new Object();
		for(hid in MainHeaderObj){
			var hidArr = hid.split("_");
			var HeaderID = hidArr[1];
			var ColVal = MainHeaderObj[hid]['column_id'];
			
			if(ColVal!=null && ColVal)
			{
				filterValObj[ColVal]=($("#fd_filter_"+ColVal).length>0 && $("#fd_filter_"+ColVal).val()!='')?$("#fd_filter_"+ColVal).val():"";	
			}
			
			if(MainHeaderObj[hid]['filter_type']==2){
				//LovValueCheck=1;
			}
		}
		renderGrid(tab_id);
	}
	
}
function manageSubMenuOut_C()
{
	$("#manageSubMenu_C").css("display", "none");
}

function manageSubMenuHOver_C()
{
	$("#manageSubMenu_C").css("position", "absolute");
	$("#manageSubMenu_C").css("display", "block");
}

function openStatuslist()
{
	var tab_id = $("#tab_id").val();
	var type = $("#type").val();
	var client_id = $("#client_id").val();	
	/*var compMatrixHeader = window.open('manage_rows.php?section=header&tab_id='+tab_id+'&type='+type+'&SectionFlag='+type+'&client_id='+client_id,
						   'compMatrixHeader','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	compMatrixHeader.focus();*/
}
function openWorkStatuslist()
{
	var tab_id = $("#tab_id").val();
	var type = $("#type").val();
	var client_id = $("#client_id").val();	
	/*var compMatrixStatus = window.open('manage_rows.php?section=status&tab_id='+tab_id+'&type='+type+'&SectionFlag='+type+'&client_id='+client_id,
							'compMatrixStatus','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes')
	compMatrixStatus.focus();*/
}

function fnAddrow(flg,upFlg)
{
	var client_id = $("#client_id").val();
	var type = $("#type").val();
	var comp_id = $("#comp_id").val();
	var section = $("#sectionVal").val();
	var sub_section = $("#sub_sectionVal").val();
	var comp_main_id = $("#comp_main_id").val();
	var tabRecVal =  currentID.split("_");
	var rec_id= tabRecVal[2];
	var addStr='';
	if(upFlg && upFlg!=''){
		addStr='&subFlg='+upFlg;
	}
	
	var comp_main_id = $("#comp_main_id").val();	
var ReviewAddRow = window.open('airworthiness_centre.php?section='+section+'&sub_section='+sub_section+'&add_section=add_row&&rec_id='+rec_id+'&pos='+flg+addStr
								,'ReviewAddRow','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes')
	ReviewAddRow.focus();
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
		var priorityVal=row_priority=dataObj.rowData[tab_id][rid]['priority'];		
		var tab_idVal = tab_id.replace("_","");
		var rec_idVal = rid.replace("_","");
		var addfun='onchange="setSaveAllValue(\'priority\','+tab_idVal+','+rec_idVal+',this)"';
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
			StatusVal =dataObj.rowData[tab_id][rid]['work_status'];
		
		var st='';
		
		if(StatusVal && statusObj["_"+StatusVal])
		{
			st='style="background-color:'+statusObj["_"+StatusVal]['bg_color']+';color:'+statusObj["_"+StatusVal]['font_color']+';"';
		}
		else if(StatusVal==0 && statusObj["_"+StatusVal])	// when doing "Deactivate Read Only"
		{
			st='style="background-color:'+statusObj["_"+StatusVal]['bg_color']+';color:'+statusObj["_"+StatusVal]['font_color']+';"';
		}
		var tab_idVal = tab_id.replace("_","");
		var rec_idVal = rid.replace("_","");
		var addfun='onchange="setSaveAllValue(\'work_status\','+tab_idVal+','+rec_idVal+',this)"';
		statusLovStr='<select id="statusLov'+tab_id+rid+'" name="statusLov'+tab_id+rid+'" '+st+' class="selectauto" '+addfun+' >';		
	} else {
		StatusVal = filterValObj['work_status'];
		statusLovStr='<select id="fd_filter'+tab_id+'_work_status" name="statusLov" onChange="filterGrid(event,\''+tab_id+'\',1);" class="selectauto">';
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
		
		
		statusLovStr+='<option '+sel+' style="background-color:'+statusObj[st_id]['bg_color']+';color:'+statusObj[st_id]['font_color']+'" value="'+StID+'" '+disable+'>'+statusObj[st_id]['name']+'</option>';	
	}	
	statusLovStr+='</select>';
	return statusLovStr;
	}catch(e){
		alert(e);
	}
}
function doRow(act,tab_id,rec_id)
{
	if(!tab_id)
	{
		if(act=="EditRow"){
			$("#act").val("EDIT");		
			var EditID = $("#currentID").val();		
		}else{
			$("#act").val("");
			var EditID = currentID;	
		}
	}
	
	if(tab_id)
	{
		var EditID=tab_id+rec_id;
	}

	var tempVar=EditID.split('_');
	
	var tempObj=new Object();
	
	var SrNo_ = $("#TR"+EditID).find("td:first").attr('id');
	if(SrNo_!=''){
		var SrNoArr = SrNo_.split('_');
		var SrNo = SrNoArr[SrNoArr.length-1];
	}
	else
	{
		var SrNo =1;
	}
	
	var tab_id="_"+tempVar[1];
	var rec_id="_"+tempVar[2];
	var editStr=getRows(SrNo,tab_id,rec_id);
	
	$("#TR"+EditID).replaceWith(editStr);		
	if($("#currentID").val()==EditID)
	$("#TR"+EditID).css({"backgroundColor":"#FFCC99","textAlign":"left","cursor":"pointer"});		
	
	freezePaneOnOff();
}

function saveRow(tab_id,rec_id)
{
	var tempHidden=document.getElementById('tempHidden').value;
	if(tempHidden!='')
	NotesObj=eval('('+tempHidden+')');
	
	var saveObj=new Object();
	var saveObjEdit=new Object();
	var rec_idVal =rec_id.replace("_","");
	var tab_idVal =tab_id.replace("_","");
	saveObjEdit['columnVal'] = new Object();
	var ai = 0;
	var auditObj = new Object();
	for(fid in MainHeaderObj){
		var old_val ="";
		var ColID = MainHeaderObj[fid]['column_id'];
		var filter_type = MainHeaderObj[fid]['filter_type'];
		var filter_auto =  MainHeaderObj[fid]['filter_auto'];
		var Header_Row = dataObj.rowData[tab_id][rec_id]['Header_Row'];		
		if((filter_type=='checkbox' || filter_type=='status' || filter_type=='priority'  || filter_type=='notes') && Header_Row==0 && ColID!='itemid')
		{
				var ColID1=ColID;
				
				if(ColID=="work_status")
					var ColID1="statusLov";
				
				else if(ColID=='priority')
					var ColID1='priorityLov';
				
				else if(ColID=='notes')
					var ColID1='notes';
				
				var IdVal1 =ColID1+tab_id+rec_id;
				
				var ColVal = $("#"+IdVal1).val();
				
				if(ColVal!=dataObj.rowData[tab_id][rec_id][ColID] && filter_type!='checkbox' &&  filter_type!='notes' && ColID!='itemid')
				{
					if(!saveObjEdit['columnVal'][ColID])
						saveObjEdit['columnVal'][ColID]='';
					
					if(!ColVal)
						ColVal=0;
					oldVal = "";
					if(ColID=="work_status"){
						var oldStatus = dataObj.rowData[tab_id][rec_id]['work_status'];
						 if(statusObj["_"+oldStatus]) {
							oldVal =  statusObj["_"+oldStatus]['name']+','+statusObj["_"+oldStatus]['bg_color'];
						 }
						 var newStatus = ColVal;
						 if(statusObj["_"+newStatus]) {
							newVal =  statusObj["_"+newStatus]['name']+','+statusObj["_"+newStatus]['bg_color'];
						 }
					}
					
					if(ColID=="priority"){
						var oldPriority = dataObj.rowData[tab_id][rec_id]['priority'];
						 if(priorityObj["_"+oldPriority]) {
							oldVal =  priorityObj["_"+oldPriority]['name']+','+priorityObj["_"+oldPriority]['bg_color'];
						 }
						 var newPriority = ColVal;
						 if(priorityObj["_"+newStatus]) {
							newVal =  priorityObj["_"+newPriority]['name']+','+priorityObj["_"+newPriority]['bg_color'];
						 }
					}
					
					saveObjEdit['columnVal'][ColID]= ColVal;
					
					auditObj[ai] = getAuditObj();
					auditObj[ai]['field_title']	=MainHeaderObj[fid]['field_name'];
					auditObj[ai]['old_value'] = oldVal;
					auditObj[ai]['new_value'] = newVal;		
					auditObj[ai]['action_id'] = 14;
					auditObj[ai]['main_id'] = rec_idVal;
					ai++;
					
				}
				else if(filter_type=='checkbox')
				{
					var ColVal= (document.getElementById(IdVal1).checked==true) ? 1 :0;
					if(ColVal!=dataObj.rowData[tab_id][rec_id][ColID])
					{
						
						var oldValue= dataObj.rowData[tab_id][rec_id][ColID];
						var oldVal = (oldValue==1)?"Yes" :"No";
						var newVal = (ColVal==1)?"Yes" :"No";
						if(!saveObjEdit['columnVal'][ColID1])
							saveObjEdit[ColID1]='';
						
						auditObj[ai] = getAuditObj();
						auditObj[ai]['field_title']	=MainHeaderObj[fid]['field_name'];
						auditObj[ai]['old_value'] = oldVal;
						auditObj[ai]['new_value'] = newVal;		
						auditObj[ai]['action_id'] = 14;
						auditObj[ai]['main_id'] = rec_idVal;
						ai++;
						
						saveObjEdit['columnVal'][ColID]= ColVal ;
					}
				}
				else if(filter_type=='notes')
				{
					try
					{
						if(!validateNotes(1,tab_id,rec_id))
						{
							
							return false;
						}
						
						var noteText ="";
						if(document.getElementById("notes"+tab_id+rec_id+""))
							noteText  = document.getElementById("notes"+tab_id+rec_id+"").value;
						
						if(Object.keys(NotesObj).length>0 && document.getElementById('notes'+tab_id+rec_id).value!='')
						{
						
							NotesObj['notesdata']['notes']=noteText;
							NotesObj['notesdata']['comp_main_id']=$("#comp_main_id").val();
							NotesObj['notesdata']['component_id']=$("#comp_id").val();
							NotesObj['notesdata']['client_id']=$("#client_id").val();
							NotesObj['notesdata']['notes_type']=user_level;
							
							NotesObj['AuditObj']= getAuditObj();
							NotesObj['AuditObj']['new_value']=noteText;
							NotesObj['AuditObj']['field_title']=commentType;
							NotesObj['AuditObj']['action_id']=36;
							var mainIdval = rec_id.replace("_","");
							NotesObj['AuditObj']['main_id']=mainIdval;
							var recIdval = dataObj.rowData[tab_id][rec_id]['rec_id'];
							NotesObj['AuditObj']['rec_id']=recIdval;
														
							ColID1='notes';
							saveObjEdit[ColID1]=new Object();
							saveObjEdit[ColID1]=NotesObj ;
						}
						
					}
					catch(e)
					{
						alert(e);
					}
				}
				
		}
		else if((filter_type!='checkbox' && filter_type!='status' && filter_type!='priority'  && filter_type!='notes' && ColID!='itemid'))
		{
			if(ColID && ColID!='')
			{
				var idstr="editfield"+tab_id+"_"+ColID
				var currentColVal = $("#"+idstr).val();
				
				if(filter_type==2 && filter_auto!=1)
				{
					currentColVal ="";
					var sep = "";
					var selObj = document.getElementById(idstr);
					for (var i=0; i<selObj.options.length; i++) {
						if (selObj.options[i].selected && selObj.options[i].value!='') {
							currentColVal += sep+selObj.options[i].value;
							sep = ",";
						}
					}
				} else if(filter_type==2 && filter_auto==1) {
					var ids = idstr.replace("editfield", "lov");
					var strIds = "editfield"+tab_id+"_"+ColID;
					
					if($("#"+ids).val()!="Enter Text")
					{
						currentColVal = $("#"+ids).val();
					} else if($("#"+strIds)) {
						currentColVal = $("#"+strIds).val();
					}
				}
				
				if(currentColVal!="Enter Text"  && $.trim(dataObj.rowData[tab_id][rec_id][ColID])!=$.trim(currentColVal))
				{
					var oldValue= '';
					if(dataObj.rowData[tab_id][rec_id][ColID]!="" && dataObj.rowData[tab_id][rec_id][ColID]!=null)
					var oldValue= dataObj.rowData[tab_id][rec_id][ColID];
					
					var oldVal = oldValue;
					var newVal = currentColVal;
					
					auditObj[ai] = getAuditObj();
					auditObj[ai]['field_title']	=MainHeaderObj[fid]['field_name'];
					auditObj[ai]['old_value'] = oldVal;
					auditObj[ai]['new_value'] = newVal;		
					auditObj[ai]['action_id'] = 14;
					auditObj[ai]['main_id'] = rec_idVal;
					ai++;
					
					saveObjEdit['columnVal'][ColID]=currentColVal;
				}
			}
		}
	}
	
	saveObjEdit["whrUpdate"] = {"id":rec_idVal};
	saveObjEdit["cat_id"] = tab_idVal;
	$("#act").val("");	
	getLoadingCombo(1);	
	if(!$.isEmptyObject(saveObjEdit['columnVal']) || !$.isEmptyObject(saveObjEdit['notes'])){		
		xajax_Update(saveObjEdit,auditObj);
	} else{
		alert("Record Updated Successfully.");
		doRow("",tab_id,rec_id);
		getLoadingCombo(0);
		
	}
	return true;
}


function updateRow(upObj)
{	
	try{
	
		document.getElementById('tempHidden').value='';
		validateNotes(0);
		if(Object.keys(upObj).length>0)
		{
			var rec_id=upObj["whrId"];
			var upID= '_'+upObj["whrId"];
			var tab_id= '_'+upObj["cat_id"];
			
			for(col_id in upObj['columnVal']){		
				var colVal = upObj['columnVal'][col_id];
				dataObj.rowData[tab_id][upID][col_id] = colVal;
			}
			if(!$.isEmptyObject(upObj['Notes']) && Object.keys(upObj['Notes']).length>0 )
			{
				if($.isEmptyObject(dataObj.Notes[rec_id]))
				{
					dataObj.Notes[rec_id]=new Object();
				}
				
				if($.isEmptyObject(dataObj.NotesUser))
				{
					dataObj.NotesUser=new Object();
				}
				
				if($.isEmptyObject(dataObj.Notes['resp_user']))
				dataObj.Notes['resp_user']=new Object();
				
				if($.isEmptyObject(dataObj.Notes['resp_user'][rec_id]))
				dataObj.Notes['resp_user'][rec_id]=new Object();	
				
				dataObj.Notes[rec_id]=upObj['Notes'][rec_id];
				
				if(!$.isEmptyObject(upObj['NotesUser']))
				{
					for(X in upObj['NotesUser'])
					{
						dataObj.NotesUser[X]=upObj['NotesUser'][X];
					}
				}
				for(var y in upObj['Notes']['resp_user']){
					var tempObj= new Object();
					tempObj=upObj['Notes']['resp_user'][y];
					for(m in tempObj){						
						dataObj.Notes['resp_user'][rec_id][m]=tempObj[m];
					}
					
				}
			}
			
			doRow("",tab_id,upID);
		}
		getLoadingCombo(0);		
	}catch(Error){
		alert(Error)
		funError("updateRow","Section : Airworthiness Centre => Component, Main page Js Error <br/> ",Error.toString(),Error);	
	}
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
	strResult+='</div><div id="pagger">';
	strResult+='<span><strong>Show Rows:</strong></span><span id="ShowHideLov"></span>&nbsp;&nbsp;&nbsp;'+GetShowHideLov();
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
function DeleteActiveRow(DeleteActiveFlag)
{
	try{
		$("#act").val("");
		var CurID= $("#currentID").val();
		
		var idArr=CurID.split("_");
		var	tab_id=idArr['1'];
		var rec_id = idArr['2'];
		
		var tempUpObj = new Object();
		tempaudObj = getAuditObj();
		var upObj = new Object();
		
		if(DeleteActiveFlag==1){
			var msg ="Are you sure you want to delete this row?";
			tempaudObj['action_id'] = 16;
			upObj['updateObj'] = {"delete_flag":1};
			upObj['alertMsg'] = "The row has been deleted successfully.";		
			
		} else {
			var msg = " Are you sure you want to activate this row?";
			tempaudObj['action_id'] = 17;
			upObj['updateObj'] = {"delete_flag":0};
			upObj['alertMsg'] = "The row has been activated successfully.";	
			
		}
		var isPrnt=dataObj.rowData["_"+tab_id]["_"+rec_id]['PrntId'];
		
		if(confirm(msg)){
			upObj['whrUpdate'] = {"id":rec_id,"tab_id":tab_id};
			upObj['row_delete_flag'] = 1;
			upObj['parent_id'] =isPrnt;		
			xajax_updateRowValues(upObj,tempaudObj);
			
		}else
		{
			return false;
		}
	}catch(e){
		alert(e);
	}
}
function updateDeleteRec(deleteObj)
{
	var CurID = '_'+deleteObj['whrUpdate']['id'];
	var tab_id= '_'+deleteObj['whrUpdate']['tab_id'];
	if(deleteObj['row_delete_flag']==1){
		var DelFlg = deleteObj['updateObj']['delete_flag'];
		dataObj.rowData[tab_id][CurID]['delete_flag']=DelFlg;
	} 
	var SrNo = $("#TR"+tab_id+CurID).find("td:first").html().replace('&nbsp;&nbsp;','');	
	var cln = (SrNo%2==0)?0:1;		
	var upStr=getRows(cln,tab_id,CurID);	
	$("#TR"+tab_id+CurID).replaceWith(upStr);
	ResetVal();	
}
function setCellValue(cellVal)
{
	currentCell=cellVal;
}


function fnDeleteCell()
{
	var cellid = document.getElementById("current_cell").value;
	xajax_DeleteCell(xajax.getFormValues('frm'));
}

function setTextBox(txtVal,LovColID)
{
	//var strId = LovColID.split("_Col_");
	var idStr = LovColID.replace('lov', "editfield");
	$("#"+idStr).html(txtVal);
	if(txtVal == "Enter Text"){
		$("#"+idStr).css("display","");
	}else{
		$("#"+idStr).css("display","none");		
	}
	//editfield_38_1_Col_2
	return false;
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


/* Hide From Third Party	Add to Action List	Deny Access */
function getcheckbox(column_id,tab_id,robj,rid)
{
	try{
		var sel='';
		if(robj[rid][column_id]=='1')
		{
			 sel=" checked='checked' ";
		}
	    var checkbox_html='<input type="checkbox" '+sel+' onclick="fn_change'+column_id+'(\''+rid+'\',this,1);" id="'+column_id+'_cli_'+rid+'" name="'+column_id+'_cli_'+rid+'">';
	}catch(e){
		alert(e);
	}
	return checkbox_html;
}
function changeRowVal(columnName,rowVal,rec_id)
{
	if(columnName = 'work_status'){
		if(saveStatusAllObj[rec_id] && saveStatusAllObj[rec_id]!=rowVal){
			saveStatusAllObj[rec_id] = rowVal;
		} else {
			saveStatusAllObj[rec_id] = rowVal;
		}
	}
}

function fn_changeStatus(Obj)
{
	var statusVal=Obj.value;
	var statusInArr="";
	var status_arr=new Array(0,1,3,12,23,25,38,40,46);
	var id=Obj.id;
	var idArr=id.split('_')
	for(var i=0;i<status_arr.length;i++)
	{
		if(parseInt(statusVal)==parseInt(status_arr[i]))
		{
			statusInArr = true;
		}	
	}
	var tab_id="_"+idArr[1];
	var rec_id="_"+idArr[2];
	//alert(Obj.value)
	var obj=document.getElementById('priorityLov'+tab_id+rec_id);
	var PriorityStr=getPriorityLov(tab_id,rec_id,Obj.value,0);
	
	obj.outerHTML=PriorityStr;
	if(statusInArr)
	{
		 for(var i=0;i<status_arr.length;i++)
		 {
			if(statusVal==status_arr[i])
			{
				 document.getElementById("add_action"+tab_id+rec_id).checked=true;
				 
			}
		 }
	}
	
	var LalInArr="";
	var Lal_arr= new Array(7,8);
	for(var i=0;i<Lal_arr.length;i++)
	{
		if(parseInt(statusVal)==parseInt(Lal_arr[i]))
		{
			LalInArr = true;
		}	
	}
	
	if(LalInArr)
	{
		 for(var i=0;i<Lal_arr.length;i++)
		 {
			if(statusVal==Lal_arr[i])
			{
				 document.getElementById("add_action_"+idArr[1]+'_'+idArr[2]).checked='';
			}
		 }
	}
}

function changePriority(recid,priorityVal)
{
 

	 if(document.getElementById("change_priority").value=="")
	 {
		document.getElementById("change_priority").value= recid+"#"+priorityVal;	
	 }
	 else
	 {
			var arrPriority = prepareArrStr("change_priority","arr");
			var strPriority = '';
			
			for(i in arrPriority)
			{
				if(arrPriority[i] && i!=recid)
				{
					strPriority += (strPriority=="")? i+'#'+arrPriority[i] : ','+i+'#'+arrPriority[i];
				}
			}
			strPriority +=  (strPriority == "") ? recid+'#'+priorityVal : ','+recid+'#'+priorityVal;
			document.getElementById("change_priority").value = strPriority; 
	 }
 
}

function fn_changeAddAction(recid,objCheck)
{
		var lalValue = (objCheck.checked)? 1 : 0;
		 objCheck.value = lalValue;
		 if(document.getElementById("change_lal").value=="")
		 {
			document.getElementById("change_lal").value= recid+"#"+lalValue;	
		 }
		 else
		 {
				var arrLal = prepareArrStr("change_lal","arr");
				var strLal = '';
				for(i in arrLal)
				{
					if(arrLal[i] && i!=recid)
					{
						strLal += (strLal=="")? i+'#'+arrLal[i] : ','+i+'#'+arrLal[i];
					}
				}
				strLal +=  (strLal == "") ? recid+'#'+lalValue : ','+recid+'#'+lalValue;
				document.getElementById("change_lal").value = strLal; 
		 }
	
} 

function fn_changeDenyAccess(recid,objCheck)
{

		var lalValue = (objCheck.checked)? 1 : 0;
		 objCheck.value = lalValue;
		 if(document.getElementById("change_deny_access").value=="")
		 {
			document.getElementById("change_deny_access").value= recid+"#"+lalValue;	
		 }
		 else
		 {
				var arrLal = prepareArrStr("change_deny_access","arr");
				var strLal = '';
				for(i in arrLal)
				{
					if(arrLal[i] && i!=recid)
					{
						strLal += (strLal=="")? i+'#'+arrLal[i] : ','+i+'#'+arrLal[i];
					}
				}
				strLal +=  (strLal == "") ? recid+'#'+lalValue : ','+recid+'#'+lalValue;
				document.getElementById("change_deny_access").value = strLal; 
		 }
	
} 
function saveAll()
{
	try {
	var ai = 0;
	var saveObj=new Object();
	var auditObj =new Object();	
	if($("#inboxmod").val()==1)
	{
		var tempAudit = {"user_id":UserID,"user_name":user_name,"type":$("#type").val(),"client_id":$("#client_id").val(),"section":1,"sub_section":1,"tail_id":$("#comp_id").val()};	
				
			for (cat_id in saveAllDataObj){
				for(mainID in saveAllDataObj[cat_id]){
					saveObj[mainID]=1;
					for(nid in dataObj.Notes[mainID]){	
					var str_resp='';					
						for(var resp_id in dataObj.Notes["resp_user"][mainID]){
							if(str_resp!="")
								str_resp+=",";	
							str_resp+=dataObj.NotesUser[dataObj.Notes["resp_user"][mainID][resp_id]]["username"];
						}						
						var tempAudit2 = {"tab_name":tab_name+"&nbsp;&raquo;&nbsp; ROW "+mainID,"field_title":dataObj.NotesUser[dataObj.Notes[mainID][nid]["sender"]]["username"],"old_value": dataObj.Notes[mainID][nid]["notes"],"new_value":str_resp,"action_id":59,"main_id":mainID};
						auditInboxObj[nid] = tempAudit2;												
					}			
				}			
			}		
		xajax_saveAllViewed(saveObj,auditInboxObj,tempAudit);
		return true;
	}	
	if(!$.isEmptyObject(saveAllDataObj)){
		for(var t in saveAllDataObj){
			if(!$.isEmptyObject[saveAllDataObj[t]]){
				for(var y in saveAllDataObj[t]){
					if(!$.isEmptyObject[saveAllDataObj[t][y]]){
						for(var c in saveAllDataObj[t][y]){
							var upVal =saveAllDataObj[t][y][c];
							var fieldtxtStr={'work_status':'Work Status','priority':'Set Priority','deny_access_cli':'Hide From Third Party','deny_access':'Deny Access','add_action':'Add to Action List'};
							var actId = 14;
							if(c=="work_status"){
								var oldStatus = dataObj.rowData['_'+t]['_'+y]['work_status'];
								 if(statusObj["_"+oldStatus]) {
									oldVal =  statusObj["_"+oldStatus]['name']+','+statusObj["_"+oldStatus]['bg_color'];
								 }
								 var newStatus = upVal;
								 if(statusObj["_"+newStatus]) {
									newVal =  statusObj["_"+newStatus]['name']+','+statusObj["_"+newStatus]['bg_color'];
								 }
								 actId=15;
							}
							
							if(c=="priority"){
								var oldPriority = dataObj.rowData['_'+t]['_'+y]['priority'];
								 if(priorityObj["_"+oldPriority]) {
									oldVal =  priorityObj["_"+oldPriority]['name']+','+priorityObj["_"+oldPriority]['bg_color'];
								 }
								 var newPriority = upVal;
								 if(priorityObj["_"+newPriority]) {
									newVal =  priorityObj["_"+newPriority]['name']+','+priorityObj["_"+newPriority]['bg_color'];
						 		}
								 actId=30;
							}
							
							if(c=="deny_access_cli" || c=="deny_access" || c=="add_action"){
								
								var oldValue = dataObj.rowData['_'+t]['_'+y][c];
								var newvalue = upVal;
								var oldVal= ""; 
								var newVal= "" 
								if(c=="deny_access"){
									if(upVal==1)
									 actId=31;
									 else
									 actId=32;
									 oldVal= (oldValue==1)?"Access Denied":"Removed Access Denied"; 
									 newVal= (newvalue==1)?"Access Denied":"Removed Access Denied"; 
								} else  if(c=="add_action"){
									if(upVal==1)
									 actId=33;
									 else
									 actId=34;									 
								}
								
							}
							
							saveObj[y]={"colName":c,"upVal":upVal,"tab_id":t};									
							
							auditObj[ai] = getAuditObj();
							auditObj[ai]['field_title']	=fieldtxtStr[c];
							auditObj[ai]['old_value'] = oldVal;
							auditObj[ai]['new_value'] = newVal;		
							auditObj[ai]['action_id'] =actId;
							auditObj[ai]['main_id'] = y;
							ai++;
						}
					}
				}
				
			}
			
		}
	}
	xajax_saveAll(saveObj,auditObj);
	}catch(Error){
		alert(Error)
		funError("updateRow","Section : Airworthiness Centre => Component, Main page Js Error <br/> ",Error.toString(),Error);	
	}
}
function updateAllRow(upObj)
{
	try{
		if(!$.isEmptyObject(upObj['updateObj'])){
			var tempObj = upObj['updateObj'];
			for(var y in tempObj){
				var tab_idVal = '_'+tempObj[y]["tab_id"];				
				var colName = tempObj[y]["colName"];
				var upVal = tempObj[y]["upVal"];				
				dataObj.rowData[tab_idVal]['_'+y][colName] = upVal;				
				var tempUpObj = new Object();
				var SrNo = $("#TR"+tab_idVal+'_'+y).find("td:first").html().replace('&nbsp;&nbsp;','');
				var cln = (SrNo%2==0)?0:1;
				var upStr=getRows(cln,tab_idVal,'_'+y);	
				$("#TR"+tab_idVal+'_'+y).replaceWith(upStr);
				
			}
			
		}
		
		ResetVal();
	}catch(Error){
		alert(Error)
		funError("updateAllRow","Section : Airworthiness Centre => Template Data, Main page Js Error <br/> ",Error.toString(),Error);
	}
}
function ResetVal()
{
	saveAllDataObj = new Object();
}

function openCategoryMgmt(client_Id)
{
	var Type = document.getElementById("Type").value
	/*var Category_win = window.open('manage_rows.php?section=CATEGORYMGMT&Type='+Type+'&link_id='+document.getElementById("link_id").value+'&tab_id=12&SectionFlag='+SectionFlag+'&client_Id='+client_Id,'Category_win','height='+screenH+',width='+screenW+',scrollbars=yes,resizable=yes,left=50,fullscreen=yes');
	Category_win.focus();*/
}


function sheetStatusPopup()
{
	var Type = document.getElementById("Type").value
	/*var Header_Win = window.open('manage_rows.php?section=SUMMARY_STATUS&Type='+Type+'&link_id='+document.getElementById("link_id").value+'&tab_id='+document.getElementById("tab_id").value+'&SectionFlag='+SectionFlag,'Header_Win','height='+screenH+',width='+screenW+',scrollbars=yes,resizable=yes,left=50,fullscreen=yes');
	Header_Win.focus();*/
}

function open_List_comment(rec_id)
{	
	var list_comment_win = window.open('airworthiness_centre.php?section=3&sub_section=9&main_id='+rec_id.replace('_',''),'list_comment_win','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1');
	list_comment_win.focus();
}
function AuditTrailsCurrentStatus(TabId,ParentId,Type,Start)
{
	
	var type = $("#type").val();	
	var	section= $("#sectionVal").val();	
	var	sub_section= $("#sub_sectionVal").val();	
	var headerAudit = window.open('airworthiness_centre_audit.php?section='+section+'&sub_section='+sub_section+'&type='+type,'headerAudit',
								 'height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	
}

function fn_ReturnCondition(arg1,arg2,arg3,arg4)
{
	/*var mywindow = window.open('manage_rows.php?section=RETURNCND&link_id='+arg1+'&Title='+arg2+'&tab_id='+arg3+'&Type='+arg4+'&SectionFlag='+SectionFlag,'Header_Win','height='+screenH+',width='+screenW+',scrollbars=yes,resizable=yes,left=50');
	mywindow.focus();*/
}

function loadSearch(clientid)
{
	var linkId = document.getElementById('link_id').value;
	var Type = document.getElementById('Type').value;
	var searchWin = window.open('search.php?view=other&cs=srch&csval=tab&linkId='+linkId+'&tabId=12&client_id='+clientid+'&otype='+Type+'&SectionFlag='+SectionFlag,'Srcwindow','scrollbars=1,resizable=1,fullscreen=yes');
	searchWin.focus();
}

//################################ REPORT AND CONTROL MENU FUNCTIONS ###############################//
function manageSubMenuHOver_R()
{
	document.getElementById("manageSubMenu_R").style.position = "absolute";
	document.getElementById("manageSubMenu_R").style.display = "block";
}
function manageSubMenuOut_R()
{
	document.getElementById("manageSubMenu_R").style.display = "none";
}
function isMouseLeaveOrEnter(e, handler) 
{ 
	if (e.type != 'mouseout' && e.type != 'mouseover') 
		return false; 
	var reltg = e.relatedTarget ? e.relatedTarget : e.type == 'mouseout' ? e.toElement : e.fromElement; 
	while (reltg && reltg != handler) 
		reltg = reltg.parentNode; 
		return (reltg != handler); 
}

function setPrintingPopWindowWrokStatus(mainPage,TabId,Tablename,ParentId,Title,Start)
{
	 var statusVal ="";
	 var pad_str="";
	 if(document.getElementById("cmb_status"))
	 {
		   var comboElm = document.getElementById("cmb_status");
		   for(i=0;i<comboElm.length;i++)
		   {
			   if(comboElm[i].selected)
				{
					statusVal += pad_str+comboElm[i].value;	
					pad_str=",";
				}
		   }
     }
	   
	var printWinObj = window.open(mainPage+'?parentId='+ParentId+'&Title='+Title+'&TabId='+TabId+'&statusVal='+statusVal+'&Start='+Start+'&SectionFlag='+SectionFlag,'printingPreview','height='+screenH+',width='+screenW+',scrollbars=yes,left=50');
 	printWinObj.focus();	
}
function show_hide(tab_id,val)
{
	if($("[rel^=group_"+tab_id+"_"+val+"]").first().css("display") == "none")
		{
			var fun_name=1;
			
			$("#img_"+tab_id+"_"+val).attr("src",pathLink+"images/minus_active.jpg");
		}
		else
		{
			var fun_name=2;
			$("#img_"+tab_id+"_"+val).attr("src",pathLink+"images/plus_active.jpg");
		}
		//$("[rel^=group_"+val+"]").Toggle("slow");
		$("[rel^=group_"+tab_id+"_"+val+"]").each(function() {
			
			if(fun_name==1)
  			 $(this).show();
			 else
			 $(this).hide();
			 
        });
}

function FnHeader(flg)
{
	var linkId = document.getElementById('link_id').value;
	var Type = document.getElementById('type').value;
	var tmpid = document.getElementById('currentID').value;

	tmpid=tmpid.split("_");
	var tab_id=tmpid[1];
	var rec_id=tmpid[2];	
	if(flg == 0){
		var currentrecId=document.getElementById('currentID').value;	
		var my_flg = 0;
		
		if(my_flg == 1)
		{
			if(confirm("Please note all documents will be lost. Are you sure you want to continue?"))
			{
				getLoadingCombo(1,'');
				xajax_setHeader(linkId,tab_id,Type,rec_id,flg);
			}
		}
		else if(my_flg == 0)
		{
			getLoadingCombo(1,'');
			xajax_setHeader(linkId,tab_id,Type,rec_id,flg);
		}
	}
	else
	{
		getLoadingCombo(1,'');
		xajax_setHeader(linkId,tab_id,Type,rec_id,flg);
	}
}
function fnActivateDeactivateReadOnly(flag)
{
	getLoadingCombo(1,"");
	var saveObj=new Object;
	saveObj['link_id']=$("#link_id").val();
	var tempval=$("#currentID").val().split('_');
	var tab_id=tempval[1];
	var rec_id=tempval[2];
	
	saveObj['Type']=$("#type").val();
	saveObj['id']=tab_id+'_'+rec_id;
	
	saveObj['tab_id']=tab_id;
	saveObj['rec_id']=rec_id;
	var str='';
	var actId=29;
	var old_value = '';
	var new_value = '';
	if(flag==1){
		str = "Activate Read Only";		
		old_value = "Activated";
		new_value= "Deactivated";
	} else {
		str = "Deactivate Read Only";		
		old_value = "Deactivated";
		new_value= "Activated";
	}	
	dataObj.rowData['_'+tab_id]['_'+rec_id]['is_readonly']=(flag==1) ? 0 : 1;
	var auditObj = getAuditObj();
	auditObj['field_title']	=str;
	auditObj['old_value'] = old_value;
	auditObj['new_value'] = new_value;		
	auditObj['action_id'] = actId;
	auditObj['main_id'] = rec_id;
	
	
	
	
	xajax_activateDeactivateReadOnly(saveObj,flag,auditObj);
	
	doRow("unsetRow","_"+tab_id,"_"+rec_id);
	return false;
}


function toggle_visibility(id) 
{
	var text = document.getElementById("aText_"+id);
	text.value = (text.value==btnTextOpen) ?  btnTextClose : btnTextOpen;
	$("tr[id^=TR_"+id+"]").toggle();
	
}

function fnAddSubTemplate()
{
	var tempval=$("#currentID").val().split('_');
	var comp_main_id=$("#comp_main_id").val();
	var comp_id=$("#comp_id").val();
	var tab_id=tempval[1];
	var recId=tempval[2];
	var link_id=$("#link_id").val();
	var Type=$("#type").val();
	var section=$("#sectionVal").val();
	var sub_section=$("#sub_sectionVal").val();
	var client_id=$("#client_id").val();
	
	var params = '';
	http://192.168.100.16/flydocstest/manage_rows.php?section=ADD_DB_TEMP&link_id=261&tab_id=1&recId=43&Type=1&SectionFlag=
	params = '&comp_main_id='+comp_main_id+'&Type='+Type+'&recId='+recId+'&client_id='+client_id+'&comp_id='+comp_id;
	var multiRow_Win = window.open('airworthiness_centre.php?add_section=ADD_DB_TEMP&section='+section+'&sub_section='+sub_section+params,'multiRow_Win','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1');
	multiRow_Win .focus();
}





// Below Functions for Manage Hyperlink





function hide_mh_combo()
{
	if(document.getElementById("hdn_hyperlink_value"))
		document.getElementById("hdn_hyperlink_value").value = "0";
	if(document.getElementById("type_div"))
		document.getElementById("type_div").style.display="none";
	if(document.getElementById("type_id"))
		document.getElementById("type_id").value="";
	if(document.getElementById("position_div"))
		document.getElementById("position_div").innerHTML="";	
	if(document.getElementById("view_Td"))
		document.getElementById("view_Td").style.display="none";
	if(document.getElementById("tdMainCS"))
		document.getElementById("tdMainCS").style.display="none";
}


function show_type(val)
{
	if(val==1)
	{
		document.getElementById("type_div").style.display="";
	}
	else
	{
		document.getElementById("type_div").style.display="none";
		document.getElementById("type_id").value = "" ;
		
		
		document.getElementById("hdn_hyperlink_value").value = "0";
		document.getElementById('tdMainCS').innerHTML = '&nbsp;';
		document.getElementById('tdSubCS').innerHTML = '&nbsp;';
		document.getElementById('tdChildCS').innerHTML = '&nbsp;';
	}
	
}

function show_View_type(val)
{
	if(val==1 || val==3 || val==5)
	{
		document.getElementById("position_div").style.display = 'none';
		var pos = '';
		var type_id = '';
		
		if(document.getElementById("position_id"))
			pos = document.getElementById("position_id").value;
		if(document.getElementById("type_id"))
			type_id = document.getElementById("type_id").value;
		
		xajax_getViewType(document.getElementById("selClients").value,pos,type_id);
		
		//document.getElementById("view_Td").style.display = '';
	}
	else if(val==2 || val==4)
	{
		document.getElementById("view_Td").style.display = 'none';
		if(val!='')
		{
			show_position(1);
			document.getElementById("position_div").style.display = '';
		}
	}
	else
	{
		document.getElementById("view_Td").style.display = 'none';
	}
	
	var centre_id = document.getElementById("centre_id").value;
	if(document.getElementById("view_ddl"))
		document.getElementById("view_ddl").value = "";
	
	if(centre_id==2)
	{
		document.getElementById("tdMainCS").style.display = 'none';
		document.getElementById("tdSubCS").style.display = 'none';
	}
	if(document.getElementById("type_id").value=='')
	{
		document.getElementById("position_div").style.display = 'none';
	}
	
}

function getSubTab(type,value)
{
	if(type==1)
	{
		if(value!=0)
		{
			aircraft_id = document.getElementById("aircraft_id").value;
			xajax_getSubTab(value,aircraft_id);
		}
		else
		{
			document.getElementById('tdSubCS').innerHTML = '&nbsp;';
			document.getElementById('tdChildCS').innerHTML = '&nbsp;';
		}
		document.getElementById("hdn_hyperlink_value").value = '0';
	}
	else
	{
		if(value==0)
		{
			document.getElementById('tdSubCS').innerHTML = '&nbsp;';
			document.getElementById('tdChildCS').innerHTML = '&nbsp;';
		}
		document.getElementById("hdn_hyperlink_value").value = value;
	}
}
function getChildTab(value)
{
	if(value==7 || value==8)
	{
		document.getElementById("hdn_hyperlink_value").value = "0";
		aircraft_id = document.getElementById("aircraft_id").value;
		xajax_getChildTab(value,aircraft_id);
	}
	else
	{
		document.getElementById('tdChildCS').innerHTML = '&nbsp;';
		document.getElementById("hdn_hyperlink_value").value = value;
	}
}

function show_position(val)
{
	document.getElementById("hdn_hyperlink_value").value = "0";
	document.getElementById('tdMainCS').innerHTML= '';
	document.getElementById('tdSubCS').innerHTML= '';
	document.getElementById('tdChildCS').innerHTML= '';
	
	if(val=="")
	{
		document.getElementById('position_div').innerHTML="";
		return false;
	}
	
	document.getElementById('position_div').style.display = '';
	xajax_show_position(document.getElementById('type_id').value,document.getElementById("component_id").value);
}

function setChildTabValue(value)
{
	document.getElementById("hdn_hyperlink_value").value = value;
}


function getSubMhTab(value)
{
	if(value!=0)
	{
		document.getElementById("hdn_hyperlink_value").value = '0';
		xajax_getSubMhTab(value,document.getElementById("selClients").value);
		
	}
	else
	{
		document.getElementById('tdSubCS').innerHTML = '&nbsp;';
		document.getElementById('tdChildCS').innerHTML = '&nbsp;';
		document.getElementById("hdn_hyperlink_value").value = '0';
	}

}
function getChildMhTab(value)
{
	if(value!=0)
	{
		if(value==-1)
		{
			value=document.getElementById("ddl_maincs").value
			document.getElementById("hdn_hyperlink_value").value = value;
		}
		else
		{
			document.getElementById("hdn_hyperlink_value").value = value;
			xajax_getChildMhTab(value);
		}
	}
	else
	{
		document.getElementById('tdChildCS').innerHTML = '&nbsp;';
	
	}

}

function show_sub_position(val)
{
	document.getElementById("hdn_hyperlink_value").value = "0";
	document.getElementById('tdMainCS').innerHTML= '';
	document.getElementById('tdSubCS').innerHTML= '';
	document.getElementById('tdChildCS').innerHTML= '';
	var pos = '';
	var type_id = '';
	if(document.getElementById("position_id"))
		pos = document.getElementById("position_id").value;
	if(document.getElementById("type_id"))
		type_id = document.getElementById("type_id").value;
	
	if(type_id!=1 && type_id!=3 && pos!='')
		xajax_getViewType(document.getElementById("selClients").value,pos,type_id);
	
	if(val=="")
	{
		if(document.getElementById('view_ddl'))
		{
			document.getElementById('view_ddl').value= '';
			document.getElementById('view_Td').style.display= 'none';
		}
		return false;
	}
	else
	{
		if(document.getElementById('view_ddl'))
		{
			//document.getElementById('view_Td').style.display= '';
			document.getElementById('view_ddl').value= '';
		}
	}
}

function setMenus()
{
	var hyperLink_option;
	var hyperLink_val = document.getElementById("hyperLink_val").value;        
        
	if(hyperLink_val==0 )
	{
		hyperLink_option = 0;
	}
	else
	{
		hyperLink_option = 1;
	}
        
	var type_ = document.getElementById("Oldtype").value;
	var typeFromUrl = document.getElementById("type").value;
	var OldCentre_id = document.getElementById("OldCentre_id").value;
	var OldPosition_id = document.getElementById("OldPosition_id").value;
	var OldSubPosition_id = document.getElementById("OldSubPosition_id").value;
	var selClients = document.getElementById("selClients").value;
	var aircraft_id = document.getElementById("component_id").value;
	var bible_viewType = document.getElementById("bible_viewType").value;
	var SelPart_Int = document.getElementById("hdnSelPart").value;
	
	xajax_setMenus(hyperLink_val,hyperLink_option,type_,OldCentre_id,OldPosition_id,OldSubPosition_id,selClients,aircraft_id,typeFromUrl,bible_viewType,SelPart_Int);
}

function show_sub_type(valVal)
{
	var val = 0;
	var type = document.getElementById("type").value;
	if(valVal==1)		//Year View
	{
		var val = document.getElementById("type_id").value;
		
		if(type==1)		//Aircraft Centre
		{
			document.getElementById("hdn_hyperlink_value").value = "0";
			document.getElementById('tdMainCS').innerHTML = '&nbsp;';
			document.getElementById('tdSubCS').innerHTML = '&nbsp;';
			document.getElementById('tdChildCS').innerHTML = '&nbsp;';
			
			xajax_getMainMhTab("",document.getElementById("selClients").value,val);
		}
		if(type!=1)		//Other Centre
		{
			xajax_getMainMhTab("",document.getElementById("selClients").value,type);
		}
	}
	else if(valVal==2)	//Delivery Bible View
	{
		if(document.getElementById("tdMainCS"))
			document.getElementById("tdMainCS").style.display = 'none';
		if(document.getElementById("tdSubCS"))
			document.getElementById("tdSubCS").style.display = 'none';
		
		document.getElementById("hdn_hyperlink_value").value = -1;
	}
	else
	{
		if(document.getElementById("tdMainCS"))
			document.getElementById("tdMainCS").style.display = 'none';
		if(document.getElementById("tdSubCS"))
			document.getElementById("tdSubCS").style.display = 'none';
	}
}


function validateNotes(flagNotes,tab_id,rec_id)
{
	if(flagNotes==1)
	{
		if(Object.keys(NotesObj).length==0 && document.getElementById('notes'+tab_id+rec_id).value!='')
		{
			alert("You need to assign this note / query to a single user or multiple users.\n\n Please select your users.");
				return false;
		}
		else
		{
			if(document.getElementById("act").value == "EDIT")
			{
				return true;
			}
		}
	}
	else if(flagNotes==0)
	{
		NotesObj=new Object();
		return true;
	}
	return true;
}


function updateRowFromViewArea(saveObj,saveObjEdit,tab_id,rec_id)
{	
	tab_id="_"+tab_id;
	rec_id="_"+rec_id;
	validateNotes(0);
	
	for(ColID in saveObj)
	{
		ColID1=ColID;
		if(ColID=="statusLov"){
			var ColID1="work_status";
			dataObj.rowData[tab_id][rec_id][ColID1]=saveObj[ColID];
		}
		else if(ColID=='priorityLov'){
			var ColID1='priority';
			dataObj.rowData[tab_id][rec_id][ColID1]=saveObj[ColID];
		}
		else if(ColID=='add_action'){
			var ColID1='add_action';
			dataObj.rowData[tab_id][rec_id][ColID1]=saveObj[ColID];
		}
		else if(ColID=='Notes')
		{
			if($.isEmptyObject(dataObj.Notes)){
				dataObj.Notes=new Object();
			}
			
			if($.isEmptyObject(dataObj.Notes[tab_id])){
				dataObj.Notes[tab_id]=new Object();
			}
			
			if(saveObj['Notes'] && saveObj['res_users']) {
				dataObj.Notes[tab_id][rec_id]=(!saveObj['Notes'][tab_id][rec_id])?'':saveObj['Notes'][tab_id][rec_id];
				dataObj.rowData[tab_id][rec_id]['res_users']=(!saveObj['res_users'][tab_id][rec_id])?'':saveObj['res_users'][tab_id][rec_id];//saveObj['res_users'][tab_id][rec_id];
			}
			else {
				dataObj.Notes[tab_id][rec_id]='';
				dataObj.rowData[tab_id][rec_id]['res_users']='';
			}
			continue;
		}
		else if(ColID=='NotesUser') 
		{
			for(x in saveObj['NotesUser'])
			{
				if($.isEmptyObject(dataObj.NotesUser)){
					dataObj.NotesUser=new Object();
				}
				dataObj.NotesUser[x]=saveObj['NotesUser'][x];
			}
			continue;
		}
		else if(ColID=='autoFilter')
		{
			for(var x in saveObj['autoFilter'][tab_id])
			{
				if($.isEmptyObject(autoFilterObj[1])){
					autoFilterObj[1]=new Object();
				}
				
				if($.isEmptyObject(autoFilterObj[1][tab_id]))
				{
					autoFilterObj[1][tab_id]=new Object();
				}
				var addFlg=0;
				if(!autoFilterObj[1][tab_id][x])
				{
					autoFilterObj[1][tab_id][x]=new Object();
					autoFilterObj[1][tab_id][x][0]=saveObj['autoFilter'][tab_id][x];
				}
				else
				{
					for(var val in autoFilterObj[1][tab_id][x])
					{
						if(autoFilterObj[1][tab_id][x][val]==saveObj['autoFilter'][tab_id][x])
						{
							addFlg=1;
							break;
						}
					}
				}
				
				if(addFlg==0)
				{
					if(autoFilterObj[1][tab_id] && saveObj['autoFilter'][tab_id])
					{
						autoFilterObj[1][tab_id][x][parseInt(val)+1]=saveObj['autoFilter'][tab_id][x];
						if($("#fd_filter"+tab_id+'_'+x))
						{
							$("#fd_filter"+tab_id+'_'+x).append('<option>', {
								value: saveObj['autoFilter'][tab_id][x],
								text: saveObj['autoFilter'][tab_id][x]
							});
						}
					}
				}
			}
		}
		else
			continue;
		
		dataObj.rowData[tab_id][rec_id][ColID]=saveObj[ColID];
	}
	
	for(ColID in saveObjEdit)
	{
		dataObj.rowData[tab_id][rec_id][ColID]=saveObjEdit[ColID];
	}
	
	doRow("unsetRow",tab_id,rec_id);
}

function fnSave()
{
		
	 var ifrm = document.getElementById('csifreame');
    var doc = ifrm.contentDocument? ifrm.contentDocument: 
        ifrm.contentWindow.document;
		
	$("#hdnRecIds").val("");
	var centre_idVal = $("#centre_id").val();
	var tempLen = Object.keys(checkedHObj).length;
	if(centre_idVal==1){		
		 //alert("Please Select atleast one Record.")
			//return false;
			if(tempLen>0){
				var hstr = '';
				for(y in checkedHObj){
					hstr += (hstr=='')?y:','+y;					
				}
				$("#hdnRecIds").val(hstr);
			}
	}
		
	var ddlLink = document.getElementById('chk_HyperLink');
	var CSLink = document.getElementById('hdn_hyperlink_value');
	var centre_id = document.getElementById('centre_id');
	var SelPart_Int = document.getElementById('hdnSelPart');
	var ShowMsg_Str=' HyperLink ';
	
	if(SelPart_Int.value == 1)
	{
		ShowMsg_Str = ' Statement Option ';
		if(ddlLink.value == '')
		{
			alert("Please select Hyperlink Option.");
			return false;
		}
		else if(ddlLink.value!=0)
		{
			if(ddlLink!= '' && centre_id.value=='')
			{
				alert("Please select Section.");
				return false;
			}
			
			if(centre_id.value==1)
			{
				CSLink.value='';
			}
			
			if(ddlLink.value == '1' &&  (CSLink.value == '0' || CSLink.value == ''))
			{
				if(centre_id.value==2)
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
						document.getElementById("hdn_hyperlink_value").value = retValue;
					}
				}
			}
			
			if(centre_id.value==2)
			{
				if(document.getElementById("view_ddl").value=='')
				{
					alert("Please select view type to link this record to.");
					return false;
				}
			}
			if(centre_id.value==2 && document.getElementById("view_ddl") && document.getElementById("view_ddl").value==1)
			{
				if(document.getElementById("ddl_maincs") && document.getElementById("ddl_maincs").value==0)
				{
					alert("Please select a Maintenance History tab to link this record to.");
					return false;
				}
			}
		}
	}
	else if (SelPart_Int.value == 2)
	{		
		document.getElementById("centre_id").value = '1';
		var retValue = validateCSCombo();
		if(retValue==false)
		{
			alert("Please select a Current Status tab to link this record to.");
			return false;
		}
		
		else if(retValue==true || (retValue!=true && retValue!=false))
		{			
			document.getElementById("hdn_hyperlink_value").value = retValue;
		}
	}
	else
	{
		return false;
	}
        var hyperLink_val = document.getElementById("hyperLink_val").value;
        var statement_val = document.getElementById("statement_val").value;
        if(SelPart_Int.value == 1 && statement_val != 0)
        {   
            if(!confirm("The"+ShowMsg_Str+"value for this row will be removed, do you want to continue?"))
            return false;
        }
        if(SelPart_Int.value == 2 && hyperLink_val != 0)
        {
            if(!confirm("The"+ShowMsg_Str+"value for this row will be removed, do you want to continue?"))
            return false;
        }
	xajax_UpdateHyperLink(xajax.getFormValues('frm'));
	window.opener.location.reload();
}

function freezePaneOnOff()
{
	if($('#onImg').is(':checked'))
		RL_freez('F');
	else if($('#offImg').is(':checked'))
		RL_freez('UF');
}
function openCompilation()
{
	
	var tab_id = $("#tab_id").val();
	var type = $("#type").val();	
	var aircraft_id = $("#link_id").val();
	
	var Category_win = window.open('manage_db_compile.php?section=1&type='+type+'&act=select&linkId='+aircraft_id+'&tabId='+tab_id+'&section='+SectionFlag+'&compilationType=DB','Category_win','height='+screenH+',width='+screenW+',scrollbars=yes,resizable=yes,left=50,fullscreen=yes');
	Category_win.focus();
	
}
function getAuditObj()
{
	var tempAudit = new Object();
	tempAudit = {"user_id":UserID,"user_name":user_name,"type":$("#type").val(),"tab_name":tab_name,"client_id":$("#client_id").val(),"section":$("#sectionVal").val(),"sub_section":$("#sub_sectionVal").val(),
				"tail_id":$("#comp_id").val(),"comp_main_id":$("#comp_main_id").val()};	
	return tempAudit;
}

function setSaveAllValue(colIdVal,tab_id,rec_id,elemObj)
{
	if(!saveAllDataObj[tab_id]){
		saveAllDataObj[tab_id]= new Object();
	}
	if(!saveAllDataObj[tab_id][rec_id]){
		saveAllDataObj[tab_id][rec_id]= new Object();
	}
	var upVal = 0;	
	if(elemObj.type=="checkbox"){
		 if(elemObj.checked==true){
			 upVal=1;
		 }
	} else {
		 upVal=elemObj.value;
	}
	var oldVal = 0;
	if(dataObj.rowData['_'+tab_id]['_'+rec_id][colIdVal]){
		var oldVal = dataObj.rowData['_'+tab_id]['_'+rec_id][colIdVal];
	}
	
	if(oldVal!=upVal){		
		saveAllDataObj[tab_id][rec_id][colIdVal]=upVal;
	} else {
		if(oldVal==upVal){		
		var arrObj = Object.keys(saveAllDataObj[tab_id][rec_id]);
		var tempArr= new Array();
		var j = 0;
		for(var k in arrObj){
			tempArr[j]= arrObj[k];
			j++;
		}
		if($.inArray(colIdVal,tempArr)>=0){					
			delete saveAllDataObj[tab_id][rec_id][colIdVal];
		}
			
		}		
	}
}
//?linkId=1&act=select&section=1&type=1&tabId=1&compilationType=DB
function active()
{
	var type=$("#type").val();
	var comp_id=$("#comp_id").val();
	var client_id=$("#client_id").val();
	var mainRowid=$("#comp_main_id").val();
	var section = $("#sectionVal").val();
	var CentreHeader = window.open('airworthiness_centre.php?section='+section+'&type='+type+'&sub_section=6&comp_id='+comp_id+'&client_id='+client_id+'&mainRowid='+mainRowid+'','CentreHeader','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	CentreHeader.focus();
}
function openGroupwindow()
{
	var type=$("#type").val();
	var comp_id=$("#comp_id").val();
	var client_id=$("#client_id").val();
	var comp_main_id=$("#comp_main_id").val();
	var section = $("#sectionVal").val();
	var RvMs = window.open('airworthiness_centre.php?section='+section+'&type='+type+'&sub_section=5&comp_id='+comp_id+'&client_id='+client_id+'&comp_main_id='+comp_main_id+'','RvMs','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	RvMs.focus();
}

function openWorkStatus()
{
	var type=$("#type").val();
	var comp_id=$("#comp_id").val();
	var client_id=$("#client_id").val();
	var comp_main_id=$("#comp_main_id").val();
	var section = $("#sectionVal").val();
	var CentreWs = window.open('airworthiness_centre.php?section='+section+'&type='+type+'&sub_section=4&comp_id='+comp_id+'&client_id='+client_id+'&comp_main_id='+comp_main_id,'CentreWs','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	CentreWs.focus();
	
}
function openStatuslist()
{
	var type = $("#type").val();
	var section = $("#sectionVal").val();
	var client_id=$("#client_id").val();
	var comp_id=$("#comp_id").val();
	var comp_main_id=$("#comp_main_id").val();
	
	var CentreHeader = window.open('airworthiness_centre.php?section='+section+'&type='+type+'&sub_section=2&comp_id='+comp_id+'&client_id='+client_id+'&comp_main_id='+comp_main_id,
						   'CentreHeader','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes')
	CentreHeader.focus();
}
function ShowModalForNotesUsers() 
{
	var type = $("#type").val();
	var section = $("#sectionVal").val();
	var sub_section = $("#sub_sectionVal").val();
	var client_id=$("#client_id").val();
	var comp_id=$("#comp_id").val();
	var comp_main_id=$("#comp_main_id").val();
	var data_idVal=$("#currentID").val().split("_");
	data_id = data_idVal[2];
	
	var url = 'airworthiness_centre.php?section='+section+'&sub_section=7&client_id='+client_id+'&comp_id='+comp_id+'&comp_main_id='+comp_main_id+'&main_id='+data_id+'&type='+type;
	var modal = window.open (url, 'UserLov', "width=1000,height=500,left=300,modal=yes,alwaysRaised=yes,resizable=yes", null);
	modal.focus();
}

function openViewArea(tab_id,rec_id)
{
	var link_id = $("#link_id").val();
	var Type = $("#type").val();
	var client_id = $("#client_id").val();
	var hyperlink_value=0;
	if(dataObj.Links[tab_id] && dataObj.Links[tab_id][rec_id])
		var hyperlink_value=dataObj.Links[tab_id][rec_id]['links'];
	
	var tab_id=tab_id.replace('_','');
	var rec_id=rec_id.replace('_','');
	if(hyperlink_value=='NO')
	{
		alert("You currently do not have access to this section. Please contact your Administrator to arrange access.");
		return false;
	}
	else if(hyperlink_value=='YES')
	{
		alert("The Component has not been assigned to this aircraft");
		return false;
	}
	else if(hyperlink_value=='NCP')
	{
		alert("Linked area(Component/Tag) is not attached with Aircraft."); 
		return false;
	}
	else if(hyperlink_value==0)
	{
		var inbparams='';	
		if($("#inboxmod").val()==1)
		{
			inbparams+='&inboxmod=1';
		}
		var ListDoc_Win = window.open('airworthiness_centre.php?section=4&srNo='+rec_id+'&rec_id='+rec_id+'&start=&client_id='+client_id+'&Type='+Type+''+inbparams,'ListDoc_Win','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1');
		ListDoc_Win.focus();
		
	}
	else
	{
		var LinkedDoc_Win = window.open(hyperlink_value,'LinkedDoc_Win','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1');
		LinkedDoc_Win.focus();
	}
}
function removeTempalte()
{
	if(confirm('Are you sure you want to delete this template?'))
	{
		var aircraft_id = $("#link_id").val();
		var comp_main_id = $("#comp_main_id").val();
		var client_id = $("#client_id").val();
		var type = $("#type").val();
		
		var deleteObj=new Object();
		deleteObj['component_id']=aircraft_id;
		deleteObj['client_id']=client_id;
		deleteObj['comp_main_id']=comp_main_id;
		deleteObj['type']=type;
		xajax_RemoveTemplate(deleteObj);
		
	}
	return false;
}
function openAuditTrail()
{
	var type = $("#type").val();	
	var	section= $("#sectionVal").val();	
	var	sub_section= $("#sub_sectionVal").val();
	var comp_main_id = $("#comp_main_id").val();	
	var headerAudit = window.open('airworthiness_centre_audit.php?section='+section+'&sub_section='+sub_section+'&type='+type+'&comp_main_id='+comp_main_id,'headerAudit',
								 'height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	headerAudit.focus();
}

function showDiv2(rowindex,flag,ev,view_flag)
{ 
	ctrl = eval("document.getElementById('view_recc"+rowindex+"')");
	var cords = mouseCoords(ev);
	var posLeft = cords.x-307;
	
	if(flag==1)
	{
		ctrl.style.visibility="visible";
		ctrl.style.display="block";
		ctrl.style.position="absolute";
		ctrl.style.width="300px";
		ctrl.style.background="#FFFFFF"; 
		ctrl.style.color="#000000";
		ctrl.style.border="#000000 solid 1px";
		ctrl.style.left=posLeft+'px';
		var im=document.getElementById("icon_"+rowindex).className;
		document.getElementById("icon_"+rowindex).className='todaymsg_ico_gray';
		if(im!="todaymsg_ico_gray")
		{
			xajax_viewnotes(rowindex);
		}
	}
	else
	{
		ctrl.style.visibility="hidden";
		ctrl.style.display="none";
	}
}
function mouseCoords(ev){
	if(ev.pageX || ev.pageY){
		return {x:ev.pageX, y:ev.pageY};
	}
	var scrOfX = 0, scrOfY = 0;
	if( typeof( window.pageYOffset ) == 'number' ) {
		scrOfY = window.pageYOffset;
		scrOfX = window.pageXOffset;
	} else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
		scrOfY = document.body.scrollTop;
		scrOfX = document.body.scrollLeft;
	} else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
		scrOfY = document.documentElement.scrollTop;
		scrOfX = document.documentElement.scrollLeft;
	}
	return {
		x:ev.clientX + scrOfX,
		y:ev.clientY + scrOfY
	};
}
//Now below functions For manage_hyperlink.php	
function fnManageHyperLnk(val)
{
	var type=$("#type").val();
	var comp_id=$("#comp_id").val();
	var client_id=$("#client_id").val();
	var comp_main_id=$("#comp_main_id").val();
	var section = $("#sectionVal").val();
	var tempval=$("#currentID").val().split('_');
	var category_id=tempval[1];
	var data_main_id=tempval[2];
	var HyperWin = window.open('airworthiness_centre.php?section='+section+'&type='+type+'&sub_section=1&comp_id='+comp_id+'&client_id='+client_id+'&comp_main_id='+comp_main_id+'&category_id='+category_id+'&data_main_id='+data_main_id+'&HYPERLINK','CentreWs','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	HyperWin.focus();	
}
function show_centre(val)
{
	resetCSCombo(0);
	$("#csifreame").attr("src","");		
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
		$("#csifreame").attr("src","");		
		if($("#comboDDiv"))
			$("#comboDDiv").attr('style','display:;');
			
		if(value==1 || value==0)
		{
			var comp_id= $("#component_id").val();
			GetCSLOV(comp_id,0,0,value);
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
function chkTab()
{
	var ComboVal=validateCSCombo();
	var chkVal = '';
	if($("#CSTabCombo_1").length>0 && $("#CSTabCombo_1").val()!=''){
		chkVal =$("#CSTabCombo_1").val();
	}
	
	var obj=document.getElementsByName('CSTabCombo[]');
	var lastElem= obj.length;
	
	if($("#centre_id").length>0 && $("#centre_id").val()==1){	
		if($("#CSTabCombo_"+lastElem).length>0 && $("#CSTabCombo_"+lastElem).val()!=0){
			
			getLoadingCombo(1);	
			var tempArr= new Array();
			var tempVal = $("#CSTabCombo_"+lastElem).val();
			if(tempVal!=""){
				tempArr = tempVal.split("|")
			}
									
			var linkIdval = $("#component_id").val();
			var airworthiFlag = $("#data_main_id").val();
			
			if(tempArr[2]!=1){
				linkIdval = tempArr[0];
			}
			var pageUrl = "manage_rows.php?section=ATTACH&link_id="+linkIdval+"&tab_id="+tempArr[1]+"&p_id="+tempArr[4]+"&Type="+tempArr[2]+"&SectionFlag=1&airworthiFlag="+airworthiFlag;						
			$("#csifreame").attr("src",pageUrl);					
		} else {
			$("#csifreame").attr("src",""); 
			getLoadingCombo(0);	
		}
	} else {
			$("#hdnRecIds").val("");
			getLoadingCombo(0);	
	}
	
}

function getDocHeight(doc) {
	
    doc = doc || document;
    // stackoverflow.com/questions/1145850/
    var body = doc.body, html = doc.documentElement;
    var height = Math.max( body.scrollHeight, body.offsetHeight, 
        html.clientHeight, html.scrollHeight, html.offsetHeight );
    return height;
}
function getDocWidth(doc) {
	
    doc = doc || document; 
    var body = doc.body, html = doc.documentElement;
    var Width = Math.max( body.scrollWidth, body.offsetWidth, 
        html.clientWidth, html.scrollWidth, html.offsetWidth );
		
    return Width;
}
function setIframeHeight(id) {
	
	
    var ifrm = document.getElementById(id);
    var doc = ifrm.contentDocument? ifrm.contentDocument: 
       ifrm.contentWindow.document;
	
	if(totalhyperlinkRows==0){
		ifrm.style.height = "160px;";		
		//$('#'+id).contents().find('body').css("background-color","#FFFFFF");
	} else{	
		ifrm.style.height = "500px";   
    	ifrm.style.height = getDocHeight( doc ) + 4 + "px";	
	 	ifrm.style.width = getDocWidth( doc ) + 4 + "px";
	    ifrm.style.visibility = 'visible';
	}
	getLoadingCombo(0);	
	
}
function openTabReport()
{
	var type = $("#type").val();
	var section = $("#sectionVal").val();
	var client_id = $("#client_id").val();	
	var client_id=$("#client_id").val();
	var comp_main_id=$("#comp_main_id").val();
	var airWorthiReport = window.open('airworthiness_centre.php?section='+section+'&type='+type+'&sub_section=8&master_flag=0&client_id='+client_id+'&comp_main_id='+comp_main_id,'airWorthiReport','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes')
	airWorthiReport.focus();	
	
}


function resetAttachCombo(startval)
{
	var valTab=$("[name='DivTab[]']").length;
	
	var objRemove = document.getElementById("ComboDiv");	
	for(var i=startval;i<=valTab;i++)
	{
		var divRemove =document.getElementById("DivTab_"+i);
		if(divRemove)
		{
			objRemove.removeChild(divRemove);
		}
	}
	
}
function getGroup(val)
{
	LastTabVal=val;
	var valarr=val.split('|');
	var CompVal=valarr[0];
	var TypeId=valarr[2];
	var TypeId=valarr[valarr.length-1];
	
	GetallCombo(TypeId,2,val,0)
}
function GetallCombo(TypeId,Level,CompVal,pid)
{
	if(CompVal!=0){
		var fval=CompVal.split("|");
		if(fval[0] && fval[1] && fval[2]){
			var tempObj={"link_id":fval[0],"tab_id":fval[1],"type":fval[2],"level":Level,"pid":pid};
			xajax_getDBTab(tempObj);
		}
	}

}
