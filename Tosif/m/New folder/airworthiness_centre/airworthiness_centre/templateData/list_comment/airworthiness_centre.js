// JavaScript Document
var current_filter="";
var xmlDoc;
var shortClient = '';
var SectionFlag='';
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
/*********************		Grid Functions		***********************/
function loadGrid()
{
	try
	{
		var main_id = document.getElementById("main_id").value;
		var params = "main_id="+main_id;
		var section = document.getElementById("section").value;
		var sub_section = document.getElementById("sub_section").value;
		
		var filter = '';
		if(filter != null)
		{
			 params = params+"&section="+section+"&sub_section="+sub_section+"&&act=GRID"+filter;
		}
		else
		{
			 params = params+"&section="+section+"&sub_section="+sub_section+"&act=GRID";
		}
		getGrid("airworthiness_centre.php",params,"divGrid");
	}
	catch(Error)
	{
		funError("loadGrid","Section : CS => List Comments,Main page Js Error <br/> ",Error.toString(),Error);		
	}
}

function getGrid(page,params,target)
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
					{
						xmlDoc = req.responseXML;
						renderGrid(xmlDoc,target);
					}
					catch(Error)
					{
						funError("renderGrid","Section : CS => List Comments,renderGrid() Error <br/> ",Error.toString(),Error);		
					}
					
					if(current_filter != "")
					{
						var ctrl = document.getElementById(current_filter);
						var pos = ctrl.value.length;
	
						if(ctrl.setSelectionRange)
						{
							ctrl.focus();
						}
						else if (ctrl.createTextRange) 
						{
							var range = ctrl.createTextRange();
							range.collapse(true);
							range.moveEnd('character', pos);
							range.moveStart('character', pos);
							range.select();
						}
					}
				}
			}
		}
		req.open("POST", strURL+'?t='+(new Date()), true);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		req.setRequestHeader("Content-length", params.length);
		req.send(params);
	}	
}

function renderGrid(xmlDoc,target)
{
	var table = '<table width="100%" style="line-height:18px;" cellpadding="3" cellspacing="1" class="tableMainBackground">';
	table += '<tr class="table_header_bg">';
	table += '<td width="11%" align="left" valign="top" style="color:#fff;">FLYdocs Comment Added By</td>';
	table += '<td width="11%" align="left" valign="top" style="color:#fff;">'+shortClient+' Comment added by</td>';
	table += '<td width="11%" align="left" valign="top" style="color:#fff;">Lessor Comment added by</td>';
	table += '<td width="19%" align="left" valign="top" style="padding-left:5px; color:#fff;">Comment</td>';
	table += '<td width="11%" align="left" valign="top" style="color:#fff;">Assign to FLYdocs User</td>';
	table += '<td width="11%" align="left" valign="top" style="color:#fff;">Assign to '+shortClient+' User</td>';
	table += '<td width="11%" align="left" valign="top" style="color:#fff;">Assign to Lessor Owner</td>'
	table += '<td nowrap  align="left" valign="top" style="color:#fff;">Sent as Private Note</td>';
	table += '<td width="15%" align="left" valign="top" style="color:#fff;">Date / Time</td>';
	table += '</tr>';
	
	// Grid Header finished here, Grid data starts,,,,
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
		var rowTag = xmlDoc.getElementsByTagName('row');
		var nid,note,n_type,n_owner,fly_user,vaa_user,lessor_user,n_date,private_note;
		
		if(rowTag.length>0)
		{
			for (var i=0;i<rowTag.length;i++)
			{ 
				nid = getNodeValue(rowTag[i],'id');
				note = getNodeValue(rowTag[i],'note');
				n_type = getNodeValue(rowTag[i],'n_type');
				n_owner = getNodeValue(rowTag[i],'n_owner');
				fly_user = getNodeValue(rowTag[i],'fly_user');
				vaa_user = getNodeValue(rowTag[i],'vaa_user');
				lessor_user = getNodeValue(rowTag[i],'lessor_user');
				n_date = getNodeValue(rowTag[i],'n_date');
				private_note = getNodeValue(rowTag[i],'private_note');
				
				var tr_color = '';
				if(n_type==1)
				{
					tr_color = '#DED7FD';
				}
				else if(n_type==2)
				{
					tr_color = '#FFE5F6';
				}
				else if(n_type==3)
				{
					tr_color = '#DDFFFE';
				}
				else
				{
					tr_color = "#DDFFFE";
				}
				
				if(fly_user==''){fly_user='-'}
				if(vaa_user==''){vaa_user='-'}
				if(lessor_user==''){lessor_user='-'}
				
				table += '<tr id="'+nid+'" bgcolor="'+tr_color+'">';
				
				table += '<td width="11%" align="left" valign="top">';
				if(n_type==0 || n_type==3)
				{ table += n_owner;}else{ table += '-';}
				table += '</td>';
				
				table += '<td width="11%" align="left" valign="top">';
				if(n_type==1){ table += n_owner;}else{ table += '-';}
				table += '</td>';
				
				table += '<td width="11%" align="left" valign="top">';
				if(n_type==2){ table += n_owner;}else{ table += '-';}
				table += '</td>';
				
				table += '<td width="19%" align="left" valign="top" style="padding-left:5px;">'+note.replace(/\n/gi, "<br>")+'</td>';
				table += '<td width="11%" align="left" valign="top">'+fly_user+'</td>';
				table += '<td width="11%" align="left" valign="top">'+vaa_user+'</td>';
				table += '<td width="11%" align="left" valign="top">'+lessor_user+'</td>'
				if(private_note==1)
				{
					var private_note_val="Yes";
				}
				else
				{
					var private_note_val="No";
				}
				table += '<td  align="left" valign="top">'+private_note_val+'</td>';
				table += '<td width="15%" align="left" valign="top">'+n_date+'</td>';
							
				table += '</tr>';
			}
		}
		else
		{
			table += '<tr><td colspan="8" align="center" valign="top" style="padding-left:5px; color:#fff;"><b>No Records Found...</b></td></tr>';
		}
	}
	
	table += "</table>";
	document.getElementById(target).innerHTML = table;
}

function filter_comments()
{
	var main_id = document.getElementById("main_id").value;
	var params = "main_id="+main_id;
	var section = document.getElementById("section").value;
	var sub_section = document.getElementById("sub_section").value;
	
	
	var filter = '';
	if(document.getElementById("comment_lov").value != '')
	{
		filter += '&n_type='+document.getElementById("comment_lov").value;
	}
	
	
	
	if(document.getElementById("owner_flydocs").value != '')
	{
		filter += '&fly_user='+document.getElementById("owner_flydocs").value;
	}
	
	if(document.getElementById("owner_vaa").value != '')
	{
		filter += '&vaa_user='+document.getElementById("owner_vaa").value;
	}
	
	if(filter != '')
	{
		 params = params+"&section="+section+"&sub_section="+sub_section+"&act=GRID"+filter;
	}
	else
	{
		 params = params+"&section="+section+"&sub_section="+sub_section+"&act=GRID";
	}
	getGrid("airworthiness_centre.php",params,"divGrid");
}

function reset_page()
{
	try
	{
		document.getElementById("comment_lov").value = 0;	
		document.getElementById("owner_flydocs").value = 0;
		document.getElementById("owner_vaa").value = 0;
		filter_comments();
	}
	catch(Error)
	{
		funError("reset_page","Section : CS => List Comments,reset_page() Js Error <br/> ",Error.toString(),Error);		
	}
}

function funError(FunctionName,msg,ErrorString,ErrorArray)
{
	alert(ErrorString);
	alert(FunctionName);
	alert(msg);
	xajax_jsError(FunctionName,msg,ErrorString,ErrorArray);
	var table = '<table width="100%" cellspacing="1" cellpadding="3" border="0" class="tableContentBG" >';
	table += '<tr>';
	table += '<td colspan="11" align="center"><strong>There is an issue in fetching record. Please Contact Administrator for further assistance.</strong></td>';
	table += '</tr>';
	table += "</table>";
	document.getElementById("divGrid").innerHTML = table;
}