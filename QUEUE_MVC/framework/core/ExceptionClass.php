<?php
class ExceptionClass extends model{

	 protected $db;
	 public function __construct(){
		 //set_error_handler('ErrorHandler',E_ALL);
		//set_error_handler('ErrorHandler');
		//set_exception_handler('ExceptionHandler');
		//set_exception_handler('ErrorHandler');
		//register_shutdown_function('ErrorHandler');
	 }
	 public function ErrorHandler($errno){
		print 'ErrorHandler-->';
		print_r($errno);
	 }

}
?>