<?php
include '../../connection.php';
session_start();
$sql = "SELECT order_ID
        FROM purchase_order
        ORDER BY order_ID DESC
        LIMIT 12;";
$result = mysqli_query($link, $sql);
$order_ID = $_SESSION["order_ID"];
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <?php var_dump($order_ID); ?>
    <div class='row well well-lg'>
      <form>
        <div class='form-group'>
          <label>Purchase order: </label>
          <select class='form-control' onchange='showPOInfoAndRefreshImage(this.value)' id='purchaseOrder' style='width:auto;'>
            <option value''>Select a PO#: </option>
            <?
            while($row = mysqli_fetch_array($result)){
              var_dump($row[0]);
              echo"<option value='".$row[0]."'>".$row[0]."</option>";
            }
            ?>
          </select>
          <br><div id="poinfo"><b>PO info will be listed here</b></div>
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
        <button type='button' class='btn btn-primary col-md-2' style='float:right;'>Add</button>
      </form>
    </div>
  </div>
  <script>
  //show the info for the PO chosen when you enter the page or refresh it
      $(document).ready(function() {
          var order_ID = <?php echo $order_ID; ?>;
          showPOInfo(order_ID);
      });
      //if the user enters the view with a PO not on the dropdownlist
      // check if the value is in the list already
      // var exists = false;
      // $('#posel option').each(function(){
      //     if (this.value == '<?php echo $po_ID; ?>') {
      //         exists = true;
      //     }
      // });
      // // if the list doesnt contain our PO we add it to the dropdown
      // if(!exists){
      //     $('#posel').append($('<option>', {
      //         value: <?php echo $po_ID; ?>,
      //         text: '<?php echo $po_number[0]; ?>'
      //     }));
      // }
      //make the dropdown list show the currently chosen PO
      $('#purchaseOrder').val("<?php echo $order_ID;?>");
    </script>
</body>
