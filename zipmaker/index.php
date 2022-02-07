<?php
if(isset($_REQUEST['act']) && $_REQUEST['act']=="ZIP")
{
    # create new zip opbject
    $zip = new ZipArchive();

    # create a temp file & open it
    $tmp_file = tempnam('.','');
    $zip->open($tmp_file, ZipArchive::OVERWRITE);
    # loop through each file
   $t=explode("\n",$_REQUEST["txtArea"]);
    foreach($t as $file){

   //echo $file;
	 $str1=str_replace("\\","\\\\",$file);
        #add it to the zip
     // $zip->addfile("C:\\Users\\Jarv!s\\Downloads\\USBWebserver v8.6\\root\\my\\test\\zip maker\\file.php");

	   $zip->addfile($str1,basename($str1));//rename folder files with another name
	 // $zip->addfile($str1);
    }

    # close zip
    $zip->close();
	$zip_name="test.zip";
    # send the file to the browser as a download
	    header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="'.$zip_name.'"');
	    flush();
        readfile($tmp_file);
	    unlink($tmp_file);
	    exit;
		// }
}
 ?>