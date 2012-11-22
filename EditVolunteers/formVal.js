


function formValidation(){
	var sel_vol = document.forms["reg"]["sel_vol"].value;
	var name = document.forms["reg"]["name"].value;
	var phone = document.forms["reg"]["phone"].value;
	var email = document.forms["reg"]["email"].value;
	var under = document.forms["reg"]["under"].value;
	var status = document.forms["reg"]["status"].value;
	var city = document.forms["reg"]["city"].value;
	
	if(selVal(sel_vol)){
	if(nameVal(name)){
	if(phoneVal(phone)){
	if(emailVal(email)){
	if(underVal(under)){
	if(statusVal(status)){
	if(cityVal(city)){
		return true;
	}}}}}}}
	return false;
}s


function nameVal(name){
	var name_len = name.length;
	if(name_len==0){
		alert("Name should not be empty");
		document.getElementById("name").focus();
		return false;
	}
	return true;
}

function selVal(sel_vol){  
	if(sel_vol == ""){  
		alert('Volunteer to be edited is not selected');  
		document.getElementById("sel_vol").focus();  
		return false;  
	}  
	else{  
		return true;  
	}  
}

function phoneVal(phone){
	var phone_len = phone.length;
	var numbers = /^[0-9]+$/;  
	if(!(phone.match(numbers)) || phone_len!=10){
		alert("Phone number should be a ten digit number");
		document.getElementById("phone").focus();
		return false;
	}
	return true;
}

function emailVal(email){  
	var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
	if(email.match(mailformat)){  
		return true;  
	}  
	else{  
		alert("Email address is invalid");  
		document.getElementById("email").focus();  
		return false;  
	}  
}  	
	
function underVal(under){  
	if(under == ""){  
		alert('Under field is not selected');  
		document.getElementById("under").focus();  
		return false;  
	}  
	else{  
		return true;  
	}  
}

function statusVal(status){  
	if(status == ""){  
		alert('Status field is not selected');  
		document.getElementById("status").focus();  
		return false;  
	}  
	else{  
		return true;  
	}  
}

function cityVal(city){  
	if(city == ""){  
		alert('City field is not selected');  
		document.getElementById("city").focus();  
		return false;  
	}  
	else{  
		return true;  
	}  
}  	