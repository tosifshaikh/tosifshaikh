<?php
class Controller{
    
    private static $instances = [];
    public function __construct() 
    {
            echo "1";
    }

    public function index() 
    {
        echo "2";
    }
     public Static function getInstance()
     {
         $cls = get_called_class(); // late-static-bound class name
         print '<pre>';print_r($cls);exit;
            if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
            }
            return self::$instances[$cls];
     }
}