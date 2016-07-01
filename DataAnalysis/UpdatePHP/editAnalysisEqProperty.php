<?php
include '../../connection.php';
$IDs = $_POST["propertyIDs"];
$names = $_POST["propertyNames"];
$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);

for($i = 0; $i < count($IDs); $i++){
	$propID = mysqli_real_escape_string($link, $IDs[$i]);
	$name = mysqli_real_escape_string($link, $names[$i]);

	if($propID === '-1'){
		$sql = "INSERT INTO anlys_property(anlys_eq_ID, anlys_prop_name)
		VALUES ('$eqID', '$name');";
	}
	else{
		$sql = "UPDATE anlys_property
		SET anlys_prop_name = '$name'
		WHERE anlys_prop_ID = '$propID';";
	}
	$result = mysqli_query($link, $sql);
	
	echo $result;
	if(!result){
		die("Could not update analysis property: ".mysqli_error($link));
	}
}
mysqli_close($link);
?>