<?php

include '../connection.php';
$discount_ID = mysqli_real_escape_string($link, $_POST['discount_ID']);

$sql =  "DELETE FROM discount
         WHERE discount_ID = $discount_ID";   
mysqli_query($link, $sql);
mysqli_close($link);
?>
