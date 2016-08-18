<?php
session_start();
include '../../connection.php';

$prcsID = mysqli_real_escape_string($link, $_POST['prcsID']);
$_SESSION["prcsID"] = $prcsID;

mysqli_close($link);
?>
