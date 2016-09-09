<?php
include '../../connection.php';
session_start();

$sampleSetID = $_SESSION["sampleSetID"];
$sampleID = $_POST["sample_ID"];
$material = $_POST["edit_material"];
$comment = $_POST["sample_comment"];
// For samplePicture
$action = "edit";

$sql = "UPDATE sample
		SET sample_material = '$material', sample_comment = '$comment'
		WHERE sample_ID = '$sampleID';";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not update sample: ".mysqli_error($link));
}

$deletePicture = $_POST["sample_picture_delete"];
if($deletePicture === "yes"){
	include '../DeletePHP/deleteSamplePicture.php';
}

// Upload photo and connect to sample.
include '../InsertPHP/samplePicture.php';

// There can be no echo before this call, otherwise the redirect will not work. 
header('Location: ../Views/addSample.php?id='.$sampleSetID);

mysqli_close($link);

?>