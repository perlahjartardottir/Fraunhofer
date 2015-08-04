<?php
include '../../connection.php';
session_start();
$supplier_name       = mysqli_real_escape_string($link, $_POST['supplier_name']);
$supplier_address    = mysqli_real_escape_string($link, $_POST['supplier_address']);
$supplier_phone      = mysqli_real_escape_string($link, $_POST['supplier_phone']);
$supplier_fax        = mysqli_real_escape_string($link, $_POST['supplier_fax']);
$supplier_email      = mysqli_real_escape_string($link, $_POST['supplier_email']);
$supplier_contact    = mysqli_real_escape_string($link, $_POST['supplier_contact']);
$supplier_website    = mysqli_real_escape_string($link, $_POST['supplier_website']);

$sql = "INSERT INTO supplier (supplier_name, supplier_address, supplier_phone, supplier_fax, supplier_email, supplier_contact, supplier_website)
        VALUES ('$supplier_name', '$supplier_address', '$supplier_phone', '$supplier_fax', '$supplier_email', '$supplier_contact', '$supplier_website');";
$result = mysqli_query($link, $sql);
 ?>
