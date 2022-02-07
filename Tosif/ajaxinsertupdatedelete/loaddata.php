<?php
include("sql_connection.php");

if(!empty($_GET["empid"])){
$empid = $_GET["empid"];
}

	
	
	$Q_select = "Select * from `employee` Where `employee_id`='".$empid."' Order by `employee_id` DESC";

	$sql_Q = mysql_query($Q_select) or die(mysql_error());	
	$totalrow = mysql_num_rows($sql_Q);
	
	if($totalrow > 0){ 
	
	$row_Q = mysql_fetch_array($sql_Q,MYSQL_BOTH);
?>

   
        
         <table border="1" cellpadding="3" cellspacing="3" style="border-collapse:collapse; font:12px Verdana, Arial, Helvetica, sans-serif;" width="750" >
         
         <tr>
         <td colspan="6">
         <b>Employee Details</b>
         </td>
         </tr>
         <tr>
	 <td>Emp ID</td>
         <td>Name</td>
         <td>Email</td>
         <td>Contact</td>
         <td>Designation</td>
         <td>Gender</td>
         <td>Options</td>
         </tr>
         
        
         <tr>
	 <td><?php echo $row_Q["employee_id"]; ?></td>
         <td><?php echo $row_Q["emp_fname"]." ".$row_Q["emp_mname"]." ".$row_Q["emp_lname"]; ?></td>
         <td><?php echo $row_Q["emp_email"]; ?></td>
         <td><?php echo $row_Q["emp_contact"]; ?></td>
         <td><?php echo $row_Q["emp_designation"]; ?></td>
         <td><?php echo $row_Q["emp_gender"]; ?></td>
         
         <td>
        <a style="cursor: pointer;" onclick="ActionFunction('edit.php',<?php echo $row_Q["employee_id"]; ?>,'edit');"><img src="edit_01.gif" border="0" alt="Edit" /></a> &nbsp;
 <a style="cursor: pointer;" onclick="ActionFunction('delete.php',<?php echo $row_Q["employee_id"]; ?>,'delete');"><img src="delete_01.gif" border="0" alt="Delete" /></a>
                    
          </td>
         </tr>
         </table>
        
       

<?php

	}

?>