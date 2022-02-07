<?php
/*set_error_handler('ErrorHandler',E_ALL);
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
function logger($message){
	$logFile=LOG_PATH.DS.'error_'.date("d-m-Y").'.log';
	$fp=fopen($logFile, 'a+');
	fwrite($fp, $message);
	fclose($fp);
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
	$date = date("d-m-Y h:m:s");
	$erroMSG='ErrorLine=>'.$errline.', ErrroStr=>'.$errstr.' , ErrorFile=>'.$errfile ;
	$message = "[{$date}] [{$erroMSG}]".PHP_EOL;
	
	//print_r(error_reporting());
	logger($message);
}
function FatalErrorHandler(){
	print '<br />';
	echo 'FatalErrorHandler==>';exit;
	$error=error_get_last();exit;
	if (!empty($error) &&  (in_array($error['type'], array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE), TRUE))){
	print '<br />';
	echo 'inerrorhandler==>';
	print_r($error);
	echo "\nFatal error: {$error['message']} in {$error['file']} on line {$error['line']}\n";
	}
}
*/
function phpErrorHandler($errno, $errstr, $errfile, $errline, $errcontext){
echo 'in111';exit;
    //If is a notice do nothing.
    if($errno == E_NOTICE | $errno == E_USER_NOTICE): null; else:

    //Error types
    $errortype = array(
      E_ERROR           => 'Error',
      E_WARNING         => 'Warning',
      E_PARSE           => 'Parsing Error',
      E_NOTICE          => 'Notice',
      E_CORE_ERROR      => 'Core Error',
      E_CORE_WARNING    => 'Core Warning',
      E_COMPILE_ERROR   => 'Compile Error',
      E_COMPILE_WARNING => 'Compile Warning',
      E_USER_ERROR      => 'User Error',
      E_USER_WARNING    => 'User Warning',
      E_USER_NOTICE     => 'User Notice',
      E_STRICT          => "Runtime Notice");

    //Just get the filename for the title.
    $filenameGoBoom = explode('/',print_r( $errfile, true));
    $filename = end($filenameGoBoom);

    //MySQL
    if ($errstr == "(SQL)"):
        $errstr = "SQL Error [$errno] " . SQLMESSAGE . "<br />\n";
        $errstr .= "Query : " . SQLQUERY . "<br />\n";
        $errstr .=  "On line " . SQLERRORLINE . " in file " . SQLERRORFILE . " ";
    endif;

    //Write the report and set out the data
    $prefix = ($errortype[$errno]) ? $errortype[$errno] : "Unknown";
    $title = ($errortype[$errno]) ? $filename ." on line ". print_r( $errline, true) : "Unknown error type: [$errfile:$errline] [$errno] $errstr";
    $error = "\n\n---Data---\n". print_r( $errstr, true).
    "\n\n---File---\n". print_r( $errfile, true). "on line ". print_r( $errline, true)." @".date('Y-m-d H:i:s').
    "\n\n---Context---\n".print_r( $errcontext, true).
    "\n\n". print_r( debug_backtrace(), true);

        //Send! Show the error and stop executing the code
        sendError($prefix, $title , $error);

        echo "Whoops, something went wrong, it could of been something you did, or something we've done or didn't expect. Please try again, this error has been reported to us.".$errline.$errstr;

        die();

    endif;
}

function mysqlErrorHandler(){

    //Setup variables.
    $prefix = 'MySQL';
    $title = 'Error - '.mysql_errno();
    $error = 'MySQL Error '.mysql_errno().':'.mysql_error();

    //Send! 
    sendError($prefix,$title,$description);

    //Kill the script
    die();

}

function fatalErrorHandler(){

    $isError = false;echo 'in sendError';exit;
    if ($error = error_get_last()):
        switch($error['type']){
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            $isError = true;
            break;
            }
        endif;

    if ($isError):
        $prefix = 'Fatal';
        $title = 'Check this out';
        $error = $error['message'];

        sendError($prefix, $title , $error);

        echo "Whoops, something went wrong, it could of been something you did, or something we've done or didn't expect. Please try again, this error has been reported to us.";

    endif; 
}

set_error_handler('phpErrorHandler');
register_shutdown_function('fatalErrorHandler');
?>