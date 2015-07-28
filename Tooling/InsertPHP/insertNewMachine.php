<?php
include '../connection.php';

// Escape user inputs for security
$mName = mysqli_real_escape_string($link, $_POST['mname']);
$mAcro = mysqli_real_escape_string($link, $_POST['macro']);

$checkSql = "SELECT *
             FROM machine
             WHERE machine_name = '$mName';";
$checkResult = mysqli_query($link, $checkSql);
if(mysqli_num_rows($checkResult) > 0){
  die("This machine name already exists!");
}
$checkSql = "SELECT *
             FROM machine
             WHERE machine_acronym = '$mAcro';";
$checkResult = mysqli_query($link, $checkSql);
if(mysqli_num_rows($checkResult) > 0){
  die("This machine acronym already exists!");
}

// attempt insert query execution
$sql = "INSERT INTO machine(machine_name, machine_acronym) VALUES ('$mName', '$mAcro')";
$result = mysqli_query($link, $sql);

if(!$result){
    die("Input data is fail" . mysqli_error($link));
}


// close connection
mysqli_close($link);
?>
