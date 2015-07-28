<?php

include '../connection.php';

$machine_ID = mysqli_real_escape_string($link, $_POST['machine_ID']);

$sql =  "DELETE FROM machine
         WHERE machine_ID = $machine_ID";   
$result = mysqli_query($link, $sql);
mysqli_close($link);
?>
