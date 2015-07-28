<?php
include '../connection.php';
session_start();
$po_ID = $_SESSION["po_ID"];
$redirect = mysqli_real_escape_string($link, $_POST['redirect']);

$fileName = $_FILES['fileToUpload']['name'];
$tmpName  = $_FILES['fileToUpload']['tmp_name'];

$fp      = fopen($tmpName, 'r');
$content = fread($fp, filesize($tmpName));
$content = addslashes($content);
fclose($fp);

// If the po already has an image, then the old one gets deleted
// and the new one inserted instead
$sql = "INSERT INTO po_scan (po_ID, image) VALUES ('$po_ID', '$content')
				ON DUPLICATE KEY
				UPDATE image = VALUES(image);";

$result = mysqli_query($link, $sql);

if(!$result){
	echo("Something went wrong : ".mysqli_error($link));
}

// close connection
mysqli_close($link);

// Redirecting to the correct view, depending on whether
// we were adding a tool or editing PO
if($redirect == 'new'){
	header('Location: ../Views/addTools2.php');
}
else if($redirect == 'edit'){
	header('Location: ../Views/editPO.php');
}
?>
