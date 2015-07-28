<?php
/*
	This file sets the session lineitem_ID
	used when adding discount to runs.
*/
session_start();
include '../connection.php';

$lineitem_ID = mysqli_real_escape_string($link, $_POST['lineitem_ID']);

$_SESSION["lineitem_ID"] = $lineitem_ID;

mysqli_close($link);
?>
