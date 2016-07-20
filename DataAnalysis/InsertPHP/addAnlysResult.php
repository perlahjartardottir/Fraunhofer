<?php
include '../../connection.php';
session_start();
header('Content-Type: text/html; charset=utf-8');
$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);
$eqPropID = mysqli_real_escape_string($link, $_POST["eqPropID"]);
$result = mysqli_real_escape_string($link, $_POST["result"]);
$comment = mysqli_real_escape_string($link, $_POST["comment"]);
$date = mysqli_real_escape_string($link, $_POST["date"]);
$params = $_POST["params"];
$param1 = $param2 = $param3 = "";
for($i = 1; $i <= count($params); $i++){
	$param1 = mysqli_real_escape_string($link, $params[$i]);
}
$sql = "INSERT INTO anlys_result(sample_ID, anlys_eq_prop_ID, anlys_res_result, anlys_res_comment,
			anlys_res_1, anlys_res_2, anlys_res_3, anlys_res_date) VALUES
			('$sampleID','$eqPropID','$result','$comment','$param1','$param2','$param3','$date');";
$result = mysqli_query($link, $sql);
if(!result){
	die("Could not add analysis result: ".mysqli_error($link));
}
?>