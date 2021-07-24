<?php
class GearModuleType extends DB_Sql
{
	public $TableName = "";
	
	// to get Grid in XML.
	function getGrid()
	{
		
		$wh = "and 1=1 ";
		if($_SESSION['UserLevel'] == "0")
		{
			$wh .= "";
		}
		else
		{
			if($_SESSION['data_view_option_list'] == "")
			{
				$in = "''";
			}
			else
			{
				$in = 	$_SESSION['data_view_option_list'];
			}
			$wh .= " and ( airlinesId = '".$_SESSION['MainClient']."'  or  airlinesId in(".$in.") and is_gear_module=1 )";
		}
		$sql = "select engmdl.*,al.COMP_NAME as airlines from fd_gear_module_type as engmdl join fd_airlines as al on engmdl.airlinesId = al.ID  where al.DELFLG = 0 and al.is_gear_module=1 ".$wh." order by al.COMP_NAME,engmdl.gearModuleType";
		return DB_Sql::query($sql);
	}
	
	//Function to count rows.
	function fungetRowCount( $TableName,$str,$where) {
	
		$this->query("SELECT count(*) as tot_rows FROM ". $TableName. " where 1=1 AND ". $str,$where);
		$this->next_record(); 
		return $this->f("tot_rows");
	}
	
	//Get Gear Type By Id.
	function getGearTypeById($Ids)
	{
		$idArr = explode(',',$Ids);
		$quest = '';
		for($j=0;$j<count($idArr);$j++)
		{
			$whArr[$j] = $idArr[$j];
			$quest .= $coma.'?';
			$coma = ',';
		}

		$str = "Select GROUP_CONCAT(GEARTYPE) as GEARTYPE from landinggear where ID IN(".$quest.") order by GEARTYPE ASC";
		DB_Sql::query($str,$whArr);
		DB_Sql::next_record();
		return DB_Sql::f("GEARTYPE");
	}
	
	//Function used in update_mul in .function file
	function updateMultiple($array_engval,$totalid_auditArr)
	{	
		$quest = '';
		for($j=0;$j<count($totalid_auditArr);$j++)
		{
			$array_engval[$j] = $totalid_auditArr[$j];
			$quest .= $coma.'?';
			$coma = ',';
		}

		if($quest!='')
		{
			$strQry = "update fd_gear_module_type set gearModuleType=?, manufacturer=?  where ID IN(".$quest.") ";
			return DB_Sql::query($strQry,$array_engval);
		}
		else
			return true;
	}
	
	function getCountModuleType($arr)
	{
		$sel = "select id from fd_gear_module_type where airlinesId=? and gearModuleType=? and id<>?";
		return DB_Sql::query($sel,$arr);
	}
	
	//NEW FUNCTIONS FOR NEW CHANGES:
	
	function getAirlinesFromAirtypeId($typeArr)
	{
		$quest = '';
		for($j=0;$j<count($typeArr);$j++)
		{
			$whArr[$j] = $typeArr[$j];
			$quest .= $coma.'?';
			$coma = ',';
		}
		
		$strQry = 'select DISTINCT(airlinesId) as airId from landinggear where ID IN('.$quest.')';
		return DB_Sql::query($strQry,$whArr);
	}
	
	
	
	function getAirlinesIdsDistinct($grp_icao,$whArr)
	{
		if($_SESSION['UserLevel']!= "0")
		{
				$in = 	$_SESSION['data_view_option_list'];
				$wh .= " and (airlinesId = ".$_SESSION['MainClient']."  or  airlinesId in(".$in.")) ";
		}
		
		if($grp_icao!='')
		{
			$strWhere = $wh." and fa.ID!=? ";
		}
		else
		{
			$strWhere =" and fa.ID =?";
		}
		
		$strQry ="select at.*,fa.ID as faid,fa.COMP_NAME from landinggear as at left join fd_airlines as fa ON at.airlinesId=fa.ID where fa.DELFLG=0 and fa.is_gear_module=1 ".$strWhere." order by fa.COMP_NAME,at.GEARTYPE ";
		return DB_Sql::query($strQry,$whArr);
	}
	
	
	function getExcludeIds($whId='',$thId)
	{
		$whId= ($whId=='')? 0 : $whId;				
		$sqlqry = "select GROUP_CONCAT(id) AS ID FROM fd_landing_gear_master WHERE is_module_level=0 and landing_gear_type IN(".$whId.")";		
		DB_Sql::query($sqlqry);
		DB_Sql::next_record();
		$ids = (DB_Sql::f("ID")!='')?DB_Sql::f("ID"):'';
		if($ids!='' && $ids!=0)
		{
			$strQry2 = "select count(*) as count from fd_landing_gear_master where is_module_level=1 and currently_on IN (".$ids.") AND landing_gear_type=?";
			DB_Sql::query($strQry2,array("landing_gear_type"=>$thId));
			DB_Sql::next_record();
			return DB_Sql::f("count");
		}
		else
			return 0;
	}
	
	function getTypeAssignTo($airlid,$airType)
	{	
		$sel = "select tp.GEARTYPE,group_concat(fa.COMP_NAME) as assingto from landinggear AS tp LEFT JOIN fd_airlines AS fa ON tp.airlinesId=fa.ID where tp.ID IN(".$airType.") group by tp.GEARTYPE order by tp.GEARTYPE,fa.COMP_NAME"; 
		return DB_Sql::query($sel);
	}
	
	function getEliminateType($typeArr,$selcliented,$flg=false)	
	{
		$whArr[0] = $selcliented;
		$quest = '';
		for($j=0;$j<count($typeArr);$j++)
		{
			$whArr[$j+1] = $typeArr[$j];
			$quest .= $coma.'?';
			$coma = ',';
		}
		$sqlqry = "select GROUP_CONCAT(GEARTYPE) AS GEARTYPE from landinggear where airlinesId = ? AND ID IN ($quest)";		
		DB_Sql::query($sqlqry,$whArr);
		DB_Sql::next_record();
		$Mainids = explode(",",DB_Sql::f("GEARTYPE"));
		$whArr = array();
		$quest='';
		$coma='';
		for($j=0;$j<count($typeArr);$j++)
		{
			$whArr[$j] = $typeArr[$j];
			$quest .= $coma.'?';
			$coma = ',';
		}
		if($flg)
		{
			$strWhere = " and airlinesId <>?";
			$whArr[] = $selcliented;
		}
		$sqlqry = "select ID,GEARTYPE,airlinesId from landinggear where ID IN ($quest) $strWhere";		
		DB_Sql::query($sqlqry,$whArr);
		$sep = '';
		$arrAirlines = array();
		while(DB_Sql::next_record())
		{
			//$arrAirlines[DB_Sql::f("airlinesId")][DB_Sql::f("ID")] = DB_Sql::f("GEARTYPE");
			$arrAirlines[DB_Sql::f("airlinesId")][] = DB_Sql::f("ID");
			if(in_array(DB_Sql::f("GEARTYPE"),$Mainids))
			{
				$finalArr.=$sep.DB_Sql::f("ID");
				$sep = ',';
				
			}
		}	
		
		return $arrAirlines; // After remove Assign to column we need to change for signle client 
		$airlinesKey = array_keys($arrAirlines);
		
		$arrFinal =array();
		foreach($arrAirlines as $key=>$air)
		{
			if($selcliented == $key)
			{
				$arrFinal[$key] = $finalArr;
			}
			else
			{
				$sep = '';
				$keyArrJ = array_keys($arrAirlines[$key]);
				for($j = 0 ; $j < count($arrAirlines[$key]); $j++)
				{
					foreach($airlinesKey as $airKey)
					{
						$keyArrI = array_keys($arrAirlines[$airKey]);
						for($i = 0; $i<count($arrAirlines[$airKey]); $i++)
						{
							if($arrAirlines[$key][$keyArrJ[$j]] == $arrAirlines[$airKey][$keyArrI[$i]])
							{	
								$arrFinal[$key] .= $sep.$keyArrI[$i];
								$sep = ',';
							}
						}
					}
				}
			}
		}
		return $arrFinal;
		
	}
	
	function getAllGearTypeBySameModType($ModType)
	{
		if($ModType!=''){
			$str="SELECT GROUP_CONCAT(gearTypeId) as gearTypeId FROM fd_gear_module_type WHERE 1=1 AND gearModuleType IN('".$ModType."') and gearTypeId!='' ORDER BY gearModuleType ASC";
			DB_Sql::query($str,$whArr);
			DB_Sql::next_record();
			return DB_Sql::f("gearTypeId");
		}
		else
			return false;
	}
	
	
	function getGearTypeMatchIds($ids,$airline,$otherAirline)
	{
		global $db,$mdb;
		$mdb = clone $db;
		if($_SESSION['UserLevel'] != 0)
		{	
			 $strWhere .=  " AND (airlinesId =".GETCLIENTID." OR airlinesId IN(".CLIENT_OPT_LIST."))";			 
		}
		if(DBType=='mssql')
		{
			$strQry ="	Select cast(STUFF( (SELECT ',' + cast(GEARTYPE as varchar) from landinggear where ID IN(".$ids.") and airlinesID=".$airline." FOR XML PATH ('')), 1, 1, '1') as varchar) AS GEARTYPE order by GEARTYPE ASC";
			DB_Sql::query($strQry);
			DB_Sql::next_record();
			if(DB_Sql::f('GEARTYPE')!='')
			{
				$GEARTYPE = "'".str_replace(",","','",DB_Sql::f('GEARTYPE'))."'";
				$strQry2 = "select cast(STUFF( (SELECT ',' + cast(ID as varchar) from landinggear WHERE GEARTYPE IN(".$GEARTYPE.") and airlinesID=".$otherAirline." FOR XML PATH ('')), 1, 1, '1') as varchar) AS ID ";
				DB_Sql::query($strQry2);
				DB_Sql::next_record();
				return DB_Sql::f('ID');
			}
			
		}else{
		
			$strQry = "select GROUP_CONCAT(GEARTYPE) as GEARTYPE from landinggear WHERE ID IN(".$ids.") and airlinesID=".$airline."";
			DB_Sql::query($strQry);
			DB_Sql::next_record();
			if(DB_Sql::f('GEARTYPE')!='')
			{
				$GEARTYPE = "'".str_replace(",","','",DB_Sql::f('GEARTYPE'))."'";
				$strQry2 = "select GROUP_CONCAT(ID) as ID from landinggear WHERE GEARTYPE IN(".$GEARTYPE.") and airlinesID=".$otherAirline."";
				DB_Sql::query($strQry2);
				DB_Sql::next_record();
				return DB_Sql::f('ID');
			}
		}
	}


	
}
?>