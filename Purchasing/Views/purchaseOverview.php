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
          <label>Supplier: </label>
          <input type="text" id='supplier_name' onkeyup='purchaseSuggestions()' class='form-control'/>
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
      </form>
    </div>

    <!-- SearchPHP/purchase_search_suggestions.php -->
    <div class="col-md-8 col-md-offset-1">
      <div id='output' class='table table-responsive'>
      </div>
    </div>
  </div>
</body>
