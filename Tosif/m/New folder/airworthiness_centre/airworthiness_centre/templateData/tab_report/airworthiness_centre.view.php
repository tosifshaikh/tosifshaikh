<?php
$type = $_REQUEST["type"];
$section= $_REQUEST["section"];
$sub_section= $_REQUEST["sub_section"];
$client_id= $_REQUEST["client_id"];
$comp_main_id= $_REQUEST["comp_main_id"];
$Title = "Airworthiness Review Centre Tab Report";

if($type=='')
{
	$msg = "Section  :- Airworthiness Review Centre=> Tab Report</br></br>type  have balnk value on View Page.";
	ExceptionMsg($msg,'Airworthiness Review Centre');
	header('Location:error.php');
	exit;
}
else if(!ctype_digit($_REQUEST["type"]) )
{
	$msg = "Section  :- Airworthiness Review Centre=> Tab Report</br></br>type   have balnk value on View Page.";
	ExceptionMsg($msg,'Airworthiness Review Centre');
	header('Location:error.php');
	exit;
}
$tempdataArr = array();
$dataArr= array();
$wherarr=array();
$wherarr["comp_main_id"]=$comp_main_id;
$wherarr["type"]=$type;
$db->getDataTabReport($wherarr);
while($db->next_record()){
       $tempdataArr[$db->f('work_status')] = $db->f('tot');    
}
$wsids  = array_keys($tempdataArr);
$whr = array();
$whr['type'] = $type;
$whr['delete_flag'] = 0;
$db->getWorkStatus($whr,3,4,$wsids);
$statusArr = array();
while($db->next_record()){
   $statusArr[$db->f("id")] = array('name'=>$db->f('status_name'),'bg_color'=>$db->f('bg_color'),'font_color'=>$db->f("font_color"));   
   $dataArr[$db->f("client_id")][$db->f("id")] = $tempdataArr[$db->f("id")];   
}
$totalRow =0;
$totalRowArr= array();
foreach($dataArr as $key=>$val){
	$totalRow = $totalRow+array_sum($val);
	$totalRowArr[$key] = array_sum($val);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $webpage_Title;?></title>
<?php $xajax->printJavascript(INCLUDE_PATH);?>
<link href="<?php echo CSS_PATH;?>style.php<?php echo QSTR; ?>" rel="stylesheet" type="text/css">
<script src="<?php echo JS_PATH;?>jquery.js"></script>
<script src="<?php echo SECTION_PATH;?>airworthiness_centre.js<?php echo QSTR; ?>" type="text/javascript"></script>
<script>
dataObj=eval(<?php echo json_encode($dataArr);?>);
statusObj =eval(<?php echo json_encode($statusArr);?>);
totalRow = <?php echo $totalRow; ?>;
totalRowObj = eval(<?php echo json_encode($totalRowArr);?>);
</script>
</head>
<body onload="getSelData(<?php echo $client_id ?>);">
<div id="LoadingDivCombo" style="display:none; z-index:1000; position:absolute; top:0;" class="background">
 <div id="bg">
  <table align="center" border="0" cellpadding="20">
    <tr>
      <td align="center"><img style="margin-top:250px;" src="./images/loading.gif"><br><br>
      <strong>Please wait while we are processing documents in the selected groups.</strong></td>
    </tr>
  </table>
 </div>
</div>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="outer_tbl">
  <tr>
    <td valign="top" class="whitebackgroundtable"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="whitemiddle_tblborder">
  <tr>
    <td align="left" height="50"><?php include(INCLUDE_PATH."logo.php")?></td>
  </tr>
  <tr>
  	<td align="left">
    	<table align="center" border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td height="40" align="left" valign="middle" class="MainheaderFont">
              <?php 
			  	echo $Title;
			  ?></td>
              <td width="16%" align="right"><?php echo date("d-m-Y"); ?></td>
            </tr>            
        </table>
    </td>
  </tr>
  <tr>
  	<td>
    	<form id="frm" name="frm" method="post" action="airworthiness_centre.php" target="_blank">
        	<input type="hidden" name="section" id="section" value="<?php echo $section;?>"> 
            <input type="hidden" name="sub_section" id="sub_section" value="<?php echo $sub_section;?>"> 
        	<input type="hidden" name="title" id="title" value="<?php echo $Title ;?>">            
            <input type="hidden" name="type" id="type" value="<?php echo $type;?>">            
            <input type="hidden" name="exportVal" id="exportVal" value="">            
        </form>
    </td>
  </tr>  
  <tr>
    <td align="left" valign="top"><div id="divGrid" style="margin-top:15px;"></div></td>
  </tr>
  
  <tr>
  	<td height="15">&nbsp;</td>
  </tr>
  
  <tr style="display:none;" id="btnTd">
    <td height="30" align="right" valign="top"><?php
    $btnArr = array();
    if($_SESSION['user_type']!='1')
    {
        $btnArr['13'] = array("onclick"=>"export_xls();");
        $btnArr['107'] = array("onclick"=>"window.print()","name"=>"printingButton","id"=>"printingButton");
    }else{ 
        echo '&nbsp;';
    } 
    $btnArr['14'] = array("onclick"=>"window.close();","id"=>"close_win");
    echo hooks_getbutton($btnArr);
    unset($btnArr);
    ?></td>
  </tr>
</table></td>
  </tr>
</table>
</body>
</html>
