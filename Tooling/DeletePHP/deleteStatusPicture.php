<?php
include '../connection.php';
$status_ID = mysqli_real_escape_string($link, $_POST['status_ID']);

$sql =  "DELETE FROM po_status
         WHERE status_ID = '$status_ID'";

mysqli_query($link, $sql);
mysqli_close($link);
?>
