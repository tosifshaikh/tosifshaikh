<?php
include_once('infly23ade/checklogin.php');
include_once('infly23ade/main.inc.php');
require_once (INCLUDE_PATH."xajax_core/xajax.inc.php");
$xajax = new xajax();
$Request=array();
$TypeId = array();

if(isset($_REQUEST["recid"]) && !empty($_REQUEST["recid"]) && !ctype_digit($_REQUEST["recid"]))
{
	 ExceptionMsg("recid have Incorrect value View page",'master type');
	header('Location:error.php');
	exit;
}

foreach($_REQUEST as $key => $value)
{
	$Request[$key]=$value;
}



if(!isset($Request["mtype"]))
{
		ExceptionMsg('section:master_type.php,Page:master_type.php, Message:MTYPE Not Found');
		header("Location:error.php");
		exit;
}
$linkIdP = $_REQUEST['linkId'];
if($linkIdP == "")
{
	$linkIdP = $_REQUEST['linkid'];
}
if (isset($linkIdP) && !ctype_digit($linkIdP))
{
		ExceptionMsg('section:master_type.php,Page:master_type.php, Message:invalid link id',$Request["mtype"].' TYPE');
		header("Location:error.php");
		exit;
}

if($Request["mtype"] == "ENGINE")
{
	require_once(DB_PATH."/engine_type_class.php");
	$db  = new EngType($_CONFIG);
	$mdb = clone $db;
	$gdb = clone $db;
	$dbj = clone $db;
	$engdb = clone $db;
	
	define("SECTION_PATH",MODULES_PATH.'engine_type/');
	include_once(SECTION_PATH.'engine.function.php');	
	$xajax->processRequest();
	if(isset($_REQUEST["act"]) && $_REQUEST["act"] != "")
	{
		if(trim($_REQUEST["act"])== "GRID")
		{
			include_once(SECTION_PATH.'engine.grid.php');
			exit();
		} else if(trim($_REQUEST["act"])== "show"){
			include_once('show_masterlist.php');
			exit();
		}
	}
	include_once(SECTION_PATH.'engine.view.php');
}

else if($Request["mtype"] == "MODULE")
{	
	require_once(DB_PATH."/module_type_class.php");
	$db = new ModulType($_CONFIG);
	$mdb = clone $gdb = clone $dbj = clone $engdb = clone $db;
	define("SECTION_PATH", MODULES_PATH.'module_type/');
	include_once(SECTION_PATH.'moduleType.function.php');

	$xajax -> processRequest();
	if(isset($_REQUEST["act"]) && $_REQUEST["act"] != "")
	{
		if(trim($_REQUEST["act"])== "GRID")
		{
			include_once(SECTION_PATH.'moduleType.grid.php');
			exit();
		} else if(trim($_REQUEST["act"])== "show"){
			include_once('show_masterlist.php');
			exit();
		}
	}
	include_once(SECTION_PATH.'moduleType.view.php');
}

else if($Request["mtype"] == "AIRCRAFT")
{
	include_once(DB_PATH.'aircraft_type_class.php');
	$db  = new AirType($_CONFIG);
	$mdb = clone $gdb = clone $dbj = clone $db;
	define("SECTION_PATH",MODULES_PATH.'aircraft_type/');
	include_once(SECTION_PATH.'aircraft_type.function.php');	
	$xajax->processRequest();
	if(isset($_REQUEST["act"]) && $_REQUEST["act"] != "")
	{
		if(trim($_REQUEST["act"])== "GRID")
		{
			include_once(SECTION_PATH.'aircraft_type.grid.php');
			exit();
		} else if(trim($_REQUEST["act"])== "show"){
			include_once('show_masterlist.php');
			exit();
		}
	}
	include_once(SECTION_PATH.'aircraft_type.view.php');
}
else if($Request["mtype"] == "CHECK")
{
	require_once(DB_PATH."/checktype_master_class.php");
	$db  = new CheckType($_CONFIG);
	$gdb = clone $mdb  = clone $dbj = clone $db;
	define("SECTION_PATH",MODULES_PATH.'checktype_master/');
	include_once(SECTION_PATH.'checktype_master.function.php');	
	$xajax->processRequest();
	if(isset($_REQUEST["act"]) && $_REQUEST["act"] != "")
	{
		if(trim($_REQUEST["act"])== "GRID")
		{
			include_once(SECTION_PATH.'checktype_master.grid.php');
			exit();
		} else if(trim($_REQUEST["act"])== "show"){
			include_once('show_masterlist.php');
			exit();
		}
	}
	include_once(SECTION_PATH.'checktype_master.view.php');
}
else if($Request["mtype"] == "GEAR")
{
	require_once(DB_PATH."/gear_type_class.php");
	$db  = new GearType($_CONFIG);
	$mdb = clone $gdb = clone $dbj = clone $geardb = clone $db;
	define("SECTION_PATH",MODULES_PATH.'landing_gear_type/');
	include_once(SECTION_PATH.'landing_gear.function.php');	
	$xajax->processRequest();
	if(isset($_REQUEST["act"]) && $_REQUEST["act"] != "")
	{
		if(trim($_REQUEST["act"])== "GRID")
		{
			include_once(SECTION_PATH.'landing_gear.grid.php');
			exit();
		} else if(trim($_REQUEST["act"])== "show"){
			include_once('show_masterlist.php');
			exit();
		}
	}
	include_once(SECTION_PATH.'landing_gear.view.php');
}
else if($Request["mtype"] == "GEARMODULE")
{
	require_once(DB_PATH."/gear_module_type_class.php");
	$db = new GearModuleType($_CONFIG);
	$mdb = clone $ndbn = clone $gdb = clone $dbj = clone $geardb = clone $db;
	define("SECTION_PATH",MODULES_PATH.'gear_module_type/');
	include_once(SECTION_PATH.'gear_module_type.function.php');
	$xajax->processRequest();
	if(isset($_REQUEST["act"]) && $_REQUEST["act"] != "")
	{
		if(trim($_REQUEST["act"]) == "GRID")
		{
			include_once(SECTION_PATH.'gear_module_type.grid.php');
			exit;
		}
		else if(trim($_REQUEST["act"]) == "show")
		{
			include_once('show_masterlist.php');
			exit;
		}
	}
	include_once(SECTION_PATH.'gear_module_type.view.php');
}
else if($Request["mtype"] == "APU")
{
	require_once(DB_PATH."/apu_type_class.php");
	$db  = new APUType($_CONFIG);
	$gdb = clone $dbj = clone $engdb = clone $mdb = clone $db;
	define("SECTION_PATH",MODULES_PATH.'apu_type/');
	include_once(SECTION_PATH.'apu.function.php');	
	$xajax->processRequest();
	if(isset($_REQUEST["act"]) && $_REQUEST["act"] != "")
	{
		if(trim($_REQUEST["act"])== "GRID")
		{
			include_once(SECTION_PATH.'apu.grid.php');
			exit();
		} else if(trim($_REQUEST["act"])== "show"){
			include_once('show_masterlist.php');
			exit();
		}
	}
	include_once(SECTION_PATH.'apu.view.php');
}
else if($Request["mtype"] == "THRUST")
{
	require_once(DB_PATH."/thrust_type_class.php");
	$db  = new THRUSTType($_CONFIG);
	$mdb = clone $gdb = clone $dbj = clone $engdb = clone $db;
	define("SECTION_PATH",MODULES_PATH.'thrust_type/');
	include_once(SECTION_PATH.'thrust.function.php');	
	$xajax->processRequest();
	if(isset($_REQUEST["act"]) && $_REQUEST["act"] != "")
	{
		if(trim($_REQUEST["act"])== "GRID")
		{
			include_once(SECTION_PATH.'thrust.grid.php');
			exit();
		} else if(trim($_REQUEST["act"])== "show"){
			include_once('show_masterlist.php');
			exit();
		}
	}
	include_once(SECTION_PATH.'thrust.view.php');
}
else if($Request["mtype"] == "BIBLE")
{
	include_once(DB_PATH.'aircraft_type_class.php');
	$db  = new AirType($_CONFIG);
	$dbs = new AirType($_CONFIG_SEARCHER);
	define("SECTION_PATH",MODULES_PATH.'bible/');
	include_once(SECTION_PATH.'bible.function.php');	
	$xajax->processRequest();
	if(trim($_REQUEST["act"])== "GRID")
	{
		include_once(SECTION_PATH.'bible.grid.php');		
		exit();
	} 
	include_once(SECTION_PATH.'bible.view.php');
	exit();
}
else if($Request["mtype"] == "RSN_CHNG")
{
	include_once(DB_PATH.'reason_for_change_class.php'); 
	$db  = new ReasonForChange($_CONFIG);
	$mdb = clone $gdb = clone $dbj = clone $tdb = clone $engdb = clone $db;
	//$dbs = new ReasonForChange($_CONFIG_SEARCHER);
	define("SECTION_PATH",MODULES_PATH.'reason_for_change/');
	include_once(SECTION_PATH.'reason_for_change.function.php');	
	$xajax->processRequest();
	if(trim($_REQUEST["act"])== "GRID")
	{
		include_once(SECTION_PATH.'reason_for_change.grid.php');		
		exit();
	} 
	include_once(SECTION_PATH.'reason_for_change.view.php');
	exit();
}
else if($Request["mtype"] == "NON_AUTHO")
{
	include_once(DB_PATH.'antr_cls.php');
	$gdb=new antr($_CONFIG);
	$db=new antr($_CONFIG);
	$dbs=new antr($_CONFIG);
	define("SECTION_PATH",MODULES_PATH.'non_auth_type/');
	include_once(SECTION_PATH.'non_auth_type.function.php');	
	$xajax->processRequest();
	if(trim($_REQUEST["act"])== "GRID")
	{
		include_once(SECTION_PATH.'non_auth_type.grid.php');		
		exit();
	} 
	include_once(SECTION_PATH.'non_auth_type.view.php');
	exit();
}
else if($Request["mtype"] == "AUTHO_ATYPE")
{
	include_once(DB_PATH.'antr_cls.php');
	$gdb=new antr($_CONFIG);
	$db=new antr($_CONFIG);
	$dbs=new antr($_CONFIG);
	define("SECTION_PATH",MODULES_PATH.'authorisations_aircraft_type/');
	include_once(SECTION_PATH.'authorisations_aircraft_type.function.php');	
	$xajax->processRequest();
	if(trim($_REQUEST["act"])== "GRID")
	{
		include_once(SECTION_PATH.'authorisations_aircraft_type.grid.php');		
		exit();
	} 
	include_once(SECTION_PATH.'authorisations_aircraft_type.view.php');
	exit();
}
else if($Request["mtype"] == "AUTHO_ETYPE")
{
	include_once(DB_PATH.'antr_cls.php');
	$gdb=new antr($_CONFIG);
	$db=new antr($_CONFIG);
	$dbs=new antr($_CONFIG);
	define("SECTION_PATH",MODULES_PATH.'authorisations_engine_type/');
	include_once(SECTION_PATH.'authorisations_engine_type.function.php');	
	$xajax->processRequest();
	if(trim($_REQUEST["act"])== "GRID")
	{
		include_once(SECTION_PATH.'authorisations_engine_type.grid.php');		
		exit();
	} 
	include_once(SECTION_PATH.'authorisations_engine_type.view.php');
	exit();
}
else if($Request["mtype"] == "PROPELLER")
{
	require_once(DB_PATH."/propeller_type_class.php");
	$db  = new PropellerType($_CONFIG);
	$mdb  = new PropellerType($_CONFIG);
	$gdb = clone $db;
	$dbj = clone $db;
	$engdb = clone $db;
	
	define("SECTION_PATH",MODULES_PATH.'propeller_type/');
	include_once(SECTION_PATH.'propeller.function.php');	
	$xajax->processRequest();
	if(isset($_REQUEST["act"]) && $_REQUEST["act"] != "")
	{
		if(trim($_REQUEST["act"])== "GRID")
		{
			include_once(SECTION_PATH.'propeller.grid.php');
			exit();
		} 
		else if(trim($_REQUEST["act"])== "show"){
			include_once('show_masterlist.php');
			exit();
		}
	}
	include_once(SECTION_PATH.'propeller.view.php');
}
else
{
	ExceptionMsg('section:master_type.php,Page:master_type.php, Message:invalid MTYPE');
	header("Location:error.php");
	exit;
}
?>