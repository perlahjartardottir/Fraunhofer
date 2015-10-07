<?php
include '../../connection.php';
$quote_ID = mysqli_real_escape_string($link, $_POST['quote_ID']);

// Delete the quote that has this ID
$sql = "DELETE FROM quote
				WHERE quote_ID ='$quote_ID';";
$result = mysqli_query($link, $sql);
if(!$result){
	die("Could not delete quote: ".mysqli_error($link));
}
mysqli_close($link);
?>
