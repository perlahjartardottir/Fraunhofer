<!DOCTYPE html>
<html lang="en">
<head>
  <?php
  include '../connection.php';
    session_start();
    //find the current user
    $user = $_SESSION["username"];
    //find his level of security
    $secsql = "SELECT security_level, employee_ID, employee_email, employee_phone
               FROM employee
               WHERE employee_name = '$user'";
    $secResult = mysqli_query($link, $secsql);

    while($row = mysqli_fetch_array($secResult)){
      $user_sec_lvl = $row[0];
      $employee_ID = $row[1];
      $employee_email = $row[2];
      $employee_phone = $row[3];
    }
  ?>
  <title>Login Fraunhofer CCD</title>
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom styles for this template -->
</head>
<body>
  <?php include '../header.php'; ?>
  <div class="container">
    <div class='row well'>
      <form>
        <div class='col-md-12'>
          <h3>Edit profile</h3>
          <div class='col-md-6 form-group'>
            <label>Email:</label>
            <input type='text' id='input_employee_email' class='form-control' value='<?php echo $employee_email;?>'/>
          </div>
          <div class='col-md-6 form-group'>
            <label>Phone:</label>
            <input type='text' id='input_employee_phone' class='form-control' value='<?php echo $employee_phone;?>'/>
          </div>
          <div class='col-md-8 col-md-offset-2'>
            <input type='submit' value='Submit changes' onclick='editProfile(<?php echo $employee_ID;?>)' class='btn btn-primary form-control'/>
          </div>
        </div>
      </form>
    </div>
    <div id='error'></div>
    <div class='row well'>
      <div class='col-md-12'>
        <h3>Edit password</h3>
        <div class='col-md-4 form-group'>
          <label>Current password:</label>
          <input type='password' id='currentPass' class='form-control'>
        </div>
        <div class='col-md-4 form-group'>
          <label>New password:</label>
          <input type='password' id='newPass' class='form-control'>
        </div>
        <div class='col-md-4 form-group'>
          <label>Confirm password:</label>
          <input type='password' id='confirmPass' class='form-control'>
        </div>
        <div class='col-md-8 col-md-offset-2'>
          <input type='submit' value='Change password' onclick='changePassword(<?php echo $employee_ID;?>)' class='btn btn-primary form-control'/>
        </div>
      </div>
    </div>
    <div class='row well'>
      <div class='col-md-12'>
        <h3>Edit e-signature</h3>
        <form action='../Purchasing/InsertPHP/addEmployeeSignature.php?id=<?php echo $employee_ID;?>' method='post' enctype='multipart/form-data'>
          <div class='col-md-6'>
            <label>Select signature to upload:</label>
            <input type='hidden' value='editProfile' id='redirect' name='redirect'>
            <input type='file' name='fileToUpload' id='fileToUpload' accept='image/jpeg/png'>
          </div>
          <div class='col-md-6' style='margin-top:-40px; margin-bottom: 15px;'>
            <input type='image' src='../Purchasing/Scan/getSignature.php?id=<?php echo $employee_ID;?>' width='250' height='100' onerror="this.src='../Purchasing/images/noimage.jpg'"/>
          </div>
          <div class='col-md-8 col-md-offset-2'>
            <input type='submit' class='btn btn-primary col-md-12' value='Upload signature' name='submit'>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
