<?php
include '../connection.php';

$comment = mysqli_real_escape_string($link, $_POST['comment']);
$name = mysqli_real_escape_string($link, $_POST['name']);
$sql = "INSERT INTO Feedback(nafn, feedback) VALUES('$name', '$comment')";

$result = mysqli_query($link, $sql);

if ($result) {
    echo 'all good yo';
} else {
    mysqli_error($link);
}
mysqli_close($link);
