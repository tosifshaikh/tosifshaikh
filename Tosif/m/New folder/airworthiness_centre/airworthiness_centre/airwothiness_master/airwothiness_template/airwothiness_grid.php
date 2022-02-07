<?php
$temp_data = array();
$temp_display = array();
$client_id=$_REQUEST['client_id'];
$reqTemplateId=$_REQUEST['templet_id'];;
$flydocs_template_group=get_flydocs_template_group($client_id,1);
$flydocs_template=get_flydocs_template($client_id,1,0);

$template_arr=getTemplateArray($client_id,1,$_REQUEST["templet_type"]);
foreach($template_arr as $tId=>$temp_name)
{  
	if(strlen($reqTemplateId)>0 && $reqTemplateId!=0 && $tId!=$reqTemplateId)
	{
		continue;
	}	
	if($_REQUEST["templet_type"]==2)
		$catagory_arr=array("0"=>"0");
	else	
		$catagory_arr = getCatagoryArray($tId);
	
	$itemId = 'A';
	
	foreach($catagory_arr as $cat_id=>$cat_name)
	{			
		$where_arr=array();
		$where_arr['client_id']=$client_id;	
		$where_arr['template_id']=$tId;	
		$where_arr['category_id']=$cat_id;	
		$addStr = "";
		if(isset($_REQUEST['manufacturer']) && $_REQUEST['manufacturer']!='')
		{
			$addStr.="and (manufacturer='Common Group' OR manufacturer=?) ";
			$where_arr["manufacturer"]=$_REQUEST['manufacturer'];
			$addStr.=" AND status = 1 ";
		}
		$query="select * from fd_airworthi_template_data_master where client_id= ? and template_id= ? and category_id= ?  $addStr order by display_order ";
	
		$db->query($query,$where_arr);		
		if($db->num_rows() > 0)
		{
			while($db->next_record())
			{
				$temp_data["_".$db->f("id")]['template_id'] = $tId;
				$temp_data["_".$db->f("id")]['template_name'] = $temp_name;
				$temp_data["_".$db->f("id")]['category_id'] = $cat_id;
				$temp_data["_".$db->f("id")]['category_name']=$cat_name;
				$temp_data["_".$db->f("id")]['ItemID']=$itemId;	
				$temp_data["_".$db->f("id")]['display_order'] = $db->f("display_order");
				$temp_data["_".$db->f("id")]['temp_description'] = $db->f("temp_description");
				$temp_data["_".$db->f("id")]['read_only'] = $db->f("read_only");
				$temp_data["_".$db->f("id")]['manufacturer'] = $db->f("manufacturer");
				$temp_data["_".$db->f("id")]['hyperlink_option'] = $db->f("hyperlink_option");
				$temp_data["_".$db->f("id")]['hyperlink_ids'] = $db->f("hyperlink_value");
				$str_hyperlink='';
				$tabArr='';
				if($db->f("centre_id")==1)
				{
					$str_hyperlink=get_cs_tabName_from_id($db->f("hyperlink_value"));
					$tabArr=get_cs_tab_from_id($db->f("hyperlink_value"));
					$tabRArr=array();
					foreach($tabArr as $key=>$val)
					{
						$tabRArr['_'.$key]=$val;
					}
				}
				elseif($db->f("centre_id")==2)
				{		
					$str_hyperlink=mhLink($db->f("type"),$db->f("position"),$db->f("sub_position"),$db->f("view_type")).mhLinkName($db->f("hyperlink_value"));
				}
				$temp_data["_".$db->f("id")]['hyperlink_value'] = $str_hyperlink;
				$flydocs_template_name='';
				if($db->f("flydocs_type")==1)
				{
					$flydocs_template_name=$flydocs_template_group[$db->f("flydocs_id")];
				}
				elseif($db->f("flydocs_type")==2)
				{
					$flydocs_template_name=$flydocs_template[$db->f("flydocs_id")];
				}
				$temp_data["_".$db->f("id")]['flydocs_Template_name'] = $flydocs_template_name;
				$temp_data["_".$db->f("id")]['type'] = $db->f("type");			
				$temp_data["_".$db->f("id")]['status'] = $db->f("status");
				$temp_data["_".$db->f("id")]['flydocs_id'] = $db->f("flydocs_id");
				$temp_data["_".$db->f("id")]['flydocs_type'] = $db->f("flydocs_type");
				$temp_data["_".$db->f("id")]['client_id'] = $db->f("client_id");
				$temp_data["_".$db->f("id")]['Tab_arr'] = $tabRArr;
			}
			$itemId++;
		}		
	}
}
$parentArr['RowData']=$temp_data;
echo json_encode($parentArr);

?>