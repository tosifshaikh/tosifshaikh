<?php
include('conn.php');
if(isset($_GET['memId']))
{
 $mem_id=$_GET['memId'];


   	$selQuery="select * from member where mem_id='".$mem_id."' ORDER BY mem_id DESC";
 	$result=mysqli_query($conn,$selQuery)or die(mysqli_error($conn)); 
 	$rowCount=mysqli_num_rows($result);
	
	
		$row=mysqli_fetch_array($result)
	   
		 ?>
        
		  <td><input type="checkbox" name="checkboxDel[]" value="<?php echo $row[0] ?>"></td>
         <td><?php echo $row[1]?></td>
          <td><?php echo $row[3]." ".$row[4]; ?></td>
         <td><?php echo $row[5]?></td>
         <td><?php echo $row[6]?></td>
         <td><?php echo $row[8]?></td>
          <td><?php echo $row[9]?></td>
         <td><a onClick="actionfunction('edit.php','edit',<?php echo $row[0] ?>);"><img src="edit_01.gif"/></a></td>
          <td><a onClick="actionfunction('delete.php','delete',<?php echo $row[0] ?>);"><img src="delete_01.gif"/></a></td>
        
   <?php 
         
      
	}
	     ?>
		   