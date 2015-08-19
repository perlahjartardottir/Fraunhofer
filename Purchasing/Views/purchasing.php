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

  // Query to find all active requests
  $requestSql = "SELECT request_ID, request_date, request_supplier, approved_by_employee, request_description, employee_ID
                 FROM order_request
                 WHERE active = 1;";
  $requestResult = mysqli_query($link, $requestSql);

  // Query to find all purchase orders that have been
  // requested and have not yet been received
  $inProgressSql = "SELECT order_ID, order_date, request_ID
                    FROM purchase_order
                    WHERE order_receive_date IS NULL;";
  $inProgressResult = mysqli_query($link, $inProgressSql);

  // Query to find 10 most recent purchase orders that
  // have been received
  $deliveredSql = "SELECT order_ID, order_date
                    FROM purchase_order
                    WHERE order_receive_date IS NOT NULL
                    ORDER BY order_receive_date DESC
                    LIMIT 10;";
  $deliveredResult = mysqli_query($link, $deliveredSql);
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

    <!-- Here is the requested table ------------------------------------------------>
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

            // Query to find name of the employee who issued the request
            $employeeRequestSql = "SELECT employee_name
                                   FROM employee
                                   WHERE employee_ID = '$requestRow[5]';";
            $employeeRequestResult = mysqli_query($link, $employeeRequestSql);
            $employeeRequestRow = mysqli_fetch_array($employeeRequestResult);
            $employee_name = $employeeRequestRow[0];

            echo"
              <tr>
                <td><a href='#' data-toggle='modal' data-target='#".$requestRow[0]."'>".$description."... </td>
                <td>".$requestRow[1];

                // Query to find requests that don't belong to a purchase order
                // If it doesn't belong to a purchase order, then you can delete it
                $emptyRequestSql = "SELECT request_ID
                        FROM purchase_order
                        WHERE request_ID = '$requestRow[0]';";
                $emptyRequestResult = mysqli_query($link, $emptyRequestSql);
                $emptyRequestRow = mysqli_fetch_array($emptyRequestResult);
                if($emptyRequestRow[0] != $requestRow[0]){
                  echo"<button style='float:right;' class='btn btn-danger btn-xs' onclick='delRequest(".$requestRow[0].")'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button>";
                }
                echo"</td>
              </tr>";
            echo"
              <div class='modal fade' id='".$requestRow[0]."' tabindex='-1' role='dialog' aria-labelledby='".$requestRow[0]."' aria-hidden='true'>
                <div class='modal-dialog'>
                  <div class='modal-content col-md-12'>
                    <div class='modal-header'>
                      <h4>Request: ".$requestRow[0]."</h4>
                    </div>
                    <div class='modal-body col-md-12'>
                      <p>Requested by: ".$employee_name."</p>
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

    <!-- Here we have the In Progress table ---------------------------->
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
          <?php
          while($inProgressRow = mysqli_fetch_array($inProgressResult)){
              echo"<tr>
                    <td><a href='#' onclick='setSessionIDSearch(".$inProgressRow[0].")' data-toggle='modal' data-target='#".$inProgressRow[0]."'>".$inProgressRow[0]."</a></td>
                    <td>".$inProgressRow[1]."</td>
                   </tr>";
          }
           ?>
        </tbody>
      </table>
      <?php
      $inProgressResult = mysqli_query($link, $inProgressSql);
      while($inProgressRow = mysqli_fetch_array($inProgressResult)){
        $orderItemSql = "SELECT quantity, part_number, description, unit_price
                         FROM order_item
                         WHERE order_ID = '$inProgressRow[0]';";
        $orderItemResult = mysqli_query($link, $orderItemSql);
        echo"
        <div class='modal fade' id='".$inProgressRow[0]."' tabindex='-1' role='dialog' aria-labelledby='".$inProgressRow[0]."' aria-hidden='true'>
          <div class='modal-dialog'>
            <div class='modal-content'>
              <div class='modal-header'>
                <h4>Purchase order: ".$inProgressRow[0]."</h4>
              </div>
              <div class='modal-body'>
                <table class='table table-responsive'>
                  <thead>
                    <tr>
                      <th>Pos. #</th>
                      <th>Quantity</th>
                      <th>Part #</th>
                      <th>Description</th>
                      <th>USD Unit</th>
                      <th>USD Total</th>
                    </tr>
                  </thead>
                  <tbody>";
                    $counter = 1;
                    $totalOrderPrice = 0;
                    while($orderItemRow = mysqli_fetch_array($orderItemResult)){
                      $total = $orderItemRow[0] * $orderItemRow[3];
                      $totalOrderPrice = $totalOrderPrice + $total;
                      echo"
                        <tr>
                          <td>".$counter."</td>
                          <td>".$orderItemRow[0]."</td>
                          <td>".$orderItemRow[1]."</td>
                          <td>".$orderItemRow[2]."</td>
                          <td>$".number_format((float)$orderItemRow[3], 2, '.', '')."</td>
                          <td>$".number_format((float)$total, 2, '.', '')."</td>";
                          $counter = $counter + 1;
                    }
                  echo"
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <th>Total Order Price:</th>
                      <th><u style='border-bottom: 1px solid black'>$".number_format((float)$totalOrderPrice, 2, '.', '')."</u></th>
                    </tr>
                  </tbody>
                </table>
                <p>Order date: ".$inProgressRow[1]."</p>
              </div>
              <div class='modal-footer'>
                <a href='../Printouts/purchaseOrder.php' class='btn btn-primary' style='float:left'>Printout</a>
                <a href='../Views/purchaseOrderReceived.php' class='btn btn-primary' style='float:left'>Received</a>
                <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
              </div>
            </div>
          </div>
        </div>";
      }
      ?>
    </div>

    <!-- Here we have the Delivered table -------------------------------->
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
          <?php
          while($deliveredRow = mysqli_fetch_array($deliveredResult)){
              echo"<tr>
                    <td><a href='#' data-toggle='modal' data-target='#".$deliveredRow[0]."'>".$deliveredRow[0]."</a></td>
                    <td>".$deliveredRow[1]."</td>
                   </tr>";
          }
          ?>
        </tbody>
      </table>
      <?php
      $deliveredResult = mysqli_query($link, $deliveredSql);
      while($deliveredRow = mysqli_fetch_array($deliveredResult)){
        $orderItemSql = "SELECT quantity, part_number, description, unit_price
                         FROM order_item
                         WHERE order_ID = '$deliveredRow[0]';";
        $orderItemResult = mysqli_query($link, $orderItemSql);
        echo"
        <div class='modal fade' id='".$deliveredRow[0]."' tabindex='-1' role='dialog' aria-labelledby='".$deliveredRow[0]."' aria-hidden='true'>
          <div class='modal-dialog'>
            <div class='modal-content'>
              <div class='modal-header'>
                <div id='invalidOrderFinalInspection'></div>
                <h4>Purchase order: ".$deliveredRow[0]."</h4>
              </div>
              <div class='modal-body'>
                <table class='table table-responsive'>
                  <thead>
                    <tr>
                      <th>Pos. #</th>
                      <th>Quantity</th>
                      <th>Part #</th>
                      <th>Description</th>
                      <th>USD Unit</th>
                      <th>USD Total</th>
                    </tr>
                  </thead>
                  <tbody>";
                    $counter = 1;
                    $totalOrderPrice = 0;
                    while($orderItemRow = mysqli_fetch_array($orderItemResult)){
                      $total = $orderItemRow[0] * $orderItemRow[3];
                      $totalOrderPrice = $totalOrderPrice + $total;
                      echo"
                        <tr>
                          <td>".$counter."</td>
                          <td>".$orderItemRow[0]."</td>
                          <td>".$orderItemRow[1]."</td>
                          <td>".$orderItemRow[2]."</td>
                          <td>$".number_format((float)$orderItemRow[3], 2, '.', '')."</td>
                          <td>$".number_format((float)$total, 2, '.', '')."</td>";
                          $counter = $counter + 1;
                    }
                  echo"
                    <tr>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <th>Total Order Price:</th>
                      <th><u style='border-bottom: 1px solid black'>$".number_format((float)$totalOrderPrice, 2, '.', '')."</u></th>
                    </tr>
                  </tbody>
                </table>
                <p>Order date: ".$deliveredRow[1]."</p>
                <form>
                  <h4>Inspection note:</h4>
                  <textarea class='form-control' id='order_final_inspection'></textarea>
                  <h4 style='margin-top: 20px; margin-bottom:-5px'>Rating: </h4>
                  <table class='table table-responsive col-md-12'>
                    <thead>
                      <tr>
                        <th>Timeliness</th>
                        <th>Quality</th>
                        <th>Price</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class='col-md-4'>
                          <select id='rating_timeliness' class='form-control'>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option selected>5</option>
                          </select>
                        </td>
                        <td class='col-md-4'>
                          <select id='rating_quality' class='form-control'>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option selected>5</option>
                          </select>
                        </td>
                        <td class='col-md-4'>
                          <select id='rating_price' class='form-control'>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option selected>5</option>
                          </select>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <button type='button' style='margin-top:5px;' onclick='setFinalInspectionNote(".$deliveredRow[0].")' class='btn btn-primary'>Set final inspection note</button>
                </form>
              </div>
              <div class='modal-footer'>
                <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
              </div>
            </div>
          </div>
        </div>";
      }
      ?>
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
        var title_function = setInterval(function(){
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
                  } else {
                    document.title = (title == "New request" ? data : "New request");
                  }
                }
              });
      }, 10000);
  });
  </script>
</body>
</html>
