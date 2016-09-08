
<?php
include '../../connection.php';
session_start();

$user = mysqli_real_escape_string($link, $_POST["user"]);
$date = mysqli_real_escape_string($link, $_POST["date"]);
$location = mysqli_real_escape_string($link, $_POST["errorLocation"]);
$sample = mysqli_real_escape_string($link, $_POST["sample"]);
$description  = mysqli_real_escape_string($link, $_POST["description"]);

$sql = "INSERT INTO da_feedback(employee_ID, fdbk_date, fdbk_location, fdbk_sample, fdbk_description, fdbk_resolved) VALUES
		('$user','$date','$location','$sample','$description', 'FALSE');";
$result = mysqli_query($link, $sql);
if(!$result){
	die("Could not add feedback: ".mysqli_error($link));
}

mysqli_close($link);

?>