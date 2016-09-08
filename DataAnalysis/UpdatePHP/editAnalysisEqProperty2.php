<?php
include '../../connection.php';
$IDs = $_POST["propertyIDs"];
$names = $_POST["propertyNames"];
$units = $_POST["propertyUnits"];
$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);

for($i = 0; $i < count($IDs); $i++){
	$propID = mysqli_real_escape_string($link, $IDs[$i]);
	$name = mysqli_real_escape_string($link, $names[$i]);
	$unit = mysqli_real_escape_string($link, $units[$i]);
	
	// New property.
	if($propID === '-1'){
		// Insert to anlys_property
		$sql = "INSERT INTO anlys_property(anlys_prop_name)
		VALUES ('$name');";
		$result = mysqli_query($link, $sql);
		if(!$result){
			die("Could not insert analysis property to anlys_property: ".mysqli_error($link));
		}
		// Insert to anlys_eq_prop
		else{
			$latestPropSql = "SELECT MAX(anlys_prop_ID)
			FROM anlys_property;";
			$latestPropResult = mysqli_query($link, $latestPropSql);
			$latestPropRow = mysqli_fetch_row($latestPropResult);
			echo "latest ID ".$latestPropRow[0];
			$eqPropSql = "INSERT INTO anlys_eq_prop(anlys_eq_ID, anlys_prop_ID, anlys_eq_prop_unit)
			VALUES ('$eqID','$latestPropRow[0]','$unit');";
			$eqPropResult = mysqli_query($link, $eqPropSql);
			if(!$result){
				die("Could not insert analysis property to anlys_eq_prop ".mysqli_error($link));
			}
		}
	}
	else{
		// Update name in anlys_property
		$sql = "UPDATE anlys_property
		SET anlys_prop_name = '$name'
		WHERE anlys_prop_ID = '$propID';";
		$result = mysqli_query($link, $sql);
		
		// This property already exists
		if(!$result){

			echo("Could not update analysis property name: ".mysqli_error($link));
		}
		// Update unit in anlys_eq_prop
		$unitSql = "UPDATE anlys_eq_prop
		SET anlys_eq_prop_unit = '$unit'
		WHERE anlys_eq_ID = '$eqID' AND anlys_prop_ID = '$propID';";
		$unitResult = mysqli_query($link, $unitSql);
		if(!$unitResult){
			die("Could not update anlys eq property: ".mysqli_error($link));
		}

	}
}
mysqli_close($link);
?>