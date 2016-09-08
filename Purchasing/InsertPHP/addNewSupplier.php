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
$supplier_login      = mysqli_real_escape_string($link, $_POST['supplier_login']);
$supplier_password   = mysqli_real_escape_string($link, $_POST['supplier_password']);
$supplier_accountNr  = mysqli_real_escape_string($link, $_POST['supplier_accountNr']);
$net_terms           = mysqli_real_escape_string($link, $_POST['net_terms']);
$supplier_notes      = mysqli_real_escape_string($link, $_POST['supplier_notes']);
$credit_card     = mysqli_real_escape_string($link, $_POST['credit_card']);

// Insert all the fields to the supplier, no matter if they are empty or not
$sql = "INSERT INTO supplier (supplier_name, supplier_address, supplier_phone, supplier_fax, supplier_email, supplier_contact, supplier_website, supplier_login, supplier_password, supplier_accountNr, net_terms, supplier_notes, credit_card)
        VALUES ('$supplier_name', '$supplier_address', '$supplier_phone', '$supplier_fax', '$supplier_email', '$supplier_contact', '$supplier_website', '$supplier_login', '$supplier_password', '$supplier_accountNr', '$net_terms', '$supplier_notes', '$credit_card');";
$result = mysqli_query($link, $sql);
 ?>
