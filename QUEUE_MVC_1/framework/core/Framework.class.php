<?php 
/* 
A singleton is a particular kind of class that can be instantiated only once.
What "instantiated only once means?" It simply means that if an object of that class was already instantiated, the system will return it instead of creating new one. Why? Because, sometimes, you need a "common" instance (global one) or because instantiating a "copy" of an already existent object is useless.
*/
 class Framework {
        public static function run() {

                self::init();
                self::autoload();
                self::dispatch();
        }
        private static function defineConstant(){
                         // Define path constants
     
	    $URL= $_SERVER['PATH_INFO'];
		$expScript=explode("/",ltrim($_SERVER['SCRIPT_NAME'],"/"));
        if(isset($URL) && $URL!=''){
                $params=explode('/',ltrim($URL,'/'));
                 }
      //  define("ROOT", getcwd() . DS);
        define('CURRENT_WORK',getcwd());
        defined("DS") OR  define("DS", DIRECTORY_SEPARATOR);;
        defined("ROOT") OR define("ROOT", '');
		define('MAIN_FOLDER_PATH',$expScript[0].DS);
        defined("APP_PATH") OR define("APP_PATH", ROOT . 'application' . DS);
        defined("CONFIG_PATH") OR define("CONFIG_PATH", APP_PATH . "config" . DS);
        defined("CONTROLLER_PATH") OR define("CONTROLLER_PATH", APP_PATH . "controllers" . DS);
        defined("MODEL_PATH") OR define("MODEL_PATH", APP_PATH . "models" . DS);
        defined("VIEW_PATH") OR define("VIEW_PATH", APP_PATH . "views" . DS);
        defined("PUBLIC_PATH") OR  define("PUBLIC_PATH", ROOT . "public" . DS);
        defined("FRAMEWORK_PATH") OR define("FRAMEWORK_PATH", ROOT . "framework" . DS);
         defined("HELPER_PATH") OR define("HELPER_PATH", FRAMEWORK_PATH . "helpers" . DS); 
        defined("CORE_PATH") OR define("CORE_PATH", FRAMEWORK_PATH . "core" . DS);
        // define("PLATFORM", isset($_REQUEST['p']) ? $_REQUEST['p'] : 'home');//module or folder
        // define("CONTROLLER", isset($_REQUEST['c']) ? $_REQUEST['c'] : 'Index');//controller inside module
        // define("ACTION", isset($_REQUEST['a']) ? $_REQUEST['a'] : 'index');//method to call
        define("PLATFORM", isset( $params[0]) ?$params[0] : 'home');//module or folder
        define("CONTROLLER", isset($params[0]) ? $params[0] : 'Index');//controller inside module
        define("ACTION", isset($params[1]) ? $params[1] : 'index');//method to call
        define("CURR_CONTROLLER_PATH", CONTROLLER_PATH . PLATFORM . DS);
        define("CURR_VIEW_PATH", VIEW_PATH.PLATFORM.DS);
		defined('REAL_PATH') OR define('REAL_PATH',$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'].DS.MAIN_FOLDER_PATH);
        defined("IMAGE_PATH") OR define("IMAGE_PATH", REAL_PATH.PUBLIC_PATH . 'images' . DS);
	    defined('MODULE_JS') OR define('MODULE_JS',REAL_PATH.VIEW_PATH.PLATFORM.DS);
		defined("JS_PATH") OR  define("JS_PATH", REAL_PATH.PUBLIC_PATH . "js" . DS);
        }
        // Initialization
        private static function init() {

        self::defineConstant();
        require_once HELPER_PATH . "singletonTraits.php";
        require_once CORE_PATH . "Controller.class.php";
        require_once CORE_PATH . "Model.class.php";
        
        //require_once CORE_PATH . "View.class.php";
        $GLOBALS['config'] = require_once CONFIG_PATH . "config.php";
        }
        private static function autoload(){
        spl_autoload_register(array(__CLASS__,'load'));
        }
        // Define a custom load method
        private static function load($classname){
        // Here simply autoload app’s controller and model classes

        if(substr($classname,-10)=='Controller')
        {
                require CURR_CONTROLLER_PATH . "$classname.class.php";
        }if (substr($classname, -5) == "Model"){
                // Model
               require MODEL_PATH . "$classname.class.php";
            }
           
        // Here simply autoload app’s controller and model classes
        }
        // Routing and dispatching
        private static function dispatch(){
        // Instantiate the controller class and call its action method
        $controller_name = CONTROLLER . "Controller";
        $action_name = ACTION . "Action";
        $model_Name=CONTROLLER.'Model';
       // $model=new self::$modelName();
 
        //singleTonClass Object
        $model=$model_Name::getInstance();
        $controller = $controller_name::getInstance();
		$controller->setValues($model);
        //singleTonClass Object
      //  $controller = $controller_name::getInstance();
  
        //$controller->$action_name();
       $params=(count($_REQUEST)>3)?$_REQUEST:array();
	  // call_user_func_array([$controller,$action_name],$params);
      /// print_r( $params);
        //if(file_exists(CONTROLLER.$action.'.php')){
               // $controller=new $controller;
               // if(method_exists($controller,$action_name)){
                        //$params will be same as index(param1,params2) or //$controller->index($param1,$params2);
                      //  call_user_func_array([$controller,$action_name],$params);
               // }else{
                      //  exit('No Parameters');
               // }


       // }

        
        }
 }
?>