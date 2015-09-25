<?php
include '../connection.php';

$employee_ID   = mysqli_real_escape_string($link, $_POST['employee_ID']);
$employee_email = mysqli_real_escape_string($link, $_POST['employee_email']);
$employee_phone = mysqli_real_escape_string($link, $_POST['employee_phone']);

// allows us to use ',' instead of 'SET' when making the SQL string
$sql = "UPDATE employee SET security_level = security_level";

if(!empty($employee_email)){
	if(filter_var($employee_email, FILTER_VALIDATE_EMAIL)){
		$sql .= ", employee_email = '$employee_email'";
	}else{
		die("invalid email");
	}
}
if(!empty($employee_phone)){
	$phone = preg_replace('/[^0-9]/', '', $_POST['employee_phone']);
	if(strlen($phone) === 10) {
    	$sql .= ", employee_phone = '$employee_phone'";
	}else{
		die("invalid phone number");
	}

}

$sql .= "WHERE employee_ID = '$employee_ID';";
$result = mysqli_query($link, $sql);
?>
