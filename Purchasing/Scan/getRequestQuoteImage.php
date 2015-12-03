<?php
  include '../../connection.php';
  session_start();
  mysql_set_charset('utf8');

  $id = $_GET['id'];

  // If there is no id sent in to this view then we find all the quotes linked that are active
  // Otherwise we find the quote that is active and has that specific id
  if(empty($id)){
    $sql = "SELECT image
            FROM quote
            WHERE create_request = 1
            ORDER BY quote_ID DESC;";
  }else{
    $sql = "SELECT image
            FROM quote
            WHERE create_request = 1
            AND quote_ID = '$id'
            ORDER BY quote_ID DESC;";
  }

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
