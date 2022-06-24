<?php
set_error_handler('ErrorHandler',E_ALL);
register_shutdown_function('FatalErrorHandler');
function log_error($msg){
	 
	 $execption=singletonClass::getInstance('ExceptionClass');
	//$execption=&getInstance('ExceptionClass');
	//$execption->throwError($msg);
	//print_r($path);exit;
	 //$path::getInstance();
	// print '<pre>';
	// print_r($this->db);
	// echo 'insdie error111122222222222224444';exit;
}
function logger($message,$logFile = "error.log"){
	return file_put_contents($logFile, $message, FILE_APPEND); 
}
function ErrorHandler($errno, $errstr, $errfile, $errline){
	print '<pre>';
	echo 'ErrorHandler==>';
	print_r($errno);
	print '<br />';
	print_r($errstr);
	print '<br />';
	print_r($errfile);
	print '<br />';
	print_r($errline);
	print '<br />error_reporting';
	print_r(get_defined_constants(true));
	//print_r(debug_backtrace());
	$date = date("Y-m-d h:m:s");
	$erroMSG='ErrorNO=>'.$errno.', ErrroStr=>'.$errstr.' , ErrorFile=>'.$errfile.' , ErrorLine=>'.$errline;
	$message = "[{$date}] [{$erroMSG}]".PHP_EOL;
	
	//print_r(error_reporting());
	logger($message,LOG_PATH.DS.'error.log');
}
function FatalErrorHandler(){
	//print '<br />';
	//echo 'FatalErrorHandler==>';
	$error=error_get_last();
	if (!empty($error) &&  (in_array($error['type'], array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE), TRUE))){
	print '<br />';
	echo 'inerrorhandler==>';
	print_r($error);
	echo "\nFatal error: {$error['message']} in {$error['file']} on line {$error['line']}\n";
	}
}

?>