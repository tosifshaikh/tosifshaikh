<?php
//$arr = array(array("category"=>"Fruit","name"=>"Apple","colour"=>"red"),array("category"=>"Bird","name"=>"parrot","colour"=>"green"),array("category"=>"bike","name"=>"pulser","colour"=>"black"));
$arr=array(

				array("id"=>1,"Name"=>"Fruit","p_id"=>0),
				array("id"=>3,"Name"=>"Apple","p_id"=>1),
				array("id"=>4,"Name"=>"pineapple","p_id"=>3),
				
				array("id"=>2,"Name"=>"Bird","p_id"=>0),
				array("id"=>5,"Name"=>"Peacock","p_id"=>2),
				array("id"=>6,"Name"=>"crow","p_id"=>5),
				
				array("id"=>7,"Name"=>"bike","p_id"=>0),
				array("id"=>8,"Name"=>"pulser","p_id"=>7),
				array("id"=>9,"Name"=>"CBR","p_id"=>8)
);
echo json_encode($arr);
?>