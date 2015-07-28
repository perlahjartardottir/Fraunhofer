<?php

include '../connection.php';

$customer_ID = mysqli_real_escape_string($link, $_POST['customer_ID']);

$sql =  "DELETE FROM customer 
         WHERE customer_ID = $customer_ID";   
$result = mysqli_query($link, $sql);
mysqli_close($link);
?>
