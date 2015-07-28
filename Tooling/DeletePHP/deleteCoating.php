<?php
include '../connection.php';

$coating_ID = mysqli_real_escape_string($link, $_POST['coating_ID']);

$sql =  "DELETE FROM coating 
         WHERE coating_ID = $coating_ID";   
$result = mysqli_query($link, $sql);
mysqli_close($link);
?>
