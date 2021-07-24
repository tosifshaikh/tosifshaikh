<?php
$temp_data = array();
$client_id=$_REQUEST['client_id'];
$db->getCategoryGrid($client_id);

while($db->next_record())
{
	$temp_data["_".$db->f("id")]['client_id'] = $db->f("client_id");
	//$temp_data["_".$db->f("id")]['client_name'] = $db->f("COMP_NAME");
	$temp_data["_".$db->f("id")]['category_name'] = $db->f("category_name");
	$client_arr[$db->f("client_id")] = $db->f("COMP_NAME");
}

$parentArr = array();
$parentArr['RowData']=$temp_data;
$parentArr['Client_Array']=$client_arr;
echo json_encode($parentArr);
?>