<?php
/*if(isset($_POST['submit']))
{

$to      = $_POST['mail'];
$subject = $_POST['sub'];
$message = 'hello';
//$headers = 'From: bhavikshah746@yopmail.com' . "\r\n" ;
$from = "From: FirstName LastName <bhavikshah746@yahoo.com>";


mail($to, $subject, $message, $from);
}*/


/*$name=$_POST['uname'];
$second=$_POST['txtpass'];
$abc=$_POST['txtpass'];

if(filter_var($name, FILTER_VALIDATE_EMAIL))
{
	echo "valid Email <br>";
}
else{echo "not valid Email <br>";}
if(filter_var($second, FILTER_VALIDATE_INT))
{
	echo "valid int <br>";
}
else{echo "not valid int <br>";}
if(filter_var($abc, FILTER_VALIDATE_URL))
{
	echo "valid url <br>";
}
else{echo "not valid url <br>";}
*/



$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["txtpass"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["txtpass"]);
  }

  if (empty($_POST["txtpass2"])) {
    $emailErr = "Password is required";
  } else {
    $email = test_input($_POST["txtpass2"]);
  }

  if (empty($_POST["uname"])) {
    $website = "";
  } else {
    $website = test_input($_POST["uname"]);
  }

  
}

?>
