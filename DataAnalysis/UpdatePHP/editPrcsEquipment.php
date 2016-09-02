<?php
include '../../connection.php';

$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);
$name = mysqli_real_escape_string($link, $_POST["name"]);
$acronym = mysqli_real_escape_string($link, $_POST["acronym"]);
$comment = mysqli_real_escape_string($link, $_POST["comment"]);

$sql = "UPDATE prcs_equipment
		SET prcs_eq_name = '$name', prcs_eq_acronym = '$acronym', prcs_eq_comment='$comment'
		WHERE prcs_eq_ID = '$eqID';";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not update process equipment: ".mysqli_error($link));
}

mysqli_close($link);

?>