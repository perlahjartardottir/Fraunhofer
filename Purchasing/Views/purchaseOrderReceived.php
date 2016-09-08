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

$order_ID = $_SESSION["order_ID"];

// find the current date
$curDate = date("Y-m-d");
$curDate = strtotime($curDate);

// Query to find all the order items within our purchase order
$sql = "SELECT order_item_ID, quantity, part_number, description, final_inspection
        FROM order_item
        WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);

//Query to find the supplier
$supplierSql = "SELECT supplier_ID, order_final_inspection, expected_delivery_date, order_receive_date
                FROM purchase_order
                WHERE order_ID = '$order_ID';";
$supplierResult = mysqli_query($link, $supplierSql);
$supplierRow = mysqli_fetch_array($supplierResult);

// Find the ratings that are linked to this PO if it has already been rated
$ratingSql = "SELECT rating_timeliness, rating_quality, rating_price, customer_service
              FROM order_rating
              WHERE order_ID = '$order_ID';";
$ratingResult = mysqli_query($link, $ratingSql);
$ratingRow = mysqli_fetch_array($ratingResult);

// Find the difference between current date and expected receiving date
$expectedDate = strtotime($supplierRow[2]);
$dateDiff = $expectedDate - $curDate;
$dateDiffDays = floor($dateDiff/(60*60*24));

$supplierNameSql = "SELECT supplier_name
                    FROM supplier
                    WHERE supplier_ID = '$supplierRow[0]';";
$supplierNameResult = mysqli_query($link, $supplierNameSql);
$supplierNameRow = mysqli_fetch_array($supplierNameResult);

// Query to find how many scans belong to this PO
$scanSql = "SELECT COUNT(scan_ID)
            FROM purchase_scan
            WHERE order_ID = '$order_ID';";
$scanResult = mysqli_query($link, $scanSql);

$numberOfScans = mysqli_fetch_array($scanResult);

$dateOfOrderSql = "SELECT order_date
FROM purchase_order
WHERE order_ID = '$order_ID';";
$dateOfOrder = mysqli_fetch_row(mysqli_query($link, $dateOfOrderSql))[0];
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <script>
		$(document).ready(function() {
      $('input[type=date]').each(function() {
        if  (this.type != 'date' ) $(this).datepicker({
          dateFormat: 'yy-mm-dd'
        });
      });
		});
	</script>
  <div class='container'>
    <div class='row well well-lg'>
      <h3><center>Order received</center></h3>
      <h4>Supplier: <?php echo $supplierNameRow[0]; ?></h4>
      <form>
        <table class='table table-responsive' id='finalInspectionTable'>
          <thead>
            <tr>
              <th>Quantity</th>
              <th>Part number</th>
              <th>Description</th>
              <th>Final inspection</th>
              <th>OK</th>
            </tr>
          </thead>
          <tbody>
            <?php
            while($row = mysqli_fetch_array($result)){
              echo"<tr id='finalInspectionRow'>
                    <input type='hidden' id=order_item_ID value='".$row[0]."'>
                    <td>".$row[1]."</td>
                    <td>".$row[2]."</td>
                    <td>".$row[3]."</td>
                    <td><textarea class='form-control' id='final_inspection'>".$row[4]."</textarea></td>
                    <td><input type='checkbox' id='ok' onchange='updateFinalInspection(\"".$row[4]."\", ".$row[0].", this)'></td>
                  </tr>";
            }
            ?>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <th style='float:right;'>All OK</th>
              <th><input type='checkbox' class='allOk'></th>
            </tr>
          </tbody>
        </table>
        <?php
        if($supplierRow[1] != ''){
          echo "<div class='col-md-6'>
                  <p><strong>Comment: </strong>".$supplierRow[1]."</p>
                </div>";
        } else{
          echo"<div class='col-md-6'></div>";
        }?>
        <div class='col-md-6'>
          <button class='btn btn-primary' style='float:right;' onclick='confirmFinalInspection();return false;'>Confirm Final Inspection Note</button>
        </div>
        <div class='col-md-12' style='margin-top: 40px;'>
        </form>
        <div class='col-md-4'>
          <form action="../InsertPHP/addImage.php" method="post" enctype="multipart/form-data" onsubmit="return checkSize(1000000)">
            <label>Select image to upload:</label>
            <!-- hidden type which is used to redirect to the correct view -->
            <input type='hidden' value='new' id='redirect' name='redirect'>
            <input type="file" name="fileToUpload" id="fileToUpload" accept="image/jpeg">
            <p></p>
            <input type="submit" class='btn btn-primary' value="Upload Image" name="submit">
            <a href='viewAllImages.php' class='btn btn-primary'>View all files <?php if(!empty($numberOfScans[0])){echo "(".$numberOfScans[0].")";} ?></a>
          </form>
        </div>
        <div class='col-md-2'>
          <label>Click to enlarge</label>
          <input type='image' src="../Scan/getImage.php" width="100" height="100" onerror="this.src='../images/noimage.jpg'" onclick="window.open('../Printouts/scanprintout.php')" />
        </div>
      </div>
      <div class='col-md-12'>
        <h4 style='margin-top: 40px;'>Rating (3 is best): </h4>
        <table class='table table-responsive col-md-12'>
          <thead>
            <tr>
              <th>Timeliness</th>
              <th>Quality</th>
              <th>Price</th>
              <th>Customer Service</th>
            </tr>
          </thead>
          <tbody>
            <?php if(mysqli_num_rows($ratingResult) == 0){
              echo"
              <tr>
                <td class='col-md-3'>
                  <select id='rating_timeliness' class='form-control'>
                    <option value='0' "; if($dateDiffDays < 0){echo "selected";} echo">Not on time</option>
                    <option value='2' "; if($dateDiffDays >= 0 || $dateDiffDays == -16682){echo "selected";} echo">On time</option>
                  </select>
                </td>
                <td class='col-md-3'>
                  <select id='rating_quality' class='form-control'>
                    <option value='0'>1</option>
                    <option value='1' selected>2</option>
                    <option value='2'>3</option>
                  </select>
                </td>
                <td class='col-md-3'>
                  <select id='rating_price' class='form-control'>
                    <option value='0'>1</option>
                    <option value='1' selected>2</option>
                    <option value='2'>3</option>
                  </select>
                </td>
                <td class='col-md-3'>
                  <select id='customer_service' class='form-control'>
                    <option value='0'>1</option>
                    <option value='1' selected>2</option>
                    <option value='2'>3</option>
                  </select>
                </td>
              </tr>";
            }else{
              echo"
              <tr>
                <td class='col-md-3'>
                  <select id='rating_timeliness' class='form-control'>
                    <option value='0' "; if($ratingRow[0] == 0){echo "selected";} echo">Not on time</option>
                    <option value='2' "; if($ratingRow[0] == 2){echo "selected";} echo">On time</option>
                  </select>
                </td>
                <td class='col-md-3'>
                  <select id='rating_quality' class='form-control'>
                    <option value='0'"; if($ratingRow[1] == 0){echo "selected";} echo">1</option>
                    <option value='1'"; if($ratingRow[1] == 1){echo "selected";} echo">2</option>
                    <option value='2'"; if($ratingRow[1] == 2){echo "selected";} echo">3</option>
                  </select>
                </td>
                <td class='col-md-3'>
                  <select id='rating_price' class='form-control'>
                    <option value='0'"; if($ratingRow[2] == 0){echo "selected";} echo">1</option>
                    <option value='1'"; if($ratingRow[2] == 1){echo "selected";} echo">2</option>
                    <option value='2'"; if($ratingRow[2] == 2){echo "selected";} echo">3</option>
                  </select>
                </td>
                <td class='col-md-3'>
                  <select id='customer_service' class='form-control'>
                    <option value='0'"; if($ratingRow[3] == 0){echo "selected";} echo">1</option>
                    <option value='1'"; if($ratingRow[3] == 1){echo "selected";} echo">2</option>
                    <option value='2'"; if($ratingRow[3] == 2){echo "selected";} echo">3</option>
                  </select>
                </td>
              </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
      <form>
        <div class='col-md-12' style='margin-top:30px;'>
          <button class='btn btn-primary' style='float:right;' onclick='packageReceived(<?php echo $order_ID;?>, this)'>Order Received</button>
          <input type='date' id='receiveDate' min='<?php echo $dateOfOrder; ?>'class='form-control' style='float:right; margin-right:5px; width:auto;'>
        </div>
      </form>
    </div>
  </div>
</body>
<script type="text/javascript">
$('.allOk').click(function() {
  if ($(this).is(':checked')) {
      $('div input').prop('checked', true);
  } else {
      $('div input').prop('checked', false);
  }
});

</script>
