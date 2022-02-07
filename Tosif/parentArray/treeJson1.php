<?php

$arr=array("0"=>array("1"=>"abc","2"=>"xyz"),
           "1"=>array("3"=>"def","11"=>"jkl"),
		   "2"=>array("4"=>"ret","5"=>"asd","6"=>"fly1"),
		    "3"=>array("10"=>"def"));
		  
echo json_encode($arr);
?>