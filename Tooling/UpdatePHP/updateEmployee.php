<?php
include '../connection.php';
session_start();
$employee_ID   = mysqli_real_escape_string($link, $_POST['employee_ID']);
$employee_email = mysqli_real_escape_string($link, $_POST['employee_email']);
$employee_name = mysqli_real_escape_string($link, $_POST['employee_name']);
$employee_phone = mysqli_real_escape_string($link, $_POST['employee_phone']);
$security_level = mysqli_real_escape_string($link, $_POST['security_level']);

//check if the employee_ID is valid
$sqlError = "SELECT employee_ID
			 FROM employee
			 WHERE employee_ID = '$employee_ID' ;";
$sqlErrorResult = mysqli_query($link, $sqlError);

if(mysqli_num_rows($sqlErrorResult) == 0){
	die("invalid ID");
}

// if(!empty($security_level)){
// 	$securityLevelSql = "SELECT security_level
// 	FROM employee
// 	WHERE employee_ID = '$employee_ID';";
// 	$security_level = mysqli_fetch_row(mysqli_query($link, $security_level))[0];
// }

// allows us to use ',' instead of 'SET' when making the SQL string
$sql = "UPDATE employee SET security_level = security_level";

if(!empty($employee_name)){
	$sql .= ", employee_name = '$employee_name'";
}
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
if(!empty($security_level)){
	if(strlen($security_level) === 4){
		$ok = 1;
		for($i = 0; i < strlen($security_level); $i++){
			if($security_level[$i] > 4 || $security_level[$i] < 0){
				$ok = 0;
				break;
			}
		}
		if($ok === 1){
			$sql .= ", security_level = '$security_level'";
		}
		else{
			die("invalid security level");
		}
		
	}
	else{
		die("invalid security level");
	}
}


$sql .= "WHERE employee_ID = '$employee_ID';";
$result = mysqli_query($link, $sql);

// Need to update user session var to display the right name and to be able to view options in views/menu.php.
if(!empty($employee_name)){
	$_SESSION["username"] = $employee_name;
}
?>
