<?php
include '../../connection.php';

$propName = mysqli_real_escape_string($link, $_POST["propName"]);

$sql = "INSERT INTO anlys_property (anlys_prop_name, anlys_prop_active)
VALUE ('$propName', TRUE);";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not insert analysis property: ".mysqli_error($link));
}

mysqli_close($link);

?>