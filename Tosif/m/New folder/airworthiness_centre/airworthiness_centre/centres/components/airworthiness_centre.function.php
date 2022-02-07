<?php
$xajax->registerFunction("SetForm");
$xajax->registerFunction("jsError");

// Function used for Add Error to database that will be occur in JS page
function jsError($FunctionName,$Msg,$Error,$Errorarr)
{
	$objResponse = new xajaxResponse();		
	ExceptionMsg($FunctionName.'  -  '.$Msg.'  -  '.$Error.'  -  '.serialize($Errorarr),"Airworthiness Review Centre");	
	return $objResponse;	
}

// Function Used for assign the value of row that will be selected by End User.
function SetForm($id,$type)
{
	global $db,$mdb;
	$getArr = array();	
	$objResponse = new xajaxResponse();			
	if($id == "" || !is_numeric($id))
	{
		ExceptionMsg("Section airworthiness centre &nbsp;&raquo;&nbsp;component&nbsp;&raquo;&nbsp;component function file : Main Id is Blank, can not Perform Set Form Operation","airworthiess centre");
		$objResponse->alert(ERROR_FETCH_MESSAGE);
		$objResponse->script("getLoadingCombo(0);");
		return $objResponse;
	}
	if($type==1){
		$db->select("*","archive",array("ID"=>$id));	
		$db->next_record();	
		$objResponse->assign("id","value",html_entity_decode($id));
		$objResponse->assign("aircraft_type","value",html_entity_decode($db->f("AIRCRFTTYPE")));
		if($db->f("MANDATE") != '')
		$objResponse->assign("date_man","value",html_entity_decode(date('d-m-Y',strtotime($db->f("MANDATE")))));
		else
		$objResponse->assign("date_man","value",'');
		if($db->f("REGDATE") != '')	
		$objResponse->assign("date_reg","value",html_entity_decode(date('d-m-Y',strtotime($db->f("REGDATE")))));
		else
		$objResponse->assign("date_reg","value",'');
		$getArr = getTail(html_entity_decode($db->f("AIRCRFTTYPE")),html_entity_decode($db->f("CLIENTID")),html_entity_decode($db->f("TAIL")),1);
		$objResponse->assign("TailListId","innerHTML",html_entity_decode($getArr[0]));	
	} else {
		$db->select("*","fd_eng_master",array("id"=>$id));
		$db->next_record();
		$eng_id=$db->f("id");
		if($db->f("is_onground") == 1){	
				$objResponse->assign("selLocation","value","2");
				$objResponse->assign("selCurrentlyOn","value","0");	
				$objResponse->assign("selClientTo","value",0);		
		} else if($db->f("is_shop_visit") == 1)	{
				$objResponse->assign("selLocation","value","3");
				$objResponse->assign("selCurrentlyOn","value","0");
				$objResponse->assign("selClientTo","value",0);
		} else	{
				// for Different client which is currentli on
				$ClientTo = getClientToStr('',$db->f("engine_type"),true);	
				$objResponse->assign("spnselClientTo","innerHTML",$ClientTo[0]);
				
				
				$mdb->select("CLIENTID","archive",array("TAIL"=>$db->f("currently_on")));
				$mdb->next_record();
				$objResponse->assign("selLocation","value","1");
				$objResponse->assign("selClientTo","value",$mdb->f("CLIENTID"));
				$objResponse->assign("selCurrentlyOn","value",$db->f("currently_on"));	
				
				
				$AircraftS = SetClientAir_Edit($mdb->f("CLIENTID"),2,$ClientTo[1],$id);
				$objResponse->assign("spnselCurrentlyOn","innerHTML",$AircraftS);						
				$objResponse->assign("selCurrentlyOn","value",$db->f("currently_on"));
		}		
		$entype = getEngineTypeCombo_Edit($db->f("client"),true);
		$objResponse->assign("TdCompType","innerHTML",$entype);
		$objResponse->assign("selEngineType","value",$db->f("engine_type"));
		$whArr  = array();
		$whArr["ID"] = $db->f("engine_type");
		$setAircraftPosition = getAircPosFrmEngType($whArr);
		$objResponse->assign("tdAircraftPosition","innerHTML",$setAircraftPosition);
		$objResponse->assign("txtAircraftPosition","value",$db->f("aircraft_position"));
	}
	$objResponse->script("getLoadingCombo(0);");
	return $objResponse;
}

// Function return the array of Tail Values in Dropdown and Manufacture name of specific Tail.
function getTail($typeId,$clientid,$tid,$flg)
{
	global $db;
	$arrPass = array();			
	if($flg==1){
		$disable = "disabled='disabled'";
	}	
	$arrwhere["TYPEID"]=$typeId;
	$arrwhere["CLIENTID"]=$clientid;
	$db->select("*","aircraft_tail",$arrwhere ,"TAIL");
	while($db->next_record())
	{
		$selected=($db->f("ID")==$tid)?"selected='selected'":"";
		$option  .= "<option value='".$db->f("ID")."' ".$selected.">".$db->f("TAIL")."</option>";
	}		 
	$option = "<select name='tailno' id='tailno' $disable>
	<option value=''>Select Tail</option>".$option ."</select>";		
	if($typeId!=""){
		 $db->select("manufacturer","aircrafttype",array("ID"=>$typeId));
		 $db->next_record();						 
		 $manufacutrer=$db->f('manufacturer');		 
	} else {
		 $manufacutrer="";
	}
	$arrPass[0] = $option;
	$arrPass[1] = $manufacutrer;
	return $arrPass; 
}

function getClientToStr($airTypeIds,$engineTypeId,$isDesable=true) // get engine type combo client wise
{	
	global $engdb;
	$isdi = "";
	if($isDesable=='true'){
		$isdi = " disabled='disabled'";
	}   
	$airTypeIds = $engdb->getSameTypeWithClientList($engineTypeId);
	$airTypeIds = ($airTypeIds!='')?$airTypeIds:0;
	$combo = "<select name='selClientTo' id='selClientTo' tabindex='1' $isdi onChange='javascript:chClientTo(this.value);'>		 
	<option value='0'>[Select Client]</option>";
	$engdb->getClientTo($airTypeIds);
	while($engdb->next_record()){
			$combo.="<option value=".$engdb->f("fid").">".$engdb->f("COMP_NAME")."</option>";
	}
	$combo .= "<select>";   
	return array($combo,$airTypeIds);
}

// Function return the array of Client(s) with their name and ID. 
function GetClientArr()
{	
	global $db;
	$ClientArr = array();
	if($db->select("ID,COMP_NAME","fd_airlines","","COMP_NAME") == false){
		return false;
	} else	{
		while($db->next_record()) {
			$ClientArr[html_entity_decode($db->f('ID'))] = html_entity_decode($db->f('COMP_NAME'));
		}
		return $ClientArr;
	}
}
function getEngineTypeCombo_Edit($airlinesId,$isDesable = false,$selId = '') // get engine type combo client wise
{	 
	$isdi = "";
	if($isDesable=='true'){
		$isdi = " disabled='disabled'";
	}   	
   $combo = "<select name='selEngineType' id='selEngineType' class='textInput' tabindex='1' onchange='SetManufactur(this.value);' $isdi><option value='0'>[Select Engine Type]</option>";
   $combo .= getTypeCombo(2,$airlinesId,$selId);
   $combo .= "<select>";   
   return $combo;
}
function getAircPosFrmEngType($where_arr)
 {
	global $db,$engdb;	
	$str='';
	$db->select("*","enginetype",$where_arr);
	$db->next_record();
	$aircradttypesIds = explode(',',$db->f("aircrafttype_id"));
	$quest = '';
	for($j=0;$j<count($aircradttypesIds);$j++){
		$whArr[$j] = $aircradttypesIds[$j];
		$quest .= $coma.'?';
		$coma = ',';
	}
	$strrr = $engdb->getAircPosFrmEngTypeCls($quest,$whArr);
	$str .= '<select class="textInput" name="txtAircraftPosition" disabled="disabled" id="txtAircraftPosition"><option value="0">[Select Position]</option>';
	for($k=1;$k<=$strrr;$k++){
		$str .= '<option value="'.$k.'">'.$k.'</option>';
	}
	$str .= '</select>';	
	return $str;
}
 
function SetClientAir_Edit($clientId,$selLoc,$airtypeid,$eid) // get client wise aircraft and engine type
{	
	global $engdb;
	if($selLoc=='1'){
		$disabled="";
	}
	else
	{
		$disabled='disabled="disabled"';
	}
	$str = '<select  name="selCurrentlyOn" id="selCurrentlyOn" '.$disabled.' onchange="setAircPos(this.value);">';
	$str.='<option value="0" selected>[Select Aircraft]</option>';
	$str .= $engdb->setClientAirquery($clientId,$airtypeid,$eid);
	$str.='</select>'; 		
	return $str;	
}
function getControls()
{
	global $lang,$controlPrivArr,$db;
	$controlStr='';
	if(count($controlPrivArr)>0 || $_SESSION['UserLevel']==0){
    	$controlsStr = $lang['40'];

    	$controlStr.='<div onmouseout="if (isMouseLeaveOrEnter(event, this)) manageSubMenuOut_C();" onmouseover="if (isMouseLeaveOrEnter(event, this)) manageSubMenuHOver_C();">';
    	$controlStr.='<div style="display:block;" class="managebutton">'.$controlsStr.'</div>';
    	$controlStr.='<ul id="manageSubMenu_C" class="manageSubMenu" style="display:none;margin-left:-74px;text-align: left";">';	
		if($_SESSION['UserLevel']==0 || isset($controlPrivArr['1'])){
    	   $controlStr.='<li style="cursor:pointer; text-align:left; "><a onClick="openStatuslist();"><strong>&raquo; '.$lang['361'].' </strong></a></li>';
    	}  		
		if($_SESSION['UserLevel']==0 || isset($controlPrivArr['2'])){
       	    $controlStr.='<li style="cursor:pointer; text-align:left;"><a onClick="openWorkStatuslist();"><strong>&raquo; '.$lang['306'].' </strong></a></li> ';
    	}
		if($_SESSION['UserLevel']==0 || isset($controlPrivArr['3'])){
    	   $controlStr.='<li style="cursor:pointer; text-align:left;"><a onClick="openEmailTemplate();"><strong>&raquo; '.$lang['297'].' </strong></a></li> ';
    	}		
		if($_SESSION['UserLevel']==0 || isset($controlPrivArr['4'])){
    	   $controlStr.='<li style="cursor:pointer; text-align:left;"><a onClick="openARCSequence();"><strong>&raquo; '.$lang['395'].' </strong></a></li> ';
    	}		 	
    	
    	$controlStr.='</ul>';
    	$controlStr.'</div>';
	}
	return $controlStr;
}
function getReports()
{
	global $lang;
	$reportStr = $lang['39'];
	$ReprtStr='';
	$ReprtStr.='<div  id="CSreport" onMouseOver="if (isMouseLeaveOrEnter(event, this)) manageSubMenuHOver_R();"  onmouseout="if (isMouseLeaveOrEnter(event, this)) manageSubMenuOut_R();">';
	$ReprtStr.='<div class="managebutton" style="display:block;">'.$reportStr.'</div>'; 
	$ReprtStr.='<ul id="manageSubMenu_R" class="manageSubMenu" style="display:none;margin-left:-81px;text-align: left;">';	
	$ReprtStr.='<li><a href="#" onClick="javascript:openTabReport();"><strong>&raquo; '.$lang['347'].'</strong></a></li>';
	$ReprtStr.='</ul>';
	$ReprtStr.='</div></div>';
	return $ReprtStr;
}
?>
