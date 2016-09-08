<?php
include '../../connection.php';

// Get the path to the picture. 
$getPictureSql = "SELECT sample_picture
FROM sample
WHERE sample_ID = '$sampleID';";
$picture = mysqli_fetch_row(mysqli_query($link,$getPictureSql))[0];

// Unlinke the pathname from database.
$removePicturePathSql = "UPDATE sample
SET sample_picture = NULL
WHERE sample_ID = '$sampleID';";
$removePicturePathResult = mysqli_query($link, $removePicturePathSql);
if(!$removePicturePathResult){
die("Could not remove sample picture: ".mysqli_error($link));
}

// Remove the picture from the server.
if(file_exists($picture)){
	unlink($picture);
}

// If the directory is empty, delete it.
$directory = dirname($picture);
if(is_dir($directory)){
	rmdir($directory);
}

?>