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
  $user_sec_lvl = str_split($user_sec_lvl);
  $user_sec_lvl = $user_sec_lvl[1];

  // if the user security level is not high enough we kill the page and give him a link to the log in page
  if($user_sec_lvl < 2){
    echo "<a href='../../Login/login.php'>Login Page</a></br>";
    die("You don't have the privileges to view this site.");
  }

  // find the current date
  $curDate = date("Y-m-d");
  $curDate = strtotime($curDate);


  // query to find how many active requests there are
  $activeRequestsSql = "SELECT COUNT(request_ID)
                        FROM order_request
                        WHERE active = 1 AND order_ID IS NULL;";
  $activeRequestsResult = mysqli_query($link, $activeRequestsSql);
  $activeRequests = mysqli_fetch_array($activeRequestsResult);
  if(!$activeRequestsResult){
    echo mysqli_error($link);
  }

  // Query to find all active requests
  $requestSql = "SELECT request_ID, request_date, request_supplier, approved_by_employee, request_description, employee_ID, department, timeframe, part_number, quantity, cost_code, request_price, unit_description, unit_price
                 FROM order_request
                 WHERE active = 1 AND order_ID IS NULL
                 ORDER BY CASE WHEN timeframe = 'Today' then 1 else 2 end,
                          CASE WHEN timeframe = 'This week' then 1 else 2 end;";
  $requestResult = mysqli_query($link, $requestSql);

  // Query to find all purchase orders that have been
  // requested and have not yet been received
   $inProgressSql = "SELECT o.order_ID, o.order_date, o.request_ID, o.order_final_inspection, o.order_name, o.supplier_ID, o.expected_delivery_date, o.net_terms, o.approval_status, comment, o.order_for_who, s.supplier_name
                     FROM purchase_order o, supplier s
                     WHERE order_receive_date IS NULL AND o.supplier_ID = s.supplier_ID
                     ORDER BY o.order_date asc;";
  $inProgressResult = mysqli_query($link, $inProgressSql);

  // Query to find 10 most recent purchase orders that
  // have been received
  $deliveredSql = "SELECT order_ID, order_date, order_receive_date, order_final_inspection, order_name, supplier_ID, ROUND(TOTAL_WEEKDAYS(order_date, order_receive_date), 2) - 1, net_terms
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
      echo "<title id='title'>(".$activeRequests[0].") Purchasing</title>";
    }
 ?>

<title id='title'>Fraunhofer CCD</title>
<body>
  <?php include '../header.php'; ?>
    <script type="text/javascript">
    window.onload = function() {
      $('input[type=date]').each(function() {
        if  (this.type != 'date' ) $(this).datepicker({
          dateFormat: 'yy-mm-dd'
        });
      });
    };
  </script>
  <div class="container">
    <div class="row well well-lg">
      <div class='col-md-12 col-md-offset-1'>
        <div class='col-md-2'>
          <button type="button" class='btn btn-primary col-md-12' onclick="location.href='supplierList.php'">Supplier list</button>
        </div>
        <div class='col-md-2'>
          <button type="button" class='btn btn-primary col-md-12' onclick="location.href='addQuote.php'">Quotes</button>
        </div>
        <div class='col-md-2'>
          <button type="button" class='btn btn-primary col-md-12' onclick="location.href='request.php'">Request for PO</button>
        </div>
        <?php
        if($user_sec_lvl > 2){
          ?>
          <div class='col-md-2' id='process_order'>
            <button type="button" class='btn btn-primary col-md-12' onclick="location.href='processOrder.php'">
              Process order
              <?php
                if($activeRequests[0] > 0){
                    echo "<span class='badge'>".$activeRequests[0]."</span>";
                }
               ?>
            </button>
          </div>
        <?php
        }
        if($user_sec_lvl > 3){
          ?>
          <div class='col-md-2 btn-group'>
              <button type='button' class='btn btn-primary col-md-9' onclick="location.href='overview.php'">Overview</button>
              <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
                <span class='caret'></span>
                <span class='sr-only'>Toggle Dropdown</span>
              </button>
              <ul class='dropdown-menu' role='menu'>
                <li><a href='forecast.php'>Forecast</a></li>
                <li><a href='quotes.php'>Quotes</a></li>
              </ul>
          </div>
        <?php } else {
          ?>
          <div class='col-md-2'>
            <button type="button" class='btn btn-primary col-md-12' onclick="location.href='quotes.php'">Quote overview</button>
          </div>
        <?php } ?>
      </div>
    </div>

    <!-- Here is the requested table ------------------------------------------------>
    <div class='col-md-6' style='padding-left: 0px'>
      <h3>Requested</h3>
      <table id='request_table' class='table table-responsive order-column'>
        <thead>
          <tr>
            <th>Request</th>
            <th>By</th>
            <th>Supplier</th>
            <th>Order by</th>
          </tr>
        </thead>
        <tbody>
          <?php
          while($requestRow = mysqli_fetch_array($requestResult)){

            // this variable shows the first 15 characters of the description
            // $description = substr($requestRow[4], 0, 15);

                // Query to find requests that don't belong to a purchase order
                // If it doesn't belong to a purchase order, then you can delete it
                $emptyRequestSql = "SELECT request_ID
                        FROM purchase_order
                        WHERE request_ID = '$requestRow[0]';";
                $emptyRequestResult = mysqli_query($link, $emptyRequestSql);
                $emptyRequestRow = mysqli_fetch_array($emptyRequestResult);

            // Query to find name of the employee who issued the request
            $employeeRequestSql = "SELECT employee_name
                                   FROM employee
                                   WHERE employee_ID = '$requestRow[5]';";
            $employeeRequestResult = mysqli_query($link, $employeeRequestSql);
            $employeeRequestRow = mysqli_fetch_array($employeeRequestResult);
            $employee_name = $employeeRequestRow[0];

            $employeeInitialsSql = "SELECT
                                    CONCAT_WS(' ',
                                      SUBSTRING_INDEX(employee_name, ' ', 1),
                                      CASE WHEN LENGTH(employee_name)-LENGTH(REPLACE(employee_name,' ',''))>2 THEN
                                        CONCAT(LEFT(SUBSTRING_INDEX(employee_name, ' ', -3), 1), '.')
                                      END,
                                      CASE WHEN LENGTH(employee_name)-LENGTH(REPLACE(employee_name,' ',''))>1 THEN
                                        CONCAT(LEFT(SUBSTRING_INDEX(employee_name, ' ', -2), 1), '.')
                                      END,
                                      CASE WHEN LENGTH(employee_name)-LENGTH(REPLACE(employee_name,' ',''))>0 THEN
                                        CONCAT(LEFT(SUBSTRING_INDEX(employee_name, ' ', -1), 1), '.')
                                      END) shortname
                                  FROM
                                    employee
                                  WHERE employee_ID = '$requestRow[5]';";
            $employee_initials = mysqli_fetch_row(mysqli_query($link, $employeeInitialsSql))[0];

            echo"
              <tr>
                <td>";
                  if($emptyRequestRow[0] != $requestRow[0]){
                  echo"
                  <a class='glyphicon glyphicon-edit' style='float:left; margin-right:8px;' onclick='displayEditRequestModal(".$requestRow[0].")'></a>
                  <span class='glyphicon glyphicon-remove' style='color: #C52F2B; float:left; margin-right:8px;' aria-hidden='true' onclick='delRequest(".$requestRow[0].")'></span>";
                }
              echo"
                <a href='#' data-toggle='modal' data-target='#".$requestRow[0]."'>".$requestRow[0]." </a></td>
                <td>".$employee_initials."</td>
                <td>".substr($requestRow[2],0,15)."</td>
                <td>".$requestRow[7];

                echo"</td>
              </tr>";
            echo"
              <div class='modal fade' id='".$requestRow[0]."' tabindex='-1' role='dialog' aria-labelledby='".$requestRow[0]."' aria-hidden='true'>
                <div class='modal-dialog'>
                  <div class='modal-content col-md-12'>
                    <div class='modal-header'>
                    <h4>Request ".$requestRow[0]."</h4>
                    <h5>By ".$employee_name." on ".$requestRow[1]."</h5>
                   <h5>".$requestRow[4]."</h5>
                    </div>
                    <div class='modal-body col-md-12'>
                      <p><strong>Supplier: </strong>".$requestRow[2]."</p>
                      <p><strong>Order timeframe: </strong>".$requestRow[7]."</p>
                      <p><strong>Department: </strong>".$requestRow[6]."</p>
                      <p><strong>Cost code: </strong>".$requestRow[10]."</p>
                      <p><strong>Part number: </strong>".$requestRow[8]."</p>
                       <p><strong>Part description: </strong>".$requestRow[12]."</p>
                      <p><strong>Quantity: </strong>".$requestRow[9]."</p>
                      <p><strong>Unit price: </strong>$".$requestRow[13]."</p>
                       <p><strong>Total price: </strong>$".$requestRow[11]."</p>
                    </div>
                    <div class='modal-footer'>
                      <button type='button' class='btn btn-primary' data-dismiss='modal'> Close</button>
                    </div>
                  </div>
                </div>
              </div>";
          }
          ?>
        </tbody>
      </table>
    </div>
   <!-- SelectPHP/editRequestModal.php -->
    <div id='editRequestModal' class='modal'></div>
    <!-- Here we have the In Progress table ---------------------------->
    <div class='col-md-6'>
      <h3>In Progress</h3>
      <table id='progress_table' class='table table-responsive order-column'>
        <thead>
          <tr>
            <th>PO</th>
            <th>For</th>
            <th>Supplier</th>
            <th>Ordered on</th>
            <th>Expected</th>
          </tr>
        </thead>
        <tbody>
          <?php
          while($inProgressRow = mysqli_fetch_array($inProgressResult)){
            // Find if the expected date is less then current date
            // and if it is, then we bold the expected date letters
            // So it's easy to see which orders are late
            $expectedDate = strtotime($inProgressRow[6]);
            $dateDiff = $expectedDate - $curDate;
            $dateDiffDays = floor($dateDiff/(60*60*24));
            if($inProgressRow[8] == 'pending'){
              echo"<tr class='bg-warning'>";
            } else if($inProgressRow[8] == 'approved'){
              echo"<tr class='bg-success'>";
            } else if($inProgressRow[8] == 'declined'){
              echo"<tr class='bg-danger'>";
            } else{
              echo"<tr>";
            }

            $employeeInitialsSql = "SELECT
                        CONCAT_WS(' ',
                          SUBSTRING_INDEX(employee_name, ' ', 1),
                          CASE WHEN LENGTH(employee_name)-LENGTH(REPLACE(employee_name,' ',''))>2 THEN
                            CONCAT(LEFT(SUBSTRING_INDEX(employee_name, ' ', -3), 1), '.')
                          END,
                          CASE WHEN LENGTH(employee_name)-LENGTH(REPLACE(employee_name,' ',''))>1 THEN
                            CONCAT(LEFT(SUBSTRING_INDEX(employee_name, ' ', -2), 1), '.')
                          END,
                          CASE WHEN LENGTH(employee_name)-LENGTH(REPLACE(employee_name,' ',''))>0 THEN
                            CONCAT(LEFT(SUBSTRING_INDEX(employee_name, ' ', -1), 1), '.')
                          END) shortname
                      FROM
                        employee
                      WHERE employee_ID = '$inProgressRow[10]';";
          $employee_initials = mysqli_fetch_row(mysqli_query($link, $employeeInitialsSql))[0];


              echo"<td><a href='#' onclick='setSessionIDSearch(".$inProgressRow[0].")' data-toggle='modal' data-target='#".$inProgressRow[0]."'>".$inProgressRow[4]."</a></td>
                  <td>".$employee_initials."</td>
                  <td>".substr($inProgressRow[11],0,15)."</td>
                  <td>".$inProgressRow[1]."</td>";
              if($dateDiffDays < 0){
                echo "<td><b>".$inProgressRow[6]."</b></td>";
              }else{
                echo "<td>".$inProgressRow[6]."</td>";
              }
                echo "</tr>";
          }
           ?>
        </tbody>
      </table>
      <?php
      $inProgressResult = mysqli_query($link, $inProgressSql);
      while($inProgressRow = mysqli_fetch_array($inProgressResult)){
        // Find the employee who the order is for
        $forEmployeeSql = "SELECT employee_name FROM employee WHERE employee_ID = '$inProgressRow[10]';";
        $forEmployeeResult = mysqli_query($link, $forEmployeeSql);
        $forEmployee = mysqli_fetch_array($forEmployeeResult);

        // If there is any expected pay date, put that in the correct format
        if($inProgressRow[7] == ""){
          $expectedPayDate = "N/A";
        } else{
          $expectedPayDate = date('Y-m-d', strtotime($inProgressRow[6]. ' + '.$inProgressRow[7].' days'));
        }
        $orderItemSql = "SELECT quantity, part_number, description, unit_price
                         FROM order_item
                         WHERE order_ID = '$inProgressRow[0]';";
        $orderItemResult = mysqli_query($link, $orderItemSql);
        $inProgressSupplierSql = "SELECT supplier_name
                                  FROM supplier
                                  WHERE supplier_ID = '$inProgressRow[5]';";
        $inProgressSupplierResult = mysqli_query($link, $inProgressSupplierSql);
        $inProgressSupplierRow = mysqli_fetch_array($inProgressSupplierResult);
        echo"
        <div class='modal fade' id='".$inProgressRow[0]."' tabindex='-1' role='dialog' aria-labelledby='".$inProgressRow[0]."' aria-hidden='true'>
          <div class='modal-dialog'>
            <div class='modal-content'>
              <div class='modal-header'>
                <center><h3>".$inProgressSupplierRow[0];
                if($inProgressRow[8] == 'pending'){ echo " (pending)";}
                else if($inProgressRow[8] == 'declined'){ echo " (declined)";}
                echo"</h3></center>
                <h4>Purchase order: ".$inProgressRow[4]."</h4>
                <h4>For employee: ".$forEmployee[0]."</h4>
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
                <p>Expected payment due: ".$expectedPayDate."</p>
                <form>
                  <label>In progress comment: </label>
                  <textarea class='form-control' id='inProgressComment'>".$inProgressRow[9]."</textarea>
                  <button class='btn btn-primary' style='margin-top: 5px;' onclick='addInProgressComment(".$inProgressRow[0].", this);'>Add comment</button>
                </form>
              </div>
              <div class='modal-footer'>";
                if($user_sec_lvl > 2){
                  echo"
                  <a href='../Views/addOrderItem.php' class='btn btn-primary' style='float:left'>Edit Order</a>";
                }
                echo"
                <a href='../Printouts/purchaseOrder.php' class='btn btn-primary' style='float:left'>Printout</a>";
                if($user_sec_lvl > 3){
                  echo"<button class='btn btn-danger' onclick='cancelPurchaseOrder(".$inProgressRow[0].")'>Cancel</button>";
                }
                echo"<a href='../Views/purchaseOrderReceived.php' class='btn btn-success'";
                if($inProgressRow[8] == 'pending' || $inProgressRow[8] == 'declined'){ echo " disabled";}
                echo">Received</a>
                <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
              </div>
            </div>
          </div>
        </div>";
      }
      ?>
    </div>

    <!-- Here we have the Delivered table -------------------------------->
 <!--    <div class='col-md-2' style='padding-right: 0px;'>
      <h4>Delivered</h4>
      <table class='table table-responsive'>
        <thead>
          <tr>
            <th>PO</th>
            <th>Received</th>
          </tr>
        </thead>
        <tbody> -->
          <?php
          // while($deliveredRow = mysqli_fetch_array($deliveredResult)){
          //     echo"<tr>
          //           <td><a href='#' data-toggle='modal' onclick='setSessionIDSearch(".$deliveredRow[0].")' data-target='#".$deliveredRow[0]."'>".$deliveredRow[4]."</a></td>
          //           <td>".$deliveredRow[2]."</td>
          //          </tr>";
          // }
          ?>
 <!--        </tbody>
      </table> -->
      <?php
      $deliveredResult = mysqli_query($link, $deliveredSql);
      while($deliveredRow = mysqli_fetch_array($deliveredResult)){
        $orderItemSql = "SELECT quantity, part_number, description, unit_price
                         FROM order_item
                         WHERE order_ID = '$deliveredRow[0]';";
        $orderItemResult = mysqli_query($link, $orderItemSql);
        $deliveredSupplierSql = "SELECT supplier_name
                                 FROM supplier
                                 WHERE supplier_ID = '$deliveredRow[5]';";
        $deliveredSupplierResult = mysqli_query($link, $deliveredSupplierSql);
        $deliveredSupplierRow = mysqli_fetch_array($deliveredSupplierResult);
        if($deliveredRow[7] == ""){
          $payDate = "N/A";
        }else{
          $payDate = date('Y-m-d', strtotime($deliveredRow[2]. ' + '.$deliveredRow[7].' days'));
        }
        echo"
        <div class='modal fade' id='".$deliveredRow[0]."' tabindex='-1' role='dialog' aria-labelledby='".$deliveredRow[0]."' aria-hidden='true'>
          <div class='modal-dialog'>
            <div class='modal-content'>
              <div class='modal-header'>
                <center><h3>".$deliveredSupplierRow[0]."</h3></center>
                <div id='invalidOrderFinalInspection'></div>
                <h4>Purchase order: ".$deliveredRow[4]."</h4>
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
                    // find if lead time is more then 1 day
                    if($deliveredRow[6] != 1){
                      $leadTime = $deliveredRow[6] . " days";
                    } else{
                      $leadTime = $deliveredRow[6] . " day";
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
                <p><strong>Order date: </strong>".$deliveredRow[1]."</p>
                <p><strong>Received date: </strong>".$deliveredRow[2]."</p>
                <p><strong>Lead time: </strong>".$leadTime."</p>
                <p><strong>Payment due: </strong>".$payDate."</p>
                <p><strong>Comment: </strong>".$deliveredRow[3]."</p>
              </div>
              <div class='modal-footer'>
                <div class='btn-group' style='float:left;'>
                    <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                      Edit <span class='caret'></span>
                    </button>
                    <ul class='dropdown-menu' role='menu'>
                      <li><a href='../Views/purchaseOrderReceived.php'>Edit received info</a></li>
                      <li><a href='../Views/addOrderItem.php'>Edit PO</a></li>
                    </ul>
                </div>
                <button type='button' onclick='printoutInfo(".$deliveredRow[0].")' class='btn btn-primary' style='float:left; margin-left:5px;'>Printout</button>
                <a href='../Views/viewAllImages.php' class='btn btn-primary' style='float:left'>View Scan</a>
                <button type='button' class='btn' data-dismiss='modal'>Close</button>
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
      // Make tables sortable and searchable. 
      // $('#request_table').DataTable();
      // $('#progress_table').DataTable();
      $('#request_table').dataTable( {
        "pageLength": 15
      } );
      $('#progress_table').dataTable( {
        "pageLength": 15
      } );
      // $('#request_table').DataTable( {
      //   paging: false
      // } );
      // $('#progress_table').DataTable( {
      //   paging: false
      // } );


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
    });

      // For the modal window to edit analysis results.
      var modal = document.getElementById('editRequestModal');
      function displayEditRequestModal(request_ID){
        editRequestModal(request_ID);
        modal.style.display = "block";
      }
      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        if (event.target == modal) {
         modal.style.display = "none";
        }
      }

  </script>
</body>
</html>
