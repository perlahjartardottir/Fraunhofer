<?php
include '../../connection.php';

$prcsID = mysqli_real_escape_string($link, $_POST["prcsID"]);

$sql = "DELETE FROM process
WHERE prcs_ID = '$prcsID';";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not delete process: ".mysqli_error($link));
}

mysqli_close($link);

?>