<?php
include '../../connection.php';
session_start();

$imgData = addslashes(file_get_contents($_FILES['sample_file']['tmp_name']));
$imgTest = 'Test';

$sql = "INSERT INTO sample(sample_name, sample_picture)
VALUES ('$imgTest', '$imgData');";
$result = mysqli_query($link, $sql);

?>