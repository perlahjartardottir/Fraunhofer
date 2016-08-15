<?php
include '../../connection.php';
session_start();
header('Content-Type: text/html; charset=utf-8');

$sampleID = $_SESSION["sampleID"];
$eqPropID = mysqli_real_escape_string($link, $_POST["eq_prop_ID"]);
$result = mysqli_real_escape_string($link, $_POST["res_res"]);
$comment = mysqli_real_escape_string($link, $_POST["res_comment"]);
$date = mysqli_real_escape_string($link, $_POST["res_date"]);
$employee = mysqli_real_escape_string($link, $_POST["employee_initials"]);
$param1 = $param2 = $param3 = "";
// For anlysFile.php
$action = "insert";

for($i = 0; $i < 3; $i++){
	if($i === 0){
		$param1 = mysqli_real_escape_string($link, $_POST["res_param"][$i]);
	}
	else if($i === 1){
		$param2 = mysqli_real_escape_string($link, $_POST["res_param"][$i]);
	}
	else if($i === 2){
		$param3 = mysqli_real_escape_string($link, $_POST["res_param"][$i]);
	}
}


$sql = "INSERT INTO anlys_result(sample_ID, anlys_eq_prop_ID, anlys_res_result, anlys_res_comment,
			anlys_res_1, anlys_res_2, anlys_res_3, anlys_res_date, employee_ID) VALUES
			('$sampleID','$eqPropID','$result','$comment','$param1','$param2','$param3','$date','$employee');";
$result = mysqli_query($link, $sql);
if(!$result){
	die("Could not add analysis result: ".mysqli_error($link));
}

$anlysResultID = mysqli_insert_id($link);
//Upload analysis file and add path to db.
include 'anlysFile.php';

mysqli_close($link);

// There can be no echo before this call, otherwise the redirect will not work. 
header('Location: ../Views/analyze.php');
?>