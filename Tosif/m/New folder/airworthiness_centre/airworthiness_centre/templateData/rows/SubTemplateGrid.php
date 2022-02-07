<?php
header('Content-Type: text/xml'); 
$strResponse = '<?xml version="1.0" encoding="ISO-8859-1" standalone="yes"?>'; 

$db->select("*","fd_airlines_config",array("airlines_id"=>$_REQUEST['ClientID']));
$db->next_record();
$templateName=$db->f("template_name");


$sql="select * from fd_airworthi_template_master where client_id=".$_REQUEST['ClientID']." and template_type=2 and isDelete=0 and type=".$_REQUEST['Type'];
$db->query($sql);
while($db->next_record())
{
	$template_name=$db->f('template_name');	
}
if($_REQUEST['Type']==1)
{
	$tableNameForManufacturer = 'archive';
}
else if($_REQUEST['Type']==2)
{
	$tableNameForManufacturer = 'fd_eng_master';
}
else if($_REQUEST['Type']==3)
{
	$tableNameForManufacturer = 'fd_apu_master';
}
else if($_REQUEST['Type']==4)
{
	$tableNameForManufacturer = 'fd_landing_gear_master';
}

$db->select('MANUFACTURER',$tableNameForManufacturer,array(($_REQUEST['Type']==1)?'TAIL':'id'=>1));
$db->next_record();
$manufacturer=$db->f('MANUFACTURER');
$strWhere='';
$strWhere .= " and (manufacturer='Common Group' OR manufacturer='".$manufacturer."') AND status=1 ";

$sql="select * from fd_airworthi_template_data_master where template_id=".$_REQUEST['template_id']." $strWhere order by display_order ASC";
$mdb->query($sql);
$desArr=array();

while($mdb->next_record())
{
	$hlVal="";
	if($_REQUEST['Type']==1)
	{
				if($mdb->f("centre_id")==2)
				{
					if($db->f("view_type")==0)
					{
						$hlVal = mhLink($mdb->f("type"),$mdb->f("position"),$mdb->f("sub_position"),$mdb->f("view_type")).mhLinkName($mdb->f("hyperlink_value"));
					}
					else
					{
						$hlVal = mhLink($mdb->f("type"),$mdb->f("position"),$mdb->f("sub_position"),$mdb->f("view_type")).$templateName." View";
					}
				}
				else if($mdb->f("centre_id")==1 || $mdb->f("centre_id")==3)
				{
					$setVal = $mdb->f("hyperlink_value");
					                                   
					$hlVal = csLinkName($_REQUEST['Type'],$setVal);
				}
	}
	else
	{
				if($mdb->f("centre_id")==2)
				{
					if($mdb->f("bible_viewType")==0)
					{
						$hlVal = "Year View&nbsp;&raquo;&nbsp;".mhLinkName($mdb->f("hyperlink_value"));
					}
					else
					{
						$hlVal = $templateName." View";
					}
				}
				else if($mdb->f("centre_id")==1 || $mdb->f("centre_id")==3)
				{
                                    $setVal = $mdb->f("hyperlink_value");
                                    if($mdb->f("statement_value") != 0)
                                        $setVal = $mdb->f("statement_value");
                                    $hlVal = csLinkName($_REQUEST['Type'],$setVal);
				}			
	}
        $statementOpt='';
        if($mdb->f('statement_value') != 0)
        {
            $statementOpt = $hlVal;
            $hlVal = '';            
        }
	$status="";
	$valOpt = "";
	$readOnly = "";
	if($mdb->f('hyperlink_option')==1)
	{
		$valOpt="Yes";
	}
	else
	{
		$valOpt="No";
	}
	
	if($mdb->f('is_readonly')==1)
	{
		$readOnly = "Yes";
	}
	else
	{
		$readOnly = "No";
	}
	
	$deny_access='';
	if($mdb->f('deny_access_cli')==1)
	{
		$deny_access= "Yes";
	}
	else
	{
		$deny_access = "No";
	}
	
	if($mdb->f('status')==1)
	{
		$status = "Active";
	}
	else
	{
		$status = "Inactive";
	}
	
	/* attach document */
				if($mdb->f("attach_flydoc")==1)
				{
					if($mdb->f("template_type")==2)
					{
						$csdb->select("document_name","fd_meta_master",array("id"=>$mdb->f("flydoc_id")));
						while($csdb->next_record())
						{
							$flydoc_template_title=$csdb->f("document_name");
						}
					}
					else if($mdb->f("template_type")==1)
					{
						$csdb->select("group_name","fd_meta_processgroup",array("id"=>$mdb->f("flydoc_id")));
						while($csdb->next_record())
						{
							$flydoc_template_title=$csdb->f("group_name");
						}
					}
				}
				else
				{
					$flydoc_template_title='';
				}
			
				/* attach document */
			
	
	$desArr[$template_name][]=$mdb->f('temp_description')."##STEMP##".$deny_access."##STEMP##".$valOpt."##STEMP##".$hlVal."##STEMP##".$flydoc_template_title."##STEMP##".$readOnly."##STEMP##".$status."##STEMP##".$mdb->f('manufacturer');
}

echo json_encode($desArr);
?>