<?php
$data_main_id=$rec_id=$_REQUEST["data_main_id"];
$client_id = $_REQUEST['client_id'];
$type = $_REQUEST['type'];
$groupArr = array();
$rowcheck = array("id"=>$_REQUEST["data_main_id"]);
$db->select('*','fd_airworthi_review_rows',$rowcheck);
while($db->next_record()){
	
	$comp_main_id=$Row_data['comp_main_id']	=$db->f('comp_main_id');
	$component_id=$Row_data['component_id']	=$db->f('component_id');
}
$grpCheck = array("comp_main_id"=>$comp_main_id,"component_id"=>$component_id,"type"=>$type,"client_id"=>$client_id,"delete_flag"=>0,"main_flag"=>1);
$db->select('*','fd_airworthi_review_groups',$grpCheck,"display_order");
while($db->next_record()){
	$groupArr[$db->f('id')]	=$db->f('group_name');
}
$dataArr= array();
$dispArr= array();
if(count($groupArr)>0){
	$groupIDs = array_keys($groupArr);	
	$whr = array();
	$whr['data_main_id'] = $data_main_id ;
	 $query=" select id,file_id,group_id,display_order from fd_airworthi_review_documents where group_id in (".implode(",",$groupIDs).") and data_main_id = ? and status!=3 order by display_order ";
	$db->query($query,$whr);
	while($db->next_record()){
		$dataArr['_'.$db->f('group_id')]['_'.$db->f('id')] = array("fileName"=>$dbs->getfilenameComp($db->f('file_id')),"display_order"=>$db->f("display_order"));		
	}
	
}
if(isset($_REQUEST['updateObj']) && $_REQUEST['updateObj']!=''){
	$upArr = json_decode($_REQUEST['updateObj'],true);
	if(count($upArr)>0){		
		foreach($upArr as $key => $val) {
			$upValArr = array();
			$upValArr['display_order']	 = $val['display_order'];
			$upValArr['group_id']	 = $val['group_id'];
			$whrUpArr = array();
			$whrUpArr['id']	 = $key;
			if(!$db->update('fd_airworthi_review_documents',$upValArr,$whrUpArr)){
				echo 'error in update';
				die;
			}
		}		
	}
	echo '<script language="javascript">window.opener.loadGrid();alert("These Documents have been reordered successfully.");window.close();</script>';
	exit;
}	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<script src="js/drag_drop.js<?php echo QSTR; ?>"></script>
<script src="js/jquery.js"></script>
<script>

var dataObj = new Object();
var dispObj = new Object();
var groupObj = new Object();
var tempUpObj = new Object();
var fromParentId =0;
var fromDispOdr = 0;
var fromDispId =0;
var toDispId =0;

dataObj = eval(<?php echo json_encode($dataArr);?>);
groupObj= eval(<?php echo json_encode($groupArr);?>);
Object.keys=Object.keys||function(o,k,r){r=[];for(k in o)r.hasOwnProperty.call(o,k)&&r.push(k);return r}
function saveOrder()
{
	var updateObj= new Object();	
	var type =$("#type").val();
	var client_id= $("#client_id").val();
	var data_main_id =$("#data_main_id").val();
	var updateStrVal =Object.keys(tempUpObj).length;
	
	if(updateStrVal>0){
		var j=confirm("Are you sure, you want to reorder?");
		if(j==true)
		{
			for(grpId in tempUpObj){
			var grpIdArr = grpId.split('_');
			for(z in dataObj[grpId]){
				var compIDArr = z.split("_");
				var compID = compIDArr[1];
					updateObj[compID] = {"display_order":dataObj[grpId][z]['display_order'],"group_id":grpIdArr[1]};
				}
			}
			if(!$.isEmptyObject(updateObj)){
				$("#updateObj").val(JSON.stringify(updateObj));
			}
//			document.frmReorder.action = 'compliance_matrix.php?section=reorder_doc&tab_id='+tab_id+'&rec_id='+rec_id+'&type='+type;
			document.frmReorder.action = "airworthiness_centre.php?section=4&data_main_id="+data_main_id+"&type="+type+"&client_id="+client_id+"&REORDER";
			document.frmReorder.submit();
		} else {
			return false;
		}
	}
}
function FileMouseOver1(elm)
{
	if(downIndex!="-1"){
		$(elm).css("outline","1px solid #F00");
	}
}
function FileMouseOut1(elm)
{
	$(elm).css("outline","1px solid transparent");
}
function fileMouseUp2(toparent1,tochild1)
{
	if(fromParentId!=toparent1){
		if(!confirm("are you sure want to move file in another Group?")){
			return false;
		}
	}
	var toparent = '_'+toparent1;
	var tochild	= '_'+tochild1;
	var toDispOdr = dataObj[toparent][tochild]['display_order'];
	var toDispId = tochild;
	downIndex = "-1";
	
	if(fromParentId!=toparent1){
		if(!dataObj[toparent]['_'+fromDispId]){
			dataObj[toparent]['_'+fromDispId] = new Object();
		}
		tempUpObj[toparent] = toparent;
		tempUpObj['_'+fromParentId] = '_'+fromParentId;
		var tempDispodr = dataObj['_'+fromParentId]['_'+fromDispId]['display_order'];
		dataObj[toparent]['_'+fromDispId] = dataObj['_'+fromParentId]['_'+fromDispId];
		dataObj[toparent]['_'+fromDispId]['display_order'] = toDispOdr;
		for(ww in dataObj[toparent]){
			var tempOdr = dataObj[toparent][ww]['display_order'];
			if(tempOdr>=toDispOdr && ww!='_'+fromDispId){
				dataObj[toparent][ww]['display_order'] = dataObj[toparent][ww]['display_order']+1;
			}
							
		}
		
		delete dataObj['_'+fromParentId]['_'+fromDispId];
		delete dispObj['_'+fromParentId][tempDispodr];						
	}else{
		if(fromDispOdr!=toDispOdr ){
				tempUpObj[toparent] = toparent;
				tempUpObj['_'+fromParentId] = '_'+fromParentId;
				var tempDispObj = new Object();
				for(ww in dataObj[toparent]){
					var tempOdr = dataObj[toparent][ww]['display_order'];
					if(tempOdr>fromDispOdr && tempOdr<=toDispOdr){
						dataObj[toparent][ww]['display_order'] = dataObj[toparent][ww]['display_order']-1;			
					}else if(tempOdr<fromDispOdr && tempOdr>=toDispOdr){
						dataObj[toparent][ww]['display_order'] = dataObj[toparent][ww]['display_order']+1;			
					}		
				}					
		}
		dataObj[toparent]['_'+fromDispId]['display_order'] = toDispOdr;
	}
	renderDocsGrid();
}
function fileMouseDown2(fromparent,fromchild)
{
	fromParentId =  fromparent;
	fromDispOdr = dataObj['_'+fromparent]['_'+fromchild]['display_order'];
	fromDispId = fromchild;
	downIndex=0;
	return false;
}
function updateDispObj()
{
	fromParentId =0;
	fromDispOdr = 0;
	fromDispId =0;
	
	dispObj = new Object();
	for(x in dataObj){
		for(y in dataObj[x]){
			var dispOdr = dataObj[x][y]['display_order'];
			if(!dispObj[x]){
				dispObj[x] = new Object();
			}
			dispObj[x][dispOdr] = y;			
		}
	}	
}
function renderDocsGrid()
{
	updateDispObj()
	var grpDivTable ='';
	if(!$.isEmptyObject(dataObj)){
		for(grpId in dispObj){
			var grpIdArr = grpId.split("_");
			var groupID = grpIdArr[1];
			grpDivTable +='<div class="group_box" id="group'+grpId+'" style="border: 1px solid rgb(102, 0, 102);">';
			grpDivTable +='<div class="group_header"><span>'+groupObj[groupID]+'</span></div>';
			for(dispId in dispObj[grpId]){
				var compDocIdArr = dispObj[grpId][dispId].split("_");
				var compDocId =compDocIdArr[1];
				var fileName = dataObj[grpId]['_'+compDocId]['fileName'];			
			grpDivTable +='<div class="group_doc" id="group'+grpId+'_doc">';
			grpDivTable +='<table width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #000; margin:2px 0"  '; 
			grpDivTable +='  onmouseover="javascript:FileMouseOver1(this);"onmouseout="javascript:FileMouseOut1(this);"  id="fileId_'+compDocId+'"> ';
			grpDivTable +='<tr>';
			grpDivTable +='<td onmouseup="fileMouseUp2('+groupID+','+compDocId+');"  onmousedown="return fileMouseDown2('+groupID+','+compDocId+');" style="width: 40px; margin-top: 35px;margin-left: 10px; height: 32px; ';
			grpDivTable +=' background-image: url(\'images/move.gif\');  background-repeat: no-repeat; background-position:center; margin-right: 10px; cursor:move; " >';
			grpDivTable +='</td>';
			grpDivTable +='<td style="padding:5px;"><div id="fileName'+compDocId+'">'+fileName+'</div></td>'
			grpDivTable +='</tr>';
			grpDivTable +='</table></div>';
			}
			grpDivTable +='</div>';
		}
	} else {
	grpDivTable='<table width="100%"><tr><td align="center" colspan="2">No Files Found</td></tr></table>';	
	}
	
	$("#divGrid").html(grpDivTable);	
}
</script>
</head>
<body>
<form name="frmReorder" id="frmReorder" method="post" action="">
<input type="hidden" name="type" id="type" value="<?php echo $type;?>" />
<input type="hidden" name="data_main_id" id="data_main_id" value="<?php echo $data_main_id;?>" />
<input type="hidden" name="client_id" id="client_id" value="<?php echo $client_id;?>" />
<input type="hidden" name="updateObj" id="updateObj" value="" />
</form>
<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" class="whitebackgroundtable">
  <tr>
    <td valign="top" class="whiteborderthree">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="80%" align="left" valign="middle" class="MainheaderFont">Reorder Documents</td>
        <td width="20%" align="right" valign="middle"><?php
        $btn = array(
            
            "6"=>array("onclick"=>"saveOrder()"),
            "14"=>array("onclick"=>"window.close()")
        );
        echo hooks_getbutton($btn);
        unset($btn);
        ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
   <td id="searchDiv" class="pink_roundedcorner"><div id="divGrid"></div>
  </tr>
   <tr>
    <td height="45" align="right"><?php
        $btn = array(
            
            "6"=>array("onclick"=>"saveOrder()"),
            "14"=>array("onclick"=>"window.close()")
        );
        echo hooks_getbutton($btn);
        unset($btn);
        ?></td>
  </tr>
  </table>
  </td>
  </tr>
  </table>
  
</body>
</html>
<script>
renderDocsGrid();
</script>
