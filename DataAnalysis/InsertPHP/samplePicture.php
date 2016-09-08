<?php
include '../../connection.php';

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
      $uploadOk = 1;
    } else {
      $uploadOk = 0;
    }
  }

// Check file size. 
  if ($_FILES[$samplePicture]["size"] > $maxPictureSize) {

    $uploadOk = 0;
  }
// Allow certain file formats.
  if(!in_array($imageFileType, $pictureFormats)) {
    $uploadOk = 0;
  }
// if everything is ok, try to upload file.
  if ($uploadOk == 1) {
	// This does replace an existing photo with same name.
    if (move_uploaded_file($_FILES[$samplePicture]["tmp_name"], $target_file)) {

      $sql = "UPDATE sample
      SET sample_picture = '$target_file'
      WHERE sample_ID = '$sampleID';";
      $result = mysqli_query($link, $sql);

      if(!$result){
        die("Could not update sample picture: ".mysqli_error($link));
      }

    } 
  }
}

?>