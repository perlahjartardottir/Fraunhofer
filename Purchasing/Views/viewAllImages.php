<?php
include '../../connection.php';
session_start();
mysql_set_charset('utf8');
header('Content-Type: text/html; charset=utf-8');
$order_ID = $_SESSION["order_ID"];

$sql = "SELECT scan_ID, scan_image
        FROM purchase_scan
        WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);

$orderSql = "SELECT quantity, part_number, description, final_inspection
             FROM order_item
             WHERE order_ID = '$order_ID';";
$orderResult = mysqli_query($link, $orderSql);

?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well'>
      <div class='col-md-4'>
        <h3>All Scans</h3>
        <table class='table table-responsive'>
          <tbody>
            <?php
            if(mysqli_num_rows($result) == 0){
              echo"<tr><td><img src='../images/noimage.jpg' width='100' height='100'></td></tr>";
            }
            while($row = mysqli_fetch_array($result)){
              echo"<tr>
                    <td><img src='../Scan/getImage.php?id=".$row[0]."' width='100' height='100'></td>
                    <td><button class='btn btn-danger' style='margin-top:35px;' onclick='deletePurchaseScan(".$row[0].")'>Delete</button></td>
                  </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class='col-md-7 col-md-offset-1'>
        <h3>Purchase Order: <?php echo $order_ID; ?></h3>
        <table class='table table-responsive'>
          <thead>
            <tr>
              <th>Quantity</th>
              <th>Part number</th>
              <th>Description</th>
              <th>Final inspection</th>
            </tr>
          </thead>
          <tbody>
            <?php
            while($orderRow = mysqli_fetch_array($orderResult)){
              echo"<tr>
                    <td>".$orderRow[0]."</td>
                    <td>".$orderRow[1]."</td>
                    <td>".$orderRow[2]."</td>
                    <td>".$orderRow[3]."</td>
                  </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class='col-md-12'>
        <div class='col-md-6'>
          <form action="../InsertPHP/addImage.php" method="post" enctype="multipart/form-data" onsubmit="return checkSize(356000)">
            <div class='col-md-6'>
              <label>Select image to upload:</label>
              <!-- hidden type which is used to redirect to the correct view -->
              <input type='hidden' value='allScans' id='redirect' name='redirect'>
              <input type="file" name="fileToUpload" id="fileToUpload" accept="image/jpeg">
            </div>
            <div class='col-md-6'>
              <input type="submit" class='btn btn-primary' value="Upload Image" name="submit">
            </div>
          </form>
        </div>
        <a href='purchaseOrderReceived.php' class='btn btn-primary' style='float:right; margin-top:15px;'>Back to purchase order</a>
      </div>
    </div>
  </div>
</body>
