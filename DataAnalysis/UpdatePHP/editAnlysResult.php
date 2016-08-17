<?php
include '../../connection.php';
session_start();

$sampleID = $_SESSION["sampleID"];
$resID = mysqli_escape_string($link, $_POST["res_ID"]);
$eqPropID = mysqli_escape_string($link, $_POST["eq_prop_ID"]);
$result = mysqli_real_escape_string($link, $_POST["res_res_edit"]);
$paramRes1 = mysqli_real_escape_string($link, $_POST["res_param_1_edit"]);
$paramRes2 = mysqli_real_escape_string($link, $_POST["res_param_2_edit"]);
$paramRes3 = mysqli_real_escape_string($link, $_POST["res_param_3_edit"]);
$comment = mysqli_escape_string($link, $_POST["comment_edit"]);
$process = mysqli_escape_string($link, $_POST["coating"]);

// For anlysFile.php.
$action = "edit";
// For redirecting.
$redirect = $_SESSION["direct"]["redirect"];

$sql = "UPDATE anlys_result SET anlys_res_result = '$result', anlys_res_comment  = '$comment', anlys_res_1 = '$paramRes1',
anlys_res_2 = '$paramRes2', anlys_res_3 = '$paramRes3', prcs_ID = '$process'
WHERE anlys_res_ID = $resID";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not update analysis result: ".mysqli_error($link));
}

include '../DeletePHP/deleteAnlysFile.php';

// Go through every file belonging to the analysis result.
for($fileCounter = 0; $fileCounter < count($_POST["file_ID"]); $fileCounter++){
	
	// Delete files those where their checkbox is marked. 
	if(mysqli_real_escape_string($link, $_POST["file_delete"][$fileCounter]) === "yes"){
		$resFileID = mysqli_real_escape_string($link, $_POST["file_ID"][$fileCounter]);

		deleteFile($resFileID);
	}
}

include '../InsertPHP/anlysFile.php';

mysqli_close($link);

// There can be no echo before this call, otherwise the redirect will not work. 
if($redirect === "analyze"){
	header("Location: ../Views/analyze.php");
}
else if($redirect == "sampleOverview"){
	header("Location: ../Views/sampleOverview.php");
}
else{
	header("Location: ../Views/dataAnalysis.php");	
}

?>