// JavaScript Document
var xmlDocument,table,pager,hdnStart=0,opt="",keyword="",from_date="",to_date="",filtertype="",hidtable="",txtold_status="",typ="",txtnew_status="",qry="",wca="";
var ArrHeader=new Array();
var ArrSubHeader1=new Array();
var ArrSubHeader2=new Array();
var optColor=new Array();
var screenW = 1340, screenH = 640;

ArrHeader[0]="";
ArrHeader[1]="Operation";
ArrHeader[2]="Client";
ArrHeader[3]="Date";
ArrHeader[4]="Record";
ArrHeader[5]="Old File";
ArrHeader[6]="New File";
ArrHeader[7]="Old Status";
ArrHeader[8]="New Status";
ArrHeader[9]="User Name";
optColor["COLUMN ADDED"]="#99CC00";
optColor["COLUMN EDITED"]="#FF9900"; 
optColor["COLUMN DELETED"]="#FFFF00";
optColor["COLUMN REORDERED"]="#6666FF";

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
	clientId=(document.getElementById("clientId"))?document.getElementById("clientId").value:"";
	opt=(document.getElementById("opt"))?document.getElementById("opt").value:"";
	keyword=(document.getElementById("keyword"))?document.getElementById("keyword").value:"";
	from_date=(document.getElementById("from_date"))?document.getElementById("from_date").value:"";
	to_date=(document.getElementById("to_date"))?document.getElementById("to_date").value:"";
	hdnStart = intStart;
	typ=(document.getElementById("Type"))?document.getElementById("Type").value:"";
	document.getElementById("hdnStart").value=intStart;
		
	var strsting=""
	if(opt!="" || from_date!="" || to_date!="" || keyword!="")
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
		 if(keyword!="")
		 {
			 var keyword1="&keyword="+keyword;
			 strsting=strsting+keyword1
		 }
		 
		 
	}
		 if(typ!="")
		 {
			var typ1="&type="+typ;
			strsting=strsting+typ1;
		 }
	var strURL = '';	

	strURL="manage_audit_trail.php?&adt=WPH_ADT&act=MCC_ADT_XML&client_id="+clientId+"&Start="+hdnStart+strsting;

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
						ExceptionMsg("Section:MCC Work Pack History Audit Trail - Error description: , "+err.message+" OR InValid XML (XML Parser Error).Error on cs_audit_trail_xml.php page.\n\n");
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
	var subHeader=Array("","Filed Name","Old Value","New Value");
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
		table += '<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableContentBG" id="results">';
		table +="<tr>";
		table += '<td width="35%" align="center" valign="middle"><strong>'+errorMsg+'</strong></td>';
		table += '</tr>';
	}
	else
	{		
		var totaltab = root[0].getElementsByTagName('total')[0];
		var total=(totaltab.childNodes[0]).nodeValue;	
		var rows = root[0].getElementsByTagName('row');
		table += '<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableContentBG" id="results">';
		
		table += '<tr class="hdtit">';
		for(j=0;j<ArrHeader.length;j++)
		{
		  table += '<td class="tableCotentTopBackground" height="25" nowrap="nowrap"><font>'+ArrHeader[j]+'</font></td>'; //Header of Grid.
		}
		table += '</tr>';
		  
		for(i=0;i< rows.length ;i++)
		{	
			//var clientName=rows[i].client;
			var clientName= getNodeValue(rows[i],'client');
			var rid_actn_actndate=getNodeValue(rows[i],'rid_actn_actndate');
			rid_actn_actndate=rid_actn_actndate.split("+");
			
			var id =rid_actn_actndate[0];
            var actn =rid_actn_actndate[1];
			var actn_date=rid_actn_actndate[2];	
			var title = getNodeValue(rows[i],'title');
			var old_value=getNodeValue(rows[i],'old_value');
			old_value=(old_value!="")?old_value:"-";
			var new_value = getNodeValue(rows[i],'new_value');
			new_value=(new_value!="")?new_value:"-";
		   	var actn_by=getNodeValue(rows[i],'uname');
			var trClass=(i%2==0)?"#FFFFFF":"#EEEEEE";
			var bgcolor=optColor[actn];
            var txt_reload="";
           
		    /////////////////////////////////// main row start ///////////////////////////////////////////
			table += '<tr name="TrId" id="TrId'+i+'" bgcolor="'+trClass+'" >';

			table +='<td  height="25" width="11">';
			table +='<a onclick="ShowTypeChild('+i+');" href="javascript://"><img id="imginact_'+i+'" width="9" border="0" height="9" src="images/plus_inactive.jpg"><img id="imgact_'+i+'" width="9" border="0" height="9" style="display:none;" src="images/minus_active1.jpg"></a>';
			table+='</td>';
			table +='<td bgcolor="'+bgcolor+'"  nowrap="nowrap"><b>'+actn+'</b></td>';
			table +='<td>'+clientName+'</td>'
			table +='<td nowrap="nowrap">'+actn_date +'</td>';
			table +='<td nowrap="nowrap">-</td>';

			table +='<td>-</td>';
			table +='<td>-</td>';
			table +='<td>-</td>';
			table +='<td>-</td>';

				
			table +='<td nowrap="nowrap"><b>'+actn_by+'</b></td></tr>';
			table +='<tr id="subrow_'+i+'" style="display:none;">';
			table +='<td colspan="10">';
			table +='<table width="100%" cellspacing="1" cellpadding="2" border="0" bgcolor="#C4C4C4" align="center">';
			table+='<tr>';
			for(var k=0;k<subHeader.length;k++)
			{	
				if(k==0){
					table +='<td height="25" width="11" bgcolor="#FFFFFF"></td>';
				}else{
					table +='<td height="25" bgcolor="'+bgcolor+'"><b>'+subHeader[k]+'</b></td>';
				}
				
			}
			var old_value_arr=(old_value.length>0)?old_value.split("|"):"";
			var New_value_arr=(new_value.length>0)?new_value.split("|"):"";
			var Tampstr="";
			
			if(old_value_arr[0])
			{
				Tampstr=Tampstr+old_value_arr[0];
			}
			if(old_value_arr[1])
			{
				Tampstr=Tampstr+',<b>Set Column Field Type</b>:['+old_value_arr[1]+']';
			}
			if(old_value_arr[2])
			{
				Tampstr=Tampstr+',<b>Inserted Values</b>:['+old_value_arr[2]+']';
			}
			if(old_value_arr[3])
			{
				Tampstr=Tampstr+',<b>Auto Prepare List</b>:['+old_value_arr[3]+']';
			}
			if(old_value_arr[4])
			{
				Tampstr=Tampstr+',<b>Tab Name</b>:['+old_value_arr[4]+']';
			}
			
			var Tampstr1="";
			if(New_value_arr[0])
			{
				Tampstr1=Tampstr1+New_value_arr[0];
			}
			if(New_value_arr[1])
			{
				Tampstr1=Tampstr1+',<b>Set Column Field Type</b>:['+New_value_arr[1]+']';
			}
			if(New_value_arr[2])
			{
				Tampstr1=Tampstr1+',<b>Inserted Values</b>:['+New_value_arr[2]+']';
			}
			if(New_value_arr[3])
			{
				Tampstr1=Tampstr1+',<b>Auto Prepare List</b>:['+New_value_arr[3]+']';
			}
			if(New_value_arr[4])
			{
				Tampstr1=Tampstr1+',<b>Tab Name</b>:['+New_value_arr[4]+']';
			}				
							
			table+='</tr>';
			table+='<tr bgcolor="#FFFFFF">';
			table+='<td height="25"></td>';
			table+='<td height="25"><b>'+title+'</b></td>';
			table+='<td height="25">'+Tampstr+'</td>';
			table+='<td height="25">'+Tampstr1+'</td>';
			
			table+='</tr>';
			table +='</table></td></tr>';
				
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
	  //document.getElementById("old_status").value="";
	 // document.getElementById("new_status").value="";
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

function export_xls()
{
	try
	{
		opt=(document.getElementById("opt"))?document.getElementById("opt").value:"";
		keyword=(document.getElementById("keyword"))?document.getElementById("keyword").value:"";
		from_date=(document.getElementById("from_date"))?document.getElementById("from_date").value:"";
		to_date=(document.getElementById("to_date"))?document.getElementById("to_date").value:"";
		var hidExp_Type=(document.getElementById("hidExp_Type"))?document.getElementById("hidExp_Type").value:"";
        
		if(qry=="")
		{
			ExceptionMsg("Section:MCC Work Pack History Edit/Reorder Lov Audit Trail - Error description:, "+err.message+".\n\n");
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
		 if(keyword!="")
		 {
			 var keyword1="&keyword="+keyword;
			 strsting=strsting+keyword1;
		 }
	
	window.open("export_audit_trail.php?hidqry="+qry+"&hidwca="+wca+"&hidExp_Type="+hidExp_Type+"&Start="+hdnStart+strsting,"mccwindow","","false"); 	 
	
	}catch(err)
	{
	    ExceptionMsg("Section:MCC Work Pack History Edit/Reorder Lov Audit Trail - Error description: , "+err.message+".\n\n");
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