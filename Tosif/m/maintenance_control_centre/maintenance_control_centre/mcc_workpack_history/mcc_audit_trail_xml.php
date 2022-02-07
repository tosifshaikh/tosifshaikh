<?php 
header('Content-Type: text/xml');
$strResponse = '<?xml version="1.0" encoding="UTF-8"  standalone="yes"?>';
$eu=($Request['Start']!="")?$Request['Start']:0;
$keyword=($Request['keyword']!="")?trim($Request['keyword']):"";
$opt=($Request["opt"]!="")?$Request["opt"]:"";
$filtertype=($Request['filtertype']!="")?$Request['filtertype']:"";
$type=($Request['type']!="")?$Request['type']:"";
$DatatypeArr=Check_DataType(array('eu'=>$eu));
if(sizeof($DatatypeArr)==0)
{
		$WhereClause="";
		$qry="";
 				
		if(isset($opt) && $opt != "")
		{
			  $WhereClauseArr[]=escape($opt);
			  $WhereClause.=" AND action =? ";
		}
	
		if($Request['client_id']!='' && $Request['client_id']!=0)
		{
			$WhereClauseArr[] = $Request['client_id'];
			$WhereClause .= " AND client_id =? ";
		}
		if($Request['type']!='' && $Request['type']!=0)
		{
			$WhereClauseArr[] = $Request['type'];
			$WhereClause .= " AND type =? ";
		}
		$strResponse .="<root>";  //Start xml
		
		if($keyword!="")
		{
			$clientIDs='';
			$clientIDs=$db->Clinetids_keywordsearch($keyword);
			$key=is_numeric(trim(str_ireplace("row","",$keyword)))?trim(str_ireplace("row","",$keyword)):-1;
			$key1=str_replace("00:00:00","",$db->GetDateTime($keyword));
		
			$WhereClauseArr[]="%".$keyword."%";
			$WhereClauseArr[]="%".$keyword."%";
			$WhereClauseArr[]="%".$keyword."%";
			$WhereClauseArr[]="%".$keyword."%";
			$WhereClauseArr[]="%".$keyword."%";
			$WhereClauseArr[]="%".$keyword."%";
			$WhereClauseArr[]="%".$key1."%";
		
			$WhereClause.=" AND (user_name LIKE ? OR file_path LIKE ? OR new_file_path LIKE ? OR";
			$WhereClause.=" old_value LIKE ? OR new_value LIKE ? OR action LIKE ? OR action_date LIKE ?)";
			}
		$qry .= " 1=1 ".$WhereClause."AND action IN('COLUMN ADDED','COLUMN EDITED','COLUMN DELETED','COLUMN REORDERED')";
		
		
		if($ndb->query_mcc_others($qry,$WhereClauseArr,$Request,""))
	    { 
		  while($ndb->next_record())
		  {
		  $cnts=$ndb->f('tot');
		  }
		  
		  $db->query_mcc_others($qry,$WhereClauseArr,$Request,$eu);
		  while($db->next_record())
		  {
				
				$UserName=($db->f('user_name')!="")?$db->f('user_name'):"-";
				$userarr = array();
				$action_date=($db->f('action_date')!="0000-00-00" && $db->f('action_date')!="")?showDateTime($db->f("action_date")):"-";	
				
				$strResponse .="<row>";
				
				if($db->f("client_id")==0)
				{
					$strResponse .="<client>";
					$strResponse .="-";
					$strResponse .="</client>";
				}else{
					$strResponse .="<client>";
					$strResponse .=getAirlinesNameById($db->f("client_id"));
					$strResponse .="</client>";
				}
					
				$strResponse .="<title>";
				$strResponse .=$db->f('field_title');
				$strResponse .="</title>";
							
				$strResponse .="<rid_actn_actndate><![CDATA[";
				$strResponse .=$db->f('id')."+".$db->f('action')."+".$action_date."+".$ujoin;
				$strResponse .="]]></rid_actn_actndate>";
			
				$strResponse .="<uname>";
				$strResponse .=$UserName;
				$strResponse .="</uname>";
				
				$old_value=($db->f('old_value')!="")?$db->f('old_value'):"-";
				$new_value=($db->f('new_value')!="")?$db->f('new_value'):"-";
				
				$strResponse .="<new_value><![CDATA[";
				$strResponse .=utf8_encode($new_value);
				$strResponse .="]]></new_value>";
				
				$strResponse .="<old_value><![CDATA[";
				$strResponse .=utf8_encode($old_value);
				$strResponse .="]]></old_value>";
				$strResponse.="</row>";
									
			}
			
			$strResponse .="<audit_type>";
			$strResponse .=($audit_type=='date')?"ROW":"GEN";
			$strResponse .="</audit_type>";
			$strResponse.="<total>".$cnts."</total>";
			$strResponse.="<qry><![CDATA[".base64_encode($qry)."]]></qry><wca><![CDATA[".implode("FLYDOCS_COMMA",$WhereClauseArr)."]]></wca>";

			
			if($cnts<1)
			{
					$strResponse.="<q><![CDATA[]]></q><error>No Records Found.</error>";
			}
				
  		}else{				
			$strResponse="<root><qry><![CDATA[]]></qry><wca><![CDATA[]]></wca><error>".ERROR_FETCH_MESSAGE."</error>";
		  }
	}else{				
			  $message="URL:".serialize($_REQUEST)."<br />\n";
			  $message.="Error Discription: Invalid Data Type  may be ".implode(",",$DatatypeArr);
			  ExceptionMsg($message,'Audit Trails');
			  $strResponse="<root><qry><![CDATA[]]></qry><wca><![CDATA[]]></wca><error>".ERROR_FETCH_MESSAGE."</error>";
    }	  	
	
			 $strResponse .= '</root>'; //End xml		  
echo $strResponse;

?>