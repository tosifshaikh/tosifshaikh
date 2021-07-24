<?php session_start();?>
<html>
<head>



   <script type="text/javascript">
//    function validateForm()
//    {
//    var a=document.forms["reg"]["fname"].value;
//    var b=document.forms["reg"]["lname"].value;
//    var c=document.forms["reg"]["gender"].value;
//    var d=document.forms["reg"]["address"].value;
//    var e=document.forms["reg"]["contact"].value;
//    var f=document.forms["reg"]["pic"].value;
//    var g=document.forms["reg"]["pic"].value;
//    var h=document.forms["reg"]["pic"].value;
//    if ((a==null || a=="") && (b==null || b=="") && (c==null || c=="") && (d==null || d=="") && (e==null || e=="") && (f==null || f==""))
//      {
//      alert("All Field must be filled out");
//      return false;
//      }
//    if (a==null || a=="")
//      {
//      alert("First name must be filled out");
//      return false;
//      }
//    if (b==null || b=="")
//      {
//      alert("Last name must be filled out");
//      return false;
//      }
//    if (c==null || c=="")
//      {
//      alert("Gender name must be filled out");
//      return false;
//      }
//    if (d==null || d=="")
//      {
//      alert("address must be filled out");
//      return false;
//      }
//    if (e==null || e=="")
//      {
//      alert("contact must be filled out");
//      return false;
//      }
//    if (f==null || f=="")
//      {
//      alert("picture must be filled out");
//      return false;
//      }
//    if (g==null || g=="")
//      {
//      alert("username must be filled out");
//      return false;
//      }
//    if (h==null || h=="")
//      {
//      alert("password must be filled out");
//      return false;
//      }
//    }
  </script>

</head>
<body>

<form action="welcome.php" method="post">
<table align="center">
<tr><td>Name: </td><td>  <input type="text" name="name" autocomplete="off"><br></td></tr>
<tr><td>E-mail: </td><td><input type="text" name="pass" autocomplete="off"><br></td></tr>
<tr><td align="center" colspan="2"><input type="submit"  name="Submit" value="Login" ></td></tr>
<tr><td colspan="2"><?php if(isset($_GET['msg'])){ echo $_GET['msg']; }?></td></tr>
</table>
</form>


   <form name="reg" action="register.php"  method="post" enctype="multipart/form-data">
    <table  align="center"  >
      <tr>
        <td colspan="2" style="color:red">
    		<div align="center">
   		  <?php 

			$remarks = (isset($_GET['remarks']) ? $_GET['remarks'] : null);
			
    		if ($remarks==null and $remarks=="")
    		{
 		   		echo 'Register Here';
    		}
    		
			if ($remarks=='success')
    		{
    			echo 'Registration Success';
    		}
   		  ?>	
    	    </div></td>
     </tr>
     <tr>
        <td><div align="right">Username:</div></td>
        <td><input type="text" name="username" /></td>
     </tr>
     <tr>
        <td><div align="right">Password:</div></td>
        <td><input type="text" name="password" /></td>
      </tr>
      <tr>
        <td width="95"><div align="right">First Name:</div></td>
        <td width="171"><input type="text" name="fname" /></td>
      </tr>
      <tr>
        <td><div align="right">Last Name:</div></td>
        <td><input type="text" name="lname" /></td>
      </tr>
      <tr>
        <td><div align="right">Gender:</div></td>
        <td>Male <input type="radio" name="gender" value="M" />
	        Female <input type="radio" name="gender" value="F" /></td>
      </tr>
      <tr>
        <td><div align="right">Address:</div></td>
        <td><input type="text" name="address" /></td>
      </tr>
      
      <tr>
        <td><div align="right">Contact No.:</div></td>
        <td><input type="text" name="contact" /></td>
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
        <td><div align="right">Picture:</div></td>
        <td><input type="file" name="profPic" /></td>
      </tr>
      <tr>
        <td><div align="right"></div></td>
        <td><input name="submit" type="submit" value="Submit" /></td>
      </tr>
      
    </table>
    </form>

</body>
</html>
