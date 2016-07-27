<?php
session_start();
include '../../connection.php';

$sampleSetDate = mysqli_real_escape_string($link, $_POST['sampleSetDate']);
$_SESSION["sampleSetDate"] = $sampleSetDate;

mysqli_close($link);
?>
