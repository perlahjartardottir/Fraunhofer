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
          <button type='button' class='btn btn-primary col-md-12'>Add new sample</button>
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
          <tr>
            <td>Equipment 1</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class='col-md-4'>
      <h4>Analysis Equipment</h4>
      <table class='table table-responsive'>
        <thead>
          <tr>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Equipment 1</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
