<?php
include '../../connection.php';
$scan_ID = mysqli_real_escape_string($link, $_POST['scan_ID']);

// Delete the scan that has this ID
$sql = "DELETE FROM purchase_scan
				WHERE scan_ID ='$scan_ID';";
$result = mysqli_query($link, $sql);
if(!$result){
	die("Could not delete scan: ".mysqli_error($link));
}
mysqli_close($link);
?>
