<?php
include '../../connection.php';
session_start();

$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);
$sampleSetID = $_SESSION["sampleSetID"];

// Delete sample picture from server if any.
include 'deleteSamplePicture.php';
	

$deleteSampleSql = "DELETE FROM sample
WHERE sample_ID = '$sampleID';";
$deleteSampleResult = mysqli_query($link, $deleteSampleSql);

if(!$deleteSampleResult){
	die("Error: ".mysqli_error($link));
}

if(mysql_affected_rows($link) === 0){
	die("Could not delete sample (affected rows are 0): ".mysqli_error($link));
}
else{

	// We have successfully deleted the sample.
	$_SESSION['sampleID'] = '-1';

	// Find how many samples are left in the set.
	$allSamplesInSetSql = "SELECT sample_ID
	FROM sample
	WHERE sample_set_ID = '$sampleSetID';";
	$allSamplesInSetResult = mysqli_query($link, $allSamplesInSetSql);
	$numberOfSamplesInSet = mysqli_num_rows($allSamplesInSetResult);

	if(!$allSamplesInSetResult){
		die("Could not count samples: ".mysqli_error($link));
	}

	if ($numberOfSamplesInSet === 0){

		// If we deleted the last sample in the set, delete the set. 
		// The set does only get deleted if there are no other components dempending on it such as storage.
		$deleteSampleSetSql = "DELETE FROM sample_set
		WHERE sample_set_ID = '$sampleSetID';";
		$deleteSampleSetResult = mysqli_query($link, $deleteSampleSetSql);

		if(!$deleteSampleSetResult){
			die("Could not delete sample set: ".mysqli_error($link));
		}

		if(mysql_affected_rows($link) === 0){
			die("Could not delete sample set (affected rows are 0): ".mysqli_error($link));
		}
		// We have successfully deleted the sampleSet. 
		$_SESSION["sampleSetID"] = "-1";
	}
}

// There can be no echo before this call, otherwise the redirect will not work. 
header('Location: ../Views/addSample.php?id='.$sampleSetID);

mysqli_close($link);
?>