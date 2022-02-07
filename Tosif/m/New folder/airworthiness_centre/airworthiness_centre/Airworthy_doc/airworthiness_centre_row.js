// JavaScript Document
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
					
					if(TempTable!='')
					{
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
			table += '<strong>No Records to Display..</strong>';
			table += '</td></tr>';
		}
	}
	else
	{
		table += '<div class="add_row" align="center" id="0" style="padding:10px; text-align:center;">';
		table += '<strong>No Records to Display..</strong>';
		table += '</div>';
	}
	table =mtable+table+'</table>';
	$("#divGird").html(table);
	
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
function getGridData(tabid)
{
	var tempGridTable ='';
	
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
			return false
			
			
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
function getRows(srNo,tab_id,rid)
{
	var temprowTable = '';
	
	var robj=new Object();
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
			groupStr='rel="group_'+robj[rid]['category_id']+'_'+robj[rid]['parent_id']+'"';
			if(robj[rid]['PrntID']!=0)
			rClass+="subHeader ";  
		}
		var class1 = (srNo%2==0)?" even":" odd";
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
					
					if(ColID=='responsibility' && robj[rid]['res_users'])
					{
						var respnsblt = robj[rid]['res_users'].split(',');
						searchStr='';
						
						for(r in respnsblt)
							searchStr += getUserFullname(respnsblt[r])+',';
						
						if((searchStr.toLowerCase()).indexOf(filterValObj[ColID].toLowerCase()) < 0)
						{
							flg=0;
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
					
					if(ColID=='itemid' && robj[rid]['Header_Row']==0 && robj[rid]['PrntID']!=0)
					{
						pad ='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
					}
					else if(ColID=='itemid' && robj[rid]['Header_Row']==1)
					{
						
						if(dataObj.countChild[robj[rid]['id']]>0)
						{
							
							pad='<img  id="img_'+robj[rid]['tab_id']+'_'+robj[rid]['id']+'" src="'+pathLink+'images/minus_active.jpg" onclick="show_hide(\''+robj[rid]['tab_id']+'\',\''+robj[rid]['id']+'\')" >&nbsp;';
					
					
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

function saveRow(tab_id,rec_id)
{
	
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
		var Header_Row = dataObj.rowData[tab_id][rec_id]['parent_id'];		
		
		if((filter_type=='checkbox' || filter_type=='status' || filter_type=='priority'  || filter_type=='notes') && Header_Row==0 )
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
				
				if(ColVal!=dataObj.rowData[tab_id][rec_id][ColID] && filter_type!='checkbox' &&  filter_type!='notes')
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
						
						var note="";
						if(document.getElementById("notes"+tab_id+rec_id+""))
							note = document.getElementById("notes"+tab_id+rec_id+"").value;
						
						ColID1='notes';
						saveObjEdit[ColID1]=new Object();
						saveObjEdit[ColID1]['note']=note;
						saveObjEdit[ColID1]['users']=xajax.getFormValues('frm');
					}
					catch(e)
					{
						alert(e);
					}
				}
				
		}
		else if((filter_type!='checkbox' && filter_type!='status' && filter_type!='priority'  && filter_type!='notes'))
		{
			if(ColID && ColID!='')
			{
				var idstr="editfield"+tab_id+"_"+ColID
				var currentColVal = $("#"+idstr).val();
				
				if(filter_type==2 && is_multiple==1)
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
				}
				
				if(currentColVal!=dataObj.rowData[tab_id][rec_id][ColID] && currentColVal!='Enter Text')
				{
					
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
	if(!$.isEmptyObject(saveObjEdit['columnVal'])){		
		xajax_Update(saveObjEdit,auditObj);
	} else{
		alert("Record Updated Successfully.");
		getLoadingCombo(0);
	}
	return true;
}


function updateRow(upObj)
{	
	try{
	var upID= '_'+upObj["whrId"];
	var tab_id= '_'+upObj["cat_id"];
	for(col_id in upObj['columnVal']){		
		var colVal = upObj['columnVal'][col_id];
		dataObj.rowData[tab_id][upID][col_id] = colVal;
	}
	doRow("",tab_id,upID);
	getLoadingCombo(0);		
	}catch(Error){
		funError("updateRow","Section : Airworthiness Centre => Component, Main page Js Error <br/> ",Error.toString(),Error);	
	}
}
