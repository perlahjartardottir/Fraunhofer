<?php
include '../../connection.php';

$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);

// Try to delete the equipment.
$tryDeleteSql = "DELETE FROM prcs_equipment WHERE prcs_eq_ID = '$eqID';";
$tryDeleteResult = mysqli_query($link, $tryDeleteSql);
if(!$tryDeleteResult){
	// If there is data depending on it, deactivate the equipment.
	$sql = "UPDATE prcs_equipment
		SET prcs_eq_active= FALSE
		WHERE prcs_eq_ID = '$eqID';";
	$result = mysqli_query($link, $sql);

	if(!$result){
		die("Could not deactive analysis equipment: ".mysqli_error($link));
	}
}



mysqli_close($link);

?>