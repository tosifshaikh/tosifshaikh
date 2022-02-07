<?php 
class Mysql{
	//use commonTraits;
        protected $db=null; //sql statement
        /**
        * Constructor, to connect to database, select database and set charset
        @param $config string configuration array
*/
	 public function __construct($config = array()){

                // $this->loader= Loader::getInstance();
                // $this->loader->library('pdowrapper');
                 // database connection setings
                $dbConfig = array(
                        "host"=>isset($config['host'])? $config['host'] : 'localhost', 
                        "dbname"=>isset($config['dbname'])? $config['dbname'] : '', 
                        "username"=>isset($config['user'])? $config['user'] : 'root', 
                        "password"=>isset($config['password'])? $config['password'] : '',
                        "port" =>isset($config['port'])? $config['port'] : '3306'
                        );
                // get instance of PDO Wrapper object
              //  $this->db= new PdoWrapper($dbConfig);        
               
        }
}
?>