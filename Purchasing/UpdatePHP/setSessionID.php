<?php
/*
	This file sets the session po_ID to the user input
*/
session_start();
include '../../connection.php';

$order_ID = mysqli_real_escape_string($link, $_POST['order_ID']);

$_SESSION["order_ID"] = $order_ID;

mysqli_close($link);
?>
