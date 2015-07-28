<?php

include '../connection.php';
session_start();
$po_ID = mysqli_real_escape_string($link, $_POST['po_ID']);

$_SESSION["po_ID"] = $po_ID;

$sql = "SELECT p.po_number, p.receiving_date, c.customer_name,  p.shipping_date, p.nr_of_lines 
		FROM pos p, customer c
		WHERE p.customer_ID = c.customer_ID
		AND po_ID = '$po_ID'";

$result = mysqli_query($link, $sql);

while($row = mysqli_fetch_array($result)) {
    echo "<p>".'PO number : '          .$row[0]."</p>";
    echo "<p>".'Receiving Date : '      .$row[1]."</p>";
    echo "<p>".'Customer : '           .$row[2]."</p>";
    echo "<p>".'Shipping Date : '      .$row[3]."</p>";
    echo "<p>".'Number of Lines : '    .$row[4]."</p>";
}

mysqli_close($link);
?>






















