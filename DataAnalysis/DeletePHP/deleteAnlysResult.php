<?php
include '../../connection.php';

$resID = mysqli_real_escape_string($link, $_POST["resID"]);

$sql = "DELETE FROM anlys_result
WHERE anlys_res_ID = '$resID';";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not delete analysis result: ".mysqli_error($link));
}

mysqli_close($link);

?>