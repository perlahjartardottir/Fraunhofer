<?php
include '../../connection.php';
session_start();

//find the current user
$user = $_SESSION["username"];
//find his level of security
$secsql = "SELECT security_level
           FROM employee
           WHERE employee_name = '$user'";
$secResult = mysqli_query($link, $secsql);

while($row = mysqli_fetch_array($secResult)){
  $user_sec_lvl = $row[0];
}
$user_sec_lvl = str_split($user_sec_lvl);
$user_sec_lvl = $user_sec_lvl[1];
// if the user security level is not high enough we kill the page and give him a link to the log in page
if($user_sec_lvl < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}

// SQL query to find the employee who is making the order
$curEmployeeSql = "SELECT employee_ID
                   FROM employee
                   WHERE employee_name = '$user'";
$curEmployeeResult = mysqli_query($link, $curEmployeeSql);
$employee_ID = mysqli_fetch_array($curEmployeeResult);


// SQL query for the employee list
$employeeSql = "SELECT employee_name
                FROM employee;";
$employeeResult = mysqli_query($link, $employeeSql);

// Query for supplier list
$supplierSql = "SELECT supplier_name
                FROM supplier;";
$supplierResult = mysqli_query($link, $supplierSql);
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <?php echo"<input type='hidden' id='employee_ID' value='".$employee_ID[0]."'>"; ?>
  <div class='container'>
    <div id='invalidPO'></div>
    <div class='row well well-lg'>
      <form>
        <h4>Purchase order</h4>
        <div class='col-md-6 form-group'>
          <label>Employee: </label>
            <input type='text' list="employees" name="employeeList" id='employeeList' value='' class='col-md-12 form-control'>
            <datalist id="employees">
              <?
              while($row = mysqli_fetch_array($employeeResult)){
                echo"<option value='".$row[0]."'></option>";
              }
              ?>
            </datalist>
        </div>
        <div class='col-md-6 form-group'>
          <label>Supplier: </label>
            <input type='text' list="suppliers" name="supplierList" id='supplierList' value='' class='col-md-12 form-control'>
            <datalist id="suppliers">
              <?
              while($row = mysqli_fetch_array($supplierResult)){
                echo"<option value='".$row[0]."'></option>";
              }
              ?>
            </datalist>
        </div>
        <div class='col-md-6 form-group'>
          <label>Approved by: </label>
          <input type="text" class='form-control' id='approved_by'>
        </div>
        <input class='form-control btn btn-primary' type="button" value="Order" onclick='createPurchaseOrder()'>
      </form>
    </div>
    <div class='row well well-lg col-md-6'>
      <h4>Select request for the PO</h4>
      <table class='table table-responsive' id='activeRequestTable'>
        <thead>
          <tr>
            <th>Requests</th>
            <th>Employee</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td onclick='activeRequest()'><a href='#'>No request</a></td>
            <td></td>
            <td></td>
          </tr>
          <?php
          $sql = "SELECT request_ID, employee_ID, request_date, request_description
                  FROM order_request
                  WHERE active = 1;";
          $result = mysqli_query($link, $sql);

          while($row = mysqli_fetch_array($result)){
            $employeeSql = "SELECT employee_name
                            FROM employee
                            WHERE employee_ID = '$row[1]'";
            $employeeResult = mysqli_query($link, $employeeSql);
            $employee = mysqli_fetch_array($employeeResult);
            echo "<tr>
                    <td onclick='activeRequest(this)' id='request_ID'><a href='#' onclick='return false;'>".$row[0]."</a></td>
                    <td id='employee_name'>".$employee[0]."</td>
                    <td>".$row[2]."<button style='float:right;' class='btn btn-danger btn-xs' onclick='finishRequest(".$row[0].")'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button></td>
                  </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

    <!-- js/app.js and the function activeRequest(element) -->
    <div id='output'>
    </div>
  </div>
</body>
