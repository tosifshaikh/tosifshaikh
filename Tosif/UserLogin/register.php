

    <?php
    include('conn.php');
    $fname=$_POST['fname'];
	$hobbiesGroup=implode(",",$_POST['hobbiesGroup']);
    $lname=$_POST['lname'];
    $gender=$_POST['gender'];
    $address=$_POST['address'];
    $contact=$_POST['contact'];
    $pic=$_FILES['profPic']['name'];
    $username=$_POST['username'];
    $password=$_POST['password'];
	//$path='D:\"'.$pic.'"';

	move_uploaded_file($_FILES['profPic']['tmp_name'],$_FILES['profPic']['name']);
    mysqli_query($conn,"INSERT INTO member(username,password,fname,lname,address,contact,picture,gender,hobbies) VALUES('$username','$password','$fname', '$lname','$address','$contact','$pic','$gender','$hobbiesGroup')") or die(mysqli_error($conn));
    header("location:form.php?remarks=success");
    mysqli_close($conn);
    ?>
	
<!--    (fname, lname, gender, address, contact, picture, username, password)-->