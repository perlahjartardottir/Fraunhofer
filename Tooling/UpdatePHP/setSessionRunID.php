<?php
/*
	This file sets the session run_ID
	POSSIBLY NOT NEEDED!
*/
session_start();
include '../connection.php';

$run_ID = mysqli_real_escape_string($link, $_POST['run_ID']);

$_SESSION["run_ID"] = $run_ID;

mysqli_close($link);
?>
