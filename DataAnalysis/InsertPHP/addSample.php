<?php
include '../../connection.php';
session_start();

$sampleSetID = mysqli_real_escape_string($link, $_POST['sampleSetID']);
$sampleSetDate = mysqli_real_escape_string($link, $_POST['sampleSetDate']);
$sampleMaterial = mysqli_real_escape_string($link, $_POST['sampleMaterial']);
$sampleComment = mysqli_real_escape_string($link, $_POST['sampleComment']);
$sampleName = "";


// If it is a new sample set.
if($sampleSetID === '-1'){ 

	// Construct the sample set name. Format: CCD-YYMMDD-XX
	$latestSampleSetNameSql = "SELECT sample_set_name
	FROM sample_set
	WHERE sample_set_ID = (SELECT MAX(s.sample_set_ID)
	FROM sample_set s);";
	$latestSampleSetNameResult = mysqli_query($link, $latestSampleSetNameSql);
	$latestSampleSetNameRow = mysqli_fetch_array($latestSampleSetNameResult);
	$latestSampleSetName = $latestSampleSetNameRow[0];
	$latestSampleSetDate = substr($latestSampleSetName, 4, 6);
	$latestSampleSetNumber = substr($latestSampleSetName, 12, 2);
	$sampleSetNumber = 1;

	// If this is not the first sample set today increase number.
	if($latestSampleSetDate === $sampleSetDate){
		$sampleSetNumber = $latestSampleSetNumber + 1;
	}
	
	// Format the XX part.
	$sampleSetNumber = str_pad($sampleSetNumber, 2, '0', STR_PAD_LEFT);
	$sampleSetName = "CCD-".$sampleSetDate."-".$sampleSetNumber;

	// Insert the set.
	$sampleSetSql = "INSERT INTO sample_set(sample_set_name)
	VALUES ('$sampleSetName');";
	$sampleSetResult = mysqli_query($link, $sampleSetSql);

	 // Get the newly inserted sample set ID.
	if($sampleSetResult){
		$sampleSetID = mysqli_insert_id($link);
	}

	$sampleName = $sampleSetName."-01";

}
else{

// Get the latest sample from the chosen sample set.
$sampleSetNameSql = "SELECT sample_set_name
FROM sample_set
WHERE sample_set_ID = '$sampleSetID';";
$sampleSetNameResult = mysqli_query($link, $sampleSetNameSql);
$sampleSetNameRow = mysqli_fetch_row($sampleSetNameResult);
$sampleSetName = $sampleSetNameRow[0];

// Format: CCD-YYMMDD-XX-NN
$latestSampleNumberSql = "SELECT COUNT(sample_id)
FROM sample
WHERE sample_set_ID = '$sampleSetID';";
$latestSampleNumberResult = mysqli_query($link, $latestSampleNumberSql);
$latestSampleNumberRow = mysqli_fetch_row($latestSampleNumberResult);
$latestSampleNumber = $latestSampleNumberRow[0];
$sampleNumber = str_pad(((int)$latestSampleNumber + 1), 2, '0', STR_PAD_LEFT);
$sampleName = $sampleSetName."-".$sampleNumber;


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
