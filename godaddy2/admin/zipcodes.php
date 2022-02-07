<?php ob_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$otype="";
$otypetext = "";
$ordlisting = "";
include("includes/top.php");  
if(isset($_POST) && !empty($_POST["st"]) && $_POST["st"] == 1)
{
	$txtzipcode = $_POST["txtzipcode"];
	$txtPlace = $_POST["txtPlace"];
	
	$insQryy = "insert into `tblzipcodes`(zipcode,place,addedIP,addeddatetime) values ('".mysqli_real_escape_string($con,$txtzipcode)."','".mysqli_real_escape_string($con,$txtPlace)."','".mysqli_real_escape_string($con,GetIPAddress())."',NOW())";
	
	mysqli_query($con,$insQryy);
	$redurl = "zipcodes.php?msg=Zip Code added successfully.";
	header('location:'.$redurl);
}
else if(isset($_POST) && !empty($_POST["st"]) && $_POST["st"] == 2)
{
	$ordid = $_POST["ordid"];
	$delordqry = "delete from tblzipcodes where id=".mysqli_real_escape_string($con,$ordid) ;
	mysqli_query($con,$delordqry);
	$redurl = "zipcodes.php?msg=Zip Code Deleted Successfully.";
	header('location:'.$redurl);
}
else if(isset($_POST) && !empty($_POST["st"]) && $_POST["st"] == 3)
{
	$ordid = $_POST["ordid"];
	$txtzipcode = $_POST["txtzipcode"];
	$txtPlace = $_POST["txtPlace"];
	
	$delordqry = "Update tblzipcodes set zipcode =". mysqli_real_escape_string($con,$txtzipcode) .",place='".mysqli_real_escape_string($con,$txtPlace)."',modifiedip='".mysqli_real_escape_string($con,GetIPAddress())."',modifieddatetime=now() where id=".mysqli_real_escape_string($con,$ordid) ;
	
	mysqli_query($con,$delordqry);
	$redurl = "zipcodes.php?msg=Zip Code Updated Successfully.";
	header('location:'.$redurl);
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
          <strong>Zip Code Management</strong> | <span>
            View / Add / Edit Zip Codes
          </span>
        </div>
      </div>
      <div id="PageDescription">
        <br />
        This page allows you to View / Add / Edit Zip Codes.<br /><hr />

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
					<form method="post">
					<table class="gridbdr" id='content' cellspacing="0" rules="all" border="1" style="background-color:White;border-color:#CCCCCC;border-style:Inset;width:100%;border-collapse:collapse;">
						<tr>
							<td colspan="2" style="background-color:#E0E0E0;">
							<strong>Add New Zip Code</strong>
							</td>
						</tr>
						<tr>
							<td style="width:15%">
								Zip Code
							</td>
							<td style="width:85%;">
								<input type="text" name="txtzipcode" maxlength="5" required />
							</td>
						</tr>
						<tr>
							<td style="width:15%">
								Place
							</td>
							<td style="width:85%;">
								<input type="text" name="txtPlace" width="100px" />
							</td>
						</tr>
						<tr>
							<td style="width:15%">
								
							</td>
							<td style="width:85%;">
								<input type="hidden" name="st" value="1" />
								<input type="submit" name="subnewvals" value="Submit" />
							</td>
						</tr>
					</table>
					</form>
				</td>
			</tr>
						<tr>
							<td style="background-color:#E0E0E0;">
							<strong>List of All Zip Codes</strong>
							</td>
						</tr>
            <tr>
              <td>
                  <input type='hidden' id='current_page' />
                  <input type='hidden' id='show_per_page' />
                  <table class="gridbdr" id='content' cellspacing="0" rules="all" border="1" style="background-color:White;border-color:#CCCCCC;border-style:Inset;width:100%;border-collapse:collapse;">
                    <tbody>
                      <tr align="center" valign="middle" style="height:20px;">
                        <th align="left" style="background-color:#E0E0E0;width:2%;">#</th>
                        <th align="left" style="background-color:#E0E0E0;width:20%;">Zip Code</th>
                        <th align="left" style="background-color:#E0E0E0;width:53%;">Place</th>
                        <th align="center" style="background-color:#E0E0E0;width:10%;">&nbsp;</th>
                      </tr>
                      <?php
$cart =  mysqli_query($con,"Select * From tblzipcodes order by zipcode, place");  
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
                        <td align="left" valign="middle">
                          <?php echo $array["zipcode"]; ?>
                        </td>
						<td align="left" valign="top">
                          <?php echo $array["place"]; ?>
						</td>
                        <td align="center" valign="middle" style="width:35px;">
                           <form name="odelete" method="post">
                            <input type="hidden" name="st" value="2" />
                            <input type="hidden" name="ordid" value="<?php echo $array["id"]; ?>" />
                            <a href="#" onclick="ShowZipUpdate('<?php echo $array["id"]; ?>' )" title="Edit Zip Code" style="border:0px;"><img src="images/icon_edit.png" title="Edit Zip Code" /></a>&nbsp;&nbsp;
                            <input type="image" src="images/icon_delete.gif" value="Delete" onclick="return confirm('Are you sure you want to delete this record?');" title="Click to delete"/>
							</form>
							<div class="dialog-zip-update" id="zip-update-<?php echo $array["id"]; ?>" title="Edit Zip Code"> 
							  <form name="oupdate" method="post">
								<input type="hidden" name="ordid" value="<?php echo $array["id"]; ?>" />
								<input type="hidden" name="st" value="3"/>
								<table cellspacing='0' cellpadding='0' width='100%'>
									<tr>
										<td align='left' style='width:20%'>Zip Code:</td>
										<td align='left' style='width:80%'><input type='text' name='txtzipcode' value='<?php echo $array["zipcode"]; ?>' /> </td>
									</tr>
									<tr>
										<td align='left' style='width:20%'>Place:</td>
										<td align='left' style='width:80%'><input type='text' name='txtPlace' value='<?php echo $array["place"]; ?>' width='100px' /> </td>
									</tr>
									<tr>
										<td align='left' style='width:20%'></td>
										<td align='left' style='width:80%'><input type="submit" name="subupvals" value="Submit" /></td>
									</tr>
								</table>
							  </form>
							</div>
                        </td>
                      </tr>
                      <?php $counter++; } ?>
                      <tr align="right" valign="middle" style="border-style:None;">
                        <td colspan="4">

				<table cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td align='left'>
                <span id="ContentArea_Lbl_paging">
                  Total Zip Code(s):<?php echo $totrec; ?>
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