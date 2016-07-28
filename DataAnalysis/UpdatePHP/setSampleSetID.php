<?php
session_start();
include '../../connection.php';

$sampleSetID = mysqli_real_escape_string($link, $_POST['sampleSetID']);
$_SESSION["sampleSetID"] = $sampleSetID;
echo"Successfully set sampleSetID";

mysqli_close($link);
?>
