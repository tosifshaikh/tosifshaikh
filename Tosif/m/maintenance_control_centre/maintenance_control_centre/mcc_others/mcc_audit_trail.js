// JavaScript Document
var xmlDocument,hidtitle="",table,pager,parentId=0,TabId=0,mcc_type='',hdnStart=0,opt="",keyword="",from_date="",tab_name="",to_date="",filtertype="",doc_type=0,hdtotal=0,qry="",wca="";
var ArrHeader=new Array();
var ArrSubHeader1=new Array();
var ArrSubHeader2=new Array();
var ArrSubHeader3=new Array();
var ComptypeObj ={2:"Engine"};
var optColor=new Array();
var screenW = 1340, screenH = 640;

ArrHeader[0]="";
ArrHeader[1]="Operation";
ArrHeader[2]="Client";
ArrHeader[3]="Date";
ArrHeader[4]="Related Details";
ArrHeader[5]="Old File";
ArrHeader[6]="New File";
ArrHeader[7]="Old Status";
ArrHeader[8]="New Status";
ArrHeader[9]="User Name";

ArrSubHeader1[0]="";
ArrSubHeader1[1]="Comment Type";
ArrSubHeader1[2]="Old Value";
ArrSubHeader1[3]="New Value";
ArrSubHeader1[4]="Responsibility";



ArrSubHeader2[0]="";
ArrSubHeader2[1]="Aircraft";
ArrSubHeader2[2]="Tab";

ArrSubHeader2[3]="Group Name";
ArrSubHeader2[4]="Aircraft";
ArrSubHeader2[5]="Tab";
ArrSubHeader2[6]="New Record";
ArrSubHeader2[7]="Group Name";

optColor["ALL DOCUMENT STATUS CHANGED"]="#e3baf4";
optColor["COMMENT ADDED"]="#AA4C01";
optColor["DOCUMENT COPIED"]="#CC9900";
optColor["DOCUMENT STATUS CHANGED"]="#99FFCC";
optColor["ROTATED DOCUMENT"]="#00FF00";
optColor["META TAG EDITED"]="#FF0FF0";
optColor["META TAG EDITED"]="#FF0FF0";
optColor["DOCUMENT UPLOADED"]="#ff9900";
optColor["DOCUMENT REPLACED"]="#FF95BA";
optColor["MOVED DOCUMENT"]="#99CC00";
optColor["DOCUMENT ATTACHED"]="#0099FF";
optColor["WORKSTATUS CHANGED"]="#A74DFB";
optColor["PROCESS ADDED"]="#85ADFF";
optColor["PROCESS EDITED"]="#FFFFD0";
optColor["PROCESS DELETED"]="#D3AED5";
optColor["WORK STATUS ADDED"]="#A0E7D9";
optColor["WORK STATUS EDITED"]="#92A6DC";
optColor["WORK STATUS DELETED"]="#F74633";
optColor["REFERENCE NUMBER CHANGED"]="#336633";
optColor["PROCESS REORDER"]="#6666FF";
optColor["WORK STATUS REORDER"]="#d7e3b5";
optColor["DOCUMENT GROUP EDITED"]="#FAD1E0";
optColor["DOCUMENT GROUP DELETED"]="#0099FF";
optColor["RE-FILE DOCUMENT"]="#CCCC00";


optColor["RANGE ADDED"]="#fa751a";
optColor["RANGE EDITED"]="#f1a5c0";
optColor["RANGE DELETED"]="#6ad6e7";
optColor["META ADDED"]="#f6546a";

optColor["RANGE STATUS ADDED"]="#ffdae0";
optColor["RANGE STATUS EDITED"]="#6dc066";
optColor["RANGE STATUS DELETED"]="#31698a";
optColor["RANGE STATUS REORDER"]="#088da5";

optColor["DOCUMENT UPLOADED VIA FSCC"]="#A57A92";

optColor["REFERENCE STATUS CHANGED"]="#fae157";
optColor["EMAIL SENT"]="#666600";
optColor["DATE OF MANUFACTURE CHANGED"]="#85ADFF";









<!-- commmon function-->

if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (searchElement /*, fromIndex */ ) {
        "use strict";
        if (this == null) {
            throw new TypeError();
        }
        var t = Object(this);
        var len = t.length >>> 0;
        if (len === 0) {
            return -1;
        }
        var n = 0;
        if (arguments.length > 1) {
            n = Number(arguments[1]);
            if (n != n) { // shortcut for verifying if it's NaN
                n = 0;
            } else if (n != 0 && n != Infinity && n != -Infinity) {
                n = (n > 0 || -1) * Math.floor(Math.abs(n));
            }
        }
        if (n >= len) {
            return -1;
        }
        var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
        for (; k < len; k++) {
            if (k in t && t[k] === searchElement) {
                return k;
            }
        }
        return -1;
    }
}

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
 
<!--common function end-->

//START : FUNCTION FOR AUDIT TRIAL
function GridLoad(intStart)
{
	
		var hdnTypeVal = $("#HdnType").val();
		var HdndateGrpID= $("#HdndateGrpID").val();
		
		 hidtitle=(document.getElementById("hidtitle"))?document.getElementById("hidtitle").value:"";
		parentId=(document.getElementById("parentId"))?document.getElementById("parentId").value:"";
		tab_name=(document.getElementById("tab_name"))?document.getElementById("tab_name").value:"";
		mcc_type=(document.getElementById("mcc_type"))?document.getElementById("mcc_type").value:"";
		doc_type=(document.getElementById("doc_type"))?document.getElementById("doc_type").value:"";
		opt=(document.getElementById("opt"))?document.getElementById("opt").value:"";
		keyword=(document.getElementById("keyword"))?document.getElementById("keyword").value:"";
		from_date=(document.getElementById("from_date"))?document.getElementById("from_date").value:"";
		to_date=(document.getElementById("to_date"))?document.getElementById("to_date").value:"";
		filtertype=(document.getElementById("commenttype"))?document.getElementById("commenttype").value:"";
		hdnStart = intStart;
		audit_type=(document.getElementById("audit_type")!="")?document.getElementById("audit_type").value:"";
		mcc_doc_id=(document.getElementById("mcc_doc_id")!="")?document.getElementById("mcc_doc_id").value:"";
		document.getElementById("hdnStart").value=intStart;
		
	    var strsting=""
		if(opt!="" || from_date!="" || to_date!="" || filtertype!="" || keyword!="")
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
			  if(keyword!="")
			 {
				 var keyword1="&keyword="+keyword;
				 strsting=strsting+keyword1
			 }
			 
		}
		if(audit_type!="")
		{
			var auditRev = "&audit_type="+audit_type;
			strsting=strsting+auditRev;				 
		}
		if(mcc_doc_id!="")
		{
			var mccStr = "&mcc_doc_id="+mcc_doc_id;
			strsting=strsting+mccStr;				 
		}
		if(hdnTypeVal!=""){
			var typeStr = "&type="+hdnTypeVal;
			strsting=strsting+typeStr;
		}
		if(HdndateGrpID!=""){
			var dateGrpIdStr = "&date_group_id="+HdndateGrpID;
			strsting=strsting+dateGrpIdStr;
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
		
		strURL="manage_audit_trail.php?adt=MCC_ADT&act=MCC_OTHERS_ADT_XML&adtFrom=CENTRAL_ADT&parentId="+parentId+"&tab_name="+tab_name+"&doc_type="+doc_type+"&mcc_type="+mcc_type+"&Start="+hdnStart+strsting;
		
	}
	else
	{
	    strURL="manage_audit_trail.php?adt=MCC_ADT&act=MCC_OTHERS_ADT_XML&parentId="+parentId+"&tab_name="+tab_name+"&doc_type="+doc_type+"&mcc_type="+mcc_type+"&Start="+hdnStart+strsting;
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
						ExceptionMsg("Section:Mcc Others Audit Trail - Error description: "+err.message+" OR InValid XML (XML Parser Error).Error on mcc_audit_trail_xml.php page.\n\n");
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
	
	var audittab = root[0].getElementsByTagName('audit_type')[0];
	var audit_type=(audittab.childNodes[0]).nodeValue;
		
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
		hdtotal=0;
		table += '<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableContentBG" id="results">';
		table +="<tr>";
		table += '<td width="35%" align="center" valign="middle"><strong>'+errorMsg+'</strong></td>';
		table += '</tr>';
	}
	else
	{		
		var totaltab = root[0].getElementsByTagName('total')[0];
		var total=(totaltab.childNodes[0]).nodeValue;
		hdtotal=total;	

		var rows = root[0].getElementsByTagName('row');
		table += '<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableContentBG" id="results">';
		
		table += '<tr class="hdtit">';
		
		for(j=0;j<ArrHeader.length;j++)
		{
		  j=(audit_type=="GEN" && ArrHeader[j]=="Component")?(j+1):j;	
		  table += '<td class="tableCotentTopBackground" height="25" nowrap="nowrap">'+ArrHeader[j]+'</td>'; //Header of Grid.
		}
		table += '</tr>';
		  
		for(i=0;i< rows.length ;i++)
		{	
			var rid_doc_type_actn_actndate_disimg_type_newtype=getNodeValue(rows[i],'rid_doc_type_actn_actndate_disimg_type_newtype');
			rid_doc_type_actn_actndate_disimg_type_newtypeArr=rid_doc_type_actn_actndate_disimg_type_newtype.split("+");
			
			var id =rid_doc_type_actn_actndate_disimg_type_newtypeArr[0];
			var doc_type=rid_doc_type_actn_actndate_disimg_type_newtypeArr[1];
		    var actn =rid_doc_type_actn_actndate_disimg_type_newtypeArr[2];
			var actn_date=rid_doc_type_actn_actndate_disimg_type_newtypeArr[3];	
			var dis_img=rid_doc_type_actn_actndate_disimg_type_newtypeArr[4];
			var ctype=rid_doc_type_actn_actndate_disimg_type_newtypeArr[5];	
			var new_ctype=rid_doc_type_actn_actndate_disimg_type_newtypeArr[6];	
			var cmtuname=rid_doc_type_actn_actndate_disimg_type_newtypeArr[7];
			
			var title = getNodeValue(rows[i],'title');
			var client = getNodeValue(rows[i],'client');
		
			var old_tab=getNodeValue(rows[i],'old_tab');
			old_tab=(old_tab!="")?old_tab:"-";
			
			var old_file=getNodeValue(rows[i],'old_file');
			old_file=(old_file!="")?old_file:"-";
			
			var old_value=getNodeValue(rows[i],'old_value');
			old_value=(old_value!="")?old_value:"-";
			
			var old_tail=getNodeValue(rows[i],'old_tail');
			
			if(actn=="DOCUMENT COPIED" || actn=="MOVED DOCUMENT" || actn=="DOCUMENT ATTACHED" || actn=="RE-FILE DOCUMENT")
			{
				var old_tab=getNodeValue(rows[i],'old_tab');
			    var old_box=getNodeValue(rows[i],'old_box');
				var new_tail=getNodeValue(rows[i],'new_tail');
				var new_tab = getNodeValue(rows[i],'new_tab');
				var new_box = getNodeValue(rows[i],'new_box');
				var new_recId = getNodeValue(rows[i],'new_rec');
				new_recId=(new_recId!="Row 0")?new_recId:"-";
			}
			
			var new_file = getNodeValue(rows[i],'new_file');
			new_file=(new_file!="")?new_file:"-";
			
			var new_value = getNodeValue(rows[i],'new_value');
			new_value=(new_value!="")?new_value:"-";
			
		   	var actn_by=getNodeValue(rows[i],'uname');
			
			var trClass=(i%2==0)?"#FFFFFF":"#EEEEEE";
			var bgcolor=optColor[actn];
            var txt_reload="";
           
		    //---------------------------------------------- main row start----------------------------------------------------------------------//
			table += '<tr name="TrId" id="TrId'+i+'" bgcolor="'+trClass+'" >';
			
			var display_btn_arr=["DOCUMENT COPIED","MOVED DOCUMENT","COMMENT ADDED","WORKSTATUS CHANGED","DOCUMENT ATTACHED","RE-FILE DOCUMENT","DOCUMENT UPLOADED VIA FSCC"];
			
			var action_level2_arr1=["DOCUMENT STATUS CHANGED","DELETED DOCUMENT","ROTATED DOCUMENT"];
			
			var action_level2_arr2=["COMMENT ADDED","WORKSTATUS CHANGED"];
			var action_level2_arr3=["DOCUMENT UPLOADED VIA FSCC"];
		    
			var TampActArray=new Array();
			TampActArray["COMMENT ADDED"]="Comment Type";
			TampActArray["WORKSTATUS CHANGED"]="Process Name";
			TampActArray["DOCUMENT UPLOADED VIA FSCC"]="File Name";
			
			var ProcessArr = ["","PROCESS ADDED","PROCESS EDITED","PROCESS DELETED","PROCESS REORDER"];
			var RangeStatusValArr = ["","RANGE STATUS ADDED","RANGE STATUS EDITED","RANGE STATUS DELETED","RANGE STATUS REORDER"];
			
			var WorkStatusArr = ["","WORK STATUS ADDED","WORK STATUS EDITED","WORK STATUS DELETED","WORK STATUS REORDER"];
			var RangeStatusArr = ["","RANGE ADDED","RANGE EDITED","RANGE DELETED"];
			

			if(display_btn_arr.indexOf(actn)>=0 || ProcessArr.indexOf(actn)>=0 || WorkStatusArr.indexOf(actn)>=0 || RangeStatusArr.indexOf(actn)>=0 || RangeStatusValArr.indexOf(actn)>=0 || actn=="EMAIL SENT")
			{
					table +='<td width="11" height="25"><a href="javascript://" onclick=ShowTypeChild("'+t+'");>';
					table +='<img width="9" height="9" border="0" src="images/plus_inactive.jpg" id="imginact_'+t+'">';
					table +='<img width="9" height="9" border="0" src="images/minus_active1.jpg" id="imgact_'+t+'" style="display:none;"></a></td>';
			
			}else{
			 		table +='<td  height="25" width="11"></td>'; 
			}
					table +='<td bgcolor="'+bgcolor+'"  nowrap="nowrap"><b>'+actn+'</b></td>';
					table +='<td nowrap="nowrap">'+client+'</td>';
					table +='<td nowrap="nowrap">'+actn_date +'</td>';
					if(audit_type=="ROW")
					{
						//table +='<td nowrap="nowrap">'+old_tail+'</td>';
					}
					
					
					table +='<td nowrap="nowrap">'+old_tab+'</td>';
					table +='<td>'+old_file+'</td>';
					
					if(actn=="DOCUMENT UPLOADED VIA FSCC")
					{
						table +='<td>-</td>';
					}
					else{
					table +=(action_level2_arr1.indexOf(actn)>=0)?'<td>-</td>':'<td>'+new_file+'</td>';
					}
			if(action_level2_arr2.indexOf(actn)>=0 || ProcessArr.indexOf(actn)>=0 ||  WorkStatusArr.indexOf(actn)>=0 || RangeStatusArr.indexOf(actn)>=0 || RangeStatusValArr.indexOf(actn)>=0 || actn=="EMAIL SENT")
			{
					table +='<td>-</td>';
					table +='<td>-</td>';
				
			}else if(actn=="ALL DOCUMENT STATUS CHANGED")
			{
					var old_valueArr=(old_value!="-")?old_value.split('+'):"";
					var new_valueArr=(new_value!="-")?new_value.split('+'):"";
					if(old_valueArr.length>1)
					{
						table +=(old_valueArr[1]!='')?'<td><b>'+old_valueArr[1]+' [Affected Group List: '+old_valueArr[0]+']<b></td>':'<td><b>-</td>';
					}else{
						table +='<td><b>-<b></td>';
					}
					if(new_valueArr.length>1)
					{
						table +=(new_valueArr[1]!='')?'<td><b>'+new_valueArr[1]+' [Affected Group List: '+new_valueArr[0]+']<b></td>':'<td>-</td>';
					}else{
						table +='<td><b>-<b></td>';
					}
				
			}
			else if(actn=="DOCUMENT UPLOADED VIA FSCC")
			{
				table +='<td>-</td>';
				table +='<td>-</td>';
			}
			else if(actn=="DOCUMENT COPIED" || actn=="MOVED DOCUMENT" || actn=="DOCUMENT ATTACHED" || actn=="RE-FILE DOCUMENT")  // for row wise audit
			{
					table +='<td>-</td>';
					table +='<td>-</td>';
			}
			else
			{
				
					table +='<td><b>'+old_value+'<b></td>';
					table +='<td><b>'+new_value+'<b></td>';
			}
			table +='<td nowrap="nowrap"><b>'+actn_by+'</b></td></tr>';
				
			  //------------------------------------------------------main row end--------------------------------------------------------------------//
				
				
				if(actn=="DOCUMENT COPIED" || actn=="MOVED DOCUMENT" || actn=="DOCUMENT ATTACHED" || actn=="RE-FILE DOCUMENT")  // for row wise audit
				{
					table+='<tr id=subrow_'+t+' style="display:none;"><td colspan="10">';
					table += '<table border="0" align="center" width="100%" cellpadding="2" bgcolor="#C4C4C4" cellspacing="1">';
					
					
					 if(actn=="COPIED DOCUMENT")
					{
					 	var SubTitle="Copy ";
					}else if(actn=="MOVED DOCUMENT")
					{
					 	var SubTitle="Move ";
					}
					else if(actn=="DOCUMENT ATTACHED")
					{
						var SubTitle="Attach";
					}
					else if(actn=="RE-FILE DOCUMENT")
					{
						var SubTitle="Re-file";
					}
				   	table +='<tr><td height="25" colspan="4" align="center"><font color="#990000"><b>'+SubTitle+' From</b></font></td>';
					table+='<td colspan="6" align="center"><font color="#990000"><b>'+SubTitle+' To</b></font></td></tr>';
					
					table += '<tr>';
					var type=getNodeValue(rows[i],'type');
					var new_type=getNodeValue(rows[i],'new_type');
					
					if(type!=1 && type!='' && type!=0){
						ArrSubHeader2[1]=ComptypeObj[type];						
					}
					if(new_type!=1 && new_type!='' && new_type!=0){
						ArrSubHeader2[4]=ComptypeObj[new_type];
						}
					for(j=0;j<ArrSubHeader2.length;j++)
					{
					  var bgcolor1=(j==0)?"#FFFFFF":bgcolor;
					  
					  table += '<td height="25" bgcolor="'+bgcolor1+'"><b>'+ArrSubHeader2[j]+'</b></td>';
					}
					table += '</tr>';
					old_tail = (old_tail!=0)?old_tail:"-";
					new_tail = (new_tail!=0)?new_tail:"-";
					table += '<tr bgcolor="#FFFFFF">';
					table +='<td>&nbsp;</td>';
					table +='<td>'+old_tail+'</td>';
					table +='<td>'+old_tab+'</td>';
					if(actn=="MOVED DOCUMENT" || actn=="DOCUMENT ATTACHED" || actn=="RE-FILE DOCUMENT")
					{
						table +='<td>'+old_value+'</td>';
					}
					else
					{
						table +='<td>'+old_box+'</td>';
					}
					table +='<td>'+new_tail+'</td>';
					table +='<td>'+new_tab+'</td>';
					table +='<td>'+new_recId+'</td>';
					if(actn=="MOVED DOCUMENT" || actn=="DOCUMENT ATTACHED" || actn=="RE-FILE DOCUMENT")
					{
						table +='<td>'+new_value +'</td>';
					}
					else
					{
						table +='<td>'+new_box +'</td>';
					}
					table += '<tr></table>';
			        table += '</td></tr>';	
					t++;
					
				}else if(TampActArray[actn] || $.inArray(actn,ProcessArr)>0 || $.inArray(actn,WorkStatusArr)>0 || $.inArray(actn,RangeStatusArr)>0 || $.inArray(actn,RangeStatusValArr)>0 || actn=="EMAIL SENT") //for general aduit
				{
					table+='<tr id=subrow_'+t+' style="display:none;"><td colspan="10">';
					table += '<table border="0" align="center" width="100%" cellpadding="2" bgcolor="#C4C4C4" cellspacing="1">';
					table += '<tr>';
					if(actn=="WORKSTATUS CHANGED")
					{
						ArrSubHeader1 = new Array();
						ArrSubHeader1[0]="";
						ArrSubHeader1[1]="Process Name";
						ArrSubHeader1[2]="Old Value";
						ArrSubHeader1[3]="New Value";
						
					}
					if(actn=="COMMENT ADDED")
					{
						ArrSubHeader1 = new Array();
						ArrSubHeader1[0]="";
						ArrSubHeader1[1]="Comment Type";
						ArrSubHeader1[2]="Old Value";
						ArrSubHeader1[3]="New Value";
						ArrSubHeader1[4]="Responsibility";
						
					}
					if(actn=="DOCUMENT UPLOADED VIA FSCC")
					{
						ArrSubHeader1 = new Array();
						ArrSubHeader1[0]="";
						ArrSubHeader1[1]="File Name";
					}
					if(actn=="EMAIL SENT")
					{
						ArrSubHeader1 = new Array();
						ArrSubHeader1[0]="";
						ArrSubHeader1[1]="Template Name";
						ArrSubHeader1[2]="Template Type";
						ArrSubHeader1[3]="Template Send To";
						ArrSubHeader1[4]="Template Copy To";
						ArrSubHeader1[5]="Send To";						
					}
					
					if($.inArray(actn,ProcessArr)>0 || $.inArray(actn,WorkStatusArr)>0 || $.inArray(actn,RangeStatusArr)>0 || $.inArray(actn,RangeStatusValArr)>0)
					{
						
						if(actn=="WORK STATUS REORDER" || actn=="PROCESS REORDER")
						{
							ArrSubHeader1 = new Array();
							ArrSubHeader1[0]="";
							ArrSubHeader1[1]="Fieid Name";
							ArrSubHeader1[2]="From Position";
							ArrSubHeader1[3]="To Position";
							
						}
						else
						{
							ArrSubHeader1 = new Array();
							ArrSubHeader1[0]="";
							ArrSubHeader1[1]="Fieid Name";
							ArrSubHeader1[2]="Old Value";
							ArrSubHeader1[3]="New Value";
						}
						
					 }
					
					for(j=0;j<ArrSubHeader1.length;j++)
					{
					  var bgcolor1=(j==0)?"#FFFFFF":bgcolor;
					  if(j==0)
					  {
					  		table +='<td height="25" width="11" bgcolor="'+bgcolor1+'"></td>';
					  }else{
							table +='<td height="25" bgcolor="'+bgcolor1+'"><b>'+ArrSubHeader1[j]+'</b></td>';
					  }
					}
					table += '</tr>';
					table += '<tr bgcolor="#FFFFFF">';
					table +='<td height="25" width="11"></td>';
					
					if(TampActArray[actn]!="" && title!="")
					{ 
						table +='<td><b>'+title+'</b></td>';
					}
					if(actn=="WORKSTATUS CHANGED")
					{
						var StatusVal=old_value.split("&nbsp;&raquo;&nbsp;");
						var NewStatusVal=new_value.split("&nbsp;&raquo;&nbsp;");
						
						table +='<td>'+StatusVal[0]+'</td>';
						if(StatusVal[1]!="" && StatusVal[1]!='')
						{
							table +='<td>'+StatusVal[1]+'</td>';
						}else
						{
							table +='<td>-</td>';
						}
						table +='<td>'+NewStatusVal[1]+'</td>';
						table += '<tr></table>';
						table += '</td></tr>';
						t++;
					}
					else if($.inArray(actn,ProcessArr)>0 || $.inArray(actn,WorkStatusArr)>0 || $.inArray(actn,RangeStatusArr)>0 || $.inArray(actn,RangeStatusValArr)>0)
					{
						
						//table +='<td>'+title+'</td>';
						table +='<td>'+old_value+'</td>';
						table +='<td>'+new_value+'</td>';
						table += '<tr></table>';
						table += '</td></tr>';
						t++;
						
					}
					else if(actn=="EMAIL SENT")
					{
						old_file=(old_file!="")?old_file:"-";
						new_file=(new_file!="")?new_file:"-";
						table +='<td>'+old_file+'</td>';
						table +='<td>'+new_value+'</td>';
						table +='<td>'+old_value+'</td>';
						table +='<td>'+new_file+'</td>';						
						table += '</tr></table>';
						table += '</td></tr>';
						t++;
					}
					else if(actn=="DOCUMENT UPLOADED VIA FSCC")
					{
						var nf=new_file.split(",");
						
						for(var p=0; p<nf.length; p++)
						{
							if(p!=0)
							{ 
								table += '<tr bgcolor="#FFFFFF">';
								table +='<td height="25"></td>';
							}
							table +='<td>'+old_value+"/"+nf[p]+'</td>';
							
							if(p<nf.length)
							{
								table += '</tr>';
							}
								
						}
						
						table += '</table>';
						table += '</td></tr>';
						t++;
					}
					else
					{
						table +='<td>'+old_value+'</td>';
						table +='<td>'+new_value+'</td>';
						table +='<td>'+cmtuname+'</td>';
						table += '<tr></table>';
						table += '</td></tr>';
						t++;
					}
				
				}
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
	document.getElementById("hdnStart").value = 0;
	var fromDateVal = $("#from_date").val();
	var toDateVal = $("#to_date").val();
	
	if(fromDateVal!=""){
		if(toDateVal==""){
			alert("Please Select To Date.")
			return false;
		}
	}
	
	if(toDateVal!=""){
		if(fromDateVal==""){
			alert("Please Select From Date.")
			return false;
		}
	}
	
	var fromDateArr = fromDateVal.split('-');
	var toDateArr = toDateVal.split('-');
	
	
	var fromDate =new Date(fromDateArr[2],fromDateArr[1],fromDateArr[0]);
	var toDate =new Date(toDateArr[2],toDateArr[1],toDateArr[0]);
	
	var fromTime = fromDate.getTime();
	var toTime = toDate.getTime();
	
	if(fromTime>toTime){
		alert("Please Select Valid Date");
		return false;
	}
	 GridLoad(0);
}

function reset_filter_grid()
{
	  document.getElementById("keyword").value="";
	  document.getElementById("opt").value="";
	  document.getElementById("from_date").value="";
	  document.getElementById("to_date").value="";
	  document.getElementById("commenttype").value="";
	  document.getElementById("commenttype").disabled=true;

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
    if(value=="COMMENT ADDED")
	{
		  document.getElementById('commenttype').disabled=false;
	}else{
		  document.getElementById('commenttype').disabled=true;
		  document.getElementById('commenttype').value="";
	}
}


function export_xls()
{
	try
	{
		parentId=(document.getElementById("parentId"))?document.getElementById("parentId").value:"";
		opt=(document.getElementById("opt"))?document.getElementById("opt").value:"";
		keyword=(document.getElementById("keyword"))?document.getElementById("keyword").value:"";
		from_date=(document.getElementById("from_date"))?document.getElementById("from_date").value:"";
		to_date=(document.getElementById("to_date"))?document.getElementById("to_date").value:"";
		filtertype=(document.getElementById("commenttype"))?document.getElementById("commenttype").value:"";
		var hidExp_Type=(document.getElementById("hidExp_Type"))?document.getElementById("hidExp_Type").value:"";
		audit_type=(document.getElementById("audit_type")!="")?document.getElementById("audit_type").value:"";
		mcc_doc_id=(document.getElementById("mcc_doc_id")!="")?document.getElementById("mcc_doc_id").value:"";
     
		if(hdtotal==0)
		{
		  alert("No Record found.");
		  return false;
		}
		if(qry=="")
		{
			ExceptionMsg("Section:MCC Others Audit Trail - Error description: "+err.message+".\n\n");
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
		 if(filtertype!="")
		 {
			 var filtertype1="&commenttype="+filtertype;
			 strsting=strsting+filtertype1;
		 }
		  if(keyword!="")
		 {
			 var keyword1="&keyword="+keyword;
			 strsting=strsting+keyword1;
		 }
		 if(audit_type!="")
		 {
			 var auditRev = "&audit_type="+audit_type;
			 strsting=strsting+auditRev;				 
		 }
		 if(mcc_doc_id!="")
		 {
			 var mccStr = "&mcc_doc_id="+mcc_doc_id;
			 strsting=strsting+mccStr;				 
		 }
	if(parentId==-1)
	{
		window.open("export_audit_trail.php?hidtitle="+escape(hidtitle)+"&hidqry="+qry+"&hidwca="+escape(wca)+"&hidExp_Type=MCC_OTHERS&parentId="+parentId+"&Start="+hdnStart+strsting,"mccOtherswindow","","false");  
	}
	else
	{
	window.open("export_audit_trail.php?hidtitle="+escape(hidtitle)+"&hidqry="+qry+"&hidwca="+escape(wca)+"&hidExp_Type=MCC_OTHERS&parentId="+parentId+"&tab_name="+escape(tab_name)+"&doc_type="+doc_type+"&mcc_type="+mcc_type+"&Start="+hdnStart+strsting,"mccOtherswindow","","false");  
	}
	  
	}catch(err)
	{
	    ExceptionMsg("Section:MCC Others Audit Trail - Error description: "+err.message+".\n\n");
		alert(exceptionmessage);
		return false;
	}
}


function ExceptionMsg(message)
{
  xajax_fn_exceptionmsg(message);
}