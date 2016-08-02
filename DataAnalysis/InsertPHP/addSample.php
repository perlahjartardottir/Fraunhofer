<?php
include '../../connection.php';
session_start();

$sampleSetID = $_POST['sample_set_ID'];
$sampleSetDate = $_POST['sample_set_date'];
$sampleMaterial = $_POST['material'];
$sampleComment = $_POST['sample_comment'];
$sampleSetName = $_POST["sample_set_name"];
$sampleName = $_POST['sample_name'];
$errorMessage  = "";

if($sampleSetDate){
	$sampleSetDate = substr(str_replace("-", "", $sampleSetDate), 2, 6);
}

// A base for picture uploading. 

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
//         //echo "File is not an image.";
//         $errorMessage .= "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
//           		"This file is not an image.</div>";
//         $uploadOk = 0;
//     }
// }
// // Check if file already exists
// if (file_exists($target_file)) {
//     // echo "Sorry, file already exists.";
//     $errorMessage .= "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
//           		"Sorry, file already exists.</div>";
//     $uploadOk = 0;
// }
// // Check file size
// if ($_FILES["fileToUpload"]["size"] > 500000) {
//     //echo "Sorry, your file is too large.";
//     $errorMessage .= "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
//           		"Sorry, your file is too large. The max size is: </div>";
//     $uploadOk = 0;
// }
// // Allow certain file formats
// if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
// && $imageFileType != "gif" ) {
//     //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//     $errorMessage .= "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
//           		"Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
//     $uploadOk = 0;
// }
// // Check if $uploadOk is set to 0 by an error
// if ($uploadOk == 0) {
//     // echo "Sorry, your file was not uploaded.";
//     $errorMessage .= "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
//           		"Sorry, your file was not uploaded.</div>";

// // if everything is ok, try to upload file
// } else {
//     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//         // echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
//     } else {
//         // echo "Sorry, there was an error uploading your file.";
//          $errorMessage .= "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
//           		"Sorry, there was an error uploading your file.</div>";
//     }
// }

// If it is a new sample set.
if($sampleSetID === '-1'){ 

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

mysqli_close($link);

// There can be no echo before this call, otherwise the redirect will not work. 
header('Location: ../Views/addSample.php?id='.$sampleSetID);
?>
