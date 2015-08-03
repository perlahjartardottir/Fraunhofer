<?php
include '../../connection.php';
session_start();
$sql = "SELECT order_ID
        FROM purchase_order
        ORDER BY order_ID DESC
        LIMIT 10;";
$result = mysqli_query($link, $sql);
$order_ID = $_SESSION["order_ID"];
$getRequestSql = "SELECT request_ID
                  FROM purchase_order
                  WHERE order_ID = '$order_ID';";
$getRequestResult = mysqli_query($link, $getRequestSql);
$row = mysqli_fetch_array($getRequestResult);
$request_ID = $row[0];
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
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
          <br><div id="poinfo"><b>PO info will be listed here</b></div>
        </div>
        <div class='form-group col-md-6'>
          <?php
          $requestSql = "SELECT request_description
                         FROM order_request
                         WHERE request_ID = '$request_ID';";
          $requestResult = mysqli_query($link, $requestSql);
          $requestRow = mysqli_fetch_array($requestResult);
          if($requestRow > 0){
            echo"<h5>Request ID: ".$request_ID."</h5>
                 <p><b>Description:</b> ".$requestRow[0]."</p>";
          }
          ?>
        </div>
      </form>
    </div>
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
          <label>Unit price: </label>
          <input type='text' id='unit_price' class='form-control'>
        </div>
        <div class='form-group col-md-6'>
          <label>Description: </label>
          <textarea id='description' class='form-control'></textarea>
        </div>
        <button type='button' class='btn btn-primary col-md-2' onclick='addOrderItem()' style='float:right;'>Add</button>
      </form>
    </div>
    <!-- SelectPHP/showOrderItems -->
    <div id='orderItems'</div>
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
