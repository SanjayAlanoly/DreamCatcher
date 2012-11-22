<?php
	
	$sel_vol = $_POST["sel_vol"];    
	

	$con = mysql_connect("localhost","Owner");
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("project_cf", $con);

	$sql= "DELETE FROM project_cf.volunteer WHERE volunteer.id = $sel_vol";

	if (!mysql_query($sql,$con))
	  {
	  die('Error: ' . mysql_error());
	  }
	
	print "Volunteer has been deleted from the database";	
	
	mysql_close($con);
?>