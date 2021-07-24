<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sql demo</title>

</head>
<?php
//$path="tos/";
   //$uploadfile = $path.basename();
if(isset($_POST['submit']))
{
	$_SESSION['username']="jaim";
	if(isset($_SESSION['username']))
	{
		echo $_SESSION['username'];
	}
	move_uploaded_file($_FILES['myFile']['tmp_name'],"D:/".$_FILES['myFile']['name']);
}

?>
<body>
<form method="post" enctype="multipart/form-data">
<!--<input type="text" name="text" value="" />-->
<input type="file" name="myFile" id="myFile" />
<input type="submit" name="submit" value="submit"  />
</form>
</body>
</html>
<?php
   if (!isset($_COOKIE['Ordering'])) {
        setcookie("Ordering", $_POST['ChangeOrdering'], time() + 31536000);
    }
?>
<form method="post" action=""> Reorder messages:
<select name="ChangeOrdering">
<option value="DateAdded ASC">Oldest first</option>
<option value="DateAdded DESC">Newest first</option>
<option value="Title ASC">By Title, A-Z</option>
<option value="Title DESC">By Title, Z-A</option>
</select>
<input type="submit" value=" Save Settings " />
</form>
<?php
//ob_end_clean();
//$arr="sadjvhfdlsf";
//$a=10;
//$b=20;
//$A=50;
//$a="";
//$s=null;
//$arr=array(10,"20","abdsfc",10.2);
//$name="jaimin";
//echo "my name is $name"."<br>";
//print "print"." my name is $name ,$a " ."<br>";
//echo "single quote".'my name is $name'."<br>";
////print "print","statement";
//echo "print","one string"."<br>";
// var_dump($arr);
// class Car {
//    function car(){
//         $this->model = "VW";
//	}
//}
//$herbie=new Car();
// echo $herbie->model."<br>";
//var_dump($s);
//
//$str="<br>hello  world   how are u world<br>";
////echo strlen($str);
////echo str_word_count($str);
////echo strrev($str);
////echo strpos($str,"how");//retuns only first occurances
//echo str_replace("l","h",$str);//repalces all occurances
//define("name","flydocs");
//echo name;
//$txt1 = "Hello";
//$txt2 = " world!";
//echo $txt1 .= $txt2;
//$arr3=array("abc","xyz","jkl");
//foreach($arr3 as $key=>$val)
//{
//	echo "<br>".$key.$val."<br>";
//}
//$cars = array("Volvo", "BMW", "Toyota","Polo");
////$cars = array("name"=>"Volvo","abc"=> "BMW", 3=>"Toyota",2=>"Polo");
////$cars = array("name"=>"Volvo","abc"=> "BMW", "xyz"=>"Toyota","my"=>"Polo");
////sort($cars);
//asort($cars);
//ksort($cars);
//arrray(),array_change_key_case()
//print_r(array_change_key_case($cars,CASE_UPPER));
//If two or more keys will be equal after running array_change_key_case() 
//print_r(array_chunk($cars,1));
//var_dump($cars);
//$clength=count($cars);
//for($x = 0; $x <  $clength; $x++) {
//     echo $cars[$x];
//     echo "<br>";
//}
//$a2=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
//$a1=array("e"=>"red","f"=>"black","g"=>"purple");
//$a3=array("a"=>"red","b"=>"black","h"=>"yellow");
//
//$result=array_diff($a1,$a2,$a3);
//print_r($result);
//$a1=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
//$a2=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"green");
//
//$result=array_diff_assoc($a1,$a2);
//print_r($result);
//$a=array("Peter","41","USA");
//print_r(array_keys($a));
//array_multisort($cars,SORT_ASC,SORT_STRING);
//print_r($cars);
//$a1=array(1=>"red",2=>"green");
//$a2=array(1=>"blue",2=>"yellow");
//print_r(array_merge_recursive($a1,$a2));
//echo "<br>";
//$str="hello welcome to my world";
//$substr=explode(" ",$str);
//print_r($substr);
//echo substr_compare("Hello world","Hello worlrtd",1,-1);
//$cookie_name = "user";
//$cookie_value = "jaimin Doe";
//setcookie($cookie_name, $cookie_value, time()-3600); // 86400 = 1 day
//
//if(!isset($_COOKIE[$cookie_name])) {
//    echo "Cookie named '" . $cookie_name . "' is not set!";
//} else {
//    echo "Cookie '" . $cookie_name . "' is set!<br>";
//    echo "Value is: " . $_COOKIE[$cookie_name];
//}
//$a1=array("a"=>"red","b"=>"green","c"=>"blue","d"=>"yellow");
//$a2=array("a"=>"purple","b"=>"orange");
//array_splice($a1,0,2,$a2);
//print_r($a1);

//$data = '//data fields condensed into a single string obtained from email or txt';
//$filename= "output.php";
//chmod("output.php",7777);
//if (file_exists($filename)){
//$newFile= fopen($filename, 'w+');
//fwrite($newFile, $data);
//fclose($newFile);
//} else {
//$newFile= fopen($filename, 'w+');
//fwrite($newFile, $data);
//fclose($newFile);
//}
//ob_start();
//echo "yyuytu ";
//echo "hello";
//$out=ob_get_contents();
//ob_end_clean();

//echo $out;


  ?>
