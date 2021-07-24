<?php
$colArr = json_decode($_REQUEST["headerObj"],true);
$tab_id = $_REQUEST["tab_id"];
$type= $_REQUEST["type"];
$rec_id= $_REQUEST["rec_id"];
$client_id= $_REQUEST["client_id"];
$whrArr = array();
$whrArr["tab_id"]=$tab_id;
$whrArr["type"]=$type;
$whrArr["client_id"]=$client_id;
$whereStr = 'and rec_id = ?';
$whrArr["rec_id"]=$rec_id;
$db->getRows($colArr,$whereStr,$whrArr);
$valArr =array();
while($db->next_record()){
	$recID = 0;
	$recID  = "_".$db->f("rec_id");
	$valArr[$recID]['order_no'] = $db->f("order_no");
	$valArr[$recID]['delete_flag'] = $db->f("delete_flag");
	$valArr[$recID]['status']= $db->f("col_1");	
	$valArr[$recID]['delete_cell_flag']= $db->f("delete_cell_flag");	
	$valArr[$recID]['rec_id']= $db->f("rec_id");
	$valArr[$recID]['srNo']= $_REQUEST['srNo'];	
	foreach($colArr as $key=>$val){
		$colVal = '';
		$colVal = $db->f($val);
		$valArr[$recID][$val] =$colVal;		

	}
}
$autoFilterVal = array();
if(isset($_REQUEST["LovValueCheck"]) && $_REQUEST["LovValueCheck"]==1){
	$autoFilterVal = getFilterValues($tab_id,$type,$client_id);
}
$parentArr = array();
$parentArr['rowData'] = $valArr;
$parentArr['autofiletrVal'] = $autoFilterVal;
echo json_encode($parentArr);
?>