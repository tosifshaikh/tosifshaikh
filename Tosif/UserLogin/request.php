<?php
session_start();
include('conn.php');

if(!isset($_SESSION['admin']))
{
	header("location:form.php");
}
 $sql="select * from member WHERE flg=0";
 $res=mysqli_query($conn,$sql);

?>
<tr><th>#</th><th align="center">Id</th><th>UserName</th><th>Fname</th><th colspan="2">Action</th></tr>
       <form method="post" action="delete.php" >

	<?php
    while($row=mysqli_fetch_array($res))
    { ?>
      
    <tr align="center"><td><input type="checkbox" name="checkbox[]" value="<?php echo $row[0] ?>"></td><td ><?php echo $row[0] ?></td><td><?php echo $row[1] ?></td><td ><?php echo $row[3] ?></td><td colspan="2" align="center"><a href='request.php?Approve_id=<?php echo $row[0] ?>&flag=1' class="myButton2">Approve</a>
    
    </td></tr>
    <?php } ?>
    <tr><td align="center"><input type="submit" name="btnDel" value="Delete" ></td></tr>
    <!--</table>-->
   
     
   </form>
    <?php
    if(isset($_GET['Approve_id']))
    {
        $Approve_id=$_GET['Approve_id'];
        $approveQuery="update member set flg=1 where mem_id='$Approve_id'";
        $result=mysqli_query($conn,$approveQuery);
        if($result)
		{
			$_SESSION['flag']=1;	
			//$GLOBALS['flag']=2;
			header('location:Search.php');
		}
    }
   
	 
	?>
  
<!--</td>
  </tr>-->