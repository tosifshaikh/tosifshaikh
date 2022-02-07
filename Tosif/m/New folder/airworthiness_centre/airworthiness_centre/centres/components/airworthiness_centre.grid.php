<?php
if(isset($_REQUEST["type"]) && !empty($_REQUEST["type"]) && !ctype_digit($_REQUEST["type"]))
{
    ExceptionMsg("type have Incorrect value grid page",'airworthiness_centre &nbsp;&raquo;&nbp; centre &nbsp;&raquo;&nbsp;component');
    header('Location:error.php');
    exit;
}
$type = $_REQUEST['type'];

$lastRevDateCol = array();
$NextRevDateCol = array();
$prntId = array();
$TempArrResult = array();
$clIdSArr= array();
$wsDetailArr= array();


$whrArr = array("type"=>$type,"delete_flag"=>0);


$filterStatusArr= array();
$tempwsStr = "";
$tempAddStr = "";
$statusArr = array();
if(isset($_REQUEST["fl_work_status"])){
	$filterStatusArr['ws'] = $_REQUEST["fl_work_status"];
	$tempwsStr = " and status_name like '%".$_REQUEST["fl_work_status"]."%' ";
	$db->getWorkStatus($whrArr,1,4,"",$tempwsStr);
	while($db->next_record()){				
		$statusArr[$db->f("id")]=$db->f("id");		
	}
}




$db->getWorkStatus($whrArr,1,4,"");
	while($db->next_record()){				
		$wsDetailArr[$db->f("id")]=array("status_name"=>$db->f("status_name"),"font_color"=>$db->f("font_color"),"bg_color"=>$db->f("bg_color"));
		$clIdSArr[$db->f("client_id")]=$db->f("client_id");
	}
			
$wskeyArr = array_keys($wsDetailArr);
$arr = array($type);			
$templStr='';
$tempnStr='';
$templsColArr = array();
$tempnsColArr= array();
if(count($clIdSArr)>0){
	$clintIds=	$clIdSArr;
	$db->getIsRemColumn($clIdSArr,$arr);	 
	while($db->next_record()){
		$lastRevDateCol[$db->f("client_id")]=$db->f("column_id");
		$prntId[$db->f("id")]=$db->f("id");
		$templsColArr[$db->f("column_id")] = $db->f("column_id");
	}
	if(isset($_REQUEST["fl_ls_date"])){
			$templsColArr = array_filter(array_unique($templsColArr));
			$filterStatusArr['ls_date'] = $_REQUEST["fl_ls_date"];
			foreach($templsColArr as $key => $val){
				//$templStr.= ($templStr=="")? $key." like '%".$_REQUEST["fl_ls_date"]."%'" :" or ".$key." like '%".$_REQUEST["fl_ls_date"]."%' ";
			}
		}
	if($templStr!=""){
		$tempAddStr.= " and (".$templStr .")";
	}
		
	if(count($prntId)>0){
		$db->getExpDateColVal($prntId);		
		while($db->next_record()){
			$NextRevDateCol[$db->f("client_id")]=$db->f("column_id");
			$tempnsColArr[$db->f("column_id")] = $db->f("column_id");			
		}
	}	
	if(isset($_REQUEST["fl_ns_date"])){
		$tempnsColArr = array_filter(array_unique($tempnsColArr));
		$filterStatusArr['ls_date'] = $_REQUEST["fl_ls_date"];
		foreach($tempnsColArr as $key => $val){
			//$tempnStr.= ($tempnStr=="")?$key." like '%".$_REQUEST["fl_ns_date"]."%'" :" or ".$key." like '%".$_REQUEST["fl_ns_date"]."%' ";
		}
	}
	if($tempnStr!=""){
		$tempAddStr.= " and (".$tempnStr .")";
	}	
	$arr1=array_unique(array_values($NextRevDateCol));
	$arr2=array_unique(array_values($lastRevDateCol));
			
	$fcLArr=array_merge($arr1,$arr2);
	$finalArr = array();
	if(count($wskeyArr)>0){
		$fcLArr =array_filter(array_unique($fcLArr));
		$db->getExpRemDateVal($fcLArr,$clintIds,$arr,$wskeyArr,$tempAddStr);
		$db->next_record();
		$TempArrResult=$db->arr_result;
		
		foreach($TempArrResult as $key => $val){					
				$clId=$val['client_id'];					
				$nrvColId = $NextRevDateCol[$clId];
				$lrvColId= $lastRevDateCol[$clId];
				$compIdVal = $val["component_id"];
				$ncolVal = '';
				$nxVal = '';
				$lsVal = '';
				$lColVal = '';
				if($NextRevDateCol[$clId]){
					$ncolVal = $NextRevDateCol[$clId];
											
					if($val[$ncolVal])
					$nxVal = $val[$ncolVal];
				}
				if($NextRevDateCol[$clId]){
					$lColVal = $lastRevDateCol[$clId];
					
					if($val[$lColVal])
					$lsVal = $val[$lColVal];
				}
				if(isset($_REQUEST["fl_work_status"]) && $_REQUEST["fl_work_status"]){
						
						if(!in_array($val["work_status"],$statusArr))							
						continue;
				}	
				if(isset($_REQUEST["fl_ls_date"])){
						
						$pos=strpos($lsVal,$_REQUEST["fl_ls_date"]);
						if($pos===false)
						continue;
				}
					
				if(isset($_REQUEST["fl_ns_date"])){
						
						$pos=strpos($nxVal,$_REQUEST["fl_ns_date"]);
						if($pos===false)
						continue;
				}
							
			$finalArr[$compIdVal]=array("Next Review Date"=>$nxVal,'Last Review Date'=>$lsVal,"work_status"=>$val["work_status"]);
		}
	}
			
}


if (isset($_REQUEST['type']) && $_REQUEST['type'] == 1) {
    include_once(DB_PATH . '/aircraft_centre_class.php');
    $compDb = new AirCentre($_CONFIG);
}
if (isset($_REQUEST['type']) && $_REQUEST['type'] == 2) {
    include_once(DB_PATH . '/engine_centre_class.php');
    $compDb = new EngCentre($_CONFIG);
    $engdb = new EngCentre($_CONFIG);
}
$typeArr = array(1 => "ICAO", 2 => "ENGINETYPE");
$idArr = array(1 => "ID", 2 => "id", 4 => "id");
$dataArr = json_decode($_REQUEST['data'], true);

$where_arr = array();

$getFilter = getFilterArray();
$strWhere .= $getFilter[1];
$where_arr = $getFilter[0];
if ($type == 1) {
    $strWhere .= " AND IS_DELETE in (0,2)";
} else {
    $strWhere .=" AND delete_flag = 0";
}

$inbox=0;
if(isset($_REQUEST['inboxmod']) && $_REQUEST['inboxmod']==1)
{
	$db->GetIDInbox($_REQUEST['UID']);
	$db->next_record();
	$strWhere .=" AND tailId in (".$db->f('component_id').") ";
	$inbox=1;
}
$i = 0;
//echo '<pre>';;
//print_r($finalArr);

if(count($filterStatusArr)>0){
	 $strWhere .=" AND tailId in (".implode(",",array_keys($finalArr)).") ";
}

$start = $_REQUEST['start'];
if ($_SESSION['user_type'] == 0) {
    $compDb->getGrid($strWhere, $where_arr, 0, $start, 0);
    
} else {
    $funtype = array(1 => "getMainAccessAircraftList", 2 => "getMainAccessEngineList");
    if ($type == 1) {
        $dataArr['component_id']["col_id"] = "TAILID";
        $idArr[1] = "ArId";
    }
    if ($type == 2) {
        $typeArr[2] = "engtnm";
        $dataArr['Currently On']["col_id"] = "c_on";
        $dataArr['Engine Type']['col_id'] = "engtnm";
        $dataArr['Client']['col_id'] = "anm";
    }
    $compDb->$funtype[$type]();
}
$valArr = array();

while ($compDb->next_record()) {
	
    foreach ($dataArr as $key => $val){
		
        $columnVal = "";
		$comp_col_id  = "";
		$comp_mainIdVal  = "";
		$comp_col_id = $dataArr['component_id']['col_id'];
		$comp_mainIdVal = $compDb->f($comp_col_id);	
        if (isset($val["dateField"]) && $val["dateField"] == 1) {
            if ($compDb->f($val['col_id']) != "") {
                $tempVal = $compDb->f($val['col_id']);
                $tempValArr = explode(" ", $tempVal);
                $columnVal = ddmmyyyy($tempValArr[0]);
            } else {
                $columnVal = $compDb->f($val['col_id']);
            }
        } else {
            if ($key == "Currently On" && $_SESSION['user_type'] == 1 && $type != 1) {
                if ($compDb->f("is_onground") == 1) {
                    $columnVal = "On Ground";
                } else if ($compDb->f("is_shop_visit") == 1) {
                    $columnVal = "Engine Shop Visit";
                } else {
                    if ($compDb->f("currently_on") != 0) {
                        $columnVal = $compDb->f("c_on");
                    } else {
                        $columnVal = "--";
                    }
                }
            } else {
				if($val['col_id'])
                $columnVal = $compDb->f($val['col_id']);
            }
        }		
		if($key=="Work Status"){					
			if($finalArr[$comp_mainIdVal]){				
				$columnVal=$finalArr[$comp_mainIdVal]['work_status'];
			}
		}
		if($key=="Next Review Date"){
			if($finalArr[$comp_mainIdVal]){				
				$columnVal=$finalArr[$comp_mainIdVal][$key];
			}
		}
		if($key=="Last Review Date"){					
			if($finalArr[$comp_mainIdVal]){				
				$columnVal=$finalArr[$comp_mainIdVal][$key];
			}
		}
        $valArr["_".$compDb->f($typeArr[$type])]["_" .$compDb->f($idArr[$type])][$key] = $columnVal;	
    }	
    $i++;
}
$totalCount=0;
if ($_SESSION['user_type'] == 0) {
    $compDb->getGrid($strWhere, $where_arr, 1, $start, 1);
    while($compDb->next_record()){
	    $totalCount = $compDb->f('cnt');
	}
} else {
    $totalCount = $i;
}



$parentArr = array();
$parentArr['data'] = $valArr;
$parentArr['total'] = $totalCount;
$parentArr['ExpDateValArr'] = $finalDateArr;
$parentArr['Work_statusArr'] = $wsDetailArr;


echo json_encode($parentArr);
die;
?>
