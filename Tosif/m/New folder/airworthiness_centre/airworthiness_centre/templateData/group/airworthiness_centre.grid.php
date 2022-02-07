<?php 
header('Content-Type: text/xml'); 
$strResponse = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>'; 	
if(!ctype_digit($Request['comp_main_id']))
{
	ExceptionMsg("comp_main_id have Non-Numeric/Blank value on Grid page(Manage Document Groups)",'Internal');
	$strResponse .= '<root>';
	$strResponse .= '<error><msg>'.ERROR_FETCH_MESSAGE.'</msg></error>';
	$strResponse .='</root>';
	echo $strResponse;die;
}
$arrwhere=array();

$arrwhere["comp_main_id"]=$Request['comp_main_id'];
$arrwhere["delete_flag"]=0;
if($db->select("*","fd_airworthi_review_groups",$arrwhere,"display_order"))
{
	$strResponse .= '<root>';
	while($db->next_record())
	{		
		$strResponse .= '<row>';
		$strResponse .= '<id>';
		$strResponse .= $db->f("id");
		$strResponse .= '</id>';
		
		$strResponse .= '<tabid>';
		$strResponse .= $db->f("comp_main_id");
		$strResponse .= '</tabid>';
		
		$strResponse .= '<boxname><![CDATA[';
		$strResponse .= $db->f("group_name");
		$strResponse .= ']]></boxname>';
		
		$strResponse .= '<mainuser_flag>';
		$strResponse .= $db->f("main_flag");
		$strResponse .= '</mainuser_flag>';
		
		$strResponse .= '<clientuser_flag>';
		$strResponse .= $db->f("client_flag");
		$strResponse .= '</clientuser_flag>';
		
		$strResponse .= '<flyRef_flag>';
		$strResponse .= $db->f("flyRef_flag");
		$strResponse .= '</flyRef_flag>';
		
		$strResponse .= '<displayorder>';
		$strResponse .= $db->f("display_order");
		$strResponse .= '</displayorder>';
		
		$strResponse .= '<default_flydoc_group>';
		$strResponse .= $db->f("default_flydoc_group");
		$strResponse .= '</default_flydoc_group>';
		
		$strResponse .= '<copy_to_next_order>';
		$strResponse .= $db->f("copy_to_next_odr");
		$strResponse .= '</copy_to_next_order>';
		
		$strResponse .= '</row>';
	}
	$strResponse .='<total>'.$db->num_rows().'</total>';
	$strResponse .='</root>';
}
else
{
	$strResponse .= '<root>';
	$strResponse .= '<error><msg>'.ERROR_FETCH_MESSAGE.'</msg></error>';
	$strResponse .='</root>';
}
echo $strResponse;
?>