<?php
include '../../connection.php';
session_start();
$redirect     = mysqli_real_escape_string($link, $_POST['redirect']);
$quote_number = mysqli_real_escape_string($link, $_POST['quote_number']);
$description  = mysqli_real_escape_string($link, $_POST['description']);
$supplier = mysqli_real_escape_string($link, $_POST['supplierList']);
$order_ID = $_SESSION["order_ID"];

// Find the supplier ID
$supplierSql = "SELECT supplier_ID
                FROM supplier
                WHERE supplier_name = '$supplier';";
$supplierResult = mysqli_query($link, $supplierSql);

$row = mysqli_fetch_array($supplierResult);
$supplier_ID = $row[0];

$fileName = $_FILES['fileToUpload']['name'];
$tmpName  = $_FILES['fileToUpload']['tmp_name'];

$fp      = fopen($tmpName, 'r');
$content = fread($fp, filesize($tmpName));
$content = addslashes($content);
fclose($fp);

if($redirect == 'addQuote'){
  $sql = "INSERT INTO quote (quote_number, description, image, create_request, supplier_ID, quote_date)
          VALUES ('$quote_number', '$description', '$content', 1, '$supplier_ID', CURDATE());";
} else if($redirect == 'orderQuote'){
  $sql = "INSERT INTO quote (quote_number, description, image, order_ID, supplier_ID, quote_date)
          VALUES ('$quote_number', '$description', '$content', '$order_ID', '$supplier_ID', CURDATE());";
} else if($redirect == 'quoteOverview'){
  $sql = "INSERT INTO quote (quote_number, description, image, supplier_ID, quote_date)
          VALUES ('$quote_number', '$description', '$content', '$supplier_ID', CURDATE());";
}

$result = mysqli_query($link, $sql);

// Redirecting to the correct view, depending on where we added the quote
if($redirect == 'addQuote'){
	header('Location: ../Views/addQuote.php');
} else if($redirect == 'orderQuote'){
  header('Location: ../Views/addOrderItem.php');
} else if($redirect == 'quoteOverview'){
  header('Location: ../Views/quotes.php');
}
?>
