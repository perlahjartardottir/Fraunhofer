<?php
include '../../connection.php';

$request_ID = mysqli_real_escape_string($link, $_POST['request_ID']);

$sql = "DELETE FROM order_request
				WHERE request_ID ='$request_ID';";
$Result = mysqli_query($link, $sql);

mysqli_close($link);
?>
