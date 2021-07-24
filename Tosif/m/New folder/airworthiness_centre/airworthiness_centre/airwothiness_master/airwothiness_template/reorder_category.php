<?php
set_time_limit(0);
if($_REQUEST["templateId"] == '')
{
	$msg = "Section: Reorder Category Page - TemplateId blank @ ".$_SERVER['REQUEST_URI'];
	ExceptionMsg($msg,'Airceaft/Engine Delivery Bible');
	header('location:error.php');
	exit;
}

if(!ctype_digit($_REQUEST["templateId"]))
{
	$msg = "Section: Reorder Category Page - TemplateId has not proper values @ ".$_SERVER['REQUEST_URI'];
	ExceptionMsg($msg,'Airceaft/Engine Delivery Bible');
	header('location:error.php');
	exit;
}


$sub_link_id = 61;
/*if($_REQUEST["type"]=="AIRCRAFT" || $_REQUEST["type"]=="aircraft")
{
		$sub_link_id = 56;
}else if($_REQUEST["type"]=="ENGINE" || $_REQUEST["type"]=="engine")
{
		$sub_link_id = 57;
}else if($_REQUEST["type"]=="LANDING_GEAR" || $_REQUEST["type"]=="landing_gear")
{
		$sub_link_id = 58;
}
else if($_REQUEST["type"]=="APU" || $_REQUEST["type"]=="apu")
{
		$sub_link_id = 59;
}*/

if(isset($_REQUEST["old_temp_val"]) && $_REQUEST["old_temp_val"]!="")
	{
		$old_temp_val = unserialize(str_replace("'","\"",$_REQUEST["old_temp_val"]));
	}
	$oldVal_Audit_arr = array();
	foreach($old_temp_val as $key=>$val)
	{
		
		$oldVal_Audit_arr[] = $val."(".$key.")";
		$old_temp_val_key[]=$key;
	}

$templateId = $_REQUEST["templateId"];

$whereArr = array();
$whereArr["id"] = $_REQUEST["templateId"];
$db->select("template_name,client_id","fd_airworthi_template_master",$whereArr);
$db->next_record();
$temp_name= $db->f("template_name");
$Client_id = $db->f("client_id");
$compArr = array();
$compArr["ID"] = $Client_id;
$db->select("COMP_NAME","fd_airlines",$compArr);
$db->next_record();
$Client_Name =  $db->f("COMP_NAME");

if(isset($_REQUEST["group"]))
{
	$arrGroups = $_REQUEST["group"];
	foreach($arrGroups as $boxId)
	{
		 $strDocuments = trim($_REQUEST["group_".$boxId."_doc_id"]);
		 $new_temp_val = explode("/",$_REQUEST["group_".$boxId."_doc_name"]);
		 if($strDocuments != "")
		{
			$arrDocuments = explode("/",$strDocuments);
			$displayOrder=1;
			
			foreach($arrDocuments as $documentId)
			{
				
				$Arr_updt['order_no'] = ($displayOrder++);
				
				$whereArr = array();
				$whereArr['template_id'] = $boxId;
				$whereArr['category_id'] = $documentId;
				$db->update("fd_airworthi_category_order_no",$Arr_updt,$whereArr);
				unset($Arr_updt);
				unset($whereArr);
				$done = 1;
			}
		}
	}
	
	$tempArr = array();
	
	foreach($old_temp_val as $old_key=>$old_val)
	{
		foreach($new_temp_val as $new_key=>$new_val)
		{
			if($old_val==$new_val)
			{
				$tempArr[$old_temp_val_key[$new_key]] =$new_val;						
			}
		}
	}
	
	$audit_val_old = implode("<br>",$oldVal_Audit_arr);
	$newVal_Audit_arr = array();
	ksort($tempArr);
	foreach($tempArr as $key=>$val)
	{
		$newVal_Audit_arr[] = $val."(".$key.")";
	}
	$audit_val_new = implode("<br>",$newVal_Audit_arr);
	
	$array_audit = array();
	$array_audit["airlinesId"] = $Client_id;
	$array_audit["operation"] = "REORDER CATEGORIES";
	$array_audit["date"] = escape(DB_Sql::GetDateTime());
	$array_audit["sublink_id"] = $sub_link_id;
	$array_audit["add_by"] = escape($_SESSION['UserId']);
	//$array_audit["category"] = $old_val;
	//$array_audit["related_details"] = $Client_Name." &nbsp;&raquo;&nbsp; ".$temp_name."###".$old_val;
	$array_audit["related_details"] = $temp_name;
	$array_audit["old_value"] = $audit_val_old;
	$array_audit["new_value"] = $audit_val_new;
	//print_r($array_audit);
	//die;
	
	$db->insert("fd_masters_audit_trail",$array_audit);
	
	
}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $webpage_Title;?></title>
<link href="css/style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<script type="text/javascript">
	var arrGroup = new Array();
	var downGroup ="-1";
	var downIndex ="-1";
	function fileMouseOver(groupId,docIndex)
	{
		if(downGroup != "-1" && downIndex != "-1")
		{
			if(downGroup==groupId)
			{
				document.getElementById("div_group_"+groupId+"_doc_"+docIndex).style.border = "1px solid #F00";
			}
			else
			{
				document.getElementById("group_"+groupId).style.border = "1px solid #F00";
				document.getElementById("div_group_"+groupId+"_doc_"+docIndex).style.border = "1px solid #F00";
			}
		}
	}
	function fileMouseOut(groupId,docIndex)
	{
		document.getElementById("group_"+groupId).style.border = "1px solid #660066";
		document.getElementById("div_group_"+groupId+"_doc_"+docIndex).style.border = "1px solid #000";
	}
	function fileMouseUp(groupId,docIndex)
	{
		//alert("groupId = "+groupId+" ,docIndex = "+docIndex);
		
		
		if(downGroup != "-1" && downIndex !="-1")
		{
			if(downGroup==groupId)
			{
				// Same Group
				var strDocIds = document.getElementById("group_"+downGroup+"_doc_id").value;
				var arrDocIds = strDocIds.split("/");
				var strDocNames = document.getElementById("group_"+downGroup+"_doc_name").value;
				var arrDocNames = strDocNames.split("/");
				document.getElementById("descTo").value = arrDocNames[docIndex];
				
				if(docIndex>downIndex)
				{
					tempId = arrDocIds[downIndex];
					tempName = arrDocNames[downIndex];
					for(var i=downIndex;i<docIndex;i++)
					{
						arrDocNames[i] = arrDocNames[i+1];
						arrDocIds[i] = arrDocIds[i+1];
					}
					
					arrDocNames[docIndex] = tempName;
					arrDocIds[docIndex] = tempId;
				}
				else
				{
					tempId = arrDocIds[downIndex];
					tempName = arrDocNames[downIndex];
					for(var i=downIndex;i>docIndex;i--)
					{
						arrDocNames[i] = arrDocNames[i-1];
						arrDocIds[i] = arrDocIds[i-1];
					}
					arrDocNames[docIndex] = tempName;
					arrDocIds[docIndex] = tempId;
				}
				
				strDocIds = "";
				strDocNames ="";
				for(var i=0;i<arrDocIds.length;i++)
				{
					strDocIds += ((strDocIds=="")? "" : "/") + arrDocIds[i];
					strDocNames += ((strDocNames=="")? "" : "/") + arrDocNames[i];
					document.getElementById("divDocName_"+downGroup+"_"+i).innerHTML = arrDocNames[i];
				}
				
				
				document.getElementById("group_"+downGroup+"_doc_id").value = strDocIds;
				document.getElementById("group_"+downGroup+"_doc_name").value = strDocNames;
			}
			else
			{
				// Diffrent Group
				var strDownDocIds = document.getElementById("group_"+downGroup+"_doc_id").value;
				var arrDownDocIds = strDownDocIds.split("/");
				//alert(arrDownDocIds);
				var strDownDocNames = document.getElementById("group_"+downGroup+"_doc_name").value;
				var arrDownDocNames = strDownDocNames.split("/");
				//alert(arrDownDocNames);
				var strUpDocIds = document.getElementById("group_"+groupId+"_doc_id").value;
				var arrUpDocIds = strUpDocIds.split("/");
				var strUpDocNames = document.getElementById("group_"+groupId+"_doc_name").value;
				var arrUpDocNames = strUpDocNames.split("/");
				
				strDownDocIds="";
				strDownDocNames="";
				for(var i=0;i<arrDownDocIds.length;i++)
				{
					if(i!=downIndex)
					{
						strDownDocIds += ((strDownDocIds=="")? "" : "/") + arrDownDocIds[i];
						strDownDocNames += ((strDownDocNames=="")? "" : "/") + arrDownDocNames[i];
					}
				}
				
				strUpDocIds="";
				strUpDocNames="";
				for(var i=0;i<arrUpDocNames.length;i++)
				{
					if(i==docIndex)
					{
						strUpDocIds += ((strUpDocIds=="")? "" : "/") + arrDownDocIds[downIndex];
						strUpDocNames += ((strUpDocNames=="")? "" : "/") + arrDownDocNames[downIndex];
						if(arrUpDocIds[i] != "")
						{
							strUpDocIds += ((strUpDocIds=="")? "" : "/") + arrUpDocIds[i];
							strUpDocNames += ((strUpDocNames=="")? "" : "/") + arrUpDocNames[i];
						}
					}
					else
					{
						strUpDocIds += ((strUpDocIds=="")? "" : "/") + arrUpDocIds[i];
						strUpDocNames += ((strUpDocNames=="")? "" : "/") + arrUpDocNames[i];
					}
				}
				
				document.getElementById("group_"+downGroup+"_doc_id").value = strDownDocIds;
				document.getElementById("group_"+downGroup+"_doc_name").value = strDownDocNames;
				
				document.getElementById("group_"+groupId+"_doc_id").value = strUpDocIds;
				document.getElementById("group_"+groupId+"_doc_name").value = strUpDocNames;
				
				document.getElementById("group_"+downGroup+"_doc").innerHTML = "";
				document.getElementById("group_"+groupId+"_doc").innerHTML = "";
				redrawGroup(downGroup);
				redrawGroup(groupId);
			}
		}
		downGroup ="-1";
		downIndex ="-1";
	}
	function redrawGroup(groupId)
	{
		var strDownDocIds = document.getElementById("group_"+groupId+"_doc_id").value;
		var arrDownDocIds = strDownDocIds.split("/");
		var strDownDocNames = document.getElementById("group_"+groupId+"_doc_name").value;
		var arrDownDocNames = strDownDocNames.split("/");
		
		var strHTML = "";
		for(var docIndex=0;docIndex<arrDownDocIds.length;docIndex++)
		{
			if(arrDownDocNames[docIndex]!="")
			{
				strHTML += '<table id="div_group_'+groupId+'_doc_'+docIndex+'" cellspacing="0" cellpadding="0" width="100%" onmouseover="fileMouseOver('+groupId+','+docIndex+');" onmouseout="fileMouseOut('+groupId+','+docIndex+');" onmouseup="fileMouseUp('+groupId+','+docIndex+');" style="border:1px solid #000; margin:2px 0">';
				strHTML += '<tr>';
				strHTML += '<td style="width: 40px; margin-top: 35px;margin-left: 10px; height: 32px; background-image: url(\'images/move.gif\');  background-repeat: no-repeat; background-position:center; margin-right: 10px; cursor:move; " onmousedown="return fileMouseDown('+groupId+','+docIndex+');">';
				strHTML += '</td>';
				strHTML += '<td style="padding:5px;"><div id="divDocName_'+groupId+'_'+docIndex+'">'+arrDownDocNames[docIndex]+'</div></td></tr></table>';
			}
			else
			{
				strHTML += '<table id="div_group_'+groupId+'_doc_'+docIndex+'" cellspacing="0" cellpadding="0" width="100%" onmouseover="fileMouseOver('+groupId+','+docIndex+');" onmouseout="fileMouseOut('+groupId+','+docIndex+');" onmouseup="fileMouseUp('+groupId+','+docIndex+');" style="border:1px solid #000; margin:2px 0">';
				strHTML += '<tr>';
				strHTML += '<td>';
				strHTML += '</td>';
				strHTML += '<td style="padding:5px;">&nbsp;</td></tr></table>';
			}
		}
		document.getElementById("group_"+groupId+"_doc").innerHTML = strHTML;
	}
	function fileMouseDown(groupId,docIndex)
	{
		
		
		var strDocNames = document.getElementById("group_"+groupId+"_doc_name").value;
		var arrDocNames = strDocNames.split("/");
		document.getElementById("descFrom").value = arrDocNames[docIndex];
		downGroup =groupId;
		downIndex =docIndex;
		return false;
	}
	
	document.onmouseup=function()
	{
		downGroup ="-1";
		downIndex ="-1";
	};
	document.onselectstart = function() {return false;}
	
	function saveOrder()
	{
		document.frmReorder.action = 'airworthiness_master.php?section='+<?php echo $_REQUEST["section"]; ?>+'&sub_section='+<?php echo $_REQUEST["sub_section"]; ?>+'&type='+<?php echo $_REQUEST["type"]; ?>+'&templateId='+<?php echo $_REQUEST["templateId"]; ?>+'&REORDERCAT';
		document.frmReorder.submit();
	}
</script>
</head>
<body>
<form name="frmReorder" id="frmReorder" method="post" action="" >
<input type="hidden" name="templateId" value="<?php echo $_REQUEST['templateId']?>" />
<input type="hidden" name="descFrom" id="descFrom"  value="" />
<input type="hidden" name="descTo" id="descTo" value="" />
<input type="hidden" name="type" id="type" value="<?php echo $_REQUEST["type"];?>" />
<input type="hidden" name="sectionVal" id="sectionVal" value="<?php echo $_REQUEST["section"];;?>"/>
<input type="hidden" name="sub_sectionVal" id="sub_sectionVal" value="<?php echo $_REQUEST["sub_section"];;?>"/>

<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" class="whitebackgroundtable">
  <tr>
    <td valign="top" class="whiteborderthree">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="45" align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="70%" align="left" valign="middle" class="MainheaderFont">Reorder Categories</td>
        <td width="8%" align="right" valign="middle"><?php
			echo hooks_getbutton(array("6" => array("onclick" => "return saveOrder();"), "14" => array("onclick" => "window.close()" , "id" => "btnClose" , "name" => "Close")));
		?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td id="searchDiv" class="pink_roundedcorner"><div id="mainTBL">
                        <?php
							
							$whereGrp = array();
							$whereGrp['id'] = $templateId;
							//$old_temp_arr = array();
							if($db->select("*","fd_airworthi_template_master",$whereGrp))
							{
								$my_flg = true;
							}
							
							if($my_flg == true)
							{
								$groupIndex = 0;
								$cat_id = "";
								while($db->next_record())
								{
									$boxId = $db->f("id");
							?>
									<script type="text/javascript">
										arrGroup[<?php echo $groupIndex++; ?>] = '<?php echo $boxId; ?>';
									</script>
									<input type="hidden" name="group[]" value="<?php echo $boxId; ?>" />
									<div id="group_<?php echo $boxId; ?>" class="group_box">
										<div class="group_header"><span><?php echo  $db->f("template_name"); ?></span></div>
										<div id="group_<?php echo $boxId; ?>_doc" class="group_doc">
							<?php
							
									$strOrder = 'SELECT group_concat(category_id ORDER BY order_no) AS IdStr FROM fd_airworthi_category_order_no WHERE';
									$strOrder .= ' template_id = '.$templateId.' ORDER BY order_no';
									$mdb->query($strOrder);
									$mdb->next_record();
									$idStr = $mdb->f('IdStr');
									if($idStr=="")
									{
										$idStr = 0;
									}
									
								 	$strDoc = 'SELECT * FROM fd_airworthi_category_master WHERE isDelete=0 and id IN ('.$idStr.') ORDER BY';
								 	$strDoc .= ' field(id,'.$idStr.')';
									
									$mdb->query($strDoc);
									
									$strDocId ="";
									
									$strDocName ="";
									$docIndex = 0;
									$itemid = 'A';
									$descprtion = "";
									
									
									$table_name = "fd_airworthi_template_data_master";
									
									while($mdb->next_record())
									{
										$sql="SELECT * FROM ".$table_name." WHERE category_id = '".$mdb->f("id")."' AND template_id = '".$templateId."' ORDER BY display_order";								

										$ndb->query($sql);
										
										if($ndb->num_rows()>0)
										{
										
										$strDocId .= (($strDocId=="")? "" : "/").$mdb->f("id");
										
										
										$docName = $mdb->f('category_name');
										$strDocName .= (($strDocName=="")? "" : "/").$docName;
										?>
										<table id="div_group_<?php echo $boxId; ?>_doc_<?php echo $docIndex; ?>" cellspacing="0" cellpadding="0" width="100%" onMouseOver="fileMouseOver(<?php echo $boxId; ?>,<?php echo $docIndex; ?>);" onMouseOut="fileMouseOut(<?php echo $boxId; ?>,<?php echo $docIndex; ?>);" onMouseUp="fileMouseUp(<?php echo $boxId; ?>,<?php echo $docIndex; ?>);" style="border:1px solid #000; margin:2px 0">
											<tr> 
												 <td width="20" align="center"><strong><?php echo $itemid;?></strong></td>
                                                 <td style="width: 40px; margin-top: 35px;margin-left: 10px; height: 32px; background-image: url('images/move.gif');  background-repeat: no-repeat; background-position:center; margin-right: 10px; cursor:move; " onMouseDown="return fileMouseDown(<?php echo $boxId; ?>,<?php echo $docIndex; ?>);">
									 </td>
                                     
												<td style="padding:5px;"><div id="divDocName_<?php echo $boxId; ?>_<?php echo $docIndex; ?>"><?php echo $docName; ?></div></td>
											
										<?php
										$old_temp_arr[$itemid] = $docName;
										$docIndex++;
										$itemid++;
									}
									
									if($docIndex==0)
									{
										
							?>
										<table id="div_group_<?php echo $boxId; ?>_doc_0" cellspacing="0" cellpadding="0" width="100%" onMouseOver="fileMouseOver(<?php echo $boxId; ?>,0);" onMouseOut="fileMouseOut(<?php echo $boxId; ?>,0);" onMouseUp="fileMouseUp(<?php echo $boxId; ?>,0);" style="border:1px solid #000; margin:2px 0">
											<tr> 
												 <td>
									 </td>
												<td style="padding:5px;">&nbsp;</td>
											</tr>
										 </table>
                                         </tr>
										 </table>
							<?php
									}
									
							?>
									</div>
									
										
									</div>
							<?php	
								
								}
								
								}
							}
							else
							{
								echo "<b>".ERROR_FETCH_MESSAGE."</b>";
							}
							
						?>
                        
                        <input type="hidden" name="old_temp_val" id="old_temp_val" value="<?php echo str_replace("\"","'",serialize($old_temp_arr));?>" />
                        <input type="hidden" name="group_<?php echo $boxId; ?>_doc_id" id="group_<?php echo $boxId; ?>_doc_id" value="<?php echo $strDocId; ?>" />
									<input type="hidden" name="group_<?php echo $boxId; ?>_doc_name" id="group_<?php echo $boxId; ?>_doc_name" value="<?php echo $strDocName; ?>" />
                        </div></td>
  </tr>

</table>
<tr>
    <td height="45" align="right"><?php
		echo hooks_getbutton(array("6" => array("onclick" => "return saveOrder();"),"14" => array("onclick" => "window.close();")));
    ?></td>
  </tr>
    </td></tr></table>
</form>
</body>
</html>
<?php
if(isset($_REQUEST["group"]) && $done==1)
{?>
<script language="javascript">	
	window.opener.loadgrid();
	alert("The Categories have been reordered successfully.");
	window.close();
</script>
<?php
}?>