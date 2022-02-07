<?php
class manage_audit_trail extends DB_Sql
{
	function getMasterAuditTrailGrid($sublinkId,$wh,$start=0)
	{
		$sqlStr = "select a.*,u.fname,u.lname from fd_masters_audit_trail  as a join fd_users as u on a.add_by = u.id  where sublink_id='".$sublinkId."' $wh order by a.id desc limit $start,100";
		return DB_Sql::query($sqlStr);
	}
	
	function getUTLAuditTrailGrid($sublinkId,$wh,$start=0)
	{
		$sqlStr = "select a.*,u.fname,u.lname from fd_utl_audit_trail  as a join fd_users as u on a.add_by = u.id  where sublink_id='".$sublinkId."' $wh order by a.id desc limit $start,100";
		return DB_Sql::query($sqlStr);
	}
	
	//function is used for get data for FLYsearch audit trail
	function getFLYsearchAuditTrailGrid($wh,$start=0)
	{
		$whereArray[]=$_REQUEST['link_id'];
		$whereArray[]=$_REQUEST['tab_id'];
		$whereArray[]=$_REQUEST['Type'];
		
		
		$sqlStr = "select a.*,u.fname,u.lname from  fd_flysearch_audit_trail  as a join fd_users as u on a.add_by = u.id  where AircraftID=? AND TabID=? AND Type=? $wh order by a.id desc limit $start,100";
		return DB_Sql::query($sqlStr,$whereArray);
	}

	function getMasterAuditTrailCount($sublinkId,$wh)
	{
		$sqlStr = "select count(*) as count from fd_masters_audit_trail  as a join fd_users as u on a.add_by = u.id  where sublink_id='".$sublinkId."' $wh order by a.id desc";
		DB_Sql::query($sqlStr);
		DB_Sql::next_record();
		return DB_Sql::f('count');
	}
	
	function getUTLAuditTrailCount($sublinkId,$wh)
	{
		$sqlStr = "select count(*) as count from fd_utl_audit_trail as a join fd_users as u on a.add_by = u.id  where sublink_id='".$sublinkId."' $wh order by a.id desc";
		DB_Sql::query($sqlStr);
		DB_Sql::next_record();
		return DB_Sql::f('count');
	}
	
	// function used for flysearcg audit trail count WRT AircraftId, TabID,Type
	function getFLYsearchAuditTrailCount($wh)
	{
		$AircraftID=$_REQUEST['link_id'];
		$TabID=$_REQUEST['tab_id'];
		$Type=$_REQUEST['Type'];
		
		$sqlStr = "select count(*) as count from  fd_flysearch_audit_trail  as a join fd_users as u on a.add_by = u.id  where AircraftID='".$AircraftID."' AND TabID='".$TabID."' AND Type='".$Type."' $wh order by a.id desc";
		DB_Sql::query($sqlStr);
		DB_Sql::next_record();
		return DB_Sql::f('count');
	}

	
	function getMetatmplAuditTrailGrid($part_sql,$WhereClauseArr,$Request,$eu)
	{
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				
				$part_sql.= " AND Date(date) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				$part_sql.= " AND Date(date) =? ";
			}
		}
		else if(isset($_REQUEST['adtFrom']) && $_REQUEST['adtFrom']=='CENTRAL_ADT')
		{
			$dt = date('Y-m-d');
			$part_sql .= " and date like '%".$dt."%'";
		}
		if($eu!="")
		{	
			 $sqlStr = "select a.* from fd_metatmpl_audit_trail as a where 1=1 and new_value is not null and new_value != '' ".$part_sql." order by a.id desc limit $eu,100"; 
		}
		else
		{
			$sqlStr = "select  count(a.id) as tot from fd_metatmpl_audit_trail as a where 1=1 and new_value is not null and new_value != '' ".$part_sql." order by a.id desc";		
		}
		return $this->query($sqlStr,$WhereClauseArr);
	}

	//	
	function getMetafieldAuditTrailGrid($part_sql,$WhereClauseArr,$Request,$eu)
	{
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
          //print_r($WhereClauseArr);
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				
				$part_sql.= " AND Date(date) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				$part_sql.= " AND Date(date) =? ";
			}
		} 
		if($eu!="")
		{	
			 $sqlStr = "select a.* from fd_metafield_audit_trail as a where 1=1 and new_value is not null and new_value != '' ".$part_sql." order by a.id desc limit $eu,100"; 
		}
		else
		{
			 $sqlStr = "select  count(a.id) as tot from fd_metafield_audit_trail as a where 1=1 and new_value is not null and new_value != '' ".$part_sql." order by a.id desc";	
				
		}
		return $this->query($sqlStr,$WhereClauseArr);
	}
	function getMetaMapAuditTrailGrid($part_sql,$WhereClauseArr,$Request,$eu)
	{
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				
				$part_sql.= " AND Date(DateTime) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				$part_sql.= " AND Date(DateTime) =? ";
			}
		}
		else if(isset($_REQUEST['adtFrom']) && $_REQUEST['adtFrom']=='CENTRAL_ADT')
		{
			$dt = date('Y-m-d');
			$part_sql .= " and Date(DateTime) like '%".$dt."%'";
		}
		if($eu!="")
		{	
			 $sqlStr = "select a.* from fd_audit_trail_metadata as a where 1=1  ".$part_sql." order by a.id desc limit $eu,100"; 
		}
		else
		{
			$sqlStr = "select  count(a.id) as tot from fd_audit_trail_metadata as a where 1=1 ".$part_sql." order by a.id desc";		
		}
		//echo $sqlStr; exit;
		return $this->query($sqlStr,$WhereClauseArr);
	}
	
	function getReturnProfileGrid($part_sql,$WhereClauseArr,$Request,$eu)
	{
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
        
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				
				$part_sql.= " AND Date(action_date) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				$part_sql.= " AND Date(action_date) =? ";
			}
		}
		else if($_REQUEST['adtFrom']=='CENTRAL_ADT')
		{
			$dt = date('Y-m-d');
			$part_sql .= " and Date(action_date) like '%".$dt."%'";
		}
		if($eu!="")
		{	
			$sqlStr = "select a.* from fd_cs_audit_trail as a where 1=1  ".$part_sql." order by a.action_date desc limit $eu,100"; 
		}
		else
		{
			$sqlStr = "select  count(a.id) as tot from fd_cs_audit_trail as a where 1=1 ".$part_sql." order by a.action_date desc";		
		}
		//print_r($WhereClauseArr);
		//echo $sqlStr; exit;
		return $this->query($sqlStr,$WhereClauseArr);
	}
	
	function getProcessLinkAuditGrid($part_sql,$WhereClauseArr,$Request,$eu)
	{
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				$part_sql.= " AND Date(dateTime) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				$part_sql.= " AND Date(dateTime) = ? ";
			}
		} 
		if($eu!="")
		{	
			$sqlStr = "select a.* from fd_meta_process_audit as a where 1=1  ".$part_sql." order by a.id desc limit $eu,100"; 
		}
		else
		{
			$sqlStr = "select  count(a.id) as tot from fd_meta_process_audit as a where 1=1 ".$part_sql;	
		}
		return $this->query($sqlStr,$WhereClauseArr);
	}
	//Function Use To Get List of Aircraft. 
	
	function getAircaftTail($TailID) // For centers audit trail done
	{
         $WhereArr[]=$TailID;
		 $sql_qry="SELECT  tail.*  FROM  archive as arch join aircraft_tail as tail on arch.TAIL = tail.ID and tail.ID=? and arch.IS_DELETE in(0,2) ORDER BY tail.TAIL";
		 return DB_Sql::query($sql_qry,$WhereArr);
    }
		
	// Get Group of Aircrafts Ids as per Aircraft Name (For Keyword Search Purpose)...
	function TailIds_keywordsearch($tailname) //done
	{
		$WhereClauseArr[]="%".$tailname."%";
		$sql="SELECT GROUP_CONCAT(ID) as TailId FROM aircraft_tail WHERE TAIL LIKE ? ";
		$this->query($sql,$WhereClauseArr);
		$this->next_record();
		if($this->f('TailId')!="")
		{
			 $TAILID=$this->f('TailId').",";
			 $TAILID=trim($TAILID,",");
		}else{
		 	 $TAILID="";  
		}
		return  $TAILID;
	}
	
	function Clinetids_keywordsearch($clName) //done
	{
		$WhereClauseArr=array();
		$WhereClauseArr[]="%".$clName."%";
		$sql="SELECT GROUP_CONCAT(ID) as clID FROM fd_airlines WHERE COMP_NAME LIKE ? ";		
		
		$this->query($sql,$WhereClauseArr);
		$this->next_record();
		$clID = "";
		if($this->f('clID')!=""){
		    $clID=$this->f('clID');			
		}
		return  $clID;
	}
	
	// Get Group of Box Ids as per Group Name (For Keyword Search Purpose)...
	function GroupIds_keywordsearch($boxname) //done
	{
		global $cdb,$db;
		$WhereClauseArr[]=$boxname;
		//$cdb->select("GROUP_CONCAT(ID) as GroupId","fd_cs_group",$WhereClauseArr,"","","0");
		$sql="Select GROUP_CONCAT(ID) as GroupId FROM fd_cs_group Where boxname LIKE ? ";
		$this->query($sql,$WhereClauseArr);
		$this->next_record();
		if($this->f('GroupId')!="")
		{
			$BoxID.= $this->f('GroupId').",";
			$BoxID=trim($BoxID,",");
		}else{
			$BoxID="";
		}
		return $BoxID;
	}

	//Function Use To Get Third party Client User list As per selected aircraft.

	function getClientUserLOV($masterId) //done
	{
        if($masterId!="")
		{
			$TmpArr=explode("|",$masterId);
			$WhereClauseArr[]=end($TmpArr);
			$strcls = " taa.componentId='".end($TmpArr)."' AND ";			
		}
		
		//$strsql = "select distinct(contact_name) as  contact_name,comp_name from fd_users as fdu,thirdparty_aircraft_access as taa  WHERE ".$strcls." fdu.id=taa.subclientid AND fdu.level='5' AND fdu.user_type='1' and dflag in (0,2) order by comp_name";
		
		$strsql="select DISTINCT(u.contact_name) as contact_name,l.COMP_NAME as comp_name from fd_users as u  
JOIN thirdparty_aircraft_access as taa ON u.id = taa.subclientid
JOIN fd_lease_company as l ON l.ID = u.leaseId
JOIN fd_airlines as a ON a.ID = u.airlinesId
WHERE  ".$strcls."    u.level='5' AND u.user_type='1' and u.dflag in (0,2) order by l.comp_name";
		
		return DB_Sql::query($strsql);
	}
	
	//Function get Component list with their Type As per selected aircraft. 
	
	function getCompList($tailId) 
	{
         $whereArr[]=$tailId;
		 $sql_qry="SELECT get_compId_bytailId(?) as complist";
		 return DB_Sql::query($sql_qry,$whereArr);
    }
	
	/*function totalRowIntdoc($part_sql)
	{ 
		 $sql_qry="SELECT id FROM fd_if_audit_trail WHERE ".$part_sql." AND action IN('DOCUMENT ATTACHED','DOCUMENT COPIED','DOCUMENT MOVED')";
		 return DB_Sql::query($sql_qry);
		
		//return DB_Sql::select("id","fd_if_audit_trail",$part_sql." AND action IN('DOCUMENT ATTACHED','DOCUMENT COPIED','DOCUMENT MOVED')");
	}*/
    
	//Function get Total Row count Of Cs/Lease Company Audit trail.
	
	function query_cs_lease($part_sql,$WhereClauseArr,$Request,$eu) //done
	{ 
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
        
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				
				$part_sql.= " AND Date(action_date) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				$part_sql.= " AND Date(action_date) =? ";
			}
		}
		else if($_REQUEST['adtFrom']=='CENTRAL_ADT')
		{
			$dt = date('Y-m-d');
			$part_sql .= " and Date(action_date) like '%".$dt."%'";
		}
		 
		if($part_sql!="")
		{
		   $Sub_part_sql=" WHERE ".$part_sql;
		}
		         
		if($eu!="")
		{
		    $sql_query="Select * from fd_cs_audit_trail ".$Sub_part_sql." ORDER BY action_date desc LIMIT $eu,100";
	
		}else{
	
			$sql_query="Select count(id) as tot from fd_cs_audit_trail ".$Sub_part_sql."  ORDER BY action_date desc";
		}
		 //echo $sql_query;
	    // print_r($WhereClauseArr);
		return $this->query($sql_query,$WhereClauseArr);
	}
	
	function query_cs_lease_report($part_sql,$WhereClauseArr,$Request,$eu) //done
	{ 
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
        
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				
				$part_sql.= " AND Date(action_date) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				$part_sql.= " AND Date(action_date) =? ";
			}
		}
		else if($_REQUEST['adtFrom']=='CENTRAL_ADT')
		{
			$dt = date('Y-m-d');
			$part_sql .= " and Date(action_date) like '%".$dt."%'";
		}
		 
		if($part_sql!="")
		{
		   $Sub_part_sql=" WHERE ".$part_sql;
		}
		         
		if($eu!="")
		{
		    $sql_query="Select fd_cs_audit_trail.* from fd_cs_audit_trail inner join fd_users on fd_users.id= fd_cs_audit_trail.user_id  ".$Sub_part_sql." and level=5 ORDER BY action_date desc LIMIT $eu,100";
	
		}else{
	
			$sql_query="Select count(fd_cs_audit_trail.id) as tot from fd_cs_audit_trail inner join fd_users on fd_users.id= fd_cs_audit_trail.user_id ".$Sub_part_sql." and level=5 ORDER BY action_date desc";
		}
		//echo $sql_query;
	    // print_r($WhereClauseArr);
		return $this->query($sql_query,$WhereClauseArr);
	}
	//Function get Total Row count Of MH/Open Folader Audit trail.
	
	function query_mh($part_sql,$WhereClauseArr,$Request,$eu,$MODE) //done
	{ 
	    global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
          
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				
				$part_sql.= " AND Date(action_date) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				
				$part_sql.= " AND Date(action_date) =? ";
			}
		} 
	    
		if($part_sql!="")
		{
		   $Sub_part_sql=" WHERE ".$part_sql;
		}
		if($MODE == "MH")
		{
			$TempWhereClauseArr = $WhereClauseArr;
			$WhereClauseArr = array_merge($WhereClauseArr,$TempWhereClauseArr);
		}
	   	if($eu!="")
		{
			$sql_query="( select * from (Select id,Section,user_id,old_file_id,old_path,old_value,new_tail_name,replace(new_tab_name ,'Â','') as 
new_tab_name,new_rec_id,new_box_name,new_file_id,new_path,new_value,action,action_date,isfolder,viewtype,folder_id,airlinesId from audit_trails  ".$Sub_part_sql." and action = 'RENAMED DOCUMENT' order by action_date desc) as tbl GROUP BY tbl.folder_id ) UNION (Select id,Section,user_id,old_file_id,old_path,old_value,new_tail_name,replace(new_tab_name ,'Â','') as new_tab_name,new_rec_id,new_box_name,new_file_id,new_path,new_value,action,action_date,isfolder,viewtype,folder_id,airlinesId from audit_trails ".$Sub_part_sql." and action <> 'RENAMED DOCUMENT' ) order by action_date desc limit $eu,100";		
	   	}
		else
		{
		
			$sql_query = "select count(tbl.id) as tot from ((Select id,Section,user_id,old_file_id,old_path,old_value,new_tail_name,replace(new_tab_name ,'Â','') as new_tab_name,new_rec_id,new_box_name,new_file_id,new_path,new_value,action,action_date,isfolder,viewtype,folder_id,airlinesId from audit_trails ".$Sub_part_sql." and action <> 'RENAMED DOCUMENT' ORDER BY action_date desc ) UNION ( Select id,Section,user_id,old_file_id,old_path,old_value,new_tail_name,replace(new_tab_name ,'Â','') as new_tab_name,new_rec_id,new_box_name,new_file_id,new_path,new_value,action,action_date,isfolder,viewtype,folder_id,airlinesId from audit_trails ".$Sub_part_sql." and action = 'RENAMED DOCUMENT' GROUP BY folder_id ORDER BY action_date desc) order by action_date desc) as tbl";
		
	    }		
		return $this->query($sql_query,$WhereClauseArr);
	}
	
	function GetRenameFilesFolder($section,$tail_id,$folder_id,$Request)
	{
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
		
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				
				$part_sql.= " AND Date(action_date) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				
				$part_sql.= " AND Date(action_date) =? ";
			}
		} 
				 
		 $sql_query ="select * from audit_trails where tail_id=".$tail_id." and viewtype=".$section." and folder_id=".$folder_id." AND ACTION='RENAMED DOCUMENT' ".$part_sql." order by action_date desc"; 
		 return $this->query($sql_query,$WhereClauseArr);		 
	}
	
	//Function get Total Row count Of MH/Search/Component Centre Audit trail.
	
	function query_component_search($part_sql,$WhereClauseArr,$Request,$eu) //done
	{ 
	    global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
          
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				
				$part_sql.= " AND Date(action_date) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				$part_sql.= " AND Date(action_date) =? ";
			}
		}
		else if($_REQUEST['adtFrom']=='CENTRAL_ADT')
		{
			$dt = date('Y-m-d');
			$part_sql .= " and Date(action_date) like '%".$dt."%'";
		}
	    	if($_SESSION['UserLevel'] != "0")
		{
			if($_SESSION['data_view_option_list'] == "")
			{
				$in = "''";
			}
			else
			{
				$in = $_SESSION['data_view_option_list'];
			}
			$part_sql.= " and (airlinesId = '".$_SESSION['MainClient']."'  or  airlinesId in(".$in.") )";
		}
		if($part_sql!="")
		{
		   $Sub_part_sql=" WHERE ".$part_sql;
		}
		
	   	if($eu!="")
		{
			$sql_query="Select id,Section,user_id,old_file_id,old_path,old_value,new_tail_name,replace(new_tab_name ,'Â','') as new_tab_name,";
			$sql_query.=" new_rec_id,new_box_name,new_file_id,new_path,new_value,action,action_date,isfolder,viewtype,folder_id,airlinesId ";
			$sql_query.=" from audit_trails ".$Sub_part_sql." ORDER BY action_date desc LIMIT $eu,100"; 
			//echo $sql_query;print_r($WhereClauseArr);exit;
			//return DB_Sql::select("*","audit_trails",$part_sql,"action_date desc","$eu,$limit","0");	
	   	}else{
	   	 	//return DB_Sql::select("count(id) as tot","audit_trails",$part_sql,"action_date desc","","0");
			$sql_query="Select count(id) as tot from audit_trails ".$Sub_part_sql." ORDER BY action_date desc";
	    }
		//echo $sql_query;
		//print_r($WhereClauseArr);
		return $this->query($sql_query,$WhereClauseArr);
	}
	
	
	//master Internal Documentation
	
	function query_mid($part_sql,$WhereClauseArr,$Request,$eu) //done
	{ 
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
          
		if($from_date!= "" && $to_date!="")
		{
			$WhereClauseArr[]=$from_date;
			$WhereClauseArr[]=$to_date;
				
			$part_sql.= " AND Date(action_date) between ? AND ? ";
		}
		else if($from_date!= "")
		{
			$WhereClauseArr[]=$from_date;
			$part_sql.= " AND Date(action_date) >=? ";
		}
		else if($to_date!= "")
		{
			$WhereClauseArr[]=$to_date;
			$part_sql.= " AND Date(action_date) <=? ";
		}
		else if($_REQUEST['adtFrom']=='CENTRAL_ADT')
		{
			$dt = date('Y-m-d');
			$part_sql .= " and Date(action_date) like '%".$dt."%'";
		}
		/*if($_SESSION['UserLevel'] != "0")
		{
			if($_SESSION['data_view_option_list'] == "")
			{
				$in = "''";
			}
			else
			{
				$in = $_SESSION['data_view_option_list'];
			}
			$part_sql .= " and (client_id = '".$_SESSION['MainClient']."'  or  client_id in(".$in.") )";
		}*/
		if($eu!="")
		{
		    $sql_query="Select * from fd_if_audit_trail WHERE ".$part_sql." ORDER BY action_date desc,id desc LIMIT $eu,100";
	
		}else{
	
			$sql_query="Select count(id) as tot from fd_if_audit_trail WHERE ".$part_sql." ORDER BY action_date desc,id desc";
		}
		//echo  $sql_query;
	  //   print_r($WhereClauseArr);
		return $this->query($sql_query,$WhereClauseArr);
	}
	
	/// for inbox ///
	function query_inbox($part_sql,$WhereClauseArr,$Request,$eu) //done
	{ 
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
          
		if($from_date!= "" && $to_date!="")
		{
			$WhereClauseArr[]=$from_date;
			$WhereClauseArr[]=$to_date;
				
			$part_sql.= " AND Date(DateTime) between ? AND ? ";
		}
		else if($from_date!= "")
		{
			$WhereClauseArr[]=$from_date;
			$part_sql.= " AND Date(DateTime) >=? ";
		}
		else if($to_date!= "")
		{
			$WhereClauseArr[]=$to_date;
			$part_sql.= " AND Date(DateTime) <=? ";
		}
		else if($_REQUEST['adtFrom']=='CENTRAL_ADT')
		{
			$dt = date('Y-m-d');
			$part_sql .= " and Date(DateTime) like '%".$dt."%'";
		}
		
		if($eu!="")
		{
		     $sql_query="select a.receiver as rec_user,a.*,b.*  from fd_cs_notes_receiver as a join fd_cs_notes as b on a.note_id=b.id where ".$part_sql." ORDER BY DateTime desc LIMIT $eu,100";
	
		}else{
	
		  
		    $sql_query="select count(a.id) as tot from fd_cs_notes_receiver as a join fd_cs_notes as b on a.note_id=b.id where ".$part_sql." ORDER BY DateTime desc";
		}
	//	echo  $sql_query;
	   //print_r($WhereClauseArr);
		 
		return $this->query($sql_query,$WhereClauseArr);
	}
	/// end inbox  ///
	
	//Document Group
	function query_cs_doc_group($part_sql,$WhereClauseArr,$Request,$eu) //done
	{ 
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
          
		if(isset($from_date) && $from_date!= "")
		{
			$part_sql.=($part_sql!="")?" AND ":"";
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				
				$part_sql.= " Date(action_date) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				$part_sql.= " Date(action_date)> ? ";
			}
		}
		else if($_REQUEST['adtFrom']=='CENTRAL_ADT')
		{
			$dt = date('Y-m-d');
			$part_sql .= " and Date(action_date) like '%".$dt."%'";
		}
		
		if($part_sql!="")
		{
		   $Sub_part_sql=" WHERE ".$part_sql;
		}
		         
		if($eu!="")
		{
		    $sql_query="Select * from fd_document_groups_audit_trail ".$Sub_part_sql." ORDER BY action_date desc LIMIT $eu,100";
	
		}else{
	
			$sql_query="Select count(id) as tot from fd_document_groups_audit_trail  ".$Sub_part_sql."  ORDER BY action_date desc";
		}
			/*echo $sql_query;
			print_r($WhereClauseArr);*/
		return $this->query($sql_query,$WhereClauseArr);
	}
	
	
	
	function fn_of_getColumnlist($WhereClauseArr,$TabId_str) //done
	{
	   $sql_query="Select fieldname,column_id from tbl_livetab_fields where aircraft_id=? AND tab_id IN (".$TabId_str.") AND type=? ";
	   return $this->query($sql_query,$WhereClauseArr);
	}
	function fn_of_GetColumnName($WhereClauseArr) //done
	{
	   $sql_query="Select fieldname from tbl_livetab_fields where aircraft_id=? AND tab_id IN (?) AND type=? AND column_id=?";
	   return $this->query($sql_query,$WhereClauseArr);
	}
	function getClientName($id)
	{
		$arr=array();
		$arr[]=$id;
		$sql="select group_concat(COMP_NAME) as COMP_NAME from fd_airlines where ID IN($id) and DELFLG=0";
		DB_Sql::query($sql);
		DB_Sql::next_record();
		$str=DB_Sql::f("COMP_NAME");
		return $str;
	}
	function getCompAuditGrid($qry,$where,$eu,$flg=0)
	{
		if($flg)
		{	
			$sql="select * from fd_audit_airfcraft_center where ".$qry." order by action_date DESC limit ".$eu.",100 ";
		}else
		{
			$sql="select id from fd_audit_airfcraft_center where ".$qry." order by action_date DESC ";
		}
		//print_r( $where);
		return DB_Sql::query($sql, $where);
	}
	function get_archive_audit($operation,$section,$WhereClauseArr,$limit='')
	{
		$slimit='';
		if($limit!='')
		{
			$slimit="limit ".$limit.",100";
		}

	 	 $str="select * from archive_audit where 1=1 ".$operation." ".$section."  order by DateTime desc ".$slimit;
		//print_r($WhereClauseArr);
		return DB_Sql::query($str,$WhereClauseArr);
	}
	
	function get_archive_audit_count($operation,$section,$WhereClauseArr)
	{
	 	$str = " select count(id) as cnt from archive_audit where 1=1 ".$operation." ".$section."  ";
		return DB_Sql::query($str,$WhereClauseArr);
	}
	
	function get_bible_audit($operation,$WhereClauseArr,$limit='')
	{
		$slimit='';
		if($limit!='')
		{
			$slimit="limit ?,100";
		}
	 	$str="select b.* ,a.COMP_NAME from bible_audit b LEFT JOIN fd_airlines a ON a.ID = b.client_id where 1=1".$operation." order by DateTime desc ".$slimit;
		return DB_Sql::query($str,$WhereClauseArr);
	}
	function getCompMoveAuditGrid($qry,$where,$eu,$flg=0)
	{
		if($flg)
		{	
			$sql="select * from fd_component_move_audit_trail where ".$qry." order by action_date DESC limit ".$eu.",100 ";
		}else
		{
			$sql="select id from fd_component_move_audit_trail where ".$qry." order by action_date DESC ";
		}
		//print_r( $where);
		return DB_Sql::query($sql, $where);
	}
	
	function query_mcc_others($part_sql,$WhereClauseArr,$Request,$eu) //done
	{ 
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
          
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				
				$part_sql.= " AND Date(action_date) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				$part_sql.= " AND Date(action_date) =? ";
			}
		} 
		else if($_REQUEST['adtFrom']=='CENTRAL_ADT')
		{
			$dt = date('Y-m-d');
			$part_sql .= " and Date(action_date) like '%".$dt."%'";
		}
		 
		if($part_sql!="")
		{
		   $Sub_part_sql=" WHERE ".$part_sql;
		}
		if($Request["audit_type"]=="reviewDocs")
		{
			$Sub_part_sql.=" and mcc_doc_id='".$_REQUEST["mcc_doc_id"]."' ";
		}
		if($Request["audit_type"]=="mccProcess")
		{
			$Sub_part_sql.=" and action in ('PROCESS ADDED','PROCESS EDITED','PROCESS DELETED','PROCESS REORDER') ";
		}
		if($Request["audit_type"]=="mccRange")
		{
			$Sub_part_sql.=" and action in ('RANGE ADDED','RANGE EDITED','RANGE DELETED') ";
		}
		if($Request["audit_type"]=="mccRangeStatus")
		{
			$Sub_part_sql.=" and action in ('RANGE STATUS ADDED','RANGE STATUS EDITED','RANGE STATUS DELETED','RANGE STATUS REORDER') ";
		}
		if($Request["audit_type"]=="mccStatus")
		{
			$Sub_part_sql.=" and action in ('WORK STATUS ADDED','WORK STATUS EDITED','WORK STATUS DELETED','WORK STATUS REORDER') ";
		}
		
		$addStr = '';
		if($_SESSION['user_type']==0){
		    
		    if($_SESSION['UserLevel']!=0){
		        
		        $addStr = ' and client_id in ('.$_SESSION['data_view_option_list'].') ';
		    }
		} 
		if($eu!="")
		{
		    $sql_query="Select * from fd_mcc_audit_trail ".$Sub_part_sql.$addStr." ORDER BY action_date desc LIMIT $eu,100";
		
		}else{
		
			$sql_query="Select count(id) as tot from  fd_mcc_audit_trail ".$Sub_part_sql.$addStr." ORDER BY action_date desc";
		}
      // echo $sql_query;
		//print_r($WhereClauseArr);
		return $this->query($sql_query,$WhereClauseArr);
	}
	
	function query_mcc($part_sql,$WhereClauseArr,$Request,$eu) //done
	{ 
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
          
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				
				$part_sql.= " AND Date(action_date) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				$part_sql.= " AND Date(action_date) =? ";
			}
		} 
		else if($_REQUEST['adtFrom']=='CENTRAL_ADT')
		{
			$dt = date('Y-m-d');
			$part_sql .= " and Date(action_date) like '%".$dt."%'";
		}
		 
		if($part_sql!="")
		{
		   $Sub_part_sql=" WHERE ".$part_sql;
		}
		         
		if($eu!="")
		{
		// echo   $sql_query="Select ca.*,tm.check_name from fd_cs_audit_trail as ca join tbl_maintenance_centre as tm on ca.Workpack_Id=tm.id ".$Sub_part_sql." ORDER BY action_date desc LIMIT $eu,100";
		 
	      	$sql_query="select * from fd_cs_audit_trail  ".$Sub_part_sql." and workpack_id != 0 order by action_date desc LIMIT $eu,100";
	
		}else{
			//$sql_query="Select count(ca.id) as tot from fd_cs_audit_trail as ca join tbl_maintenance_centre as tm on ca.Workpack_Id=tm.id ".$Sub_part_sql." ORDER BY action_date desc";
			//$sql_query="Select count(id) as tot from fd_cs_audit_trail ".$Sub_part_sql." ORDER BY action_date desc";
			$sql_query="select count(id) as tot from fd_cs_audit_trail  ".$Sub_part_sql." and workpack_id != 0";
		}
		//echo $sql_query;
		//print_r($WhereClauseArr);
		return $this->query($sql_query,$WhereClauseArr);
	}
	
	function query_mccManageAssetsCentral($part_sql,$WhereClauseArr,$Request,$eu) //done
	{ 
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
          
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				
				$part_sql.= " AND Date(action_date) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				$part_sql.= " AND Date(action_date) =? ";
			}
		} 
		else if($_REQUEST['adtFrom']=='CENTRAL_ADT')
		{
			$WhereClauseArr[]= "%".date('Y-m-d')."%";
			$part_sql.= " AND action_date like ? ";
		}
		
		if($part_sql!="")
		{
		   $Sub_part_sql=" WHERE ".$part_sql;
		}
		         
		if($eu!="")
		{
		    $sql_query="Select * from fd_mcc_audit_trail ".$Sub_part_sql." ORDER BY action_date desc LIMIT $eu,100";
		
		}else{
		
			$sql_query="Select count(id) as tot from  fd_mcc_audit_trail ".$Sub_part_sql." ORDER BY action_date desc";
		}
		return $this->query($sql_query,$WhereClauseArr);
	}
	
	
	function getMetaFileAuditTrailGrid($part_sql,$WhereClauseArr,$Request,$eu)
	{
		global $db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;
				
				$part_sql.= " AND date(date) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				$part_sql.= " AND date(date) =? ";
			}
		} 
		if($eu!="")
		{
			$sqlStr = "select a.*,u.fname,u.lname,fm.document_name from fd_metafile_audit_trail  as a join fd_users as u on a.add_by = u.id join fd_meta_master as fm on a.m_id=fm.id where a.mf_id=".$Request['fileId']." ".$part_sql." order by a.id desc limit $eu,100";
			

		}
		else
		{
			 $sqlStr = "select count(a.id) as tot from fd_metafile_audit_trail as a join fd_meta_master as fm on a.m_id=fm.id   where a.mf_id=".$Request['fileId']." ".$part_sql." order by a.id desc ";
		}
		return $this->query($sqlStr,$WhereClauseArr);
	}
	
	
	function getClientsForModule($engMod=false,$gearMod=false)
	{
		if($engMod)
		{
			$str = 'SELECT GROUP_CONCAT(ID) as ID from fd_airlines where is_module = 1';
		}
		else if($gearMod)
		{
			$str = 'SELECT GROUP_CONCAT(ID) as ID from fd_airlines where is_gear_module = 1';
		}
		DB_Sql::query($str);
		DB_Sql::next_record();
		$isClientMod = (DB_Sql::f("ID")!='') ? DB_Sql::f("ID") : '0';
		return $isClientMod;
	}
	function getuname($uid)
	{
		// get user detail
		$uar=array();
		$uar['id']=$uid;
		$sql="select  (case when contact_name ='' or contact_name is null then concat(fname,' ',lname) else contact_name end ) as user_name,id,user_type,email from fd_users where id=".$uid." ";
		return DB_Sql::query($sql,$uar);
	}
	function getuser_name($uid)
	{
		// get user detail
		$uar=array();
		$uar['id']=$uid;
		$sql="select  (case when contact_name ='' or contact_name is null then concat(fname,' ',lname) else contact_name end ) as user_name,id,user_type,email from fd_users where id=".$uid." ";
		DB_Sql::query($sql,$uar);
		DB_Sql::next_record();
		return DB_Sql::f("user_name");
	}
	function getresusername($noteid)
	{
		$qry="select  GROUP_CONCAT((case when contact_name ='' or contact_name is null then concat(fname,' ',lname) else contact_name end )) as res_user_name from fd_users where id in(select receiver from fd_cs_notes_receiver as a where a.note_id =".$noteid." and a.responsiblity=1);";
		DB_Sql::query($qry);
		DB_Sql::next_record();
		return DB_Sql::f("res_user_name");

	}
	
	function getClientNameFromComp($aid,$typ)
	{
		if($typ==1)
		{
			$sql="	select b.COMP_NAME from aircraft_tail as a join fd_airlines as b  on  a.CLIENTID=b.ID where a.ID=".$aid."";
		}
		else
		{
			$typearr = array("2"=>"fd_eng_master","3"=>"fd_apu_master","4"=>"fd_landing_gear_master","5"=>"fd_thrust_reverser_master","11"=>"fd_propeller_master");
			$sql=" select b.COMP_NAME from ".$typearr[$typ]." as a join fd_airlines as b  on  a.client=b.ID where a.ID=".$aid."";
		}
		
		DB_Sql::query($sql);
		DB_Sql::next_record();
		return DB_Sql::f("COMP_NAME");
	}
	// function get Api Centre Audit Trail Data.
	function getApiFeedAudit($wh,$WhereClauseArr,$eu)
	{
		global $Request,$db;
		$from_date=($Request['from_date']!="")?$db->GetDate($Request["from_date"]):"";
		$to_date=($Request["to_date"]!="")?$db->GetDate($Request["to_date"]):"";
		
		if(isset($from_date) && $from_date!= "")
		{
			if(isset($to_date) &&  $to_date!="")
			{
				$WhereClauseArr[]=$from_date;
				$WhereClauseArr[]=$to_date;			
				$wh.= " AND Date(`date`) between ? AND ? ";
			}
			else
			{
				$WhereClauseArr[]=$from_date;
				$wh.= " AND Date(`date`) =? ";
			}
		}
		else if($_REQUEST['adtFrom']=='CENTRAL_ADT')
		{
			$dt = date('Y-m-d');
			$wh .= " and Date(date) like '%".$dt."%'";
		}
		
		if($eu!="")
		{
		    $sql_query="Select * FROM fd_masters_audit_trail WHERE sublink_id=? $wh ORDER BY date desc LIMIT $eu,100";
	
		}else{
	
			$sql_query="Select count(id) as tot FROM fd_masters_audit_trail WHERE sublink_id=? $wh ORDER BY date desc";
		}
        /*echo $sql_query;
		print_r($WhereClauseArr);*/
		return $this->query($sql_query,$WhereClauseArr);
	}
	
	// Get Centre Details for Archive Audit Trail...
	function fn_of_getMainSection($WhereClauseArr)
	{
		 $sql = "SELECT * FROM tbl_adminsublinks WHERE linkid=1 and id NOT IN (20, 22, 71, 84, 85, 86, 87, 96,99, 117,212,216,217,218,219,144,220) ORDER BY linkorder ";
		 return DB_Sql::query($sql,$WhereClauseArr);
	}
	
	function getAircraftName($typeid)
	{
		if($_SESSION['UserLevel'] == 0 || $_SESSION['UserLevel'] == 3)
		{
			$where = " arch.TAIL = tail.ID and tail.TYPEID= ".$typeid." and arch.IS_DELETE in(0,2) ";
		}
		else
		{
			$where = " (tail.CLIENTID =".$_SESSION['MainClient']." OR tail.CLIENTID IN(".$_SESSION['data_view_option_list'].")) and arch.TAIL = tail.ID and tail.TYPEID=".$typeid." and arch.IS_DELETE in(0,2) ";
		}
		
		$sqlPart="";
		if($_SESSION['UserLevel']!=0)
		{
			if($_SESSION['aircraft_comps']!='')
			{
				$sqlPart=" AND arch.TAIL IN (".$_SESSION['aircraft_comps'].") ";
			}
			else
			{
				$sqlPart=" AND arch.TAIL IN (0) ";
			}
		}
		
		$query="select tail.* from archive arch,aircraft_tail tail where".$where.$sqlPart." order by tail.TAIL";
		return DB_Sql::query($query);
	}
	function getEngineName($etypeid)
	{
		if($_SESSION['UserLevel'] == 0)
		{
			$wh1 = " engine_type=".$etypeid." and delete_flag=0 ";
		}
		else
		{
			$wh1 = " (client ='".$_SESSION['MainClient']."' OR client IN(".$_SESSION['data_view_option_list'].")) and   engine_type=".$etypeid." and delete_flag=0 ";
		}
		
		$sqlPart="";
		if($_SESSION['UserLevel']!=0)
		{
			if($_SESSION['engine_comps']!='')
			{
				$sqlPart=" AND id IN (".$_SESSION['engine_comps'].") ";
			}
			else
			{
				$sqlPart=" AND id IN (0) ";
			}
		}
		
		$query="select * from fd_eng_master where ".$wh1.$sqlPart." order by serial_number";
		return DB_Sql::query($query);
	}
	function getGearName($gtypeid)
	{
		if($_SESSION['UserLevel'] == 0)
		{
			$wh2 = " landing_gear_type=".$gtypeid." and delete_flag=0 ";
		}
		else
		{
			$wh2 = " (client ='".$_SESSION['MainClient']."' OR client IN(".$_SESSION['data_view_option_list'].")) and   landing_gear_type=".$gtypeid." and delete_flag=0 ";
		}
		
		$sqlPart="";
		if($_SESSION['UserLevel']!=0)
		{
			if($_SESSION['gear_comps']!='')
			{
				$sqlPart=" AND id IN (".$_SESSION['gear_comps'].") ";
			}
			else
			{
				$sqlPart=" AND id IN (0) ";
			}
		}
		
		$query="select * from fd_landing_gear_master where ".$wh2.$sqlPart." order by serial_number";
		return DB_Sql::query($query);
	}
	function getApuName($atypeid)
	{
		if($_SESSION['UserLevel'] == 0)
		{			
			$wh3 = " apu_type=".$atypeid." and delete_flag=0 ";
		}
		else
		{
			$wh3 = " (client ='".$_SESSION['MainClient']."' OR client IN(".$_SESSION['data_view_option_list'].")) and  apu_type=".$atypeid." and delete_flag=0 ";
		}
		
		$sqlPart="";
		if($_SESSION['UserLevel']!=0)
		{
			if($_SESSION['apu_comps']!='')
			{
				$sqlPart=" AND id IN (".$_SESSION['apu_comps'].") ";
			}
			else
			{
				$sqlPart=" AND id IN (0) ";
			}
		}
		
		$query="select * from fd_apu_master where ".$wh3.$sqlPart." order by serial_number";
		return DB_Sql::query($query);
	}
	function getThrustName($ttypeid)
	{
		if($_SESSION['parentId'] == 0 || $_SESSION['UserLevel'] == 3)
		{		
			$wh4 = " thrust_reverser_type=".$ttypeid." and delete_flag=0 ";
		}
		else
		{
			$wh4 = " (client ='".$_SESSION['MainClient']."' OR client IN(".$_SESSION['data_view_option_list'].")) and  thrust_reverser_type=".$ttypeid." and delete_flag=0 ";
		}
		//*","fd_thrust_reverser_master",$wh4,"serial_number
		
		$sqlPart="";
		if($_SESSION['UserLevel']!=0)
		{
			if($_SESSION['thrust_comps']!='')
			{
				$sqlPart=" AND id IN (".$_SESSION['thrust_comps'].") ";
			}
			else
			{
				$sqlPart=" AND id IN (0) ";
			}
		}
		
		$query="select * from fd_thrust_reverser_master where ".$wh4.$sqlPart." order by serial_number";
		return DB_Sql::query($query);
	}
	
	// To Get Title (and if Sub Title) for particular Sublink...
	function GetTitle($subId)
	{
		$sql = "SELECT sub.title AS subtitle,parent.title AS ptitle FROM fd_acs_sublinks AS sub JOIN fd_acs_tabmaster AS";
		$sql .= " parent ON sub.parent_id = parent.id WHERE sub.id=?";
		$w = array('sub.id'=>$subId);
		return DB_Sql::query($sql,$w);
	}
	
	// To get audit trails of Home >> Settings tab
	function getSettingsAudit($sublinkId,$wh,$start=0)
	{
		$sqlStr = "select a.*,u.fname,u.lname from fd_settings_audit  as a join fd_users as u on a.userId = u.id where sectionFlag='STTNG' $wh order by a.id desc limit $start,100";
		return DB_Sql::query($sqlStr);
	}
	
	function getSettingsAuditCount($sublinkId,$wh)
	{
		$sqlStr = "select count(*) as count from fd_settings_audit  as a join fd_users as u on a.userId = u.id where sectionFlag='STTNG' $wh order by a.id desc";
		DB_Sql::query($sqlStr);
		DB_Sql::next_record();
		return DB_Sql::f('count');
	}
}

//Function Use to get List(Array) Of AllUser.

function Allusers() //done
{
	global $db;
	$sql_query="SELECT case when fname='' AND lname='' then contact_name else concat(fname,' ',lname) end as fullname,id as userId from fd_users";
	$arrUser=array();
	$db->query($sql_query);
	while($db->next_record())
	{
		$arrUser[$db->f("userId")] = $db->f("fullname");
	}
	return  $arrUser;
}
//Function Use for get List(Array) of Row Work Status Array.

function getRowStatusArray() //done
{
	global $mdb;
	$arrRowStatus = array(); 
	$mdb->select("id,name,bg_color,font_color,status","tbl_livetab_work_status","","name","","0");
	 while($mdb->next_record())
	{
		$arrRowStatus[$mdb->f('name')]= $mdb->f('name').",".$mdb->f('bg_color').",".$mdb->f('font_color');
	}
	return $arrRowStatus;
}

//Function Use for get List(Array) of Row Work Status list For Specially UltraMain Tab.

function getRowStatusUltraTabArray() //done
{
	global $mdb;
	$arrRowStatus = array(); 
	$mdb->select("id,name,bg_color,font_color,status","fd_ultramain_work_status","","name","","0");
	 while($mdb->next_record())
	{
		$arrRowStatus[$mdb->f('name')]= $mdb->f('name').",".$mdb->f('bg_color').",".$mdb->f('font_color');
	}
	return $arrRowStatus;
}

//Function Use for get Array Of Row Data of Component(engine/Apu/Landing Gear/Thrust Reverser).

function RecComponentArray($sectionflg) //done
{
	global $mdb;
	if($sectionflg=="EFS" || $sectionflg=="EOW")
	{
		$mdb->select("GROUP_CONCAT(CONCAT('\"',serial_number,'+',part_number,'+',id,'\"')) as fieldvalue","fd_eng_master","","","","0");
	    $mdb->next_record();
		
	}else if($sectionflg=="LGFS" || $sectionflg=="LGOG")
	{
		$mdb->select("GROUP_CONCAT(CONCAT('\"',serial_number,'+',part_number,'+',id,'\"')) as fieldvalue","fd_landing_gear_master","","","","0");
	    $mdb->next_record();
		
	}else if($sectionflg=="AFS" || $sectionflg=="ANI")
	{
		$mdb->select("GROUP_CONCAT(CONCAT('\"',serial_number,'+',part_number,'+',id,'\"')) as fieldvalue","fd_apu_master","","","","0");
	    $mdb->next_record();
	
	}else if($sectionflg=="TRFS" || $sectionflg=="TRNI")
	{
		$mdb->select("GROUP_CONCAT(CONCAT('\"',serial_number,'+',part_number,'+',id,'\"')) as fieldvalue","fd_thrust_reverser_master","","","","0");
	    $mdb->next_record();
	}
	else if($sectionflg=="PFS" || $sectionflg=="PWW")
	{
	    $mdb->select("GROUP_CONCAT(CONCAT('\"',serial_number,'+',part_number,'+',id,'\"')) as fieldvalue","fd_propeller_master","","","","0");
	    $mdb->next_record();
	}
	
	$RecCompstr=explode(",",$mdb->f("fieldvalue"));
	return $RecCompstr;
}

/*---------------------common function -------------------------------*/

//Function Use for get List of Aircraft by given Airline.

function get_Aircraft($airlinesId,$selectitm) //done
{
	global $db,$mdb;
	$mdb = clone $db;
	
	$WhereClauseArr['airlinesId']=$airlinesId;
	$Str = '';
	$Str.='<option value="0">Select Aircraft</option>';
	$db->select("*","aircrafttype",$WhereClauseArr,"ICAO");
	
	while($db->next_record())
	{
		$Str.= '<option value="'.$db->f("ID").'" style="color:#FF0000" disabled="disabled">'.$db->f("ICAO").'</option>';
		
		$WhereClauseArr1['tail.TYPEID']=$db->f('ID');
		if($_SESSION['UserLevel'] == 0)
		{
			$where = " arch.TAIL = tail.ID and tail.TYPEID=? and arch.IS_DELETE in(0,2) ";
		}
		else
		{
			if($_SESSION['aircraft_comps']!="")
			{
			  $wherePart=" and arch.TAIL IN (".$_SESSION['aircraft_comps'].")";
			}else
			{
			   $wherePart=" and arch.TAIL=0 ";
			}
			$where = " (tail.CLIENTID ='".GETCLIENTID."' OR tail.CLIENTID IN(".CLIENT_OPT_LIST.")) and arch.TAIL = tail.ID and tail.TYPEID=? and arch.IS_DELETE in(0,2) ".$wherePart;
		}
		//$mdb->select("tail.*","archive arch,aircraft_tail tail",$WhereClauseArr1,"tail.TAIL");
		
		$sql_query="SELECT tail.* FROM archive arch,aircraft_tail tail  WHERE ".$where." ORDER BY tail.TAIL";
		$mdb->query($sql_query,$WhereClauseArr1);
		
		$res=array();
		while($mdb->next_record())
		{
			if($selectitm==$mdb->f("TAIL").'|'.$mdb->f("ID"))
			{
				$Str.= '<option value="'.$mdb->f("ID").'" selected="selected">&nbsp;&nbsp;&nbsp;'.$mdb->f("TAIL").'</option>';
			}else{
				$Str.= '<option value="'.$mdb->f("ID").'" >&nbsp;&nbsp;&nbsp;'.$mdb->f("TAIL").'</option>';
			}	
		}		
	}
	return $Str;
}

//Function Use for get List of Aircraft by given Airline.

function get_Aircraft_new($airlinesId,$selectitm) //done
{
	global $db,$mdb,$ndb;
	
	$WhereClauseArr['airlinesId']=$airlinesId;
	$Str = '';
	$Str.='<option value="0">Select Aircraft</option>';
	
	$db->select("*","aircrafttype",$WhereClauseArr,"ICAO");
	while($db->next_record())
	{
		$Str.= '<option value="'.$db->f("ID").'" style="color:#FF0000" disabled="disabled">'.$db->f("ICAO").'</option>';
		$WhereClauseArr1[0]=$db->f('ID');
		if($_SESSION['UserLevel'] == 0)
		{
			$where = " arch.TAIL = tail.ID and tail.TYPEID=? and arch.IS_DELETE in(0,2) ";
		}
		else
		{
			if($_SESSION['aircraft_comps']!="")
			{
			  $wherePart=" and arch.TAIL IN (".$_SESSION['aircraft_comps'].")";
			}else
			{
			   $wherePart=" and arch.TAIL=0 ";
			}
			
			$where = " (tail.CLIENTID ='".GETCLIENTID."' OR tail.CLIENTID IN(".CLIENT_OPT_LIST.")) and arch.TAIL = tail.ID and tail.TYPEID=? and arch.IS_DELETE in(0,2) ".$wherePart;
		}
		//$mdb->select("tail.*","archive arch,aircraft_tail tail",$where,"tail.TAIL");
		$Qry_sql="SELECT tail.* FROM archive arch,aircraft_tail tail WHERE ".$where." ORDER BY tail.TAIL";
		$mdb->query($Qry_sql,$WhereClauseArr1);
		$res=array();
		while($mdb->next_record())
		{
			$ndb->getCompList($mdb->f("ID"));
			$ndb->next_record();
			$complist=$ndb->f('complist');
			if($selectitm==$complist."|".$mdb->f("ID"))
			{
				$Str.= '<option value="'.$mdb->f("ID").'" selected="selected">&nbsp;&nbsp;&nbsp;'.$mdb->f("TAIL").'</option>';
			}else{
				$Str.= '<option value="'.$mdb->f("ID").'" >&nbsp;&nbsp;&nbsp;'.$mdb->f("TAIL").'</option>';
			}	
		}		
	}
	return $Str;
}

//Function Use for Get List of MainTab Combo-box.

function getAircraftTab($SpecificId) //done
{
	global $db;
	$gdb=clone $db;
	$Str = '';
	$SqlStr='';
	$splitTab = explode('|',$SpecificId);
	if(!empty($splitTab))
	{   $wha[0]=end($splitTab);
		$sql_qry="Select count(ID) as num_db_status from  archive where tail=? and db_status!=0";
		$gdb->query($sql_qry,$wha);
		$gdb->next_record();
		$num_db_status=$gdb->f('num_db_status');
		$Str.='<option value="0" selected="selected">Select Tab</option>';
		
		$client_Id = GetClientIDFromComponentID("1",end($splitTab));
		
		if($_SESSION['UserLevel']==0)
		{
			$sql="Select * from tbl_currentstatus_links where pid=0 and id != '50' order by linkorder";
		}
		else
		{
			$sql ="Select link.* from tbl_currentstatus_links as link JOIN ";
			$sql .="(select * from tbl_currentstatus_priv where  gid='".$_SESSION['GroupId']."' group by link_id ) ";
			$sql .="as subpriv ON subpriv.link_id=link.id  and link.pid=0 and link.id != '50'  ORDER BY linkorder";
		}
		
		$db->query($sql);
		while($db->next_record())
		{
			if($num_db_status<1 and $db->f("linkname")=="Delivery Bible")
			{
			}else{
				if($db->f("id")==1)
				{
					$STR1=$db->f("tab_id").'|'.$db->f("id").'|'.$_SESSION['BIBLE_TEMPLATE_NEW'][$client_Id];
					$TbName=$_SESSION['BIBLE_TEMPLATE_NEW'][$client_Id];
				}else{
				    $STR1=$db->f("tab_id").'|'.$db->f("id").'|'.$db->f("linkname");
					$TbName=$db->f("linkname");
				}
				$Str.= '<option value="'.$STR1.'">'.$TbName.'</option>';
			}
		}
	}else{
	    $str="error";
	}
	return $Str;
}

//Function Use for Get List of SubTab Combo-box.

function getAircraftsubTab($SpecificId) //done
{
	global $db,$mdb,$kdb,$cdb;
	$Str = '';
	$i=0;				
	$idExplode = explode('|',$SpecificId);
	$aid = end($idExplode);
	$pid = $idExplode[1];
	$linkName = $idExplode[2];
	
	$pos=array("2"=>array(1=>25,2=>26,3=>27,4=>28), "3"=>array(1=>81,2=>82,3=>83,4=>84));
	
	$Str.='<option value="0" selected="selected">Select Sub Tab</option>';
	
	$WhereClauseArr[]=$pid;
	if($pid != 1)
	{
		if ($_SESSION['UserLevel']==0)
		{
			$sql = "Select * from tbl_currentstatus_links where pid = ? and id != '56'  order by linkorder ";
		}
		else
		{
			$sql = "Select link.* from tbl_currentstatus_links as link JOIN ";
			$sql .= " (select * from tbl_currentstatus_priv where gid='".$_SESSION['GroupId']."' group by link_id ) ";
			$sql .= " as subpriv ON subpriv.link_id=link.id and link.id != '56' and link.pid=? ORDER BY linkorder";
		}
	
		$mdb->query($sql,$WhereClauseArr);

		if($mdb->num_rows()>0)
		{
			$subflg=0;

			while($mdb->next_record())
			{
				if($mdb->f('Type')==3)
				{
					$WhereClauseArr1['currently_on']=$aid;
					$cdb->select("*","fd_apu_master",$WhereClauseArr1);
					if($cdb->num_rows()>0)
					{
						$cdb->next_record();
						$link_id=$cdb->f('id');
						$tab_id=1;
						$type=3;
						$tab_name=$cdb->f('serial_number');
					}
					else
					{
						$link_id=0;
						$tab_id=1;
						$type=3;
						$tab_name=$mdb->f('linkname');
					}
				}
				else if($mdb->f('Type')==2)
				{
					$type=2;
					//$position=array_search($mdb->f('tab_id'),$pos[$pid]);
					$position=$mdb->f('Position');
					$WhereClauseArr1['currently_on']=$aid;
					$WhereClauseArr1['aircraft_position']=$position;
					$cdb->select("*","fd_eng_master",$WhereClauseArr1);
					if($cdb->num_rows()>0)
					{
						$cdb->next_record();
						$link_id=$cdb->f('id');
						$tab_name=$cdb->f('serial_number');
					}
					else
					{
						$link_id=0;
						$tab_name=$mdb->f('linkname');
					}
					if($pid==2)
					{
						$tab_id=1;
						
					}
					else if($pid==3)
					{
						$tab_id=2;
					}
				}	
				else if($mdb->f('Type')==1)
				{
					$link_id=$aid;
					$type=1;
					$tab_id=$mdb->f('tab_id');
					$tab_name=$mdb->f('linkname');
				}
			/*	if($link_id!=0)
				{*/
					$Str.= '<option value="'.$link_id.'|'.$tab_id.'|'.$type.'|'.$mdb->f('id').'">'.$tab_name.'</option>';
				//}
			}
		}
	}
	else if($pid == 1)
	{
		/*$WhereClauseArr2['tab_id']=12;
		$mdb->select("*","tbl_subtab_master",$WhereClauseArr2);
		if($mdb->num_rows()>0)
		{
			$subflg=0;

			while($mdb->next_record())
			{
				$type = 1;
				$link_id=$aid;
				$pid = 1;
				if($link_id!=0)
				{
					$Str.= '<option value="'.$link_id.'|'.$mdb->f('sub_id').'|'.$type.'|'.$pid.'">'.$mdb->f("subtab_title").'</option>';
				}
			}
		}*/
		
		$type = 1;
		$link_id=$aid;
		$pid = 1;
		
		$DBArray=getDB_Tab($link_id,$type,'','');
		
		foreach($DBArray as $key=>$dbValue)
		{
		     $subtabN=$key;
			 $str=$link_id.'|'.$key.'|'.$type.'|'.$pid;
			 if($selectitm==$str)
			 {
				 $Str.= '<option value="'.$link_id.'|'.$key.'|'.$type.'|'.$pid.'" selected="selected">'.$dbValue.'</option>';
			 }else{
				$Str.= '<option value="'.$link_id.'|'.$key.'|'.$type.'|'.$pid.'">'.$dbValue.'</option>';
			 }
		  
		}
	}
	
	return $Str;
}

//Function Use for Get List of Child-SubTab Combo-box mainly for Engine LLP and Gear LLP.
function getAircraftChildTab($SpecificId) //done
{
	global $db,$mdb,$kdb,$cdb;
	$Str = '';
	$i=0;
	
	$idExplode = explode('|',$SpecificId);

	$aid = end($idExplode);
	$pid = $idExplode[3];
	$linkName = $idExplode[4];
	
	$pos = array("2"=>array(1=>25,2=>26,3=>27,4=>28), "3"=>array(1=>81,2=>82,3=>83,4=>84));
	
	$WhereClauseArr[]=$pid;
	$Str.='<option value="0" selected="selected">Select Sub Tab</option>';
	
	if ($_SESSION['UserLevel']==0)
	{
		$sql='Select * from tbl_currentstatus_links where pid=?  order by linkorder ';	
	}
	else
	{
		$sql ="Select link.* from tbl_currentstatus_links as link JOIN ";
		$sql .="(select * from tbl_currentstatus_priv where  gid='".$_SESSION['GroupId']."' group by link_id ) ";
		$sql .="as subpriv ON subpriv.link_id=link.id  and link.pid=? ORDER BY linkorder";
	}
	$kdb->query($sql,$WhereClauseArr);
	if($kdb->num_rows()>0)
	{	
			while($kdb->next_record())
			{	
				$link_id=0;
				$tab_name='';
				
				if($kdb->f('Type')==2)
				{ 
					//$EngName=str_replace(strtolower('Engine '),'',strtolower($kdb->f('linkname')));
					$EngName=$kdb->f('Position');
					$WhereClauseArr1['currently_on']=$aid;
					$WhereClauseArr1['aircraft_position']=$EngName;
					
					$cdb->select("*","fd_eng_master",$WhereClauseArr1);
					if($cdb->num_rows()>0)
					{
						$cdb->next_record();
						$link_id=$cdb->f('id');
						$tab_id=4;
						$tab_name=$cdb->f('serial_number');
					}
					else
					{
						$tab_name=$kdb->f('linkname');
						$link_id=0;
						$tab_id=4;
					}
				}
				else if($kdb->f('Type')==4)
				{
					$WhereClauseArr1['currently_on']=$aid;
					$WhereClauseArr1['aircraft_position']=$kdb->f('Position');
					
					$cdb->select("*","fd_landing_gear_master",$WhereClauseArr1);				
					if($cdb->num_rows()>0)
					{
						$cdb->next_record();
						$link_id=$cdb->f('id');
						$tab_id=1;
						$tab_name=$cdb->f('serial_number');
					}
					else
					{
						$tab_name=$kdb->f('linkname');
						$link_id=0;
						$tab_id=1;
					}
				}
					if($link_id!=0)
					{
						$Str.= '<option value="'.$link_id.'|'.$tab_id.'|'.$kdb->f('Type').'|'.$kdb->f('id').'">'.$tab_name.'</option>';
					}
					
				}
		}
	else
	{
		$Str= "";
	}
	return $Str;
}

function Pagging($intCurrent, $intLimit=100, $intTotal)
{
	$intPage = $intCurrent / $intLimit;
	$intLastPage = floor((float)$intTotal /(float) $intLimit);
	
	$strResult = '<div id="pagger" style="float:right;">';
	
	if($intCurrent != 0) // Not First Page
	{
		$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.($intCurrent - $intLimit).'\')">'.
					  '<span class="previous_next">&laquo; Previous</span></a>';
	}
	else
	{
		$strResult .= '<a href="#" class="disabled"><span class="previous_next">&laquo; Previous</span></a>';
	}
	
	if($intLastPage < 12) // Total pages less then 13
	{
		for($i=0;$i<$intTotal;$i+=$intLimit)
		{
			if(($i/$intLimit) == $intPage)
			{
				$strResult .= '<a href="#" class="selected">'.(($i/$intLimit)+1).'</a>';
			}
			else
			{
				$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
			}
		}
	}
	else if($intPage < 12) // Current Page From First 6 Pages
	{
		for($i=0;$i<=(($intPage+2)*$intLimit);$i+=$intLimit)
		{
			if(($i/$intLimit) == $intPage)
			{
				$strResult .= '<a href="#" class="selected">'.(($i/$intLimit)+1).'</a>';
			}
			else
			{
				$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
			}
		}
		$strResult .= '<input id="searchtext1" type="text"   onkeypress="return searchpage(this.value,'.$intLimit.','.$intLastPage.',event.keyCode);" />';
		for($i=(($intLastPage*$intLimit) - $intLimit);$i<=$intTotal;$i+=$intLimit)
		{
			$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
		}
	}
	else if($intPage > ($intLastPage - 11)) // Current Page From Last 12 Pages
	{
		for($i=0;$i<=(2*$intLimit);$i+=$intLimit)
		{
			if(($i/$intLimit) == $intPage)
			{
				$strResult .= '<a href="#" class="selected">'.(($i/$intLimit)+1).'</a>';
			}
			else
			{
				$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
			}
		}
		$strResult .= '<input id="searchtext1" type="text"  onkeypress="return searchpage(this.value,'.$intLimit.','.$intLastPage.',event.keyCode);" />';
		for($i=(($intPage-2)*$intLimit);$i<=$intTotal;$i+=$intLimit)
		{
			if(($i/$intLimit) == $intPage)
			{
				$strResult .= '<a href="#" class="selected">'.(($i/$intLimit)+1).'</a>';
			}
			else
			{
				$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
			}
		}
	}
	else if($intPage <= ($intLastPage - 11)) // Current Page Between First 12 Pages and Last 12 Pages
	{
		for($i=0;$i<(2*$intLimit);$i+=$intLimit)
		{
			$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
		}
		$strResult .= '<input id="searchtext1" type="text"  onkeypress="return searchpage(this.value,'.$intLimit.','.$intLastPage.',event.keyCode);" />';
		for($i=(($intPage-2)*$intLimit);$i<=(($intPage+2)*$intLimit);$i+=$intLimit)
		{
			if(($i/$intLimit) == $intPage)
			{
				$strResult .= '<a href="#" class="selected">'.(($i/$intLimit)+1).'</a>';
			}
			else
			{
				$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
			}
		}
		$strResult .= '<input id="searchtext2" type="text" onkeypress="return searchpage(this.value,'.$intLimit.','.$intLastPage.',event.keyCode);" />';
		for($i=(($intLastPage*$intLimit) - $intLimit);$i<$intTotal;$i+=$intLimit)
		{
			$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.$i.'\')">'.
					  (($i/$intLimit)+1).'</a>';
		}
	}
		
	if(($intCurrent+$intLimit) < $intTotal) // Not First Page
	{
		$strResult .= '<a href="javascript:void(0);" onclick="paging(\''.($intCurrent + $intLimit).'\')">'.
					  '<span class="previous_next">Next &raquo;</span></a>';
	}
	else
	{
		$strResult .= '<a href="#" class="disabled"><span class="previous_next">Next &raquo;</span></a>';
	}
	
	$strResult .= '</div>';
	return $strResult;
}

function Check_DataType($ArrVar)
{
	 $TampArr=array();  
	 foreach($ArrVar as $avk=>$av)
	 {
		if(!is_numeric($av) && !is_null($av))
		{
		  $TampArr[$avk]=$av; 
		}
	 }
 return $TampArr;
}

function get_Exception_Message($RequestArr,$DataType,$Section)
{
   ExceptionMsg("URL:".serialize($RequestArr)."<br />Error Description:InValide DataType may be ".$DataType.".",$Section);
   ?>
   <script type="text/javascript">alert('<?php echo ERROR_FETCH_MESSAGE; ?>');window.close();</script>
  <?php
}

function GetColName($tailId,$tabId,$type,$colName)
{
	global $db;
	$ydb=clone $db;
	
	$WhereClauseArr['aircraft_id']=$tailId;
	$WhereClauseArr['tab_id']=$tabId;
	$WhereClauseArr['type']=$type;
	$WhereClauseArr['column_id']=$colName;
	
	$ydb->fn_of_GetColumnName($WhereClauseArr);
	$ydb->next_record();
	$columnName= ($ydb->f('fieldname')!="")?$ydb->f('fieldname'):"No Column Exist.(".$colName.")";	
	return $columnName;
}

function getMCCWorkStatus() //done
{
	global $mdb;
	$arrRowStatus = array(); 
	$mdb->select("id,name,bg_color,font_color,status","tbl_maintenance_work_status","","name","","0");
	 while($mdb->next_record())
	{
		$arrRowStatus[$mdb->f('name')]= $mdb->f('name').",".$mdb->f('bg_color').",".$mdb->f('font_color');
	}
	return $arrRowStatus;
}

function getMCCDocTypeStatus($name)
{
	global $mdb;
	$DocTypeStatus=array();
	$mdb->select("id,TagName","fd_mcc_doctype","","","","0");
	while($mdb->next_record())
	{
		$DocTypeStatusArr[$mdb->f('id')]=$mdb->f('TagName');
	}
	return  $DocTypeStatusArr;
}

function getInternalHeader($tab_id)
{
	global $db;
	$db->select("*","fd_if_master","id = '".$tab_id."'");
	$db->next_record();
	if($db->f('sub_id') == 0)
	{
		$sec_name = ucfirst($db->f('section_name'));
	}
	else
	{
		$sub_sec_name = $db->f('section_name');
		$sub_id = $db->f('sub_id');
		
		$db->select("*","fd_if_master","id = '".$sub_id."'");
		$db->next_record();
		$name = $db->f('section_name');
		$sec_name = '';
		$sec_name = ucfirst($name)."&nbsp;&raquo;&nbsp;".ucfirst($sub_sec_name);
	}
	$dept_id = $db->f('dept_id');
	
	$db->select("*","department","id = '".$dept_id."'");
	$db->next_record();
	$dept_name = $db->f('dept');  
	$area_id = $db->f('areaid');
	
	$db->select("*","fd_user_area","id = '".$area_id."'");
	$db->next_record();
	$area_name = $db->f('area');
	
	return ucfirst($area_name)."&nbsp;&raquo;&nbsp;".ucfirst($dept_name)."&nbsp;&raquo;&nbsp;".ucfirst($sec_name);
}

function getCsHeader($tab_id,$arr=NULL)
{
	
	global $db;
	$db->select("*","tbl_currentstatus_links",array("id"=>$tab_id));
	$db->next_record();
	if($db->f('pid') == 0)
	{

		$arr[] = ucfirst($db->f('linkname'));
	
		return implode("&nbsp;&raquo;&nbsp;",array_reverse($arr));
	}
	else
	{

		$arr[] = ucfirst($db->f('linkname'));
		
		return getCsHeader($db->f('pid'),$arr);
	}
}

function getsublinkHeader($tab_id,$arr=NULL,$is_parent=0)
{
	global $db;
	if($is_parent==1)
	{
		$db->select("*","fd_acs_tabmaster",array("id"=>$tab_id));
		$db->next_record();
		$arr[] = ucfirst($db->f('title'));
		return implode("&nbsp;&raquo;&nbsp;",array_reverse($arr));
	}
	else
	{
		$db->select("*","fd_acs_sublinks",array("id"=>$tab_id));
		$db->next_record();
		
		if($db->f('is_sub') == 0)
		{
			$arr[] = ucfirst($db->f('title'));
			return getsublinkHeader($db->f('parent_id'),$arr,1);
			
		}
		else
		{
	
			$arr[] = ucfirst($db->f('title'));
			return getsublinkHeader($db->f('parent_id'),$arr);
		}
		
	}
}

function get_cs_tab_name($type,$tab_id)
{
	$arrayTabName=array();

	$arrayTabName[2][100]="Engine Delivery Bible";
	$arrayTabName[2][1]="Engine ADs";
	$arrayTabName[2][2]="Engine SBs";
	$arrayTabName[2][3]="Engine LLPs";
	
	$arrayTabName[3][1]="APU LLPs";

	$arrayTabName[4][1]="Gear LLPs";
	
	$arrayTabName[5][1]="Thurst Reverser LLPs";
	
	if($type==1 || $type==11)
	{	
		 return getCsHeader($tab_id);
	}
	if($type==6)
	{
		return getInternalHeader($tab_id);
	}
	if($type==9)
	{
		return getsublinkHeader($tab_id);
	}
	return $arrayTabName[$type][$tab_id];
}

     function GetFolderNameFromId($FolderId)
	{
		global $_CONFIG_SEARCHER,$db;
		$sql ="SELECT ".$_CONFIG_SEARCHER["DATABASE"].".GetSubFolderNamesFromID(".$FolderId.") as folderpath ";
		$db->query($sql);
		$db->next_record();
		return $db->f('folderpath');
	}
	
	function get_ApiFeeds_AuditTrail_Header($id)
	{
		global $gdb;
		$gdb->select("feed_name","fd_api_feeds",array("id"=>$id));
		$gdb->next_record();
		return $gdb->f('feed_name');
	}
	
	function getTabIdsFromClientID($airlineId)		//	Function for Central Audit Trail when we filter with MID.
	{
		global $db,$gdb,$mdb;
		$arrIds = array();
		
		if($_REQUEST['MIFTabID'])
		{
			$strQuery = "select * from fd_if_master WHERE id =".$_REQUEST['MIFTabID']."";
		}
		else if($_REQUEST['DeptTabId'])
		{
			$strQuery = "select * from fd_if_master WHERE dept_id = ".$_REQUEST['DeptTabId']."";
		}
		else if($_REQUEST['AreaTabId'])
		{
			$strQuery = "select * from fd_if_master WHERE dept_id in (select id from department where areaid = ".$_REQUEST['AreaTabId'].")";
		}
		else 
		{
			$strQuery = 'select * from fd_if_master WHERE dept_id in (select id from department where areaid in (select id from fd_user_area where client='.$airlineId.'))';
		}
		
		$gdb->query($strQuery);
		while($gdb->next_record())
		{
			$Ids .= $comma.$gdb->f("id");
			$comma = ',';
		}
		return $Ids;
	}
?>