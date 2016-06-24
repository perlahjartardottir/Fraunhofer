<?php
include '../../connection.php';
$sampleSetID = mysqli_real_escape_string($link, $_POST(['SampleSetID']));
$sampleName = mysqli_real_escape_string($link, $_POST['sampleName']);
$sampleMaterial = mysqli_real_escape_string($link, $_POST['sampleMaterial']);
$sampleComment = mysqli_real_escape_string($link, $_POST['sampleComment']);
$sql = "INSERT INTO sample(sample_set_ID, sample_name, sample_material, sample_comment)
		VALUES ('sampleSetID', '$sampleName', '$sampleMaterial', '$sampleComment');";
$result = mysqli_query($link, $sql);
if(!$result){
	mysqli_error($link);
}
mysqli_close($link);
?>
