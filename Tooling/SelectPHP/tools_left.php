<?php
include '../connection.php';
session_start();
$line_on_po = mysqli_real_escape_string($link, $_POST['line_on_po']);
$po_ID = $_SESSION['po_ID'];
// find the total quantity for this lineitem and its ID. We use the lineitem_ID in the next query
$sql = "SELECT l.quantity, l.lineitem_ID
        FROM lineitem l
        WHERE l.po_ID = '$po_ID'
        AND l.line_on_po = '$line_on_po';";
$result = mysqli_query($link, $sql);
if(!$result){
  echo mysqli_error($link);
}

$total_quantity = mysqli_fetch_array($result);
$lineitem_ID = $total_quantity[1];

$sumSql = "SELECT SUM(number_of_items_in_run)
           FROM lineitem_run
           WHERE lineitem_ID = '$lineitem_ID';";
$sumResult = mysqli_query($link, $sumSql);
$sum = mysqli_fetch_array($sumResult);

$total_left = $total_quantity[0] - $sum[0];

echo $total_left;

mysqli_close($link);
?>
