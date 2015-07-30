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
  // query to find how many active requests there are
  $activeRequestsSql = "SELECT COUNT(request_ID)
                        FROM order_request
                        WHERE active = 1;";
  $activeRequestsResult = mysqli_query($link, $activeRequestsSql);
  $activeRequests = mysqli_fetch_array($activeRequestsResult);
  if(!$activeRequestsResult){
    echo mysqli_error($link);
  }
  ?>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class="container">
    <div class="row well well-lg">
      <div class='col-md-12'>
        <div class='col-md-3'>
          <button type="button" class='btn btn-primary col-md-8' onclick="location.href='supplierList.php'">Supplier list</button>
        </div>
        <div class='col-md-3'>
          <button type="button" class='btn btn-primary col-md-8' onclick="location.href='request.php'">Request for PO</button>
        </div>
        <div class='col-md-3'>
          <button type="button" class='btn btn-primary col-md-8' onclick="location.href='processOrder.php'">
            Process order <span class='badge'>".$activeRequests[0]."</span>
          </button>
        </div>
        <div class='col-md-3'>
          <button type='button' class='btn btn-primary col-md-8' onclick="location.href='purchaseOverview.php'">Overview</button>
        </div>
      </div>
    </div>
    <div class='col-md-4'>
      <h4>Requested</h4>
      <table class='table table-responsive'>
        <thead>
          <tr>
            <th>Purchase Order</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    <div class='col-md-4'>
      <h4>In progress</h4>
      <table class='table table-responsive'>
        <thead>
          <tr>
            <th>Purchase Order</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    <div class='col-md-4'>
      <h4>Delivered</h4>
      <table class='table table-responsive'>
        <thead>
          <tr>
            <th>Purchase Order</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
