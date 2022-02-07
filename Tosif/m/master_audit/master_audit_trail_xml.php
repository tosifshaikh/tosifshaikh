<?php
header('Content-Type: text/xml');
$strResponse = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
$wh = "";
if($_REQUEST['adtFrom']!='CENTRAL_ADT')
{
	if($_REQUEST['sec']=='U_AUD')
	{
		if($_REQUEST['opt2']!="32" && $_REQUEST['opt2']!="109")
		{
			$_REQUEST['sublinkId'] = $_REQUEST['opt2'];
		}
		if($_REQUEST['selFlyUser']!='')
		{
			$wh .= " and add_by='".$_REQUEST['selFlyUser']."'";
		}	
	}
	if($_REQUEST['keyword'] != "")
	{		
		$keyword=$_REQUEST['keyword'];	
		$wh .= " and (old_value like '%".$keyword."%' or new_value like '%".$keyword."%' or  related_details like '%".$keyword."%'  or CONCAT(u.fname,' ',u.lname) like '%".$keyword."%')";	
	}
	if($_REQUEST['airlines'] != "0" && trim($_REQUEST['airlines']) !="")
	{
		if(DBType=="mysql")
			$wh .= " and FIND_IN_SET(".$_REQUEST['airlines'].",a.airlinesId)";
		else
			$wh .= " and 1=dbo.find_in_set(".$_REQUEST['airlines'].",a.airlinesId)";
	}
	if($_REQUEST['from_date'] || $_REQUEST['to_date'])
	{
		$dtarr1 = explode('-',$_REQUEST['from_date']);
		$dt1 = $dtarr1[2]."-".$dtarr1[1]."-".$dtarr1[0];
		
		$_REQUEST["to_date"] = date("Y-m-d",strtotime(date("Y-m-d", strtotime($_REQUEST["to_date"])) . " +1 day"));
		$dtarr2 = explode('-',$_REQUEST['to_date']);	
		$dt2 = $dtarr2[0]."-".$dtarr2[1]."-".$dtarr2[2];
		
		$wh .= " and (date between '".$dt1."' and '".$dt2."')";
	}
	else if($_REQUEST['sec']=='U_AUD')
	{
		$dt = date('Y-m-d');
		$wh .= " and a.date like '%".$dt."%'";
	}
	if($_REQUEST['opt'] != "")
	{
		$wh .= " and  operation='".$_REQUEST['opt']."'  ";
	}
	if($_REQUEST['selairlines'] != "" && $_REQUEST['selairlines'] != "0")
	{
		$wh .= " and FIND_IN_SET(".$_REQUEST['selairlines'].",airlinesId)";
	}
	if($_SESSION['UserLevel'] != "0" && $_REQUEST['sublinkId'] !='171' && $_REQUEST['sublinkId'] !='223')
	{
		if($_SESSION['data_view_option_list'] == "")
		{
			$in = "''";
		}
		else
		{
			$in = $_SESSION['data_view_option_list'];
		}
		$wh .= " and (a.airlinesId = '".$_SESSION['MainClient']."'  or  a.airlinesId in(".$in.") )";
	}
	
	
	if($_REQUEST['sublinkId']==99)
	{
		$isClientModEng = $db->getClientsForModule(true,'');
		$wh .= " AND a.airlinesId IN(".$isClientModEng.")";
	}
	
	if($_REQUEST['sublinkId']==101)
	{
		$isClientModGear = $db->getClientsForModule('',true);
		$wh .= " AND a.airlinesId IN(".$isClientModGear.")";
	}
	if(in_array($_REQUEST['sublinkId'],array(56,57,58,59,60)) && (isset($_REQUEST['template_id']) && $_REQUEST['template_id']!='' && $_REQUEST['template_id']!=0)  && isset($_REQUEST['subSection']) && $_REQUEST['subSection']== "reorder")
	{
		$wh .= ' AND operation IN ("EDITED LOV VALUE","DELETED LOV VALUE","REORDERED LOV VALUE") ';
		$wh .= " AND template_id =".$_REQUEST['template_id']." ";
	}
	else if(in_array($_REQUEST['sublinkId'],array(56,57,58,59,60)) && (isset($_REQUEST['template_id']) && $_REQUEST['template_id']!='' && $_REQUEST['template_id']!=0))
	{
		$wh .= ' AND operation IN ("COLUMN ADDED","COLUMN EDITED","COLUMN DELETED","COLUMN REORDER") ';
		$wh .= " AND template_id =".$_REQUEST['template_id']." ";
	}
	
}
else
{
	if($_REQUEST['keyword'] != "")
	{		
		$keyword=$_REQUEST['keyword'];	
		$wh .= " and (old_value like '%".$keyword."%' or new_value like '%".$keyword."%' or  related_details like '%".$keyword."%' or operation like '%".$keyword."%')";	
	}
	if($_REQUEST['selFlyUser']!='')
	{
		$wh .= " and add_by=".$_REQUEST['selFlyUser']." ";
	}
	if($_REQUEST['selAirUser']!='')
	{
		$wh .= " and add_by=".$_REQUEST['selAirUser']." ";
	}
	
	$exceptTheseSublinks = array(158,161,163,164,197,223);
	
	if(!in_array($_REQUEST['sublinkId'],$exceptTheseSublinks))
	{
		if($_REQUEST['airlines'] != "0" && trim($_REQUEST['airlines']) !="")
		{
			if(DBType=="mysql")
				$wh .= " and FIND_IN_SET(".$_REQUEST['airlines'].",a.airlinesId)";
			else
				$wh .= " and 1=dbo.find_in_set(".$_REQUEST['airlines'].",a.airlinesId)";
		}
	}
	
	if($_REQUEST['from_date'] || $_REQUEST['to_date'])
	{
		$dtarr1 = explode('-',$_REQUEST['from_date']);
		$dt1 = $dtarr1[2]."-".$dtarr1[1]."-".$dtarr1[0];
		
		$_REQUEST["to_date"] = date("Y-m-d",strtotime(date("Y-m-d", strtotime($_REQUEST["to_date"])) . " +1 day"));
		$dtarr2 = explode('-',$_REQUEST['to_date']);	
		$dt2 = $dtarr2[0]."-".$dtarr2[1]."-".$dtarr2[2];
		
		$wh .= " and (date between '".$dt1."' and '".$dt2."')";
	}
	else
	{
		$dt = date('Y-m-d');
		$wh .= " and a.date like '%".$dt."%'";
	}
	
	if($_SESSION['UserLevel'] != "0" && $_REQUEST['sublinkId']!='171')
	{
		if($_SESSION['data_view_option_list'] == "")
		{
			$in = "''";
		}
		else
		{
			$in = $_SESSION['data_view_option_list'];
		}
		$wh .= " and (a.airlinesId = '".$_SESSION['MainClient']."'  or  a.airlinesId in(".$in.") )";
	}
	
	
	if($_REQUEST['sublinkId']==99)
	{
		$isClientModEng = $db->getClientsForModule(true,'');
		$wh .= " AND a.airlinesId IN(".$isClientModEng.")";
	}
	
	if($_REQUEST['sublinkId']==101)
	{
		$isClientModGear = $db->getClientsForModule('',true);
		$wh .= " AND a.airlinesId IN(".$isClientModGear.")";
	}
	
	if(in_array($_REQUEST['sublinkId'],array(56,57,58,59,60)) && (isset($_REQUEST['template_id']) && $_REQUEST['template_id']!='' && $_REQUEST['template_id']!=0)  && isset($_REQUEST['subSection']) && $_REQUEST['subSection']!= "reorder")
	{
		$wh .= ' AND operation IN ("EDITED LOV VALUE","DELETED LOV VALUE","REORDERED LOV VALUE") ';
		$wh .= " AND template_id =".$_REQUEST['template_id']." ";
	}
	else if(in_array($_REQUEST['sublinkId'],array(56,57,58,59,60)) && (isset($_REQUEST['template_id']) && $_REQUEST['template_id']!='' && $_REQUEST['template_id']!=0))
	{
		$wh .= ' AND operation IN ("COLUMN ADDED","COLUMN EDITED","COLUMN DELETED","COLUMN REORDER") ';
		$wh .= " AND template_id =".$_REQUEST['template_id']." ";
	}
	
}
$cnts = $db->getMasterAuditTrailCount($_REQUEST['sublinkId'],$wh);
$db->getMasterAuditTrailGrid($_REQUEST['sublinkId'],$wh,$_REQUEST['Start']);
$strResponse .= '<root>';
while($db->next_record())
{
	$color="#CC99FF";
	if(($db->f("operation")=="DELETE AIRCRAFT TYPE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT AIRCRAFT TYPE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD AIRCRAFT TYPE") || ($db->f("operation")=="ADD WATERMARK"))
	{
		$color="#FF95BA";
	}
	
	else if(($db->f("operation")=="DELETE ENGINE TYPE") || ($db->f("operation")=="DELETE WATERMARK"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT ENGINE TYPE") || ($db->f("operation")=="EDIT WATERMARK"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD ENGINE TYPE"))
	{
		$color="#FF95BA";
	}
	
	else if(($db->f("operation")=="DELETE MODULE TYPE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT MODULE TYPE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD MODULE TYPE"))
	{
		$color="#FF95BA";
	}
	
	else if(($db->f("action")=="DELETE GEAR TYPE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT GEAR TYPE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD GEAR TYPE"))
	{
		$color="#FF95BA";
	}
	else if(($db->f("action")=="DELETE PROPELLER TYPE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT PROPELLER TYPE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD PROPELLER TYPE"))
	{
		$color="#FF95BA";
	}
	
	else if(($db->f("operation")=="DELETE LANDING GEAR SUB-ASSEMBLY TYPE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT LANDING GEAR SUB-ASSEMBLY TYPE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD LANDING GEAR SUB-ASSEMBLY TYPE"))
	{
		$color="#FF95BA";
	}
	
	
	else if(($db->f("operation")=="DELETE APU TYPE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT APU TYPE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD APU TYPE"))
	{
		$color="#FF95BA";
	}
	else if(($db->f("operation")=="DELETE THRUSTREVERSER TYPE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT THRUSTREVERSER TYPE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD THRUSTREVERSER TYPE"))
	{
		$color="#FF95BA";
	}
	
	else if(($db->f("operation")=="DELETED CHECK TYPE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDITED CHECK TYPE"))
	{
		$color="#FFFF00";
	}
	if(($db->f("operation")=="ADDED CHECK TYPE"))
	{
		$color="#FF95BA";
	}
	
	
	else if(($db->f("operation")=="DELETE ATA CODES"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT ATA CODES"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD ATA CODES"))
	{
		$color="#FF95BA";
	}
	else if(($db->f("operation")=="DELETE FLYSEARCH SETTING"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT FLYSEARCH SETTING"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD FLYSEARCH SETTING"))
	{
		$color="#FF95BA";
	}
	
	else if(($db->f("operation")=="DELETE FLYSEARCH TYPE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT FLYSEARCH TYPE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD FLYSEARCH TYPE"))
	{
		$color="#FF95BA";
	}
	else if(($db->f("operation")=="DELETE EMPLOYER NAME"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT EMPLOYER NAME"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD EMPLOYER NAME"))
	{
		$color="#FF95BA";
	}
	else if(($db->f("operation")=="DELETE EMPLOYER STATUS"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT EMPLOYER STATUS"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD EMPLOYER STATUS"))
	{
		$color="#FF95BA";
	}
	else if(($db->f("operation")=="DELETE APPROVAL LEVEL"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT APPROVAL LEVEL"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD APPROVAL LEVEL"))
	{
		$color="#FF95BA";
	}
	
	else if(($db->f("operation")=="DELETE REGULATORY AUTHORITY"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT REGULATORY AUTHORITY"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD REGULATORY AUTHORITY"))
	{
		$color="#FF95BA";
	}
	
	
	else if(($db->f("operation")=="DELETE TYPE OF TRAINING RECORD"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT TYPE OF TRAINING RECORD"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD TYPE OF TRAINING RECORD"))
	{
		$color="#FF95BA";
	}
	
	
	else if(($db->f("operation")=="DELETE AUTHORISATION CONDITION"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT AUTHORISATION CONDITION"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD AUTHORISATION CONDITION"))
	{
		$color="#FF95BA";
	}
	
	
	else if(($db->f("operation")=="DELETE TASK CODE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT TASK CODE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD TASK CODE"))
	{
		$color="#FF95BA";
	}
	
	
	else if(($db->f("operation")=="DELETE AIRLINE CODE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT AIRLINE CODE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD AIRLINE CODE"))
	{
		$color="#FF95BA";
	}
	
	
	
	else if(($db->f("operation")=="DELETE LICENSE TYPE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT LICENSE TYPE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD LICENSE TYPE"))
	{
		$color="#FF95BA";
	}
	
	
	
	
	else if(($db->f("operation")=="DELETE TYPES OF AUTHORISATION"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT TYPES OF AUTHORISATION"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD TYPES OF AUTHORISATION"))
	{
		$color="#FF95BA";
	}
	
	
	else if($db->f("operation")=="DELETE BASE LOCATION") 
	{
		$color="#CC99FF";
	}
	else if($db->f("operation")=="DELETE CATEGORY")
	{
		$color="#01d999";
	}
	else if(($db->f("operation")=="EDIT BASE LOCATION") || ($db->f("operation")=="EDITED CATEGORY"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD BASE LOCATION") || ($db->f("operation")=="ADDED CATEGORY"))
	{
		$color="#FF95BA";
	}

	else if(($db->f("operation")=="DELETE AIRCRAFT CENTRE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT AIRCRAFT CENTRE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD AIRCRAFT CENTRE"))
	{
		$color="#FF95BA";
	}
	else if(($db->f("operation")=="REORDER AIRCRAFT CENTRE"))
	{
		$color="#DBA901";
	}
	else if(($db->f("operation")=="DELETE DESCRIPTION TITLE"))
	{
		$color="#CC99FF";
	}
	if(($db->f("operation")=="EDIT DESCRIPTION TITLE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD DESCRIPTION TITLE"))
	{
		$color="#FF95BA";
	}
	else if(($db->f("operation")=="REORDER CATEGORIES"))
	{
		$color="#EF7168";
	}
	else if($db->f("operation")=="ADD TEMPLATE")
	{
		$color="#6666FF";
	}
	else if($db->f("operation")=="ADDED SUB TEMPLATE")
	{
		$color="#FF0939";
	}
	else if($db->f("operation")=="DELETE TEMPLATE")
	{
		$color="#DBA901";
	}
	else if($db->f("operation")=="DELETED CATEGORY")
	{
		$color="#01d999";
	}
	else if(($db->f("operation")=="DELETE INTERNAL FILE"))
	{
		$color="#CC99FF";
	}
	else if((trim($db->f("operation")) == trim("EDIT INTERNAL FILE")))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD INTERNAL FILE"))
	{
		$color="#FF95BA";
	}
	
	
	if(($db->f("operation")=="DELETE ELECTRONIC DOCUMENT"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT ELECTRONIC DOCUMENT"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD ELECTRONIC DOCUMENT"))
	{
		$color="#FF95BA";
	}
	
	if(($db->f("operation")=="DELETE DROPDOWN"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT DROPDOWN"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD DROPDOWN"))
	{
		$color="#FF95BA";
	}
	if(($db->f("operation")=="DELETE REASON FOR ARCHIVE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT REASON FOR ARCHIVE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADD REASON FOR ARCHIVE"))
	{
		$color="#FF95BA";
	}
	
	if(($db->f("operation")=="DELETED REASON FOR CHANGE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDITED REASON FOR CHANGE"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="ADDED REASON FOR CHANGE"))
	{
		$color="#FF95BA";
	}
	
	
	else if($db->f("operation")=="MULTIPLE EDIT AIRCRAFT TYPE" || $db->f("operation")=="MULTIPLE EDIT ENGINE TYPE" || $db->f("operation")=="MULTIPLE EDIT MODULE TYPE" || $db->f("operation")=="MULTIPLE EDIT ENGINE TYPE"|| $db->f("operation")=="MULTIPLE EDIT GEAR TYPE" || $db->f("operation")=="MULTIPLE EDIT APU TYPE" || $db->f("operation")=="MULTIPLE EDIT THRUSTREVERSER TYPE" || $db->f("operation")=="EDITED MULTIPLE CHECK TYPES" || $db->f("operation")=="MULTIPLE EDIT ATA CODES" || $db->f("operation")=="MULTIPLE EDIT AIRCRAFT CENTRE" || $db->f("operation")=="MULTIPLE EDIT ".BIBLE_TEMPLATE || $db->f("operation")=="MULTIPLE EDIT ELECTRONIC DOCUMENT" || $db->f("operation")=="MULTIPLE EDIT REASON FOR ARCHIVE" || $db->f("operation")=="MULTIPLE EDIT LANDING GEAR SUB-ASSEMBLY TYPE" || $db->f("operation")=="MULTIPLE EDIT PROPELLER TYPE")
	{
		$color="#DBA901";
	}
	else if($db->f("operation")=='COPY THRUSTREVERSER TYPE' || $db->f("operation")=='COPIED CHECK TYPE' || $db->f("operation")=='COPY AIRCRAFT TYPE' || $db->f("operation")=='COPY ENGINE TYPE' || $db->f("operation")=='COPY MODULE TYPE' || $db->f("operation")=='COPY GEAR TYPE' || $db->f("operation")=='COPY APU TYPE' || $db->f("operation")=='COPY REASON FOR ARCHIVE' || $db->f("operation")=='COPY LANDING GEAR SUB-ASSEMBLY TYPE' || $db->f("operation")=='COPY PROPELLER TYPE' )
	{
		$color = '#EF7168';
	}
	
	if(($db->f("operation")=="ADD EXPIRY RULES"))
	{
		$color="#FF95BA";
	}
	else if(($db->f("operation")=="DELETE EXPIRY RULES"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT EXPIRY RULES"))
	{
		$color="#FFFF00";
	}
	
	if(($db->f("operation")=="ADD NON AUTHORISATIONS TYPE"))
	{
		$color="#FF95BA";
	}
	else if(($db->f("operation")=="DELETE NON AUTHORISATIONS TYPE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT NON AUTHORISATIONS TYPE"))
	{
		$color="#FFFF00";
	}
	
	if(($db->f("operation")=="ADD AUTHORISATIONS AIRCRAFT TYPE"))
	{
		$color="#FF95BA";
	}
	else if(($db->f("operation")=="DELETE AUTHORISATIONS AIRCRAFT TYPE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT AUTHORISATIONS AIRCRAFT TYPE"))
	{
		$color="#FFFF00";
	}
	
	if(($db->f("operation")=="ADD AUTHORISATIONS ENGINE TYPE"))
	{
		$color="#FF95BA";
	}
	else if(($db->f("operation")=="DELETE AUTHORISATIONS ENGINE TYPE"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="EDIT AUTHORISATIONS ENGINE TYPE"))
	{
		$color="#FFFF00";
	}
	if(($db->f("operation")=="ADDED FLYSIGN MANAGEMENT"))
	{
		$color="#FF95BA";
	}
	else if(($db->f("operation")=="EDITED FLYSIGN MANAGEMENT"))
	{
		$color="#CC99FF";
	}
	else if(($db->f("operation")=="DELETED FLYSIGN MANAGEMENT"))
	{
		$color="#FFFF00";
	}
	
	
	/*Column Management for RL.*/
	if(($db->f("operation")=="COLUMN ADDED"))
	{
		$color="#99CC00";
	}
	else if(($db->f("operation")=="COLUMN EDITED"))
	{
		$color="#FF9900";
	}
	else if(($db->f("operation")=="COLUMN DELETED"))
	{
		$color="#FFFF00";
	}
	else if(($db->f("operation")=="COLUMN REORDER"))
	{
		$color="#6666FF";
	}
	else if($db->f("operation")=="ADD LANGUAGE")
	{
	    $color="#FF95BA";
	}
	else if($db->f("operation")=="EDIT LANGUAGE")
	{
	    $color="#CC99FF";
	}
	else if($db->f("operation")=="DELETE LANGUAGE")
	{
	    $color="#FFFF00";
	}
		/*Edit / Reorder LOV Values Column Management for RL.*/
		if(($db->f("operation")=="EDITED LOV VALUE"))
		{
			$color="#FFFF99";
		}
		else if(($db->f("operation")=="DELETED LOV VALUE"))
		{
			$color="#99CCFF";
		}
		else if(($db->f("operation")=="REORDERED LOV VALUE"))
		{
			$color="#66CCCC";
		}
		/*end ----Edit / Reorder LOV Values Column Management for RL.*/

	/*end ----Column Management for RL.*/
	
	
	$strResponse .="<row>";
	
	$strResponse .="<id>";
	$strResponse .=$db->f('id');
	$strResponse .="</id>";
	
	$strResponse .="<operation>";
	$strResponse .=$db->f('operation');
	$strResponse .="</operation>";
	
	$strResponse .="<date>";
	$strResponse .= showDateTime($db->f("date"));
	$strResponse .="</date>";
	
	$strResponse .="<related_details><![CDATA[";
	$strResponse .=html_entity_decode(utf8_encode(iconv('UTF-8','ISO-8859-1//IGNORE',$db->f('related_details'))));
	$strResponse .="]]></related_details>";

	$strResponse .="<old_value><![CDATA[";
	if($_REQUEST['sublinkId'] == '223')
	    $strResponse .= utf8_decode($db->f('old_value'));
	else 
	    $strResponse .=html_entity_decode(utf8_encode(iconv('UTF-8','ISO-8859-1//IGNORE',$db->f('old_value'))));
	$strResponse .="]]></old_value>";
	
	$strResponse .="<new_value><![CDATA[";
	if($_REQUEST['sublinkId'] == '223')
	   $strResponse .= utf8_decode($db->f('new_value'));
	else 
	    $strResponse .=html_entity_decode(utf8_encode(iconv('UTF-8','ISO-8859-1//IGNORE',$db->f('new_value'))));
	
	$strResponse .="]]></new_value>";
	
	$strResponse .="<user><![CDATA[";
	$strResponse .=$db->f("fname")." ".$db->f("lname");
	$strResponse .="]]></user>";
	
	$strResponse .="<color>";
	$strResponse .=$color;
	$strResponse .="</color>";
	
	$strResponse .="<airlinesId>";
	if($db->f("airlinesId")!=0)
	{
		$strResponse .=$kdb->getClientName($db->f("airlinesId"));	
	}else
	{
		$strResponse .="-";
	}
	
	
	$strResponse .="</airlinesId>";
	
	$strResponse .="<flysign_level_name><![CDATA[";
	$strResponse .=$db->f("flysign_level_name");
	$strResponse .="]]></flysign_level_name>";
	
	$strResponse .="</row>";
}
$strResponse.="<total>".$cnts."</total>";
if($cnts<1)
{
	$strResponse.="<q><![CDATA[]]></q><error>No Records Found.</error>";
}
$strResponse .= '</root>'; //End xml
echo $strResponse;
?>