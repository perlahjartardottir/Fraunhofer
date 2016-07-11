<?php
  include '../../connection.php';
  session_start();

mysql_set_charset('utf8');
$id = $_GET['id'];

$sql = "SELECT sample_picture
            FROM sample
            WHERE sample_ID = '$id';";

$result = mysqli_query($link, $sql);

  if(!$result){
      echo("Could not get sample image : ".mysqli_error($link));
  }
  if(mysqli_num_rows($result) > 0){
    header('Content-Type: text/html; charset=utf-8');
      while($row = mysqli_fetch_array($result)){
        echo $row[0];
      }
  }else{
      echo "Sample image does not exist.";
  }
  mysql_close($link);
?>