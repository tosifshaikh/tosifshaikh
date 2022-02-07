// Static Array for Workapck
var WorkpackId_Arr = new Array();
WorkpackId_Arr = [501,502,503,504,505,506];

var TimeoutMinutes = 60;
function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    }
    else
        var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function getSessionTimeout() {
    var xmlhttp;
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    }
    else {// code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var strDate = xmlhttp.responseText;
			
            if(strDate=='1')
			{
				 document.location.href = 'logout.php?ses_out=1';
			}
			var arrDate = String(strDate).split(',');
			
            if (arrDate.length == 6) {
                var sesDate = new Date(arrDate[0], arrDate[1], arrDate[2], arrDate[3], arrDate[4], arrDate[5]);
                CheckRemSecs(sesDate);
            }
			
        }
    }
    xmlhttp.open("GET", "checkSession.php", true);
    xmlhttp.send();
}

function CheckSession() {
    var Year, Month, date, Hours, Mins, Secs;
    Year = readCookie('FLYdocsSessionYear');
    Month = readCookie('FLYdocsSessionMonth');
    date = readCookie('FLYdocsSessionDay');
    Hours = readCookie('FLYdocsSessionHours');
    Mins = readCookie('FLYdocsSessionMinutes');
    Secs = readCookie('FLYdocsSessionSeconds');

    if (Year == null && Month == null && date == null) {
        getSessionTimeout();
    }
    else {
        var sesDate = new Date(Year, Month, date, Hours, Mins, Secs);
		CheckRemSecs(sesDate);
    }
   
}

function CheckRemSecs(sesDate){
 var curDate = new Date();
    var remSecs = Math.floor((sesDate.getTime() - curDate.getTime()) / (1000));
 
    if (remSecs <= 0) {
        document.location.href = 'logout.php?ses_out=1';
    }
    else {
        if (remSecs > 10) {
            setTimeout("CheckSession();", (remSecs - 10)*1000);
        }
        else {
            setTimeout("CheckSession();", 1000);
        }
    }
}

function updateCookie()
{
	var curDate = new Date();
	createCookie("FLYdocsSessionYear", curDate.getFullYear(), 1);
	createCookie("FLYdocsSessionMonth", curDate.getMonth(), 1);
	createCookie("FLYdocsSessionDay", curDate.getDate(), 1);
	createCookie("FLYdocsSessionHours", curDate.getHours(), 1);
	createCookie("FLYdocsSessionMinutes", curDate.getMinutes() + TimeoutMinutes, 1);
	createCookie("FLYdocsSessionSeconds", curDate.getSeconds(), 1);
}
//updateCookie();
setTimeout("CheckSession();", (TimeoutMinutes - 1)*60*1000);


// JavaScript Document
var CSlevel=0;
var currentId="";
var syswidth =630;
var syshieght =460;
if (document.body && document.body.offsetWidth) 
{
	 syswidth = document.body.offsetWidth;
	 syshieght = document.body.offsetHeight;
}
if (window.innerWidth && window.innerHeight) 
{
	 syswidth = window.innerWidth;
	 syshieght = window.innerHeight;
}
syswidth =  parseInt(syswidth)/1.5;
syshieght = parseInt(syswidth)/1.5;

function getXMLHTTP()
{ 	/**
	*fuction to return the xml http object
	*/
	var xmlhttp=false;	
	try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{
		try{
			xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(e){
			try{
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch(e1){
				xmlhttp=false;
			}
		}
	}
	return xmlhttp;
}
function open_folder_list_live(FolderID)
{
	var section;
	if(document.getElementById("selSearchType"))
	{
		section=document.getElementById("selSearchType").value;
	}
	else
	{
		section=(document.getElementById("hdn_open_Section"))?document.getElementById("hdn_open_Section").value:"";
	}
	
	var serial_no=(document.getElementById("hdn_open_Serial_no"))?document.getElementById("hdn_open_Serial_no").value:"";
	var airlinesid=(document.getElementById('airlinesID'))?(document.getElementById('airlinesID')!="")?document.getElementById('airlinesID').value:"":"";
	var titleImg=(document.getElementById('titleImg'))?(document.getElementById('titleImg')!="")?document.getElementById('titleImg').value:"":"";
	
	var params="";
	if(arguments[1])
	{
		var params="&FileID="+arguments[1];
	}if(arguments[2])
	{
		var params=params+"&cs_nop_link_id="+arguments[2];
	}if(arguments[3])
	{
		var params=params+"&cs_nop_tab_id="+arguments[3];
	}if(arguments[4])
	{
		var params=params+"&cs_nop_rec_id="+arguments[4];
	}if(arguments[5])
	{
		var params=params+"&cs_nop_group_id="+arguments[5];
	}if(arguments[6])
	{
		var params=params+"&cs_nop_Type="+arguments[6];
	}if(titleImg!="")
	{
		var params=params+"&from_path="+titleImg;
	}
	if(document.getElementById("sflg"))
	{
		var params=params+"&sflg=search";
	}
	window.open("about:blank","move_dialog","height="+screenH+",width="+screenW+",menubar=0,resizable='yes',scrollbars=yes,status=0,toolbar=0");
	if(serial_no=="")
	{
		serial_no="";
	}
	else
	{
		serial_no = "&serial_no="+serial_no
	}
	if(section=="")
	{		
		section="";
	}
	else
	{		
		section = "&section="+section
	}
	document.frmSearch.action = "open_folder.php?FolderID="+FolderID+section+serial_no+"&airlinesid="+airlinesid+params;
	document.frmSearch.target = "move_dialog"
	document.frmSearch.submit();
	
}
function next_pre_10_list_live(FolderID,FileID,view_mode)
{
	var section;
	if(document.getElementById("selSearchType"))
	{
		section=document.getElementById("selSearchType").value;
	}
	else
	{
		section=(document.getElementById("hdn_open_Section"))?document.getElementById("hdn_open_Section").value:"";
	}
	
	//var section=(document.getElementById("hdn_open_Section"))?document.getElementById("hdn_open_Section").value:"";
	var serial_no=(document.getElementById("hdn_open_Serial_no"))?document.getElementById("hdn_open_Serial_no").value:"";
	var airlinesid=(document.getElementById('airlinesID'))?(document.getElementById('airlinesID')!="")?document.getElementById('airlinesID').value:"":"";
	var titleImg=(document.getElementById('titleImg'))?(document.getElementById('titleImg')!="")?document.getElementById('titleImg').value:"":"";

	var params="";
	if(arguments[1])
	{
		var params="&FileID="+arguments[1];
	}if(arguments[3])
	{
		var params=params+"&cs_nop_link_id="+arguments[3];
	}if(arguments[4])
	{
		var params=params+"&cs_nop_tab_id="+arguments[4];
	}if(arguments[5])
	{
		var params=params+"&cs_nop_rec_id="+arguments[5];
	}if(arguments[6])
	{
		var params=params+"&cs_nop_group_id="+arguments[6];
	}if(arguments[7])
	{
		var params=params+"&cs_nop_Type="+arguments[7];
	}if(titleImg!="")
	{
		var params=params+"&from_path="+titleImg;
	}
	
	window.open("about:blank","move_dialog","height="+screenH+",width="+screenW+",menubar=0,resizable='yes',scrollbars=1,status=0,toolbar=0");
	if(serial_no=="")
	{
		serial_no="";
	}
	else
	{
		serial_no = "&serial_no="+serial_no
	}
	if(section=="")
	{
		section="";
	}
	else
	{
		section = "&section="+section
	}
	document.frmSearch.action = "open_folder.php?FolderID="+FolderID+"&FileID="+FileID+section+serial_no+"&view_mode="+view_mode+"&airlinesid="+airlinesid+params;
	document.frmSearch.target = "move_dialog"
	document.frmSearch.submit();
}
/*function isMouseLeaveOrEnter(e, handler) 
{ 
	if (e.type != 'mouseout' && e.type != 'mouseover') 
		return false; 
	var reltg = e.relatedTarget ? e.relatedTarget : e.type == 'mouseout' ? e.toElement : e.fromElement; 
	while (reltg && reltg != handler) 
		reltg = reltg.parentNode; 
		return (reltg != handler); 
}*/

function setMenuScript_Header(obj,parentDiv,flg)
{
	var subDiv = "SubMenu_header";
	hideMenu_Header(parentDiv,subDiv);
	var disp = flg > 0 ? "" : "none";
	if(obj)
		obj.style.display = disp;
}
function hideMenu_Header(parentDiv,subDiv)
{
	var mainDivObj = document.getElementById(parentDiv);
	var divElm = mainDivObj.getElementsByTagName("div");
	var len = mainDivObj.getElementsByTagName("div").length;

 	for(i=1;i<=len;i++)
 	{
		var id = divElm.id;
		var dObj = document.getElementById(id);
		if(dObj)
		dObj.style.display = "none";
	}
}
function setClassId(obj,class_name,flg)
{	
	var hover_class = class_name+"Hover";
	var exstClass = obj.className;
	
	if(obj.id != hover_class)
	{	 
		obj.id = hover_class;
		obj.className = hover_class;			
	}	
	else
	{
		if(flg)
		{
			obj.id = class_name;	
			obj.className = class_name;	
		}
	}	
}


function setClassSubmenu()
{
   var strsubmenu=document.getElementById('sub_menu_title').value;
	var strsubmenu_array=strsubmenu.split(",");
	for(var i=0;i<strsubmenu_array.length;i++)
	{
	 document.getElementById(strsubmenu_array[i]).className='bottommenuBg';
	}
}

function class1(id)
{   
	if(recids!="")
	{
		document.getElementById(id).className = "bottommenuBg";
	}
}
function class_hover(id)
{ 
	if(recids!="")
	{
		document.getElementById(id).className = "bottommenuBg_hover";
	}
}

function fn_component_audit(ComponentName)
{ 
	winWidth = 800; 
	winheight = 800;
	
	if (screen){ 
	   winWidth = screen.width;
	   winHeight = screen.height;
	}
	var listliveNew=window.open("manage_audit_trail.php?adt=MOVE_ADT&comp="+ComponentName+"",'listliveNew1','toolbar=no,location=no,scrollbars=yes,resizable=yes,width='+winWidth+',height='+winHeight+',left=0,top=0');
	listliveNew.focus();
}

function fn_audit(sectionflag)
{
    winWidth = 800; 
	winheight = 800;
	
	if (screen){ 
	   winWidth = screen.width;
	   winHeight = screen.height;
	}

	var list_liveNew=window.open("manage_audit_trail.php?adt=COMP_ADT&secflg="+sectionflag+"",'list_liveNew1','toolbar=no,location=no,scrollbars=yes,resizable=yes,width='+winWidth+',height='+winHeight+',left=0,top=0');
	list_liveNew.focus();
}

// JavaScript Document
var recids = ""; 
	function isMouseLeaveOrEnter(e, handler) 
	{ 
		if (e.type != 'mouseout' && e.type != 'mouseover') 
			return false; 
		var reltg = e.relatedTarget ? e.relatedTarget : e.type == 'mouseout' ? e.toElement : e.fromElement; 
		while (reltg && reltg != handler) 
			reltg = reltg.parentNode; 
			return (reltg != handler); 
	}

  	function manageSubMenuHOver()
	{
		document.getElementById("manageSubMenu").style.position = "absolute";
		document.getElementById("manageSubMenu").style.display = "block";
	}
	function manageSubMenuOut()
	{
		document.getElementById("manageSubMenu").style.display = "none";
	}
	
	function manageSubMenuHOver1()
	{
		document.getElementById("manageSubMenu1").style.position = "absolute";
		document.getElementById("manageSubMenu1").style.display = "block";
	}
	function manageSubMenuOut1()
	{
		document.getElementById("manageSubMenu1").style.display = "none";
	}
	function setMenuScript(obj,parentDiv,flg)
	{ 
		var subDiv = "SubMenu";
		hideMenu(parentDiv,subDiv);
		
		document.getElementById('submenuset').value=recids;
		var setflag=document.getElementById('submenuset').value;
		//alert(setflag);
		
		var disp =(flg > 0 && setflag!="")? "" : "none";
		if(obj)
			obj.style.display = disp;
	}	 
	function hideMenu(parentDiv,subDiv)
	{
		var mainDivObj = document.getElementById(parentDiv);
		var len = mainDivObj.getElementsByTagName("div").length;
		for(i=1;i<=len;i++)
		{
			var id = "SubMenu"+i;
			var dObj = document.getElementById(id);
			if(dObj)
				dObj.style.display = "none";
	}
}


// code by KK 

function setElement(arg,frm)
{
    var frm1="";
	if(frm == "") 
	{
		frm1 = document.forms[0];
	}
	else
	{
	    frm1 = eval("document."+frm);
	}
	
	var len = Math.abs(frm1.elements.length);	
	var flag; 
	
	switch(arg)
	{
			case 1 : flag =  "disabled";break;
			case 2 :
			{
				flag =  "";
				break;
			}
			default : flag = "";break;
	}
	for(var i=0;i<len;i++)
	{
		var id = frm1.elements[i].id;
		var obj = document.getElementById(id);
		var type = new String(obj.type);
		type = type.toLowerCase();
		var tag = new String(obj.tagName);
		tag = tag.toLowerCase();
		//alert(type);
		if(tag == "select")
		{
			//alert(arg)
			if( (arg == 0 || arg == 1 )) //&& obj.multiple == false)
			{					
				//alert(obj.multiple)
				obj.selectedIndex = 0;
			}				
			obj.disabled = flag;
		}
		else if(type != "button" && type != "hidden" && type != "reset" && type != "submit")
		{
			if(type == "checkbox" )
			{
				if(arg == 0 || arg == 1)				
				obj.checked = false;

				obj.disabled = (flag.length > 0);
			}
			else if(type == "radio" )
			{
				if(arg == 0 || arg == 1)				
				obj.checked = false;

				obj.disabled = (flag.length > 0);
			}
			else 	
			{
				if(document.getElementById("act").value == "ADD" || document.getElementById("act").value == "") 
				{
					obj.value = "";
				}		
				obj.disabled = flag;
			}
		}
	}
}

function frmAction(arg,frm)
{
  //alert(arg)
  var objAdd = document.getElementById("addBtn");
  var objEdit = document.getElementById("editBtn");
  var objDelete = document.getElementById("archiveBtn");
  var objSave = document.getElementById("saveBtn");
  var objReset = document.getElementById("resetBtn");
  switch(arg)
	{
		case 'Add' :	
					document.getElementById("act").value = 'ADD';
					document.getElementById("id").value = "";
					//alert("add")
					setElement(0,frm);										
					if(objAdd)
					{
					  objAdd.className="disbutton";
		   			  objAdd.disabled = 'disabled';
					}
					if(objEdit)
					{
					  objEdit.className="disbutton";
					  objEdit.disabled = 'disabled';
					}
					if(objDelete)
					{
					  objDelete.className="disbutton";
					  objDelete.disabled = 'disabled';
					}
					if(objSave)
					{ 
					  objSave.className="button";
					  objSave.disabled = '';
					}
					objReset.className="button";					
				break;
		case 'Edit' :
					if(document.getElementById("id").value)
					{
					document.getElementById("act").value = "EDIT";	
					//alert("edit")
					setElement(2,frm);
					if(objAdd)
					{
						objAdd.className="disbutton";
						objAdd.disabled = 'disabled';
					}
					if(objEdit)
					{
						objEdit.className="disbutton";
						objEdit.disabled = 'disabled';
					}
					if(objDelete)
					{	
						objDelete.className="disbutton";
						objDelete.disabled = 'disabled';
					}
					if(objSave)
					{
						objSave.className="button";
						objSave.disabled = '';
					}
						objReset.className="button";				
					}
					else
						alert("Please Select Record from below grid");
						
				break;			
		case 'Reset' :	
					if(document.getElementById("act"))
						document.getElementById("act").value = "";	
					if(document.getElementById("id"))
						document.getElementById("id").value = "";	
					//alert("reset")
					setElement(1,frm);
					if(objAdd)
					{
						objAdd.className="button";
						objAdd.disabled = '';
					}
					if(objEdit)
					{
						objEdit.className="disbutton";					
						objEdit.disabled = 'disabled';
					}
					if(objDelete)
					{
						objDelete.className="disbutton";
						objDelete.disabled = 'disabled';
					}
					if(objSave)
					{
						objSave.className="disbutton";
						objSave.disabled = 'disabled';
					}
						objReset.className="button";					
				break;		
		default : 	
					//alert("dif") 
					setElement(1,frm);
					if(objAdd)
					{
						objAdd.className="button";
						objAdd.disabled = '';
					}
					if(objEdit)
					{
						objEdit.className="button";
						objEdit.disabled = '';
					}
					if(objDelete)
					{
						objDelete.className="button";
						objDelete.disabled = '';
					}
					if(objSave)
					{
						objSave.className="disbutton";
						objSave.disabled = 'disabled';
					}
						objReset.className="button";	
				break;
	}
  return false;
}

function popitup(url,name,winWidth,winheight) 
{	
	
	if(winWidth!='' && winheight!='')
	{
		winWidth = winWidth;
		winheight = winheight; 
	}
	else
	{
		winWidth = 800; 
		winheight = 800;
	}		
	
	if (screen){ 
	   winWidth = screen.width;
	   winHeight = screen.height;
	}
	
	newwindow=window.open(url,name,'toolbar=no,fullscreen=yes,location=no,scrollbars=yes,resizable=yes,width='+winWidth+',height='+winHeight+',left=0,top=0');
	if (window.focus) {newwindow.focus()}
	return false;
}	
function RotateImage(fileid,container_id,direction,filepath,flag)
{	    
	  var section=(document.getElementById('hdn_open_Section'))?(document.getElementById('hdn_open_Section')!="")?document.getElementById('hdn_open_Section').value:"":"";
	  var serial_no=(document.getElementById('hdn_open_Serial_no'))?(document.getElementById('hdn_open_Serial_no')!="")?document.getElementById('hdn_open_Serial_no').value:"":"";
	  var section_view=(document.getElementById('section_view'))?(document.getElementById('section_view')!="")?document.getElementById('section_view').value:"":"";
	  var folderid=(document.getElementById('hdn_open_folderId'))?(document.getElementById('hdn_open_folderId')!="")?document.getElementById('hdn_open_folderId').value:"":"";
	  var airlinesid=(document.getElementById('airlinesID'))?(document.getElementById('airlinesID')!="")?document.getElementById('airlinesID').value:"":"";
	  var strURL="saveImageAjax.php?folderId="+folderid+"&fileid="+fileid+"&container_id="+escape(container_id)+"&direction="+direction+"&filepath="+escape(filepath)+"&serial_no="+serial_no+"&section="+section+"&section_view="+section_view+"&airlinesid="+airlinesid+"&t="+(new Date());

	  var req = getXMLHTTP();
	  if (req) 
	  {	
		  req.onreadystatechange = function() 
		  {
			  if (req.readyState == 4)
			  {
				  if (req.status == 200) 
				  {
					  if(flag=="cs")
					  {
						 xajax_RotateImg_AuditTrial(fileid,xajax.getFormValues('listlivetab'));
					     loadGrid('doc');
					  }else if(flag=="open")
					  {  
					  }else{
						   if(section_view=='BOX VIEW')
						   {
							   if(document.getElementById("box_folid")){
							   LoadGrid_boxview(document.getElementById("box_folid").value);}
						   }
						   else{
					     	   LoadMyGrid();
						   }
					  }
				  } 
			  }
		  }
		  req.open("GET", strURL, true);
		  req.send(null);
	  }
 }
 
function RotateImage_open(fileid,container_id,direction,filepath,flag,temp_src,i,doc_id,isAPI)
{
	
	var fExtArr = filepath.split(".");
	var fExt = fExtArr[fExtArr.length-1];
	
	if(fExt.toLowerCase() == "flv")
	{		
		if(document.getElementById('myimageFLV_'+i))
		{
			document.getElementById('myimageFLV_'+i).src=temp_src;
		}
		return false;
	}
	if(fExt.toLowerCase() == "doc" || fExt.toLowerCase() == "docx" || fExt.toLowerCase() == "xls" || fExt.toLowerCase() == "xlsx" || fExt.toLowerCase() == "ppt" || fExt.toLowerCase() == "pptx" || fExt.toLowerCase() == "xlsm")
	{
		if(document.getElementById('myimage_'+i))
		document.getElementById('myimage_'+i).src=temp_src;
		return false;
	}
	try
	{
		if(arguments[7])
		{
			var doc_id = arguments[7];
		}
		  var section=(document.getElementById('hdn_open_Section'))?(document.getElementById('hdn_open_Section')!="")?document.getElementById('hdn_open_Section').value:"":"";
		  var serial_no=(document.getElementById('hdn_open_Serial_no'))?(document.getElementById('hdn_open_Serial_no')!="")?document.getElementById('hdn_open_Serial_no').value:"":"";
		  var section_view=(document.getElementById('section_view'))?(document.getElementById('section_view')!="")?document.getElementById('section_view').value:"":"";
		  var folderid=(document.getElementById('hdn_open_folderId'))?(document.getElementById('hdn_open_folderId')!="")?document.getElementById('hdn_open_folderId').value:"":"";
		  var airlinesid=(document.getElementById('airlinesID'))?(document.getElementById('airlinesID')!="")?document.getElementById('airlinesID').value:"":"";
		  if(isAPI!='API')
			  var strURL="saveImageAjax.php?folderId="+folderid+"&fileid="+fileid+"&container_id="+escape(container_id)+"&direction="+direction+"&filepath="+escape(filepath)+"&serial_no="+serial_no+"&section="+section+"&section_view="+section_view+"&airlinesid="+airlinesid+"&t="+(new Date());
		  else
			 var strURL="saveImageAjax.php?folderId="+folderid+"&fileid="+fileid+"&container_id="+escape(container_id)+"&direction="+direction+"&filepath="+escape(filepath)+"&serial_no="+serial_no+"&section="+section+"&section_view="+section_view+"&airlinesid="+airlinesid+"&t="+(new Date())+"&API=Y";

		  var req = getXMLHTTP();
		  if (req) 
		  {	
			  req.onreadystatechange = function() 
			  {
				  if (req.readyState == 4)
				  {
					  if (req.status == 200) 
					  {
						  if(flag=="cs" || flag=="cstab" || flag=="CS" || flag=="CSTAB" || flag=="comp_matrix")
						  {
							 	if(flag=="cs" || flag=="CS")
								{
									  xajax_RotateImg_AuditTrial(fileid,xajax.getFormValues('listlivetab'),doc_id);
								
								}else if(flag=="cstab" || flag=="CSTAB")
								{
									  xajax_RotImg_csTabsrch_Audit(fileid,xajax.getFormValues('pdfSerach'),doc_id);
									  flag="cs";
								} else if(flag=="comp_matrix"){
									
									xajax_RotateImg_AuditTrial(fileid,xajax.getFormValues('frm'),doc_id);
								} 
								var rand_num =Math.random().toString(36).substring(2);
								var new_image=new Image();
								new_image.src=temp_src+'&t='+rand_num;
								new_image.onload=function()
								{
									document.getElementById('myimage_'+i).src=temp_src+'&t='+rand_num;
									if(document.getElementById('zLink'+i))
									{
										document.getElementById('zLink'+i).href=document.getElementById('zLink'+i).href+'&t='+rand_num;
										var big_image=new Image();
										big_image.src=document.getElementById('zLink'+i).href;
									}
									else if(document.getElementById('BigImg'))
									{
										document.getElementById('BigImg').src=document.getElementById('BigImg').src+'&t='+rand_num;
									}
								}
						  }
						  else if(flag=="internal")
						  {
							 xajax_RotateImg_AuditTrial(fileid,xajax.getFormValues('listlivetab'),doc_id);
							 var rand_num =Math.random().toString(36).substring(2);
								var new_image=new Image();
								new_image.src=temp_src+'&t='+rand_num;
								new_image.onload=function()
								{
									document.getElementById('myimage_'+i).src=temp_src+'&t='+rand_num;
									if(document.getElementById('zLink'+i))
									{
										document.getElementById('zLink'+i).href=document.getElementById('zLink'+i).href+'&t='+rand_num;
										var big_image=new Image();
										big_image.src=document.getElementById('zLink'+i).href;
									}
									else if(document.getElementById('BigImg'))
									{
										document.getElementById('BigImg').src=document.getElementById('BigImg').src+'&t='+rand_num;
										
									}
								//	document.getElementById('pre_big_image_'+i).src=document.getElementById('pre_big_image_'+i).src+'&t='+rand_num;
								}
						  }
						  else if(flag=="mcc_docs")
						  {
							 xajax_RotateImg_AuditTrial(fileid,xajax.getFormValues('mccDocs'),doc_id);
							 var rand_num =Math.random().toString(36).substring(2);
								var new_image=new Image();
								new_image.src=temp_src+'&t='+rand_num;
								new_image.onload=function()
								{
									document.getElementById('myimage_'+i).src=temp_src+'&t='+rand_num;
									if(document.getElementById('zLink'+i))
									{
										document.getElementById('zLink'+i).href=document.getElementById('zLink'+i).href+'&t='+rand_num;
										var big_image=new Image();
										big_image.src=document.getElementById('zLink'+i).href;
									}
									else if(document.getElementById('BigImg'))
									{
										document.getElementById('BigImg').src=document.getElementById('BigImg').src+'&t='+rand_num;
										
									}
								//	document.getElementById('pre_big_image_'+i).src=document.getElementById('pre_big_image_'+i).src+'&t='+rand_num;
								}
						  }
						  else if(flag=="open")
						  {  
								var rand_num =Math.random().toString(36).substring(2);
								var temp_src_ = temp_src.split('&');
								var rType = temp_src_[(temp_src_.length)-1];
								if(rType == 'FLV')
								{
									document.getElementById('myimageFLV_'+i).src=temp_src+'&t='+rand_num;
								}
								else
								{
								var new_image=new Image();
								new_image.src=temp_src+'&t='+rand_num;
								new_image.onload=function()
								{
									/*document.getElementById('myimage_'+i).src=temp_src+'&t='+rand_num;
									document.getElementById('zLink'+i).href=document.getElementById('pre_big_image_'+i).href+'&t='+rand_num;
									document.getElementById('pre_big_image_'+i).src=document.getElementById('pre_big_image_'+i).src+'&t='+rand_num;*/
									document.getElementById('myimage_'+i).src=temp_src+'&t='+rand_num;
									if(document.getElementById('zLink'+i))
									{
										document.getElementById('zLink'+i).href=document.getElementById('zLink'+i).href+'&t='+rand_num;
										var big_image=new Image();
										big_image.src=document.getElementById('zLink'+i).href;
									}
									else if(document.getElementById('BigImg1'))
									{
										document.getElementById('BigImg1').src=document.getElementById('BigImg1').src+'&t='+rand_num;
									}
								}
								}
						  }else{
								var rand_num =Math.random().toString(36).substring(2);
								var new_image=new Image();
								new_image.src=temp_src+'&t='+rand_num;
								new_image.onload=function()
								{
									//document.getElementById('myimage_'+i).src=temp_src+'&t='+rand_num;
									//document.getElementById('zLink'+i).href=document.getElementById('pre_big_image_'+i).src+'&t='+rand_num;
									//document.getElementById('pre_big_image_'+i).src=document.getElementById('pre_big_image_'+i).src+'&t='+rand_num;
									document.getElementById('myimage_'+i).src=temp_src+'&t='+rand_num;
									if(document.getElementById('zLink'+i))
									{
										document.getElementById('zLink'+i).href=document.getElementById('zLink'+i).href+'&t='+rand_num;
										var big_image=new Image();
										big_image.src=document.getElementById('zLink'+i).href;
									}
									else if(document.getElementById('BigImg'))
									{
										document.getElementById('BigImg').src=document.getElementById('BigImg').src+'&t='+rand_num;
									}
								}
						  }
						  
					  } 
					  if(req.responseText=="error")
					  {
					    alert("Error in Rotate Document. Please Contact Administrator for further assistance.")
						//ErrorExeception("Section:"+section_view+" - Error Description- error In Query of SaveImageAjax.php page : ","")
					  }
				  }
			  }
			  req.open("GET", strURL, true);
			  req.send(null);
		  }
	}catch(err)
	{
	    alert("Error in Rotate Document. Please Contact Administrator for further assistance.")
		//ErrorExeception("COMMON.JS : RotateImage_open : ", e)
	}
 }

function getMetaTags (filename,fids,filepath)
{
	try
	{
		winWidth = 800; 
		winheight = 800;

		if (screen)
		{ 
		   winWidth = screen.width;
		   winHeight = screen.height;
		}
		var string;

		if (document.getElementById("hdn_open_Section"))
		{
			string ="&section="+ document.getElementById("hdn_open_Section").value;
		}
		if(document.getElementById("FolderID"))
		{
			string =string+"&FolderID="+ document.getElementById("FolderID").value;
			var flagtype=(document.getElementById("flagtype"))?document.getElementById("flagtype").value:"open";
			string =string+"&flagtype="+flagtype;
		}
		if(arguments[3])
		{
			string=string+"&flagtype="+arguments[3];
		}
		if(document.getElementById("hdn_open_Serial_no"))
		{
			var sectionsrno = document.getElementById("hdn_open_Serial_no").value;
		}
		else
		{
			if(arguments[3]=='MCC')
			{
				var sectionsrno = arguments[4];
				string=string+"&FolderID="+arguments[5];
			}
			else
			{
				var sectionsrno = 0;
			}
		}

		if(arguments[3] == 'MID' || arguments[3] == 'PROFILE')
		{
			window.open('metatags_popup.php?flagtype=MID&fids='+fids,'','toolbar=no,location=no,scrollbars=yes,resizable=yes,width='+winWidth+',height='+winHeight+',left=0,top=0');
		}
		else
		{
			window.open('metatags_popup.php?filename='+filename+'&fids='+fids+'&filepath='+filepath+"&"+string+"&serial_no="+sectionsrno,'','toolbar=no,location=no,scrollbars=yes,resizable=yes,width='+winWidth+',height='+winHeight+',left=0,top=0');
		}
	}
	catch(e)
	{
		alert("Error in page load. Please Contact Administrator for further assistance.");
		ErrorExeception("COMMON.JS : GETMETATAGS : 00001  ", e);
	}
}

function saveMetaTag(){
	 xajax_SaveFile_content(xajax.getFormValues('open_file_form'));	 		
}
function getMetaTags4User(filename,fids)
{  //get filecontent for User who has file meta tag Edit Rights.
	try{
		document.getElementById("filename").value = filename;
		document.getElementById("fileIDS").value = fids;
		document.getElementById("TDfilename").innerHTML=filename;
		document.getElementById("file_content_div").style.display = "block";
		if(fids=="")
		{
			getloading1(0);	
			document.getElementById('file_content_div').style.display = 'none';
			alert('File does not exists..');
		}
	} catch(e) {
		alert("Error in page load. Please Contact Administrator for further assistance.")
		ErrorExeception("COMMON.JS : GETMETATAGS : 00002  ", e)
	}
}
function saveMetaTag4User()
{
  	 getloading1(1);	
	 xajax_Save_filecontent4user(xajax.getFormValues('open_file_form'));	 		
}

function getloading1(flg){
var elm = document.getElementById("LoadingDivCombo_upload");

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

// ============================General Functions==========================================//


function numOnly(field, event)
{
 var key,keychar;

 if (window.event)
   key = window.event.keyCode;
 else if (event)
    key = event.which;
 else
    return true;
 
 keychar = String.fromCharCode(key);

var valid_str="0123456789";
  // then check for the numbers 

 if ((key==null) || (key==0) || (key==8) || 
     (key==9) || (key==13) || (key==27) )
   return true;

  else if ((valid_str.indexOf(keychar) > -1))
         {
           window.status = "";
           return true;
         }
       else
         {
           window.status = "Field accepts Alphabetical characters only"; 
           return false;
         }
	return false;	 
 }
 
function open_new_window(strUrl,winName)
{
	var winName = window.open(strUrl,winName,'height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes');
	winName.focus();
}

//For Browser Identified
var flg_browser=0;

navigator.sayswho= (function(){
    var ua= navigator.userAgent, 
    N= navigator.appName, tem, 
    M= ua.match(/(opera|chrome|safari|firefox|msie|trident)\/?\s*([\d\.]+)/i) || [];
    M= M[2]? [M[1], M[2]]:[N, navigator.appVersion, '-?'];
    if(M && (tem= ua.match(/version\/([\.\d]+)/i))!= null) M[2]= tem[1];
    var bname=M.join(' ');	
	if(bname.indexOf('MSIE') != -1 || bname.indexOf('Trident') != -1)
	{
		flg_browser=1;
	}
})();
// code for session time out 
var flg_opener=0;
kinit();
var interval;
function kinit()
{
	interval = setInterval(trackLogin,1000*3602);
}
function trackLogin()
{		
	var xmlReq = false;
	try {
	xmlReq = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
	try {
	xmlReq = new ActiveXObject("Microsoft.XMLHTTP");
	} catch (e2) {
	xmlReq = false;
	}
	}
	if (!xmlReq && typeof XMLHttpRequest != 'undefined') {
	xmlReq = new XMLHttpRequest();
	}
	xmlReq.open('get', 'checkSession.php', true);
	xmlReq.setRequestHeader("Connection", "close");
	xmlReq.send(null);
	xmlReq.onreadystatechange = function(){
		if(xmlReq.readyState == 4 && xmlReq.status==200) {	
			if(xmlReq.responseText == 1)
			{
				clearInterval(interval);				
				if(flg_browser==1)
				{
					if(window.opener != undefined)
					{	
						flg_opener=1;				
						window.close();
						window.opener.location.href = "logout.php?ses_out=1";
					}
					else if(flg_opener==0)
					{						
						window.location.href = "index.php?loginspire=1";						
					}
				}
				else
				{
					window.location.href = "logout.php?ses_out=1";
					window.close();
				}
				
			}			
		}
	}
}
/*by KK 
This Fucntion equivalent of PHP's in_array()
*/
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
} 

function chnageListType(sel)
{
  var result = [];
  var opt = sel.options;  
  var preSelect = sel.options;  
  var deSelect = sel.options;   
  var arr = Array();
  var Selarr = Array();
  for (var k=0; k < deSelect.length;k++) {
	if(deSelect[k].id != '')
	{ 		
		if(document.getElementById(deSelect[k].id)){					
			document.getElementById(deSelect[k].id).disabled='disabled';				
		}
	}
	else
	{
		arr[k] = deSelect[k].text;			
	}
	if(deSelect[k].selected)
	{
		Selarr[k] = deSelect[k].text;
	}
  }	  
   for (var i=0; i < preSelect.length;  i++) {		
   if(preSelect[i].id != '')
	{  		  	   
		if(inArray(preSelect[i].text,Selarr))
				{	
			for (var j=0; j < arr.length;  j++) {
									   
				if(preSelect[i].text==arr[j])
				{															
					if(preSelect[i].id != '')
					{ 
						document.getElementById(preSelect[i].id).disabled='';
					}
				}
				}
			}
	}
  }
  return true;
}
// Returns the version of Internet Explorer or a -1
// (indicating the use of another browser).
function getInternetExplorerVersion()
{
  var rv = -1; // Return value assumes failure.
  if (navigator.appName == 'Microsoft Internet Explorer')
  {
    var ua = navigator.userAgent;
    var re  = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
    if (re.exec(ua) != null)
      rv = parseFloat( RegExp.$1 );
  }
  return rv;
}
var IEV = getInternetExplorerVersion();
function getCookie(c_name)
{
	var c_value = document.cookie;
	var c_start = c_value.indexOf(" " + c_name + "=");
	if (c_start == -1)
	{
	  c_start = c_value.indexOf(c_name + "=");
	}
	if (c_start == -1)
	{
	  c_value = null;
	}
	else
	{
	  c_start = c_value.indexOf("=", c_start) + 1;
	  var c_end = c_value.indexOf(";", c_start);
	  if (c_end == -1)
	  {
		c_end = c_value.length;
	  }
	  c_value = unescape(c_value.substring(c_start,c_end));
	}
	return c_value;
}
var expireFLg = 0;
function jinit()
{
	interval = setInterval(checkExpireToken,1000*60);
}
function checkExpireToken()
{
	var xmlReq = false;
	try {
	xmlReq = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
	try {
	xmlReq = new ActiveXObject("Microsoft.XMLHTTP");
	} catch (e2) {
	xmlReq = false;
	}
	}
	if (!xmlReq && typeof XMLHttpRequest != 'undefined') {
	xmlReq = new XMLHttpRequest();
	}
	xmlReq.open('get', 'chk_token_expire.php', true);
	xmlReq.setRequestHeader("Connection", "close");
	xmlReq.send(null);
	xmlReq.onreadystatechange = function(){
		if(xmlReq.readyState == 4 && xmlReq.status==200) {	
			if(xmlReq.responseText == '1')
			{
				alert("Login Expired\nYour session has  now expired.  Please request a new token to login again.\nPlease click OK to go back to the home page.");
				location.href = 'logout.php?res=expire';		
			}
			else if(xmlReq.responseText == '2' && expireFLg != 1)
			{
				alert("Due to security reasons. Your session will expire in the next 5 minutes.");
				expireFLg = 1;
			}
		}
	}
}
if(getCookie('expire_time') != '' && getCookie('expire_time') != null)
{
	jinit();
}


/////////////////////////////////
/////////////////////////////////
///---CS LOV coding start------//
/////////////////////////////////
////////////////////////////////
function GetCSLOV(link_id,val,level,sectionFlag)
{
	var Level=level;
	
	resetCSCombo(Level);
	var addStr = '';
	if(document.getElementById("selAttachType") && document.getElementById("selAttachType").value){
		addStr+='&attachType='+document.getElementById("selAttachType").value;
	}
	if((sectionFlag!=0  && level==0)  ||  (val!=0 && level!=0))
	{
		
			var strURL="";
			var section=1;
			var parent_id=0;
			var tab_val=Array();
			if(val!=0)
			{
				var tab_val=val.split("|");
			}
			else
			{
				 tab_val[0]=val;
			}
			
			var params="?sectionFlag="+sectionFlag+"&link_id="+link_id+"&pid="+tab_val[tab_val.length-1]+"&CSTabLevel="+level+"&CSTABCOMBO"+addStr+"&"+new Date();
		
			var req = getXMLHTTP();
			if (req)
			{
				req.onreadystatechange = function() 
				{
					if (req.readyState == 4)
					{
						if (req.status == 200) 
						{
							var html_text = req.responseText; 
							if(html_text!='')
							{
								var divobj=document.createElement('div');
								divobj.setAttribute("id","CSDivTab_"+Level);
								divobj.setAttribute("name","CSDivTab[]");
								//divobj.setAttribute("style","width:auto; float:left; margin-left:2px;");
			
								
								document.getElementById('ComboDiv').appendChild(divobj);
								/*if($("#ComboDiv").find("div:#CSDivTab_"+Level))
								{	
								
									//$("#aircraft_header_div").find('div:#mainnav_txt').css("margin-left","0px")
									//$("#ComboDiv").find("div:#CSDivTab_"+Level).css({"display":"table-cell","padding-left":"5px"});
									//$("#ComboDiv").find("div:#CSDivTab_"+Level).css({"display":"table-cell","margin-left":"2px","float":"left"});
								}*/
								document.getElementById('CSDivTab_'+Level).innerHTML=html_text;
							}	
							
						}
					}
				}
				
				req.open("GET", strURL+params, false);
				req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				req.setRequestHeader("Content-length", params.length);
				req.send();
			} 
	}
}

function resetCSCombo(startval)
{	
	var valTab=$("[name='CSDivTab[]']").length;
	var objRemove = document.getElementById("ComboDiv");
	
	for(var i=startval;i<=valTab;i++)
	{
		var divRemove =document.getElementById("CSDivTab_"+i);
		if(divRemove)
		{
			objRemove.removeChild(divRemove);
		}
	}
	
	return true;

}
function validateCSCombo()
{
	var valTab=$("[name='CSDivTab[]']").length;
	if(valTab!=0)
	{
		if(document.getElementById("CSTabCombo_"+valTab) && document.getElementById("CSTabCombo_"+valTab).value==0)
		{
			//alert("Please select Tab");
			return false;
		}
		else
		{
			return document.getElementById("CSTabCombo_"+valTab).value;
		}
	}
	return true;
}

function enabledisableCSCombo(flag)
{
	var valTab=$("[name='CSDivTab[]']").length;
	for(var i=1; i<=valTab;i++)
	{
		if(document.getElementById("CSTabCombo_"+i))
		{
			if(flag==1)
			document.getElementById("CSTabCombo_"+i).disabled=true;
			else
			document.getElementById("CSTabCombo_"+i).disabled=false;
		}
	}
}


function selectedCSCombo(strIds)
{
	var valTab=$("[name='CSDivTab[]']").length;
	var strArr=strIds.split(",");
	
	for(var i=1; i<=valTab;i++)
	{
		if(document.getElementById("CSTabCombo_"+i))
		{
			for(var j=0;j<strArr.length;j++)
			{
				var obj=document.getElementById("CSTabCombo_"+i);
				
				for(var k=0;k<obj.options.length;k++)
				{
					var tempVal=obj[k].value;
					var tempArr=tempVal.split("|");
					if(tempArr[tempArr.length-1]==strArr[j])
					{
						document.getElementById("CSTabCombo_"+i).value=tempVal;
					}
				}
			}
		}
	}
}
/////////////////////////////////
/////////////////////////////////
///---CS LOV coding End------////
/////////////////////////////////
////////////////////////////////
function cs_open(link_id,tab_id,type,pid,SecFlag)
{
	var tempStr='';
	if(SecFlag==2)
	{
		var tempStr =  "center=ENG&";
	}
	else if(SecFlag==3)
	{
		var tempStr = "centre=APU&";
	}
	else if(SecFlag==4)
	{
		var tempStr = "centre=GEAR&";
	}
	else if(SecFlag==11)
	{
		var tempStr =  "center=PROP&";
	}
	
	if(tab_id==12)
	{
		var CSWin = window.open('manage_rows.php?'+tempStr+'section=BIBLE&link_id='+link_id+'&tab_id='+tab_id+'&p_id='+pid+'&Type='+type+'&SectionFlag='+SecFlag+'','CSWin','height='+screenH+',width='+screenW+',scrollbars=yes,resizable=yes');
	}
	else if(tab_id==46)
	{
		var CSWin = window.open('preview_returnprofile.php?section=RETURN_PROFILE&link_id='+link_id+'&tab_id='+tab_id+'&p_id='+pid+'&Type='+type+'&SectionFlag='+SecFlag+'','CSWin','height='+screenH+',width='+screenW+',scrollbars=yes,resizable=yes');
	}
	else if(tab_id==47)
	{
		var CSWin = window.open('manage_rows.php?section=PMO_ROW&link_id='+link_id+'&tab_id='+tab_id+'&p_id='+pid+'&Type='+type+'&SectionFlag='+SecFlag+'','CSWin','height='+screenH+',width='+screenW+',scrollbars=yes,resizable=yes');
	}
	else 
	{
		var CSWin = window.open('manage_rows.php?'+tempStr+'section=ROW&link_id='+link_id+'&tab_id='+tab_id+'&p_id='+pid+'&Type='+type+'&SectionFlag='+SecFlag+'','CSWin','height='+screenH+',width='+screenW+',scrollbars=yes,resizable=yes');
	}
	CSWin.focus();
}

/*set enable the cs button in all center for both main side and client side */
function SetClassName()
{	
	$("#aircraft_header_div").find('div:#mainnav_txt').css("margin-left","0px");
	$("#aircraft_header_div").find("div").find(".bottommenuText1_dis").each(function(){
	$(this).attr("class","bottommenuText1");
	});	
	
}
function TDRmail(tab_id,rec_id,status)
{
	var strURL= 'send_tdr_mail.php';
	var params='tab_id='+tab_id+'&rec_id='+rec_id+'&status='+status;
	var req = getXMLHTTP();
	if (req)
	{
		req.onreadystatechange = function() 
		{
			if (req.readyState == 4)
			{
				if (req.status == 200) 
				{
					var a=req.responseText;
				
										
				}
			}
		}
		
		req.open("POST", strURL+'?t='+(new Date()), true);
		req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		
		req.send(params);
	}
}
var flg=0;
if(typeof isPageURLPrev!='undefined')
{
	if(isPageURLPrev==1)
		flg=1;
}
if(flg==0)
{
	var strURL = "view_page.php";
	var req = getXMLHTTP();
	if (req)
	{
		req.onreadystatechange = function() 
		{
			if (req.readyState == 4)
			{
				if (req.status == 200) 
				{
					
				}
			}
		}
		req.open("GET", strURL, true);
		req.send(null); 
	}
}
/**/


// Common function for active or deactivate RPM for centres

function setRpm(id,tailid,airlinesId,rpm_flag,center_type,audit_section)
{
	xajax.config.defaultMode = 'synchronous';
	if(rpm_flag==0)		// If already InActive
	{
		if(confirm('Are you sure you want to Activate The Return Project management?'))
		{
			xajax_activateRPMdeactivate(id,tailid,airlinesId,rpm_flag,center_type,audit_section);
		}
	}
	else				// If already Active
	{
		if(confirm('The Return Project management has already been activated, are you sure you want to continue?'))
		{
			xajax_activateRPMdeactivate(id,tailid,airlinesId,rpm_flag,center_type,audit_section);
		}
	}
}

function openPopupForRLstatusList(subLink_id,master_section)
{
	var var_template_id = 0;
	if(document.getElementById("ddl_template") && (document.getElementById("ddl_template").value!=0 && document.getElementById("ddl_template").value!='add_temp'))
		var_template_id = document.getElementById("ddl_template").value;
	else if(document.getElementById("hdn_template_id"))
		var_template_id = document.getElementById("hdn_template_id").value;
	
	if(var_template_id==0 || var_template_id=='')
	{
		alert("Please select a template First.");
		return false;
	}
	
	var ChkTempType=0;
	
	if(document.getElementById("SelTemType"))
	{
		var ChkTempType = document.getElementById("SelTemType").value;
	}
	
	var selClients = 0;
	if(document.getElementById("selClients"))
		selClients = document.getElementById("selClients").value;
	
	if(ChkTempType==1)
	{
		var Master_Header_Win = window.open('bible.php?section=MASTER_HEADER&type='+master_section+'&tab_id=12&SectionFlag='+master_section+'&template_id='+var_template_id+'&client_id='+selClients+'&subLink_id='+subLink_id,'Master_Header_Win','scrollbars=1,resizable=1,fullscreen=yes');
		
		//var Master_Header_Win = window.open('bible.php?section=MASTER_HEADER&type='+master_section+'&tab_id=12&SectionFlag='+master_section+'&template_id='+var_template_id+'&client_id='+selClients+'&subLink_id='+subLink_id,'Master_Header_Win','scrollbars=yes,resizable=yes,left=50,fullscreen=yes');
		Master_Header_Win.focus();
	}
}

// Function for sphinx error page 

function spxErrorHtml(section)
{
	if(section=='MCC')
	{
		$("#getDoc").load( "search_error.php" );
		$("#csdiv").hide();
	}
	else if(section=='CSDOC')
	{
		$("#getDoc").load( "search_error.php" );
	}
	else if(section=='COMPONENT')
	{
		$("#searchtable" ).load( "search_error.php" );
	}	
	else
	{
		$( "#divContent" ).load( "search_error.php" );
		$( "#viewattach" ).hide();
		$( "#viewDiv" ).hide();		
	}
	return true;
}
function GetMappedTextAreaString(textAreaValue)
{
    var arrtextAreaVal = textAreaValue.split('<br />');        
    var returnTextAreaVal='';
    for(var x in arrtextAreaVal)
    {
        if (arrtextAreaVal.hasOwnProperty(x)) 
        {   
            if(arrtextAreaVal[x]=='')
            {                
                returnTextAreaVal+=arrtextAreaVal[x]+'\n';
            }
            else
            {
                returnTextAreaVal+=arrtextAreaVal[x]
            }            
        }
    }        
    return returnTextAreaVal;
}