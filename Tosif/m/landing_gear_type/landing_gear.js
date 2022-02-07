// JavaScript Document
var xmlDoc ='';
var arrHeader = new Array();
var arrFilter = new Array();
var arrFilterVal = new Array();

var count = 0;

SimpleContextMenu.setup({'preventDefault':true, 'preventForms':false});
SimpleContextMenu.attach('class_dis', 'contextmenu_dis');
SimpleContextMenu.attach('class_act', 'contextmenu');

function fn_selectedClient(CiD){

	xajax_getAircraftType(CiD,'',true);
	
}
// function for load grid
function loadGrid()
{
	try
	{
	arrHeader[0] = "Clients";
	arrHeader[1] = "Landing Gear Type";
	arrHeader[2] = "Manufacturer";	
	arrHeader[3] = "Aircraft Type";
	//arrHeader[4] = "Also Assigned To";
	if(document.getElementById("no_of_modules"))
	{
		arrHeader[4] = "Number of Modules";
		arrHeader[5] = "Editable";
	}
	else
	{
		arrHeader[4] = "Editable";
	}
	
	arrFilter[0] = "fd_filter_airlines";
	arrFilter[1] = "fd_filter_GearType";
	arrFilter[2] = "fd_filter_S_menufacturer";
	arrFilter[3] = "fd_filter_S_AIRCRAFTTYPE";
	//arrFilter[4] = "fd_filter_also_assigned_to";
	
	var filter = "";
	for(var i=0;i<arrFilter.length;i++)
	{
		filter += "&"+arrFilter[i]+"="+((document.getElementById('fd_filter_'+arrFilter[i])) ? document.getElementById('fd_filter_'+arrFilter[i]).value : "");
		arrFilterVal[i] = ((document.getElementById('fd_filter_'+arrFilter[i])) ? document.getElementById('fd_filter_'+arrFilter[i]).value : "");
	}
	
	if(filter != null)
	{
		var params = "act=GRID"+filter;
	}
	else
	{
		var params = "act=GRID";	
	}	

	getGrid("master_type.php?mtype=GEAR",params,arrHeader,arrFilter,"divGrid",arrFilterVal);
	}
	catch(err) {
		exc_error("loadGrid","section : landing gear Type  <br/> ",err.toString(),err);
	}
}
// for xml response
function getGrid(page,params,arrHeader,arrFilter,target,arrFilterVal)
{
	var strURL= page;
	document.getElementById(target).innerHTML="";
	var req = getXMLHTTP();
	if (req)
	{
		req.onreadystatechange = function() 
		{
			if (req.readyState == 4)
			{
				if (req.status == 200) 
				{					
					try{
						xmlDoc = req.responseXML;
					renderGrid(arrHeader,arrFilter,xmlDoc,target,arrFilterVal);
					}catch (err){
						exc_error( 'getGrid', 'section: landing gear Type <br />', err.toString(), err );
					}
				}
			}
		}
		req.open("POST", strURL, true);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.setRequestHeader("Content-length", params.length);
		req.send(params);
	}	
}
// function for render grid
function renderGrid(arrHeader,arrFilter,xmlDoc,target,arrFilterVal)
{
	try
	{
	var table = '<table width="100%" cellspacing="1" cellpadding="3" border="0" class="tableContentBG" >';

	table += "<tr>";
	for(i=0; i<arrHeader.length;i++)
	{
		table += '<td align="left" class="tableCotentTopBackground" >'+arrHeader[i]+'</td>';
	}
	table += "</tr>";
	
	table += "<tr>";
	for(i=0; i<arrFilter.length;i++)
	{
		var valFilter;
		if(document.getElementById(arrFilter[i])){
			valFilter = document.getElementById(arrFilter[i]).value;
		}else {valFilter= "";}
		table += '<td align="left" class="tableCotentTopBackground"><input type="text" value="'+valFilter+'" name="'+arrFilter[i]+'" id="'+arrFilter[i]+'" onkeyup="return filter(this.id,event);" class="gridinput"></td>';
	}
	table += '<td align="left" class="tableCotentTopBackground"></td>';

	if(document.getElementById("no_of_modules"))
	{
		table += '<td align="left" class="tableCotentTopBackground"></td></tr>';
	}
	else
		table +='</tr>';
	
	var prnt = xmlDoc.getElementsByTagName('row');
	var Parent_id,Parent_geartype,Parent_gearmenuf,Parent_airtype,Parent_recuse,Parent_modules,valrecuse;
	var jclient;
	var SignleType;
	var assingedto;
	var airtCount;
	var ChkLevel;
	var singletypeassign = '';
	
	if(prnt.length>0)
	{
		for (i=0;i<prnt.length;i++)
  		{
			var flags = 0;
			var flg = 0;
			
			if(i%2 == 0){
				var clas = "odd";
			} else {
				var clas = "even";
			}
			
			Parent_id = getNodeValue(prnt[i],'id');
			Parent_airlines = getNodeValue(prnt[i],'airlines');
			Parent_geartype = getNodeValue(prnt[i],'geartype');
			Parent_gearmenuf = getNodeValue(prnt[i],'gearmenuf');
			Parent_airtype = getNodeValue(prnt[i],'airtype');
			Parent_modules = getNodeValue(prnt[i],'modules');
			Parent_recuse = getNodeValue(prnt[i],'recuse');
			ChkLevel = (UserLevel!=0)?Parent_recuse:0;
			var fParent_airtype = Parent_airtype.split("#");			
			airtCount = fParent_airtype.length;			
			if(airtCount>1)
			{				
				singletypeassign='';
				assingedto='';
			}
			else
			{
				if(fParent_airtype[0]!='')
				{
					SignleType = fParent_airtype[0].split("ASGNTO");
					singletypeassign = SignleType[0];
					assingedto = SignleType[1];
				}
				else
				{
					singletypeassign='';
					assingedto='';
				}
			}	
						
			if(Parent_recuse == 1) 
			{
				if(ChkLevel==0){clas += ' class_act';}else{clas +=' class_dis';}
				valrecuse = 'No';
			}
			else
			{
				if(ChkLevel==0){clas += ' class_act';}else{clas +=' class_act';}
				valrecuse = 'Yes';
			}	
			
			
			if(document.getElementById('fd_filter_GearType') || document.getElementById('fd_filter_S_menufacturer') || document.getElementById('fd_filter_S_AIRCRAFTTYPE'))
			{
				if((Parent_airlines).toLowerCase().indexOf((document.getElementById('fd_filter_airlines').value).toLowerCase()) == -1)
				{
					flags = 1;
				}
				if((Parent_geartype).toLowerCase().indexOf((document.getElementById('fd_filter_GearType').value).toLowerCase()) == -1)
				{
					flags = 1;
				}
				if((Parent_gearmenuf).toLowerCase().indexOf((document.getElementById('fd_filter_S_menufacturer').value).toLowerCase()) == -1)
				{
					flags = 1;
				}
				if((Parent_airtype).toLowerCase().indexOf((document.getElementById('fd_filter_S_AIRCRAFTTYPE').value).toLowerCase()) == -1)
				{
					flags = 1;
				}
				/*if((fParent_airtype[0]).toLowerCase().indexOf((document.getElementById('fd_filter_also_assigned_to').value).toLowerCase()) == -1)
				{
					flags = 1;
				}*/
			}
			
			var grupId ;
			if(flags == 0)
			{
				if(jclient != Parent_airlines)
				{
				
					jclient = Parent_airlines;
					table += '<tr style="background-color:#FFFFFF;" id="group'+Parent_id+'" >';
					table += '<td valign="top" align="left"  colspan="'+(arrHeader.length)+'"><a href="javascript: ShowTypeChild('+i+');"><img width="9" height="9" border="0" src="images/plus_inactive.jpg" id="ImgTypeId'+i+'"></a>&nbsp;&nbsp'+Parent_airlines;
					table +='<input type="hidden" id="typeTR'+grupId+'" value="'+count+'"></td>';
					table += '</tr>';					
					count = 0;
					grupId = i;
				}
				table += '<tr class="'+clas+'" align="left" onmouseover="javascript:MouseOver(this.id);" onmousedown="javascript:Edit2('+Parent_id+',\'child_'+grupId+"_"+count+'\','+ChkLevel+');" onmouseout="javascript:MouseOut(this.id,'+Parent_id+');" onclick="javascript:if(this.onmousedown){} else Edit2('+Parent_id+',\'child_'+grupId+"_"+count+'\','+ChkLevel+')" id="child_'+grupId+"_"+count+'" >';
				table += '<td width="" >&nbsp;&nbsp;</td>';
				table += '<td width="" >'+Parent_geartype+'</td>';
				table += '<td width="" >'+Parent_gearmenuf+'</td>';
				table += '<td width="">'+singletypeassign+'</td>';
				//table += '<td width="">'+assingedto+'</td>';
				if(document.getElementById("no_of_modules"))
				{
				table += '<td width="" >'+Parent_modules+'</td>';
				}
				table += '<td width="">&nbsp;&nbsp;'+valrecuse+'</td>';
				table += '</tr>';
				
				// For Multiple aircraft type Grid
				if(airtCount>1)
				{
					for(var k=0;k<airtCount;k++)
					{
						flg=0;
						sParent_assignedto = fParent_airtype[k].split("ASGNTO");						
						if(k%2 == 0){
						var clas_sub = "even";
						} else {
							var clas_sub = "odd";
						}
						if(document.getElementById('fd_filter_S_AIRCRAFTTYPE'))
						{
							if((sParent_assignedto[0]).toLowerCase().indexOf((document.getElementById('fd_filter_S_AIRCRAFTTYPE').value).toLowerCase()) == -1){
								flg=1;
							}
						}
						if(flg==0)
						{
							table += '<tr align="left" class="'+clas_sub+'">';
							table += '<td width=""></td>';
							table += '<td width=""></td>';
							table += '<td width=""></td>';
							table += '<td width="">'+sParent_assignedto[0]+'</td>';
							//table += '<td width="">'+sParent_assignedto[1]+'</td>';
							table += '<td width=""></td>';
							if(document.getElementById("no_of_modules"))
							{
								table += '<td width=""></td>';						
							}
							table += '</tr>';
						}
					}
				}
				count ++;
			}
 		}
	}
	table += "</table>";
	table +='<input type="hidden" id="typeTR'+grupId+'" value="'+count+'">';
	document.getElementById(target).innerHTML = table;
	
	}
	catch(err) {
		exc_error("renderGrid","section : langing_gear type <br/> ",err.toString(),err);
	}
}
// for set object intiation values
var currentId;
function objInit()
{
	var objField = new Array();
	objField['Add'] = document.getElementById("addBtn");
	objField['Edit'] = document.getElementById("editBtn");
  	objField['Delete'] = document.getElementById("deleteBtn");
	objField['archiveBtn'] = document.getElementById("archiveBtn");
  	objField['Save'] = document.getElementById("saveBtn");
 	objField['Reset'] = document.getElementById("resetBtn");
	objField['Link'] = document.getElementById("link_title");
	objField['Status'] = document.getElementById("status");
	objField['Manufacturer'] = document.getElementById("Manufacturer");
	objField['Act'] = document.getElementById("act");
	objField['Id'] = document.getElementById("id");
	objField['TabOld'] = document.getElementById("oldTab");
	return objField;
}
// for clear form values
function clearForm()
{
	if(document.getElementById("selclient").selectedIndex>0)
	{
		var clearType = ' <select name="AIRCRAFTTYPE" id="AIRCRAFTTYPE" multiple="multiple" size="15" disabled="disabled" /><option value="" disabled>[Select Aircraft Type]</option></select>';	
		document.getElementById("selclient").value = "0";
		document.getElementById("airType").innerHTML = clearType;
	}
	else
	{
		document.getElementById("AIRCRAFTTYPE").value = "0";
	}
	document.getElementById("GearType").value = "";
	document.getElementById("txtManufacturer").value = "";
	(document.getElementById("no_of_modules"))?document.getElementById("no_of_modules").value = '0':'';
}
// for enable form control
function enableForm()
{
	document.getElementById("selclient").disabled = "";
	document.getElementById("GearType").disabled = "";
	document.getElementById("AIRCRAFTTYPE").disabled = "";
	document.getElementById("txtManufacturer").disabled = "";
	(document.getElementById("no_of_modules"))?document.getElementById("no_of_modules").disabled = "":'';
}
// for disable form control
function disableForm()
{
	document.getElementById("selclient").disabled = "disabled";
	document.getElementById("GearType").disabled = "disabled";
	document.getElementById("AIRCRAFTTYPE").disabled = "disabled";
	document.getElementById("txtManufacturer").disabled = "disabled";
	(document.getElementById("no_of_modules"))?document.getElementById("no_of_modules").disabled = "disabled":'';
}

//function call on edit record
function Edit2(rid,ctrlId,recuse) 
{
   	 var Field =  new Array();
	 Field = objInit();
	 
	 recids=rid;
	 if(Field['Add'])
	 {
	 	 Field['Add'].className = "button";
		 Field['Add'].disabled = '';
	 }
	 if(Field['Edit'])
	 {
		 if(recuse == 1){
			 Field['Edit'].className = "disbutton";
			 Field['Edit'].disabled = "disabled";
		 } else {
			 Field['Edit'].className = "button";
			 Field['Edit'].disabled = '';
		 }
	 }
	
	 if(Field['Delete'])
	 {
		 if(recuse == 1){
			 Field['Delete'].className = "disbutton";
			 Field['Delete'].disabled = "disabled";
		 } else {
			 Field['Delete'].className = "button";
		 	 Field['Delete'].disabled = '';
		 }
	 }
	 if(Field['Save'])
	 {
		 Field['Save'].className = "disbutton";
		 Field['Save'].disabled = 'disabled';
	 }
	 clearForm();
	 disableForm();	
	document.getElementById("id").value = rid;	

	if(currentId!="" && document.getElementById(currentId))
	{
		document.getElementById(currentId).style.backgroundColor = "";
	}	
	if(elm.id != "") 
	{		
		elm.style.backgroundColor = "#FFCC99";
		elm.style.textAlign = "left";
		elm.style.cursor = "pointer";
	}
	currentId=ctrlId;
	xajax_SetForm(rid);
	
}
// on add button event
function fnAdd()
{
	clearForm();
	enableForm();
	var Field =  new Array();
	Field = objInit();
	if(Field['Add'])
	{
		Field['Add'].className = "disbutton";
		Field['Add'].disabled = 'disabled';
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
	if(Field['Save'])
	{
		Field['Save'].className = "button";
		Field['Save'].disabled = '';
	}
	//document.getElementById('selclient').multiple = true;
	Field['Act'].value = "ADD";
	return false;
}
// on Edit button event
function fnEdit()
{
	enableForm();
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
	Field['Act'].value = "EDIT";
	//chnageListType(document.getElementById('AIRCRAFTTYPE'));
	//document.getElementById('selclient').disabled='disabled';
	return false;
}
//for record delete
function fnDelete()
{
	try
	{
		document.getElementById("deleteBtn").value = "DELETE";
		if(confirm("Are you sure you want to delete this Landing Gear Type?"))
		{
			xajax_Delete(xajax.getFormValues('form1'));
		}
		return false;
	}
	 catch(err) {
		exc_error("fnDelete","section : Gear Type  <br/> ",err.toString(),err);
	}
}
// for saving record
function fnSave()
{
	try
	{
	if(document.getElementById("selclient").value == 0)
	{		
		alert('Please Select Client.');
		document.getElementById("selclient").focus();
		return false;
	}
	if(document.getElementById("GearType").value == '')
	{		
		alert('Please Enter Type.');
		document.getElementById("GearType").focus();
		return false;
	}
	
	var gearType = document.getElementById("GearType").value;
	var gearType_n = spaceTrim(gearType);
	if(gearType_n == '')
	{	
		alert('Only Spaces are not allowed in Landing Gear Type. Please Enter Landing Gear Type.');
		document.getElementById("GearType").focus();
		return false;
	}
	
	if((document.getElementById("AIRCRAFTTYPE").value == '0' || document.getElementById("AIRCRAFTTYPE").value == '') && !document.getElementById("AIRCRAFTTYPE").disabled)
	{
		alert('Please Select AirCraft Type.');
		document.getElementById("AIRCRAFTTYPE").focus();
		return false;
	}
	if(document.getElementById("txtManufacturer").value == '')
	{
		alert('Please Enter Manufacturer.');
		document.getElementById("txtManufacturer").focus();
		return false;
	}
	
	var manufact = document.getElementById("txtManufacturer").value;
	var manufact_n = spaceTrim(manufact);
	if(manufact_n == '')
	{
		alert('Only Spaces are not allowed in Manufacturer. Please Enter Manufacturer.');
		document.getElementById("txtManufacturer").focus();
		return false;
	}
	
	if(document.getElementById("no_of_modules") && document.getElementById('divmodules2').style.display=='')
	{
		if(document.getElementById("no_of_modules").value == '0' || document.getElementById("no_of_modules").value == '')
		{
			alert('Please Select Number Of Modules.');
			document.getElementById("no_of_modules").focus();
			return false;
		}
	}

	
	var selectedArray ="";
	  var sep = "";
	  var selObj = document.getElementById('AIRCRAFTTYPE');
	  for (var i=0; i<selObj.options.length; i++) {
		if (selObj.options[i].selected) {
		  selectedArray += sep+selObj.options[i].value;
		  sep = ",";
		}
	  }
	 document.getElementById('HidAirTypeid').value = selectedArray;
	 
	 
	if(document.getElementById("act").value == "ADD")
	{
		xajax_Save(xajax.getFormValues('form1'));
	}
	else if(document.getElementById("act").value == "EDIT")
	{
		xajax_Update(xajax.getFormValues('form1'));
	}
	else if(document.getElementById("act").value == "EDIT_MUL")
	{
		window.open('master_type.php?recid='+document.getElementById('id').value+'&aircraft_id='+document.getElementById('HidAirTypeid').value+'&client_id='+document.getElementById('selclient').value+'&orglandingtype='+document.getElementById('Org_GearType').value+'&landingtype='+document.getElementById('GearType').value+'&act=show&mtype=GEAR','','height=550px,width=650px');
	}
	return false;
	}
	catch(err) {
		exc_error("fnSave","section : landing gear Type  <br/> ",err.toString(),err);
	}
}
// function which reset form values
function fnReset()
{
	clearForm();
	disableForm();
	var Field =  new Array();
	Field = objInit();
	if(Field['Add'])
	{
		Field['Add'].className = "button";
		Field['Add'].disabled = '';
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
	if(Field['Save'])
	{
		Field['Save'].className = "disbutton";
		Field['Save'].disabled = 'disabled';
	}	
	Field['Reset'].className = "button";
	Field['Act'].value = "";
	Field['Id'].value = "";
	return false;
}
function filter(fid,e)
{
	if(e.keyCode == 13)
	{
		renderGrid(arrHeader,arrFilter,xmlDoc,"divGrid",arrFilterVal);
		return false;
	}
}
function getAircraftType(alrlinesId)
{
	xajax_getAircraftType(alrlinesId);
}
// on multiple edit
function fnEdit_Mul()
{
	enableForm();
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
	Field['Act'].value = "EDIT_MUL";
	document.getElementById('AIRCRAFTTYPE').disabled='disabled';
	document.getElementById('selclient').disabled='disabled';
	return false;
}
//copy functionality popup
function fnCopy()
{
	enableForm();
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
	window.open('master_type.php?recid='+document.getElementById('id').value+'&aircraft_id='+document.getElementById('HidAirTypeid').value+'&client_id='+document.getElementById('selclient').value+'&orglandingtype='+document.getElementById('Org_GearType').value+'&landingtype='+document.getElementById('GearType').value+'&copyto=Copy_New_Rec&act=show&mtype=GEAR','','height=550px,width=650px,resizable=yes,scrollbars=yes');
	//Field['Act'].value = "COPY";
	return false;
}

function update_val(ToVal){
	var rec_id = '';
	var cli_id = '';
	for(i=1;i<=ToVal;i++){
		if(document.getElementById('cli_tick_'+i))
		{
			if(document.getElementById('cli_tick_'+i).checked){
				
				if(rec_id == ''){
					rec_id = document.getElementById('rec_id_'+i).value;
					cli_id = document.getElementById('cli_id_'+i).value;
				} else {
					rec_id += ','+document.getElementById('rec_id_'+i).value;
					cli_id += ','+document.getElementById('cli_id_'+i).value;
				}
			}
		}
	}
	window.self.close();
	window.opener.save_mul(rec_id,cli_id);
}
// for copy functionality
function copy_val(ToVal){
	var rec_id = '';
	for(i=1;i<=ToVal;i++){
		if(document.getElementById('cli_tick_'+i).checked){
			
			if(rec_id == ''){
				rec_id = document.getElementById('cli_id_'+i).value;
			} else {
				rec_id += ','+document.getElementById('cli_id_'+i).value;
			}
			
		}
	}
	
	window.opener.save_copy(rec_id);
	window.self.close();
}
// for saving multiple record
function save_mul(ToRecID,ToCliID){
	
	xajax_Update_mul(xajax.getFormValues('form1'),ToRecID,ToCliID);

}

function save_copy(ToRecID){
	
	xajax_CopyNew(xajax.getFormValues('form1'),ToRecID);

}



function getLoadingCombo(flg)
{
var elm = document.getElementById("LoadingDivCombo");

	if(flg ==1)
	{
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

// function for audit trail functionality
function fnaudit_master(subid,title)
{ 
	winWidth = 800; 
	winheight = 800;
	
	if (screen){ 
	   winWidth = screen.width;
	   winHeight = screen.height;
	}
	
	var newwidnow=window.open("manage_audit_trail.php?adt=MASTER_ADT&sublinkId="+subid+"&title="+title,'newwidnow1','toolbar=no,location=no,scrollbars=yes,resizable=yes,width='+winWidth+',height='+winHeight+',left=0,top=0');
	newwidnow.focus();
}
function exc_error(FunctionName,msg,ErrorString,ErrorArray)
{
	xajax_excJsError(FunctionName,msg,ErrorString,ErrorArray);
	var table = '<table width="100%" cellspacing="1" cellpadding="3" border="0" class="tableContentBG" >';
	table += '<tr>';
	table += '<td colspan="11" align="center"><strong>There is an issue in fetching record. Please Contact Administrator for further assistance.</strong></td>';
	table += '</tr>';
	table += "</table>";
	document.getElementById(target).innerHTML = table;
}

function spaceTrim(name)
{return name.replace(/^\s+|\s+$/g, '');};