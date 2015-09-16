<?php
include '../../connection.php';
$quote_ID = mysqli_real_escape_string($link, $_POST['quote_ID']);
$sql = "UPDATE quote
        SET create_request = 0
        WHERE quote_ID = '$quote_ID';";
$result = mysqli_query($link, $sql);
?>
