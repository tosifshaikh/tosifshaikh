// JavaScript Document	
var xmlDocument;
var table;
var hdnStart=0;
var ArrHeader=new Array();
var optColor=new Array();
var pager;
var limit = 100;
var clientCalled = 0;
var screenW = 1340, screenH = 640;
var glb_total = 0;
function fn_selectedClient(CiD)
{
	clientCalled = 1;
}

function getXMLHTTP()
{ //fuction to return the xml http object
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

//START : FUNCTION FOR AUDIT TRIAL
function GridLoad(intStart)
{
		
	hdnStart = intStart;
	document.getElementById("hdnStart").value=intStart;
	
	if(secname!='FLYsign Management')
	{
		ArrHeader[0]="Operation";
		ArrHeader[1]="Client";
		ArrHeader[2]="Date";
		ArrHeader[3]="Related Details";
		ArrHeader[4]="Old Value";
		ArrHeader[5]="New Value";
		ArrHeader[6]="Action By";
	}
	else
	{
		ArrHeader[0]="Operation";	
		ArrHeader[1]="Date";
		ArrHeader[2]="FLYsign Level Name";
		ArrHeader[3]="Related Details";
		ArrHeader[4]="Old Value";
		ArrHeader[5]="New Value";
		ArrHeader[6]="Action By";
	}
	var opt=(document.getElementById("opt"))?document.getElementById("opt").value:"";
	var keyword=(document.getElementById("keyword"))?document.getElementById("keyword").value:"";
	var from_date=(document.getElementById("from_date"))?document.getElementById("from_date").value:"";
	var to_date=(document.getElementById("to_date"))?document.getElementById("to_date").value:"";
	var sublinkId = document.getElementById('sublinkId').value;
	var selairlines=(document.getElementById("selairlines"))?document.getElementById("selairlines").value:"0";
	var hdntemplate_id=(document.getElementById("hdntemplate_id"))?document.getElementById("hdntemplate_id").value:"";
	var hdnsubSection=(document.getElementById("hdnsubSection"))?document.getElementById("hdnsubSection").value:"";
	
	var strsting=""
	if(opt!="" || from_date!="" || to_date!="" || keyword!="" || selairlines !="" || hdntemplate_id!='' || hdnsubSection!='')
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
			 var keyword1="&keyword="+encodeURIComponent(keyword);
			 strsting=strsting+keyword1			
		 }
		 if(selairlines != "0")
		 {
			 var selairlines1="&airlines="+selairlines;
			 strsting=strsting+selairlines1
		 }
		 if(hdntemplate_id != "0")
		 {
			 var hdntemplate_id1="&template_id="+hdntemplate_id;
			 strsting=strsting+hdntemplate_id1
		 }
		 if(hdnsubSection != "0")
		 {
			 var hdnsubSection1="&subSection="+hdnsubSection;
			 strsting=strsting+hdnsubSection1
		 }
	}
	var strURL = ''
	
	
	// Central audit Trail code. START >>>>>
	
	if(document.getElementById("hdnAdtFrom") && document.getElementById("hdnAdtFrom").value!='' && document.getElementById("hdnAdtFrom").value=='CENTRAL_ADT')
	{
		selFlyUser = (document.getElementById("selFlyUser") && document.getElementById("selFlyUser").value!='')?document.getElementById("selFlyUser").value:'';
		selAirUser = (document.getElementById("selAirUser") && document.getElementById("selAirUser").value!='')?document.getElementById("selAirUser").value:'';
		if(selFlyUser!='')
		{
			strsting+='&selFlyUser='+selFlyUser;
		}
		if(selAirUser!='')
		{
			strsting+='&selAirUser='+selAirUser;
		}
		
		strURL="manage_audit_trail.php?adt=MASTER_ADT&act=MASTER_ADT_XML&adtFrom=CENTRAL_ADT"+strsting+"&Start="+hdnStart+"&sublinkId="+sublinkId;
	}
	else
	{
		if(opt!="" || keyword!="" || from_date!="" || to_date!="" || selairlines !="0" || hdntemplate_id!='' || hdnsubSection!='')
		{
			strURL="manage_audit_trail.php?adt=MASTER_ADT&act=MASTER_ADT_XML"+strsting+"&Start="+hdnStart+"&sublinkId="+sublinkId;		
		}
		else
		{
			strURL="manage_audit_trail.php?adt=MASTER_ADT&act=MASTER_ADT_XML&Start="+hdnStart+"&sublinkId="+sublinkId;
		}
	}
	
	// Central audit Trail code. END <<<<<
	
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
						Render_Grid(ArrHeader);
					}
					catch(err)
					{
						exc_error('GridLoad','section: Master Audti Trail <br />', err.toString(), err);
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

function Render_Grid(ArrHeader)
{
	table = '';
	
	var opt=(document.getElementById("opt"))?document.getElementById("opt").value:"";
	var keyword=(document.getElementById("keyword"))?document.getElementById("keyword").value:"";
	var from_date=(document.getElementById("from_date"))?document.getElementById("from_date").value:"";
	var to_date=(document.getElementById("to_date"))?document.getElementById("to_date").value:"";
	
	var root = xmlDocument.getElementsByTagName('root');
	var error = root[0].getElementsByTagName('error');
	
	var start = parseInt(document.getElementById("hdnStart").value);
	var totaltab = root[0].getElementsByTagName('total')[0];
	var total=(totaltab.childNodes[0]).nodeValue;
	glb_total=total;
	var cLimit = ((start+limit) > total) ? total : (start+limit);
	
	table+='<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">';
	
	table += '<tr class="tableCotentTopBackground">';
	table += '<td align="center" valign="middle" height="30px;" style="margin-bottom:5px;">';
	if(total==0)
	{
		table += '<strong> No Result(s) Found.</strong><div id="ShowHideDiv" style="float:right; width:200px;">';
		table += '</span><span id="ShowHideLov"></span></div>&nbsp;&nbsp;&nbsp;';

	}
	else
	{
		table += '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		table += '<strong>'+(start+1)+' - '+cLimit+' of '+total+'  Result(s) Found.</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}
	table += '<div align="right" id="divTopPagging" style="float:right; valign="middle"></div></td></tr>';
	
	table+='<tr><td>';
	var rows = root[0].getElementsByTagName('row');
	
	table += '<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableContentBG" id="results">';
	table += '<tr class="hdtit">';
	for(j=0;j<ArrHeader.length;j++)
	{
	  table += '<td width="15%" class="tableCotentTopBackground"><font>'+ArrHeader[j]+'</font></td>';
	}
	table += '</tr>';
	var t=0;   
	for(i=0;i< rows.length ;i++)
	{
		var operation = getNodeValue(rows[i],'operation');
		var date = getNodeValue(rows[i],'date');
		var related_details = getNodeValue(rows[i],'related_details');
		var old_value = getNodeValue(rows[i],'old_value');
		var new_value=getNodeValue(rows[i],'new_value');
		var user=getNodeValue(rows[i],'user');
		var color=getNodeValue(rows[i],'color');
		var airlinesId = getNodeValue(rows[i],'airlinesId');
		var flysign_level_name = getNodeValue(rows[i],'flysign_level_name');
		var val1,val2,flag;
		var  trClass=(i%2==0)?"#FFFFFF":"#EEEEEE";
	
		if(related_details=='Section moved to Sub Section' || related_details=='Sub Section moved to Section')
		{
			if(related_details=='Section moved to Sub Section')
			{
				val1 = 'Section';
				val2 = 'Sub Section';
			}
			else if(related_details=='Sub Section moved to Section')
			{
				val1 = 'Sub Section';
				val2 = 'Section';
			}
			
			
			table += '<tr name="TrId[]" id="TrId'+i+'" bgcolor="'+trClass+'"  >';
			table +='<td bgcolor="'+color+'" nowrap="nowrap" width="15%">';
			table +='<span onclick="ShowTypeChild(\''+i+'\');" id="imginact_'+i+'" class="plusicon" ';
			table +='style="cursor:pointer;"></span>&nbsp;';
		
			table +='<b>'+operation+'</b></td>';
			if(secname!='FLYsign Management')			
			table+='<td width="15%">'+airlinesId+'</td>';
			table +='<td width="15%">'+date +'</td>';
			table +='<td width="15%">'+related_details+'</td>';
			table +='<td width="15%">-</td>';
			table +='<td width="15%">-</td>';
			table +='<td width="10%">'+user+'</td></tr>';	
			
			
			table += '<tr id="subrow_'+i+'" style="display: none;">';
			table +='<td colspan="7">';
			
			table +='<table width="100%" cellspacing="1" cellpadding="2" border="0" align="center">';
			table +='<tr>';
			table +='<td height="15" bgcolor="'+color+'" width="45%"></td>';
			table +='<td height="15" bgcolor="'+color+'" width="15%" align="center"><b>'+val1+'</b></td>';
			table +='<td height="15" bgcolor="'+color+'" width="15%" align="center"><b>'+val2+'</b></td>';
			table +='<td height="15" bgcolor="'+color+'" width="25%"></td>';
			table +='</tr>';
			
			table +='<tr bgcolor="#FFFFFF">';
			table +='<td height="15" align="center" width="45%"></td>';
			table +='<td width="15%" align="center">'+old_value+'</td>';
			table +='<td width="15%" align="center">'+new_value+'</td>';
			table +='<td height="15" align="center" width="25%"></td>';
			table +='</tr></table>';
			
			table +='</td>';
			table += '</tr>';
		}
		else
		{
			var pad = '';
			/*if(document.getElementById("hdnsublinkId").value == 56 || document.getElementById("hdnsublinkId").value == 57)
			{
				if(operation == "REORDER CATEGORIES" )
				{
					pad = 'style = "padding-left:0px"';
					
				}
				else
				{
					pad = 'style = "padding-left:18px"';
				}
				
			}*/
			
			
			table += '<tr name="TrId[]" id="TrId'+i+'" bgcolor="'+trClass+'"  >';
			table +='<td '+pad+' bgcolor="'+color+'" nowrap="nowrap" width="15%">';
			/*if(operation == "REORDER CATEGORIES")
			{
				table += '<span onclick="ShowTypeChild(\''+i+'\');" id="imginact_'+i+'" class="plusicon" style="cursor:pointer;"></span>&nbsp;';
				//table += '<img width="9" height="9" border="0" src="images/plus_inactive.jpg" id="imginact_1">';
			}*/
			table += '<b>'+operation+'</b></td>';
			if(secname!='FLYsign Management')
			table +='<td width="15%">'+airlinesId+'</td>';
			table +='<td width="15%">'+date +'</td>';
			if(secname=='FLYsign Management')
			table +='<td width="15%">'+flysign_level_name+'</td>';
			table +='<td width="15%">'+related_details+'</td>';
			var tempOldArr  = new Array();
			var tempNewArr  = new Array();			
			if(operation == "REORDER CATEGORIES")
			{
				 /*tempOldArr = old_value.split(",");
				 tempNewArr = new_value.split(",");
				 var tempOldStr = "";
				 for(var i =0; i<tempOldArr.length;i++)
				 {
					
					 tempOldStr += tempOldArr[i]+'\n';
				 }
				 var tempNewStr = "";
				 for(var i =0; i<tempNewArr.length;i++)
				 {
					
					 tempNewStr += tempNewArr[i]+'\n';
				 }
				// alert(tempOldStr)
				// alert(tempNewStr)*/
				table +='<td width="15%">'+old_value+'</td>';
				table +='<td width="15%">'+new_value+'</td>';
				
			}
			else
			{
				table +='<td width="15%">'+old_value+'</td>';
				table +='<td width="15%">'+new_value+'</td>';
			}

			
			table +='<td width="10%">'+user+'</td>';	
			

		
			

			/*if(operation == "REORDER CATEGORIES")
			{
				table+='<tr id=subrow_'+i+' style="display:none;">';
				table+='<td colspan = 7>';
				
				table+='<table width="100%" class="tableContentBG"  border="0" align="center" cellpadding="4" cellspacing="1">';
				table += '<tr name="TrId[]" id="TrId'+i+'" bgcolor="'+trClass+'"  >';
				table +='<td class="tableCotentTopBackground" nowrap="nowrap" width="15%"><b>Template Name</b></td>';
				table +='<td class="tableCotentTopBackground" width="15%">Category</td>';
				table +='<td class="tableCotentTopBackground" width="15%">From Position</td>';
				table +='<td class="tableCotentTopBackground" width="15%">To Position</td>';
				table +='</tr>';
				
				
				var val = related_details.split('###');
				//alert(val[0])
				//alert(val[1])
				
				table += '<tr name="TrId[]" id="TrId'+i+'" bgcolor="'+trClass+'"  >';
				table +='<td  nowrap="nowrap" width="15%"><b>'+val[0]+'</b></td>';
				table +='<td width="15%">'+val[1]+'</td>';
				table +='<td width="15%">'+old_value +'</td>';
				table +='<td width="15%">'+new_value+'</td>';
				table +='</tr>';
				table +='</table>';
				table+='</td>';
				table+='</tr>';
				
			}*/
		}
		t++;
	}
	if(t==0)
	{
		table += '<tr bgcolor="#FFFFFF" >';
		table +='<td colspan="9" align="center">No Record Found.</td>';
		table +='</tr>';
	}
	table+='</table></td></tr>';
	table+='<tr><td align="center" valign="middle" height="30px;" style="margin-bottom:5px;">';
	table+='<div align="right" id="divBottomPagging" style="float:right"></div></td></tr></table>';
	document.getElementById("RecShowDiv").innerHTML = table;
	
	getPagging(start,limit,total);
	
	getLoadingMain(0);
	return false;
}
/*function getpagging()
{
	pager = new Pager('results',100); 
	pager.init(); 
	pager.showPageNav('pager', 'pageNavPosition');
	pager.showPage(1);
}*/
function filter_grid()
{
	if(document.getElementById('from_date').value!='' || document.getElementById('to_date').value!='')
	{
		if(document.getElementById('to_date').value=='')
		{
			alert("Please select to date.");
			return false;
		}
		if(document.getElementById('from_date').value=='')
		{
			alert("Please select from date.");
			return false;
		}	
		var todate=document.getElementById("to_date").value;		
		var t=todate.split("-");
		var newtodate=t[2]+"-"+t[1]+"-"+t[0];
		var totime = new Date(newtodate).getTime();
		if(isNaN(totime))
		{
			var totime = new Date(todate).getTime();
		}
	
		var fromdate=document.getElementById("from_date").value;		
		var f=fromdate.split("-");
		var newfromdate=f[2]+"-"+f[1]+"-"+f[0];	
		var fromtime = new Date(newfromdate).getTime();
		if(isNaN(fromtime))
		{
			var fromtime = new Date(fromdate).getTime();
		}
	
		totime=Math.abs(totime);
		fromtime=Math.abs(fromtime);		
		
		if(totime < fromtime)
		{
			alert("Please Select From Date Smaller Than To Date .");
			return false;
		}		
	}	
   document.getElementById("hdnStart").value = 0;
   GridLoad(0);
}
function reset_filter_grid()
{
	document.getElementById("keyword").value="";
	document.getElementById("opt").value="";
	document.getElementById("from_date").value="";
	document.getElementById("to_date").value="";
	if(clientCalled==0)
	{
		if(document.getElementById("selairlines"))
			document.getElementById("selairlines").value="0";
	}
	document.getElementById("hdnStart").value = 0;
	if(document.getElementById("calendarDiv"))
		{closeCalendar();}
	
	GridLoad(0);
}

/*function searchpage(page,limit,total,key)
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
			alert("Page limit exsided..!!");
		}
		return false;
	}
}
function paging(intStart)
{
	if(document.getElementById('hdnStart'))
	{
		document.getElementById('hdnStart').value = intStart;
	}
	hdnStart = intStart;
	GridLoad();
}*/

function getPagging(intCurrent, intLimit, intTotal)
{
	intPage = intCurrent / intLimit;
	intLastPage = Math.floor( parseFloat(intTotal) / parseFloat(intLimit));
	
	strResult = '<div id="pagger">';
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
	strResult += '</div>';
	
	document.getElementById("divTopPagging").innerHTML = strResult;
	document.getElementById("divBottomPagging").innerHTML = strResult;
}

function paging(intStart)
{
	hdnStart = intStart;
	document.getElementById("hdnStart").value = intStart;
	GridLoad(intStart);	
	
}

function searchpage(page,limit,total,key)
{
	if(key==13)
	{
		chknumeric(page);
		if(page>0)
		{
			if(page==0){page=1;}		
			page = page -1;
			if(page <= total)
			{
				paging(page*limit);
			}
			else
			{
				alert("Highest page limit exceeded..!!");
				document.getElementById("searchtext1").value = '';
				document.getElementById("searchtext1").focus();
			}
		}
		else
		{
			alert("Lowest page limit exceeded..!!");
			document.getElementById("searchtext1").value = '';
			document.getElementById("searchtext1").focus();
		}
		return false;
	}
}

function chknumeric(sText)
{
	var ValidChars = "0123456789 ";
	var IsNumber=true;
	var Char;
	var str = sText;
	for (i = 0; i < str.length && IsNumber == true; i++) 
	{ 
		Char = str.charAt(i); 
		if (ValidChars.indexOf(Char) == -1) 
		{
			IsNumber = false;
		}
	}
	if(!IsNumber)
	{
		alert("Please enter numeric value.");
		document.getElementById("searchtext1").value= '';
		document.getElementById("searchtext1").focus();
		return false;
	}
}

function ShowTypeChild(typeRow)
{
	
	if(document.getElementById("subrow_"+typeRow).style.display=='none')
	{
		document.getElementById("subrow_"+typeRow).style.display='';
		document.getElementById("imginact_"+typeRow).className = 'minusicon';
	}
	else
	{
		document.getElementById("subrow_"+typeRow).style.display='none';
		document.getElementById("imginact_"+typeRow).className = 'plusicon';
	}
} 

function export_database()
{
	if(glb_total>0)
	{
		document.exportform.action="export_audit_trail_masters.php";
		document.exportform.submit();
	}
}
function exc_error(FunctionName,msg,ErrorString,ErrorArray)
{
	xajax_excJsError(FunctionName,msg,ErrorString,ErrorArray);
	alert("There is an issue in fetching record. Please Contact Administrator for further assistance.");
	getLoadingMain(0);
}