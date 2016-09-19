<?php
include '../../connection.php';
$propertyIDs = $_POST["propertyIDs"];
$eqPropIDs = $_POST["eqPropIDs"];
$units = $_POST["propertyUnits"];
$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);

for($i = 0; $i < count($eqPropIDs); $i++){
	$propID = mysqli_real_escape_string($link, $propertyIDs[$i]);
	$eqPropID = mysqli_real_escape_string($link, $eqPropIDs[$i]);
	$unit = mysqli_real_escape_string($link, $units[$i]);

	// We are connecting a new property
	if($eqPropID === '-1'){
		$newEqPropSql = "INSERT INTO anlys_eq_prop (anlys_eq_ID, anlys_prop_ID, anlys_eq_prop_unit)
		VALUES ('$eqID', '$propID', '$unit');";
		$newEqPropResult = mysqli_query($link, $newEqPropSql);
		if(!$newEqPropResult){
			die("Could not insert analysis property: ".mysqli_error($link));
		}
	}
	else{
		// Link the newly chosen property to the equipment.
		$eqPropSql = "UPDATE anlys_eq_prop
		SET anlys_prop_ID = '$propID'
		WHERE anlys_eq_prop_ID = '$eqPropID';";
		$eqPropResult = mysqli_query($link, $eqPropSql);
		if(!$eqPropResult){
			die("Could not update analysis property: ".mysqli_error($link));
		}
				// Update unit in anlys_eq_prop.
		$unitSql = "UPDATE anlys_eq_prop
		SET anlys_eq_prop_unit = '$unit'
		WHERE anlys_eq_prop_ID = '$eqPropID';";
		$unitResult = mysqli_query($link, $unitSql);
		if(!$unitResult){
			die("Could not update analysis property unit: ".mysqli_error($link));
		}
	}
}
mysqli_close($link);
?>