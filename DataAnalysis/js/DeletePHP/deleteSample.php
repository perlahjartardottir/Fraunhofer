<?php
include '../../connection.php';
session_start();

$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);

$deleteSampleSql = "DELETE FROM sample
WHERE sample_ID = '$sampleID';";
$deleteSampleResult = mysqli_query($link, $deleteSampleSql);

if(!$deleteSampleResult){
	die("Could not delete sample: ".mysqli_error($link));
}
mysqli_close($link);
?>