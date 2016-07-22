<?php
include '../../connection.php';
session_start();
$securityLevel = $_SESSION["securityLevelDA"];

if($securityLevel< 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}

?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well well-lg col-md-3'>
      <form>
        <h4 class='custom_heading'>Search by:</h4>
        <div class='col-md-12 form-group'>
          <label>Sample Name: </label>
          <input type="text" id='sample_name' class='form-control' onkeyup='displaySearchResults()' />
        </div>
        <div class='col-md-12 form-group'>
          <label>Coating: </label>
          <input type="text" id='' class='form-control' onkeyup='displaySearchResults()' />
        </div>
        <div class='col-md-12 form-group'>
          <label id='search_thickness_label' class='col-md-12'>Thickness: </label>
          <input type="text" id='begin_thickness' class='col-md-6' placeholder='Min' onkeyup='displaySearchResults()' />
          <input type="text" id='end_thickness' class='col-md-6' placeholder='Max' onkeyup='displaySearchResults()' />
        </div>
        <div class='col-md-12 form-group'>
          <label>Sample date from:</label>
          <input type="date" id='begin_date' class='form-control' onchange='displaySearchResults()'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Sample date to:</label>
          <input type="date" id='end_date' class='form-control' onchange='displaySearchResults()'/>
        </div>
      </form>
    </div>

    <!-- SearchPHP/searchResults.php -->
    <div class="col-md-8 col-md-offset-1">
      <div id='search_results' class='table table-responsive'>
      </div>
    </div>
  </div>
    <script type="text/javascript">
    $(document).ready(function(){
      displaySearchResults();
      $('input[type=date]').each(function() {
        if  (this.type != 'date' ) $(this).datepicker({
          dateFormat: 'yy-mm-dd'
        });
      });
    });
  </script>
</body>