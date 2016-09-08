<?php
include '../../connection.php';

$prcsID = mysqli_escape_string($link, $_POST["prcsID"]);
$coating = mysqli_escape_string($link, $_POST["coating"]);
$eqID = mysqli_escape_string($link, $_POST["eqID"]);
$position = mysqli_escape_string($link, $_POST["position"]);
$rotation = mysqli_escape_string($link, $_POST["rotation"]);
$comment = mysqli_escape_string($link, $_POST["comment"]);
$run = mysqli_escape_string($link, $_POST["run"]);
$runID = mysqli_escape_string($link, $_POST["runID"]);


$sql = "UPDATE process SET prcs_coating = '$coating', prcs_eq_ID = '$eqID', prcs_position = '$position', prcs_rotation = '$rotation', prcs_comment = '$comment', prcs_run_number = '$run', prcs_run_ID = '$runID'
WHERE prcs_ID = '$prcsID';";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not update process: ".mysqli_error($link));
}

mysqli_close($link);

?>