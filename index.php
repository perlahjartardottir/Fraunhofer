<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  include '../connection.php';
    //session_start();
  ?>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Login Fraunhofer CCD</title>
  <link href="/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom styles for this template -->
  <link href="/css/signin.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src='Tooling/dest/fraunhofer.min.js'></script>
</head>
<body>
<div class="container">
    <div class="form-signin">
      <label for="userID" class="sr-only">Employee ID</label>
      <input type="number" id="userID" class="form-control" placeholder="Employee ID" required autofocus>
      <label for="password" class="sr-only">Password</label>
      <input type="password" id="password" class="form-control" placeholder="Password" required>
      <button class="btn btn-lg btn-primary btn-block" id="loginbtn" type="submit" onclick="authenticate()">Sign in</button>
    </div>
    <?php
    $sql = "SELECT employee_name, employee_ID
            FROM employee";
    $result = mysqli_query($link, $sql);
    while($row = mysqli_fetch_array($result)){
      echo "<span>".$row[0]." ID: ".$row[1]."</span></br>";
    }
    ?>
  </div> <!-- /container -->
</body>
<script type='text/javascript'>
$(document).ready(function(){
  $('#password').keypress(function(e){
    if(e.keyCode==13)
      $('#loginbtn').click();
  });
});
</script>
</html>
