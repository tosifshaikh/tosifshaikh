<?php
$dataArr = array();
$type = $_REQUEST["type"];
$sectionVal = $_REQUEST["section"];
$sub_sectionVal= $_REQUEST["sub_section"];

$tbl = $Air_tblArr[$sectionVal][2];
$whr = array("type"=>$type,"template_id"=>0,"delete_flag"=>0,"view_flag"=>0,"filter_type"=>2);
if(isset($_REQUEST["client_id"]) && $_REQUEST["client_id"]!=0){
	$whr["client_id"]=$_REQUEST["client_id"];	
}
if(isset($_REQUEST["template_id"]) && $_REQUEST["template_id"]!=0){
	$whr["template_id"]=$_REQUEST["template_id"];	
}
if(isset($_REQUEST["comp_id"]) && $_REQUEST["comp_id"]!=0 && isset($_REQUEST["comp_main_id"]) && $_REQUEST["comp_main_id"]!=0 ){
	$whr["component_id"]=$_REQUEST["comp_id"];
	$whr["comp_main_id"]=$_REQUEST["comp_main_id"];
}
$coliDs = array();
$db->getHeaders($whr,$sectionVal,2);
while($db->next_record()){       
	$dataArr[$db->f("client_id")]["_".$db->f("id")] =$db->f("header_name");
	$coliDs[$db->f("id")]=$db->f("id");
}

$lovArr = array();
$dispArr= array();
if(count($dataArr)>0){	
	$db->getLovValue($arr,$sectionVal,2,$coliDs);
	while($db->next_record()){
		$lovArr[$db->f("id")] = array("lov_value"=>$db->f("lov_value"),"is_active"=>$db->f("is_active"),"client_id"=> $db->f("client_id"),"display_order"=>$db->f("display_order"),"parent_id"=>$db->f("column_id"));
		$dispArr[$db->f("column_id")][$db->f("display_order")] = $db->f("id");
	 }
}

$clIdArr = array();
$clIDs = array_keys($dataArr);
if(count($clIDs)>0){
    $db->getClientDetails($clIDs);
	 while($db->next_record()){
                $clIdArr["_".$db->f("id")] = $db->f("COMP_NAME");
  	} 
}
$finalArr =array();
$finalArr["dataArr"]= $dataArr;
$finalArr["client"]= $clIdArr;
$finalArr["lovArr"]= $lovArr;
$finalArr["dispArr"]= $dispArr;
echo json_encode($finalArr);
?>