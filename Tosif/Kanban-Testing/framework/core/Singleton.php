<?php 
/* 
A singleton is a particular kind of class that can be instantiated only once.
What "instantiated only once means?" It simply means that if an object of that class was already instantiated, the system will return it instead of creating new one. Why? Because, sometimes, you need a "common" instance (global one) or because instantiating a "copy" of an already existent object is useless.
*/
class SingletonClass
{
    private static $_instances = [];
	public static function getInstance($class,$params=null)
	{
		//static $_instances=array();
		if(!isset(self::$_instances[$class]))
		{
			self::$_instances[$class] =new $class($params);
		}
		return self::$_instances[$class];
	}
	
	public function destroy(){
		self::$instance = NULL;
	}
	// Clone method as private so by mistake developer not crate
	//second object of the User class with the use of clone.
	private function __clone()
	{
	}
}
?>