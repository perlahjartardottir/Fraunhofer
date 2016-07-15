<?php
include '../../connection.php';

$propID = mysqli_real_escape_string($link, $_POST["propID"]);

$sql = "DELETE FROM anlys_property
WHERE anlys_prop_ID = '$propID';";
$result= mysqli_query($link, $sql);

if(!$result){
	die("Error: ".mysqli_error($link));
}

mysqli_close($link);

?>