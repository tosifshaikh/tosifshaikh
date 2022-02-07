var type = 0;
var currentCompID=0;
var fixStrObj = new Object();
var currentTypeId=0;
var ColumnHeaderObj = new Object();
var headerObj = new Object();
var mainCoumnHeaderObj = new Object();
var dataObj = new Object();
var filetrValObj= new Object();
var excludeColumnArr = new Array(); 
var cliStr ='';
var addPath = "./";
var mainHeaderObj = new Object();
var optionValArr = {1:"Aircraft Type",2:"Engine Type"};
var excludeFilterArr= {1:new Array("Aircraft Type","Work Status"),2:new Array("Engine Type")};
var tdIdStrArr = {2:"Engine"};
var compIdArr = {1:"aircraft_type",2:"engine_type"};
var colIdArr = {2:"ENGINETYPE"};
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

Object.keys=Object.keys||function(o,k,r){r=[];for(k in o)r.hasOwnProperty.call(o,k)&&r.push(k);return r}

mainHeaderObj= {1:{
       			 1:{"Client":{"funName":"getClientCombo1"},"Aircraft Type":{"funName":"getCompType","tdId":"airtype"},"Tail":{"funName":"getTail","tdId":"TailListId"}},
				 2:{"Manufacturer":{"filterType":1,"id":"manufracutre"},"Owner/Lessor":{"filterType":1,"id":"owner"},"Date of Manufacture":{"filterType":2,"id":"date_man","dateIconId":"cal3"}},				 
				 3:{"Date Of Registration":{"filterType":2,"id":"date_reg","dateIconId":"cal1"},"Country of Registration":{"filterType":1,"id":"reg_country"}},				 
				 4:{"Last Review Date":{"filterType":2,"id":"date_lr","dateIconId":"cal4"},"Next Review Date":{"filterType":2,"id":"date_nr","dateIconId":"cal5"}}
				 },
				2:{
					1:{"Client":{"funName":"getClientCombo1"},"TSN":{"filterType":1,"id":"txtTSN"},"Currently On":{"funName":"getCurrentlyOnCombo"}},
					 2:{"Engine Type":{"funName":"getCompType","tdId":"TdCompType"},"CSN":{"filterType":1,"id":"txtCSN"},"Aircraft Position":{"funName":"getAirpostion","tdId":"tdAircraftPosition"}},				 
					 3:{"Part Number":{"filterType":1,"id":"txtPartNumber"},"Owner/Lessor":{"filterType":1,"id":"txtLessorOwner"},"Entry into Service Date":{"filterType":2,"id":"txtServiceDate",
					 	"dateIconId":"cal2"}},
					 4:{"Serial Number":{"filterType":1,"id":"txtSerialNumber"},"Install Date":{"filterType":2,"id":"txtIntsallDate","dateIconId":"cal1"},
					 	"Return Date":{"filterType":2,"id":"txtReturnDate","dateIconId":"cal4"}},
					 5:{"Manufacturer":{"filterType":1,"id":"txtManufacturer"},"Status At":{"filterType":2,"id":"txtStatusAt","dateIconId":"cal3"}}
				}
	};
				 
	mainCoumnHeaderObj={
					   1:{
					   "Aircraft Type":{"col_id":"ICAO"},"Client_id":{"col_id":"CLIENTID"},"component_id":{"col_id":"TAIL"},"Client":{"col_id":"COMP_NAME","elemId":'client_id'},
					   "Tail":{"col_id":"tailName",'elemId':'txtTail'},"MSN Number":{"col_id":"tailMSNO","elemId":"tailMSN"},"Manufacturer":{"col_id":"MANUFACTURER","elemId":"manufracutre"},
					  	"Date of Manufacture":{"col_id":"MANDATE","elemId":"date_man","dateField":1},"Date of Registration":{"col_id":"REGDATE","elemId":"date_reg","dateField":1},
						"Country of Registration":{"col_id":"REGCOUNTRY","elemId":"reg_country"},"Owner/Lessor":{"col_id":"OWNER","elemId":"owner"},"Last Review Date":{"elemId":"date_lr"},"Next Review Date":{"elemId":"date_nr"},
						"Work Status":{"elemId":"comp_status"}
						},
						2:{
							"Engine Type":{"col_id":colIdArr[type],"elemId":compIdArr[type]},"component_id":{"col_id":"id"},"Client_id":{"col_id":"client","elemId":"client"},"Client":{"col_id":"COMP_NAME","elemId":"client_id"},
						   "Part Number":{"col_id":"part_number","elemId":"txtPartNumber"},"Serial Number":{"col_id":"serial_number","elemId":"txtSerialNumber"},
						   "Manufacturer":{"col_id":"manufacturer","elemId":"txtManufacturer"},"TSN":{"col_id":"tsn","elemId":"txtTSN"},"CSN":{"col_id":"csn","elemId":"txtCSN"},
						   "Aircraft Position":{"col_id":"aircraft_position","elemId":"aircraft_position"},"Install Date":{"col_id":"install_date","elemId":"txtIntsallDate","dateField":1},
						   "Status At":{"col_id":"status_at","elemId":"txtStatusAt","dateField":1},"Currently On":{"col_id":"currently","elemId":"currently_on"},
							"Aircraft Type":{"col_id":"ICAO","id":"aircraft_type","elemId":"ICAO"},"Owner/Lessor":{"col_id":"owner_lessor","elemId":"txtLessorOwner"},
							"Entry into Service Date":{"col_id":"service_entry_date","elemId":"txtServiceDate","dateField":1},
							"Return Date":{"col_id":"return_date","elemId":"txtReturnDate","dateField":1}
						}
				     };
excludeColumnArr = Array("Client_id","component_id");
function filterGrid(e,id,obj)
{
	if(e.keyCode==13){		
		filetrValObj[id] = obj.value;
		for(x in filetrValObj){
			if($("#filter_"+x).val()==""){
				delete filetrValObj[x];
			}
		}
		renderGrid();
		
	}
}
function loadGrid(intStart)
{
	try{
		var type = $("#type").val();
	        hdnStart=intStart;
		if(UserLevel==5){
			addPath = "../";
			if(type!=1){
				delete mainCoumnHeaderObj[type]["Aircraft Type"];
				delete mainCoumnHeaderObj[type]["Owner/Lessor"];
				delete mainHeaderObj[type][3]["Owner/Lessor"];
			}
			
		}
		$("#headerDiv").html("");
		headerObj = mainHeaderObj[type];
		ColumnHeaderObj = mainCoumnHeaderObj[type];
		
		getTableHeader();
		if(UserLevel!=5){
	        var filter='';
	        for(x in ColumnHeaderObj)
	        {
	            if(ColumnHeaderObj[x]['elemId'] && $("#filter_"+ColumnHeaderObj[x]['elemId']).length > 0 && $("#filter_"+ColumnHeaderObj[x]['elemId']).val() != '')
	            {
	                filter += "&"+ColumnHeaderObj[x]['col_id']+"="+(($("#filter_"+ColumnHeaderObj[x]['elemId'])) ? $("#filter_"+ColumnHeaderObj[x]['elemId']).val() : "");
	                filetrValObj[ColumnHeaderObj[x]['elemId']] = (($("#filter_"+ColumnHeaderObj[x]['elemId'])) ? $("#filter_"+ColumnHeaderObj[x]['elemId']).val() : "");
	            }
	            else
	            {
	                if(ColumnHeaderObj[x]['elemId'] && filetrValObj[ColumnHeaderObj[x]['elemId']] && $("#filter_"+ColumnHeaderObj[x]['elemId']).val() == '')
	                {
	                    delete filetrValObj[ColumnHeaderObj[x]['elemId']];
	                }
	            }
	        }
		}
	        
		var sectionVal = $("#sectionVal").val();
		var sub_sectionVal= $("#sub_sectionVal").val();
	    var param = "section="+sectionVal+"&sub_section="+sub_sectionVal+"&act=GRID&data="+JSON.stringify(ColumnHeaderObj)+'&type='+type+"&start="+hdnStart+filter;	
		$.ajax({url: "airworthiness_centre.php?t="+(new Date()), async:true,type:"POST",data:param,success: function(data){
		dataObj = eval("("+data+")");
		renderGrid();	
		}});
	}catch(Error){
		funError("loadGrid","Section : Airworthiness Centre => Center => Component, Main page Js Error <br/> ",Error.toString(),Error);		
	}
}
function renderGrid()
{
	var table = '';
	var type = $("#type").val();
	table+='<table class="tableContentBG" width="100%" border="0" cellspacing="1" cellpadding="3">';	
	table+=getHeaderTable();
	table+=getFilterTable();	
	var k = 0;
	var t =0;	
	for(w in dataObj['data']){
		var mainTable = '';	
		mainTable+='<tr style="background-color:#FFFFFF;" >';
		var colSpanLen = Object.keys(ColumnHeaderObj).length;
		mainTable+='<td colspan="'+colSpanLen+'" align="left"><a href="javascript: setHideVal('+t+');"><img width="9" height="9" border="0" id="ImgTypeId_'+t+'" src="'+addPath+'images/minus_active1.jpg"></a>&nbsp;'+w+'</td></tr>';
		k++;
		var innerTable = '';
		for(q in dataObj['data'][w]){
			var flg = 0;
			if(UserLevel==5){
				if(!$.isEmptyObject(filetrValObj)){
					for(e in ColumnHeaderObj){	
						if($("#filter_"+ColumnHeaderObj[e]['elemId']).length>0 && $("#filter_"+ColumnHeaderObj[e]['elemId'])!=''){					
							var valSearch = String($("#filter_"+ColumnHeaderObj[e]['elemId']).val()).toLowerCase();
							var dataVal = String(dataObj['data'][w][q][e]).toLowerCase();					
							var result = dataVal.indexOf(valSearch)>=0;
							if(!result){
								flg=1;
							}	
						}
					}
					
				}
			}
			var AddClass= (k%2==0)?"even":"odd";		
			if(flg==0){
				innerTable+='<tr id="TR_'+t+q+'" class="'+AddClass+'" onclick="setForm(\''+w+'\',\''+q+'\','+t+')">';
				for(y in ColumnHeaderObj){
						if($.inArray(y,excludeFilterArr[type])>=0){	
						innerTable+='<td>&nbsp;</td>';
					} else {
						if($.inArray(y,excludeColumnArr)<0){
							var tdVal = (dataObj['data'][w][q][y]!="")?dataObj['data'][w][q][y]:"&nbsp;"
							innerTable+='<td>'+tdVal+'</td>';
						}
					}
				}
				var comp_id = dataObj['data'][w][q]['component_id'];
				innerTable+= '<td width="3%" align="center"><a onclick="Fun_Open('+comp_id+')"><div class="view_active_icon"></div></a></td>';
				innerTable+='</tr>';
				k++;						
			}
		}
		t++;
		if(innerTable!=''){
			table+=mainTable+innerTable;
		}
	}
	table+='</table>';	
        if(UserLevel != 5)
        getPagging(hdnStart,100,dataObj['total']);    
	$("#divGrid").html(table);
         if($("#pagger").length > 0)
        $("#pagger").css( "float","right");
}

function getHeaderTable()
{
	var headerTable = '';
	headerTable+='<tr>';
	for(x in ColumnHeaderObj){
		if($.inArray(x,excludeColumnArr)<0){
			headerTable+='<td class="tableCotentTopBackground" align="left">'+x+'</td>';
		}
	}
	headerTable+='<td align="left" class="tableCotentTopBackground">View</td>';
	return headerTable+='</tr>';
		
}

function getFilterTable()
{
	var filterTable = '';
	var type = $("#type").val();
	filterTable+='<tr>';
	for(x in ColumnHeaderObj){
		if($.inArray(x,excludeColumnArr)<0){
			var filterId =ColumnHeaderObj[x]['elemId'];			
			var filterValue = (filetrValObj[filterId] && filetrValObj[filterId]!="")?filetrValObj[filterId]:"";			
			if($.inArray(x,excludeFilterArr[type])>=0){	
				filterTable+='<td class="tableCotentTopBackground">&nbsp;</td>';
			} else {
				filterTable+='<td class="tableCotentTopBackground" align="left"><input type="text" class="gridinput" name="filter_'+filterId+'" id="filter_'+filterId+'" value="'+filterValue+'" ';
				if(UserLevel==5){
					filterTable+=' onkeydown="filterGrid(event,\''+filterId+'\',this);"/></td>';
				} else { 
					filterTable+=' onkeydown="filter(this.id,event);"/></td>';
				}
			}
		}
	}
	filterTable+='<td align="left" class="tableCotentTopBackground">&nbsp;</td>';
	filterTable+='</tr>';
	return filterTable;		
}


function getTableHeader()
{
	var table = '<table width="100%" border="0" cellspacing="1" cellpadding="1" align="right">';
	for(x in headerObj){
		table+='<tr>';
		for(y in headerObj[x]){
			tdIdStr = '';
			if(headerObj[x][y]["tdId"]){
				tdIdStr =' id="'+headerObj[x][y]["tdId"]+'" ';
			}
			table+='<td width="12%">'+y+':</td>';
			table+='<td align="left" '+tdIdStr+'>';
			table+=getTdElement(headerObj[x][y]);
			table+='</td>';	
		}
		table+='</tr>';		
	}
	table+="</table>";
	
	document.getElementById("HeaderDiv").innerHTML = table;
}


function getCurrentlyOnCombo(){
	
	var type = $("#type").val();
	var locStr = tdIdStrArr[type]+" Shop Visit";
	if(type!=4){
		var locationArr = {1:"Aircraft",2:"On Ground",3:locStr};
	} else {
		locationArr = {"NLG":"NLG","RHMLG":"RHMLG","LHMLG":"LHMLG","CTMLG":"CTMLG","LHBG":"LHBG","RHBG":"RHBG"};
	}
	var str = ''
	str='<select onchange="javascript:chLocation(this);" disabled="disabled" id="selLocation" name="selLocation">';
	str+='<option value="">[ Select Location ]</option>';
	
	for(q in locationArr){
		str+='<option value="'+q+'">'+locationArr[q]+'</option>';
	}	
	str+='</select>';
	str+='<span id="spnselClientTo"><select onchange="javascript:chClientTo(this.value);" disabled="disabled" tabindex="1" id="selClientTo" name="selClientTo">';	                  
  	str+='<option value="0">[ Select Client ]</option>';                  
    str+='</select></span><span id="spnselCurrentlyOn">';
	str+='<select onchange="setAircPos(this.value);" disabled="disabled" id="selCurrentlyOn" name="selCurrentlyOn">';
    str+='<option selected="" value="0">[Select Aircraft]</option>';
	str+='</select></span>';
	return  str;
}
function getClientCombo1()
{
	var typeVal = $("#type").val();
	var cliStr = fixStrObj[typeVal][0];
	str='<select onchange="javascript:chClient(this.value);" disabled="disabled" tabindex="1" id="client_id" name="client_id">';
	str+=cliStr;
	return str+='</select>';
}

function getCompType()
{
	var type = $("#type").val();
	var TypeStr = fixStrObj[type][1];
	var typeVal = type;
	if(type==1){
		var onChangeEve = '';
	} else {
		 var onChangeEve = ' onchange="SetManufactur(this.value);" ';
	}
	var str='<select  '+onChangeEve+' tabindex="1" class="textInput" disabled="disabled" id="'+compIdArr[typeVal]+'" name="'+compIdArr[typeVal]+'">'; 
    str+='<option value="0">[ Select '+optionValArr[typeVal]+' ]</option>';
	str+=TypeStr;
   return str+='</select>';	
}

function getAirpostion()
{
	str= '<select class="textInput" disabled="disabled"  name="txtAircraftPosition" id="txtAircraftPosition">';
    str+= '<option value="0">[Select Position]</option>';
    str+= '</select>';
	return str;
}
function getTail()
{
	str ='';
	str+='<select disabled="" tabindex="3" id="tailno" name="tailno">';
	str+='<option value="">Tail</option>';
	return str+='</select>';
}
function getTdElement(obj){
	if(obj['funName'] && obj['funName']!=''){
		var functionName= obj['funName']+'();';	
		return eval(functionName);		
	}
	
	if(obj['filterType'] && obj['filterType']==1 ){
		return '<input type="text" class="textInput" disabled="disabled" id="'+obj['id']+'" name="'+obj['id']+'">';
	}
	if(obj['filterType'] && obj['filterType']==2 ){
		str = '<input type="text" disabled="disabled" tabindex="13" id="'+obj['id']+'" name="'+obj['id']+'" readonly="readonly" value="" class="textInput">';
         str+='<input type="image" border="0" disabled="disabled" onclick="displayCalendar(document.getElementById(\''+obj['id']+'\'),\'dd-mm-yyyy\',this);return false;" src="'+addPath+'images/Calender.gif" alt="Date" id="'+obj['dateIconId']+'">';
		 if(obj['checkBox']){
			str+='<input type="checkbox" name="'+obj['checkBox']['id']+'" id="'+obj['checkBox']['id']+'" onclick="'+obj['checkBox']['onclick']+'();" />';
		 }
		return str;
	}
}
function setForm(compType,mainId,typeRowId){	
try{
	var RowId  = "TR_"+currentTypeId+'_'+currentCompID;
	    if(currentCompID!=""){
			if($("#"+RowId)){
				$("#"+RowId).css("backgroundColor","");
			}
		}
		var currentRow = "TR_"+typeRowId+mainId;
		$("#"+currentRow).css({"backgroundColor":"#FFCC99","textAlign":"left","cursor":"pointer"});			
		currentCompID= mainId.replace("_","");
		currentTypeId = typeRowId;
		for(y in ColumnHeaderObj){
			if(y!="Client" && ColumnHeaderObj[y]['elemId']){
				$("#"+ColumnHeaderObj[y]['elemId']).val(dataObj['data'][compType][mainId][y])
			}
			if(y=="Client"){				
				$("#"+ColumnHeaderObj[y]['elemId']).val(dataObj['data'][compType][mainId]['Client_id'])
			}		
		}
		var type = $("#type").val();
		xajax_SetForm(currentCompID,type);
	} catch(Error){
		funError("setForm","Section : Airworthiness Centre => Center => Component, Main page Js Error <br/> ",Error.toString(),Error);	
	}
}

function setHideVal(trId)
{
	$("tr[id^=TR_"+trId+"_]").toggle();
	
	if($("#ImgTypeId_"+trId).attr('src') == addPath+"images/minus_active1.jpg"){
		$("#ImgTypeId_"+trId).attr('src',addPath+"images/plus_inactive.jpg");
	} else {
		$("#ImgTypeId_"+trId).attr('src',addPath+"images/minus_active1.jpg");
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
	$("#divGrid").html(table);
}

function manageSubMenuOut_C()
{
	$("#manageSubMenu_C").css("display", "none");
}
function manageSubMenuHOver_C()
{
	$("#manageSubMenu_C").css("position", "absolute");
	$("#manageSubMenu_C").css("display", "block");
}
function openStatuslist()
{
	var type = $("#type").val();
	var section = $("#sectionVal").val();
	var CentreHeader = window.open('airworthiness_centre.php?section='+section+'&type='+type+'&sub_section=2&master_flag=0',
						   'CentreHeader','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes')
	CentreHeader.focus();
}
function openWorkStatuslist()
{
	var type = $("#type").val();
	var section = $("#sectionVal").val();
	var Work_StatusPopUP = window.open('airworthiness_centre.php?section='+section+'&type='+type+'&sub_section=4&master_flag=0',
						   'Work_StatusPopUP','height='+screenH+',width='+screenW+',scrollbars=yes,left=50,resizable=1,fullscreen=yes')
	Work_StatusPopUP.focus();
}
