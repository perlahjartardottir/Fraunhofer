<?php
// this file adds a line in the packinglist table containing the po_ID and a comment about it
// if there exists a row with a comment it updates that comment.
include '../connection.php';

$comment = mysqli_real_escape_string($link, $_POST['comment']);
$po_ID	 = mysqli_real_escape_string($link, $_POST['po_ID']);


$sql = "INSERT INTO packinglist(po_ID, packinglist_comment) VALUES('$po_ID', '$comment')
			  ON DUPLICATE KEY 
				UPDATE packinglist_comment=VALUES(packinglist_comment)";

$result = mysqli_query($link, $sql);

if(!$result){
	echo("Something went wrong: ".mysqli_error($link));
}
mysqli_close($link);
?>
