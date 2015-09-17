<?php
session_start();
include '../../connection.php';

$sql = "UPDATE quote
        SET create_request = 0
        WHERE create_request = 1;";
$result = mysqli_query($link, $sql);
?>
