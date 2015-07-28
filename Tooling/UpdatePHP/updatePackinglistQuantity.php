<?php
include '../connection.php';

$lineitem_ID = mysqli_real_escape_string($link, $_POST['lineitem_ID']);
$quantity    = mysqli_real_escape_string($link, $_POST['quantity']);

$sql = "UPDATE lineitem
        SET quantity_on_packinglist = '$quantity'
        WHERE lineitem_ID = '$lineitem_ID'";   
$result = mysqli_query($link, $sql);

if (!$result) {
    $message  = 'Invalid query: ' . mysqli_error();
}
mysqli_close($link);
?>