<?php
/*
	This file sets the session currency
*/
session_start();
include '../../connection.php';

$currency = mysqli_real_escape_string($link, $_POST['currency']);

$_SESSION["currency"] = $currency;

mysqli_close($link);
?>
