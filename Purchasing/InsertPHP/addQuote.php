<?php
include '../../connection.php';
session_start();
$redirect     = mysqli_real_escape_string($link, $_POST['redirect']);
$quote_number = mysqli_real_escape_string($link, $_POST['quote_number']);
$quote_date   = mysqli_real_escape_string($link, $_POST['quote_date']);
$description  = mysqli_real_escape_string($link, $_POST['quoteDescription']);
$supplier     = mysqli_real_escape_string($link, $_POST['supplierList']);
$order_ID = $_SESSION["order_ID"];

// Find the supplier ID
$supplierSql = "SELECT supplier_ID
                FROM supplier
                WHERE supplier_name = '$supplier';";
$supplierResult = mysqli_query($link, $supplierSql);

$row = mysqli_fetch_array($supplierResult);
$supplier_ID = $row[0];

$fileName = $_FILES['fileToUpload']['name'];
$fileType = $_FILES['fileToUpload']['type'];
$fileSize = $_FILES['fileToUpload']['size'];
$tmpName  = $_FILES['fileToUpload']['tmp_name'];

$fp      = fopen($tmpName, 'r');
$content = fread($fp, filesize($tmpName));
$content = addslashes($content);
fclose($fp);

if($redirect == 'addQuote'){
  if($quote_date == ''){
    $sql = "INSERT INTO quote (quote_number, description, content, create_request, supplier_ID, quote_date, type, size)
            VALUES ('$quote_number', '$description', '$content', 1, '$supplier_ID', CURDATE(), '$fileType', '$fileSize');";
  } else{
    $sql = "INSERT INTO quote (quote_number, description, content, create_request, supplier_ID, quote_date, type, size)
            VALUES ('$quote_number', '$description', '$content', 1, '$supplier_ID', '$quote_date', '$fileType', '$fileSize');";
  }
} else if($redirect == 'orderQuote'){
  if($quote_date == ''){
    $sql = "INSERT INTO quote (quote_number, description, content, order_ID, supplier_ID, quote_date, type, size)
            VALUES ('$quote_number', '$description', '$content', '$order_ID', '$supplier_ID', CURDATE(), '$fileType', '$fileSize');";
  } else{
    $sql = "INSERT INTO quote (quote_number, description, content, order_ID, supplier_ID, quote_date, type, size)
            VALUES ('$quote_number', '$description', '$content', '$order_ID', '$supplier_ID', '$quote_date', '$fileType', '$fileSize');";
  }
}

mysqli_query($link, $sql) or die('Error, query failed');

// Redirecting to the correct view, depending on where we added the quote
if($redirect == 'addQuote'){
	header('Location: ../Views/addQuote.php');
} else if($redirect == 'orderQuote'){
  header('Location: ../Views/addOrderItem.php');
}
?>
