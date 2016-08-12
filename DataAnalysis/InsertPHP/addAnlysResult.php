<?php
include '../../connection.php';
session_start();
header('Content-Type: text/html; charset=utf-8');
$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);
$eqPropID = mysqli_real_escape_string($link, $_POST["eqPropID"]);
$result = mysqli_real_escape_string($link, $_POST["result"]);
$comment = mysqli_real_escape_string($link, $_POST["comment"]);
$date = mysqli_real_escape_string($link, $_POST["date"]);
$employee = mysqli_real_escape_string($link, $_POST["employee"]);
$params = $_POST["params"];
$param1 = $param2 = $param3 = "";

for($i = 0; $i < count($params); $i++){
	if($i === 0){
		$param1 = mysqli_real_escape_string($link, $params[$i]);
	}
	else if($i === 1){
		$param2 = mysqli_real_escape_string($link, $params[$i]);
	}
	else if($i === 2){
		$param3 = mysqli_real_escape_string($link, $params[$i]);
	}
}

$sql = "INSERT INTO anlys_result(sample_ID, anlys_eq_prop_ID, anlys_res_result, anlys_res_comment,
			anlys_res_1, anlys_res_2, anlys_res_3, anlys_res_date, employee_ID) VALUES
			('$sampleID','$eqPropID','$result','$comment','$param1','$param2','$param3','$date','$employee');";
$result = mysqli_query($link, $sql);
if(!$result){
	die("Could not add analysis result: ".mysqli_error($link));
}

mysqli_close($link);
?>