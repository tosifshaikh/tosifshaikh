<style>
.pagelink
{
	cursor:pointer;
}

</style>

<?php
include('conn.php');
$selectQuery="select * from member";
$result=mysqli_query($conn,$selectQuery);
$totalRec= mysqli_num_rows($result);


$pageLimit=3;
$totalpages=ceil($totalRec/$pageLimit);



if(isset($_GET['page']))
{
	//$page=$_GET['page'];
	//$offset=$_GET['page'];
	//echo  $offset;
	
	  $page=$_GET['page'];
	  $offset=$_GET['page'];
  
}

if(isset($_GET['action']))
{
	$action=$_GET['action'];
	if($action=='previous')
	{
		$offset=$page-$pageLimit;
	    if($offset<0)
		{
			$offset=0;
		}


	}
	else if($action=="next")
	{
		
		// $offset=$page+$pageLimit;
		$offset=$page+$pageLimit;
	    if($offset>=$totalRec)
		 {
			$offset=$offset-$pageLimit;
		 }
	}
	else if($action=="first")
	{
		$offset=0;
	}
	else if($action=="last")
	{
		$offset=$totalRec-$pageLimit;
		if($offset<0)
		{
			$offset=0;
		}
	}
	if($action=='noRows')
	{
		$pageLimit=$_GET['page'];
		$offset=0;
	}
	//if($action=='pagging')
//	{
//		$offset=$_GET['page'];
//	}
	

}
else
{
	$offset=0;
}

$record="select * from member order by mem_id DESC limit $offset,$pageLimit ";
  $fetchRec=mysqli_query($conn,$record);
if(isset($_GET['word']))
{
	$record="select * from member where username like '".$_GET['word']."%'";
    $fetchRec=mysqli_query($conn,$record);
	if(mysqli_num_rows($fetchRec)<=0)
	{
		echo "NO RECORD FOUND";
	}
	//if(isset($_GET['word']))
//	{
//		echo $_GET['word'];
//		//$record="select * from member where username like '".$_GET['word']."%'";
//        //$fetchRec=mysqli_query($conn,$record);
//	}
}
?>
 <!--<table align="center" border="1" id="loadData"  style="margin-top:10px;">
     <tr><td colspan="2"><input type="checkbox" id="selectAll" >check/uncheck All</td><td><input type="button" name="delete" value="Delete Selected" onClick="actionfunction('delete.php','checkDel')"> </td></tr>-->
       <table border="1" width="100%">
       <tr>
        
         <th>#</th>
        <th>UserName</th>
        <th>Full Name</th>
        <th>Address</th>
        <th>Mobile</th>
        <th>Gender</th>
        <th>Hobbies</th>
        <th colspan="2">Operation</th>
        </tr>
        
<?php

while($row=mysqli_fetch_array($fetchRec))
{
	
	?>  
         <tr align="center" id="rowId_<?php echo $row[0] ?>" >
         
         <td><input type="checkbox" name="checkboxDel[]" value="<?php echo $row[0] ?>"></td>
        
         <td><?php echo $row[1]?></td>
          <td><?php echo $row[3]." ".$row[4]; ?></td>
         <td><?php echo $row[5]?></td>
         <td><?php echo $row[6]?></td>
         <td><?php echo $row[8]?></td>
          <td><?php echo $row[9]?></td>
         <td><a onClick="actionfunction('edit.php','edit',<?php echo $row[0] ?>);"><img src="edit_01.gif"/></a></td>
          <td><a onClick="actionfunction('delete.php','delete',<?php echo $row[0] ?>);"><img src="delete_01.gif"/></a></td>
          </tr>
<?php
  
}

?>
<tr>
	<td colspan="10">
    	<table align="center">
 <tr><td><a onClick="pagging('pagging.php',<?php echo $offset ?>,'first')" class="pagelink"><input type="button" value="First"></a></td><td><a onClick="pagging('pagging.php',<?php echo $offset ?>,'previous')" class="pagelink"><input type="button" value="Previous"></a></td><td><?php 
              $l=1;
              for($i=0;$i<$totalRec;$i=$i+$pageLimit)
			 //for($i=0;$i<$totalpages;$i++)
			  {
				  
				  echo "<a onClick=pagging('pagging.php','$i','pagging')><input type='button'  value='$l' ></a>";
				  $l++;
			  }
			  
				?>
			  
               </td><td><a onClick="pagging('pagging.php',<?php echo $offset ?>,'next')" class='pagelink'><input type="button" value="Next"></a></td><td><a onClick="pagging('pagging.php',<?php echo $offset ?>,'last')" class='pagelink'><input type="button" value="Last"></a></td>
               <td><select onChange="pagging('pagging.php',this.value,'noRows')" >
                          <option value="0">Select Limit</option>
                           <option value="3">3</option>
                           <option value="6">6</option>
                           <option value="9">9</option>
                           <option value="12">12</option>
                            <option value="<?php echo $totalRec ?>"><?php echo $totalRec ?></option>
                          
                  </select></td>
               </tr>
 </table>       
	</td>
</tr>
</table>
