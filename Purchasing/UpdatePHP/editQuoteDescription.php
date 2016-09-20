<?php

session_start();
include '../../connection.php';

$description = mysqli_real_escape_string($link, $_POST['description']);
$quote_ID = mysqli_real_escape_string($link, $_POST['quote_ID']);

  $sql = "UPDATE quote
          SET description = '$description'
          WHERE quote_ID = '$quote_ID';";

$result = mysqli_query($link, $sql);
if(!$result){
  die("error: " + mysqli_error($link));
}
?>
