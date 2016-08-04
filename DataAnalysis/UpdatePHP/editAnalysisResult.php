<?php
include '../../connection.php';

$resID = mysqli_escape_string($link, $_POST["resID"]);
$result = mysqli_real_escape_string($link, $_POST["result"]);
$paramRes1 = mysqli_real_escape_string($link, $_POST["paramRes1"]);
$paramRes2 = mysqli_real_escape_string($link, $_POST["paramRes2"]);
$paramRes3 = mysqli_real_escape_string($link, $_POST["paramRes3"]);
$comment = mysqli_escape_string($link, $_POST["comment"]);

$sql = "UPDATE anlys_result SET anlys_res_result = $result, anlys_res_comment  = '$comment', anlys_res_1 = $paramRes1,
anlys_res_2 = $paramRes2, anlys_res_3 = $paramRes3
WHERE anlys_res_ID = $resID";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not update analysis result: ".mysqli_error($link));
}

mysqli_close($link);

?>