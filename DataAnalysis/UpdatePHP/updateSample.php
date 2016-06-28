<?php
include '../../connection.php';

$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);
$name = mysqli_real_escape_string($link, $_POST["name"]);
$material = mysqli_real_escape_string($link, $_POST["material"]);
$comment = mysqli_real_escape_string($link, $_POST["comment"]);


mysqli_close($link);

?>