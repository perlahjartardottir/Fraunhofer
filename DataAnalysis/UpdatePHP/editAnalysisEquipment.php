<?php
include '../../connection.php';

$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);
$name = mysqli_real_escape_string($link, $_POST["name"]);
$comment = mysqli_real_escape_string($link, $_POST["comment"]);

$sql = "UPDATE anlys_equipment
		SET anlys_eq_name = '$name', anlys_eq_comment='$comment'
		WHERE anlys_eq_ID = '$eqID';";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not update analysis equipment: ".mysqli_error($link));
}

mysqli_close($link);

?>