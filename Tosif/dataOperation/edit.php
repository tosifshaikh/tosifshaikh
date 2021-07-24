<?php
include('conn.php');
if(isset($_GET['memId']))
{
 $mem_id=$_GET['memId'];
}
 	//$selQuery=mysqli_prepare($conn,'SELECT * from member WHERE mem_id=? ORDER BY mem_id DESC');
	$selQuery="select * from member where mem_id='".$mem_id."' ORDER BY mem_id DESC";
	//mysqli_stmt_bind_param($selQuery,'i',$mem_id);
	//mysqli_stmt_execute($selQuery);
 	$result=mysqli_query($conn,$selQuery)or die(mysqli_error($conn)); 
 	$rowCount=mysqli_num_rows($result);
	//$rowCount=mysqli_stmt_num_rows($selQuery);
	//$row=mysqli_stmt_fetch($selQuery);
	$row=mysqli_fetch_array($result);
	  
?>
<form method="post" id="updateForm" onSubmit="return callFunction(this.id,'update.php','pagging.php',this)">
    <input type="hidden" name="action" value="update">
     <input type="hidden" name="memId" value="<?php echo $row[0]; ?>">
    <table  align="center"  >
      <th colspan="2" style="color:#F00">Update Form</th>
     <tr>
        <td ><div align="right">Username:</div></td>
        <td><input type="text" name="username" value="<?php echo $row[1]; ?>"/></td>
     </tr>
     <tr>
        <td><div align="right">Password:</div></td>
        <td><input type="text" name="password" /></td>
      </tr>
      <tr>
        <td width="95"><div align="right">First Name:</div></td>
        <td width="208"><input type="text" name="fname" value="<?php echo $row[3]; ?>"/></td>
      </tr>
      <tr>
        <td><div align="right">Last Name:</div></td>
        <td><input type="text" name="lname" value="<?php echo $row[4]; ?>"/></td>
      </tr>
      <tr>
        <td><div align="right">Gender:</div></td>
        <td>Male <input type="radio" name="gender" value="M" <?php if($row[8]=="M"){ echo "checked";}elseif($row[8]=="F"){echo "checked";}?>/>
	        Female <input type="radio" name="gender" value="F" /></td>
      </tr>
      <tr>
        <td><div align="right">Address:</div></td>
        <td><input type="text" name="address" value="<?php echo $row[5]; ?>"/></td>
      </tr>
      
      <tr>
        <td><div align="right">Contact No.:</div></td>
        <td><input type="text" name="contact" value="<?php echo $row[6]; ?>"/></td>
      </tr>
      <tr>
    <td align="right">hobbies :</td>
    <td colspan="2">
     <?php
	   $hobbie_arr=array("cricket","chess","football");
	  $hobbie=explode(',',$row[9]);
	  foreach($hobbie_arr as $hobi)
	  {
         if(in_array($hobi,$hobbie))
		 {
	     
        
          echo "<input type='checkbox' name='hobbiesGroup[]' value=\"$hobi\" class='hobbi' checked> $hobi" ;
         
		 }
		 else
		 {
       
         echo "<input type='checkbox' name='hobbiesGroup[]' value=\"$hobi\" class='hobbi'>$hobi";
    
      
	  }
	  }
      ?>
     </td>
 
  </tr>
      
      <tr>
        <td><div align="right"></div></td>
        <td><input name="submit" type="submit" value="Update" id='btn' />
        <a  href="index.php" style="text-decoration:none;"><input type="button" value="Insert" /></a></td>
      </tr>
      
    </table>
    </form>
    