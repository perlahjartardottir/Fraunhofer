<?php
include '../../connection.php';
session_start();
$order_ID = $_SESSION["order_ID"];

// Query to find all the order items within our purchase order
$sql = "SELECT order_item_ID, quantity, part_number, description, final_inspection
        FROM order_item
        WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);

//Query to find the supplier
$supplierSql = "SELECT supplier_ID
                FROM purchase_order
                WHERE order_ID = '$order_ID';";
$supplierResult = mysqli_query($link, $supplierSql);
$supplierRow = mysqli_fetch_array($supplierResult);
$supplierNameSql = "SELECT supplier_name
                    FROM supplier
                    WHERE supplier_ID = '$supplierRow[0]';";
$supplierNameResult = mysqli_query($link, $supplierNameSql);
$supplierNameRow = mysqli_fetch_array($supplierNameResult);
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well well-lg'>
      <h3><center>Package received</center></h3>
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
        <div class='col-md-12'>
          <button class='btn btn-primary' style='float:right;' onclick='confirmFinalInspection();return false;'>Confirm Final Inspection Note</button>
        </div>
        <h4>Rating</h4>
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
      </form>
      <button class='btn btn-primary' style='float:right;'>Package Received</button>
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
