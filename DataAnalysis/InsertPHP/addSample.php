<?php
include '../../connection.php';
session_start();

$sampleSetID = $_POST['sample_set_ID'];
$sampleSetDate = $_POST['sample_set_date'];
$sampleMaterial = $_POST['material'];
$sampleComment = $_POST['sample_comment'];
$sampleName = "";

if($sampleSetDate){
	$sampleSetDate = substr(str_replace("-", "", $sampleSetDate), 2, 6);
}

// $target_dir = "../Upload/uploads/";
// $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
// $uploadOk = 1;
// $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// // Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
//     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//     if($check !== false) {
//         // echo "File is an image - " . $check["mime"] . ".";
//         $uploadOk = 1;
//     } else {
//         echo "File is not an image.";
//         $uploadOk = 0;
//     }
// }
// // Check if file already exists
// if (file_exists($target_file)) {
//     echo "Sorry, file already exists.";
//     $uploadOk = 0;
// }
// // Check file size
// if ($_FILES["fileToUpload"]["size"] > 500000) {
//     echo "Sorry, your file is too large.";
//     $uploadOk = 0;
// }
// // Allow certain file formats
// if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
// && $imageFileType != "gif" ) {
//     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//     $uploadOk = 0;
// }
// // Check if $uploadOk is set to 0 by an error
// if ($uploadOk == 0) {
//     echo "Sorry, your file was not uploaded.";
// // if everything is ok, try to upload file
// } else {
//     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//         // echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
//     } else {
//         echo "Sorry, there was an error uploading your file.";
//     }
// }
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

// There can be no echo before this call, otherwise the redirect will not work. 
header('Location: ../Views/addSample.php?id='.$sampleSetID);
?>
