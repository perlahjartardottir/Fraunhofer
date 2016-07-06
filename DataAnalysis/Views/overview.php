<!-- This is the front page -->
<!DOCTYPE html>
<html>
<head>
  <?php
  include '../../connection.php';
  session_start();
  // find the current user
  $user = $_SESSION["username"];
  // find his level of security
  $securitySql = "SELECT security_level
  FROM employee
  WHERE employee_name = '$user'";
  $securityResult = mysqli_query($link, $securitySql);
  while($row = mysqli_fetch_array($securityResult)){
    $securityLevel = $row[0];
  }
  // Get the third digit from the security level since that digit represents the
  // security level of the data analysis database
  $securityLevel = str_split($securityLevel);
  $securityLevel = $securityLevel[2];

  // // if the user security level is not high enough we kill the page and give him a link to the log in page
  // if($securityLevel < 4){
  //   echo "<a href='../../Login/login.php'>Login Page</a></br>";
  //   die("You don't have the privileges to view this site.");
  // } 

  ?>

  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
  <?php include '../header.php';?>
  <div class="container">
  <!--Analysis Equipment-->
    <div class='row well well-lg'>
      <div class='col-md-12'>
        <h3>Analysis Equipment</h3>
        <div class='btn-group'>
          <?php
          if($user_sec_lvl > 3){
            echo "<a href='addAnalysisEquipment.php' class='btn btn-success btn-lg'>Add new analysis equipment</a>";
          }
          ?>
          <div class='btn-group'>
            <a href='viewAnalysisEquipment.php' class='btn btn-primary btn-lg'>View all analysis equipment</a>
          </div>
        </div>
      </div>
    </div>
      <!--Process Equipment-->
    <div class='row well well-lg'>
      <div class='col-md-12'>
        <h3>Process Equipment</h3>
        <div class='btn-group'>
          <?php
          if($user_sec_lvl > 3){
            echo "<a href='../../Tooling/Views/addNewMachine.php' class='btn btn-success btn-lg'>Add new process equipment</a>";
          }
          ?>
          <div class='btn-group'>
            <a href='../../Tooling/Views/ViewAllMachines.php' class='btn btn-primary btn-lg'>View all process equipment</a>
          </div>
        </div>
      </div>
    </div>
  </div>