<?php ob_start();
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	if(!isset($_SESSION)) 
	{ 
		session_start(); 
	} 

	if(empty($_SESSION["custvalid"]) || $_SESSION["custvalid"]!=1)
		header('location:sign-in.php');

	require_once("includes/connection.php");

	$cust_id = (isset($_SESSION["custid"]) && $_SESSION["custid"] != "") ? $_SESSION["custid"] : '';

	if($cust_id == '')
		header('location:sign-in.php');

	$custAdd1 = "";
	$custLink1 = "";
	$custAdd2 = "";
	$custLink2 = "";
	$custAdd3 = "";
	$custLink3 = "";
	$custAdd4 = "";
	$custLink4 = "";

	$strUserCheck = "SELECT * FROM `customeraddress` WHERE cust_id = '". mysqli_real_escape_string($con,$cust_id) ."' "; 
	$dbRes = mysqli_query($con,$strUserCheck);
	$tarray = mysqli_fetch_array($dbRes);
	if(!empty($tarray))
	{
		$custAdd1 = ltrim(rtrim($tarray["address1"]));
		$custLink1 = ltrim(rtrim($tarray["addLink1"]));

		$custAdd2 = ltrim(rtrim($tarray["address2"]));
		$custLink2 = ltrim(rtrim($tarray["addLink2"]));

		$custAdd3 = ltrim(rtrim($tarray["address3"]));
		$custLink3 = ltrim(rtrim($tarray["addLink3"]));

		$custAdd4 = ltrim(rtrim($tarray["address4"]));
		$custLink4 = ltrim(rtrim($tarray["addLink4"]));
	}

	$msg="";
	$tab ="";
	if (!empty($_GET))
	{
		$msg="<p><strong>".((isset($_GET["msg"]) && $_GET["msg"] != "") ? $_GET["msg"] : '') ."</strong></p>";
		if(isset($_GET["typeid"]))
			$tab = (int)$_GET["typeid"];
		else
			$tab = 0;

	}

	$tabsel1="";$pagesel1="";$tabsel2="";$pagesel2="";$tabsel3="";$pagesel3="";$tabsel4="";$pagesel4="";$tabsel5="";$pagesel5="";
	if($tab == 1)
	{
		$tabsel1=" active";$pagesel1=" active";
	}
	else if($tab == 2)
	{
		$tabsel2=" active";$pagesel2=" active";
	}
	else if($tab == 3)
	{
		$tabsel3=" active";$pagesel3=" active";
	}
	else if($tab == 4)
	{
		$tabsel4=" active";$pagesel4=" active";
	}
	else if($tab == 5)
	{
		$tabsel5=" active";$pagesel5=" active";
	}
	else
	{
		$tabsel1=" active";$pagesel1=" active";
	}


	$FullName = "";
	$Contact="";
	$Alternate="";
	$Email="";
	$Zip = "";
	$strUserCheck = "SELECT * FROM `customer` WHERE id = '". mysqli_real_escape_string($con,$cust_id) ."' "; 
	$dbRes = mysqli_query($con,$strUserCheck);
	$tarray = mysqli_fetch_array($dbRes);
	if(!empty($tarray))
	{
		$FullName = $tarray["FullName"];
		$Contact = $tarray["phone"];
		$Alternate = $tarray["alternatecontact"];
		$Email = $tarray["email"];
		$Zip = $tarray["zipcode"];
	}
	
	$oldpws=(isset($_SESSION["oldpws"]) && $_SESSION["oldpws"] != "") ? $_SESSION["oldpws"] : '';
	$newpws=(isset($_SESSION["newpws"]) && $_SESSION["newpws"] != "") ? $_SESSION["newpws"] : '';
	$retypepws=(isset($_SESSION["retypepws"]) && $_SESSION["retypepws"] != "") ? $_SESSION["retypepws"] : '';
	

	$strOrdrs = "SELECT * FROM `customerordermaster` WHERE cust_id = '". mysqli_real_escape_string($con,$cust_id) ."' order by id desc"; 
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
                            <li class="active dropdown"> <a href="#">Welcome <?php echo $_SESSION["custname"]; ?>,</a></li>
                            <li class="dropdown"><a href="sign-out-proc.php" style="padding-right:0px;">Sign Out</a></li>
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
      <div class="row ma5-page"> <a class="ma5-toggle-menu cd-nav-trigger hidden-sm hidden-md hidden-lg" href="#!"><span class="cd-nav-icon"></span> <svg x="0px" y="0px" width="54px" height="54px" viewBox="0 0 54 54"><circle fill="transparent" stroke="#656e79" stroke-width="1" cx="27" cy="27" r="25" stroke-dasharray="157 157" stroke-dashoffset="157"></circle></svg> Navigation </a>
        <div class="ma5-menu-mobile"> 
          <!-- Left nav -->
          <ul class="  ma5-ul">
            <li class="ma5-li-1"><a href="#"><b>Welcome <?php echo $_SESSION["custname"]; ?></b></a></li>          
            <li class="ma5-li-2<?php echo $tabsel1;?>"><a href="#placeorder" data-toggle="tab" class="ma5-toggle-menu">PLACE YOUR ORDER</a></li>
            <li class="ma5-li-3<?php echo $tabsel2;?>"><a href="#trackyourorder" data-toggle="tab" class="ma5-toggle-menu">TRACK YOUR ORDER</a></li>
            <li class="ma5-li-4<?php echo $tabsel3;?>"><a href="#uraddress" data-toggle="tab" class="ma5-toggle-menu">YOUR ADDRESS</a></li> 
            <li class="ma5-li-5<?php echo $tabsel4;?>"><a href="#profile" data-toggle="tab" class="ma5-toggle-menu">YOUR PROFILE</a></li> 
            <li class="ma5-li-6<?php echo $tabsel5;?>"><a href="#changepws" data-toggle="tab" class="ma5-toggle-menu">CHANGE PASSWORD</a></li>    
            <li class="ma5-li-7"><a href="sign-out-proc.php">SIGN OUT</a></li>
          </ul>
        </div>
      </div>  
	</div>  
   
  </heder>
</div>
<!-- Inner Teaser -->
<div id="tabcont">
  <div class="container">
  <?php echo $msg;?>
  	<div class="row">
        <div class="col-sm-2 tb hidden-xs"> 
          <ul class="nav nav-tabs tabs-left">
            <li class="<?php echo $tabsel1;?>"><a href="#placeorder" data-toggle="tab">Place your Order</a></li>
            <li class="<?php echo $tabsel2;?>"><a href="#trackyourorder" data-toggle="tab">Track your Order</a></li>            
            <li class="<?php echo $tabsel3;?>"><a href="#uraddress" data-toggle="tab">Your Address</a></li>
            <li class="<?php echo $tabsel4;?>"><a href="#profile" data-toggle="tab">Your Profile</a></li>
            <li class="<?php echo $tabsel5;?>"><a href="#changepws" data-toggle="tab">Change Password</a></li>                                      
          </ul>
        </div>
        <div class="col-sm-10 tbcont">
            <div class="tab-content">
              <div class="tab-pane <?php echo $pagesel1;?>" id="placeorder">
              	<h2>Place your order</h2>
                <form action="customer-order-proc.php" method="POST">              
                	<div class="row selectplace">
                        <div class="col-sm-6 pickup">
                            <h5>Pickup From</h5>
                              <input type="text" class="form-control" required id="Store" name="Store" placeholder="Store Name" aria-label="Enter Store Name" title="Enter Store Name" />
                              <textarea class="form-control" id="SAddress" name="SAddress" title="Enter Store Address" rows="6" required="required" placeholder="Store Address" aria-label="Enter Store Address"></textarea>
                              <input type="text" class="form-control" id="GMaplink" name="GMaplink" placeholder="Share Google Map link" aria-label="Share Google Map link" title="Share Google Map link" />                                                                       
                        </div>
                        <div class="col-sm-6 delivery">
                            <h5>Deliver To</h5>
                              <select type="text" class="form-control" required id="Address" name="Address">
                                <option>Select from Stored Addres</option>
								<?php if($custAdd1 != ""){ ?>
                                <option value="1">Address 1</option>
								<?php }?>
								<?php if($custAdd2 != ""){ ?>
                                <option value="2">Address 2</option>
								<?php }?>
								<?php if($custAdd3 != ""){ ?>
                                <option value="3">Address 3</option>
								<?php }?>
								<?php if($custAdd4 != ""){ ?>
                                <option value="4">Address 4</option>                          
								<?php }?>
                              </select>
                              <textarea class="form-control" id="CAddress" title="Write New Address" name="CAddress" rows="6" required="required" placeholder="Write New Address" aria-label="Write New Address"></textarea>
                              <input type="text" class="form-control" id="CGMaplink" name="CGMaplink" placeholder="Share Google Map link" aria-label="Share Google Map link" title="Share Google Map link" />                                                                                                
                        </div>                    
                    </div>
                    <div class="row selcttime">  
		                <h5>Select Delivery Slots</h5>                             
                        <div class="col-sm-4"><input type="radio" name="deliverySlots" id="optionsRadios1" value="9:00 am - 10:00 am" /> 9:00 am - 10:00 am</div>
                        <div class="col-sm-4"><input type="radio" name="deliverySlots" id="optionsRadios1" value="10:00 am - 11:00 am" /> 10:00 am - 11:00 am</div>
                        <div class="col-sm-4"><input type="radio" name="deliverySlots" id="optionsRadios1" value="11:00 am - 12:00 pm" /> 11:00 am - 12:00 pm</div>                                        
                        <div class="col-sm-4"><input type="radio" name="deliverySlots" id="optionsRadios1" value="12:00 pm - 1:00 pm" /> 12:00 pm - 1:00 pm</div>
                        <div class="col-sm-4"><input type="radio" name="deliverySlots" id="optionsRadios1" value="1:00 pm - 2:00 pm" /> 1:00 pm - 2:00 pm</div>
                        <div class="col-sm-4"><input type="radio" name="deliverySlots" id="optionsRadios1" value="2:00 pm - 3:00 pm" /> 2:00 pm - 3:00 pm</div>    
                        <div class="col-sm-4"><input type="radio" name="deliverySlots" id="optionsRadios1" value="3:00 pm - 4:00 pm" /> 3:00 pm - 4:00 pm</div>
                        <div class="col-sm-4"><input type="radio" name="deliverySlots" id="optionsRadios1" value="4:00 pm - 5:00 pm" /> 4:00 pm - 5:00 pm</div>
                        <div class="col-sm-4"><input type="radio" name="deliverySlots" id="optionsRadios1" value="5:00 pm - 6:00 pm" /> 5:00 pm - 6:00 pm</div>  
                        <div class="col-sm-4"><input type="radio" name="deliverySlots" id="optionsRadios1" value="6:00 pm - 7:00 pm" /> 6:00 pm - 7:00 pm</div>
                        <div class="col-sm-4">&nbsp;</div>
                        <div class="col-sm-4">&nbsp;</div>                                                                                                
                    </div>
                    <div class="row">
                    	<div class="col-sm-12">
                        	<textarea class="form-control" id="Comments" name="Comments" title="Enter Comments" rows="6" required="required" placeholder="Enter Comments (Provide us your Order / Invoice No)" aria-label="Enter Comments"></textarea>
                        </div>
                    </div>                    
                    <div class="row">
                    	<div class="col-sm-12 btnsub">
                        	<input type="submit" class="button" value="Place Order"/> 
                        </div>
                    </div>
				</form>                    
              </div>
              
              <div class="tab-pane <?php echo $pagesel2;?>" id="trackyourorder">  
              	<h2>Track your Order</h2>    
						
				<div class="table-responsive" style="height:350px;overflow-y:auto;">
						<div>
					<table class="table table-bordered table-striped"><thead>
							<tr>
								<th style="width:12%">Date</th>
								<th style="width:16%">Order#</th>
								<th style="width:22%">Store</th>
								<th style="width:22%">Deliver At</th>
								<th style="width:15%">Delivery Slot</th>
								<th style="width:13%">Status</th>
							</tr>
						</thead>
						<tbody>
<?php
							$orders =  mysqli_query($con,$strOrdrs);  
							$totrec = mysqli_num_rows($orders);
							$counter = 1;
							if($totrec > 0){
							while($array = mysqli_fetch_array($orders)) 
							{
								$ostatus = "";
								if($array["orderstatus"] == 1)
									$ostatus = "Pending";
								else if($array["orderstatus"] == 2)
									$ostatus = "Delivered";
								else if($array["orderstatus"] == 3)
									$ostatus = "Completed";
								else if($array["orderstatus"] == -1)
									$ostatus = "Cancelled";

?>
							<tr>
								<td style="width:15%"><?php echo date_format(new DateTime($array["dateadd"]),'d-m-y'); ?></td>
								<td style="width:20%"><?php echo $array["OrderCode"]; ?></td>
								<td style="width:22%"><?php echo $array["storename"]; ?><br/><?php echo $array["storeadd"]; ?></td>
								<td style="width:22%"><?php echo $array["toname"]; ?><br/><?php echo $array["toadd"]; ?></td>
								<td style="width:15%"><?php echo $array["deliveryslots"]; ?></td>
								<td style="width:13%"><?php echo $ostatus; ?></td>
							</tr>
<?php 
							} 
							}
							else{
?>
							<tr>
								<td colspan="5">No Order Found</td>
							</tr>
<?php
							}
?>
						
						</tbody>
					</table></div>
				</div>                      
              </div>
              
              <div class="tab-pane <?php echo $pagesel3;?>" id="uraddress">
              	<h2>Your Address</h2>
				 <form action="add-my-address-proc.php" method="Post">
                	<div class="row uraddresses">
                        <div class="col-sm-6">
                            <h5>Address 1</h5>
                             <textarea class="form-control" id="Address1" name="Address1" title="Enter Address" rows="6" required="required" placeholder="Enter Address" aria-label="Enter Address"><?php echo $custAdd1; ?></textarea>
                              <input type="text" class="form-control" id="GMaplink1" name="GMaplink1" placeholder="Share Google Map link" aria-label="Share Google Map link" title="Share Google Map link" value="<?php echo $custLink1; ?>"/>                                                                                                     
                            <h5>Address 2</h5>
                             <textarea class="form-control" id="Address2" name="Address2" title="Enter Address" rows="6" placeholder="Enter Address" aria-label="Enter Address"><?php echo $custAdd2; ?></textarea>                             
                             <input type="text" class="form-control" id="GMaplink2" name="GMaplink2" placeholder="Share Google Map link" aria-label="Share Google Map link" title="Share Google Map link" value="<?php echo $custLink2; ?>"/>                                                                                                     
                        </div>
                        <div class="col-sm-6 delivery">
                            <h5>Address 3</h5>
                             <textarea class="form-control" id="Address3" name="Address3" title="Enter Address" rows="6" placeholder="Enter Address" aria-label="Enter Address"><?php echo $custAdd3; ?></textarea>
                             <input type="text" class="form-control" id="GMaplink3" name="GMaplink3" placeholder="Share Google Map link" aria-label="Share Google Map link" title="Share Google Map link" value="<?php echo $custLink3; ?>"/>                                                                                                     
                            <h5>Address 4</h5>
                             <textarea class="form-control" id="Address4" name="Address4" title="Enter Address" rows="6" placeholder="Enter Address" aria-label="Enter Address"><?php echo $custAdd4; ?></textarea>                             
                             <input type="text" class="form-control" id="GMaplink4" name="GMaplink4" placeholder="Share Google Map link" aria-label="Share Google Map link" title="Share Google Map link" value="<?php echo $custLink4; ?>"/>                                                                                                     
                        </div>                    
                    </div>
					                    
                    <div class="row">
                    	<div class="col-sm-12 btnsub">
                        	<input type="submit" class="button" value="Update Address"/>
                        </div>
                    </div>
				</form>                                      
              </div>
              
              <div class="tab-pane <?php echo $pagesel4;?>" id="profile">
              	<h2>Your Profile</h2>
                <form action="Update-Profile-Proc.php" method="POST">
                	<div class="row updateprofile">
                    	<div class="col-sm-3">&nbsp;</div>                    
                        <div class="col-sm-6">
                              <input type="text" class="form-control" required id="FName" name="FName" placeholder="Your Full Name" aria-label="Enter Your Full Name" title="Enter Your Full Name" value="<?php echo $FullName; ?>" />
                              <input type="text" class="form-control" id="ContactNo" name="ContactNo" placeholder="Your Contact Number" aria-label="Enter Your Contact No" title="Enter Your Contact Number" value="<?php echo $Contact; ?>" />
                              <input type="text" class="form-control" id="AltContactNo" name="AltContactNo" placeholder="Your Alternate Contact Number" aria-label="Enter Your Alternate Contact No" title="Enter Your Alternate Contact Number" value="<?php echo $Alternate; ?>" />
                              <input type="text" class="form-control" required id="Email" name="Email" placeholder="Your Email Address" aria-label="Enter Your Email Address" title="Enter Your Email Address" disabled value="<?php echo $Email; ?>" />
			                  <input type="tel" class="form-control" required id="Zipcod" name="Zipcod" placeholder="Zip Code" aria-label="Enter your Zip Code" title="Enter your Zip Code" value="<?php echo $Zip; ?>" />
                        </div>   
                    	<div class="col-sm-3">&nbsp;</div>                                      
                    </div>   
                    
                    <div class="row">
                    	<div class="col-sm-12 btnsub">
                        	<input type="submit" class="button" value="Submit"/>  
                        </div>
                    </div>
				</form>                                    
              </div>
              
              <div class="tab-pane <?php echo $pagesel5;?>" id="changepws">
              	<h2>Change Password</h2>
                <form method="Post" action="change-password-proc.php">
                	<div class="row changepws">
                    	<div class="col-sm-3">&nbsp;</div>
                        <div class="col-sm-6">
                              <input type="text" class="form-control" required id="oldpws" name="oldpws" value="<?php echo $oldpws;?>" placeholder="Enter Your Current Password" aria-label="Enter Your Current Password" title="Enter Your Current Password" />
                              <input type="password" class="form-control" required id="newpws" name="newpws" value="<?php echo $newpws;?>" placeholder="Enter Your New Password" aria-label="Enter Your New Password" title="Enter Your New Password" />
                              <input type="password" class="form-control" required id="retypepws" name="retypepws" value="<?php echo $retypepws;?>" placeholder="ReType Your New Password" aria-label="ReType Your New Password" title="ReType Your New Password" />
                        </div>  
                    	<div class="col-sm-3">&nbsp;</div>                                        
                    </div>    
                    <div class="row">
                    	<div class="col-sm-12 btnsub">
                        	<input type="submit" class="button" value="Submit" /> 
                        </div>
                    </div>
				</form>                                  
              </div>              
            </div>
        </div>     
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

		$("#Address").on('change', function() {
			var id = this.value;
			var aid = "#Address" + id;
			var gid = "#GMaplink" + id;
			var address = $(aid).val();
			var gmaplink = $(gid).val();
			$("#CAddress").val(address);
			$("#CGMaplink").val(gmaplink);
		});
	});
</script>
</body>
</html>