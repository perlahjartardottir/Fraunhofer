<?php session_start(); ?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <script type="text/javascript">
    window.onload = function() {
      overview();
    };
  </script>
  <div class='container'>
    <div class='row well well-lg col-md-3'>
      <form>
        <div class='col-md-12 form-group'>
          <label>Department: </label>
          <select class='form-control' id='department' onchange='overview()'>
            <option selected value='department'>All departments</option>
            <option value=''>All departments overall</option>
            <option value='PVD'>PVD</option>
            <option value='CVD'>CVD</option>
          </select>
        </div>
        <div class='col-md-12 form-group'>
          <label>Time interval: </label>
          <select id='group_by_select' class='form-control' onchange='overview()'>
            <option value="Month">Month</option>
            <option value="Year">Year</option>
            <option value="Week">Week</option>
          </select>
        </div>
        <div class='col-md-12 form-group'>
          <label>From:</label>
          <input type="date" name="date_from" id="date_from" class='form-control' onchange='overview()'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>To:</label>
          <input type="date" name="date_to" id="date_to" class='form-control' onchange='overview()'/>
        </div>
      </form>
    </div>

    <!-- SearchPHP/overview.php -->
    <div class="col-md-8 col-md-offset-1">
      <div id='output'>
      </div>
    </div>
  </div>
</body>
