<?php
	
	$sel_vol = $_POST["sel_vol"];
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
	
	$sql ="UPDATE project_cf.volunteer SET name = '$name', email = '$email', phone = '$phone',
			status = $status, parent_id = $under, city_id = $city WHERE volunteer.id = $sel_vol";

	if (!mysql_query($sql,$con))
	  {
	  die('Error: ' . mysql_error());
	  }
	
	print "Details of volunteer " .$name. " has been edited";	
	
	mysql_close($con);
?>