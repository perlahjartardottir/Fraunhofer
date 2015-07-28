<?php
/*
	This file sets the session po_ID to the user input
*/
session_start();
include '../connection.php';

$po_ID = mysqli_real_escape_string($link, $_POST['po_ID']);

$_SESSION["po_ID"] = $po_ID;

mysqli_close($link);
?>
