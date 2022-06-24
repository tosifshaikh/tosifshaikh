<?php 
new Autoloader();
 class Autoloader {

       private $params;
       private $loader='';
       private $mainPath='';

       public function __construct(){
            
			ini_set('display_errors',1);
			error_reporting(-1);			
             $this->params=[];
             $this->mainPath='';
             $this->run();
       }
       public function run()
        {  
               $this->init();
               $this->autoload();
               $this->dispatch();
        }
        private function getPageURL()
        {
                //print '<pre>';print_r($_SERVER);exit;
				// Define path constants
                if(isset($_SERVER['SCRIPT_NAME'])){     
                
                        $this->mainPath=explode("/",ltrim($_SERVER['SCRIPT_NAME'],"/"));
                        array_pop($this->mainPath);
                        $this->mainPath=implode('/',$this->mainPath);
                }
                if(isset($_SERVER['PATH_INFO']))
                {
                         $URL= $_SERVER['PATH_INFO'];
                        if(isset($URL) && $URL!='')
                        {
                          $this->params=explode('/',ltrim($URL,'/'));
                        } 
                        if(isset($_REQUEST) && count($_REQUEST)>0)
                        {
                          $this->params = array_filter(array_merge($this->params,$_REQUEST));
                        }

                }else{
					//header("HTTP/1.1 301 Moved Permanently"); 
					exit('Please passs Parameters');
					header("Location: /505.html"); 
				}
                
        }
        private function defineConstant()
        { 
          defined("DS") OR  define("DS", DIRECTORY_SEPARATOR);
          defined("APP_PATH") OR define("APP_PATH", 'application' . DS);
          defined("CONFIG_PATH") OR define("CONFIG_PATH", APP_PATH . "config" . DS);
          defined("CONTROLLER_PATH") OR define("CONTROLLER_PATH", APP_PATH . "controllers" . DS);
          defined("MODEL_PATH") OR define("MODEL_PATH", APP_PATH . "models" . DS);
          defined("VIEW_PATH") OR define("VIEW_PATH", APP_PATH . "views" . DS);
          defined("PUBLIC_PATH") OR  define("PUBLIC_PATH","public" . DS);
          defined("FRAMEWORK_PATH") OR define("FRAMEWORK_PATH","framework" . DS);
		  defined("LOG_PATH") OR define('LOG_PATH', "logs" . DS);
          defined("HELPER_PATH") OR define("HELPER_PATH", FRAMEWORK_PATH . "helpers" . DS); 
          defined("CORE_PATH") OR define("CORE_PATH", FRAMEWORK_PATH . "core" . DS);
          defined("DB_PATH") OR define('DB_PATH', FRAMEWORK_PATH . "database" . DS);
          defined("LIB_PATH") OR define('LIB_PATH', FRAMEWORK_PATH . "libraries" . DS);
          define("PLATFORM", isset( $this->params[0]) ? $this->params[0] : 'home');//module or folder
          define("CONTROLLER", isset($this->params[0]) ? $this->params[0] : 'Index');//controller inside module
          define("ACTION", isset($this->params[1]) ? $this->params[1] : 'index');//method to call
          define('MAIN_FOLDER_PATH',$this->mainPath.'/');
          defined('REAL_PATH') OR define('REAL_PATH','https://'.$_SERVER['SERVER_NAME'].'/'.MAIN_FOLDER_PATH);
          defined("IMAGE_PATH") OR define("IMAGE_PATH", REAL_PATH.PUBLIC_PATH . 'images' . DS);
          defined("JS_PATH") OR  define("JS_PATH", REAL_PATH.PUBLIC_PATH . "js" . DS); 
          defined('MODULE_JS') OR define('MODULE_JS',REAL_PATH.VIEW_PATH.PLATFORM.DS);

//echo MODULE_JS;exit;
        }
        private function requireFile(){
               /* $filesArr=array(
                     CORE_PATH . "Common.php"
                );
                foreach($filesArr as $filePath){
                        if(file_exists($filePath)){
                                require_once $filePath;
                        }
                }*/
        }
        // Initialization
        private function init()
        { 
                $this->getPageURL();
                $this->defineConstant();
                $this->requireFile();
                $GLOBALS['config'] = require  CONFIG_PATH . "db.php";
        }
        // Here simply autoload app’s controller and model classes
      private function autoload(){
        $module=[CORE_PATH,LIB_PATH,APP_PATH,FRAMEWORK_PATH,CONTROLLER_PATH.CONTROLLER,MODEL_PATH];
        set_include_path(get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR,$module));
        spl_autoload_register('spl_autoload',false);
        
        }

        // Routing and dispatching
        private function dispatch(){


        // Instantiate the controller class and call its action method
        $controller_name = CONTROLLER . "Controller";
        $action_name = ACTION . "Action";
        $model_Name=CONTROLLER.'Model';
	// $this->loader=&getInstance('Loader');
	    $this->loader=singletonClass::getInstance('Loader');

        $this->loader->helper('Exception');
        //$controller=&getInstance($controller_name);
      //  echo $controller_name;
		$controller=singletonClass::getInstance($controller_name);
	//print '<pre>' ;print_r( $controller);echo $action_name."<br>";
  //echo implode(",",array_values($this->params));exit;
       if(method_exists($controller, $action_name)){
                call_user_func(array($controller,$action_name),array_values($this->params));
        }
    }
 }
?>