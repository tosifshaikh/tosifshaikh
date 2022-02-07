<?php
ini_set("display_errors", "off");
//Check Login.....
$sessionTimeoutSeconds = 3600;
function setClientTimeout()
{
	global $sessionTimeoutSeconds;
	$clientTimeout = date("Y,m,d,H,i,s",strtotime(date("Y-m-d H:i:s"))+$sessionTimeoutSeconds+($_SESSION["SESS_TIME_DIFF"]));
	$_SESSION["ClientTimeout"] = $clientTimeout;
}
if(isset($_SESSION["SESS_TIME_DIFF"]) && $_SESSION["SESS_TIME_DIFF"]!="")
{
	setClientTimeout();
}
ob_start();
error_reporting(0);
// Initialize global variables
if(!defined("BASEPATH"))
{    
	$base_url="";
	define("DBType",'mysql');
	define("BASEPATH",$base_url."");
	
	define("DB_PATH","fly20ade/".DBType."/");			
	define("INCLUDE_PATH","infly23ade/");
	define("CLASS_PATH",INCLUDE_PATH."classes/");
	define("HEADER_PATH",INCLUDE_PATH."header/");
	define("FUNCTION_PATH","functions/");
	define("JS_PATH","js/");
	define("TINYMCE_JS_PATH","tinymce/jscripts/tiny_mce/");
	define("CSS_PATH","css/");
	define("THEME_PATH",BASEPATH."themes/");
	define("MODULES_PATH",BASEPATH."modules/");
	define("IMAGE_PATH",$base_url."images/");
	define("DOCUMENT_ROOT",$_SERVER['DOCUMENT_ROOT']);
	define("IMAGE_ROOT",$_SERVER['DOCUMENT_ROOT']."\\IMAGES\\");
	define("CALENDAR_PATH","js/calendar/");
	define("_APP_URL_PH",$_SERVER['DOCUMENT_ROOT']);
	define("CATCH_FOLDER","C:\\FLYdocsCache");
	define("SHORT_CLIENT","VAA");
	define("FULL_EXPORT_CLIENT","Virgin Atlantic Airways");
	define("EXPORT_APPROVED_BY","Martyn Haines, GM Continuing Airworthiness:");
	define("CATCH_FOLDER_PATH","C:\\FLYdocsCache\\Notification\\test.txt");
	define("MAIN_FODLER_ROOT_NAME","DevelopmentSource/flydocstest/");
	define("SPX_NAME","FLYdocsMainTemp");
	define("SPX_IP","192.168.100.58");
	define("SPX_PORT","9306");
	define("SUPER_PWD","GEN2ADMIN");
	define("_TOOL_TIP_TEXT","FLYCHAT opening times:@Monday to Friday: 0900 - 1700 UK time@Saturday and Sunday: Closed@Bank Holidays: Closed");
	define("DOWNLOAD_EXE_PATH","C:\\inetpub\\wwwroot\\DevelopmentSource\\flydocstest\\services\\");
	define('ERROR_SAVE_MESSAGE','There is an issue in saving record. Please Contact Administrator for further assistance.');
    define('ERROR_FETCH_MESSAGE','There is an issue in fetching record. Please Contact Administrator for further assistance.');
    define('ERROR_DELETE_MESSAGE','There is an issue in deleting record. Please Contact Administrator for further assistance.');
	define('INVALID_INPUT','Invalid Argument.');	
	define("_SITE_EMAIL","FLYdocs: Admin <admin@flydocs.aero>");
	
	define("DIGITAL_SIGN_EXE_PATH",$_SERVER['DOCUMENT_ROOT'].'\\DevelopmentSource\\flydocstest\\services\\DigitalSign.exe');
	define("STATUS_SHEET_SIGN_EXE_PATH",$_SERVER['DOCUMENT_ROOT'].'\\DevelopmentSource\\flydocstest\\services\\StatusSheetSign\\StatusSheetSign.exe');
	
	define("VERIFYSING_SIGN_EXE_PATH",$_SERVER['DOCUMENT_ROOT'].'/DevelopmentSource/flydocstest/VerifySignature.exe');
	
	define('SITE_PROTOCOL',(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on'))?'https':'http');
	
	define("DIGITAL_SIGN_EXE_PATH_DOWNLOAD", SITE_PROTOCOL."://www.flydocstest.com/services/VerifySignature.zip");
	define("CLIENT_UPLOAD","client upload");
	define("AIRCRAFT_CENTER","Aircraft Centre");
	define("PRIMARY_CLIENT_UPLOAD","VAA Client Upload"); 
	define("PRIMARY_DELIVERY_BIBLE","VAA Delivery Bible");
	define("PARTIAL_IPACCESS","1");
	define("FLYDOCS_DIFF_FLG_CONTENT","++**##++");
	define("FLYSEARCH_PATH",$_SERVER['DOCUMENT_ROOT']."\\FLYsearch\\FLYsearch.exe");
	define("SELECT_ALL_PATH","C:\\inetpub\\wwwroot\\DevelopmentSource\\flydocstest\\FLYsearch\\FLYdocsSelectAllService.exe");
	//define("FLYSEARCH_PATH",$_SERVER['DOCUMENT_ROOT']."\\FLYsearch\\NewFLYSearch\\FLYsearch.exe");
	define("PIA_APP_PATH","http://www.flydocstest.com/services/API.apk");
	define("LANG",BASEPATH."language/");
	
	// for  client Sesion use 
	if($_SESSION['data_view_option_list']!="" && $_SESSION['data_view_option_list']!=0){
		define("CLIENT_OPT_LIST",$_SESSION['data_view_option_list']);}
	else {
			define("CLIENT_OPT_LIST","'".$_SESSION['data_view_option_list']."'");}
			define("GETCLIENTID",$_SESSION['MainClient']);
	
			// Load global configuration
			
			$webpage_Title =  "FLYdocs - Advanced Aviation Systems";
			
			require_once(INCLUDE_PATH."webconfig.inc");
			require_once(INCLUDE_PATH."htmlcommon.php");
			
			// Load database configuration
			
			require_once(INCLUDE_PATH."dbconfig.inc");
			// Initialize database object
			
			if(DBType=='mssql')
			{			
				require_once("fly20ade/conn/db_mssql.inc");
			}
			else
			{
				require_once("fly20ade/conn/db_mysql.inc");
			}
		$db = new DB_Sql($_CONFIG);
		$ndb = new DB_Sql($_CONFIG);
		$mdb= new DB_Sql($_CONFIG);
		
		$time = time() - (3600); 
	
		$query = " select * from fd_logged_in_user where updated_on < $time  and hour_out_flag=0 ";
		$db->query($query);
		while($db->next_record())
		{
			$UpdateMCCArr = array();
			$UpdateMCCArr['work_user_id']=0;
			$ndb->select("id","fd_mcc_docs",array("work_user_id"=>$db->f("user_id")));
			if($ndb->num_rows()>0)
			{
				$mdb->update('fd_mcc_docs',$UpdateMCCArr,array("work_user_id"=>$db->f("user_id")));
			}
			
			$UpdateArr['hour_out_flag']=1;
			$UpdateArr["log_out_time"] = date("Y-m-d H:i:s");
			$mdb->update('fd_logged_in_user',$UpdateArr,array("id"=>$db->f("id")));
		}
			if (isset($_SESSION['TIMEOUT']) && (time() - $_SESSION['TIMEOUT']) > $sessionTimeoutSeconds) {
				
				
				
			$db = new DB_Sql($_CONFIG);
			$WhrupdateArr = array();
			$WhrupdateArr['user_id'] = $_SESSION['UserId'];
			$WhrupdateArr['session_id'] = session_id();
			$WhrupdateArr['hour_out_flag']=0;
			
			$UpdateArr = array();
			$UpdateArr['hour_out_flag']=1;
			$UpdateArr["log_out_time"] = date("Y-m-d H:i:s");
			
			$db->update('fd_logged_in_user',$UpdateArr,$WhrupdateArr);	
			
			
			
			/*mcc currently work on Work*/
			$upmccArr = array();
			$upmccArr["work_user_id"] =0; 
			
			$whereMccArr = array();
			$whereMccArr["work_user_id"] = $_SESSION['UserId'];
			
			$db->update('fd_mcc_docs',$upmccArr,$whereMccArr);
			
			/*  */
			session_destroy();   // destroy session data in storage
			header("Location:logout.php?ses_out=11");
			exit();
		}
		
		$_SESSION['TIMEOUT'] = time();
		
		if(end(explode("/",$_SERVER['PHP_SELF']))!="edoc_template.php" && end(explode("/",$_SERVER['PHP_SELF']))!="metadata_template.php")
		{
			foreach($_REQUEST as $key=>$val)
			{
				if(is_array($val))
				{
					foreach($val as $sub_key=>$sub_val)
					{
						$_REQUEST[$key][$sub_key]=escape($sub_val);
					}
				}
				else
				{
					$_REQUEST[$key]=escape($val);
				}
			}
		}
		
		// Manage document Buttons
		include(INCLUDE_PATH.'language.php');
		include(THEME_PATH."hooks_new.inc");
		// Manage document functions
		//require_once(INCLUDE_PATH."manage_document.inc");
		require_once(INCLUDE_PATH."encrypt_url.php");
			
		require_once(INCLUDE_PATH."common.inc");
	
		include_once(INCLUDE_PATH."check_access.php");
		
		if($_REQUEST['access']!="")
		{
			if(!isset($_SESSION['accessSup']) || $_SESSION['accessSup']=="")
			 {
				@session_start();
				$_SESSION['accessSup'] =trim($_REQUEST['access']);
			 }
		}		
		if(!isset($flg)) $flg='';
		//for ip secure page access.	
		if($_REQUEST['token'] != '' && (checkAccess::curPageName() =='index.php' || checkAccess::curPageName() =='verifyaccount.php'))
		{
			$ur = str_replace("sls","/",$_REQUEST['token']);
			$ur = str_replace("pls","+",$ur);	
			$key = decrypt($ur,"mypass","a1");
			$keyArr = explode(':@:',$key);
			if(end($keyArr) == 'token')
			{
				$flg = 1;
			 	$_SESSION['key_username'] = $keyArr[0];
			}
			else
			{
				header("Location: restrictedPage.php");
				exit;
			}
		}
		
		if($_SESSION['secure_ip_access'] != '' && $_SESSION['secure_ip_access'] == $_SESSION['UserId'])
		{
			if(($_SESSION['secure_ip_access_time'] < time()))
			{
				$db->delete("secure_ip_access","user_id=".$_SESSION['UserId']);
				$_SESSION['secure_ip_access'] = '';
				header('localhost:logout.php');
				exit;
			}
			else
			{
				$flg = '1';
			}
		}
		//..
		
	
		
		if($_REQUEST['key'] != '' && $_SESSION['accessSup'] != "GEN2ADMIN")
		{
			$kurl = str_replace("shlesh","/",$_REQUEST['key']);
			$kurl = str_replace("plus","+",$kurl);
			$_SESSION['userurl_key'] = $kurl;
			$key = decrypt($kurl,"mypass","a1");
			$db->select("id","fd_users","username = '$key'");
			if($db->num_rows())
			{
				$_SESSION['accessSup'] = "GEN2ADMIN";
				$_SESSION['key_username'] = $key;
			}
			else
			{
				$_SESSION['accessSup'] = "";
			}
		}		
		$SuperPwd = "GEN2ADMIN";
		
		if(strcmp($_SESSION['accessSup'],$SuperPwd) != 0  && $flg!="1" && checkAccess::curPageName()!='roaming.php')
		{
			$sqlPart="";
			
			if(PARTIAL_IPACCESS==1)
			{
				$GlobalIpArr=explode(".",$_SERVER['REMOTE_ADDR']);
				$GlobalIp_1=$GlobalIpArr[0].".".$GlobalIpArr[1].".*.*";
				$GlobalIp_2=$GlobalIpArr[0].".".$GlobalIpArr[1].".".$GlobalIpArr[2].".*";
				$sqlPart=" or ipaddress='".$GlobalIp_1."' or ipaddress='".$GlobalIp_2."'  ";
			}
			
			
			$db = new DB_Sql($_CONFIG);
			 $db->select('*','tbl_ipaccess'," active =1 AND (ipaddress='".$_SERVER['REMOTE_ADDR']."' ".$sqlPart."  )");
			
			
			if(strpos($_SERVER['REQUEST_URI'], '/clients')===FALSE)
			{
				if(!$db->num_rows()>0)
				{
					header("Location: restrictedPage.php");
					exit;
				}
			}
		}
		
		// For Old functioanliy, please remove this condition.......................
		if($_SESSION['UserLevel'] != "0" ) { 
			$db = new DB_Sql($_CONFIG);
			$db->select("id, is_multiple_logine","fd_users","id='".$_SESSION['UserId']."' ");
			$db->next_record();
			if( $_SESSION['is_multiple_logine'] == 0 || $db->f('is_multiple_logine') == 0 ) {
		
				include_once('session_user.php');
			}
		}
		if($_SESSION['UserLevel']==0 || $_SESSION['UserLevel']==3)
		{
			define("BIBLE_TEMPLATE","Delivery Bible");
		}
		else
		{
			define("BIBLE_TEMPLATE",$_SESSION['BIBLE_TEMPLATE']);
		}
		if(isset($_SESSION["client_logo"]) &&  $_SESSION["client_logo"]=="")
		{
			include_once(DB_PATH."/db_common.php");
			$_dbcf  = new commonFun($_CONFIG);	
			getThem();
		}
		
		if(isset($_SESSION['UserId']))
		{
			$whr= array();
			$whr['user_id']=$_SESSION['UserId'];
			$whr['session_id'] = session_id();
			$whr['hour_out_flag'] = 0;
			
			$db->select("*","fd_logged_in_user",$whr);
			$totalRow = $db->num_rows();
			if($totalRow>0)
			{
				$UpdateArr = array();
				$UpdateArr['updated_on'] = $_SESSION['TIMEOUT'];
				$db->update('fd_logged_in_user',$UpdateArr,$whr);
			}
			else
			{
				$info = getLocationInfoByIp();
				$whr['updated_on'] = $_SESSION['TIMEOUT'];
				$whr['remote_ip']=$info['remote_ip'];
				$whr['browser']=$_SERVER['HTTP_USER_AGENT'];
				$whr['log_in_time']=date("Y-m-d H:i:s");
				$db->insert('fd_logged_in_user',$whr);
				
			}	
		}		
}
	
///Code for Chnage Session value if Session Hvaing a Blank Theam Value//////
	$ThemFlag=0;
	$cssSessionArr=array('client_logo','btn_color_code','tab_color','menu_color','banner_color_code','menu_hover_color','button_font_color','tabs_outline_color','button_hov_col','css_path');
		foreach($cssSessionArr as $key=>$val)	
		{
			if(!isset($_SESSION[$val]) ||  $_SESSION[$val]=='')
			{
				$ThemFlag=1;
				break;
			}
		}
		
		$loginFlag=0;
		if(isset($flg))
		{
			$loginFlag=$flg;
		}
		if(isset($_SESSION['key_username']) && $loginFlag==1)
		{
			$loginFlag=0;
		}
		if($ThemFlag==1 && $loginFlag!=1)
		{

			$db=new DB_Sql($_CONFIG);
				
			if(isset($_SESSION['clientid']) && $_SESSION['clientid']!=='' && isset($_SESSION['UserLevel']) && $_SESSION['UserLevel']!=='')
			{
			
			
				if($_SESSION['UserLevel']==0 || $_SESSION['UserLevel']==3)
				{
					$sql = "select cc.* from  fd_airlines_config as cc where cc.primary_comp='Y'";	
				}
				else
				{
					$sql = "select cl.ID,cc.* from fd_airlines as cl, fd_airlines_config as cc where  cl.ID=cc.airlines_id and cl.ID=".$_SESSION['clientid'];
				}
				$db->query($sql);
				
				while($db->next_record())
				{
					foreach($cssSessionArr as $key=>$val)
					{
						
						if($val!='css_path')
						{
							$_SESSION[$val]=$db->f($val);
						}
						else
						{
							$_SESSION[$val]=$db->f('client_sub_domain');
						}
					}
				}
			}
			else
			{
				
			}
			
			
		}
/////////////////////////////////////////////////////////////////////////////////
updateTimeoutCookie();
?>