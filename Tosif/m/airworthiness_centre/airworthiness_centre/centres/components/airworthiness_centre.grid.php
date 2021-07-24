<?php
if(isset($_REQUEST["type"]) && !empty($_REQUEST["type"]) && !ctype_digit($_REQUEST["type"]))
{
    ExceptionMsg("type have Incorrect value grid page",'airworthiness_centre &nbsp;&raquo;&nbp; centre &nbsp;&raquo;&nbsp;component');
    header('Location:error.php');
    exit;
}
$type = $_REQUEST['type'];
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

$i = 0;
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
    foreach ($dataArr as $key => $val) {
        $columnVal = "";
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
                $columnVal = $compDb->f($val['col_id']);
            }
        }
        $valArr[$compDb->f($typeArr[$type])]["_" . $compDb->f($idArr[$type])][$key] = $columnVal;
    }
    $i++;
}
if ($_SESSION['user_type'] == 0) {
    $compDb->getGrid($strWhere, $where_arr, 1, $start, 1);
    $compDb->next_record();
    $totalCount = $compDb->f('cnt');
} else {
    $totalCount = $i;
}
$parentArr = array();
$parentArr['data'] = $valArr;
$parentArr['total'] = $totalCount;
echo json_encode($parentArr);
die;
?>
