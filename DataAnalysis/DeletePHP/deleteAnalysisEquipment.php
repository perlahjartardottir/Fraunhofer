<?php
include '../../connection.php';

$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);

// Try to delete the equipment.
$tryDeleteSql = "DELETE FROM anlys_equipment WHERE anlys_eq_ID = '$eqID';";
$tryDeleteResult = mysqli_query($link, $tryDeleteSql);
if(!$tryDeleteResult){
	// There is data depending on the equipment, deacticate it. 
	$sql = "UPDATE anlys_equipment
			SET anlys_eq_active= FALSE
			WHERE anlys_eq_ID = '$eqID';";
	$result = mysqli_query($link, $sql);

	if(!$result){
		die("Could not deactive analysis equipment: ".mysqli_error($link));
	}
}

mysqli_close($link);

?>