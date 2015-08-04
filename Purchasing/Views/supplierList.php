<?php session_start(); ?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <script type="text/javascript">
    window.onload = function() {
      supplierSuggestions();
    };
    $(function () {
      $('[data-toggle="popover"]').popover()
    })
  </script>
  <div class='container'>
    <div class='row well well-lg col-md-3'>
      <form>
        <h4>Enter info to search for a supplier</h4>
        <div class='col-md-12 form-group'>
          <label>Supplier name: </label>
          <input type="text" id='supplier_name'/>
        </div>
        <div class='col-md-12 form-group'>
          <a href='../Views/addSupplier.php' type='button' class='form-group btn btn-primary'>Add a new supplier</a>
        </div>
      </form>
    </div>

    <!-- SearchPHP/supplier_search_suggestions.php -->
    <div class="col-md-8 col-md-offset-1">
      <div id='output' class='table table-responsive'>
      </div>
    </div>
  </div>
</body>
