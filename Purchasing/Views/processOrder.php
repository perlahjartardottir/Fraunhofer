<?php
include '../../connection.php';
session_start();
$user = $_SESSION["username"];
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
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <?php echo"<input type='hidden' id='employee_ID' value='".$employee_ID[0]."'>"; ?>
  <div class='container'>
    <div class='row well well-lg'>
      <form>
        <h4>Purchase order</h4>
        <div class='col-md-6 form-group'>
          <label>Employee: </label>
          <br>
            <input list="employees" name="employees" class='col-md-12 form-control'>
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
          <input type="text" class='form-control' id='supplier_name'>
        </div>
        <div class='col-md-6 form-group'>
          <label>Approved by: </label>
          <input type="text" class='form-control' id='approved_by'>
        </div>
        <input class='form-control btn btn-primary' type="button" value="Order" onclick='createPurchaseOrder()'>
      </form>
    </div>
    <div class='row well well-lg col-md-5'>
      <table class='table table-responsive' id='activeRequestTable'>
        <thead>
          <tr>
            <th>Requests</th>
            <th>Employee</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
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
                    <td>".$row[2]."</td>
                  </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

    <!-- js/app.js and the function activeRequest(element) -->
    <div id='output' class='col-md-6 col-md-offset-1'>
    </div>
  </div>
</body>
