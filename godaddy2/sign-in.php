<?php ob_start();
////error_reporting(E_ALL);
////ini_set('display_errors', 1);
//echo "test";exit;
if(!isset($_SESSION)) 
{ 
	session_start(); 
} 

if(!empty($_SESSION["custvalid"]) && $_SESSION["custvalid"]==1)
	header('location:my-account.php');
$ErrMsg="";
$Email="";
$Pass="";
require_once("includes/connection.php");

if (!empty($_POST))
{
	$Email = (isset($_POST["Email"]) && $_POST["Email"] != "") ? $_POST["Email"] : '';
	$Pass = (isset($_POST["Password"]) && $_POST["Password"] != "") ? $_POST["Password"] : '';
		
		$strUserCheck = "SELECT * FROM `customer` WHERE email = '". mysqli_real_escape_string($con,$Email) ."' and custpass='". mysqli_real_escape_string($con,$Pass) ."' and authenticated = 0 ";
		$result=$con->query($strUserCheck);
		if($result->num_rows>0)
		{
			$sql="UPDATE  customer set authenticated=1 where email = '". mysqli_real_escape_string($con,$Email) ."' and custpass='". mysqli_real_escape_string($con,$Pass) ."' and authenticated = 0";
			$result2=$con->query($sql);
		}
		$strUserCheck = "SELECT * FROM `customer` WHERE email = '". mysqli_real_escape_string($con,$Email) ."' and custpass='". mysqli_real_escape_string($con,$Pass) ."' and authenticated = 1 "; 
		
		$tarray = mysqli_query($con,$strUserCheck);
		$tot = mysqli_fetch_array($tarray);
		if(!empty($tot))
		{
				$custid = $tot["id"];
				$_SESSION["custid"]=$custid;
				$_SESSION["custvalid"]=1;
				$_SESSION["custname"]=$tot["FullName"];
				if(!empty($_GET))
				{
					$rediurl= (isset($_GET["rediurl"]) && $_GET["rediurl"] != "") ? $_GET["rediurl"] : '';
					
					if($rediurl != '')
				  header( 'Location:'.$rediurl) ;
				  else
				  header( 'Location:my-account.php') ;
				}
				  else
				  header( 'Location:my-account.php') ;
		}
		else{
			$ErrMsg = "<p><strong>Invalid Username / Password Combination.<br/>Please Try again.</strong></p>";
		}
}
else if(!empty($_GET)){
	$ErrMsg = (isset($_GET["msg"]) && $_GET["msg"] != "") ? $_GET["msg"] : '';
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="favicon.ico">
<title>Zip Corc</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/ma5-mobile-menu.css" rel="stylesheet" type="text/css" />
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="js2/html5shiv.min.js" async></script>
      <script src="js/respond.min.js" async></script>
    <![endif]-->
</head>
<body>
<div class="container-fluid">
  <div class="row visible-xs mobcalltoaction">
    <div class="col-xs-10" id="icon-phoneno"><i class="fa fa-phone" aria-hidden="true"></i> 865-323-7309</div>
    <div class="col-xs-2" id="icon-fb"><a href="https://www.facebook.com/Zipcroc-1898590123719467/?ref=bookmarks" target="_blank"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></div>
  </div>
</div>
<!-- TOP and Secondary Navigation -->
<div class="container">
  <heder>
    <div class="row">
      <div class="col-xs-12 col-sm-3 col-md-3 complogo"><a href="index.html"><img src="images/zipcroc.png" alt="zipcroc" /></a></div>
      <div class="col-xs-12 col-sm-9 col-md-9 hidden-xs">
      
        <div class="row calltoaction">      
          <div id="icon-phoneno" class="col-sm-10">
            <div><span><i class="fa fa-phone" aria-hidden="true"></i> 865-123-4567</span></div>
          </div>
          <div id="icon-fb" class="col-sm-2"><span><a href="https://www.facebook.com/Zipcroc-1898590123719467/?ref=bookmarks" target="_blank"><img src="images/icon-fb.png" width="25" height="25"></a></span></div>            
        </div>
        
        <div id="mainnavi">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-12">        
                <!-- Main Navigation -->
                <nav class="navbar navbar-default">
                  <div class="container-fluid">
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                        <div id="navbar" class="navbar-collapse collapse">
                          <ul class="nav navbar-nav">
                            <li class="active dropdown"> <a href="#">SIGN IN</a></li>
                            <li class="dropdown"><a href="sign-up.php" style="padding-right:0px;">SIGN UP</a></li>
                          </ul>
                        </div>
                    </div>
                  </div>
                </nav>
                <!--/ .Navigation -->  
              </div>                  
            </div>                    
          </div>                 
        </div>
        
      </div>
    </div>
    
    <div role="navigation" class="hidden-sm hidden-md hidden-lg">
      <div class="row ma5-page"> <a class="ma5-toggle-menu cd-nav-trigger hidden-sm hidden-md hidden-lg " href="#!"><span class="cd-nav-icon"></span> <svg x="0px" y="0px" width="54px" height="54px" viewBox="0 0 54 54"><circle fill="transparent" stroke="#656e79" stroke-width="1" cx="27" cy="27" r="25" stroke-dasharray="157 157" stroke-dashoffset="157"></circle></svg> Navigation </a>
        <div class="ma5-menu-mobile"> 
          <!-- Left nav -->
          <ul class="  ma5-ul">
            <li class="ma5-li-1"><a href="#">SIGN IN</a></li>
            <li class="ma5-li-2 "><a href="sign-up.php">SIGN UP</a></li>
          </ul>
        </div>
      </div>  
	</div>  
   
  </heder>
</div>
<!-- Inner Teaser -->
<div id="inrcontent1">
  <div class="container">
  	<div class="row">
      <div class="col-sm-12">
      	  <h2>Already have an Account<br>Sign In</h2>
      </div>       
    </div>
    <div class="row"> 
      <div class="hidden-xs col-sm-2 col-md-3">&nbsp;</div>
      <div class="col-xs-12 col-sm-8 col-md-6">       
      	<div class="teaser">
        	<div>     
				<?php echo $ErrMsg."<br/>"; ?>    
            	<form action="sign-in.php" method="post">
                    <input type="text" class="form-control" required id="Email" name="Email" placeholder="Email Address" value="<?php echo $Email; ?>" aria-label="Enter your Email Address" title="Enter your Email Address">
                    <input type="password" class="form-control" required id="Password" name="Password" placeholder="Password" value="<?php echo $Pass; ?>" aria-label="Enter your Password" title="Enter your Password">                    
                    <button type="submit" class="button" value="Submit"><span>Sign In</span></button>                	
                </form>    	           
                <p><a href="forgot-password.php">Forgot password?</a></p>
                <p>If your are a New User then? <br class="visible-xs"/><a href="sign-up.php">Click here to sign up</a></p>
            </div>
        </div>
      </div> 
      <div class="hidden-xs col-sm-2 col-md-3">&nbsp;</div>     
    </div>
  </div>
</div>

<!-- Footer -->
<div id="footer">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 footnavi"><a href="about-us.html">ABOUT US</a>  |  <a href="how-it-works.html">FAQs</a>  |  <a href="customer-services.html">CUSTOMER SERVICES</a>  |  <a href="our-mission.html">OUR MISSION</a>  |  <a href="oppnings.html">BECOME A DRIVER</a></div>
      <div class="col-xs-12 copytxt">Copyright &copy; 2017 Zipcroc, All Rights Reserved.<br /><br /><b>WE ACCEPT</b></div>
      <div class="col-xs-12 weaccept"><img src="images/payment.png" /></div>
    </div>
  </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/main.js"></script> 
<script src="js/ma5-mobile-menu.js"></script>  
<script>
	$(document).ready(function () {
	    function isTouchDevice() {

	        $.getJSON("checkuser.php", function (data) {
	            $("#navbar ul li").remove();
	            $("#navbar ul").append(data["web"]);
	            $(".ma5-menu-mobile ul li").remove();
	            $(".ma5-menu-mobile ul").append(data["mob"]);
	        });

			return 'ontouchstart' in window
		};
		if (isTouchDevice()) {
			$("body").swipe({
				swipe: function (event, direction, distance, duration, fingerCount, fingerData) {
					if (direction == 'left') { $('html').removeClass('ma5-menu-active'); }
					if (direction == 'right') { $('html').addClass('ma5-menu-active'); }
				},
				allowPageScroll: "vertical"
			});
		};

		$.getJSON("checkuser.php", function (data) {
		    $("#navbar ul li").remove();
		    $("#navbar ul").append(data["web"]);
		    $(".ma5-menu-mobile ul li").remove();
		    $(".ma5-menu-mobile ul").append(data["mob"]);
		});
	});
</script>
</body>
</html>