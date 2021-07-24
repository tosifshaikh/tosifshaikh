<?php
include("sql_connection.php");

if(!empty($_GET["empid"])){
$empid = $_GET["empid"];
}

$Q_select = "Select * from `employee` Where `employee_id`='".$empid."' Order by `employee_id` DESC";
$sql_Q = mysql_query($Q_select) or die(mysql_error());	
$totalrow = mysql_num_rows($sql_Q);
$row_Q = mysql_fetch_array($sql_Q,MYSQL_BOTH);
?>

  <form id="formupdate" name="formupdate" method="post"  enctype="multipart/form-data" style="margin:0; padding:0; float:left;" onsubmit="return CommonFunction(this,'updatedb_file.php', 'loaddata.php','formupdate');">	
  
  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="emp_id" value="<?php echo $empid; ?>" />
  <p class="errormsg" style="display:none;">&nbsp;</p>
  <table border="1" cellpadding="2" cellspacing="1" style="border-collapse:collapse; font:12px Verdana, Arial, Helvetica, sans-serif;">
  
  <tr>
  <td align="left"> First Name : </td>
  <td> <input type="text" name="fname" value="<?php echo $row_Q["emp_fname"]; ?>" size="20" /></td>
  </tr>
  
  <tr>
  <td align="left"> Middle Name : </td>
  <td> <input type="text" name="mname" value="<?php echo $row_Q["emp_mname"]; ?>" size="20" /></td>
  </tr>
  
  <tr>
  <td align="left"> Last Name : </td>
  <td> <input type="text" name="lname" value="<?php echo $row_Q["emp_lname"]; ?>" size="20" /></td>
  </tr>
  
  <tr>
  <td align="left"> Email : </td>
  <td> <input type="text" name="email" value="<?php echo $row_Q["emp_email"]; ?>" size="25"/></td>
  </tr>
  
  
  <tr>
  <td align="left"> Contact No : </td>
  <td> <input type="text" name="contact" value="<?php echo $row_Q["emp_contact"]; ?>" size="20"/></td>
  </tr>
  
  <tr>
  <td align="left"> Designation : </td>
  <td> <input type="text" name="designation" value="<?php echo $row_Q["emp_designation"]; ?>" size="20" /></td>
  </tr>
  
  <tr>
  <td align="left"> Gender : </td>
  <td>
   <?php
   $radio1 = "";
   $radio2 = "";
   if($row_Q["emp_gender"] == "Male"){
    $radio1 = "checked";
   }elseif($row_Q["emp_gender"] == "Female"){
    $radio2 = "checked";
   }
   ?>
  <input type="radio" name="gender" value="Male" <?php echo $radio1; ?>/> Male &nbsp;&nbsp;<input type="radio" name="gender" value="Female" <?php echo $radio2; ?>/> Female
  </td>
  </tr>
  
  <tr>
  <td align="left"> Living Country : </td>
  <td>
  <?php
  $country_array = array("India", "USA","UK");
  ?>
  <select name="country">
   <?php
    foreach($country_array as $val){
	
	$sel = "";
	if($val == $row_Q["emp_country"]){
	    $sel = "selected";
	}
    ?>
     <option value="<?php echo $val; ?>" <?php echo $sel; ?>><?php echo $val; ?></option>
    <?php
    }
    ?>
  </select>
  </td>
  </tr>
  
  
  
  <tr>
  <td align="left"> Comments : </td>
  <td> <textarea name="comments" cols="20" rows="5"><?php echo $row_Q["emp_comments"]; ?></textarea></td>
  </tr>
  
  <tr>
  <td align="left" colspan="2"> <input type="submit" name="Sub" value="Update" /> &nbsp; <input type="reset"  value="Reset" /> </td>
  </tr>
  
  </table>
  </form>
