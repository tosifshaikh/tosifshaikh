<?php
$colArr = json_decode($_REQUEST["headerObj"],true);
$LovChkArr= json_decode($_REQUEST["LovValueCheck"],true);
$sectionVal = $_REQUEST["section"];
$sub_sectionVal = $_REQUEST["sub_section"];
$type= $_REQUEST["type"];
$client_id = $_REQUEST["client_id"];
$comp_id = $_REQUEST["comp_id"];

$whrArr = array();
$whrArr["client_id"]=$client_id;
$whrArr["type"]=$type;
$whrArr["comp_id"]=$comp_id;
$whereStr = '';
if(isset($_REQUEST['searchObj'])){
	$searchObj = json_decode($_REQUEST["searchObj"],true);
	if(isset($searchObj["work_status"])){	
		$whereStr .=" and work_status = ".$searchObj["work_status"];		
		unset($searchObj["work_status"]);
	}
	foreach($searchObj as $key=>$val){
		$whereStr .=" and $key like '%$val%' ";		
	}	
}
if($Request['isdelVal']=='HD'){
	$whereStr .=" and delete_flag=0 ";
}
else if($Request['isdelVal']=='SD'){
	$whereStr .=" and delete_flag=1 ";
}
$inbox=0;
if(isset($_REQUEST['inboxmod']) && $_REQUEST['inboxmod']==1)
{
	$db->GetIDInbox($_REQUEST['UID']);
	$db->next_record();
	$whereStr .=" AND id in (".$db->f('comp_main_id').") ";
	$inbox=1;
}
$statusArr = array();
$whr = array("type"=>$type,"delete_flag"=>0,"client_id"=>$client_id,"template_id"=>0,"master_flag"=>0);
$db->getWorkStatus($whr,1,4);
while($db->next_record()){
	$statusArr[]=$db->f("id");
}
$totalRows=0;
$valArr =array();
$recIdArr = array();
$NotesArr = array();
$autoFilterVal = array();
if(count($statusArr)>0)
{
	$whereStr .=" and work_status in (".implode(',',$statusArr).") ";


$db->getCompTotalRows($whereStr,$whrArr);
$db->next_record();
 $totalRows =$db->f('totRows');

$db->getCompRows($colArr,$whereStr,$whrArr);



while($db->next_record()){
	$recID = 0;
	$idVal  = "_".$db->f("id");
	$recIdArr[] =  $db->f("rec_id");
	$valArr[$idVal]['display_order'] = $db->f("display_order");
	$valArr[$idVal]['delete_flag'] = $db->f("delete_flag");
	$valArr[$idVal]['template_id'] = $db->f("template_id");
	$valArr[$idVal]['check_list']= $db->f("check_list");
	$valArr[$idVal]['work_status']= $db->f("work_status");	
	$valArr[$idVal]['delete_cell_flag']= $db->f("delete_cell_flag");
	$valArr[$idVal]['rec_id']= $db->f("rec_id");		
	
	foreach($colArr as $key=>$val){
		$colVal = '';
		$colVal = $db->f($val);
		$valArr[$idVal][$val] =$colVal;		
	}	
}

$i=1;
foreach($valArr as $key=>$val){
	$valArr[$key]['srNo']= $i;
	$i++;
}


if(count($LovChkArr)>0){	
	$autoFilterVal = getCompFilterValues($LovChkArr,$type,$client_id,$comp_id);
}

$whrArr= array("component_id"=>$comp_id,"client_id"=>$client_id,"type"=>$type);
$db->getCompNotes($whrArr);
while($db->next_record()){
	if($db->f("comp_main_id")!=0){
		$NotesArr[$db->f("comp_main_id")]["_".$db->f("id")]=array("notes"=>$db->f("notes"),"notes_type"=>$db->f("notes_type"));
	}
}
}
$parentArr = array();
$parentArr['rowData'] = $valArr;
$parentArr['totalRows'] = $totalRows;
$parentArr['autofiletrVal'] = $autoFilterVal;
$parentArr['notesArr'] = $NotesArr;
echo json_encode($parentArr);
?>