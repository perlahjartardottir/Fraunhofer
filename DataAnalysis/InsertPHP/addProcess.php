<?php
include '../../connection.php';
session_start();

$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);
$date = mysqli_real_escape_string($link, $_POST["date"]);
$employee = mysqli_real_escape_string($link, $_POST["employee"]);
$coating = mysqli_real_escape_string($link, $_POST["coating"]);
$equipment = mysqli_real_escape_string($link, $_POST["equipment"]);
$position = mysqli_real_escape_string($link, $_POST["position"]);
$rotation = mysqli_real_escape_string($link, $_POST["rotation"]);
$comment = mysqli_real_escape_string($link, $_POST["comment"]);

$sql = "INSERT INTO process(sample_ID, prcs_date, employee_ID, prcs_coating, prcs_eq_ID, prcs_position, prcs_rotation, prcs_comment)
		VALUES ('$sampleID','$date','$employee','$coating','$equipment','$position','$rotation','$comment');";
$result = mysqli_query($link, $sql);
if(!result){
	die("Could not add process ".mysqli_error($link));
}

mysqli_close($link);
?>