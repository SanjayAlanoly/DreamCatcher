<?php
	
	$name = $_POST["name"];    
	$email = $_POST["email"];	
	$phone = $_POST["phone"];
	$status = $_POST["status"];	
	$under = $_POST["under"];
	$city = $_POST["city"];

	$con = mysql_connect("localhost","Owner");
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("project_cf", $con);

	$sql= "INSERT INTO project_cf.volunteer(name,email,phone,status,parent_id,city_id) 
		VALUES('$name','$email','$phone','$status','$under','$city')";

	if (!mysql_query($sql,$con))
	  {
	  die('Error: ' . mysql_error());
	  }
	
	mysql_close($con);
?>