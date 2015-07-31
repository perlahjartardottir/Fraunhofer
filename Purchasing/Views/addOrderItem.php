<?php
include '../../connection.php';
session_start();
$sql = "SELECT order_ID
        FROM purchase_order
        ORDER BY order_date DESC
        LIMIT 12;";
$result = mysqli_query($link, $sql);
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well well-lg'>
      <form>
        <div class='form-group'>
          <label>Purchase order: </label>
          <select class='form-control' onchange='showToolsAndRefreshImage(this.value)' id='purchaseOrder' style='width:auto;'>
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
  </div>
</body>
