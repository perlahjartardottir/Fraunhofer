<?php
session_start();
include '../../connection.php';

$sampleID = mysqli_real_escape_string($link, $_POST['sampleID']);
$_SESSION["sampleID"] = $sampleID;

mysqli_close($link);
?>
