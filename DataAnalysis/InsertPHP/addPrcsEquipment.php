<?php
include '../../connection.php';

$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);
$name = mysqli_real_escape_string($link, $_POST["name"]);
$acronym = mysqli_real_escape_string($link, $_POST["acronym"]);
$comment = mysqli_real_escape_string($link, $_POST["comment"]);

$sql = "INSERT INTO prcs_equipment (prcs_eq_name, prcs_eq_acronym, prcs_eq_comment, prcs_eq_active)
VALUE ('$name', '$acronym', '$comment', TRUE);";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not insert process equipment: ".mysqli_error($link));
}

mysqli_close($link);

?>