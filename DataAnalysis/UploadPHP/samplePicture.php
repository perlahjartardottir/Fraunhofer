<?php
include '../../connection.php';
session_start();
$sampleSetID = $_SESSION["sampleSetID"];
$sampleID = $_SESSION["sampleID"];
$sampleName =  $_POST["sample_name"];
$maxPictureSize = $_SESSION["pictureValidation"]["maxSize"];
$pictureFormats = $_SESSION["pictureValidation"]["formats"];

$samplePicture = "";
if($action === "edit"){
  $samplePicture = "sample_picture_edit";
}
else if ($action === "insert"){
  $samplePicture = "sample_picture";
}


if($_FILES[$samplePicture]["name"]){

// Build the path
  $target_dir = "../../../Fraunhofer Uploads/Data Analysis/".date("Y")."/".date("m")."/".$sampleName."/";
// If the folder does not exist create it.
  if (!file_exists($targer_dir)) {
    mkdir($target_dir, 0777, true);
  }
  $temp = explode(".", $_FILES[$samplePicture]["name"]);
  $newName = $sampleName."_profile-picture.".end($temp);

  $target_file = $target_dir.$newName;

  $uploadOk = 1;
  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES[$samplePicture]["tmp_name"]);
    if($check !== false) {
        // echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
    } else {
        //echo "File is not an image.";
      $errorMessage .= "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
      "This file is not an image.</div>";
      $uploadOk = 0;
    }
  }

// Check file size. Max size: 5 MB.
  if ($_FILES[$samplePicture]["size"] > $maxPictureSize) {
    //echo "Sorry, your file is too large.";
    $errorMessage .= "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
    "Sorry, your file is too large. The max size is: </div>";
    $uploadOk = 0;
  }
// Allow certain file formats
  if(!in_array($imageFileType, $pictureFormats)) {
    //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $errorMessage .= "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
    "Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
    $uploadOk = 0;
  }
// Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    // echo "Sorry, your file was not uploaded.";
    $errorMessage .= "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
    "Sorry, your file was not uploaded.</div>";

// if everything is ok, try to upload file
  } else {
	// This does replace an existing photo with same name.
    if (move_uploaded_file($_FILES[$samplePicture]["tmp_name"], $target_file)) {
        // echo "The file ". basename( $_FILES[$samplePicture]["name"]). " has been uploaded.";

      $sql = "UPDATE sample
      SET sample_picture = '$target_file'
      WHERE sample_ID = '$sampleID';";
      $result = mysqli_query($link, $sql);

      if(!$result){
        die("Could not update sample picture: ".mysqli_error($link));
      }

    } else {
        // echo "Sorry, there was an error uploading your file.";
     $errorMessage .= "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
     "Sorry, there was an error uploading your file.</div>";
   }
 }

}

?>