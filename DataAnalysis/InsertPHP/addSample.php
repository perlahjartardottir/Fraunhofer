<?php
include '../../connection.php';
session_start();

$sampleSetID = mysqli_real_escape_string($link, $_POST['sampleSetID']);
$sampleName = mysqli_real_escape_string($link, $_POST['sampleName']);
$sampleMaterial = mysqli_real_escape_string($link, $_POST['sampleMaterial']);
$sampleComment = mysqli_real_escape_string($link, $_POST['sampleComment']);

// LATER THIS WILL BE CHANGED TO A UNIQUE DESCRIPTIVE NAME FOR EACH SET AND SAMPLE SET

// If it is a new sample set.
if($sampleSetID === '-1'){ 

	// Insert the set.
	$sampleSetSql = "INSERT INTO sample_set(sample_set_name)
 		VALUES ('Some Set');";
 	$sampleSetResult = mysqli_query($link, $sampleSetSql);
 	
 	// Get the latest ID
 	if($sampleSetResult){
 		$sampleSetID = mysqli_insert_id($link);
 	}

 	$sampleSetNewName = "Set ".(string)$sampleSetID;
 	
 	// Update the name with the just added ID number.
 	$sampleSetNameSql = "UPDATE sample_set
 	SET sample_set_name = '$sampleSetNewName'
 	WHERE sample_set_ID = '$sampleSetID';";
 	$sampleSetNameResult = mysqli_query($link, $sampleSetNameSql);

}

$_SESSION["sampleSetID"] = $sampleSetID;

$sql = "INSERT INTO sample(sample_set_ID, sample_name, sample_material, sample_comment)
		VALUES ('$sampleSetID', '$sampleName', '$sampleMaterial', '$sampleComment');";
$result = mysqli_query($link, $sql);


if(!$result){
	mysqli_error($link);
}

mysqli_close($link);

?>
