<?php session_start(); ?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <script type="text/javascript">
    window.onload = function() {
      orderItemSuggestions();
    };
  </script>
  <div class='container'>
    <div class='row well well-lg col-md-3'>
      <form>
        <h4>Enter info to search for an order item</h4>
        <div class='col-md-12 form-group'>
          <label>Part number: </label>
          <input type="text" id='part_number' onkeyup='orderItemSuggestions()' class='form-control'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Description: </label>
          <input type="text" id='description' onkeyup='orderItemSuggestions()' class='form-control'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Department: </label>
          <select class='form-control' id='department' onchange='orderItemSuggestions()'>
            <option selected value=''>All departments</option>
            <option value='PVD'>PVD</option>
            <option value='CVD'>CVD</option>
          </select>
        </div>
        <div class='col-md-12 form-group'>
          <label>Order date from:</label>
          <input type="date" id='first_date' class='form-control' onchange='orderItemSuggestions()'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Order date to:</label>
          <input type="date" id='last_date' class='form-control' onchange='orderItemSuggestions()'/>
        </div>
      </form>
    </div>

    <!-- SearchPHP/order_item_search_suggestions.php -->
    <div class="col-md-8 col-md-offset-1">
      <div id='output' class='table table-responsive'>
      </div>
    </div>
  </div>
</body>
