<?php
$type = $_REQUEST["type"];
$sectionVal=$_REQUEST["section"];
$sub_sectionVal=$_REQUEST["sub_section"];
$master_flag = $_REQUEST["master_flag"];
$template_id = $_REQUEST["template_id"];
$clId =$_REQUEST["client_id"];
$whr = array("type"=>$type,"delete_flag"=>0,"client_id"=>$_REQUEST["client_id"],"hide_status_internal"=>1);
if(isset($_REQUEST["comp_id"]) && $_REQUEST["comp_id"]!=0 && isset($_REQUEST["comp_main_id"]) && $_REQUEST["comp_main_id"]!=0 && isset($_REQUEST["client_id"]) && $_REQUEST["client_id"]!=0)
{
	$whr["component_id"]=$_REQUEST["comp_id"];
	$whr["comp_main_id"]=$_REQUEST["comp_main_id"];
}else {
	$whr["master_flag"]=$master_flag;
	$whr["template_id"]=$template_id;
}
$db->getWorkStatus($whr,$sectionVal,$sub_sectionVal);
$dataArr = array();
$dispArr = array();
$templateIds = array();

while($db->next_record()){
    $dataArr[$db->f("id")] = array("client_id"=>$db->f("client_id"),"status_name"=>$db->f("status_name"),"bg_color"=>$db->f("bg_color"),"font_color"=>$db->f("font_color"),"enable_status_mainClient"=>$db->f("enable_status_mainClient"),
                                   "enable_status_internal"=>$db->f("enable_status_internal"),"hide_status_client"=>$db->f("hide_status_client"),"hide_status_internal"=>$db->f("hide_status_internal"),
                                    "disable_row_internal"=>$db->f("disable_row_internal"),"disable_row_client"=>$db->f("disable_row_client"),"default_status"=>$db->f("default_status"),"disableForURL"=>$db->f("disableForURL"),
                                    "mail_template_id"=>$db->f("mail_template_id"),"rem_exp_status"=>$db->f("rem_exp_status"),"display_order"=>$db->f("display_order"));
    $dispArr[$db->f("client_id")][$db->f("display_order")] = $db->f("id");
	$templateIds[$db->f("mail_template_id")]=$db->f("mail_template_id");
}
$cl_ids = array_keys($dispArr);
$clNameArr = array();
if(count($cl_ids)>0){
    $db->getClientDetails($cl_ids);
	while($db->next_record()){
                $clNameArr["_".$db->f("id")] = $db->f("COMP_NAME");
  	} 
}
$isRemClArr = array();
$whrRem = array("type"=>$type);
if(!isset($_REQUEST["comp_main_id"])  && (!isset($_REQUEST["template_id"]) || (isset($_REQUEST["template_id"]) && $_REQUEST["template_id"]==0))){
	$db->getIsRemColClient($whrRem,$sectionVal,2);
	while($db->next_record()){
		$isRemClArr[$db->f("client_id")] = $db->f("is_reminder");
	}
}
$temIds = array();
if(count($templateIds)>0){
	$temIds = getTemplateData($clId);
}

$finalDataArr = array();
$finalDataArr["data"] =$dataArr;
$finalDataArr["client"] =$clNameArr;
$finalDataArr["dispData"] =$dispArr;
$finalDataArr["isRemClArr"] =$isRemClArr;
$finalDataArr["templateDetail"] =$temIds;

echo json_encode($finalDataArr);

?>