<?php
include '../../connection.php';

$propID = mysqli_real_escape_string($link, $_POST["propID"]);
$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);

$eqPropSql = "DELETE FROM anlys_eq_prop
WHERE anlys_eq_ID = '$eqID' AND anlys_prop_ID = '$propID';";
$eqPropResult = mysqli_query($link, $eqPropSql);
if(!$eqPropResult){
	die("Error0: ".mysqli_error($link));
}

$sql = "DELETE FROM anlys_property
WHERE anlys_prop_ID = '$propID';";
$result= mysqli_query($link, $sql);

if(!$result){
	die("Error2: ".mysqli_error($link));
}

mysqli_close($link);

?>