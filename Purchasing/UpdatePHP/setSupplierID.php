<?php
/*
	This file sets the session supplier ID
*/
include '../../connection.php';
session_start();

$supplier_ID = mysqli_real_escape_string($link, $_POST['supplier_ID']);

$_SESSION["supplier_ID"] = $supplier_ID;

?>
