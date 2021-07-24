<?php 
class DB_Sql
{
  /* public: connection parameters */
  var $Host     = "";
  var $Database = "";
  var $User     = "";
  var $Password = "";

  /* public: configuration parameters */
  var $Auto_Free     = 0;     ## Set to 1 for automatic mysql_free_result()
  var $Debug         = 0;     ## Set to 1 for debugging messages.
  var $Halt_On_Error = "no"; ## "yes" (halt with message), "no" (ignore errors quietly), "report" (ignore errror, but spit a warning)
  var $Seq_Table     = "db_sequence";

  /* public: result array and current row number */
  var $Record   = array();
  var $Row;

  /* public: current error number and error text */
  var $Errno    = 0;
  var $Error    = "";

  /* public: this is an api revision, not a CVS revision. */
  var $type     = "mysql";
  var $revision = "1.2";

  /* private: link and query handles */
  var $Link_ID  = 0;
  var $Query_ID = 0;
  var $col_fields='';
  var $Total_Rows=0;
  var $arr_result=array();
	

  /* public: constructor */
  function DB_Sql($arrDBConfig=NULL) 
  {
      $this->connect($arrDBConfig);
  }
	
  /* public: some trivial reporting */
  function link_id() 
  {
    return $this->Link_ID;
  }

	/*Set Link id this is only for spinix*/
	function SetLinkId($linkid) 
	{
		
		if($linkid!="")
		{
			$this->Link_ID = $linkid;
			$this->type = "SPX";
		}
		
		return $this->Link_ID;
	}
	
	/*END:Set Link id this is only for spinix*/
  function query_id() 
  {
    return $this->Query_ID;
  }

  /* public: connection management */
  function connect($arrDBConfig=NULL) 
  {
	$this->Link_ID=0;
  	if($arrDBConfig!=NULL)
	{
		$this->Database=$arrDBConfig["DATABASE"];
		$this->Host=$arrDBConfig["HOST"];
		$this->User=$arrDBConfig["USER"];
		$this->Password=$arrDBConfig["PASSWORD"];
	}
      
    /* establish connection, select database */
    if ( 0 == $this->Link_ID )
	 {
     	 $this->Link_ID=mysqli_connect($this->Host, $this->User, $this->Password);
		  if (!$this->Link_ID) {
			
			$this->halt("connect($Host, $User, \$Password) failed.");
			return 0;
		  }

      if (!@mysqli_select_db($this->Link_ID,$this->Database)) {
        
		$this->halt("cannot use database ".$this->Database);
        return 0;
      }
    }
    
    return $this->Link_ID;
  }
  function Close()
  {
	  mysqli_stmt_close($this->Query_ID);
  }
  
  /* public: discard the query result */
  function free() 
  {
	  
      @mysqli_stmt_free_result($this->Query_ID);
	  $this->arr_result=NULL;
      $this->Query_ID = 0;
  }
/* query function for sphinix */
function query_spx($Query_String) 
  {
	  
	 
    /* No empty queries, please, since PHP4 chokes on them. */
    if ($Query_String == "")
      /* The empty query string is passed on from the constructor,
       * when calling the class without a query, e.g. in situations
       * like these: '$db = new DB_Sql_Subclass;'
       */
      return 0;
	if($this->type == "mysql")
	{
		if (!$this->connect()) {
		  return 0; /* we already complained in connect() about that. */
		};
	}

    # New query, discard previous result.
    if ($this->Query_ID) {
      $this->free();
    }

    if ($this->Debug)
      printf("Debug: query = %s<br>\n", $Query_String);
//echo $this->Link_ID;

    $this->Query_ID = mysqli_query($this->Link_ID,$Query_String);
	//echo "Hello";
//	return 0;
    $this->Row   = 0;
    $this->Errno = mysqli_errno($this->Link_ID);
    $this->Error = mysqli_error($this->Link_ID);
	
	//...
    if (!$this->Query_ID) {
      $this->halt("Invalid SQL: ".$Query_String);	  
    }

    # Will return nada if it fails. That's fine.
    return $this->Query_ID;
  }
	
  /* public: perform a query */
  function query($Query_String,$where=NULL) 
  {
	 
	/* No empty queries, please, since PHP4 chokes on them. */
    if ($Query_String == "")
      /* The empty query string is passed on from the constructor,
       * when calling the class without a query, e.g. in situations
       * like these: '$db = new DB_Sql_Subclass;'
       */
      return 0;
	  if(!$this->Link_ID)
	  {
		if($this->type == "mysql")
		{
			if (!$this->connect()) {
			  return 0; /* we already complained in connect() about that. */
			};
		}
	  }

    # New query, discard previous result.
    if ($this->Query_ID) {
      $this->free();
    }

	
	$strType='';
	$this->Query_ID=mysqli_prepare($this->Link_ID,$Query_String) ;
	$multipleInsert = 0;
	if(is_array($where) && count($where)>0)
	{
		$i=0;
		foreach($where as $val)
		{
			$val1=array();
			$valutestf = array();
			if(is_array($val))
			{
				$multipleInsert=1;		
				$strType='';
				foreach($val as $nextValue)
				{
					 $strType.="s";
					 $valutestf[] =$nextValue; 
				}
				call_user_func_array('mysqli_stmt_bind_param', array_merge (array($this->Query_ID, $strType), $this->refValues($valutestf))) ; 
				mysqli_stmt_execute($this->Query_ID) ; 
			}
			else
			{
				 $strType.="s";
			}			
		}
		if($multipleInsert ==0)
	call_user_func_array('mysqli_stmt_bind_param', array_merge (array($this->Query_ID, $strType), $this->refValues($where))) ; 
	}
	if($multipleInsert == 0)
	mysqli_stmt_execute($this->Query_ID) ; 
	
    $this->Row   = 0;
   	$this->Errno = mysqli_stmt_error($this->Query_ID);
    $this->Error = mysqli_error($this->Link_ID);
    if (!$this->Query_ID) {
      $this->halt("Invalid SQL: ".$Query_String);	  
    }

    # Will return nada if it fails. That's fine.
    return $this->Query_ID;
  }
  
  function refValues($arr){
    if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
    {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    return $arr;
} 

  function last_id()
  {
	  return mysqli_stmt_insert_id($this->Query_ID);
  }
  function Get_MaxId($ColumnName,$TableName)
  {
	  $this->query("SELECT MAX(".$ColumnName.") as `MAXID` FROM ". $TableName);
	  $this->next_record();
	  return $this->f("MAXID");
  }
  /* public: select query */
  function select($fields="",$from="",$where=NULL,$order="",$limit="")
  {
	
  		if($fields=="")
		{
			return 0;
		}
		if($from=="")
		{
			return 0;
		}
		
		$this->col_fields=$fields;
		$strSql = "SELECT ".$fields." FROM ".$from;
		$strSqlWhere='';
		if(is_array($where) && count($where)>0)
		{
			foreach($where as $key=>$val)
			{
				$strSqlWhere .= ($strSqlWhere=='') ? $key."=?" : " AND ".$key."=?";
			}
		}
		else if (is_string($where) && $where!='')
		{
			$strSqlWhere=$where;
		}
		/*if($where != "")
		{
			$strSql .= " WHERE ".$where;
		}*/
		if($strSqlWhere!='')
		{
			$strSql .= " WHERE ".$strSqlWhere;
		}
		
		if($order != "")
			$strSql .= " ORDER BY ".$order;
		if($limit != "")
			$strSql .= " LIMIT ".$limit;
		//echo $strSql;
		//return $strSql;		

		return $this->query($strSql,$where);
  }
  
  /* This function will work only on Integer value.
  This function will gives the max value of given field(must be integer)from table.  */  
  function GetMaxValue($fields="",$from="",$where="",$val)
  {
  		if($fields=="")
		{
			return 0;
		}
		if($from=="")
		{
			return 0;
		}
		
		$strSql = "SELECT MAX(".$fields.") as `maxval` FROM ".$from;
		if($where != "")
		$strSql .= " WHERE ".$where;

		$this->query($strSql,$val);
		$this->next_record();
		if( $this->f("maxval")==NULL)
		{
			 return '0';
		}
		return $this->f("maxval");
  }
  function select_fn($fun="",$id='')
  {
	 $strSql = "SELECT ".$fun."(".$id.") as path";
	  return $this->query($strSql);
  }
  
  /* This function will work only on Integer value.
  This function will gives the min value of given field(must be integer)from table.  */
  
  function GetMinValue($fields="",$from="",$where="")
  {
  		if($fields=="")
		{
			return 0;
		}
		if($from=="")
		{
			return 0;
		}
		
		$strSql = "SELECT MIN(".$fields.") as `minval` FROM ".$from;
		if($where != "")
			$strSql .= " WHERE ".$where;

		$this->query($strSql);
		$this->next_record();
		if( $this->f("minval")==NULL)
		{
			 return true;
		}
		return $this->f("minval");
  }
  
  /* public: select all query */
  function select_all($from="",$where="",$order="",$limit="")
  {
  		if($from=="")
		{
			return 0;
		}
		
		$strSql = "SELECT * FROM ".$from;
		if($where != "")
			$strSql .= " WHERE ".$where;
		if($order != "")
			$strSql .= " ORDER BY ".$order;
		if($limit != "")
			$strSql .= " LIMIT ".$limit;
		return $this->query($strSql);
  }
  
  /* public: insert query */
  function insert($table, $arrValues)
  {
  		if(!is_array($arrValues) || $table == "")
		{
			return 0;
		}
		$keys="";
		$values="";
		$arrKey = array();
		$arrVal = array();
		$i=0;
		foreach($arrValues as $k => $v)
		{
			if(is_array($v) && $i==0)
			{
				foreach($v as $ktest => $vtest)
				{
					$arrKey[] = $ktest;					
				}
				$arrVal[] = $v;
			}
			else
			{
				if(!is_array($v))
				{
					$arrKey[] = $k;
					$arrVal[] = $v;
				}
				else
				{
					$arrVal[] = $v;
				}
			}
			$i++;
		}
		$keys = implode(",",$arrKey);
		$values = implode(",",$arrVal);		
		$StrPara='';
		foreach($arrVal as $val)
		{
			if(is_array($val))
			{
				$StrPara='';
				foreach($val as $valutest)
				{
					$StrPara .= ($StrPara=='') ? '?' : ',?';
				}
			}
			else
			$StrPara .= ($StrPara=='') ? '?' : ',?';
		}
		
		$strSql="INSERT INTO ".$table." (".$keys.") VALUES(".$StrPara.")"; 
		return $this->query($strSql,$arrVal);
  }
  
  
  /* public: update query */
  function update($table, $arrValues,$where,$flag=0)
  {
	
  		if(!is_array($arrValues) || $table == "")
		{
			return 0;
		}
		$values="";
		$paraarr=array();
		foreach($arrValues as $k => $v)
		{
			$values .= (($values=="")? "" : ", ").$k."= ? ";
			$paraarr[]=$v;
		}
		
		$strSqlWhere='';
		if(is_array($where) && count($where)>0)
		{
			foreach($where as $key=>$val)
			{
				$strSqlWhere .= ($strSqlWhere=='') ? $key."=?" : " AND ".$key."=?";
				$paraarr[]=$val;
			}
		}
		else if (is_string($where) && $where!='')
		{
			$strSqlWhere=$where;
			//$paraarr='';
		}

		$strSql = "UPDATE ".$table." SET ".$values;
		if($where != "")
		$strSql .= " WHERE ".$strSqlWhere;
		if($flag==1)
		{
		  echo $strSql;	
		}
		
		 //echo $strSql;	
		return $this->query($strSql,$paraarr);
  }
  
  /* public: delete query */
  function delete($from, $where)
  {
  		if($from=="")
		{
			return 0;
		}
		

		
		$strSqlWhere='';
		if(is_array($where) && count($where)>0)
		{
			foreach($where as $key=>$val)
			{
				$strSqlWhere .= ($strSqlWhere=='') ? $key."=?" : " AND ".$key."=?";
				
			}
		}
		else if (is_string($where) && $where!='')
		{
			$strSqlWhere=$where;
			
		}

		
		$strSql = "DELETE FROM ".$from;
		if($where != "")
			$strSql .= " WHERE ".$strSqlWhere;

		
		return $this->query($strSql,$where);
  }
  
   /*next record function for sphinix */
  function next_record_spx() 
  {
    if (!$this->Query_ID) {
      $this->halt("next_record called with no query pending.");
      return 0;
    }

    $this->Record = @mysqli_fetch_array($this->Query_ID);
    $this->Row   += 1;
    $this->Errno  = mysqli_errno($this->Link_ID);
    $this->Error  = mysqli_error($this->Link_ID);

    $stat = is_array($this->Record);
    if (!$stat && $this->Auto_Free) {
      $this->free();
    }
    return $stat;
  }
  /* public: walk result set */
  function next_record() 
  {
    if (!$this->Query_ID) {
      $this->halt("next_record called with no query pending.");
      return 0;
    }
	
	$result=$this->Query_ID;
	
	if(count($this->arr_result) ==0)
	{
		$this->arr_result=$this->fetch($result);
		$this->Row=0;
		
		//print_r($this->arr_result);
		
	}
		$total_cnt=count($this->arr_result);
	           
           
    // don't know why, but when I tried $array[] = $data, I got the same one result in all rows
    
	if($total_cnt>$this->Row)
	{
		$this->Record = $this->arr_result[$this->Row];
		$this->Row   += 1;
		$this->Errno  = mysqli_errno($this->Link_ID);
		$this->Error  = mysqli_error($this->Link_ID);
		$stat = is_array($this->Record);
		
	}
	else
	{
		$stat=false;
	}
	
	if (!$stat && $this->Auto_Free) {
		  $this->free();
		}
		return $stat;
    
  }


function fetch($result)
{   
    $array = array();
   
    if($result instanceof mysqli_stmt)
    {
        $result->store_result();
       
        $variables =array();
        $data = array();
        $meta = $result->result_metadata();
       
        while($field = $meta->fetch_field())
            $variables[] = &$data[$field->name]; // pass by reference
       
        call_user_func_array(array($result, 'bind_result'), $variables);
       
        $i=0;
        while($result->fetch())
        {
            $array[$i] = array();
            foreach($data as $k=>$v)
                $array[$i][$k] = $v;
            $i++;
           
            // don't know why, but when I tried $array[] = $data, I got the same one result in all rows
        }
    }
    elseif($result instanceof mysqli_result)
    {
        while($row = $result->fetch_assoc())
            $array[] = $row;
    }
   
    return $array;
}

  /* public: position in result set */
  function seek($pos = 0) 
  {
	
    $status = @mysqli_stmt_data_seek($this->Query_ID, $pos);
    if ($status)
      $this->Row = $pos;
    else 
	{
      $this->halt("seek($pos) failed: result has ".$this->num_rows()." rows");
      /* half assed attempt to save the day, 
       * but do not consider this documented or even
       * desireable behaviour.
       */
      @mysqli_stmt_data_seek($this->Query_ID, $this->num_rows());
      $this->Row = $this->num_rows;
      return 0;
    }
    return 1;
  }

  /* public: table locking */
  function lock($table, $mode="write") 
  {
	  if($this->type == "mysql")
	  {
		  $this->connect();
	  }
    
    $query="lock tables ";
    if (is_array($table)) 
	{
      while (list($key,$value)=each($table)) 
	  {
        if ($key=="read" && $key!=0)
		{
          $query.="$value read, ";
        } else
		{
          $query.="$value $mode, ";
        }
      }
      $query=substr($query,0,-2);
    } else {
      $query.="$table $mode";
    }
    $res = @mysqli_query($this->Link_ID,$query);
    if (!$res) {
      $this->halt("lock($table, $mode) failed.");
      return 0;
    }
    return $res;
  }
  
  function unlock() 
  {
	  if($this->type == "mysql")
	  {
		  $this->connect();
	  }

    $res = @mysqli_query("unlock tables");
    if (!$res) {
      $this->halt("unlock() failed.");
      return 0;
    }
    return $res;
  }

  /* public: evaluate the result (size, width) */
  function affected_rows() 
  {
    return @mysqli_stmt_affected_rows($this->Query_ID);
  }
	function num_rows_spx() 
  {
    return @mysqli_num_rows($this->Query_ID);
  }
  function num_rows() 
  {
	  
	 
	mysqli_stmt_store_result($this->Query_ID);
    return  @mysqli_stmt_num_rows($this->Query_ID);
    //return @mysqli_num_rows($this->Query_ID);
  }

  function num_fields() 
  {
	  
	mysqli_stmt_store_result($this->Query_ID);
    return  @mysqli_stmt_field_count($this->Query_ID);
    
  }

  /* public: shorthand notation */
  function nf() 
  {
    return $this->num_rows();
  }

  function np() 
  {
    print $this->num_rows();
  }

  function f($Name) 
  {
	 
    if(isset($this->Record[$Name]))
      return $this->Record[$Name];
    else 
      return "";
  }
  
  function fr() 
  {
    if(isset($this->Record))
      return $this->Record;
    else 
      return NULL;
  }

  function p($Name) 
  {
    print $this->Record[$Name];
  }

  /* public: sequence numbers */
  function nextid($seq_name) 
  {
	  if($this->type == "mysql")
	  {
		  $this->connect();
	  }
    
    if ($this->lock($this->Seq_Table)) 
	{
      /* get sequence number (locked) and increment */
      $q  = sprintf("select nextid from %s where seq_name = '%s'",
                $this->Seq_Table,
                $seq_name);
      $id  = @mysqli_query($this->Link_ID,$q);
      $res = @mysqli_fetch_array($id);
      
      /* No current value, make one */
      if (!is_array($res)) 
	  {
        $currentid = 0;
        $q = sprintf("insert into %s values('%s', %s)",
                 $this->Seq_Table,
                 $seq_name,
                 $currentid);
        $id = @mysqli_query($this->Link_ID,$q);
      } 
	  else 
	  {
        $currentid = $res["nextid"];
      }
      $nextid = $currentid + 1;
      $q = sprintf("update %s set nextid = '%s' where seq_name = '%s'",
               $this->Seq_Table,
               $nextid,
               $seq_name);
      $id = @mysqli_query( $this->Link_ID,$q);
      $this->unlock();
    } 
	else 
	{
      $this->halt("cannot lock ".$this->Seq_Table." - has it been created?");
      return 0;
    }
    return $nextid;
  }

  /* public: return table metadata */
  function metadata($table='',$full=false) 
  {
    $count = 0;
    $id    = 0;
    $res   = array();

    /*
     * Due to compatibility problems with Table we changed the behavior
     * of metadata();
     * depending on $full, metadata returns the following values:
     *
     * - full is false (default):
     * $result[]:
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags
     *
     * - full is true
     * $result[]:
     *   ["num_fields"] number of metadata records
     *   [0]["table"]  table name
     *   [0]["name"]   field name
     *   [0]["type"]   field type
     *   [0]["len"]    field length
     *   [0]["flags"]  field flags
     *   ["meta"][field name]  index of field named "field name"
     *   The last one is used, if you have a field name, but no index.
     *   Test:  if (isset($result['meta']['myfield'])) { ...
     */

    // if no $table specified, assume that we are working with a query
    // result
    if ($table) 
	{
		if($this->type == "mysql")
	    {
			$this->connect();
		}
      $id = @mysql_list_fields($this->Database, $table);
      if (!$id)
        $this->halt("Metadata query failed.");
    } 
	else 
	{
      $id = $this->Query_ID; 
      if (!$id)
        $this->halt("No query specified.");
    }
 
    $count = @mysql_num_fields($id);

    // made this IF due to performance (one if is faster than $count if's)
    if (!$full) 
	{
      for ($i=0; $i<$count; $i++) 
	  {
        $res[$i]["table"] = @mysql_field_table ($id, $i);
        $res[$i]["name"]  = @mysql_field_name  ($id, $i);
        $res[$i]["type"]  = @mysql_field_type  ($id, $i);
        $res[$i]["len"]   = @mysql_field_len   ($id, $i);
        $res[$i]["flags"] = @mysql_field_flags ($id, $i);
      }
    } 
	else 
	{ // full
      $res["num_fields"]= $count;
    
      for ($i=0; $i<$count; $i++) 
	  {
        $res[$i]["table"] = @mysql_field_table ($id, $i);
        $res[$i]["name"]  = @mysql_field_name  ($id, $i);
        $res[$i]["type"]  = @mysql_field_type  ($id, $i);
        $res[$i]["len"]   = @mysql_field_len   ($id, $i);
        $res[$i]["flags"] = @mysql_field_flags ($id, $i);
        $res["meta"][$res[$i]["name"]] = $i;
      }
    }
    
    // free the result only if we were called on a table
    if ($table) @mysql_free_result($id);
    return $res;
  }

  /* private: error handling */
  function halt($msg) {
   $this->Error = @mysqli_error($this->Link_ID);
    $this->Errno = @mysqli_errno($this->Link_ID);
    if ($this->Halt_On_Error == "no")
   		//ExceptionMsg($msg."<br> ---- ".$this->Error,"DB CLASS HALF");
	  return false ;

    $this->haltmsg($msg);

    if ($this->Halt_On_Error != "report")
      die("Session halted.");
  }

 function haltmsg($msg) {
    printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
    printf("<b>MySQL Error</b>: %s (%s)<br>\n",
      $this->Errno,
      $this->Error);
  }
/* function haltmsg($msg) {
   $ret = "</td></tr></table><b>Database error:</b> %s<br>\n".$msg;
   $ret.= "<b>MySQL Error</b>: %s (%s)<br>\n ".$this->Errno." ".$this->Error;
   echo  $ret;
  }*/

  function table_names() 
  {
    $this->query("SHOW TABLES");
    $i=0;
    while ($info=mysqli_fetch_row($this->Query_ID))
     {
      $return[$i]["table_name"]= $info[0];
      $return[$i]["tablespace_name"]=$this->Database;
      $return[$i]["database"]=$this->Database;
      $i++;
     }
   return $return;
  }

function TimeNdate()
{
	echo 1;
	$this->query("SELECT DATE_FORMAT(NOW(),'%Y-%m-%d %h:%i:%s') as time");
	$this->next_record();
	return $this->f("time");	
}
  function GetConnectionData()
  {
    $data_str = array();
	$data_str["DB"] = $this->Database;
	$data_str["USER"] = $this->User;
	$data_str["PWD"] = $this->Password;
	$data_str["HOST"] = $this->Host;
	
	
	 $this->query("SELECT * FROM tbl_users where 1=1");
    $i=0;
    while ($info=mysqli_fetch_row($this->Query_ID))
     {	  
      $data_str["USER_INFO"]["USER"][$i]= $info[2];
	  $data_str["USER_INFO"]["PWD"][$i]= $info[3];     
      $i++;
     }
    return $data_str;
  }

	
 function kquery($Query_String) 
 {
    /* No empty queries, please, since PHP4 chokes on them. */
    if ($Query_String == "")
      /* The empty query string is passed on from the constructor,
       * when calling the class without a query, e.g. in situations
       * like these: '$db = new DB_Sql_Subclass;'
       */
      return 0;

    # New query, discard previous result.
    if ($this->Query_ID) 
	{
      $this->free();
    }

    if ($this->Debug)
      printf("Debug: query = %s<br>\n", $Query_String);

    $this->Query_ID = @mysql_query($Query_String,$this->Link_ID);
    $this->Row   = 0;
    $this->Errno = mysql_errno();
    $this->Error = mysql_error();
    if (!$this->Query_ID) {
      return 0;	  
    }
    # Will return nada if it fails. That's fine.
    return $this->Query_ID;
  }
	function StartTransaction($_LinkID,$bool)
	{
		return mysqli_autocommit($_LinkID,$bool);
	}
	
	function Commit($_LinkID)
	{
		return  mysqli_commit($_LinkID);
	}
	
	function Rollback($_LinkID)
	{
		if(mysqli_rollback($_LinkID))
		{
			 return true;
		}
		else
		{
			return false;
		}
	}
	
	
	function GetCurrentDate()
	{
		return  date('Y-m-d H:i:s');
	}
	
	function GetDate($strDate='')
	{
		if($strDate!='')
		{
			return $date=date('Y-m-d',strtotime($strDate));
		}
		else
		{
			return $date=date('Y-m-d');
		}
		
		return $date=date('Y-m-d',strtotime($strDate));
	}
	
	function GetDateTime($strDate='')
	{
		if($strDate!='')
		{
			return $date=date('Y-m-d H:i:s' ,strtotime($strDate));
		}
		else
		{
			return $date=date('Y-m-d H:i:s');
		}
		
	}
	
	function getRowCount( $TableName, $where ) {
	
		$this->query("SELECT count(*) as tot_rows FROM ". $TableName." where ipaddress=?",$where);
		$this->next_record(); 
		return $this->f("tot_rows");
	}
	function IsRestart()
	{	
		$this->query("SELECT * FROM sphinxcounter");
		$this->next_record(); 
		return $this->f("IsRestart");		
	}

}

/*function escape($str)
{
		return "'".mysql_real_escape_string($str)."'";
}*/

function remove_invisible_characters($str, $url_encoded = TRUE)
	{
		$non_displayables = array();
		
		// every control character except newline (dec 10)
		// carriage return (dec 13), and horizontal tab (dec 09)
		
		if ($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
		}
		
		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do
		{
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
}
function escape($str,$is_purifire=0,$is_real_escape=0)
{
	global $dbs,$db;
	$LinkId=-1;
	if($dbs->Link_ID!=0)
	{
		$LinkId=$dbs->Link_ID;
	}
	else if($db->Link_ID!=0)
	{
		$LinkId=$db->Link_ID;
	}
	//$rtn ="'".mysqli_real_escape_string($LinkId,strip_tags(remove_invisible_characters($str)))."'";
	//$str = mysqli_real_escape_string($LinkId,$str);
	
	if($is_purifire==0)
	{
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$str = $purifier->purify($str);
	}
	$rtn =($str);
	if($is_real_escape==1)
	{
		$rtn ="'".mysqli_real_escape_string($LinkId,remove_invisible_characters($str))."'";
	}
	return $rtn;
}

function ddmmyyyy($date)
{
  $newdate='';
  if($date!='')
  {
	  $datearr=explode(' ',$date);
	  $newdatearr;
	  $newdatearr=explode("-",$datearr[0]);
	  $newdate=$newdatearr[2].'-'.$newdatearr[1].'-'.$newdatearr[0];
  }
  return $newdate;
}

function showDateTime($strDate='')
{
	if($strDate!='')
	{
		return $date=date('d-m-Y H:i:s' ,strtotime($strDate));
	}
	else
	{
		return $date=date('d-m-Y H:i:s');
	}
	
} 

?>