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

// show latest purchase orders
$sql = "SELECT order_ID
        FROM purchase_order
        ORDER BY order_ID DESC
        LIMIT 10;";
$result = mysqli_query($link, $sql);
$order_ID = $_SESSION["order_ID"];

// Get the request and supplier ID from the purchase order
$getRequestSql = "SELECT request_ID, supplier_ID, approval_status, approved_by, approval_response, order_for_who
                  FROM purchase_order
                  WHERE order_ID = '$order_ID';";
$getRequestResult = mysqli_query($link, $getRequestSql);
$row = mysqli_fetch_array($getRequestResult);
$request_ID = $row[0];
$supplier_ID = $row[1];
$approval_status = $row[2];
$approved_by = $row[3];
$approval_response = $row[4];
$order_for_who = $row[5];

// Get supplier name from its ID
$getSupplierNameSql = "SELECT supplier_name
                       FROM supplier
                       WHERE supplier_ID = '$supplier_ID';";
$getSupplierNameResult = mysqli_query($link, $getSupplierNameSql);
$supplierRow = mysqli_fetch_array($getSupplierNameResult);
$supplier_name = $supplierRow[0];

// Find all active requests that have the same supplier and are not the current request
$findActiveRequestsSql = "SELECT request_ID, request_supplier, request_date, employee_ID, request_description, part_number, quantity
                          FROM order_request
                          WHERE active = 1
                          AND request_supplier = '$supplier_name'
                          AND request_ID != '$request_ID';";
$findActiveRequestsResult = mysqli_query($link, $findActiveRequestsSql);
if(!$findActiveRequestsResult){
  die(mysqli_error($link));
}

// Get quotes that are linked to this purchase order or linked to
// the request that is linked to this purchase order
$quoteSql = "SELECT quote_ID, image, quote_number, supplier_ID, quote_date
             FROM quote
             WHERE request_ID = '$request_ID'
             OR order_ID = '$order_ID';";
$quoteResult = mysqli_query($link, $quoteSql);

// Update the quote to have this order ID if we only maintained that
// quote from a request
while($quoteRow = mysqli_fetch_array($quoteResult)){
  if($quoteRow[3] == $supplier_ID){
    $quoteUpdateSql = "UPDATE quote
                       SET order_ID = '$order_ID'
                       WHERE quote_ID = $quoteRow[0];";
    $quoteUpdateResult = mysqli_query($link, $quoteUpdateSql);
  }
}

// Find all departments
$departmentSql = "SELECT department_name
                  FROM department;";
$departmentResult = mysqli_query($link, $departmentSql);

// Find all suppliers
$supplierSql = "SELECT supplier_name
                FROM supplier;";
$supplierResult = mysqli_query($link, $supplierSql);

//Get total value of the po
$totalValueSql = "SELECT SUM(oi.quantity * oi.unit_price)
                  FROM purchase_order po, order_item oi
                  WHERE po.order_ID = oi.order_ID
                  AND po.order_ID = '$order_ID';";
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <?php
     if($approval_status == 'pending'){
       echo"<div class='alert alert-warning fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>This PO is waiting for approval</div>";
     } else if($approval_status == 'approved'){
       echo"<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>This PO has been approved by ".$approved_by."</div>
       <div class='row well'>
         <h4>Message from ".$approved_by."</h4>
         <p>".$approval_response."</p>
       </div>";
     } else if($approval_status == 'declined'){
       echo"<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>This PO has been declined by ".$approved_by."</div>
       <div class='row well'>
         <h4>Message from ".$approved_by."</h4>
         <p>".$approval_response."</p>
       </div>";
     }
     ?>
    <div class='row well well-lg'>
      <form>
        <div class='form-group col-md-6'>
          <label>Purchase order: </label>
          <select class='form-control' onchange='showPOInfoAndRefreshImage(this.value)' id='purchaseOrder' style='width:auto;'>
            <option value=''>Select a PO#: </option>
            <?
            while($row = mysqli_fetch_array($result)){
              echo"<option value='".$row[0]."'>".$row[0]."</option>";
            }
            ?>
          </select>

          <!-- SelectPHP/POInfoForOrderItem ------------------------->
          <br><div id="poinfo"><b>PO info will be listed here</b></div>
        </div>
        <div class='form-group col-md-6'>
          <?php
          $requestSql = "SELECT request_description, department, part_number, quantity, cost_code
                         FROM order_request
                         WHERE request_ID = '$request_ID';";
          $requestResult = mysqli_query($link, $requestSql);
          $requestRow = mysqli_fetch_array($requestResult);
          if($requestRow > 0){
            echo"<h4>Request ID: ".$request_ID."</h4>
                 <p><b>Department:</b> ".$requestRow[1]."</p>
                 <p><b>Cost code:</b> ".$requestRow[4]."</p>
                 <p><b>Part number:</b> ".$requestRow[2]."</p>
                 <p><b>Quantity:</b> ".$requestRow[3]."</p>
                 <p><b>Description:</b> ".$requestRow[0]."</p>";
          }
          $quoteResult = mysqli_query($link, $quoteSql);
          while($quoteRow = mysqli_fetch_array($quoteResult)){
            $supplierNameSql = "SELECT supplier_name
                                FROM supplier
                                WHERE supplier_ID = '$quoteRow[3]';";
            $supplierNameResult = mysqli_query($link, $supplierNameSql);
            $supplierNameRow = mysqli_fetch_array($supplierNameResult);
            echo"<div class='col-md-3'>
                  <p><input type='image' src='../Scan/getQuoteImage.php?id=".$quoteRow[0]."' width='100' height='100' onerror=\"this.src='../images/noimage.jpg'\" onclick=\"window.open('../Printouts/quotePrintout.php?id=".$quoteRow[0]."')\"/></p>
                  <button class='btn btn-danger' style='margin-top:5px; margin-right:20px' onclick='deleteQuote(".$quoteRow[0].")'>Delete</button>
                </div>
                <div class='col-md-3'>
                  <p><strong>Quote number: </strong><a href='../SelectPHP/download.php?id=".$quoteRow[0]."'>".$quoteRow[2]."</a><br></p>
                  <p><strong>Supplier: </strong>".$supplierNameRow[0]."</p>
                  <p><strong>Date issued: </strong>".$quoteRow[4]."</p>
                </div>";
          }
          ?>
        </div>
        <div class='col-md-6'>
          </form>
          <h4>Add Quotes</h4>
          <form action="../InsertPHP/addQuote.php" method="post" enctype="multipart/form-data">
            <div class='col-md-6'>
              <label>Quote number: </label>
              <input type='text' class='form-control' name='quote_number' id='quote_number'>
            </div>
            <div class='col-md-6'>
              <label>Description: </label>
              <input type='text' class='form-control' name='quoteDescription' id='quoteDescription'>
            </div>
            <div class='col-md-6'>
              <label>Supplier: </label>
                <input type='text' list="suppliers" name="supplierListTwo" id='supplierListTwo' value='' class='col-md-12 form-control'>
                <datalist id="suppliers">
                  <?
                  while($row = mysqli_fetch_array($supplierResult)){
                    echo"<option value='".$row[0]."'></option>";
                  }
                  ?>
                </datalist>
            </div>
            <div class='col-md-6'>
              <label>Quote issued: </label>
              <input type='date' class='form-control' name='quote_date' id='quote_date'>
            </div>
            <?php
            echo "<input type='hidden' id='supplierList' name='supplierList' value='".$supplier_name."'>";
            ?>
            <div class='col-md-6'>
              <label>Select image to upload:</label>
              <!-- hidden type which is used to redirect to the correct view -->
              <input type='hidden' value='orderQuote' id='redirect' name='redirect'>
              <input type='hidden' name='MAX_FILE_SIZE' value='2000000'>
              <input type="file" name="fileToUpload" id="fileToUpload">
            </div>
            <div class='col-md-6'>
              <input type="submit" class='btn btn-primary col-md-12' value="Add quote" name="submit" style='margin-top:25px;'>
            </div>
          </form>
        </div>
    </div>
    <?php
    if(mysqli_num_rows($findActiveRequestsResult) > 0){
      echo"<style>
              ul#activeRequests li {
                  display:inline;
                  font-size: 150%;
              }
            </style>
            <div class='row well'>
            <h4>Active requests with supplier: ".$supplier_name."</h4>
            <ul id='activeRequests'>";
            while($findActiveRequestsRow = mysqli_fetch_array($findActiveRequestsResult)){
              // Find the employee who requested this
              $employeeSql = "SELECT employee_name FROM employee
                              WHERE employee_ID = '$findActiveRequestsRow[3]';";
              $employeeResult = mysqli_query($link, $employeeSql);
              $employeeRow = mysqli_fetch_array($employeeResult);
              echo"<li><a href='#' data-toggle='modal' data-target='#".$findActiveRequestsRow[0]."'> ".$findActiveRequestsRow[0]." </a></li>";
              echo"
                <div class='modal fade' id='".$findActiveRequestsRow[0]."' tabindex='-1' role='dialog' aria-labelledby='".$requestRow[0]."' aria-hidden='true'>
                  <div class='modal-dialog'>
                    <div class='modal-content col-md-12'>
                      <div class='modal-header'>
                        <h4>Request ID: ".$findActiveRequestsRow[0]."</h4>
                      </div>
                      <div class='modal-body col-md-12'>
                        <p><strong>Requested by:</strong> ".$employeeRow[0]."</p>
                        <p><strong>Supplier:</strong> ".$findActiveRequestsRow[1]."</p>
                        <p><strong>Date:</strong> ".$findActiveRequestsRow[2]."</p>
                        <p><strong>Part number:</strong> ".$findActiveRequestsRow[5]."</p>
                        <p><strong>Quantity:</strong> ".$findActiveRequestsRow[6]."</p>
                        <p><strong>Description:</strong> ".$findActiveRequestsRow[4]."</p>
                      </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' onclick='addNewRequest(".$findActiveRequestsRow[0].")'>Use</button>
                        <button type='button' class='btn' data-dismiss='modal'>Close</button>
                      </div>
                    </div>
                  </div>
                </div>";
            }
          echo"</ul></div>";
    } ?>
    <div class='row well well-lg'>
      <h4>Add a new item</h4>
      <form>
        <div class='form-group col-md-6'>
          <label>Quantity: </label>
          <input type='text' id='quantity' class='form-control'>
        </div>
        <div class='form-group col-md-6'>
          <label>Part number: </label>
          <input type='text' id='part_number' class='form-control'>
        </div>

        <div class='form-group col-md-6'>
          <label>Department: </label>
          <select id='department' class='form-control' onchange='updateCostCode()'>
            <option value=''>All departments</option>
            <?php
            while($departmentRow = mysqli_fetch_array($departmentResult)){
              echo "<option value='".$departmentRow[0]."'>".$departmentRow[0]."</option>";
            }?>
          </select>
        </div>
        <div class='form-group col-md-6 result'>
        </div>
        <div class='form-group col-md-6'>
          <label>Unit price: </label>
          <input type='text' id='unit_price' class='form-control'>
        </div>
        <div class='form-group col-md-6'>
          <label>Description: </label>
          <textarea id='description' class='form-control'></textarea>
        </div>
        <div class='form-group col-md-12'>
          <button type='button' class='btn btn-primary col-md-2' onclick='addOrderItem()' style='float:right;'>Add</button>
        </div>
      </form>
    </div>
    <?php
    $totalValueResult = mysqli_query($link, $totalValueSql);
    $totalValue = mysqli_fetch_array($totalValueResult);
    // If the total value of the order is more than $1000 then the order needs to be approved by Lars, Thomas or Becker
    // If the order is being ordered for Lars, Becker or Thomas then this step is not necessary
    if($totalValue[0] > 1000 && $approval_status == '' && $order_for_who != 1 && $order_for_who != 5 && $order_for_who != 8){
      echo "<div class='alert alert-warning fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Purchase orders worth more than $1000 need to be approved</div>
            <div class='row well'>
              <form>
                <div class='col-md-12'>
                  <h4>Ask for approval</h4>
                  <div class='col-md-3'>
                    <select id='approvedBy' class='form-control' style='width:auto;'>
                      <option value='1'>Thomas Schuelke</option>
                      <option value='5'>Lars Haubold</option>
                      <option value='8'>Michael Becker</option>
                      <option value='7'>Freyr Fridfinnsson</option>
                    </select>
                  </div>
                  <div class='col-md-3'>
                    <button class='btn btn-primary' onclick='requestApproval(); return false;'>Send request for approval</button>
                  </div>
                </div>
              </form>
            </div>";
    }
    ?>
    <!-- SelectPHP/showOrderItems -->
    <div id='orderItems'></div>
  </div>
  <script>
  //show the info for the PO chosen when you enter the page or refresh it
      $(document).ready(function() {
          var order_ID = <?php echo $order_ID; ?>;
          showPOInfo(order_ID);
          showOrderItems(order_ID);
          updateCostCode();
      });
      //if the user enters the view with a PO not on the dropdownlist
      // check if the value is in the list already
      var exists = false;
      $('#purchaseOrder option').each(function(){
          if (this.value == '<?php echo $order_ID; ?>') {
              exists = true;
          }
      });
      // if the list doesnt contain our PO we add it to the dropdown
      if(!exists){
          $('#purchaseOrder').append($('<option>', {
              value: <?php echo $order_ID; ?>,
              text: '<?php echo $order_ID; ?>'
          }));
      }
      //make the dropdown list show the currently chosen PO
      $('#purchaseOrder').val("<?php echo $order_ID;?>");
    </script>
</body>
