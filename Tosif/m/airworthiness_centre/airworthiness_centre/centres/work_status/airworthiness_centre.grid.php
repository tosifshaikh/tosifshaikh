<?php
$type = $_REQUEST["type"];
$sectionVal=$_REQUEST["section"];
$sub_sectionVal=$_REQUEST["sub_section"];
$master_flag = $_REQUEST["master_flag"];
$template_id = $_REQUEST["template_id"];
$whr = array("type"=>$type,"master_flag"=>$master_flag,"delete_flag"=>0,"template_id"=>$template_id);
$db->getWorkStatus($whr,$sectionVal,$sub_sectionVal);
$dataArr = array();
$dispArr = array();
while($db->next_record()){
    $dataArr[$db->f("id")] = array("client_id"=>$db->f("client_id"),"status_name"=>$db->f("status_name"),"bg_color"=>$db->f("bg_color"),"font_color"=>$db->f("font_color"),"enable_status_mainClient"=>$db->f("enable_status_mainClient"),
                                   "enable_status_internal"=>$db->f("enable_status_internal"),"hide_status_client"=>$db->f("hide_status_client"),"hide_status_internal"=>$db->f("hide_status_internal"),
                                    "disable_row_internal"=>$db->f("disable_row_internal"),"disable_row_client"=>$db->f("disable_row_client"),"default_status"=>$db->f("default_status"),"disableForURL"=>$db->f("disableForURL"),
                                    "mail_template_id"=>$db->f("mail_template_id"),"rem_exp_status"=>$db->f("rem_exp_status"));
    $dispArr[$db->f("client_id")][$db->f("display_order")] = $db->f("id");
}
$cl_ids = array_keys($dispArr);
$clNameArr = array();
if(count($cl_ids)>0){
    $db->getClientDetails($cl_ids);
	while($db->next_record()){
                $clNameArr["_".$db->f("id")] = $db->f("COMP_NAME");
  	} 
}
$finalDataArr = array();
$finalDataArr["data"] =$dataArr;
$finalDataArr["client"] =$clNameArr;
$finalDataArr["dispData"] =$dispArr;
echo json_encode($finalDataArr);

?>