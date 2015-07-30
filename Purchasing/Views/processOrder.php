<?php
include '../../connection.php';
session_start();
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well well-lg'>
      <form>
        <h4>Purchase order</h4>
        <p class='col-md-6 form-group'>
          <label>Employee: </label>
          <input type="text" class='form-control'>
        </p>
        <p class='col-md-6 form-group'>
          <label>Supplier: </label>
          <input type="text" class='form-control'>
        </p>
        <p class='col-md-6 form-group'>
          <label>Quantity: </label>
          <input type="text" class='form-control'>
        </p>
        <p class='col-md-6 form-group'>
          <label>Approved by: </label>
          <input type="text" class='form-control'>
        </p>
        <p class='col-md-6 form-group'>
          <label>Description: </label>
          <textarea class='form-control'></textarea>
        </p>
        <input class='form-control btn btn-primary' type="button" value="Order" onclick='order()'>
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
          $sql = "SELECT request_ID, employee_ID, request_date
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
    <div id='output' class='col-md-6 col-md-offset-1'>
    </div>
  </div>
</body>
