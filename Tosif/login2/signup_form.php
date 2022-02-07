<?php
session_start()
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<form action="signup.php" method="post" enctype="multipart/form-data" name="form1">
<table width="60%" cellspacing="1" cellpadding="1" align="center">
  <tr>
    <td colspan="4" align="center">Sign up Form</td>
  </tr>
  <tr>
    <td width="22%">Email :</td>
    <td width="42%">
      <input type="text" name="txtEmail" id="txtEmail" >
   </td>
    <td width="6%">&nbsp;</td>
    <td width="30%">&nbsp;</td>
  </tr>
  <tr>
    <td>Password</td>
    <td><input type="password" name="txtPass" id="txtPass" ></td>
    <td>Retype :</td>
    <td><input type="text" name="txtPass2" id="txtPass2" ></td>
  </tr>
  <tr>
    <td>Name :</td>
    <td>
      <input type="text" name="txtFname" id="txtFname">
       <input type="text" name="txtMname" id="txtMname">
        <input type="text" name="txtLname" id="txtLname">
   </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Address :</td>
    <td><textarea name="txtAddress" id="txtAddress" cols="35" rows="4"></textarea></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Gender :</td>
    <td><table width="200">
      <tr>
        <td><label>
          <input type="radio" name="genderGroup" value="Male" id="genderGroup_0">
          Male</label><label>
          <input type="radio" name="genderGroup" value="Female" id="genderGroup_1">
          Female</label></td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Country :</td>
    <td><select name="selectCountry" id="selectCountry">
      <option value="0">Select Country</option>
      <option value="ind">india</option>
      <option value="us">us</option>
      <option value="uk">uk</option>
    </select></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>hobbies :</td>
    <td colspan="2">
     
        <label>
          <input type="checkbox" name="hobbiesGroup[]" value="cricket" id="hobbiesGroup_0">
          Cricket</label>
       
        <label>
          <input type="checkbox" name="hobbiesGroup[]" value="chess" id="hobbiesGroup_1">
          Chess</label>
        
        <label>
          <input type="checkbox" name="hobbiesGroup[]" value="football" id="hobbiesGroup_2">
          football</label>
        <br>
      </p></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Image :</td>
    <td colspan="2"><input type="file" name="myFile" id="myFile"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" align="center"><input type="submit" name="txtSubmit" id="txtSubmit" value="Save"></td>
    <td>&nbsp;</td>
  </tr>
</table>
 </form>
 <?php
 session_destroy();
 
 ?>
 
</body>
</html>

