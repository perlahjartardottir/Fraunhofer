<?php
  include '../connection.php';
  session_start();
  mysql_set_charset('utf8');

  $po_ID = $_SESSION["po_ID"];
  //$id = $_GET['id'];
  // do some validation here to ensure id is safe

  $sql = "SELECT image 
          FROM po_scan
          WHERE po_ID = '$po_ID'";
  $result = mysqli_query($link, $sql);

  if(!$result){
      echo("Something went wrong : ".mysqli_error($link));
  }
  if(mysqli_num_rows($result) > 0){
      $row = mysqli_fetch_array($result);
      header('Content-Type: text/html; charset=utf-8');
      echo $row[0];
  }else{
      echo "Image does not exist";
  }
  mysql_close($link);
?>

