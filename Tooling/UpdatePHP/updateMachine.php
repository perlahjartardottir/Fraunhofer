<?php
include '../connection.php';

$machine_ID   	 = mysqli_real_escape_string($link, $_POST['machine_ID']);
$machine_name 	 = mysqli_real_escape_string($link, $_POST['machine_name']);
$machine_acronym = mysqli_real_escape_string($link, $_POST['machine_acronym']);
$machine_comment = mysqli_real_escape_string($link, $_POST['machine_comment']);


// check if the machine_ID is valid
$sqlError = "SELECT machine_ID
			 FROM machine
			 WHERE machine_ID = '$machine_ID' ;";
$sqlErrorResult = mysqli_query($link, $sqlError);
if(mysqli_num_rows($sqlErrorResult) == 0){
	die("Error! Invalid ID");
}

// Check if machine name already exists
$checkSql = "SELECT *
						 FROM machine
						 WHERE machine_name = '$machine_name';";
$checkResult = mysqli_query($link, $checkSql);
if(mysqli_num_rows($checkResult) > 0){
	die("Error! This machine name already exists");
}

// Check if machine acronym already exists
$checkSql = "SELECT *
						 FROM machine
						 WHERE machine_acronym = '$machine_acronym';";
$checkResult = mysqli_query($link, $checkSql);
if(mysqli_num_rows($checkResult) > 0){
	die("Error! This machine acronym already exists");
}

// allows us to use ',' instead of 'SET' when making the SQL string
$sql = "UPDATE machine SET machine_name = machine_name";

if(!empty($machine_name)){
	$sql .= ", machine_name = '$machine_name'";
}
if(!empty($machine_acronym)){
	$sql .= ", machine_acronym = '$machine_acronym'";
}
if(!empty($machine_comment)){
	$sql .= ", machine_comment = '$machine_comment'";
}


$sql .= "WHERE machine_ID = '$machine_ID';";

$result = mysqli_query($link, $sql);
?>
