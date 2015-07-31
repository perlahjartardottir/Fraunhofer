<?php
include '../../connection.php';
session_start();
$sql = "SELECT order_ID
        FROM purchase_order;";
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
        <div class='form-group col-md-3'>
          <label>Purchase order: </label>
          <select class='form-control'>
            <?
            while($row = mysqli_fetch_array($result)){
              var_dump($row[0]);
              echo"<option value='".$row[0]."'>".$row[0]."</option>";
            }
            ?>
          </select>
        </div>
      </form>
    </div>
  </div>
</body>
