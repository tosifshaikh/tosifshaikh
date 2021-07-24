var headerObj = new Object();
var lovObj = new Object();
var autoFilterObj= new Object();
var UserID=0;
var UserLevel = 0;
var flgSubmit = 0;
var user_name='';
var tab_name = '';
var expRemObj = new Object();
var expremColumnObj = new Object();
var monthWeekObj = {d:"Day",m:"Month",w:"Week"};

Object.keys=Object.keys||function(o,k,r){r=[];for(k in o)r.hasOwnProperty.call(o,k)&&r.push(k);return r}

function validateForm()
{
	try{	
	var rowstoadd = $("#rowstoadd").val();
	var AuditObj = new Object();
	if(rowstoadd<1){
		alert("The number of rows should be minimum 1.")
		return false;
	}
	var mailFlag=0;
	var tempObj = new Object();
	var InsertObj = new Object();
	var noteObj = new Object();
	var eRobj = new Object();
	var is_reminderVal = 0;	
	var mailObj  = new Object();
	var tempMailObj= new Object();
	for(var i=0;i<parseInt(rowstoadd);i++){
		var counterBlank = 0;
		var erChk = 0;
		var tempInsertObj = new Object();
		for(hid in headerObj){
			var ColID = headerObj[hid]['column_id'];
			var is_reminder= headerObj[hid]['is_reminder'];	
			if($("#field_"+i+"_"+headerObj[hid]['column_id']) && $("#field_"+i+"_"+headerObj[hid]['column_id']).val()!="" 
				 && $("#field_"+i+"_"+headerObj[hid]['column_id']).val()!="Enter Text"){
				counterBlank=1;
				
				if((is_reminder==2 || is_reminder==1) && erChk==0){	
								
					eRobj = getExpRemDateVal(i,ColID,is_reminder);
					is_reminderVal	=ColID;													
					 var expDateCol = expremColumnObj["Expiry Date"];
					 var RemDateCol = expremColumnObj["Reminder Date"];					
					 $("#field_"+i+"_"+expDateCol).val(eRobj["expVal"]);
					 $("#field_"+i+"_"+RemDateCol).val(eRobj["remVal"]);
					 erChk=1;
					 
					 var whrStatus=0;
					 var curDate= new Date();
						var a = new Date(curDate).getTime();
						a=Math.abs(a);			
						
						
						var b=gettimestampofdate($("#field_"+i+"_"+is_reminderVal).val());
						
						if(b<=a)
						{
							whrStatus=3;
						}
						if(eRobj["remVal"]!='')
						{
							var b=gettimestampofdate(eRobj["remVal"]);
							if(b<=a)
							{
								whrStatus=2;
							}
						}				
						if(eRobj["expVal"]!='')
						{
							var b=gettimestampofdate(eRobj["expVal"]);
							if(b<=a)
							{
								whrStatus=1;
							}
						}	
						
						if(whrStatus!=0){
							mailFlag=1;		
						}
						tempMailObj["mailFlag"]=mailFlag;
						tempMailObj["whrMailStatus"]=whrStatus;
					}				
						
						
				tempInsertObj[headerObj[hid]['column_id']] = $("#field_"+i+"_"+headerObj[hid]['column_id']).val();
			}			
		}
		
		
		
		if(counterBlank==0){
			tempObj[i+1]=i+1;//i+1 heree for row number
		} else {			
			InsertObj[i] = tempInsertObj;
			AuditObj[i] = getAuditObj();
			noteObj[i]= getNotesObj();
			mailObj[i]=tempMailObj;
			
		}
	}
	var RowCount = Object.keys(tempObj).length;
	var Rows = Object.keys(tempObj);	
	if(rowstoadd==RowCount){
		alert('Please input a value for at least one Row.');
		return false;
	}
	if(RowCount!=0){
		alert("Row number "+Rows+" are blank.\nThese rows will not be added to Status");
		for(x in tempObj){
			$("#row_"+x).remove();
		}
	}	
	var InsVal = new Object();
	if(flgSubmit==0){
		flgSubmit=1;
		InsVal={"AuditVal":AuditObj,"dataVal":InsertObj,"notes":noteObj,"mailObj":mailObj};
		$("#insertVal").val(JSON.stringify(InsVal));
		$('#addrowform').submit();	
		return true;
	}
	}catch(e){
		alert(e);
	}	
}
function renderGrid()
{
	try{
		var headerCount =Object.keys(headerObj).length+4;
		
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
		
		var table='<table width="100%" cellspacing="1" cellpadding="3" border="0" class="tableContentBG">';
		table +='<tr class="tableWhiteBackground"><td class="tableCotentTopBackgroundNew" colspan="'+headerCount+'"></td></tr>';
		table +=getHeaderRow();	
		table +=getFilterRow();	
		table +='</table>';
		$("#addRowTable").html(table);
		} catch(e){
			alert(e);
	}
}

function getHeaderRow()
{
	var tempHeaderTable='';
	tempHeaderTable+='<tr id="h_row3" class="tableWhiteBackground setinternalwidth">';
	tempHeaderTable+='<td align="left" class="tableCotentTopBackgroundNew">Sr No.</td>';	
	for(hid in headerObj){
		tempHeaderTable+='<td align="left" class="tableCotentTopBackgroundNew">'+headerObj[hid]['header_name']+'</td>';		
	}
	return tempHeaderTable+='</tr>';
}
function getFilterRow()
{
	var tempFilterTable='';	
	var rowstoadd = $("#rowstoadd").val();
	for(var i=0;i<parseInt(rowstoadd);i++){	
		tempFilterTable+='<tr id="row_'+parseInt(i+1)+'">';
		tempFilterTable+='<td class="tdContentBg" nowrap>'+parseInt(i+1)+'</td>';
		for(fid in headerObj){
			tempFilterTable+='<td class="tdContentBg" nowrap>'+getObjElement(fid,i)+'</td>';
		}	
		tempFilterTable+='</tr>'
	}
	return tempFilterTable;	
}


function getObjElement(col_id,Row)
{
	var tempElement = '';
	var filterType = headerObj[col_id]['filter_type'];
	var filterAuto = headerObj[col_id]['filter_auto'];
	var ColName = headerObj[col_id]['header_name'];
	var ColIdName=headerObj[col_id]['column_id'];
	var	addFun='';
	var addStr ='';
	if(filterAuto==0){
		var IdStr ="field_"+Row+'_'+headerObj[col_id]['column_id'];		
	} else {
		IdStr="lov_"+Row+'_'+headerObj[col_id]['column_id'];;
		addFun='onChange="setTextBox(this.value,this.id);"';
		addStr='<option style="background-color:#CC99FF;" value="Enter Text">Enter Text</option>';
	}
	if(ColName=="Expiry Date" || ColName=="Reminder Date" ){
		expremColumnObj[ColName]=ColIdName;	
	}
	if(ColName=="Expiry Period" || ColName=="Reminder Period" ){
		expremColumnObj[ColName]=ColIdName;		
		return getRemExpLov(ColName,Row,ColIdName);
	}
	
	if(filterType==0 || filterType==1){
		tempElement+='<input type="text" class="addpg_multiplerows_input" name="'+IdStr+'" id="'+IdStr+'" value="">';
	} else if(filterType==2){
		tempElement+='<select '+addFun+' class="selectauto"  style="overflow:scroll;" id="'+IdStr+'" name="'+IdStr+'">';
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
				
				tempElement+='<option '+sel+' '+enbDisStr+' value="'+lovval+'">'+lovval+'</option>';						
			}			
		}		
		tempElement+='</select>';
		if(filterAuto==1){
		tempElement+='<textarea style="display:none;" onFocus="this.value=\'\'" name="field_'+Row+'_'+headerObj[col_id]['column_id']+'" id="field_'+Row+'_'+headerObj[col_id]['column_id']+'" cols="22" rows="4">Enter Text</textarea>';	
		}
	} else if(filterType==3){
		if(ColName=="Expiry Date" || ColName=="Reminder Date"){
				tempElement+='<input readonly="readonly" type="text" name="'+IdStr+'" id="'+IdStr+'" value="">';
		} else {
			tempElement+='<input type="text"  id="'+IdStr+'" name="filter[]" class="date_input" tabindex="10"   onkeydown="return filter(this.id,event);" />';
			tempElement+='&nbsp;<img border="0" onclick="displayCalendar(document.getElementById(\''+IdStr+'\'),\'dd-mm-yyyy\',this);return false;" ';
			tempElement+=' style="width:18px;height:17px; alignment-adjust:middle; border:0px solid transparent; cursor:pointer;" src="images/Calender.gif" alt="Date"> ';
		}
	}
	return tempElement;	
}
function numOnly(field, event)
{
	if(field.value>999){
		alert("T");
	}
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
 	 if ((key==13)){
		renderGrid();
		return false;
	 } else if ((key==null) || (key==0) || (key==8) || (key==27) || (key==9)){
	 	return true;
	 } else if ((valid_str.indexOf(keychar) > -1)){
           window.status = "";
           return true;
     }else {
           window.status = "Field Accepts Alphabetical Characters only"; 
           return false;
     }
	return false;	 
 }
function getAuditObj()
{
	var tempAudit = new Object();
	var section=$("#sectionVal").val();
	var sub_section=$("#sub_sectionVal").val();
	if($("#pos").val()=="above"){
		actID= 12;	
	} else {
		actID = 13;
	}
	tempAudit = {"user_id":UserID,"user_name":user_name,"action_id":actID,"type":$("#type").val(),"tab_name":tab_name,"client_id":$("#client_id").val(),"tail_id":$("#comp_id").val(),"section":section,"sub_section":sub_section};		
	return tempAudit;
}
function setTextBox(txtVal,LovColID)
{
	var idStr = LovColID.replace("lov", "field");
	$("#"+idStr).html(txtVal);
	if(txtVal == "Enter Text"){
		$("#"+idStr).css("display","");
	}else{
		$("#"+idStr).css("display","none");		
	}
	return false;
}

function getExpRemDateVal(mainID,ColID,is_reminder)
{
	try{	
		var dateVal = $("#field_"+mainID+"_"+ColID).val();			
		var expColumnId = expremColumnObj["Expiry Period"];
		var addexpVal = $("#field_"+mainID+"_"+expColumnId).val();
		var remColumnId = expremColumnObj["Reminder Period"];
		var addRemVal = $("#field_"+mainID+"_"+remColumnId).val();
		var ExpVal = "";
		var RemVal = "";
		if(addexpVal!="" && dateVal!=""){					
			var tempDate = dateVal.split("-");
			var adddate = new Date(parseInt(tempDate[2]),parseInt(tempDate[1]- 1),parseInt(tempDate[0])); 
			var monthWeekVal = addexpVal.substr(-1);
			var strLen = parseInt(addexpVal.length)-1;
			var numVal = addexpVal.substr(0,strLen);
			var ExpDate = "";
			if(monthWeekVal=="d"){
				ExpDate = addSubtractDays(adddate,numVal,1);
			} 
			if(monthWeekVal=="m"){												
				ExpDate = addSubtractMonth(adddate,numVal,1);							
			}						
			if(ExpDate!=""){
				var tempExpDate = ExpDate.getDate();
				if(tempExpDate.toString().length==1){
					tempExpDate="0"+tempExpDate;
				}
				var tempExpmonth = ExpDate.getMonth()+1;
				if(tempExpmonth.toString().length==1){
					tempExpmonth="0"+tempExpmonth;
				}
				ExpVal = tempExpDate+"-"+(tempExpmonth)+"-"+ExpDate.getFullYear();							
			}
			
			
			if(addRemVal!="" && is_reminder==2){
				var RemDate = "";
				var monthWeekVal = addRemVal.substr(-1);
				var strLen = parseInt(addRemVal.length)-1;
				var numVal = addRemVal.substr(0,strLen);
			
				if(monthWeekVal=="w"){
					numVal= 7*numVal;
				}
				
				if(monthWeekVal=="d" || monthWeekVal=="w"){
					RemDate = addSubtractDays(ExpDate,numVal,2);
				} 
				if(monthWeekVal=="m"){												
					RemDate = addSubtractMonth(ExpDate,numVal,2);							
				}
				if(RemDate!=""){
					var tempRemDate = RemDate.getDate();
					if(tempRemDate.toString().length==1){
						tempRemDate="0"+tempRemDate;
					}
					var tempRemmonth = RemDate.getMonth()+1;
					if(tempRemmonth.toString().length==1){
						tempRemmonth="0"+tempRemmonth;
					}
					RemVal = tempRemDate+"-"+(tempRemmonth)+"-"+RemDate.getFullYear();								
				}
			}		
		}
		var finalobj = {"expVal":ExpVal,"remVal":RemVal};					
		return finalobj;
	}catch(Error){
		alert(Error)
		funError("getExpRemDateVal","Section : Airworthiness Centre => Component, Main page Js Error <br/> ",Error.toString(),Error);		
	}
}
function getRemExpLov(ClName,mainRcID,ClIdName){
	
	var expRemStr= '';	
	var idVal = '';
	
	idVal = "field_"+mainRcID+"_"+ClIdName;		
	var addFun = "";
	
	if(ClName=="Expiry Period"){
		addFun=' onChange="getRemCombo(this.value,\''+mainRcID+'\');" ';
	} else {
		//addFun=' onChange="loadGrid();" ';
	}
	expRemStr+='<select '+addFun+' class="selectauto" id='+idVal+' name='+idVal+'>';
	if(ClName=="Expiry Period"){		
		expRemStr+='<option value="">Select Expiry Period</option>';
		for(var w in expRemObj){
			var monthWeekVal = w.substr(-1);
			var strLen = parseInt(w.length)-1;
			var numVal = w.substr(0,strLen);
			var expRemVal = numVal+' '+monthWeekObj[monthWeekVal];
			if(parseInt(numVal)>0){
				expRemVal= numVal+' '+monthWeekObj[monthWeekVal]+'s';
			}
			expRemStr+='<option  value='+w+'>'+expRemVal+'</option>';
		}
	} else {
		expRemStr+='<option value="">Select Reminder Period</option>';
		var tempObj = new Object();
		var	exDbVal ="";
		var remColumnId = expremColumnObj["Reminder Period"];
		var expColumnId = expremColumnObj["Expiry Period"];		
		if(mainRcID==0){			
			for(var w in expRemObj){
				for(var k in expRemObj[w]){
					tempObj[k]=k;
				}
			}
		} else{				
			if(exDbVal!=""){				
				tempObj = expRemObj[exDbVal];
			}
		}
		for(var y in tempObj){
			var monthWeekVal = y.substr(-1);
			var strLen = parseInt(y.length)-1;
			var numVal = y.substr(0,strLen);
			var expRemVal = numVal+' '+monthWeekObj[monthWeekVal];
			if(parseInt(numVal)>1){
				expRemVal= numVal+' '+monthWeekObj[monthWeekVal]+'s';
			}			
			expRemStr+='<option value='+y+'>'+expRemVal+'</option>';
		}						
		
	}
	expRemStr+='</select>';	
	return expRemStr;
}
function getRemCombo(expVal,mainrec_id)
{
	var remStr = '';
	var remColumnId = expremColumnObj["Reminder Period"];			
	var	idVal = "field_"+mainrec_id+"_"+remColumnId;
	remStr+='<select class="selectauto" id='+idVal+' name='+idVal+'>';
	remStr+='<option value="">Select Reminder Period</option>';
	for(var rv in expRemObj[expVal]){
		var monthWeekVal = rv.substr(-1);
			var strLen = parseInt(rv.length)-1;
			var numVal = rv.substr(0,strLen);
			var RemVal = numVal+' '+monthWeekObj[monthWeekVal];
			if(parseInt(numVal)>1){
				RemVal= numVal+' '+monthWeekObj[monthWeekVal]+'s';
			}			
			
		remStr+='<option value='+rv+'>'+RemVal+'</option>';
	}
	remStr+='</select>';	
	$("#"+idVal).replaceWith(remStr);
}

function addSubtractDays(theDate,days,flg) {
	if(flg==1){
	    return new Date(theDate.getTime() + days*24*60*60*1000);
	} else {		
		return new Date(theDate.getTime() - days*24*60*60*1000);
	}
}

function addSubtractMonth(theDate,month,flg) {
	if(flg==1){
	   return new Date(theDate.setMonth(theDate.getMonth() + parseInt(month)));
	} else {
		return new Date(theDate.setMonth(theDate.getMonth() - parseInt(month)));
	}
}

function getNotesObj()
{
	var tempNotes = new Object();
	tempAudit = {"admin_id":UserID,"type":$("#type").val(),"client_id":$("#client_id").val(),"component_id":$("#comp_id").val(),"notes_type":UserLevel,"doc_note_type":6};	
	return tempAudit;
}

function gettimestampofdate(fielddate)
{
	var ndate=fielddate;		
	var n=ndate.split("-");
	var newdate=n[2]+"-"+n[1]+"-"+n[0];			
	var NextDate= new Date(n[2],n[1]-1,n[0]);					
	var b = new Date(NextDate).getTime();					
	return Math.abs(b);
}