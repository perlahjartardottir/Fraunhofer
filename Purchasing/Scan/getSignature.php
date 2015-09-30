<?php
  include '../../connection.php';
  session_start();
  mysql_set_charset('utf8');

  $employee_ID = mysqli_real_escape_string($link, $_POST['employee_ID']);
  $id = $_GET['id'];

  $sql = "SELECT employee_signature
          FROM employee
          WHERE employee_ID = '$id';";
  $result = mysqli_query($link, $sql);

  if(!$result){
      echo("Something went wrong : ".mysqli_error($link));
  }
  if(mysqli_num_rows($result) > 0){
    header('Content-Type: text/html; charset=utf-8');
      while($row = mysqli_fetch_array($result)){
        echo $row[0];
      }
  }else{
      echo "Image does not exist";
  }
  mysql_close($link);
?>
