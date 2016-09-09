<?php
include '../../connection.php';
session_start();

$sampleSetID = $_POST['sample_set_ID'];
$sampleSetDate = $_POST['sample_set_date'];
$sampleSetNumber = $_POST['sample_set_number'];
$sampleMaterial = $_POST['material_new'];
$sampleComment = $_POST['sample_comment'];
$sampleSetName = $_POST["sample_set_name"];
$sampleName = $_POST['sample_name'];
$errorMessage  = "";
// For samplePicture.php
$action = "insert";

// If new sample set.
if($sampleSetDate){
	$sampleSetDate = substr(str_replace("-", "", $sampleSetDate), 2, 6);
}

if($sampleSetNumber){
	$sampleSetNumber = str_pad($sampleSetNumber, 2, '0', STR_PAD_LEFT);
}

// If it is a new sample set.
if($sampleSetID === '-1'){ 

	$sampleSetName = "CCD-".$sampleSetDate."-".$sampleSetNumber;
	$sampleName = $sampleSetName."-01";

	// Insert the set.
	$sampleSetSql = "INSERT INTO sample_set(sample_set_name)
	VALUES ('$sampleSetName');";
	$sampleSetResult = mysqli_query($link, $sampleSetSql);

	// Get the newly inserted sample set ID.
	if($sampleSetResult){
		$sampleSetID = mysqli_insert_id($link);
	}
}

$_SESSION["sampleSetID"] = $sampleSetID;

$sql = "INSERT INTO sample(sample_set_ID, sample_name, sample_material, sample_comment)
VALUES ('$sampleSetID', '$sampleName', '$sampleMaterial', '$sampleComment');";
$result = mysqli_query($link, $sql);
if($result){
	$sampleID = mysqli_insert_id($link);
	$_SESSION['sampleID'] = $sampleID;
}
else{
	mysqli_error($link);
}

//Upload photo and connect to sample.
include 'samplePicture.php';

// There can be no echo before this call, otherwise the redirect will not work. 
header('Location: ../Views/addSample.php?id='.$sampleSetID);
?>