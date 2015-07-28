<!DOCTYPE html>
<?php
include '../connection.php';
session_start();
//find the current user
//find his level of security
$secsql = "SELECT security_level
           FROM employee
           WHERE employee_name = '" . $_SESSION['username'] . "';";
$secResult = mysqli_query($link, $secsql);

while($row = mysqli_fetch_array($secResult)){
  $user_sec_lvl = $row[0];
}
?>
<html>
<head>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
  <link href='../css/main.css' rel='stylesheet'>



</head>
<body>
<?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well well-lg'>
      <div class='col-md-12'>
        <h2>Employees</h2>
        <table id="report" class='col-md-12'>
          <tr>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Security Level</th>
          </tr>
          <?php
          $sql ="SELECT employee_ID, employee_name, employee_email, employee_phone, security_level
                 FROM employee";
          $result = mysqli_query($link, $sql);
          if (!$result){
           die("Database query failed: " . mysql_error());
          }
         while($row = mysqli_fetch_array($result)){
            echo "<tr>".
                    "<td>".$row[0]."</td>".
                    "<td>".$row[1]."</td>".
                    "<td>"."<a href='mailto:$row[2]'>".$row[2]."</a>".
                    "</td>".
                    "<td>".$row[3]."</td>".
                    "<td>".$row[4]."</td>".
                "</tr>";
         }
        ?>
      </table>
    </div>
  </div>
    <?php
    if($user_sec_lvl >=3)
    {
      echo"
        <div class='row well well-lg'>
          <form>
            <h4>Enter employee ID to insert or change some values in the table. The employee ID can not be changed!</h4>
            <div class='col-md-12'>
              <div class='col-md-4 form-group'>
                <label>Enter the employee ID: </label>
                <input type='number' id='input_employee_ID' class='form-control'/>
              </div>
              <div class='col-md-4 form-group'>
                <label>Change employee name:</label>
                <input type='text' id='input_employee_name' class='form-control'/>
              </div>
              <div class='col-md-4 form-group'>
                <label>Change employee email:</label>
                <input type='text' id='input_employee_email' class='form-control'/>
              </div>
            </div>
            <div class='col-md-12'>
            <div class='col-md-4 form-group'>
              <label>Change employee phone:</label>
              <input type='text' id='input_employee_phone' class='form-control'/>
            </div>
            <div class='col-md-4 form-group'>
              <label>Change employee security level:</label>
              <input type='text' id='input_security_level' class='form-control'/>
            </div>

            <div class='col-md-4 form-group'>
              <label>Delete employee: </label>
              <button type='button' class='btn btn-danger form-control' style='width:auto;' onclick='deleteEmployee()'>
                <span class='glyphicon glyphicon-trash' aria-hidden='true'></span>
              </button>
            </div>
          </div>
            <div class='col-md-12' style='float:right;'>
              <input type='submit' value='Submit changes' onclick='changeEmployee()' class='btn btn-primary' style='float:right;'/>
            </div>
          </form>
        </div>";
    }
  ?>
</div>
</body>
</html>
