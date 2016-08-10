<?php
include '../../connection.php';

$sampleID = $_POST["sample_ID"];
$material = $_POST["material_edit"];
$comment = $_POST["sample_comment"];

$sql = "UPDATE sample
		SET sample_material = '$material', sample_comment='$comment'
		WHERE sample_ID = '$sampleID';";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not update sample: ".mysqli_error($link));
}

//Upload photo and connect to sample.
include '../UploadPHP/samplePicture.php';

mysqli_close($link);

?>