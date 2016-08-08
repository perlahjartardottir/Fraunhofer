<?php
include '../../connection.php';

$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);
$name = mysqli_real_escape_string($link, $_POST["name"]);
$material = mysqli_real_escape_string($link, $_POST["material"]);
$comment = mysqli_real_escape_string($link, $_POST["comment"]);

$sql = "UPDATE sample
		SET sample_name = '$name', sample_material = '$material', sample_comment='$comment'
		WHERE sample_ID = '$sampleID';";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not update sample: ".mysqli_error($link));
}

mysqli_close($link);

?>