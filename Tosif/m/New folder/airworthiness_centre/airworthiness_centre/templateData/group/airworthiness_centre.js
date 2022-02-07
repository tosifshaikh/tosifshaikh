// JavaScript Document
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
function loadGrid()
{
	try
	{	var arrHeader = new Array();
		var arrFilter = new Array();
		
		arrHeader[0] = "Display Order";
		arrHeader[1] = "Group Name";
		arrHeader[2] = "Show Group to Main User";
		//arrHeader[3] = "Show Group to Third Party User";
		arrHeader[3] = "Default FLYdoc Group";
		arrHeader[4] = "Use as FLYdoc Reference";
		//arrHeader[6] = "Copy to Next Group Order";
		
		var TabId = document.getElementById("TabId").value;
		var section = document.getElementById("section").value;
		var sub_section = document.getElementById("sub_section").value;
		var comp_main_id= document.getElementById("comp_main_id").value;
		
		var params = "comp_main_id="+comp_main_id+'&section='+section+'&sub_section='+sub_section;
		params = params+"&act=GRID";		
		getGrid("airworthiness_centre.php",params,arrHeader,"divGrid");
	}
	catch(Error)
	{		alert(Error)
		funError("loadGrid","Section : MIF => Manage Document Groups,Main page Js Error <br/> ",Error.toString(),Error);		
	}
}

function getGrid(page,params,arrHeader,target)
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
					try
					{	var xmlDoc = req.responseXML;
						renderGrid(arrHeader,xmlDoc,target);
					}
					catch(Error)
					{
						alert(Error)
						funError("renderGrid","Section : MIF => Manage Document Groups,Issue in renderGrid.<br/> ",Error.toString(),Error);
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

function renderGrid(arrHeader,xmlDoc,target)
{
	var table = '<table width="100%" border="0" cellspacing="1" cellpadding="3" class="tableContentBG">';
	table += '<tr>';
	for(i=0; i<arrHeader.length;i++)
	{
		if(i==0 || i==1)
		{
			al = "left";	
		}
		else
		{
			al = "center";
		}
		
		table += '<td align="'+al+'" class="tableCotentTopBackground" >'+arrHeader[i]+'</td>';
	}
	table += "</tr>";
	
	var rootTag = xmlDoc.getElementsByTagName('root');
	var total = getNodeValue(rootTag[0],'total');
	var err = xmlDoc.getElementsByTagName("error");
	if(err.length>0)
	{
		table += '<tr>';
		table += '<td colspan="6" align="center"><strong>'+getNodeValue(err[0],'msg')+'</strong></td>';
		table += '</tr>';
	}
	else
	{
		var prnt = xmlDoc.getElementsByTagName('row');
		var Parent_id,linkid,tabid,boxname,mainuser_flag,clientuser_flag,print_flag,displayorder,third_party_flag,default_flydoc_group,copyToNextOrder;
		
		if(prnt.length>0)
		{
			for (i=0;i<prnt.length;i++)
			{
				Parent_id = getNodeValue(prnt[i],'id');
				tabid = getNodeValue(prnt[i],'tabid');
				boxname = getNodeValue(prnt[i],'boxname');
				mainuser_flag = getNodeValue(prnt[i],'mainuser_flag');
				clientuser_flag = getNodeValue(prnt[i],'clientuser_flag');
				flyRef_flag = getNodeValue(prnt[i],'flyRef_flag');
				default_flydoc_group = getNodeValue(prnt[i],'default_flydoc_group');
				copyToNextOrder = ((getNodeValue(prnt[i],'copy_to_next_order')==0)?'N/A':getNodeValue(prnt[i],'copy_to_next_order'));

				displayorder = getNodeValue(prnt[i],'displayorder');
				third_party_flag = 1;
				
				if(i%2 == 0){
					var clas = "even";
				} else {
					var clas = "odd";
				}
	
				table += '<tr name="TrId[]" class="'+clas+'" id="TrId'+i+'" onMouseOver="javascript:MouseOver(\'TrId'+i+'\')" onMouseOut="javascript:MouseOut(\'TrId'+i+'\',\''+clas+'\')" onclick="DeptEdit('+Parent_id+',\''+clas+'\',\''+i+'\')">';
				table += '<td style="padding-left:5px;">'+displayorder+'</td>';
				table += '<td>'+boxname+'</td>';
						
				if(mainuser_flag == 1){ checked_main = 'checked="checked"'; } else { checked_main = " "; }
				table += '<td  align="center"><input type="checkbox" name="mainuser_flag'+Parent_id+'" id="mainuser_flag'+Parent_id+'" value="'+Parent_id+'" onclick="" '+checked_main+' /></td>';
				
				/*static changes */
				
				//if(clientuser_flag == 1){ checked_main = 'checked="checked"'; } else { checked_main = " "; }
				//table += '<td  align="center"><input type="checkbox" name="clientuser_flag'+Parent_id+'" id="clientuser_flag'+Parent_id+'" value="'+Parent_id+'" onclick="" '+checked_main+' /></td>';
			
				if(default_flydoc_group == 1){ checked_fg = 'checked="checked"'; } else { checked_fg = ""; }
				table += '<td  align="center"><input  type="radio" name="default_group_flag"  id="default_group_flag'+Parent_id+'"  value="'+Parent_id+'" onclick="setflagForBox(\''+Parent_id+'\',\'D\',this);" '+checked_fg+' /></td>';
			
	
				if(flyRef_flag == 1){ checked_fly = 'checked="checked"'; } else { checked_fly = " "; }
				table += '<td  align="center"><input type="checkbox" name="flyRef_flag'+Parent_id+'" id="flyRef_flag'+Parent_id+'" value="'+Parent_id+'" onclick="" '+checked_fly+' /></td>';
								
				//table += '<td  align="center">'+copyToNextOrder+'</td>';
				
				/*end static changes */
				table += '</tr>';
			}
		} else {
			
			table += '<tr>';
		table += '<td colspan="6" align="center"><strong>No Records Found.</strong></td>';
		table += '</tr>';
		}
	}
	table += "</table>";
	document.getElementById(target).innerHTML = table;
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


function parent_refresh()
{
	window.parent.location.reload();
}

function validate()
{
	 if(document.getElementById("gname").value =="")
	 {
		 alert("Please Enter Group Name.")
		 document.getElementById("gname").focus();
		 return false;
	 }
	 if(spaceTrim(document.getElementById("gname").value) =="")
	 {
		 alert("Space not Allow")
		 document.getElementById("gname").focus();
		 return false;
	 }
	 if (document.getElementById("displayorder").value =="")
	 {
		 alert("Please select Display Order.")
		 document.getElementById("displayorder").focus();
		 return false;
	 }
	 document.getElementById("copyToNextOrder").value=0;
	 if (document.getElementById("copyToNextOrder") && document.getElementById("copyToNextOrder").value =="")
	 {
		 alert("Please select copy to next order.")
		 document.getElementById("copyToNextOrder").focus();
		 return false;
	 }
	  
	 var TabId = document.getElementById("TabId").value;
	 xajax_saveCRBOX(xajax.getFormValues("form1"));
	 return false;
}
 
function DeptEdit(id,Class,Index)
{
	try
	{
		
		getSelected(Index,Class);
		
		
		var elm = document.getElementsByName("TrId[]");
		var trClass="";
		
		for(i=0;i<elm.length;i++)
		{
			if(i%2 == 0)
			{
				trClass="even";
			}
			else
			{
				trClass="odd";
			}
			
			if(i == Index)
			{
				elm[i].className=Class+" marked";
			}
			else
			{
				elm[i].className=trClass;
			}
		}
		var makeit=0;
		var default_flydoc_group;
		var clientmakeit=0;
		if(document.getElementById("mainuser_flag"+id).checked==true)
		{
			makeit=1;
		}
		else
		{
			makeit=0;
		}
		if(document.getElementById("clientuser_flag"+id) && document.getElementById("clientuser_flag"+id).checked==true)
		{
			clientmakeit=1;
		}
		else
		{
			clientmakeit=0;
		}
		
		if(document.getElementById("flyRef_flag"+id).checked==true)
		{
			flyFlag=1;
		}
		else
		{
			flyFlag=0;
		}
		 if(document.getElementById("default_group_flag"+id).checked==true)
		 {
		 	default_flydoc_group=1;  	
		 }
		 else 
		 {
			 default_flydoc_group=0;
		 }
	 
		getLoadingCombo(1);
		
		xajax_setCRBOX(id,makeit,clientmakeit,flyFlag,default_flydoc_group);
		
		return false;
	}
	catch(e)
	{
		alert("error");
	}
}

function setActionRole(arg)
{

  var objAdd = document.getElementById("addBtn");
  var objEdit = document.getElementById("editBtn");
  var objDelete = document.getElementById("deleteBtn");
  var objSave = document.getElementById("saveBtn");
  var objReset = document.getElementById("resetBtn");
  

  if(objAdd)
  {
	switch(arg)
	{
		case 'Add' :	
		
					
					var totalRecords = document.getElementById("totalRecords").value;
					
					totalRecords =  eval(totalRecords)+1;
					var disporderStr = '<select name="displayorder" id="displayorder" style="width:90px;min-width: 0px;" disabled><option value="">-Select-</option>';
					for(var i=1;i<=totalRecords;i++)
					{
						disporderStr = disporderStr+'<option value="'+i+'">'+i+'</option>';
					}
					document.getElementById("displayorderTD").innerHTML=disporderStr;
					
					var maxtotalRecords = document.getElementById("maxtotalRecords").value;
					maxtotalRecords =  eval(maxtotalRecords)+1
					var disporderStr = '<select name="copyToNextOrder" id="copyToNextOrder" style="width:90px;min-width: 0px;" disabled><option value="">-Select-</option>';
					for(var i=1;i<=maxtotalRecords;i++)
					{
						disporderStr = disporderStr+'<option value="'+i+'">'+i+'</option>';
					}
					disporderStr = disporderStr+'<option value="0" >N/A</option>';
					if(document.getElementById("copyToNextOrderTD"))
					document.getElementById("copyToNextOrderTD").innerHTML=disporderStr;
					
					document.getElementById("act").value = arg;
					document.getElementById("gname").disabled=false;
					document.getElementById("displayorder").disabled=false;
					document.getElementById("gname").value="";
					
					if(document.getElementById("copyToNextOrder"))
					document.getElementById("copyToNextOrder").disabled=false;
					
					objAdd.src = "./images/addButton_dis.png";
					objAdd.disabled = 'disabled';
										
					objAdd.className = "disbutton";
					objAdd.disabled = 'disabled';
					objEdit.className = "disbutton";
					objEdit.disabled = 'disabled';
					objDelete.className = "disbutton";
					objDelete.disabled = 'disabled';
					objSave.className = "button";
					objSave.disabled = '';
					objReset.className = "button";
					
					
				break;
		case 'Edit' :
		
					if(document.getElementById("id").value)
					{
						
						var totalRecords = document.getElementById("totalRecords").value;
						var displayorderHid = eval(document.getElementById("displayorderHid").value);	
						var disporderStr = '<select name="displayorder" id="displayorder" style="width:90px;min-width: 0px;" disabled><option value="">-Select-</option>';
						for(var i=1;i<=totalRecords;i++)
						{
							if(i==displayorderHid){selectStr="selected";}else{selectStr="";}
							disporderStr = disporderStr+'<option value="'+i+'" '+selectStr+'>'+i+'</option>';
						}
						document.getElementById("displayorderTD").innerHTML=disporderStr;
						
						var maxtotalRecords = document.getElementById("maxtotalRecords").value;
						var copyToNextOrderHid = eval(document.getElementById("copyToNextOrderHid").value);	
						var disporderStr = '<select name="copyToNextOrder" id="copyToNextOrder" style="width:90px;min-width: 0px;" disabled><option value="">-Select-</option>';
						for(var i=1;i<=maxtotalRecords;i++)
						{
							if(i==copyToNextOrderHid){selectStr="selected";}else{selectStr="";}
							disporderStr = disporderStr+'<option value="'+i+'" '+selectStr+'>'+i+'</option>';
						}
						if(0==copyToNextOrderHid){selectStr="selected";}else{selectStr="";}
						disporderStr = disporderStr+'<option value="0" '+selectStr+'>N/A</option>';
						
						if(document.getElementById("copyToNextOrderTD"))
						document.getElementById("copyToNextOrderTD").innerHTML=disporderStr;
						
						document.getElementById("act").value = "Edit";	
						document.getElementById("displayorder").disabled=true;
						
						if(document.getElementById("copyToNextOrder"))
						document.getElementById("copyToNextOrder").disabled=true;
						
						document.getElementById("gname").disabled=true;
						objAdd.className = "button";
						objAdd.disabled = '';
						objEdit.className = "button";
						objEdit.disabled = '';
						objDelete.className = "button";
						objDelete.disabled = '';
						objSave.className = "disbutton";
						objSave.disabled = 'disabled';
						objReset.className = "button";						
					
					}
					else
						alert("Please Select Record from below grid");
						
				break;
		case 'EditMisc' :
		
					if(document.getElementById("id").value)
					{
						
						var totalRecords = document.getElementById("totalRecords").value;
						var displayorderHid = eval(document.getElementById("displayorderHid").value);	
						var disporderStr = '<select name="displayorder" id="displayorder" style="width:90px;min-width: 0px;" disabled><option value="">-Select-</option>';
						for(var i=1;i<=totalRecords;i++)
						{
							if(i==displayorderHid){selectStr="selected";}else{selectStr="";}
							disporderStr = disporderStr+'<option value="'+i+'" '+selectStr+'>'+i+'</option>';
						}
						
						document.getElementById("displayorderTD").innerHTML=disporderStr;
						
						var maxtotalRecords = document.getElementById("maxtotalRecords").value;
						var copyToNextOrderHid = eval(document.getElementById("copyToNextOrderHid").value);	
						
						var disporderStr = '<select name="copyToNextOrder" id="copyToNextOrder" style="width:90px;min-width: 0px;" disabled><option value="">-Select-</option>';
						for(var i=1;i<=maxtotalRecords;i++)
						{
							if(i==copyToNextOrderHid){selectStr="selected";}else{selectStr="";}
							disporderStr = disporderStr+'<option value="'+i+'" '+selectStr+'>'+i+'</option>';
						}
						if(0==copyToNextOrderHid){selectStr="selected";}else{selectStr="";}
						disporderStr = disporderStr+'<option value="0" '+selectStr+'>N/A</option>';
						
						if(document.getElementById("copyToNextOrderTD"))
						document.getElementById("copyToNextOrderTD").innerHTML=disporderStr;
						
						document.getElementById("act").value = "Edit";	
						document.getElementById("displayorder").disabled=true;
						document.getElementById("copyToNextOrder").disabled=true;
						document.getElementById("gname").disabled=true;
						objAdd.className = "button";
						objAdd.disabled = '';
						objEdit.className = "button";
						objEdit.disabled = '';
						objDelete.className = "disbutton";
						objDelete.disabled = 'disabled';
						objSave.className = "disbutton";
						objSave.disabled = 'disabled';
						objReset.className = "button";											
					}
					else
						alert("Please Select Record from below grid");
						
				break;		
				
		case 'EditRec' :
		
					if(document.getElementById("id").value)
					{
						document.getElementById("act").value = "Edit";
						document.getElementById("gname").disabled=false;
						document.getElementById("displayorder").disabled=false;
						
						if(document.getElementById("copyToNextOrder"))
						document.getElementById("copyToNextOrder").disabled=false;
									
						objAdd.className = "disbutton";
						objAdd.disabled = 'disabled';
						objEdit.className = "disbutton";
						objEdit.disabled = 'disabled';
						objDelete.className = "disbutton";
						objDelete.disabled = 'disabled';
						objSave.className = "button";
						objSave.disabled = '';
						objReset.className = "button";											
					
					}
					else
						alert("Please Select Record from below grid");
						
				break;	
		
		case 'Reset' :	
					if(document.getElementById("act"))
						document.getElementById("act").value = "";	
					if(document.getElementById("id"))
						document.getElementById("id").value = "";	
		
				    objAdd.className = "button";
					objAdd.disabled = '';
					objEdit.className = "disbutton";
					objEdit.disabled = 'disabled';
					objDelete.className = "disbutton";
					objDelete.disabled = 'disabled';
					objSave.className = "disbutton";
					objSave.disabled = 'disabled';
					objReset.className = "button";
					document.getElementById("gname").value="";
					document.getElementById("gname").disabled=true;
					document.getElementById("displayorder").disabled=true;
					
					if(document.getElementById("copyToNextOrder"))
					document.getElementById("copyToNextOrder").disabled=true;
					
					document.getElementById("hasDocInside").value = '';
					var disporderStr = '<select name="displayorder" id="displayorder" style="width:90px;min-width: 0px;" disabled><option value="">-Select-</option></select>';
					document.getElementById("displayorderTD").innerHTML=disporderStr;
					var disporderStr = '<select name="copyToNextOrder" id="copyToNextOrder" style="width:90px;min-width: 0px;" disabled><option value="">-Select-</option></select>';
					
					if(document.getElementById("copyToNextOrderTD"))
					document.getElementById("copyToNextOrderTD").innerHTML=disporderStr;
				break;		
		default : 	 
					
					
					objAdd.className = "button";
					objEdit.className = "disbutton";
					objEdit.disabled = 'disabled';
					objDelete.className = "disbutton";
					objDelete.disabled = 'disabled';
					objSave.className = "disbutton";
					objSave.disabled = 'disabled';
					objReset.className = "button";					
					document.getElementById("gname").value="";
				break;
	}
  }
  return false;
}
function deptdeleteFun()
{
	
	if(document.getElementById("id").value)
	{
		if(document.getElementById("hasDocInside").value=='N')
		{
			var ans = confirm("Are you sure want to Delete this record?");
			if(ans)
				xajax_DeptdeCRBOX('currentstatusboxlist.php',xajax.getFormValues('form1'),'PageContent')
		}
		else if(document.getElementById("hasDocInside").value=='Y')
		{
			var ans = confirm("This Document Group has documentation inside, are you sure you want to continue?");
			if(ans)
				xajax_DeptdeCRBOX('currentstatusboxlist.php',xajax.getFormValues('form1'),'PageContent')
		}
	}
	else
		alert("Please Select Record from below grid");
}
function setflagForBox(id,whichflag,obj)
{
	var makeit=0;
	if(obj.checked==true)
	{
		makeit=1;
	}
	if(obj.checked==false)
	{
		makeit=0;
	}
	//xajax_setfalgforbox(id,whichflag,makeit);
}

function getSelected(Index,Class)
{
	var elm = document.getElementsByTagName("tr");
	var j=0,elm1="";
	for(i=0;i<elm.length;i++)
	 {
		  if(elm[i].getAttribute('name') == "TrId[]")
		   {
			     if(j%2 == 0)
				   {
				   trClass="even";
				   }
				   else
				   {
				   trClass="odd";
				   }
				   
				   if(j == Index)
				   {
                    elm1 = eval("document.getElementById('TrId"+Index+"')");
					TRID = elm1.id;
					elm1.className = Class+" marked";						
				   }
				   else
				   {				
				   elm1 = eval("document.getElementById('TrId"+j+"')");
				   elm1.className = trClass;
				   }
				j++;
		   }
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
	
	document.getElementById("divGrid").innerHTML = table;
}
function callArchive()
{
	var sec_id = document.getElementById('id').value;
	document.setarchive.hdnarchive.value=sec_id;
	var client = window.opener.window.opener.document.getElementById('selclient').value;
	var serial_no = document.getElementById("TabId").value;
	
	var archwidnow=window.open("set_for_archive.php?sec=Int_Group&type=8&serial_no="+serial_no+"&client="+client,'archwidnow','toolbar=no,location=no,scrollbars=yes,resizable=yes,width=800,height=370,left=0,top=0');
	archwidnow.focus();
}
function spaceTrim(name)	//for prevent only blank space in input [-mec]
{return name.replace(/^\s+|\s+$/g, '');};
function Internal_file_Audit(TabId,clientid)
{  
	var type = 1;//document.getElementById("type").value;	
	var	section= 3;//document.getElementById("sectionVal").value;	
	var	sub_section=5;// document.getElementById("sub_sectionVal").value;
	var comp_main_id = document.getElementById("comp_main_id").value;
	
	var groupAudit = window.open('airworthiness_centre_audit.php?section='+section+'&sub_section='+sub_section+'&type='+type+'&comp_main_id='+comp_main_id,'groupAudit',
								 'height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	groupAudit.focus();
	//open_new_window('manage_audit_trail.php?adt=INT_DOC_ADT&TabId='+TabId+'&clientId='+clientid+'&sec=groupsec','newwidnow');
}