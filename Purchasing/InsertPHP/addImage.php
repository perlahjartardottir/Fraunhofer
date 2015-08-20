<?php
include '../../connection.php';
session_start();
$order_ID = $_SESSION["order_ID"];
$redirect = mysqli_real_escape_string($link, $_POST['redirect']);

$fileName = $_FILES['fileToUpload']['name'];
$tmpName  = $_FILES['fileToUpload']['tmp_name'];

$fp      = fopen($tmpName, 'r');
$content = fread($fp, filesize($tmpName));
$content = addslashes($content);
fclose($fp);

$sql = "INSERT INTO purchase_scan (order_ID, scan_image) VALUES ('$order_ID', '$content');";
$result = mysqli_query($link, $sql);

if(!$result){
	echo("Something went wrong : ".mysqli_error($link));
}

// close connection
mysqli_close($link);

// Redirecting to the correct view, depending on whether
// we were adding a tool or editing PO
if($redirect == 'new'){
	header('Location: ../Views/purchaseOrderReceived.php');
}
?>
