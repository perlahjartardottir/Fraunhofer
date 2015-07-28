<?php
/*
	This file deletes all POs who are older than 7 years old
*/
include '../connection.php';
session_start();
$user = $_SESSION["username"];
$sql = "SELECT po_ID
        FROM pos
        WHERE receiving_date < DATE_SUB(NOW(), INTERVAL 7 YEAR);";
$result = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($result)){
  echo"
  <script type='text/javascript'>
    delAllOldPOs(".$row[0].");
  </script>";
}
?>
