<?php
include '../../connection.php';
session_start();

// Get the user who is logged in
$user = $_SESSION["username"];
$comment = mysqli_real_escape_string($link, $_POST['comment']);

// Insert the comment to the feedback table 
$sql = "INSERT INTO Feedback(nafn, feedback) VALUES('$user', '$comment')";
$result = mysqli_query($link, $sql);

if ($result) {
    echo 'all good yo';
} else {
    mysqli_error($link);
}
mysqli_close($link);
