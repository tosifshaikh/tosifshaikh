<?php
$colArr = json_decode($_REQUEST["headerObj"],true);
$start = $_REQUEST["start"];
$limit = $_REQUEST["limit"];
$p=end(explode("/",$_SERVER['PHP_SELF']));
$p=explode("?",str_replace($p,"",curPageURL()));
$clip_path=$p[0]."getdownload.php";
$whrgroup = array();
$whrgroup['tab_id']=$_REQUEST['tab_id'];
$type=$whrgroup['type']=$_REQUEST['type'];
$client_id=$_REQUEST['client_id'];

$groupArr = array();
$groupIdsArr = array();
$rowcheck = array("id"=>$_REQUEST["rec_id"]);
$LovChkArr= json_decode($_REQUEST["LovValueCheck"],true);

$db->getSingleRows($colArr,$rowcheck);
$db->next_record();
$TempArrResult=$db->arr_result;
$db->free();
//print_r($TempArrResult);
foreach($TempArrResult as $key=>$val)
{
	$recID  = "_".$val["id"];
	$rowResult[$recID]=$val;
}

$db->select('*','fd_airworthi_review_rows',$rowcheck);
while($db->next_record()){
	
	$comp_main_id=$Row_data['comp_main_id']	=$db->f('comp_main_id');
	$componentID=$Row_data['component_id']	=$db->f('component_id');
}
$grpCheck = array("comp_main_id"=>$Row_data['comp_main_id'],"type"=>$_REQUEST["type"],"client_id"=>$_REQUEST["client_id"],'component_id'=>$Row_data['component_id'],"delete_flag"=>0);

$db->select('*','fd_airworthi_review_groups',$grpCheck,"display_order");
while($db->next_record()){
	if($db->f('main_flag')==1){
		$groupIdsArr[] = $db->f('id');
		$groupArr["_".$db->f('id')]	=$db->f('group_name');
	}
}

$whrarr = array();
$whrarr['rec_id']=$_REQUEST['rec_id'];
$docArr = array();
$fileIDArr = array();
$tot=0;
$addGridStr = '';
if($_REQUEST['StatusVal']==3){
	$addGridStr.=" and a.status=3 ";
} else { 
$addGridStr.=" and a.status!=3 ";
}
$idValArr = array();
if(count($groupArr)>0){	
 	$query = $db->getAirworthiDocs($groupIdsArr,$addGridStr);		
	$db->query($query,$whrarr);
	while($db->next_record()){
		$boxId = $db->f('group_id');
		$curId ='_'.$db->f('id');
		$fileID = $db->f('file_id');
		$docArr[$boxId][$curId] = $fileID;
		$idValArr[$curId]= array("file_id"=>$fileID,"status"=>$db->f("status"),'group_id'=>$boxId);
		$fileIDArr[$fileID]['status'] =$db->f('status'); 
		$fileIDArr[$fileID]['box_id'] =$boxId; 
		$fileIDArr[$fileID]['file_id'] = $db->f('file_id');
		$fileIDArr[$fileID]['container'] =$db->f('file_container');
		$fileIDArr[$fileID]['file_name'] =$db->f('file_name');
		$fileIDArr[$fileID]['file_size'] =(number_format($db->f("file_length")/1024,2));
		$fileIDArr[$fileID]['doc_id'] =$db->f('id');
		$fileIDArr[$fileID]['document_path'] =str_replace("#","%23",$db->f('file_path'));                
	      	$fileIDArr[$fileID]['issign'] =$db->f('isSign');
	       	$fileIDArr[$fileID]['tdrid'] =$db->f('tdrid');
	       	$fileIDArr[$fileID]['tdrgroupid'] =$db->f('tdrgroupid');
		$fileIDArr[$fileID]['download_path'] = $clip_path."/".$db->f("file_name")."?".encrypt("download=".$db->f("file_id"),"mypass","abcd12344")."flg=2";
		$tot++;
	}	
}

$notesUser=$db->getNotesUser($client_id);
$notes=$db->getNotesData($_REQUEST['rec_id']);
if(count($notes['userid'])>0)
{
	$userNames= $db->getUserName($notes['userid']);
	
	foreach($userNames as $key=>$val)
	{
		$notesUser['username'][$key]=$val;
	}
	
}
$autoFilterVal = array();
if(count($LovChkArr)>0){	
	$autoFilterVal = getReviewFilterValues($LovChkArr,$comp_main_id,$componentID);
}
$mainValArr=array();
$mainValArr['rowData'] = $rowResult;
$mainValArr['groupVal'] = $groupArr;
$mainValArr['group_fileid_Val'] = $docArr;
$mainValArr['file_id_Val'] = $fileIDArr;
$mainValArr['idValArr'] = $idValArr;
$mainValArr['total'] = $tot;
$mainValArr['MainUserArr'] = $notesUser['MainUserArr'];
$mainValArr['username'] = $notesUser['username'];
$mainValArr['UserLevel'] = $notesUser['level'];
$mainValArr['NoteData'] = $notes;
$mainValArr['autofiletrVal'] = $autoFilterVal;
echo json_encode($mainValArr);
?>
