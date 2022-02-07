<?php
include("sql_connection.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Demo - Insert Update Delete Using jQuery Animations + PHP</title>
<style type="text/css">
#mloaddata{
margin-left:10px;
}
</style>

<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript">

function ClearFields(theForm){

	theForm.fname.value="";
	theForm.mname.value="";
	theForm.lname.value="";
	theForm.email.value="";
	theForm.contact.value="";
	theForm.designation.value="";
	theForm.comments.value="";
}

function InputFieldValidations(theForm) {


    if (theForm.fname.value == "") {
	alert("Please Enter Your First Name.");
	theForm.fname.focus();
	return (false);
    }
	
	if (theForm.mname.value == "") {
	alert("Please Enter Your Middle Name.");
	theForm.mname.focus();
	return (false);
    }
	
	if (theForm.lname.value == "") {
	alert("Please Enter Your Last Name.");
	theForm.lname.focus();
	return (false);
    }
	
	
    if (theForm.email.value == "") {
	alert("Please Enter Your Email Address.");
	theForm.email.focus();
	return (false);
    }
    
    if  (theForm.email.value != "") {   
    var eresult
    var str=theForm.email.value
    var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
	if (!filter.test(str)) {
	    alert("Please enter a valid Email address!")
	    theForm.email.focus();
	    eresult=false;
	    return (eresult);
	}	
    }   
    
    if (theForm.contact.value == "") {
	alert("Please Enter Your Contact Number.");
	theForm.contact.focus();
	return (false);
    }
	
	if (theForm.designation.value == "") {
	alert("Please Enter Your Designation.");
	theForm.designation.focus();
	return (false);
    }
	
	
	if (theForm.comments.value == "") {
	alert("Please write your message/comments in message box.");
	theForm.comments.focus();
	return (false);
    }
    
   
  
  return true;
  
}

function ActionFunction(urls, empids, work){
    
    if (work == "edit") {
      
	  
	  var divcontainer=$('#mforminsert');
	 
	  divcontainer.empty();
      divcontainer.html('<div style="float:left; margin-left:5px; width:400px; height:250px;"><img src="loading.gif" /></div>');
	 
	  divcontainer.slideDown('slow', function(){
      divcontainer.load(urls+'?empid='+empids+'&sid='+Math.random());
      });
	 
	 
    }
    
    if (work == "delete") {
     
	 
     var result = confirm("Are you sure you want to delete?");
     
	 
      if (result == true) {
	
	
	$.ajax({
	    url: urls,
	    async: true,
	    cache: false,
	    data: {empid: empids},
	    type: 'get',			
	    success: function (data) {
		   data=data.replace(/\s+/g,"");
		   var spancontainer=$('span#record'+empids);
		   if(data != 0){

			 spancontainer.slideUp('slow', function(){
             spancontainer.fadeOut("slow");
	         spancontainer.remove();
			 });
			    
			
		    }
		    else {
		        spancontainer.slideUp('slow', function(){		
			    spancontainer.html("Error While this deleting a record");
				});
		    }
	    },
	    error : function(XMLHttpRequest, textStatus, errorThrown) {
		    alert(textStatus);
	    }
	});
	
	
	
     
      }
     
    }
    
}

function CommonFunction(FormObject, pageurl, loadurl,  FormID) { 
   
    var noofrecords = $('span.items').length;
	
    if(InputFieldValidations(FormObject)) {	
		    
	$.ajax({
	    url: pageurl,
	    async: true,
	    cache: false,
	    data: $('#'+FormID).serialize(),
	    type: 'post',			
	    success: function (data) {
		   data=data.replace(/\s+/g,"");
		   
		   
		   if(data != 0){
		 
				var spancontainer;
			    $('.errormsg').empty();
			    $('div#successfulpost').fadeIn();
			    
			    if($('span#record'+data).length){
				spancontainer=$('span#record'+data);
			    }else{
			    $("<span id='record"+data+"' class='items'></span>").appendTo("#mloaddata");
				spancontainer=$('span#record'+data);
			    }
			    ///If an element found
			    if(spancontainer.length){
					
					spancontainer.slideDown('slow', function(){
					spancontainer.html('<div style="float:left; margin-left:5px;"><img src="loading.gif" /></div>');
					spancontainer.fadeIn("slow");
			   		spancontainer.load(loadurl+'?empid='+data+'&sid='+Math.random());
					});
			    }
			    
		
			
		    }
		    else {
		        
			    $('#'+FormID).show(function(){					
			    $('.errormsg').html(data);
			    $('.errormsg').fadeIn(500);
			    });
		    }
	    },
	    error : function(XMLHttpRequest, textStatus, errorThrown) {
		    alert(textStatus);
	    }
	});
	
	ClearFields(FormObject);
	return false;
    }
    return false;    
}

</script>

</head>

<body>


<div id="mforminsert">

  <form id="forminsert" name="forminsert" method="post"  enctype="multipart/form-data" style="margin:0; padding:0; float:left;" onsubmit="return CommonFunction(this,'savedb_file.php', 'loaddata.php','forminsert');">	
  
  <input type="hidden" name="action" value="insert" />
  <input type="hidden" name="emp_id" value="0" />
  <p class="errormsg" style="display:none;">&nbsp;</p>
  <table border="1" cellpadding="2" cellspacing="1" style="border-collapse:collapse; font:12px Verdana, Arial, Helvetica, sans-serif;">
  
  <tr>
  <td align="left"> First Name : </td>
  <td> <input type="text" name="fname" value="" size="20" /></td>
  </tr>
  
  <tr>
  <td align="left"> Middle Name : </td>
  <td> <input type="text" name="mname" value="" size="20" /></td>
  </tr>
  
  <tr>
  <td align="left"> Last Name : </td>
  <td> <input type="text" name="lname" value="" size="20" /></td>
  </tr>
  
  <tr>
  <td align="left"> Email : </td>
  <td> <input type="text" name="email" value="" size="25"/></td>
  </tr>
  
  
  <tr>
  <td align="left"> Contact No : </td>
  <td> <input type="text" name="contact" value="" size="20"/></td>
  </tr>
  
  <tr>
  <td align="left"> Designation : </td>
  <td> <input type="text" name="designation" value="" size="20" /></td>
  </tr>
  
  <tr>
  <td align="left"> Gender : </td>
  <td> 
  <input type="radio" name="gender" value="Male" checked="checked" /> Male &nbsp;&nbsp;<input type="radio" name="gender" value="Female" /> Female
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
	
	
    ?>
     <option value="<?php echo $val; ?>"><?php echo $val; ?></option>
    <?php
    }
    ?>
  </select>
  </td>
  </tr>
  
  
  
  <tr>
  <td align="left"> Comments : </td>
  <td> <textarea name="comments" cols="20" rows="5"></textarea></td>
  </tr>
  
  <tr>
  <td align="left" colspan="2"> <input type="submit" name="Sub" value="Insert" /> &nbsp; <input type="reset"  value="Reset" /> </td>
  </tr>
  
  </table>
  </form>
 
</div>

<div style="font:bold 12px Verdana, Arial, Helvetica, sans-serif; color:#FF0000; display:none;" id="successfulpost">
<p><span>Thank you,</span> Your Data Updated Successfully !!</p>
</div> 
  
  
 
<div id="mloaddata">

<?php
$Q_select = "Select * from `employee` Order by `employee_id` DESC";
$sql_Q = mysql_query($Q_select) or die(mysql_error());	

 while ($row_Q = mysql_fetch_array($sql_Q,MYSQL_BOTH))
 {	
?>

<span id="record<?php echo $row_Q["employee_id"]; ?>" class="items">

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

</span>

 <?php
	}
?>






</div>

 
  
  
  
</body>
</html>
