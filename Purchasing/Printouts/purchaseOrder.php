<!DOCTYPE html>
<?php
include '../../connection.php';
session_start();
//find the current user
$user = $_SESSION["username"];
$order_ID = $_SESSION["order_ID"];

$orderSql = "SELECT supplier_ID
             FROM purchase_order
             WHERE order_ID = '$order_ID';";
$orderResult = mysqli_query($link, $orderSql);
while($row = mysqli_fetch_array($orderResult)){
  $supplier_ID = $row[0];
}

$supplierSql = "SELECT supplier_name, supplier_address, supplier_phone, supplier_email
                FROM supplier
                WHERE supplier_ID = '$supplier_ID';";
$supplierResult = mysqli_query($link, $supplierSql);
$supplierRow = mysqli_fetch_array($supplierResult);

?>
<html>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well well-lg'>
      <form>
        <h4>Select a purchase order</h4>
        <select class='form-control' onchange='showPOInfoAndRefreshImage(this.value)' id='purchaseOrder' style='width:auto;'>
          <option value=''>Select a PO#: </option>
          <?php
          $sql = "SELECT order_ID
                  FROM purchase_order
                  ORDER BY order_ID DESC
                  LIMIT 10;";
          $result = mysqli_query($link, $sql);
          while($row = mysqli_fetch_array($result)){
            echo"<option value='".$row[0]."'>".$row[0]."</option>";
          }
          ?>
        </select>
      </form>
    </div>
    <div class='col-xs-12'>
      <img src="../images/fraunhoferlogo.jpg" alt="Fraunhofer Logo" style="float:right; width:220px; height:auto; margin-top:10px;"/>
    </div>
    <div class='col-xs-8'>
      <h4>Purchase Order</h4>
      <span class='col-xs-12'><strong>To supplier: </strong></span>
      <span class='col-xs-12'><strong><?php echo $supplierRow[0]; ?></strong></span>
      <span class='col-xs-12'><?php echo $supplierRow[1]; ?></span>
      <span class='col-xs-12'>Phone: <?php echo $supplierRow[2]; ?></span>
      <p class='col-xs-12'>Email: <?php echo $supplierRow[3]; ?></p>
      <span class='col-xs-12'><strong>Purchase Order Number: <?php echo $order_ID; ?></strong></span>
      <p class='col-xs-12'><i>Please refer to the purchase order number on all invoices</i></p>
      <p class='col-xs-12'>Date: </p> <!--SPYRJA HVADA DATE UM HER ER RAETT -->
    </div>
    <div class='col-xs-4'>
      <span class='col-xs-12'><strong>Shipping & Billing:</strong></span>
      <span class='col-xs-12'>Fraunhofer USA CCD</span>
      <span class='col-xs-12'>1449 Engineering Research Court</span>
      <p class='col-xs-12'>East Lansing, MI 48824</p>
      <p class='col-xs-12'><i>Please send invoices to the above address or email to kklimczak@fraunhofer.org</i></p>
      <span class='col-xs-12'><strong>Technical Contact:</strong></span>
      <span class='col-xs-12'><strong>Kathryn Klimczak</strong></span>
      <span class='col-xs-12'>+1 517-432-8709  (phone)</span>
      <span class='col-xs-12'>+1 517-432-8167  (fax)</span>
      <p class='col-xs-12'>kklimczak@fraunhofer.org</p>
      <span class='col-xs-12'><strong>Accounts Payable Contact:</strong></span>
      <span class='col-xs-12'>Beth Fohrman</span>
      <span class='col-xs-12'>+1 734-738-0556</span>
      <span class='col-xs-12'>bfohrman@fraunhofer.org</span>
    </div>
    <div class='col-md-12' style='margin-top:20px;'>
      <table class='table table-responsive'>
        <thead>
          <tr>
            <th>Pos. No.</th>
            <th>Quantity.</th>
            <th>Part #</th>
            <th>Description</th>
            <th>USD Unit</th>
            <th>USD Total</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
  <script>
//show the info for the PO chosen when you enter the page or refresh it
    $(document).ready(function() {
        var order_ID = <?php echo $order_ID; ?>;
        showPOInfo(order_ID);
        showOrderItems(order_ID);
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
</html>
