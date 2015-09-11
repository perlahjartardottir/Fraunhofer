<?php
include '../../connection.php';
session_start();
$redirect     = mysqli_real_escape_string($link, $_POST['redirect']);
$quote_number = mysqli_real_escape_string($link, $_POST['quote_number']);
$description  = mysqli_real_escape_string($link, $_POST['description']);
$order_ID = $_SESSION["order_ID"];

$fileName = $_FILES['fileToUpload']['name'];
$tmpName  = $_FILES['fileToUpload']['tmp_name'];

$fp      = fopen($tmpName, 'r');
$content = fread($fp, filesize($tmpName));
$content = addslashes($content);
fclose($fp);

if($redirect == 'requestQuote'){
  $sql = "INSERT INTO quote (quote_number, description, image, create_request)
          VALUES ('$quote_number', '$description', '$content', 1);";
} else if($redirect == 'orderQuote'){
  $sql = "INSERT INTO quote (quote_number, description, image, order_ID)
          VALUES ('$quote_number', '$description', '$content', '$order_ID');";
}

$result = mysqli_query($link, $sql);

if(!$result){
	echo("Something went wrong : ".mysqli_error($link));
}

// close connection
mysqli_close($link);

// Redirecting to the correct view, depending on whether
// we were adding a tool or editing PO
if($redirect == 'requestQuote'){
	header('Location: ../Views/request.php');
} else if($redirect == 'orderQuote'){
  header('Location: ../Views/addOrderItem.php');
}
?>
