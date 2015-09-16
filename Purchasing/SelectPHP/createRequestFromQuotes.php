<?php
include '../../connection.php';
session_start();
$selected = mysqli_real_escape_string($link, $_POST['selected']);
$array = explode(",", $selected);
foreach ($array as $value) {
    $sql = "UPDATE quote
            SET create_request = 1
            WHERE quote_ID = '$value';";
    $result = mysqli_query($link, $sql);
}
?>
