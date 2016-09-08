<?php
include '../../connection.php';

$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);
$name = mysqli_real_escape_string($link, $_POST["name"]);
$acronym = mysqli_real_escape_string($link, $_POST["acronym"]);
$owner = mysqli_real_escape_string($link, $_POST["owner"]);
$comment = mysqli_real_escape_string($link, $_POST["comment"]);

$sql = "UPDATE machine
		SET machine_name = '$name', machine_acronym = '$acronym',
		machine_owner = '$owner', machine_comment='$comment'
		WHERE machine_ID = '$eqID';";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not update analysis equipment: ".mysqli_error($link));
}

mysqli_close($link);

?>