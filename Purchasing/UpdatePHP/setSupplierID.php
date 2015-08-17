<?php
/*
	This file sets the session supplier ID
*/
session_start();
include '../../connection.php';

$supplier_ID = mysqli_real_escape_string($link, $_POST['supplier_ID']);

$_SESSION["supplier_ID"] = $supplier_ID;

mysqli_close($link);
?>
