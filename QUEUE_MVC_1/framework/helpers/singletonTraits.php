<?php 
/**
 * Singleton patter in php
 **/
trait SingletonTrait {
 private static $instances = array();
  public static function getInstance() 
    {
        $cls = get_called_class(); // late-static-bound class name
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }
        return self::$instances[$cls];

    }
}
?>