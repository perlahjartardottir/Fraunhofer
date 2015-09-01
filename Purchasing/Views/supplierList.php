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
      supplierSuggestions();
    };
    //dismiss popover when click on body
    $('body').on('click', function (e) {
    if ($(e.target).data('toggle') !== 'popover' && $(e.target).parents('.popover.in').length === 0) {
        $('[data-toggle="popover"]').popover('hide');
    }
});
  </script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <div class='container'>
    <div class='row well well-lg col-md-3'>
      <form>
        <h4>Enter info to search for a supplier</h4>
        <div class='col-md-12 form-group'>
          <label>Supplier name: </label>
          <input type="text" id='supplier_name' class='form-control' onkeyup='supplierSuggestions()'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Supplier contact: </label>
          <input type="text" id='supplier_contact' class='form-control' onkeyup='supplierSuggestions()'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Supplier phone: </label>
          <input type="text" id='supplier_phone' class='form-control' onkeyup='supplierSuggestions()'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Supplier email: </label>
          <input type="text" id='supplier_email' class='form-control' onkeyup='supplierSuggestions()'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Supplier address: </label>
          <input type="text" id='supplier_address' class='form-control' onkeyup='supplierSuggestions()'/>
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
