<?php
$whr = array("type"=>$_REQUEST["type"],"master_flag"=>$_REQUEST["master_flag"],"template_id"=>$_REQUEST["template_id"],"delete_flag"=>0,"view_flag"=>0,"client_id"=>$_REQUEST["client_id"]);
if(isset($_REQUEST["comp_id"]) && $_REQUEST["comp_id"]!=0 && isset($_REQUEST["comp_main_id"]) && $_REQUEST["comp_main_id"]!=0 )
{
	$whr["component_id"]=$_REQUEST["comp_id"];
	$whr["comp_main_id"]=$_REQUEST["comp_main_id"];
}

$type = $_REQUEST["type"];
$sectionVal=$_REQUEST["section"];
$sub_sectionVal=$_REQUEST["sub_section"];
$db->getHeaders($whr,$sectionVal,$sub_sectionVal);
$dataArr = array();
$dispArr = array();
$excludeArr= array("Item Ref","Description");
while($db->next_record()){    
if(isset($_REQUEST["comp_main_id"]) && $_REQUEST["comp_main_id"]!="" || isset($_REQUEST["template_id"]) && $_REQUEST["template_id"]!="" ){
	if(in_array($db->f("header_name"),$excludeArr)){
		continue;
	}
}
    $dataArr[$db->f("id")] = array("header_name"=>$db->f("header_name"),"filter_type"=>$db->f("filter_type"),"filter_auto"=>$db->f("filter_auto"),"read_only"=>$db->f("read_only"),"display_order"=>$db->f("display_order"),                                    
                                    "client_id"=>$db->f("client_id"),"is_reminder"=>$db->f("is_reminder"),"refMax_value"=>$db->f("refMax_value"));
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
$lovArr = array();
$colIds = array_keys($dataArr);
$arr=array("type"=>$type); 
if(count($arr)>0){
     $db->getLovValue($arr,$sectionVal,$sub_sectionVal,$colIds);
	 while($db->next_record()){
           $lovArr[$db->f("column_id")][] = $db->f("lov_value");
        }
}

$finalDataArr = array();
$finalDataArr["data"] =$dataArr;
$finalDataArr["client"] =$clNameArr;
$finalDataArr["dispData"] =$dispArr;
$finalDataArr["lovArr"] =$lovArr;
echo json_encode($finalDataArr);


?>