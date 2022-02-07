<?php
// //get the last-modified-date of this very file
// $lastModified=filemtime(__FILE__);
// //get a unique hash of this file (etag)
// $etagFile = md5_file(__FILE__);
// //get the HTTP_IF_MODIFIED_SINCE header if set
// $ifModifiedSince=(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
// //get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
// $etagHeader=(isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

// //set last-modified header
// header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModified)." GMT");
// //set etag-header
// header("Etag: $etagFile");
// //make sure caching is turned on
// header('Cache-Control: public');
// //echo $lastModified.'--->'.strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']).'--->'.$etagHeader.'--->'.$etagFile;
// if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])==$lastModified || $etagHeader == $etagFile)
// {
//        header("HTTP/1.1 304 Not Modified");
//      //  exit;
// }
//?v=<?php echo  filemtime( 'mk\login.js' ) 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="cache" />
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="mk\login.js">
    </script>
    <!-- Bootstrap -->
   
  </head>
  <body>
    
  </body>
</html>
