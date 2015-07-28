<?php

include '../connection.php';
$po_ID = mysqli_real_escape_string($link, $_POST['po_ID']);

$sql =  "DELETE FROM po_scan
         WHERE po_ID = '$po_ID'";
            
mysqli_query($link, $sql);
mysqli_close($link);
?>
