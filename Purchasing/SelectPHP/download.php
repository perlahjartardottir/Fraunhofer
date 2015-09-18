<?php
include '../../connection.php';
session_start();
// if is set then get the file with the id from database
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $sql = "SELECT quote_number, type, size, content
          FROM quote
          WHERE quote_ID = '$id';";
  $result = mysqli_query($link, $sql);
  $row = mysqli_fetch_array($result);
  header("Content-length: $row[2]");
  header("Content-type: $row[1]");
  header("Content-Disposition: attachment; filename = $row[0]");
  echo $row[3];
  exit;
}
 ?>
