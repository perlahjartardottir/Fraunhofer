<?php
include '../../connection.php';

$propID = mysqli_real_escape_string($link, $_POST['propID']);
$propName = mysqli_real_escape_string($link, $_POST['propName']);

// check if the property ID is valid
$IDSql = "SELECT anlys_prop_ID
			 FROM anlys_property
			 WHERE anlys_prop_ID = '$propID';";
$IDResult = mysqli_query($link, $IDSql);
if(mysqli_num_rows($IDResult) === 0){
	die("Error! Invalid ID");
}

// Check if machine name already exists
$nameSql = "SELECT anlys_prop_name
FROM anlys_property
WHERE anlys_prop_name = '$propName';";
$nameResult = mysqli_query($link, $nameSql);
if(mysqli_num_rows($nameResult) > 0){
	die("Error! This property name already exists");
}


$sql = "UPDATE anlys_property SET anlys_prop_name = '$propName'
WHERE anlys_prop_ID = '$propID';";
$result = mysqli_query($link, $sql);
if(!$result){
	die("Could not update analysis property ".mysqli_error($link));
}
mysqli_close($link);
?>