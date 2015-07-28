
<!--                          ATTENTION                              -->
<!-- If you want to edit the PO search table in this view then       -->
<!-- you have to edit the SearchPHP/search_suggestions.php file      -->
<!-- The javascript functions are located in js/searchScript.js file -->
<!-- in a function called suggestions()                              -->

<!DOCTYPE html>
<?php
include '../connection.php';
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
?>
<html>
<head>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
  <?php include '../header.php'; ?>
  <script type="text/javascript">
    window.onload = function() {
      suggestions();
    };
  </script>
  <div class='container'>
    <!-- The filter for the search -->
    <div class='row well well-lg col-md-3'>
      <form>
        <h4>Enter info to search for a PO</h4>
        <div class='col-md-12 form-group'>
          <label >Input the PO number</label>
          <input type="text" name="po_number" id="search_box_PO" class='search_box form-control' onkeyup='suggestions()'/>
        </div>

        <div class='col-md-12 form-group'>
          <label>Receiving date from:</label>
          <input type="date" name="datefirst" id="search_box_date_first" onchange='suggestions()' class='form-control'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Receiving date to:</label>
          <input type="date" name="datelast" id="search_box_date_last" onchange='suggestions()' class='form-control'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Customer: </label>
          <select id='customer_select' onchange='suggestions()' class='form-control'>
            <option value="">All customers: </option>
            <?php
              $sql = "SELECT customer_ID, customer_name
                      FROM customer;";
              $result = mysqli_query($link, $sql);
              if (!$result){
                die("Database query failed: " . mysqli_error($link));
              }
              while($row = mysqli_fetch_array($result)){
                echo '<option value="'.$row['customer_ID'].'">'.$row['customer_name'].'</option>';
              }
            ?>
          </select>
        </div>
          <div class='col-md-12 form-group'>
          <label>Order by: </label>
          <br>
            <select id='order_by_select' onchange='suggestions()' class='form-control'>
              <option value='receiving_date'>Receiving date</option>
              <option value='shipping_date'>Shipping date</option>
              <option value='SUM(l.price * l.quantity)'>Final price</option>
              <option value='SUM(l.quantity)'>Number of tools</option>
              <option value='SUM(l.quantity * l.price) / SUM(l.quantity)'>Average tool price</option>
            </select>
        </div>
        <div class='col-md-12 form-inline'>
          <label>Show all results:
            <input type='checkbox' id='top_100' onchange='suggestions()'/>
          </label>
        </div>
      </form>
    </div>

    <!-- SearchPHP/search_suggestions.php -->
    <div class="col-md-8 col-md-offset-1">
      <div id='output' class='table table-responsive'>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery.js"></script>

</body>
</html>
