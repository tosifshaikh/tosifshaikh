<?php
$colArr = json_decode($_REQUEST["headerObj"],true);
$isFlydocsUser = ($_SESSION["UserLevel"] == '3' || $_SESSION["UserLevel"] == '0')? 1:0;
$sectionVal = $_REQUEST["section"];
$sub_sectionVal = $_REQUEST["sub_section"];
$comp_main_id = $_REQUEST["comp_main_id"];
$type= $_REQUEST["type"];
$aircraft_id = $_REQUEST["link_id"];
$clientId = $_REQUEST["client_id"];
$whrArr = array();
$whrArr["aircraft_id"]=$aircraft_id;
$whrArr["type"]=$type;
$whrArr["client_id"]=$clientId;
$whrArr["comp_main_id"]=$comp_main_id;
$LovChkArr= json_decode($_REQUEST["LovValueCheck"],true);

$tabArr=getAirworthiCatArr($clientId);

$db->select("*","fd_airlines_config",array("airlines_id"=>$clientId));
$db->next_record();
$templateName=$db->f("template_name");

$whereStr = '';
if(isset($_REQUEST['searchObj'])){
	$searchObj = json_decode($_REQUEST["searchObj"],true);
	foreach($searchObj as $key=>$val){
		$whereStr .=" and $key like '%$val%' ";		
	}	
}


$valArr =array();
$categoryArr=array();
$inbox=0;
if(isset($_REQUEST['inboxmod']) && $_REQUEST['inboxmod']==1)
{
	$db->GetIDInbox($_REQUEST['UID']);
	$db->next_record();
	$whereStr .=" AND id in (".$db->f('data_man_id').") ";
	$inbox=1;
}

$statusArr = array();
$whr = array("type"=>$type,"delete_flag"=>0,"client_id"=>$clientId,"comp_main_id"=>$comp_main_id);
$db->getWorkStatus($whr,3,4);
while($db->next_record()){
	$statusArr[]=$db->f("id");
}

if(count($statusArr)>0)
{
	$whereStr .=" and work_status in (".implode(',',$statusArr).") ";
}



$db->getReviewRows($colArr,$whereStr,$whrArr);
$db->next_record();
$TempArrResult=$db->arr_result;
$db->free();
$resArrStr['res_users']='';

$countChild=array();
$linkArr=array();

$autoFilterVal = array();
if(count($LovChkArr)>0){	
	$autoFilterVal = getReviewFilterValues($LovChkArr,$comp_main_id,$aircraft_id);
}
foreach($TempArrResult as $key=>$val){
		
	$recID = 0;
	$tab_id="_".$val["category_id"];
	$recID  = "_".$val["id"];
	$type="_".$val["type"];
	$res_users=$val["res_users"];
	$isExclude=0;
	if($isExclude==0){
		$tempDataVal = array();
		if($res_users!='')
		$resArrStr['res_users'] .=($resArrStr['res_users']=='') ? $res_users : ','.$res_users;
		
		foreach($val as $tempKey=>$tempVal)	{
			if(is_string($tempVal))
				$tempDataVal[$tempKey]=iconv( "ISO-8859-1","UTF-8//TRANSLIT", ($tempVal));
			else
				$tempDataVal[$tempKey]=$tempVal;
		}
		$valArr[$tab_id][$recID]=$tempDataVal;
		$recIdArr[$tab_id][]=$recID;
		$categoryArr[$tab_id]="Item ".$val["cat"]." &raquo; ".$tabArr[$val["category_id"]];
		
		if(!isset($countChild[$val["PrntId"]]))
		{
			$countChild[$val["PrntId"]]=0;
		}
		
		$countChild[$val["PrntId"]]+=1;
		
		if($val["hyperlink_option"]!=0){	
			$linkArr[$tab_id][$recID]=array("centre_id"=>$val["centre_id"],"hyperlink_value"=>$val['hyperlink_value'],"rowid"=>$val['id']);
		}
		
	}
}
$totalRows =count($valArr);
$tabidArr=array_keys($categoryArr);
foreach($tabidArr as $val)
{
 	$AlltabidArr[]=str_replace('_','',$val);
}
$whrArr=array("c.comp_main_id"=>$comp_main_id);
$notes=$db->getNotesData($whrArr,$aircraft_id,1);

if(!isset($notes['resp_user']))
$notes['resp_user']=array();

$notesUserArr=array_keys($notes['users']);

$MainUserArr= array();
if(count($notesUserArr)>0){
	$sql="select a.id,case when a.level=5 then a.contact_name  else concat(a.fname,' ',a.lname)  end as uname,a.airlinesId,b.company_type,a.level from fd_users as a LEFT JOIN  ";
	$sql.=" fd_lease_company as b on a.leaseId=b.id where  a.id in (".implode(',',($notesUserArr)).")";
	$db->query($sql);
	$db->next_record();
	$UserArr=$db->arr_result;
	$db->free();
	
	foreach($UserArr as $key=>$val)
	{
		$userId=$val["id"];
		
		if($val['level']==1)
		$tval['CompanyName']=getCompanyTypeName($val['airlinesId']);
		else if($val['level']==5)
		$tval['CompanyName']=getCompanyNameLessor($val['airlinesId'],$val['company_type']);
		else 
		$tval['CompanyName']="FLYdocs";
		
		$tval['username']=$val['uname'];
		$tval['UserLevel']=$val['level'];
		
		$MainUserArr[$userId]=$tval;
		
	}
}


//for DOc count / view icon 

$whrArr = array();
$whrArr["aircraft_id"]=$aircraft_id;

$tabids=array_keys($tabArr['arrTab']);
$whrArr["type"]=$_REQUEST["type"];

$client=0;
if($_SESSION['user_type']==1)
$client=1;

$arr = array("comp_main_id"=>$comp_main_id);

$db->GetDocCountAirworthi($arr);
while($db->next_record()){
	$main_doc_arr["_".$db->f('data_main_id')] = $db->f('fileCnt');
}


//// for Hyper Link
$mainLinkArr=array();
foreach($linkArr as $tab_id=>$recidArr)
{
	foreach($recidArr as $rec_id=>$LinkvalArr)
	{
		$strResponse='';
		if($LinkvalArr["centre_id"] == 1)
		{
			if($_REQUEST['type']==1)
			{
				$strUrl=explode("###",getLinkedTabUrl($LinkvalArr["hyperlink_value"],$_REQUEST['link_id'],1,$rec_id));
				
			}
			else if($_REQUEST['type']!=1)
			{
				$strUrl=explode("###",getLinkedTabUrl($LinkvalArr["hyperlink_value"],$_REQUEST['link_id'],$_REQUEST['type'],$rec_id));
				
			}
		}
		else if($LinkvalArr["centre_id"] == 2)
		{
			$link_type_ = $LinkvalArr["link_type"];
			if($_REQUEST["SectionFlag"]!=1)
			{
				$link_type_=$_REQUEST['type'];
			}
			else if($_REQUEST["SectionFlag"]==1 && $_REQUEST['type']!=1)
			{
				$link_type_=0;
			}
			
			$strUrl[0] = getMHLinkedTabUrl($LinkvalArr["hyperlink_value"],$link_type_,$LinkvalArr["position"],$LinkvalArr["sub_position"],$_REQUEST['link_id'],$_REQUEST['type'],$LinkvalArr["rowid"],$LinkvalArr["bible_viewType"]);
			
				$NewName ='';
				if($_REQUEST['type']==1)
					{
						$NewName = "Maintenance History&nbsp;&raquo;&nbsp;";
					}
					else
					if($_REQUEST['type']!=1)
					{
						if($SectionFlag==$_REQUEST['type'])
							$NewName = "";
						else
							$NewName = "Maintenance History";
					}					
					if($LinkvalArr["bible_viewType"]==0)
					{
						$NewName = $NewName.mhLink($_REQUEST['link_id'],$link_type_,$LinkvalArr["position"],$LinkvalArr["sub_position"],$LinkvalArr["bible_viewType"]).mhLinkName($LinkvalArr["hyperlink_value"]);
					}
					else
					{
						$NewName = $NewName.mhLink($_REQUEST['link_id'],$link_type_,$LinkvalArr["position"],$LinkvalArr["sub_position"],$LinkvalArr["bible_viewType"]).$templateName." View";
					}
					
					$strUrl[1] = $NewName;
		}
		$mainLinkArr[$tab_id][$rec_id]['links']=$strUrl[0];
		$mainLinkArr[$tab_id][$rec_id]['tiptext']=$strUrl[1];
	}
		
}

$parentArr = array();
$parentArr['rowData'] = $valArr;
$parentArr['totalRows'] = $totalRows;
$parentArr['autofiletrVal'] = $autoFilterVal;
$parentArr['category'] = $categoryArr;
$parentArr['Notes'] =$notes;
$parentArr['NotesUser'] = $MainUserArr;
$parentArr['countChild'] =$countChild;
$parentArr['Docs']=$main_doc_arr;
$parentArr['Links']=$mainLinkArr;
echo json_encode($parentArr);
?>