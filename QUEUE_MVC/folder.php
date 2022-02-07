<?php 
$json = file_get_contents('build.json');
$folders = json_decode($json);

function buildDirs($folders, $path = null){
  $path = $path == null ? "" : $path . "/";

  foreach($folders as $key => $val){
     if(!is_dir($path.$val->name)){
	   mkdir($path.$val->name);
     }
	 echo "Folder: " . $path . $val->name . "<br>";

     if(!empty($val->files)){
        foreach($val->files as $file){
           //Create the files inside the current folder $val->name
          echo "File: " . $path . $val->name . "/" . $file->name . "<br>";
		
		   if($val->name && !file_exists($path . $val->name . "/". $file->name)){
			
			$fileDetail=pathinfo($file->name);
			 $extension=$fileDetail['extension'];			 
			   $data='';
			  if(strtolower($extension)=='html')
			  {
				   $data='<html>'.PHP_EOL;
				   $data.='<head></head>'.PHP_EOL;
				   $data.='<body></body>'.PHP_EOL;
				   $data.='</html>';
			  }
			   if(strtolower($extension)=='js')
			  {
				   $data='//javascript document'.PHP_EOL;
				  
			  }
			   if(strtolower($extension)=='php')
			  {
				   $data='<?php'.PHP_EOL;
				   if($file->file_type=='classfile')
				   {
					   $data .=' class '.$fileDetail['filename'].PHP_EOL;
					   
					   $data .='{ '.PHP_EOL;
					   
					   $data .='}'.PHP_EOL;
				   }
				   $data .='?>';
			   }
			   echo $path . $val->name . "/". $file->name;
           file_put_contents($path . $val->name . "/". $file->name, $data);
			
		   }
		}
     }

     if(!empty($val->folders)){ //If there are any sub folders, call buildDirs again!
        buildDirs($val->folders, $path . $val->name);
     }
  }
}
//print '<pre>';print_r($folders->folders);exit;
buildDirs($folders->folders); //Will build from current directory, otherwise send the path without trailing slash /var/www/here
?>