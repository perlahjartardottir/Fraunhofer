<?php
include '../connection.php';

// Escape user inputs for security
$cName        = mysqli_real_escape_string($link, $_POST['cName']);
$cAddress     = mysqli_real_escape_string($link, $_POST['cAddress']);
$cEmail       = mysqli_real_escape_string($link, $_POST['cEmail']);
$cPhone       = mysqli_real_escape_string($link, $_POST['cPhone']);
$cFax         = mysqli_real_escape_string($link, $_POST['cFax']);
$cContact     = mysqli_real_escape_string($link, $_POST['cContact']);
$cNotes       = mysqli_real_escape_string($link, $_POST['cNotes']);


if($cName == ""){
 	echo"The customer needs a name!";
 }
 else{
// attempt insert query execution
$sql = "INSERT INTO customer(customer_name, customer_address, customer_email, customer_phone, customer_fax, customer_contact, customer_notes)
        VALUES ('$cName', '$cAddress', '$cEmail', '$cPhone', '$cFax', '$cContact', '$cNotes')";

$result = mysqli_query($link, $sql);

if(!$result){
    echo("Input data is fail" . mysqli_error($link));
}
}
// close connection
mysqli_close($link);
?>
