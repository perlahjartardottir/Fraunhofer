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
  $secsql = "SELECT security_level, employee_ID
             FROM employee
             WHERE employee_name = '$user'";
  $secResult = mysqli_query($link, $secsql);

  while($row = mysqli_fetch_array($secResult)){
    $user_sec_lvl = $row[0];
    $employee_ID  = $row[1];
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

  $requestSql = "SELECT request_ID, request_date, request_supplier, approved_by_employee, request_description
                 FROM order_request
                 WHERE employee_ID = '$employee_ID'
                 AND active = 1;";
  $requestResult = mysqli_query($link, $requestSql);
  ?>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
</head>
<?php
    if($activeRequests[0] > 0){
      echo "<title id='title'>New request</title>";
    } else{
      echo "<title id='title'>Purchasing</title>";
    }
 ?>

<title id='title'>Fraunhofer CCD</title>
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
        <div class='col-md-3' id='process_order'>
          <button type="button" class='btn btn-primary col-md-8' onclick="location.href='processOrder.php'">
            Process order
            <?php
              if($activeRequests[0] > 0){
                  echo "<span class='badge'>".$activeRequests[0]."</span>";
              }
             ?>
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
            <th>Request</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php
          while($requestRow = mysqli_fetch_array($requestResult)){

            // this variable shows the first 15 characters of the description
            $description = substr($requestRow[4], 0, 15);

            echo"
              <tr>
                <td><a href='#' data-toggle='modal' data-target='#".$requestRow[0]."'>".$description."... </td>
                <td>".$requestRow[1]."<button style='float:right;' class='btn btn-danger btn-xs' onclick='delRequest(".$requestRow[0].")'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button></td>
              </tr>";
            echo"
              <div class='modal fade' id='".$requestRow[0]."' tabindex='-1' role='dialog' aria-labelledby='".$requestRow[0]."' aria-hidden='true'>
                <div class='modal-dialog'>
                  <div class='modal-content col-md-12'>
                    <div class='modal-header'>
                      <h4>Request: ".$requestRow[0]."</h4>
                    </div>
                    <div class='modal-body col-md-12'>
                      <p>Date: ".$requestRow[1]."</p>
                      <p>Supplier: ".$requestRow[2]."</p>
                      <p>Approved by: ".$requestRow[3]."</p>
                      <p>Description: ".$requestRow[4]."</p>
                    </div>
                    <div class='modal-footer'>
                      <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
                    </div>
                  </div>
                </div>
              </div>";
          }
          ?>
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
  <script>
    $(document).ready(function(){
      setInterval(test, 10000);
      function test(){
          $.ajax({
            url: "../UpdatePHP/update_request_count.php",
            type: "POST",
            data: {
            },
            success: function(data, status, xhr) {
              $('#process_order').html(data);
            }
          });
        }
        setInterval(function(){
          var title = document.title;
              $.ajax({
                url: "../UpdatePHP/update_title_text.php",
                type: "POST",
                data: {
                },
                success: function(data, status, xhr) {
                  //document.title = (data);
                  if(data == "Purchasing"){
                    document.title = "Purchasing";
                    return;
                  } else {
                    document.title = (title == "New request" ? data : "New request");
                  }
                }
              });
      }, 1000);
  });

  </script>
</body>
</html>
