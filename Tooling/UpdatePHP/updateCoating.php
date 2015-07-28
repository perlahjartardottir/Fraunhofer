<?php
include '../connection.php';

$coating_ID   	 	 = mysqli_real_escape_string($link, $_POST['coating_ID']);
$coating_type 	  	 = mysqli_real_escape_string($link, $_POST['coating_type']);
$coating_description = mysqli_real_escape_string($link, $_POST['coating_description']);

var_dump($coating_ID);
var_dump($coating_type);
var_dump($coating_description);

// check if the coating_ID is valid
$sqlError = "SELECT coating_ID
			 FROM coating
			 WHERE coating_ID = '$coating_ID' ;";
$sqlErrorResult = mysqli_query($link, $sqlError);			

if(mysqli_num_rows($sqlErrorResult) == 0){
	die("invalid ID");
}
// allows us to use ',' instead of 'SET' when making the SQL string
$sql = "UPDATE coating SET coating_type = coating_type";

if(!empty($coating_type)){
	$sql .= ", coating_type = '$coating_type'";
}
if(!empty($coating_description)){
	$sql .= ", coating_description = '$coating_description'";
}

$sql .= "WHERE coating_ID = '$coating_ID';";

$result = mysqli_query($link, $sql);
?>

