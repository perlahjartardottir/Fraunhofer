Implement when you have more info.

<?php
include '../connection.php';
// Escape user inputs for security
$coatingType = mysqli_real_escape_string($link, $_POST['coatingType']);
$coatingDesc = mysqli_real_escape_string($link, $_POST['coatingDesc']);
 var_dump($coatingDesc);
// attempt insert query execution
$sql = "INSERT INTO coating(coating_type, coating_description) VALUES ('$coatingType', '$coatingDesc');";
if(mysqli_query($link, $sql)){
    echo "Records added successfully.";
} else{
    echo "ERROR: Could not execute $sql. " . mysqli_error($link);
}
 
// close connection
mysqli_close($link);
?>


