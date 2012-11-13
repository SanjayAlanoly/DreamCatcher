<?php
	$con = mysql_connect("localhost","Owner","");
	if(!$con)
		die("Could not connect:" . mysql_error());
	
	mysql_select_db("project_cf",$con);
	
	$sql = "SELECT id,name FROM project_cf.volunteer WHERE status <> 'unknown' ORDER BY name";
	if(!($result = mysql_query($sql,$con)))
			die("Error1:" . mysql_error());
	while($temp = mysql_fetch_assoc($result)) {
		$strA = $temp['id'];
		$strB = $temp['name'];
		print "Id: $strA Name: $strB \n";
	}
	mysql_close($con);
			
?>