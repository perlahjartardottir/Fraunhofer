<?php
include '../../connection.php';
session_start();
$supplier_phone    = mysqli_real_escape_string($link, $_POST['supplier_phone']);
$supplier_address  = mysqli_real_escape_string($link, $_POST['supplier_address']);
$supplier_email    = mysqli_real_escape_string($link, $_POST['supplier_email']);

// Look for an existing supplier that has the same address and if we find one then the user will see a warning
// letting him know that there already exists a supplier with the same address.
// So he might be trying to create a supplier that already exists, and this will hopefully prevent that.
$addressSql = "SELECT supplier_name FROM supplier
               WHERE supplier_address = '$supplier_address';";
$addressResult = mysqli_query($link, $addressSql);
if($supplier_address != ''){
  if(mysqli_num_rows($addressResult) > 0){
    $row = mysqli_fetch_array($addressResult);
    echo($row[0] . " already has this address.\r\n");
  }
}

// Look for an existing supplier with the same email
$emailSql = "SELECT supplier_name FROM supplier
               WHERE supplier_email = '$supplier_email';";
$emailResult = mysqli_query($link, $emailSql);
if($supplier_email != ''){
  if(mysqli_num_rows($emailResult) > 0){
    $row = mysqli_fetch_array($emailResult);
    echo($row[0] . " already has this email.\r\n");
  }
}

// Look for an existing supplier with the same phone number 
$phoneSql = "SELECT supplier_name FROM supplier
               WHERE supplier_phone = '$supplier_phone';";
$phoneResult = mysqli_query($link, $phoneSql);
if($supplier_phone != ''){
  if(mysqli_num_rows($phoneResult) > 0){
    $row = mysqli_fetch_array($phoneResult);
    echo($row[0] . " already has this phone number.\r\n");
  }
}

 ?>
