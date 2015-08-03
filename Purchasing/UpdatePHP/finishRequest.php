<?php
include '../../connection.php';
$request_ID = mysqli_real_escape_string($link, $_POST['request_ID']);
$sql = "UPDATE order_request
        SET active = 0
        WHERE request_ID = '$request_ID';";
$result = mysqli_query($link, $sql);
?>
