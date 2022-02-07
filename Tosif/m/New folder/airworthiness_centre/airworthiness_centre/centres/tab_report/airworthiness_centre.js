var dataObj = new Object();
var statusObj = new Object();
var totalRow = 0;
var  headerObj = {1:"Sr.No",2:"Status",3:"Total"};
var selClient = 0;
var totalRowObj = new Object();
Object.keys=Object.keys||function(o,k,r){r=[];for(k in o)r.hasOwnProperty.call(o,k)&&r.push(k);return r}

function fn_selectedClient(val){
	getSelData(val);
}

function getSelData(SelVal)
{
	selClient = SelVal;
	renderGrid();
}
function renderGrid()
{
	if(selClient==0){
		$("#divGrid").html("");
		$("#btnTd").css("display","none");
	} else{		
		var table = '<table width="100%" cellspacing="0" cellpadding="5" bordercolor="#666666" border="1" style="border-collapse: collapse;">';
		table+=getHeader();
		if(totalRow>0){
			table+=getData();
			table+=getTotalRow();
		}  else {
			table+='<tr class="tdContentBg"><td align="center" colspan="3"><strong>No Record Found</strong></td></tr>';
		}
		table+= '</table>';
		$("#divGrid").html(table);
		$("#btnTd").css("display","");
		
	}
}

function getHeader()
{
	var headerTable = '';
	headerTable+='<tr>';
	for(x in headerObj){
		var alignstr="center";
		if(x==2){
			alignstr='left';
		}
		headerTable+='<td align="'+alignstr+'" class="tableCotentTopBackground">'+headerObj[x]+'</td>';
	}
	headerTable+='</tr>';
	return headerTable;
}

function getData()
{
	var dataTable = '';
	var j=1;
	var tempNewObj = new Object();
	if(selClient!=0){
		tempNewObj[selClient]= dataObj[selClient];
	} 
	for(y in tempNewObj){
		for(x in tempNewObj[y]){
			if(statusObj[x]){
				dataTable+='<tr>';
				dataTable+='<td align="center" style="background-color:'+statusObj[x]['bg_color']+';color:'+statusObj[x]['font_color']+';">'+j+'</td>';
				dataTable+='<td>'+statusObj[x]['name']+'</td>';
				dataTable+='<td align="center">'+tempNewObj[y][x]+'</td>';
				dataTable+='</tr>';
				j++;
			}
		}
	}
	return dataTable;	
}

function getTotalRow()
{
	var totalTable = ''
	totalTable+='<tr bgcolor="#e5e564">';
	totalTable+='<td colspan="2" align="right"><strong>Grand Total</strong></td>';
	var totRows = 0;
	if(selClient!=0){
		if(totalRowObj[selClient])
		totRows = totalRowObj[selClient];
		else 
		totRows = 0;
		
	}
	totalTable+='<td align="center"><strong>'+totRows+'</strong></td>';
	totalTable+='<tr>';
	return totalTable;
}


function export_xls()
{
	var tempNewObj = new Object();
	if(selClient!=0){
		tempNewObj[selClient]= dataObj[selClient];
	} 
	var totRows = 0;
	if(selClient!=0){
		if(totalRowObj[selClient])
		totRows = totalRowObj[selClient];
		else 
		totRows = 0;
		
	}
	$("#auditType").val("export_audit");
	var exportVal = new Object();
	exportVal={"dataObj":tempNewObj,"statusObj":statusObj,"headerObj":headerObj,"totalRow":totRows};	
	$("#exportVal").val((JSON.stringify(exportVal)));	
	document.frm.submit();		
}
