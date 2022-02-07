<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); 
$arr = array();
$json_Arr=array();


try
{
	if($db->select("*","fd_airworthi_aviation_authority","","id"))
	{
		while($db->next_record())
		{
			$json_arr['_'.$db->f("id")]=array("short_name"=>$db->f("short_name"),"description"=>$db->f("description"));
		}
	
	}
	else{
		$msg = 'Section Name: Avitation Authority , Message: Grid is not loaded properly due to query error';
		$exception_message = $msg;
		ExceptionMsg($exception_message,'Avitation Authority');     	
		$json_Arr['err']=ERROR_FETCH_MESSAGE;
	}
}
catch(Exception $e)
{
	echo $e->getMessage();
}
$grid['data']=$json_arr;
print json_encode($grid);

?>