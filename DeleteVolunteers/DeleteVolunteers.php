<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Delete Volunteer</title>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<script type="text/javascript" src="view.js"></script>
<script type="text/javascript" src="formVal.js"></script>

</head>
<body id="main_body" >
	
	<img id="top" src="top.png" alt="">
	<div id="form_container">
	
		<h1><a>Delete Volunteer</a></h1>
		<form name = "reg" id="form_524864" class="appnitro"  method="post"  action="RemVol.php" onSubmit="return formValidation();">
					<div class="form_description">
			<h2>Delete Volunteer</h2>
			<p>Select the volunteer to be deleted</p>
		</div>						
			<ul >
			
					<li id="li_4" >
		<label class="description" for="sel_vol">Select Volunteer: </label>
		<div>
		<select class="element select medium" id="sel_vol" name="sel_vol"> 
			<option value="" selected="selected"></option>
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
					echo "<option value=\"$strA\">$strB</option>\n";
					
				}
				mysql_close($con);
			?>

		</select>
		</div><p class="guidelines" id="guide_4"><small>Select the volunteer to be deleted.</small></p> 
		</li>
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="524864" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>	
		
	</div>
	<img id="bottom" src="bottom.png" alt="">
	</body>
</html>