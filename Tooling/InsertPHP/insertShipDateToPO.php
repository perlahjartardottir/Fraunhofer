<?php
include '../connection.php';
// Escape user inputs for security
session_start();
$po_ID   = $_SESSION["po_ID"];
$date    = mysqli_real_escape_string($link, $_POST['date']);
$comment = mysqli_real_escape_string($link, $_POST['comment']);


// find the overall price of the PO
$sumSql = "SELECT round(sum(l.price * l.quantity),2)
		   FROM lineitem l
		   WHERE l.po_ID = '$po_ID';";
$sumResult = mysqli_query($link, $sumSql);

while($row = mysqli_fetch_array($sumResult)){
	$finalPrice = $row[0];	
}
// sets the sql safe update off so you can update tables.
$sql1 = "SET SQL_SAFE_UPDATES=0;";
$sql2 ="UPDATE pos SET shipping_date = '$date' WHERE po_ID = '$po_ID'";
$sql3 ="UPDATE pos SET final_inspection = '$comment' WHERE po_ID = '$po_ID'";
$sql4 ="UPDATE pos SET final_price = '$finalPrice' WHERE po_ID = '$po_ID'";
// turns the safe update feature back on
$sql5 = "SET SQL_SAFE_UPDATES=1;";

// run all the above queries in the right order
$result1 = mysqli_query($link, $sql1);
$result2 = mysqli_query($link, $sql2);
$result3 = mysqli_query($link, $sql3);
$result4 = mysqli_query($link, $sql4);
$result5 = mysqli_query($link, $sql5);

// Multible error messages so the user knows what went wrong
if(!$result1){
	echo("Error. Input data is fail on setting SQL_SAFE_UPDATES = 0".mysqli_error($link));
}

if(!$result2){
	echo("Error. Input data is fail on setting  shippingdate".mysqli_error($link));
}

if(!$result3){
	echo("Error. Input data is fail on setting final inspection".mysqli_error($link));
}

if(!$result4){
	echo("Error. Input data is fail on calculating final price".mysqli_error($link));
}

if(!$result5){
	echo("Error. Input data is fail on setting SQL_SAFE_UPDATES = 1".mysqli_error($link));
}
// close connection
mysqli_close($link);
?>
