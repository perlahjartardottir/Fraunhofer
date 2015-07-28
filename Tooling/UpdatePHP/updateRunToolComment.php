<?php
include '../connection.php';

$lineitem_ID = mysqli_real_escape_string($link, $_POST['lineitem_ID']);
$run_ID 	 = mysqli_real_escape_string($link, $_POST['run_ID']);
$comment 	 = mysqli_real_escape_string($link, $_POST['comment']);

$sql = "UPDATE lineitem_run
		SET lineitem_run_comment = '$comment'
		WHERE lineitem_ID = '$lineitem_ID'
		AND run_ID = '$run_ID'";   
$result = mysqli_query($link, $sql);

if (!$result) {
    $message  = 'Invalid query: ' . mysql_error();
}
mysqli_close($link);
?>