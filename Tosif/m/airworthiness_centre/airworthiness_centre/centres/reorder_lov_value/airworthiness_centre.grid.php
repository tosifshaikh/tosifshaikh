<?php
$dataArr = array();
$type = $_REQUEST["type"];
$sectionVal = $_REQUEST["section"];
$arr=array("type"=>$type);
$lovArr = array();
$db->getLovValue($arr,$sectionVal);
while($db->next_record()){
	$lovArr[$db->f("id")] = array("lov_value"=>$db->f("lov_value"),"is_active"=>$db->f("is_active"),"client_id"=> $db->f("client_id"),"display_order"=>$db->f("display_order"),"parent_id"=>$db->f("column_id"));
	$dispArr[$db->f("column_id")][$db->f("display_order")] = $db->f("id");
 }

$dataArr = array();
if(count($lovArr)>0){
    $query="select * from fd_airworthi_header_master where id in (".implode(",",array_keys($dispArr)).") and filter_type = 2  order by display_order " ;
    $db->query($query);
    while($db->next_record()){       
        $dataArr[$db->f("client_id")]["_".$db->f("id")] =$db->f("header_name");
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