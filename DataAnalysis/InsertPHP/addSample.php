<?php
include '../../connection.php';

$sampleName = mysqli_real_escape_string($link, $_POST['sampleName']);
$sampleMaterial = mysqli_real_escape_string($link, $_POST['sampleMaterial']);
$sampleComment = mysqli_real_escape_string($link, $_POST['sampleComment']);

$sql = "INSERT INTO sample(sample_name, sample_material, sample_comment)
		VALUES ('$sampleName', '$sampleMaterial', '$sampleComment');";
$result = mysqli_query($link, $sql);

echo "i am here";

if(!$result){
	mysqli_error($link);
}


mysqli_close($link);
?>
