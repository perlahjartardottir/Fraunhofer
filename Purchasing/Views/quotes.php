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
          <label>Purchase number: </label>
          <input type="text" id='order_name' onkeyup='quoteSuggestions()' class='form-control'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Supplier: </label>
          <input type="text" id='supplier_name' onkeyup='quoteSuggestions()' class='form-control'/>
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

    <!-- SearchPHP/purchase_search_suggestions.php -->
    <div class="col-md-8 col-md-offset-1">
      <p>Select checkboxes and then click "create request" to create a request with all checked quotes</p>
      <div id='output' class='table table-responsive'>
      </div>
    </div>

  </div>
</body>
