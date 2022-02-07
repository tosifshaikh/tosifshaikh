<?php
class GearType extends DB_Sql
{
	public $TableName = "";
	function getGrid()
	{
		$wh = " and 1=1 ";
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
			$wh .= " and airlinesId = ".$_SESSION['MainClient']."  or  airlinesId in(".$in.") ";
		}
		//return $sql = "select gr.*,al.COMP_NAME as airlines from landinggear as gr join fd_airlines as al on gr.airlinesId = al.ID where al.DELFLG=0 ".$wh." order by al.COMP_NAME,gr.GEARTYPE";
		return $sql = "select gr.*,al.COMP_NAME as airlines,al.is_gear_module from landinggear as gr join fd_airlines as al on gr.airlinesId = al.ID where al.DELFLG=0 ".$wh." order by al.COMP_NAME,gr.GEARTYPE";
	}
	
	
	function fungetRowCount( $TableName,$str,$where) {
	
		$this->query("SELECT count(*) as tot_rows FROM ". $TableName. " where 1=1 AND ". $str,$where);
		$this->next_record(); 
		return $this->f("tot_rows");
	}
	
	function selectMultiple($totalid)
	{	
		global $tdb;
		$airLinesIdArr= explode(",",$totalid);
		$quest='';
		for($k=0;$k<count($airLinesIdArr);$k++)
		{
			$whArrSt[$k]=$airLinesIdArr[$k];
			$quest .= $coma.'?';
			$coma=',';
		}
		return DB_Sql::query("SELECT * from aircrafttype where ID in(".$quest.")",$whArrSt);
	}
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
			$strQry = "update landinggear set GEARTYPE=?, manufacturer=?, no_of_module=? where ID IN(".$quest.")";
			return DB_Sql::query($strQry,$array_engval);
		}
		else
			return true;
	}
	
	function getCountGearType($arr)
	{
		$sel = "select id from landinggear where airlinesId=? and GEARTYPE=? and id<>?";
		return DB_Sql::query($sel,$arr);
	}

	
	function checkSameAircraftTypes($clientsId,$airTypeId)
	{
		$sqlQry = 'Select ICAO from aircrafttype where Id = ?';
		DB_Sql::query($sqlQry,$airTypeId);
		DB_Sql::next_record();
		
		$sqlQry2 = 'Select COUNT(ICAO) as totIcao from aircrafttype where airlinesId = ?';
		DB_Sql::query($sqlQry2,array("airlinesId"=>DB_Sql::f("airlinesId")));
		DB_Sql::next_record();
		return DB_Sql::f("totIcao");
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
		
		$strQry = 'select DISTINCT(airlinesId) as airId from aircrafttype where ID IN('.$quest.')';
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
		
		$strQry ="select at.*,fa.ID as faid,fa.COMP_NAME from aircrafttype as at left join fd_airlines as fa ON at.airlinesId=fa.ID where fa.DELFLG=0".$strWhere." order by fa.COMP_NAME,at.ICAO ";
		return DB_Sql::query($strQry,$whArr);
	}
	
	
	function getExcludeIds($whId='',$thId)
	{
		$whId= ($whId=='')? 0 : $whId;				
		$sqlqry = "select GROUP_CONCAT(TAIL) AS TAIL FROM archive WHERE AIRCRFTTYPE IN(".$whId.")";		
		DB_Sql::query($sqlqry);
		DB_Sql::next_record();
		$ids = (DB_Sql::f("TAIL")!='')?DB_Sql::f("TAIL"):'';
		if($ids!='' && $ids!=0)
		{
			$strQry2 = "select count(*) as count from fd_landing_gear_master where currently_on IN (".$ids.") AND landing_gear_type=?";
			DB_Sql::query($strQry2,array("landing_gear_type"=>$thId));
			DB_Sql::next_record();
			return DB_Sql::f("count");
		}
		else
			return 0;
	}
	
	function getTypeAssignTo($airlid,$airType)
	{
		$sel = "select tp.ICAO,group_concat(fa.COMP_NAME) as assingto from aircrafttype AS tp LEFT JOIN fd_airlines AS fa ON tp.airlinesId=fa.ID 
where tp.ID IN(".$airType.") group by ICAO order by tp.ICAO,fa.COMP_NAME";
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
		$sqlqry = "select GROUP_CONCAT(ICAO) AS ICAO from aircrafttype where airlinesId = ? AND ID IN ($quest)";		
		DB_Sql::query($sqlqry,$whArr);
		DB_Sql::next_record();
		$Mainids = explode(",",DB_Sql::f("ICAO"));
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
		$sqlqry = "select ID,ICAO,airlinesId from aircrafttype where ID IN ($quest) $strWhere";		
		DB_Sql::query($sqlqry,$whArr);
		$sep = '';
		$arrAirlines = array();
		while(DB_Sql::next_record())
		{
			//$arrAirlines[DB_Sql::f("airlinesId")][DB_Sql::f("ID")] = DB_Sql::f("ICAO");
			$arrAirlines[DB_Sql::f("airlinesId")][] = DB_Sql::f("ID");
			if(in_array(DB_Sql::f("ICAO"),$Mainids))
			{
				$finalArr.=$sep.DB_Sql::f("ID");
				$sep = ',';
				
			}
		}	
		//print_r($arrAirlines);exit;
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
	
	function getAllAirTypeBySameGearType($gearType)
	{
		if($gearType!=''){
			$str="SELECT GROUP_CONCAT(aircrafttype_id) as aircrafttype_id FROM landinggear WHERE 1=1 AND GEARTYPE IN('".$gearType."') and aircrafttype_id!='' ORDER BY GEARTYPE ASC";
			DB_Sql::query($str,$whArr);
			DB_Sql::next_record();
			return DB_Sql::f("aircrafttype_id");
		}
		else
			return false;
	}
	
	function is_check_parent_type($id)
	{
		$query = "SELECT count(*) as totCount FROM fd_gear_module_type where FIND_IN_SET(?,gearTypeId)";
		DB_Sql::query($query,array($id));
		DB_Sql::next_record();
		return DB_Sql::f("totCount");
	}


}
?>