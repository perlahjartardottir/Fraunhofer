<?php
include '../../connection.php';

$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);


$sql = "UPDATE prcs_equipment
		SET prcs_eq_active = TRUE
		WHERE prcs_eq_ID = '$eqID';";
$result = mysqli_query($link, $sql);

if(!result){
	die("Could not activate process equipment: ".mysqli_error($link));
}

mysqli_close($link);

?>