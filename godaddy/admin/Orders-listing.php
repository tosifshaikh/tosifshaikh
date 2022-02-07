<?php ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$otype="";
$otypetext = "";
$ordlisting = "";
include("includes/top.php");  
if(isset($_REQUEST) && !empty($_REQUEST["otype"]))
	$otype = $_REQUEST["otype"];

if(isset($_POST) && !empty($_POST["st"]) && $_POST["st"] == 2)
{
	$ordid = $_POST["ordid"];
	$delordqry = "delete from customerordermaster where id=".mysqli_real_escape_string($con,$ordid) ;
	mysqli_query($con,$delordqry);
	$redurl = "Orders-listing.php?msg=Order Deleted Successfully.";
	if($otype != "")
		$redurl .= "&otype=".$otype;
	header('location:'.$redurl);
}
else if(isset($_POST) && !empty($_POST["st"]) && $_POST["st"] == 3)
{
	$ordid = $_POST["ordid"];
	$orstatus = $_POST["orderstatus"];
	$delordqry = "Update customerordermaster set orderstatus =". mysqli_real_escape_string($con,$orstatus) ." where id=".mysqli_real_escape_string($con,$ordid) ;
	echo $delordqry;
	mysqli_query($con,$delordqry);
	$redurl = "Orders-listing.php?msg=Order Status Updated Successfully.";
	if($otype != "")
		$redurl .= "&otype=".$otype;
	header('location:'.$redurl);
}
$otypetext = "All";
$ordlisting = "select * from customerordermaster order by id desc";

if(isset($_REQUEST) && !empty($_REQUEST["otype"]))
{
	$otype = $_REQUEST["otype"];
	if($otype == "1")
	{
		$otypetext = "Pending";
	}
	else if($otype == "2")
	{
		$otypetext = "Delivered";
	}
	else if($otype == "3")
	{
		$otypetext = "Completed";
	}
	else if($otype == "-1")
	{
		$otypetext = "Cancelled";
	}
	$ordlisting = "select * from customerordermaster where orderstatus=". $otype ." order by id desc";
}

?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" summary="" id="container">
  <tr>
    <td id="sidenavigation" valign="top">
      <?php include("includes/left-menu.php"); ?>
    </td>
    <td id="maintextarea" height="445">
      <!-- START: Header Panels -->
      <div id="PageTtitle">
        <div id="col2">
          <span id="AddBack">&nbsp;</span>
        </div>
        <div id="col1">
          <strong>Order Management</strong> | <span>
            View <?php echo $otypetext; ?> Orders
          </span>
        </div>
      </div>
      <div id="PageDescription">
        <br />
        This page allows you to view the <?php echo $otypetext; ?> order details.<br /><br />

      </div>
      <!-- END: Header Panels -->
      <!-- START: Error Panels -->
      <?php 
if(isset($_GET["msg"]) && $_GET["msg"] != "")
{
?>
      <div id="div_Success" Class="PanSuccess">
        <?php echo $_GET["msg"]; ?>
      </div>
      <?php
}
?>
      <div id="div_Error" Class="PanError" style="display:none;">PanError</div>
      <div id="div_Message" Class="PanNoRecord" style="display:none;">PanNoRecord</div>
      <!-- END: Error Panels -->
      <!-- START: Main Grid Panels -->
      <div class="PanGrid">
        <table cellpadding="0" cellspacing="0" width="100%">
          <tbody>
            <tr>
              <td>
                  <input type='hidden' id='current_page' />
                  <input type='hidden' id='show_per_page' />
                  <table class="gridbdr" id='content' cellspacing="0" rules="all" border="1" style="background-color:White;border-color:#CCCCCC;border-style:Inset;width:100%;border-collapse:collapse;">
                    <tbody>
                      <tr align="center" valign="middle" style="height:20px;">
                        <th align="left" scope="col" style="background-color:#E0E0E0;">#</th>
                        <th align="left" scope="col" style="background-color:#E0E0E0;">Order Date</th>
                        <th align="left" scope="col" style="background-color:#E0E0E0;">Order Details</th>
                        <th align="left" scope="col" style="background-color:#E0E0E0;height:40px;">Store Details</th>
                        <th align="left" scope="col" style="background-color:#E0E0E0;height:40px;">Customer Details</th>
                        <th align="center" scope="col" style="background-color:#E0E0E0;">&nbsp;</th>
                      </tr>
                      <?php
$cart =  mysqli_query($con,$ordlisting);  
$totrec = mysqli_num_rows($cart);
$counter = 1;
while($array = mysqli_fetch_array($cart)) 
{
if($counter % 2 == 0)
{
$css = "class='alternategridrow1 paging' style='background-color:#F0F0F0;'";
$css2 = "class='trd alternategridrow1' style='background-color:#F0F0F0;'";
}
else{
$css = "class='paging'";
$css2 = "class='trd'";
}
?>
                      <tr
                        <?php echo $css; ?>>
                        <td align="left" valign="middle">
                          <?php echo $counter; ?>
                        </td>
                        <td align="center" valign="middle">
                          <?php echo date_format(new DateTime($array["dateadd"]),'d/m/Y'); ?>
                        </td>
						<td align="left" valign="top">
							<strong>Code:</strong><?php echo $array["OrderCode"]; ?><br/>
							<strong>Delivery Slots:</strong><?php echo $array["deliveryslots"]; ?><br/>
							<strong>Comments:</strong><?php echo str_replace('\r\n','<br/>',$array["note"]); ?>
						</td>
                        <td align="left" valign="top" style="height:30px;">
                          <?php echo $array["storename"]; ?><br/><?php echo str_replace('\r\n','<br/>', $array["storeadd"]); ?>
						  <?php 
							if($array["storegmap"] != "")
								echo "<br/><a href='".$array["storegmap"]."' target='_blank'>Store Location<img src='images/icon_link.gif' border='0px' /></a>";
							?>
                        </td>
                        <td align="left" valign="top" style="height:30px;">
                          <strong>Name:</strong><br/><?php echo $array["toname"]; ?><br/><strong>Phone:</strong><?php echo $array["tophone"]; ?><br/><strong>Address:</strong><br/><?php echo str_replace('\r\n','<br/>', $array["toadd"]); ?>
						  <?php 
							if($array["togmap"] != "")
								echo "<br/><a href='".$array["togmap"]."' target='_blank'>Customer Location<img src='images/icon_link.gif' border='0px' /></a>";
							?>
                        </td>
                        <td align="center" valign="middle" style="width:35px;">
                           <form name="odelete" method="post">
                            <input type="hidden" name="st" value="2" />
                            <input type="hidden" name="ordid" value="<?php echo $array["id"]; ?>" />
                            <input type="image" src="images/icon_delete.gif" value="Delete" onclick="return confirm('Are you sure you want to delete this record?');" title="Click to delete"/><br/><br/>
                            <a href="#" onclick="ShowOrderUpdateDialog('<?php echo $array["id"]; ?>' )" title="Edit Order Status" style="border:0px;"><img src="images/icon_edit.png" title="Edit Order Status" /></a>
							</form>
							<div class="dialog-order-status" id="order-status-<?php echo $array["id"]; ?>" title="Update Order Status"> 
							  <form name="oupdate" method="post">
								<input type="hidden" name="ordid" value="<?php echo $array["id"]; ?>" />
								<strong>Order Code:</strong><?php echo $array["OrderCode"]; ?><br/>
								<?php
								$ostatus = "";
								if($array["orderstatus"] == 1)
									$ostatus = "Pending";
								else if($array["orderstatus"] == 2)
									$ostatus = "Delivered";
								else if($array["orderstatus"] == 3)
									$ostatus = "Completed";
								else if($array["orderstatus"] == -1)
									$ostatus = "Cancelled"; ?>

								<strong>Current Status:<?php echo $ostatus ?></strong><br/>
								<strong>New Status:</strong>
								<select name="orderstatus">
									<option value="0">Select Status</option>
									<option value="1">Pending</option>
									<option value="2">Delivered</option>
									<option value="3">Completed</option>
									<option value="-1">Cancelled</option>
								</select><br/><br/>
								<input type="hidden" name="st" value="3"/>
								<input type="submit" value="Change Status" />
							  </form>
							</div>
                        </td>
                      </tr>
                      <?php $counter++; } ?>
                      <tr align="right" valign="middle" style="border-style:None;">
                        <td colspan="7">

				<table cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td align='left'>
                <span id="ContentArea_Lbl_paging">
                  Total <?php echo $otypetext; ?> Order(s):<?php echo $totrec; ?>
                </span>
						</td><td align='right' id='page_navigation'></td>
					</tr>
				</table>						
						</td>
                      </tr>
                    </tbody>
                  </table>
              </td>
            </tr>
            <tr>
              <td>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- END: Main Grid Panels -->

    </td>
  </tr>
</table>
<?php include("includes/bottom.php"); ?>