<?php

session_start();
include '../../connection.php';

$supplier_name = mysqli_real_escape_string($link, $_POST['supplier_name']);
$supplier_phone = mysqli_real_escape_string($link, $_POST['supplier_phone']);
$supplier_fax = mysqli_real_escape_string($link, $_POST['supplier_fax']);
$net_terms = mysqli_real_escape_string($link, $_POST['net_terms']);
$supplier_email = mysqli_real_escape_string($link, $_POST['supplier_email']);
$supplier_address = mysqli_real_escape_string($link, $_POST['supplier_address']);
$supplier_contact = mysqli_real_escape_string($link, $_POST['supplier_contact']);
$supplier_accountNr = mysqli_real_escape_string($link, $_POST['supplier_accountNr']);
$supplier_website = mysqli_real_escape_string($link, $_POST['supplier_website']);
$supplier_login = mysqli_real_escape_string($link, $_POST['supplier_login']);
$supplier_password = mysqli_real_escape_string($link, $_POST['supplier_password']);
$supplier_notes = mysqli_real_escape_string($link, $_POST['supplier_notes']);

$sql = "UPDATE supplier
        SET supplier_phone = '$supplier_phone', supplier_fax = '$supplier_fax', supplier_email = '$supplier_email', supplier_address = '$supplier_address', net_terms = '$net_terms',
        supplier_contact = '$supplier_contact', supplier_accountNr = '$supplier_accountNr', supplier_website = '$supplier_website', supplier_login = '$supplier_login', supplier_password = '$supplier_password', supplier_notes = '$supplier_notes'
        WHERE supplier_name = '$supplier_name';";
$result = mysqli_query($link, $sql);
?>
