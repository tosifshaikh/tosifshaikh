<?php
$rec_id = $_REQUEST["rec_id"];
$category_id= 0;
$PrntId = 0;
$prntHeaderId= 0;
$sectionVal= (isset($_REQUEST["section"]) && $_REQUEST["section"]!='')?$_REQUEST["section"]:0;
$sub_sectionVal= (isset($_REQUEST["sub_section"]) && $_REQUEST["sub_section"]!='')?$_REQUEST["sub_section"]:0;
$subFlg = (isset($_REQUEST["subFlg"]) && $_REQUEST["subFlg"]!='')?$_REQUEST["subFlg"]:0;
$db->select('category_id,component_id,type,client_id,comp_main_id,Header_Row,PrntID','fd_airworthi_review_rows',array("id"=>$rec_id));
while($db->next_record()){
	$category_id= $db->f('category_id');
	$comp_id= $db->f('component_id');
	$type = $db->f('type');
	$client_id = $db->f('client_id');
	$comp_main_id= $db->f('comp_main_id');
	$Header_Row = $db->f('Header_Row');
	$PrntId = $db->f('PrntID');	
}

if($Header_Row!=0 && $subFlg!=0){
	$prntHeaderId =$rec_id;
}
$defaultStatus = array();
$wsId =0;
$itemChar='A';
$prntitemId = 'A';
if(isset($_REQUEST["insertVal"])){
	
	if($prntHeaderId!=0){
		$db->select("display_order,itemid","fd_airworthi_review_rows",array("id"=>$prntHeaderId));
		$db->next_record();
		$prntitemId= $db->f("itemid");		
	}
	$db->select("display_order,itemid","fd_airworthi_review_rows",array("id"=>$rec_id));
	$db->next_record();
	$itemId= $db->f("itemid");
	$fid = 	$db->f("display_order");
	if($prntHeaderId==0){	
		
		$sub_id = substr($db->f('itemid'),0,1);		
		if(strlen($fid)==1){
			 $fid_val = $sub_id."00".$fid;	
		} if(strlen($fid)==2){
			$fid_val = $sub_id."0".$fid;	
		}	if(strlen($fid)==3)	{
			$fid_val = $sub_id.$fid;	
		}
	} else	{
		if(strlen($itemId)==5)	{
			$itemId=substr($itemId,0,4);
		}
		$fid_val=$itemId.($itemChar+($db->f('display_order')-1));
	}
	//echo $itemChar+(1);
	//echo 'A';
	//echo $prntitemId;
		
		
	$defaultStatus =getReviewDefaultStatus($type,$client_id,$comp_main_id);
	$wsId = key($defaultStatus);
	if(count($defaultStatus)===0){
	?>
    </script>
    <script>
	alert("No Default Status is Selected.Please Select Default Status for this tab.");
	window.close();
	</script>    
    <?php
	exit;
	}
	$insVal = json_decode($_REQUEST["insertVal"],true);
	
	$Chkarr =array();
	$Chkarr["component_id"] = $comp_id;
	$Chkarr["type"] = $type;
	$Chkarr["client_id"] = $client_id;	
	$maxRecId = $db->getReviewMaxrecID($comp_main_id,$prntHeaderId);
	if(isset($_REQUEST["rec_id"]) && $_REQUEST["rec_id"]!=0 && $_REQUEST["rec_id"]!='' && $prntHeaderId==0){		
		$Chkarr["comp_main_id"] = $comp_main_id;
		$Chkarr["id"] = $_REQUEST["rec_id"];	
		
		$db->select("display_order","fd_airworthi_review_rows",$Chkarr);;
		$db->next_record();
		$temp_disp_order =$db->f("display_order");
	}
	else if(isset($_REQUEST["addAirWorRow"]) && $_REQUEST["addAirWorRow"]==1){
		$temp_disp_order=1;
	}
	else{			
		$temp_disp_order = $db->GetMaxValue("display_order","fd_airworthi_review_rows","comp_main_id = ".$comp_main_id." and PrntId=".$prntHeaderId);
	}
	if($_REQUEST['pos']=="above"){
		$orderNo = $temp_disp_order;	
	} else {
		$orderNo = $temp_disp_order+1;	
	}
	
	foreach($insVal["dataVal"] as $key=>$val){
		if($key==0){
		  $orderNo=$orderNo;
		  } else{
		  $orderNo=$orderNo+1;
		 }
		$insertArr= array();
		$insertArr = $insVal["dataVal"][$key];
		$insertArr["component_id"] = $comp_id;
		$insertArr["itemid"] = $fid_val;
		$insertArr["type"] = $type;
		$insertArr["client_id"] = $client_id;
		$insertArr["rec_id"] = $maxRecId;
		$insertArr["display_order"] =$orderNo;
		$insertArr["work_status"] = $wsId;
		$insertArr["comp_main_id"] = $comp_main_id;		
		$insertArr["category_id"] = $category_id;
		$insertArr["priority"] = 3;
		if($PrntId!=0){
			$prntHeaderId=$PrntId;
		}
		if(!$db->update_airReview_values($orderNo,$comp_main_id,$prntHeaderId)){
			ExceptionMsg($errMsg."<br> issue in update Airworthi Value for add row - 2",'Airworthiness Review Center');
			?>
			<script>
			alert("There is an issue in saving record. Please Contact Administrator for further assistance.");
			window.close();</script>
		<?php
		}	
		
		$insertArr["PrntId"] = $prntHeaderId;	
		
		if($db->insert("fd_airworthi_review_rows",$insertArr)){
				$lastAddedId = $db->last_id();
				$arrWhere=array();
				$arrWhere[]=$comp_main_id;
				$arrWhere[]=$prntHeaderId;				
				$arrWhere[]=$orderNo;
				$strWhereItem = "select id,itemid,display_order from fd_airworthi_review_rows where comp_main_id = ? and PrntId = ? and display_order >= ? ";
				$db->query($strWhereItem,$arrWhere);				
				while($db->next_record()){ 					
					$fid = '';
					$sub_id = '';
					$fid_val = '';
					$mdb->select("display_order,itemid","fd_airworthi_review_rows",array("id"=>$db->f('id')));
					$mdb->next_record();
					if($prntHeaderId==0){						
						$fid = $mdb->f('display_order');
						$sub_id = substr($mdb->f('itemid'),0,1);						
						if(strlen($fid)==1){
							$fid_val = $sub_id."00".$fid;	
						} if(strlen($fid)==2)	{
							$fid_val = $sub_id."0".$fid;	
						}if(strlen($fid)==3){
							$fid_val = $sub_id.$fid;	
						}						
					}	else	{
						if(strlen($itemId)==5){
							$itemId=substr($itemId,0,4);
						}
						$fid_val=$itemId.($itemChar+($mdb->f('display_order')-1));
					}
					
					$strWhere = "";
					$updateArr = array();
					$updateArr['itemid'] = escape($fid_val);					
					$strWhere='id='.$db->f('id');
					if(!$mdb->update("fd_airworthi_review_rows",$updateArr,array("id"=>$db->f('id'))))
					{
						ExceptionMsg($errMsg."<br> issue in update db_cs_value for add row above",'CS-DB');
						?>
<script>alert("There is an issue in saving record. Please Contact Administrator for further assistance.");
						window.close();
						</script>
<?php
					}
				}			
			$strWhereItem = '';
			$strWhereItem = 'comp_main_id =?';
			$strWhereItem .= ' AND PrntId = 0 ';
			
			$arrWhere=array();
			$arrWhere[]=$comp_main_id;			
			
			$PrntArr=array();
			$query = "select * from fd_airworthi_review_rows where $strWhereItem";
			
			
			
		//	$db->select_query("*","fd_airworthi_review_rows",$strWhereItem,$arrWhere);
			$db->query($query,$arrWhere);
			while($db->next_record())
			{
				$PrntArr[$db->f('id')]=$db->f('itemid');
			}
			
			$strWhereItem = '';
			
			foreach($PrntArr as $Pkey=>$Pval)
			{
				$itemChar='A';
				$order_no=1;
				
				$arrWhere=array();
				$arrWhere['PrntId']=$Pkey;
				$db->select("*","fd_airworthi_review_rows",$arrWhere,'display_order');
				while($db->next_record())
				{
					$prntItemid=$Pval;
					
					$updateArr=array();
					$updateArr['itemid'] =$prntItemid.$itemChar++;
					$updateArr['display_order'] =$order_no++;
					$strWhere='id='.$db->f('id');
					
					if(!$mdb->update("fd_airworthi_review_rows",$updateArr,array("id"=>$db->f('id'))))
					{
						ExceptionMsg($errMsg."<br> issue in update db_cs_value for update Sub row ",'CS-DB');
						?>
		<script>alert("There is an issue in saving record. Please Contact Administrator for further assistance.");
						window.close();
						</script>
		<?php
					}
				}
			}			
			$auditIns = array();
			$auditIns = $insVal["AuditVal"][$key];
			$auditIns['main_id']=$lastAddedId;			
			$auditIns['action_date']=$mdb->GetCurrentDate();
			$auditIns["new_value"] = $defaultStatus[$wsId]["status_name"].','.$defaultStatus[$wsId]["bg_color"];			
			
			if(!$db->insert("fd_airworthi_audit_trail",$auditIns)){
				ExceptionMsg($errMsg."<br> issue in insert audit trail value for add row",'Airworthiness Review Center');
			?>
			 <script>
			alert("There is an issue in saving record. Please Contact Administrator for further assistance.");
			window.close();</script>
			<?php
			}
		} else {
			ExceptionMsg($errMsg."<br> issue in insert for add row",'Airworthiness Review Center');
			?>
			 <script>
			alert("There is an issue in saving record. Please Contact Administrator for further assistance.");
			window.close();</script>
             <?php			
		}
		$maxRecId++;
	}
	?>
    <script>
	alert("<?php echo count($insVal["dataVal"]);?> Rows have been added successfully.");
	window.close();
    window.opener.location.reload();</script>
	<?php
	exit;	
}

$Title = get_Comp_Name($comp_id,$type);
$whr = array("comp_main_id"=>$comp_main_id,"delete_flag"=>0);
$db->getHeaders($whr,3,2);
$lovValueCheck =0;
$autoFilterVal = array();
$colArr =array();
$tempcolArr=array();
$lovColArr=array();
$excludeArr= array("Item Ref"); 
while($db->next_record()){	
	if(in_array($db->f("header_name"),$excludeArr)){
		continue;
	}
	if($db->f("view_flag")!=0){		
		$expClArr[$db->f("view_flag")]["_".$db->f("id")] = array("header_name"=>$db->f("header_name"),"filter_type"=>$db->f("filter_type"),"filter_auto"=>$db->f("filter_auto"),
							"column_id"=>$db->f("column_id"),"edit_flag"=>0,"is_reminder"=>$db->f("is_reminder"),"view_flag"=>$db->f(""));
	} else{
		$tempcolArr["_".$db->f("id")] = array("header_name"=>$db->f("header_name"),"filter_type"=>$db->f("filter_type"),"filter_auto"=>$db->f("filter_auto"),
							"column_id"=>$db->f("column_id"),"edit_flag"=>0,"is_reminder"=>$db->f("is_reminder"));	
	}			
	if($db->f('filter_type')==2){
		$lovColArr["lovCol"][$db->f("id")]=$db->f("id");
		if($db->f('filter_auto')==1){
				$lovColArr["lovFilterAutoCol"][$db->f("id")]=$db->f("column_id");
		}
	}			
}
foreach($tempcolArr as $key=>$val){
	$fkey = str_replace("_","",$key);
	$colArr[$key] = $tempcolArr[$key];
	if($expClArr[$fkey]){
		$colArr = array_merge_recursive($colArr,$expClArr[$fkey]);
	}	
}
$lovVal = array();
if(count($lovColArr["lovCol"])>0){	
			$lovVal = getReviewFilterValues($lovColArr,$type,$client_id,$comp_id);
}
$expValArr = array();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $webpage_Title;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<link type="text/css" rel="stylesheet" href="<?php echo CALENDAR_PATH;?>calendar.css"/>
<script src="<?php echo CALENDAR_PATH;?>calendar.js" type="text/javascript"></script>
<script src="<?php echo JS_PATH;?>context_menu.js<?php echo QSTR; ?>" type="text/javascript"></script>
<script src="<?php echo JS_PATH;?>common.js<?php echo QSTR; ?>"></script>
<script src="<?php echo JS_PATH;?>jquery.js"></script>
<script src="<?php echo SECTION_PATH;?>add_rows.js<?php echo QSTR; ?>"></script>
<script>
UserID = '<?php echo $_SESSION["UserId"];?>';
user_name ="<?php echo addslashes($_SESSION["User_FullName"]);?>";
tab_name='<?php echo $Title;?>';
headerObj=eval(<?php echo json_encode($colArr);?>);
autoFilterObj=eval(<?php echo json_encode($lovVal);?>);
expRemObj=eval(<?php echo json_encode($expValArr);?>);
</script>
</head>

<body onLoad="renderGrid();">
<form name="addrowform" id="addrowform"  action="" method="post">
  <input type="hidden" id="mainRowid" name="mainRowid" value="">
  <input type="hidden" id="type" name="type" value="<?php echo $type; ?>">
  <input type="hidden" id="pos" name="pos" value="<?php echo $Request["pos"]; ?>" />
  <input type="hidden" id="comp_id" name="comp_id" value="<?php echo $comp_id; ?>">
  <input type="hidden" id="client_id" name="client_id" value="<?php echo $client_id; ?>">
  <input type="hidden" id="sectionVal" name="sectionVal" value="<?php echo $sectionVal; ?>">
  <input type="hidden" id="sub_sectionVal" name="sub_sectionVal" value="<?php echo $sub_sectionVal;?>">
   <input type="hidden" id="comp_main_id" name="comp_main_id" value="<?php echo $comp_main_id;?>">
   <input type="hidden" id="category_id" name="category_id" value="<?php echo $category_id;?>">
<input type="hidden" name="insertVal" id="insertVal" value="" />
</form>
<table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" class="whitebackgroundtable">
<tr>
 <td valign="top" class="whiteborderthreenew">
 			<table width="100%"  cellspacing="0" cellpadding="0" border="0" align="center">
           <tr valign="top" >
              <td class="MainheaderFont">Add Multiple Rows</td>
            </tr>

            <tr>
              <td align="left" valign="middle" height="50" ><strong>Please Insert The Number of Rows :</strong> &nbsp;
              <input name="rowstoadd" type="text" value="1" id="rowstoadd" onKeyPress="return numOnly(this, event);" >
              <span>(If the number of rows is more than 1, please press ENTER to generate the rows on the page.)</span>
              </td>
            </tr>
            <tr>
              <td align="left" valign="top" id="addRowTable"></td>
            </tr>
            <tr>
              <td align="left" valign="bottom" height="42"><?php 
              echo hooks_getbutton(array("6"=>array("id"=>"saveBtn","onclick"=>"return validateForm()"),"14"=>array("id"=>"close_win","onclick"=>"window.close();")),"left");
              ?>
              </td>
            </tr>
            </table>
	</td>
	</tr>
</table>

</body>
</html>