<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td style="height: 40px;">
			<span class="special"><a id="a1" href="Orders-listing.php?type=0"><b>Home</b></a> | Admin,<a href="logout.php"> Logout</a></span>
		</td>
	</tr>
	<!-- Order Management -->
	<tr>
		<td bgcolor="#f5f5f5">
			<table style="background-color: #cccccc;" border="0" cellpadding="4" cellspacing="1" width="100%">
					<tr>
						<td style="background-color: #e0e0e0;"><span class="special">Order Management</span></td>
					</tr>
<?php
			$strall = "SELECT count(*) as tot FROM `customerordermaster`"; 
            $totall = mysqli_fetch_array(mysqli_query($con,$strall));

			$strallP = "SELECT count(*) as tot FROM `customerordermaster` where orderstatus = 1"; 
            $totallP = mysqli_fetch_array(mysqli_query($con,$strallP));

			$strallD = "SELECT count(*) as tot FROM `customerordermaster` where orderstatus = 2"; 
            $totallD = mysqli_fetch_array(mysqli_query($con,$strallD));

			$strallCo = "SELECT count(*) as tot FROM `customerordermaster` where orderstatus = 3"; 
            $totallCo = mysqli_fetch_array(mysqli_query($con,$strallCo));

			$strallCa = "SELECT count(*) as tot FROM `customerordermaster` where orderstatus = -1"; 
            $totallCa = mysqli_fetch_array(mysqli_query($con,$strallCa));
              
?>					
					<tr>
						<td style="background-color: #ffffff;">
							<a href="Orders-listing.php?otype=1">Pending Orders [<?php echo $totallP["tot"]; ?>]</a>
						</td>
					</tr>
					<tr>
						<td style="background-color: #ffffff;">
							<a href="Orders-listing.php?otype=2">Delivered Orders [<?php echo $totallD["tot"]; ?>]</a>
						</td>
					</tr>
					<tr>
						<td style="background-color: #ffffff;">
							<a href="Orders-listing.php?otype=3">Completed Orders [<?php echo $totallCo["tot"]; ?>]</a>
						</td>
					</tr>
					<tr>
						<td style="background-color: #ffffff;">
							<a href="Orders-listing.php?otype=-1">Cancelled Orders [<?php echo $totallCa["tot"]; ?>]</a>
						</td>
					</tr>
					<tr>
						<td style="background-color: #ffffff;">
							<a href="Orders-listing.php">All Orders [<?php echo $totall["tot"]; ?>]</a>
						</td>
					</tr>
			</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	
	<!-- Customer Management -->
<?php
			$stract = "SELECT count(*) as tot FROM `customer` WHERE authenticated = 1"; 
            $totact = mysqli_fetch_array(mysqli_query($con,$stract));
			
			$strinact = "SELECT count(*) as tot FROM `customer` WHERE authenticated = 0"; 
            $totinact = mysqli_fetch_array(mysqli_query($con,$strinact));
?>
	<tr>
		<td bgcolor="#f5f5f5">
			<table style="background-color: #cccccc;" border="0" cellpadding="4" cellspacing="1" width="100%">
					<tr>
						<td style="background-color: #e0e0e0;"><span class="special">Customer Management</span></td>
					</tr>
					<tr>
						<td style="background-color: #ffffff;">
							<a id="A9" href="viewcustomers.php?type=1">Active Customers</a>[<?php echo $totact["tot"]; ?>]<br />
							<a href="viewcustomers.php?type=0">Inactive Customers</a>[<?php echo $totinact["tot"]; ?>]
						</td>
					</tr>
			</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	
	<!-- Zip Code Management -->
<?php
			$stract = "SELECT count(*) as tot FROM `tblzipcodes`"; 
            $totact = mysqli_fetch_array(mysqli_query($con,$stract));
			
			$stract = "SELECT count(*) as totstores FROM `stores`"; 
            $totStores = mysqli_fetch_array(mysqli_query($con,$stract));
?>
	<tr>
		<td bgcolor="#f5f5f5">
			<table style="background-color: #cccccc;" border="0" cellpadding="4" cellspacing="1" width="100%">
					<tr>
						<td style="background-color: #e0e0e0;"><span class="special">Zip Code Management</span></td>
					</tr>
					<tr>
						<td style="background-color: #ffffff;">
							<a id="A9" href="zipcodes.php" >Zip Codes</a>[<?php echo $totact["tot"]; ?>]<br />
						</td>
					</tr>
                    <tr>
						<td style="background-color: #e0e0e0;"><span class="special">Store Management</span></td>
					</tr>
                    <tr>
						<td style="background-color: #ffffff;">
							<a id="A9" href="store.php" >Stores</a>[<?php echo $totStores["totstores"]; ?>]<br />
						</td>
					</tr>
					
			</table>
		</td>
	</tr>
</table>