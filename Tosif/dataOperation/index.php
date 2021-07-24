<?php 
echo $_SERVER['SERVER_NAME'];
echo $_SERVER['SERVER_NAME'];

session_start();
include('conn.php');
 
?>
<!DOCTYPE html>
<html>
<head>

<script src="jquery-1.11.3.min.js"></script>
<script>
//function deleteFunction(pageUrl,action)
//{
// 	var selectedBox = new Array();
//	$('input[name="checkboxDel[]"]:checked').each(function() {
//		
//	selectedBox.push(this.value);
//     });//end of each function
//	 
////alert("Number of selected Languages: "+selectedBox.length+"\n"+"And, they are: "+selectedBox);
//$.ajax({
//			   type:'POST',
//			   url:pageUrl, 
//			   dataType:"json",
//			   data:{selId:selectedBox,action:action},
//			   success: function(data)
//			   {
//				  
//				for(var i  in data)
//				{
//				  $('tr#rowId_'+data[i]).remove(); 	
//				}
//				
//			   }
//			
//			 });//ajax end
//}

function clearconsole() { 
  console.log(window.console);
  if(window.console || window.console.firebug) {
   console.clear();
  }
}
$(document).ready(function(){
    $("#selectAll").click(function(){
		
        $("input[name='checkboxDel[]']").prop("checked",$("#selectAll").prop("checked"))
    }) 
});
function SearchRecords(pageUrl,wrd)
{
	
	if(wrd!='')
	{
		$("#loadData").load(pageUrl+'?word='+wrd);
		
	}
	else
	{
		$("#loadData").load(pageUrl);
		
     }
	//clearconsole();
}
function pagging(pageUrl,id,action)
{
	 $("#selectAll").removeAttr('checked');
	 $("#loadData").load(pageUrl+'?page='+id+'&action='+action);
			
}
function validateFields(formObj)
{
	//var expression1=/^[a-zA-Z_0-9]+$/;
	//var expression2=/^[a-zA-Z_]+$/;
	
	if(formObj.username.value=='')
	{
		alert('Username is blank');
		formObj.username.focus();
		return false;
	}
	if(formObj.password.value=='')
	{
		alert('password is blank');
		formObj.password.focus();
		return false;
	}
	if(formObj.fname.value=='')
	{
		alert('firstname is blank');
		formObj.fname.focus();
		return false;
	}
	
	if(formObj.lname.value=='')
	{
		alert('lastname is blank');
		formObj.lname.focus();
		return false;
	}

	if($('input[name=gender]:checked').length<=0)
	{
		alert('gender is blank');
		return false;
	}
	if(formObj.address.value=='')
	{
		alert('Address is blank');
		formObj.address.focus();
		return false;
	}
	if(formObj.contact.value=='')
	{
		alert('contact is blank');
		formObj.contact.focus();
		return false;
	}
	
	if($('input[name="hobbiesGroup[]"]:checked').length==0)
	{
		alert('Hobbies is not Checked');
		return false;
	}
	return true;
	
}  
function clearFormFields(formObj)
{
	
	formObj.username.value='';
	formObj.password.value='';
	formObj.fname.value='';
	formObj.lname.value='';
	$('input[name=gender]').removeAttr('checked');
	$('input[name="hobbiesGroup[]"]').removeAttr('checked');
	formObj.address.value='';
	formObj.contact.value='';
}

function actionfunction(pageUrl,action,memId)
{
	if(action=="edit")
	{
		var divContainer=$('#replaceform');
	    divContainer.empty();
		divContainer.load(pageUrl+'?memId='+memId);
	}
	if(action=="delete")
	{
		var confirmBox=confirm('Are you sure you want to Delete?');
		if(confirmBox==true)
		{
			$.ajax({
			   type:'POST',
			   url:pageUrl,
			   data:{memid:memId},
			   success: function(data)
			   {
				 $('tr#rowId_'+data).remove(); 
				 
			   }
			
			 });//ajax end 
			
     	}
		
	}
	 if(action=="checkDel")
	 {
		
		
		var selectedBox = new Array();
		$('input[name="checkboxDel[]"]:checked').each(function() {	
		selectedBox.push(this.value);
     	});//end of each function
	 
	 	var confirmBox=confirm('Are you sure you want to Delete All Records?');
		if(confirmBox==true)
		{ 
		  $("#selectAll").removeAttr('checked');
//alert("Number of selected Languages: "+selectedBox.length+"\n"+"And, they are: "+selectedBox);
		$.ajax({
			   type:'POST',
			   url:pageUrl, 
			   dataType:"json",
			   data:{selId:selectedBox,action:action},
			   success: function(data)
			   {  
				for(var i  in data)
				{
				  $('tr#rowId_'+data[i]).remove(); 	
				}
				 $('#myId').html("Records Are Deleted"); 
				 
			   }
			
			 });//ajax end 
		 }
	  
	 }
}

function callFunction(formId,pageUrl,loadUrl,formObj)
{ 
//serialize o/p action=insert&username=dsg&password=gsdg&fname=dsg&lname=sdg&gender=M&address=sdg&contact=dg&hobbiesGroup%5B%5D=cricket
//serialize Array O/P= [Object { name="action",  value="insert"}, Object { name="username",  value="qwr"}, Object { name="password",  value="qwr"}, Object { name="fname",  value="wqr"}, Object { name="lname",  value="qwr"}, Object { name="gender",  value="M"}, Object { name="address",  value="qwr"}, Object { name="contact",  value="qwr"}, Object { name="hobbiesGroup[]",  value="cricket"}, Object { name="hobbiesGroup[]",  value="chess"}]
   if(validateFields(formObj))
   {
	
	$.ajax({
		   type:'POST',
		   url:pageUrl,
		   data:$('#'+formId).serialize(),
		  
		   success: function(data)
		   {
			 var spanContainer;
			 data=data; 
			 if(data!=0)
			 {
			  //$('#myId').html(data.msg); 
			  $('#myId').fadeIn(900);
			  if($('#rowId_'+data).length)
			  {
				 spanContainer=$('tr#rowId_'+data); 
			  }
			  else
			  {
			   $("<tr id='rowId_"+data+"' align='center'></tr>").appendTo('#loadData');
			    spanContainer=$('tr#rowId_'+data);
			  }
				// spanContainer.load(loadUrl+'?memId='+data);
				// $('#'+formId).trigger('reset');//Or use formObj.reset(); or $('#updateForm')[0].reset();
				  $("#loadData").load(loadUrl); 
				  $('#myId').fadeOut("slow"); 
			 }
			 else
			 {
				$('#errorMsg').fadeIn("slow"); 
			 }
			 
			 
			   
			}
		 });//ajax end
      clearFormFields(formObj);
	  return false; 
	 }
	
   return false; 
}
</script>
</head>
<body>

  <div id='replaceform'>
   <form method="post" id="insertForm" onSubmit="return callFunction(this.id,'add.php','pagging.php',this)">
    <input type="hidden" name="action" value="insert">
    <table  align="center">
     <th colspan="2" style="color:#F00">Sign Up Form</th>
     <tr>
        <td ><div align="right">Username:</div></td>
        <td><input type="text" name="username" id="username" /></td>
     </tr>
     <tr>
        <td><div align="right">Password:</div></td>
        <td><input type="text" name="password" /></td>
      </tr>
      <tr>
        <td width="95"><div align="right">First Name:</div></td>
        <td width="234"><input type="text" name="fname" /></td>
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
        <td><textarea name="address" rows="4" cols="20" ></textarea></td>
      </tr>
      
      <tr>
        <td><div align="right">Contact No.:</div></td>
        <td><input type="text" name="contact" /></td>
      </tr>
      <tr>
    <td align="right">hobbies :</td>
    <td colspan="2">
         <input type="checkbox" name="hobbiesGroup[]" value="cricket" class='hobbi'>
          Cricket
          <input type="checkbox" name="hobbiesGroup[]" value="chess" class='hobbi'>
          Chess
         <input type="checkbox" name="hobbiesGroup[]" value="football" class='hobbi'>
          football
      </td>
  </tr> 
      <tr>
        <td><div align="right"></div></td>
        <td><input name="submit" type="submit" value="Save" id='btn' /></td>
      </tr> 
    </table>
    </form>
    </div>
    <div id="myId"  align="center" style="font-size:14px;color:#F00;display:none;">"Row Updated Successfully"</div>
    <div id="errorMsg"  align="center" style="font-size:14px;color:#F00;display:none;">"Some Error Occur"</div>
   
    <table align="center" style="margin-top:10px;" width="60%" >
    <tr>
       <td width="306" ><input type="checkbox" id="selectAll" >check/uncheck All &nbsp;&nbsp;<input type="button" name="delete" value="Delete Selected" onClick="actionfunction('delete.php','checkDel')" ></td>
       <td width="227">search : <input type="text" id='search' onKeyUp="SearchRecords('pagging.php',this.value,'search')" placeholder="Filter By Username"></td>
      </tr>
      <tr><td colspan="2" align="center"><div id="loadData"><?php include('pagging.php')?></div></td></tr>
    </table>
     
    <!--<table align="center" border="1" id="loadData"  style="margin-top:10px;">
     <tr><td colspan="2"><input type="checkbox" id="selectAll" >check/uncheck All</td><td><input type="button" name="delete" value="Delete Selected" onClick="actionfunction('delete.php','checkDel')"> </td></tr>
       
       <tr>
         <th># </th>
        <th>UserName</th><th>Full Name</th><th>Address</th><th>Mobile</th><th>Gender</th><th>Hobbies</th><th colspan="2">Operation</th></tr>-->
   <?php
   
   	//$selQuery="select * from member ORDER BY mem_id DESC";
// 	$result=mysqli_query($conn,$selQuery)or die(mysqli_error($conn)); 
// 	$totalRec=mysqli_num_rows($result);
//	
//		while($row=mysqli_fetch_array($result))
//	   { 
		 ?>
        
		 <!--<tr align="center" id="rowId_<?php //echo $row[0] ?>" >
         <td><input type="checkbox" name="checkboxDel[]" value="<?php //echo $row[0] ?>"></td>
         <td><?php //echo $row[1]?></td>
         <td><?php //echo $row[3]." ".$row[4]; ?></td>
         <td><?php// echo $row[5]?></td>
         <td><?php //echo $row[6]?></td>
         <td><?php //echo $row[8]?></td>
          <td><?php //echo $row[9]?></td>
         <td><a onClick="actionfunction('edit.php','edit',<?php //echo $row[0] ?>);"><img src="edit_01.gif"/></a></td>
          <td><a onClick="actionfunction('delete.php','delete',<?php //echo $row[0] ?>);"><img src="delete_01.gif"/></a></td>
         </tr>-->
        
   <?php    
       
	//}
	     ?>
       <!-- </table>-->
        <!--<table align="center" border="1">
        <tr><td><a onClick="pagging('pagging.php')">previous</a></td><td><a>next</a></td><td><a>Last</a></td></tr>
        </table> -->
   
</body>
</html>
