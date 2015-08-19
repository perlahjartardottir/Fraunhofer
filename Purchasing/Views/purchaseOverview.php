<?php session_start(); ?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <script type="text/javascript">
    window.onload = function() {
      purchaseSuggestions();
    };
  </script>
  <div class='container'>
    <div class='row well well-lg col-md-3'>
      <form>
        <h4>Enter info to search for a purchase order</h4>
        <div class='col-md-12 form-group'>
          <label>Purchase number: </label>
          <input type="text" id='order_name' onkeyup='purchaseSuggestions()' class='form-control'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Order date from:</label>
          <input type="date" id='first_date' class='form-control' onchange='purchaseSuggestions()'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Order date to:</label>
          <input type="date" id='last_date' class='form-control' onchange='purchaseSuggestions()'/>
        </div>
        <div class='col-md-12 form-inline'>
          <label>Not received:
            <input type='checkbox' id='notReceived' onchange='purchaseSuggestions()'/>
          </label>
        </div>
        <div class='col-md-12 form-inline'>
          <label>Missing final inspection:
            <input type='checkbox' id='noFinalInspection' onchange='purchaseSuggestions()'/>
          </label>
        </div>
      </form>
    </div>

    <!-- SearchPHP/purchase_search_suggestions.php -->
    <div class="col-md-8 col-md-offset-1">
      <div id='output' class='table table-responsive'>
      </div>
    </div>
  </div>
</body>
