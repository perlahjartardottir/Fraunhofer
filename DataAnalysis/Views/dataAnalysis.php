<!-- In this view we only display some parts if the security level is high enough -->
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
  $secsql = "SELECT security_level
  FROM employee
  WHERE employee_name = '$user'";
  $secResult = mysqli_query($link, $secsql);
  while($row = mysqli_fetch_array($secResult)){
    $user_sec_lvl = $row[0];
  }
  // Get the third digit from the security level since that digit represents the
  // security level of the data analysis database
  $user_sec_lvl = str_split($user_sec_lvl);
  $user_sec_lvl = $user_sec_lvl[2];

  // if the user security level is not high enough we kill the page and give him a link to the log in page
  if($user_sec_lvl < 2){
    echo "<a href='../../Login/login.php'>Login Page</a></br>";
    die("You don't have the privileges to view this site.");
  }
  $processEquipmentSql = "SELECT prcs_eq_ID, prcs_eq_name
                          FROM process_equipment
                          WHERE prcs_eq_active = TRUE;";
  $processEquipmentResult = mysqli_query($link, $processEquipmentSql);

  $analysisEquipmentSql = "SELECT anlys_eq_ID, anlys_eq_name
                          FROM anlys_equipment
                          WHERE anlys_eq_active = TRUE;";
  $analysisEquipmentResult = mysqli_query($link, $analysisEquipmentSql);

  ?>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class="container">
    <div class='row well'>
      <div class='col-md-12'>
        <div class='col-md-6'>
          <button type='button' class='btn btn-primary col-md-12' onclick="location.href='addSample.php'">Add new sample</button>
        </div>
        <div class='col-md-6'>
          <button type='button' class='btn btn-primary col-md-12'>Add to existing sample</button>
        </div>
      </div>
    </div>
    <div class='col-md-4'>
      <h4>Recent Samples</h4>
      <table class='table table-responsive'>
        <thead>
          <tr>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Sample 1</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class='col-md-4'>
      <h4>Process Equipment</h4>
      <table class='table table-responsive'>
        <thead>
          <tr>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          while($processEqRow = mysqli_fetch_array($processEquipmentResult)){
            echo"
            <tr>
               <td><a href='#' data-toggle='modal' data-target='#".$processEqRow[0]."'>".$processEqRow[1]."</a><td>
            </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
    <div class='col-md-4'>
      <h4>Analysis Equipment <button type='button' class='btn btn-success' onclick="location.href=''"><span class='glyphicon glyphicon-plus' aria-hidden='true'></span></button></h4>
      <table class='table table-responsive'>
        <thead>
          <tr>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          while($analysisEqRow = mysqli_fetch_array($analysisEquipmentResult)){
            echo"
            <tr>
              <td><a href='#' onclick='' data-toggle='modal' data-target='#".$analysisEqRow[0]."'>".$analysisEqRow[1]."</a></td>
              </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
    <?php
    $analysisEquipmentResult = mysqli_query($link, $analysisEquipmentSql);
    while($analysisEqRow = mysqli_fetch_array($analysisEquipmentResult)){
    echo"
    <div class='modal fade' id='".$analysisEqRow[0]."' tabindex='-1' role='dialog' aria-labelledby='".$analysisEqRow[0]."' aria-hidden='true'>
          <div class='modal-dialog'>
            <div class='modal-content'>
              <div class='modal-header'>
                <center><h3>Hello - ".$analysisEqRow[1]."</h3></center>
                <div class='modal-body'>
                </div>
                </div>
                </div>
                </div>";
              }

                ?>
  </div>
</body>
</html>