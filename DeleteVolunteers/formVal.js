


function formValidation(){
	var sel_vol = document.forms["reg"]["sel_vol"].value;
	
	
	if(selVal(sel_vol)){
		return true;
	}
	return false;
}



function selVal(sel_vol){  
	if(sel_vol == ""){  
		alert('Volunteer to be deleted is not selected');  
		document.getElementById("sel_vol").focus();  
		return false;  
	}  
	else{  
		return true;  
	}  
}
