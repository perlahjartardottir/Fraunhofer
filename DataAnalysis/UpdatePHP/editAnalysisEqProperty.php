<?php
include '../../connection.php';
$IDs = $_POST["propertyIDs"];
$names = $_POST["propertyNames"];
$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);

echo"number of ids: ".count($IDs);

for($i = 0; $i < count($IDs); $i++){
	$propID = mysqli_real_escape_string($link, $IDs[$i]);

	$name = mysqli_real_escape_string($link, $names[$i]);
	echo "propID ".$propID." name ".$name;

	if($propID === '-1'){
		$sql = "INSERT INTO anlys_property(anlys_prop_name)
		VALUES ('$name');";
		$result = mysqli_query($link, $sql);
		if(!result){
			die("Could not insert analysis property to anlys_property: ".mysqli_error($link));
		}
		else{
			$latestPropSql = "SELECT MAX(anlys_prop_ID)
			FROM anlys_property;";
			$latestPropResult = mysqli_query($link, $latestPropSql);
			$latestPropRow = mysqli_fetch_row($latestPropResult);
			echo "latest ID ".$latestPropRow[0];
			$eqPropSql = "INSERT INTO anlys_eq_prop(anlys_eq_ID, anlys_prop_ID)
			VALUES ('$eqID','$latestPropRow[0]');";
			$eqPropResult = mysqli_query($link, $eqPropSql);
			if(!result){
				die("Could not insert analysis property to anlys_eq_prop ".mysqli_error($link));
			}
		}
	}
	else{
		$sql = "UPDATE anlys_property
		SET anlys_prop_name = '$name'
		WHERE anlys_prop_ID = '$propID';";

		$result = mysqli_query($link, $sql);
		if(!result){
			die("Could not update analysis property: ".mysqli_error($link));
		}
	}
}
mysqli_close($link);
?>