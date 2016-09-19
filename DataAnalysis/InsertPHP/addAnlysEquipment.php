<?php
include '../../connection.php';

$name = mysqli_real_escape_string($link, $_POST["eqName"]);
$comment = mysqli_real_escape_string($link, $_POST["eqComment"]);
$prop = mysqli_real_escape_string($link, $_POST["eqProp"]);
$propUnit = mysqli_real_escape_string($link, $_POST["eqPropUnit"]);

// Add the equipment
$sql = "INSERT INTO anlys_equipment (anlys_eq_name, anlys_eq_comment, anlys_eq_active)
VALUE ('$name', '$comment', TRUE);";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not insert anlys equipment: ".mysqli_error($link));
}

// Get the ID of the newly inserted equipment.
$eqID = mysqli_insert_id($link);

// Connect the property to the equipment.
$eqPropSql = "INSERT INTO anlys_eq_prop(anlys_eq_ID, anlys_prop_ID, anlys_eq_prop_unit)
VALUES ('$eqID','$prop','$propUnit');";
$eqPropResult = mysqli_query($link, $eqPropSql);
if(!$result){
	die("Could not insert analysis property to anlys_eq_prop ".mysqli_error($link));
}


mysqli_close($link);

?>