<?php
$whr = array("type"=>$_REQUEST["type"],"delete_flag"=>0,"client_id"=>$_REQUEST["client_id"]);
$type = $_REQUEST["type"];
$sectionVal=$_REQUEST["section"];
$sub_sectionVal=$_REQUEST["sub_section"];
$db->Get_Arc_sequence($whr,$sectionVal,$sub_sectionVal);
$dataArr = array();
$dispArr = array();

while($db->next_record()){    

    $dataArr[$db->f("id")] = array("header_name"=>$db->f("header_name"),"expiry_period"=>$db->f("expiry_period"),"reminder_period"=>$db->f("reminder_period"),"template_id"=>$db->f("template_id"),"display_order"=>$db->f("display_order"),"client_id"=>$db->f("client_id"),"check_list"=>$db->f("check_list"));
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
//$finalDataArr["lovArr"] =$lovArr;
echo json_encode($finalDataArr);


?>