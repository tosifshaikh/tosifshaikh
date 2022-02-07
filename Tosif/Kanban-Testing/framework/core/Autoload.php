<?php
class AutoLoad{
    
    public function __construct()
    {
        $this->Run();
    }
    private function Run()
    {
       $this->init();
       $this->autoload();
       $this->dispatch();
    }
    private function defineConstant()
    {
       $URL = 'index';
        if (isset($_SERVER['PATH_INFO'])) {
             $URL= $_SERVER['PATH_INFO'];
             if (isset($URL) && $URL != '') {
                $params=explode('/',ltrim($URL,'/'));
            }
        }
        
       
        defined('DS') OR  define('DS', DIRECTORY_SEPARATOR);
        defined('BASEPATH') OR define('BASEPATH', $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI']);
        defined('CSS') OR  define('CSS', 'css' . DS);
        defined('IMG') OR  define('IMG', 'images' . DS);
        defined('APP') OR  define('APP', 'application' . DS);
        defined('FRAMEWORK') OR  define('FRAMEWORK', 'framework' . DS);
        defined('CORE_PATH') OR  define('CORE_PATH', FRAMEWORK . DS . 'core' . DS);
        defined('MODEL_PATH') OR  define('MODEL_PATH', APP . 'models' . DS);
        defined('VIEW_PATH') OR  define('VIEW_PATH', APP . 'views' . DS);
        defined('CONTROLLER_PATH') OR  define('CONTROLLER_PATH', APP . 'controllers' . DS);
        defined('CONTROLLER') OR  define('CONTROLLER', isset($params[0]) ? $params[0] :  $URL);
        defined('MODULE') OR  define('MODULE', isset($params[0]) ? $params[0] :  $URL);
        defined('ACTION') OR  define('ACTION', isset($params[1]) ? $params[1] :  $URL);
        defined('Current_Cont_PATH') OR  define('Current_Cont_PATH', CONTROLLER_PATH . MODULE . DS);
       
    }
  
    private function init() 
    {
       $this->defineConstant(); 
       //require_once CORE_PATH . "Controller.class.php";
       require_once CORE_PATH . "Singleton.php";
      // require_once CORE_PATH . "Singleton.class.php";
    }
    private function autoload()
    {
        spl_autoload_register(array(__CLASS__,'load'));
    }
    private function load($classname)
    {
       echo $classname;
      // echo Current_Cont_PATH;
        if (substr($classname,-10) == 'Controller') {
             require_once Current_Cont_PATH . $classname.'.class.php';
        } else {
            require_once CORE_PATH . $classname.'.php';
        }
    }
    private static function dispatch()
    {
        $controllerName = CONTROLLER . "Controller";
        $actionName = ACTION . "Action";
        $modelName=CONTROLLER.'Model';
        //SingletonClass
        $controller=SingletonClass::getInstance($controllerName);
       print '<pre>';print_r($controller);exit;
    }
}