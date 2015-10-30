<?php
include '../../connection.php';
session_start();
//find the current user
$user = $_SESSION["username"];
//find his level of security
$secsql = "SELECT security_level
           FROM employee
           WHERE employee_name = '$user'";
$secResult = mysqli_query($link, $secsql);

// Query for supplier list
$supplierSql = "SELECT supplier_name
                FROM supplier;";
$supplierResult = mysqli_query($link, $supplierSql);

while($row = mysqli_fetch_array($secResult)){
  $user_sec_lvl = $row[0];
}
$user_sec_lvl = str_split($user_sec_lvl);
$user_sec_lvl = $user_sec_lvl[1];
// if the user security level is not high enough we kill the page and give him a link to the log in page
if($user_sec_lvl < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <script type="text/javascript">
    window.onload = function() {
      quoteSuggestions();
      $('input[type=date]').each(function() {
        if  (this.type != 'date' ) $(this).datepicker();
      });
    };
  </script>
  <div class='container'>
    <div class='row well well-lg col-md-3'>
      <form>
        <h4>Enter info to search for a purchase order</h4>
        <div class='col-md-12 form-group'>
          <label>Quote number: </label>
          <input type="text" id='quote_number' onkeyup='quoteSuggestions()' class='form-control'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Supplier: </label>
          <input type="text" id='supplier_name' onkeyup='quoteSuggestions()' class='form-control'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Description: </label>
          <input type="text" id='quote_description' onkeyup='quoteSuggestions()' class='form-control'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Purchase number: </label>
          <input type="text" id='order_name' onkeyup='quoteSuggestions()' class='form-control'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Quote issue date from:</label>
          <input type="date" id='first_date' class='form-control' onchange='quoteSuggestions()'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Quote issue date to:</label>
          <input type="date" id='last_date' class='form-control' onchange='quoteSuggestions()'/>
        </div>
      </form>
    </div>

    <!-- SearchPHP/quote_suggestions.php -->
    <div class="col-md-8 col-md-offset-1">
      <span>This table contains information about all quotes in the system.</span>
      <span><br>Select received quotes for PO request. Click "create request" to create a new request based on checked quotes.</span>
      <p>All quotes will be included in request, the final quote will be linked to the PO</p>
      <div id='output' class='table table-responsive'>
      </div>
    </div>

  </div>
</body>
