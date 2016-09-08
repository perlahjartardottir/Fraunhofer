<?php
include '../../connection.php';

$propID = mysqli_real_escape_string($link, $_POST["propID"]);
$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);

// Delete connection between equipment and property. 
$eqPropSql = "DELETE FROM anlys_eq_prop
WHERE anlys_eq_ID = '$eqID' AND anlys_prop_ID = '$propID';";
$eqPropResult = mysqli_query($link, $eqPropSql);
if(!$eqPropResult){
	die("Error1: ".mysqli_error($link));
}

// If there is no more equipment connected to the property, delete the property.
$sql = "DELETE FROM anlys_property
WHERE anlys_prop_ID = '$propID';";
$result= mysqli_query($link, $sql);

if(!$result){
	die("Error: ".mysqli_error($link));
}

mysqli_close($link);

?>