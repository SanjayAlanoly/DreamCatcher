<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Add Volunteers</title>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<script type="text/javascript" src="view.js"></script>

</head>
<body id="main_body" >
	
	<img id="top" src="top.png" alt="">
	<div id="form_container">
	
		<h1><a>Add Volunteers</a></h1>
		<form id="form_519035" class="appnitro"  method="post" action="">
					<div class="form_description">
			<h2>Add Volunteers</h2>
			<p>Enter the details of the volunteer</p>
		</div>						
			<ul >
			
					<li id="li_1" >
		<label class="description" for="element_1">Name: </label>
		<div>
			<input id="element_1" name="element_1" class="element text medium" type="text" maxlength="255" value=""/> 
		</div><p class="guidelines" id="guide_1"><small>Enter the name of the volunteer.</small></p> 
		</li>		<li id="li_2" >
		<label class="description" for="element_2">Phone: </label>
		<div>
			<input id="element_2" name="element_2" class="element text medium" type="text" maxlength="255" value=""/> 
		</div><p class="guidelines" id="guide_2"><small>Enter the mobile number of the volunteer.</small></p> 
		</li>		<li id="li_3" >
		<label class="description" for="element_3">Email: </label>
		<div>
			<input id="element_3" name="element_3" class="element text medium" type="text" maxlength="255" value=""/> 
		</div><p class="guidelines" id="guide_3"><small>Enter email address of the volunteer.</small></p> 
		</li>		<li id="li_4" >
		<label class="description" for="element_4">Under: </label>
		<div>
		<select class="element select medium" id="element_4" name="element_4"> 
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
		</div><p class="guidelines" id="guide_4"><small>Which person is this volunteer under?</small></p> 
		</li>		<li id="li_5" >
		<label class="description" for="element_5">Status: </label>
		<div>
		<select class="element select medium" id="element_5" name="element_5"> 
			<option value="" selected="selected"></option>
<option value="1" >Volunteer</option>
<option value="2" >POC</option>
<option value="3" >City Manager</option>
<option value="4" >Regional Manager</option>

		</select>
		</div><p class="guidelines" id="guide_5"><small>What is the status of this volunteer?</small></p> 
		</li>
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="519035" />
			    
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Submit" />
		</li>
			</ul>
		</form>	
		
	</div>
	<img id="bottom" src="bottom.png" alt="">
	</body>
</html>