<?php
header('Content-Type: text/xml'); 
if(	 isset($_REQUEST['linkId']) && ctype_digit($_REQUEST['linkId']) == false)  {
	
		$strError = CheckError();
		$msg = 'Section Name:'.$Request["mtype"].' master , Message: request parameter manipulated.';
		$exception_message = $msg;  
		ExceptionMsg( $exception_message, $Request["mtype"].' Master');     	
		header("Location : error.php");	
		exit;
	}
try
{
	$strResponse = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>'; 
	
	$strResponse .= '<root>';
	if($db->query($db->getGrid()))
	{
	while($db->next_record())
	{
		$whArrA["landing_gear_type"] = $db->f("ID");
		$whArrA["is_module_level"]=0;
		$subStr= "landing_gear_type = ? AND is_module_level=?";
	
		$total_rows = $mdb->fungetRowCount("fd_landing_gear_master",$subStr,$whArrA);
		
		$is_parent_type = $mdb->is_check_parent_type($db->f("ID"));
		
		$recuse = '';
		if($total_rows > 0 || $is_parent_type > 0){
			$recuse = 1;
		} else {
			$recuse = 0;
		}
		
		$mdb->getTypeAssignTo($db->f("airlinesId"),$db->f("aircrafttype_id"));		
		$strResponse .= '<row>';
		$strResponse .= '<id>';
		$strResponse .= $db->f("ID");
		$strResponse .= '</id>';
		$strResponse .= '<airlines>';
		$strResponse .= $db->f("airlines");
		$strResponse .= '</airlines>';
		$strResponse .='<geartype><![CDATA[';
		$strResponse .= $db->f("GEARTYPE");
		$strResponse .= ']]></geartype>';
		$strResponse .='<gearmenuf><![CDATA[';
		$strResponse .= $db->f("manufacturer");
		$strResponse .= ']]></gearmenuf>';
		$strResponse .='<airtype><![CDATA[';
		$sepr='';
		while($mdb->next_record())
		{
			$assigntoArr = array_diff(explode(",",$mdb->f('assingto')),array($db->f("airlines")));
			$strResponse .=$sepr.$mdb->f('ICAO')."ASGNTO".implode(",",$assigntoArr);
			$sepr='#';					
		}	
		$strResponse .= ']]></airtype>';
				
		if($_SESSION['is_gear_module']==1)
		{
			$strResponse .='<modules>';
			//$strResponse .=  $db->f("no_of_module");
			$strResponse .=  ($db->f("is_gear_module")==1)?$db->f("no_of_module"):'-';
			$strResponse .= '</modules>';
		}
		$strResponse .='<recuse>';
		$strResponse .= $recuse;
		$strResponse .= '</recuse>';
		$strResponse .= '</row>';
	}
	}
	else{
		$msg = 'Section Name: Gear Type , Message: Grid is not loaded properly due to query error';
		$exception_message = $msg;
		ExceptionMsg( $exception_message,'Gear Type Master');     	
		$strResponse .= '<error><msg>'.ERROR_FETCH_MESSAGE.'</msg></error>';
	}
	$strResponse .='</root>';
}
catch(Exception $e)
{
	echo $e->getMessage();
}
echo $strResponse;
?>