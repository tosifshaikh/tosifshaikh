<?php //ob_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
if(!isset($_SESSION)) 
{ 
	session_start(); 
}   
if(empty($_SESSION["validadmin"]) || $_SESSION["validadmin"]!=1)
{
	header('location:index.php');
}
require("../includes/connection.php");
					$otype="";
						$otypetext = "";
						$ordlisting = ""; 
						if(isset($_GET["type"]) && $_GET["type"] == 0)
						{
							$otype = 0;
							$otypetext = "Inactive";
								$ordlisting = "select * from customer where authenticated=0  order by id desc";
					
						}
						elseif(isset($_GET["type"]) && $_GET["type"] ==1)
						{
							$otype = 1;
							$otypetext = "Active";
								$ordlisting = "select * from customer where authenticated=1  order by id desc";
						}
						
						if(isset($_POST) && !empty($_POST["st"]) && $_POST["st"] == 2)
						{
							$custid = $_POST["custid"];
							$delordqry = "delete from customer where id=".mysqli_real_escape_string($con,$custid) ;
							
							mysqli_query($con,$delordqry);
							
							//echo $strupd;
							$redurl = "viewcustomers.php?type=". $_GET["type"] ."&msg=Customer Deleted Successfully.";
							header('location:'.$redurl);
						}
						else if(isset($_POST) && !empty($_POST["st"]) && $_POST["st"] == 3)
						{
							$custid = $_POST["custid"];
							$otype = $_POST["otype"];
							$delordqry = "update customer set authenticated=". $otype ." where id=".mysqli_real_escape_string($con,$custid) ;
							
							mysqli_query($con,$delordqry);
							
							//echo $strupd;
							$redurl = "viewcustomers.php?type=". $_GET["type"] ."&msg=Customer Status Changed Successfully.";
							header('location:'.$redurl);
						}
					?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ZipCroc - Admin Area</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="en-us" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="css/base.css"></link>

    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" language="javascript">

      $(document).ready(function(){
      doStartPaging();
      $(".trd").hide();
      });
      function showDet(tdid)
      {
      var xid = "#"+tdid;
      $(xid).show();
      }
      function closedet(tdid)
      {
      var xid = "#"+tdid;
      $(xid).hide();
      }
      function checkFileType(src, arg) {

      var fileName = arg.Value;
      var ext;
      ext = fileName.substr(fileName.lastIndexOf('.')).toLowerCase();
      if (fileName == '') {
      arg.IsValid = false;
      }
      if ('.jpg, .gif, .png, .jpeg, .jpe,.JPG,.GIF,.PNG,.JPEG,.JPE'.indexOf(ext + ',') < 0) {
            arg.IsValid = false;
        }
        else
            arg.IsValid = true;
    }
    function documentType(src, arg) {

        var fileName = arg.Value;
        var ext;
        ext = fileName.substr(fileName.lastIndexOf('.')).toLowerCase();
        if (fileName == '') {
            arg.IsValid = false;
        }
        if ('.pdf, .ppt,.txt, .docx, .doc,.xls,.xlsx'.indexOf(ext + ',') < 0) {
            arg.IsValid = false;
        }
        else
            arg.IsValid = true;
    }
    function CheckKeyCode(e) {
        if (navigator.appName == "Microsoft Internet Explorer") {
            if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode == 8) || (e.keyCode == 46)) {
      return true;
      }
      else {
      return false;
      }
      }
      else {
      if ((e.charCode >= 48 && e.charCode <= 57) || (e.charCode == 0) || (e.keyCode == 46)) {
      return true;
      }
      else {
      return false;
      }
      }
      }
      function doStartPaging()
      {
      var show_per_page = 15;  //Rows Per Page
      var number_of_items = $('#content tr.paging').size();
      var number_of_pages = Math.ceil(number_of_items / show_per_page);
      $('#current_page').val(0);
      $('#show_per_page').val(show_per_page);
      var navigation_html = '<a class="previous_link toplinks" href="javascript:previous();">Prev</a>';
      var current_link = 0;
      while (number_of_pages > current_link) {
      navigation_html += '<a class="page_link toplinks" href="javascript:go_to_page(' + current_link + ')" longdesc="' + current_link + '">' + (current_link + 1) + '</a>';
      current_link++;
      }
      navigation_html += '<a class="next_link toplinks" href="javascript:next();">Next</a>';
      $('#page_navigation').html(navigation_html);
      $('#page_navigation .page_link:first').addClass('active_page toplinks');
      $('#content tr.paging').css('display', 'none');
      $('#content tr.paging').slice(0, show_per_page).css('display', '');
      }
      function previous() {
      new_page = parseInt($('#current_page').val()) - 1;
      if ($('.active_page').prev('.page_link').length == true) {
      go_to_page(new_page);
      }
      $(".trd").hide();
      }

      function next() {
      new_page = parseInt($('#current_page').val()) + 1;
      if ($('.active_page').next('.page_link').length == true) {
      go_to_page(new_page);
      }
      $(".trd").hide();
      }
      function go_to_page(page_num) {
      var show_per_page = parseInt($('#show_per_page').val());
      start_from = page_num * show_per_page;
      end_on = start_from + show_per_page;
      $('#content tr.paging').css('display', 'none').slice(start_from, end_on).css('display', '');
      $('.page_link[longdesc=' + page_num + ']').addClass('active_page').siblings('.active_page').removeClass('active_page');
      $('#current_page').val(page_num);
      $(".trd").hide();
      }
    </script>
    <style>
      #page_navigation a {
      padding: 3px;
      margin: 2px;
      color: black;
      text-decoration: none;
      }

      .active_page {
      background: #49c5ac;
      color: white !important;
      }
    </style>
  </head>
  <body>
    <table border="0" cellspacing="0" cellpadding="0" width="1000" summary="Administrative Area" align="center">
      <tr>
        <td height="77px">
          <img src="images/head_admin.jpg" />
        </td>
      </tr>
      <tr>
        <td>
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
                    <strong>Customer Management</strong> | <span>
                      View <?php echo $otypetext; ?> Customers
                    </span>
                  </div>
                </div>
                <div id="PageDescription">
                  <br />
                  This page allows you to view the <?php echo $otypetext; ?> customer list.<br /><br />

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
                          <div style="height:500px;overflow-y:auto;">
                            <input type='hidden' id='current_page' />
                            <input type='hidden' id='show_per_page' />
                            <table class="gridbdr" cellspacing="0" rules="all" border="1" id='content'  style="background-color:White;border-color:#CCCCCC;border-style:Inset;width:100%;border-collapse:collapse;">
                              <tbody>
                                <tr align="center" valign="middle" style="height:20px;">
                                  <th align="left" scope="col" style="background-color:#E0E0E0;">#</th>
                                  <th align="left" scope="col" style="background-color:#E0E0E0;">Name</th>
                                  <th align="left" scope="col" style="background-color:#E0E0E0;">Email</th>
                                  <th align="left" scope="col" style="background-color:#E0E0E0;">Phone</th>
                                  <th align="left" scope="col" style="background-color:#E0E0E0;">Reg. Date</th>
                                  <th align="left" scope="col" style="background-color:#E0E0E0;">Status</th>
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
						$css2 = "class='trd alternategridrow1 paging' style='background-color:#F0F0F0;'";
						}
						else{
						$css = "class='paging'";
						$css2 = "class='trd paging'";
						}
?>
                                <tr <?php echo $css; ?>>
                                  <td align="left" valign="middle">
                                    <?php echo $counter; ?>
                                  </td>
                                  <td align="left" valign="middle">
                                    <?php echo $array["FullName"] ?>
                                  </td>
                                  <td align="left" valign="middle" style="height:30px;">
                                    <?php echo $array["email"]; ?>
                                  </td>
                                  <td align="left" valign="middle">
                                    <?php echo $array["phone"]; ?>
									<?php
										$Alternate = $array["alternatecontact"];
										if($Alternate != "")
											echo "<br/>Alt.".$Alternate;
									?>
                                  </td>
                                  <td align="left" valign="middle" style="height:30px;">
                                    <?php echo date_format(new DateTime($array["regdate"]),'d-m-y'); ?>
                                  </td>
                                  <td align="center" valign="middle" style="width:70px;">
									<?php
										$altTxt = "Deactivate";
										$tempotype = -1;
										if($otype == 1){
											$altTxt = "Deactivate";
											$tempotype = 0;
										}
										else if($otype == 0){
											$altTxt = "Activate";
											$tempotype = 1;
										}
									?>
                                    <form method="post">
                                      <input type="hidden" name="st" value="3" />
                                      <input type="hidden" name="otype" value="<?php echo $tempotype; ?>" />
                                      <input type="hidden" name="custid" value="<?php echo $array["id"]; ?>" />
                                      <input type="submit" value="<?php echo $otypetext; ?>" onclick="return confirm('Are you sure you want to <?php echo $altTxt; ?> this customer?');" title="Click to <?php echo $altTxt; ?> this customer">
									</form>
                                  </td>
                                  <td align="center" valign="middle" style="width:70px;">
                                    <form method="post">
                                      <input type="hidden" name="st" value="2" />
                                      <input type="hidden" name="custid" value="<?php echo $array["id"]; ?>" />
									  <input type="image" src="images/icon_delete.gif" value="Delete" onclick="return confirm('Are you sure you want to delete this record?');" title="Click to delete"/>
									</form>
                                  </td>
                                </tr>
                                <?php $counter++; } ?>
                                <tr align="right" valign="middle" style="border-style:None;">

                                  <td colspan="7">
							<table cellspacing="0" cellpadding="0" width="100%">
								<tr>
									<td align='left'>
							<span id="ContentArea_Lbl_paging">
							  Total <?php echo $otypetext; ?> Customer(s):<?php echo $totrec; ?>
							</span>
									</td><td align='right' id='page_navigation'></td>
								</tr>
							</table>								  
								  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <!-- END: Main Grid Panels -->

              </td>
            </tr>
          </table>
          <script language="javascript" type="text/javascript">
            $(document).ready(function(){
            $(".trd").hide();
            });
            function showDet(tdid)
            {
            var xid = "#"+tdid;
            $(xid).show();
            }
            function closedet(tdid)
            {
            var xid = "#"+tdid;
            $(xid).hide();
            }
          </script>
<?php include("includes/bottom.php"); ?>