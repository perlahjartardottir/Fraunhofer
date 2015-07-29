<?php session_start(); ?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well well-lg'>
      <form>
        <h4>Purchase order</h4>
        <p class='col-md-6 form-group'>
          <label>Employee: </label>
          <input type="text" class='form-control'>
        </p>
        <p class='col-md-6 form-group'>
          <label>Supplier: </label>
          <input type="text" class='form-control'>
        </p>
        <p class='col-md-6 form-group'>
          <label>Quantity: </label>
          <input type="text" class='form-control'>
        </p>
        <p class='col-md-6 form-group'>
          <label>Approved by: </label>
          <input type="date" class='form-control'>
        </p>
        <p class='col-md-6 form-group'>
          <label>Description: </label>
          <textarea class='form-control'></textarea>
        </p>
        <input class='form-control btn btn-primary' type="button" value="Order" onclick='order()'>
      </form>
    </div>
  </div>
</body>
