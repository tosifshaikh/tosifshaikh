<?php 
class Model
{
        protected $db; //database connection object
        protected $loader;
        public function __construct()
      {
        $this->loader=singletonClass::getInstance('Loader');
		//$this->loader=&getInstance('Loader');
		$this->loader->library('pdohelper');
        $this->loader->library('pdowrapper');

       // database connection setings
                $dbConfig = array(
                        "host"=>isset($GLOBALS['config']['host'])?$GLOBALS['config']['host'] : 'localhost', 
                        "dbname"=>isset($GLOBALS['config']['dbname'])? $GLOBALS['config']['dbname'] : '', 
                        "username"=>isset($GLOBALS['config']['user'])? $GLOBALS['config']['user'] : 'root', 
                        "password"=>isset($GLOBALS['config']['password'])? $GLOBALS['config']['password'] : '',
                        "port" =>isset($GLOBALS['config']['port'])?$GLOBALS['config']['port'] : '3306'
                        );
                //get instance of PDO Wrapper object
               $this->db= new PdoWrapper($dbConfig); 
       
       // $this->db = new Mysql($dbconfig);
      }
}
?>