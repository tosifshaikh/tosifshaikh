<?php
$isFlydocsUser = ($_SESSION["UserLevel"] == '3' || $_SESSION["UserLevel"] == '0')? 1:0;
header('Content-Type: text/xml'); 
$strResponse = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'; 

if($Request["main_id"]=='' || !ctype_digit($Request["main_id"]))
{
	$msg = "Section  :- Airworthiness Review   => List Comments Airworthiness Review </br></br>main_id  have balnk  or non numeric value on Grid Page.";
	ExceptionMsg($msg,'Airworthiness Review');
	$strResponse .= '<root>';
	$strResponse .= '<error><msg>'.ERROR_FETCH_MESSAGE.'</msg></error>';
	$strResponse .='</root>';
	echo $strResponse;die;
}
$arrWhere=array();
$arrWhere[0]=$Request["main_id"];
$strWhere = " data_main_id=? ";


$strType = '';
if(isset($Request["n_type"]) && $Request["n_type"]!='all')
{
	if($Request["n_type"]=='1')
	{
		$strType = ' AND notes_type = 1';
	}
	else if($Request["n_type"]=='2')
	{
		$strType = ' AND notes_type = 2';
	}
	else if($Request["n_type"]=='3')
	{
		$strType = ' AND notes_type IN(0,3)';
	}
}

$strCli = '';
if(isset($Request["fly_user"]) && $Request["fly_user"]!=0)
{
	
	$arrWhere[4]=$Request["fly_user"];
	$strCli = ' AND admin_id=? ';
	unset($arrWhere[5]);
}

$strVaa = '';
if(isset($Request["vaa_user"]) && $Request["vaa_user"]!=0)
{
	
	$arrWhere[4]=$Request["vaa_user"];
	$strCli = ' AND admin_id=? ';
	unset($arrWhere[5]);
}


$mdb = clone $db;
$strWhere = $strWhere.$strType.$strCli.$strFly.$strVaa. " order by DateTime DESC";
if($db->query("select * from fd_airworthi_review_notes where ".$strWhere,$arrWhere))
{	
	$strResponse .= '<root>';
	while($db->next_record())
	{
		$userid=$db->f("admin_id");
		$temp=explode(",",$db->f("receiver")) ;
			if($db->f("private_note")==1)
			{
				if($_SESSION["UserId"]==$userid || in_array($_SESSION["UserId"],$temp) || $_SESSION['UserLevel']==0)
				{
					$check=1;
				}
				
				else
				{
					$check=2;
				}
			}
			else
			{
				$check=3;
			}
			if($check!=2)
			{
		$strResponse .= '<row>';
		$strResponse .= '<id><![CDATA['.$db->f("id").']]></id>';
		$strResponse .= '<note><![CDATA['.$db->f("notes").']]></note>';
		$strResponse .= '<n_type><![CDATA['.$db->f("notes_type").']]></n_type>';
		if($db->f("admin_id")!=0)
		{
			
			$tempUser = '';
			$tempUser = getUserFullName($db->f("admin_id"));
			
			if($tempUser == 'USER_ERROR' || $tempUser=='')
			{
				$tempstrResponse .= '<root>';
				$tempstrResponse .= '<error><msg>'.ERROR_FETCH_MESSAGE.'</msg></error>';
				$tempstrResponse .='</root>';
				echo $tempstrResponse;die;
			}
			else
			{
				$strResponse .= '<n_owner><![CDATA['.$tempUser.']]></n_owner>';
			}
		}
		else
		{
			$tempUser = '';
			$tempUser = getUserFullName($db->f("client_id"));
			if($tempUser == 'USER_ERROR' || $tempUser=='')
			{
				$tempstrResponse .= '<root>';
				$tempstrResponse .= '<error><msg>'.ERROR_FETCH_MESSAGE.'</msg></error>';
				$tempstrResponse .='</root>';
				echo $tempstrResponse;die;
			}
			else
			{
				$strResponse .= '<n_owner><![CDATA['.$tempUser.']]></n_owner>';
			}
		}
		$vaa_user = array();
		$fly_user = array();
		$lessor_user = array();
		
		if($mdb->getAssignedUser($db->f("id")))
		{
			while($mdb->next_record())
			{
				
				if($mdb->f("level")==0 || $mdb->f("level")==3)
				{
					array_push($fly_user,$mdb->f('full_name'));
				}
				else if($mdb->f("level")==1)
				{
					array_push($vaa_user,$mdb->f('full_name'));
				}
				else if($mdb->f("level")==5)
				{
					array_push($lessor_user,$mdb->f('contact_name'));
				}
			}
		}
		else
		{
			$tempstrResponse .= '<root>';
			$tempstrResponse .= '<error><msg>'.ERROR_FETCH_MESSAGE.'</msg></error>';
			$tempstrResponse .='</root>';
			echo $tempstrResponse;die;
		}
		$strResponse .= '<fly_user><![CDATA['.implode(",",$fly_user).']]></fly_user>';
		$strResponse .= '<vaa_user><![CDATA['.implode(",",$vaa_user).']]></vaa_user>';
		$strResponse .= '<lessor_user><![CDATA['.implode(",",$lessor_user).']]></lessor_user>';
		$strResponse .= '<private_note>'.$db->f("private_note_flag").'</private_note>';
		$strResponse .= '<n_date><![CDATA['.showDateTime($db->f("date")).']]></n_date>';
		$strResponse .= '</row>';
			}
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