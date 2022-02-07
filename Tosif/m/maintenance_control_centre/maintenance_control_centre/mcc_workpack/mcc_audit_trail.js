// JavaScript Document
var xmlDocument,table,pager,parentId=0,TabId=0,RecId=0,Type=1,HdTitle="",hdnStart=0,opt="",keyword="",from_date="",to_date="",filtertype="",hidtable="",txtold_status="",txtnew_status="",qry="",wca="";
var ArrHeader=new Array();
var ArrSubHeader1=new Array();
var ArrSubHeader2=new Array();
var optColor=new Array();
var screenW = 1340, screenH = 640;
var globTotal = 0;
ArrHeader[0]="";
ArrHeader[1]="Operation";
ArrHeader[2]="Date";
ArrHeader[3]="Check Name";
ArrHeader[4]="Column Name";
ArrHeader[5]="Old Value";
ArrHeader[6]="New Value";
ArrHeader[7]="Old Status";
ArrHeader[8]="New Status";
ArrHeader[9]="User Name";

optColor["ROW ADDED"]="#66CCFF";
optColor["ROW DELETED"]="#DBBBA4"; 
optColor["ROW EDITED"]="#FF95BA";
optColor["ROW STATUS CHANGED"]="#A74DFB";
optColor["ROW TAG STATUS CHANGED"]="#CF0254";
var dategroupID=0;
var docType=0;
function getXMLHTTP()
{   
	var xmlhttp=false;	
	try
	{
		xmlhttp=new XMLHttpRequest();
	}
	catch(e)	
	{
		try
		{
			xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
		}
		catch(e)
		{
			try
			{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch(e1)
			{
				xmlhttp=false;
			}
		}
	}
	return xmlhttp;
}

function getLoadingMain(flg)
{
	var elm = document.getElementById("LoadingDivMain");

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

function getNodeValue(parent, tagName) 
{
	var node = parent.getElementsByTagName(tagName)[0];
	return (node && node.firstChild) ? node.firstChild.nodeValue : ""; 
}

function getPagging(intCurrent, intLimit, intTotal)
{
	intPage = intCurrent / intLimit;
	intLastPage = Math.floor( parseFloat(intTotal) / parseFloat(intLimit));
	
	var start=intCurrent;
	var cLimit = ((start+intLimit) > intTotal) ? intTotal : (start+intLimit);
	
	strResult = '<div style="width:60%;float:right;"><div style="width:auto;float:left;" ><strong>'+(intCurrent+1)+' - '+cLimit+' of '+intTotal+'  Result(s) Found.</strong></div><div id="pagger">';
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

function ShowTypeChild(typeRow)
{
	if(document.getElementById("subrow_"+typeRow).style.display=='none')
	{
		document.getElementById("imgact_"+typeRow).style.display='';
		document.getElementById("imginact_"+typeRow).style.display='none';
		document.getElementById("subrow_"+typeRow).style.display='';
	}
	else
	{
		document.getElementById("imgact_"+typeRow).style.display='none';
		document.getElementById("imginact_"+typeRow).style.display='';
		document.getElementById("subrow_"+typeRow).style.display='none';
	}
}


//START : FUNCTION FOR AUDIT TRIAL
function GridLoad(intStart)
{ 
	    parentId=(document.getElementById("parentId"))?document.getElementById("parentId").value:"";
		TabId=(document.getElementById("TabId"))?document.getElementById("TabId").value:"";
		RecId=(document.getElementById("RecId"))?document.getElementById("RecId").value:"";
		Type=(document.getElementById("type"))?document.getElementById("type").value:"";
		pmo_flag=(document.getElementById("pmo_flag"))?document.getElementById("pmo_flag").value:"";
		HdTitle=document.getElementById("hidtitle").value;
		HdTitle=HdTitle.replace("/\s*/g", " "); 
		
		opt=(document.getElementById("opt"))?document.getElementById("opt").value:"";
		keyword=(document.getElementById("keyword"))?document.getElementById("keyword").value:"";
		from_date=(document.getElementById("from_date"))?document.getElementById("from_date").value:"";
		to_date=(document.getElementById("to_date"))?document.getElementById("to_date").value:"";
		//filtertype=(document.getElementById("commenttype"))?document.getElementById("commenttype").value:"";
	    txtold_status=(document.getElementById("old_status"))?document.getElementById("old_status").value:"";
		txtnew_status=(document.getElementById("new_status"))?document.getElementById("new_status").value:"";
		hdnStart = intStart;
		document.getElementById("hdnStart").value=intStart;
		
	    var strsting=""
		if(opt!="" || from_date!="" || to_date!="" || filtertype!=""  || txtold_status!="" || txtnew_status!="" || keyword!="")
		{
			 if(opt!="")
			 {
				  var opt1="&opt="+opt;
				  strsting=opt1;
			 }
			 if(from_date!="")
			 {
				  var from_date1="&from_date="+from_date;
				  strsting=strsting+from_date1;
			 }
			 if(to_date!="")
			 {
				  var to_date1="&to_date="+to_date;
				  strsting=strsting+to_date1;
			 }
			 if(filtertype!="")
			 {
				 var filtertype1="&filtertype="+filtertype;
				 strsting=strsting+filtertype1;
			 }
			 if(txtold_status!="")
			 {
				 var txtold_status1="&old_status="+escape(txtold_status);
				 strsting=strsting+txtold_status1;
			 }
			 if(txtnew_status!="")
			 {
				 var txtnew_status1="&new_status="+escape(txtnew_status);
				 strsting=strsting+txtnew_status1;
			 }
			  if(keyword!="")
			 {
				 var keyword1="&keyword="+keyword;
				 strsting=strsting+keyword1
			 }
		}
		
	var strURL = '';	
	if(document.getElementById("hdnAdtFrom") && document.getElementById("hdnAdtFrom").value!='' && document.getElementById("hdnAdtFrom").value=='CENTRAL_ADT')
	{
		selFlyUser = (document.getElementById("selFlyUser") && document.getElementById("selFlyUser").value!='')?document.getElementById("selFlyUser").value:'';
		selAirUser = (document.getElementById("selAirUser") && document.getElementById("selAirUser").value!='')?document.getElementById("selAirUser").value:'';
		selairlines = (document.getElementById("selairlines") && document.getElementById("selairlines").value!='')?document.getElementById("selairlines").value:'';
		if(selairlines!='')
		{
			strsting+='&selairlines='+selairlines;
		}
		
		if(selFlyUser!='')
		{
			strsting+='&selFlyUser='+selFlyUser;
		}
		if(selAirUser!='')
		{
			strsting+='&selAirUser='+selAirUser;
		}
		
		strURL="manage_audit_trail.php?HdTitle="+HdTitle+"&adt=MCC_ADT&adtFrom=CENTRAL_ADT&act=MCC_ADT_XML&parentId="+parentId+"&TabId="+TabId+"&RecId="+RecId+"&pmo_flag="+pmo_flag+"&type="+Type+"&Start="+hdnStart+strsting;
		
	}
	else
	{
		var addStr="";
		if(dategroupID!=0){
			addStr = "&dateGroupID="+dategroupID;
			if(docType!=""){
				addStr += "&docType="+docType;
			}
		}
	    strURL="manage_audit_trail.php?HdTitle="+HdTitle+"&adt=MCC_ADT&act=MCC_ADT_XML&parentId="+parentId+"&TabId="+TabId+"&RecId="+RecId+"&pmo_flag="+pmo_flag+"&type="+Type+"&Start="+hdnStart+strsting+addStr;
	}

	var req = getXMLHTTP();
	getLoadingMain(1);
	if(req)
	{
		req.onreadystatechange = function() 
		{
			if (req.readyState == 4) 
			{
				if (req.status == 200) 
				{
					try
					{
						xmlDocument = req.responseXML;
						Render_Grid(ArrHeader,optColor);
					}catch(err)
					{
					    alert(err);
						getLoadingMain(0);
						ExceptionMsg("Section:Maintenance Control Centre Audit Trail - Error description:  "+escape(HdTitle)+", "+err.message+" OR InValid XML (XML Parser Error).Error on cs_audit_trail_xml.php page.\n\n");
						table = '<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableContentBG" id="results">';
						table +="<tr>";
						table += '<td width="35%" align="center" valign="middle"><strong>'+exceptionmessage+'</strong></td>';
						table += '</tr></table>';
						document.getElementById("RecShowDiv").innerHTML=table;
					}	
				} 
				else 
				{
					alert("There was a problem while using XMLHTTP:\n" + req.statusText);
				}
			}
		}		
		req.open("GET", strURL, true);
		req.send(null);
	}
}


function Render_Grid(ArrHeader,optColor)
{
	table = '';
	var root = xmlDocument.getElementsByTagName('root');
	var qrytab = root[0].getElementsByTagName('qry')[0];
	qry=(qrytab.childNodes[0]).nodeValue;
	var wcatab = root[0].getElementsByTagName('wca')[0];
	wca=(wcatab.childNodes[0]).nodeValue;
		
	var error = root[0].getElementsByTagName('error');
	if(error.length > 0)
	{
		var errortab = root[0].getElementsByTagName('error')[0];
		var errorMsg=(errortab.childNodes[0]).nodeValue;
	}
	
	var t=0; 
	
	table+='<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >';
	table+='<tr><td align="right" height="35" id="divTopPagging"></td></tr><tr><td>';
	if(error.length > 0)
	{
		globTotal = 0;
		table += '<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableContentBG" id="results">';
		table +="<tr>";
		table += '<td width="35%" align="center" valign="middle"><strong>'+errorMsg+'</strong></td>';
		table += '</tr>';
	}
	else
	{		
		var totaltab = root[0].getElementsByTagName('total')[0];
		var total=(totaltab.childNodes[0]).nodeValue;	
		globTotal = total;
		/*var audittab = root[0].getElementsByTagName('audit_type')[0];
		var audit_type=(audittab.childNodes[0]).nodeValue;*/
		var rows = root[0].getElementsByTagName('row');
		table += '<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableContentBG" id="results">';
		
		table += '<tr class="hdtit">';
		for(j=0;j<ArrHeader.length;j++)
		{
		  //j=(audit_type=="ROW" && ArrHeader[j]=="Record")?(j+1):j;
		  table += '<td class="tableCotentTopBackground" height="25" nowrap="nowrap"><font>'+ArrHeader[j]+'</font></td>'; //Header of Grid.
		}
		table += '</tr>';
		  
		for(i=0;i< rows.length ;i++)
		{	
			var rid_sec_actn_actndate_disimg_pmo_type_newtype=getNodeValue(rows[i],'rid_sec_actn_actndate_disimg_pmo_type_newtype');
			rid_sec_actn_actndate_disimg_pmo_type_newtypeArr=rid_sec_actn_actndate_disimg_pmo_type_newtype.split("+");
			
			var id =rid_sec_actn_actndate_disimg_pmo_type_newtypeArr[0];
			var sec=rid_sec_actn_actndate_disimg_pmo_type_newtypeArr[1];
                        var actn =rid_sec_actn_actndate_disimg_pmo_type_newtypeArr[2];
			var actn_date=rid_sec_actn_actndate_disimg_pmo_type_newtypeArr[3];	
			var dis_img=rid_sec_actn_actndate_disimg_pmo_type_newtypeArr[4];
			var ctype=rid_sec_actn_actndate_disimg_pmo_type_newtypeArr[6];	
			var new_ctype=rid_sec_actn_actndate_disimg_pmo_type_newtypeArr[7];	
			
			var title = getNodeValue(rows[i],'title');
			var chk_name = getNodeValue(rows[i],'chk_name');
			
			var old_value=getNodeValue(rows[i],'old_value');
			//old_value=(old_value!="")?old_value:"-";

			var new_value = getNodeValue(rows[i],'new_value');
			//new_value=(new_value!="")?new_value:"-";
			
		   	var actn_by=getNodeValue(rows[i],'uname');
			
			var trClass=(i%2==0)?"#FFFFFF":"#EEEEEE";
			var bgcolor=optColor[actn];
                        var txt_reload="";
           
		    ////////////////////////////////////////////////////////////////// main row start //////////////////////////////////////////////////////////////////
			table += '<tr name="TrId" id="TrId'+i+'" bgcolor="'+trClass+'" >';
			
                                table +='<td  height="25"></td>';
                                table +='<td bgcolor="'+bgcolor+'"  nowrap="nowrap"><b>'+actn+'</b></td>';
                                table +='<td nowrap="nowrap">'+actn_date +'</td>';
                                table +='<td nowrap="nowrap">'+chk_name +'</td>';

                                table +='<td>'+title+'</td>';
			
				if(actn=="ROW STATUS CHANGED")
				{
					table +='<td>-</td>';
					table +='<td>-</td>';
                    var old_valueArr=old_value.split(',');
					var new_valueArr=new_value.split(',');
                                        
					if(old_value == '') {
						table +='<td nowrap="nowrap">-</td>';
					} else {
						table +='<td nowrap="nowrap" bgcolor="'+old_valueArr[1]+'"><font color="'+old_valueArr[2]+'"><b>'+old_valueArr[0]+'</b></font></td>';
					}
if(new_value == '') {
						table +='<td nowrap="nowrap">-</td>';
					} else {
						table +='<td nowrap="nowrap" bgcolor="'+new_valueArr[1]+'"><font color="'+new_valueArr[2]+'"><b>'+new_valueArr[0]+'</b></font></td>';
					}
				}
				else if(actn=="ROW TAG STATUS CHANGED")
				{
					table +='<td>-</td>';
					table +='<td>-</td>';
					table +='<td><b>'+old_value+'<b></td>';
					table +='<td><b>'+new_value+'<b></td>';
				}
				else
				{
					table +='<td><b>'+old_value+'<b></td>';
					table +='<td><b>'+new_value+'<b></td>';
					table +='<td>-</td>';
					table +='<td>-</td>';
				}
				table +='<td nowrap="nowrap"><b>'+actn_by+'</b></td></tr>';
			
		}
		if(rows.length==0)
		{
				table += '<tr class="even">';
				table +='<td colspan="9" align="center">No Record Found.</td>';
				table +='</tr>';
		}
		table+='</table></td></tr><tr><td align="right" height="35" id="divBottomPagging"></td></tr></table>';
	}
	document.getElementById("RecShowDiv").innerHTML = table;
	
    if(error.length <= 0)
	{
		 getPagging(hdnStart,100,total);
	}
	getLoadingMain(0);
	return false;
}

function filter_grid()
{ 
	  cursor_position=0;
	  GridLoad(0);
}

function reset_filter_grid()
{
	  document.getElementById("keyword").value="";
	  document.getElementById("opt").value="";
	  document.getElementById("from_date").value="";
	  document.getElementById("to_date").value="";
	  document.getElementById("old_status").value="";
	  document.getElementById("new_status").value="";
	  if(document.getElementById("calendarDiv"))
	  {
	  	document.getElementById("calendarDiv").style.display='none';
	  }
	  paging(0);
}

function paging(intStart)
{
	  GridLoad(intStart);
	  document.getElementById("hdnStart").value=intStart;
}

function ActDact_Status(value)
{
	if(value=="ROW STATUS CHANGED")
	{
	  document.getElementById('old_status').disabled="";
	  document.getElementById('new_status').disabled="";

	}else if(value=="COMMENT ADDED" || value=="COMMENT DELETED" || value=="COMMENT EDITED")
	{
	
	  document.getElementById('old_status').value="";
	  document.getElementById('new_status').value="";

	}else{	
	  document.getElementById('old_status').value="";
	  document.getElementById('new_status').value="";
	}
}


function export_xls()
{
	try
	{
		if(globTotal == 0)
			return false;
		opt=(document.getElementById("opt"))?document.getElementById("opt").value:"";
		keyword=(document.getElementById("keyword"))?document.getElementById("keyword").value:"";
		from_date=(document.getElementById("from_date"))?document.getElementById("from_date").value:"";
		to_date=(document.getElementById("to_date"))?document.getElementById("to_date").value:"";
		txtold_status=(document.getElementById("old_status"))?document.getElementById("old_status").value:"";
		txtnew_status=(document.getElementById("new_status"))?document.getElementById("new_status").value:"";
		var hidExp_Type=(document.getElementById("hidExp_Type"))?document.getElementById("hidExp_Type").value:"";
        
		if(qry=="")
		{
			ExceptionMsg("Section:Maintenance Control Centre Audit Trail - Error description: "+escape(HdTitle)+", "+err.message+".\n\n");
			alert(exceptionmessage);
			return false;
		}
		if(hidExp_Type=="")
		{
		  alert("filter first.");
		  return false;
		}
		var strsting="";
		  if(opt!="")
		 {
			  var opt1="&opt="+opt;
			  strsting=opt1;
		 }
		 if(from_date!="")
		 {
			  var from_date1="&from_date="+from_date;
			  strsting=strsting+from_date1;
		 }
		 if(to_date!="")
		 {
			  var to_date1="&to_date="+to_date;
			  strsting=strsting+to_date1;
		 }
		
		 if(txtold_status!="")
		 {
			 var txtold_status1="&old_status="+txtold_status;
			 strsting=strsting+txtold_status1;
		 }
		 if(txtnew_status!="")
		 {
			 var txtnew_status1="&new_status="+txtnew_status;
			 strsting=strsting+txtnew_status1;
		 }
		  if(keyword!="")
		 {
			 var keyword1="&keyword="+keyword;
			 strsting=strsting+keyword1;
		 }
		 window.open("export_audit_trail.php?hidqry="+qry+"&hidwca="+wca+"&hidtitle="+HdTitle+"&hidExp_Type="+hidExp_Type+"&parentId="+parentId+"&TabId="+TabId+"&type="+Type+"&Start="+hdnStart+strsting,"mccwindow","","false"); 	 
	
	}catch(err)
	{
	    ExceptionMsg("Section:Maintenance Control Centre Audit Trail - Error description: "+escape(HdTitle)+", "+err.message+".\n\n");
		alert(exceptionmessage);
		return false;
	}
}

function convertDate(inputFormat) {
	
  var d = new Date(inputFormat);
  return [d.getDate(), d.getMonth()+1, d.getFullYear()].join('-');
}

function ExceptionMsg(message)
{
  xajax_fn_exceptionmsg(message);
}