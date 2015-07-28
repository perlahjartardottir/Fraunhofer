<?php

include '../connection.php';

$employee_ID = mysqli_real_escape_string($link, $_POST['employee_ID']);

$sql =  "DELETE FROM employee
         WHERE employee_ID = $employee_ID";   
$result = mysqli_query($link, $sql);
mysqli_close($link);
?>
