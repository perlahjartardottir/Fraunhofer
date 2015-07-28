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
if($user_sec_lvl < 4){
  echo "<a href='../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}
?>
<html>
<head>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well well-lg col-md-12'>
      <h3>Customer overview</h3>
      <p>The data available from this view is </p>
      <ul>
        <li>The number of POs</li>
        <li>Average price for POs</li>
        <li>Average turnaround time without weekends</li>
        <li>The total number of tools</li>
        <li>Average price for tools</li>
        <li>Sum of revenue</li>
      </ul>
      <p>The form on the left is used to filter the results.</p>
      <p>This data can be copied to an excel sheet.</p>
    </div>
    <div class='row well well-lg col-md-3'>
      <form>
        <div class='col-md-12 form-group'>
          <label>Customer: </label>
          <select id='customer_select' class='form-control'>
            <option value='customer'>All customers</option>
            <option value=''>All customers overall</option>
            <?php
            $sql = "SELECT customer_ID, customer_name
                    FROM customer;";
            $result = mysqli_query($link, $sql);
            if (!$result)
            {
              die("Database query failed: " . mysqli_error($link));
            }
            while($row = mysqli_fetch_array($result))
            {
              echo '<option value="'.$row['customer_ID'].'">'.$row['customer_name'].'</option>';
            }
            ?>
          </select>
        </div>
        <div class='col-md-12 form-group'>
          <label>Time interval: </label>
          <select id='group_by_select' class='form-control'>
            <option value="Month">Month</option>
            <option value="Year">Year</option>
            <option value="Week">Week</option>
          </select>
        </div>
        <div class='col-md-12 form-group'>
          <label>Show corrected pricing
            <input type='checkbox' id='show_discount' checked/>
          </label>
        </div>
        <div class='col-md-12 form-group'>
          <label>From:</label>
          <input type="date" name="date_from" id="date_from" class='form-control'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>To:</label>
          <input type="date" name="date_to" id="date_to" class='form-control'/>
        </div>
        <div class='col-md-12 form-group'>
          <input type='button' class='btn btn-success form-control' onclick='applyFilter();' value='Apply'/>
        </div>
      </form>
    </div>
    <div class='row well well-lg col-md-8 col-md-offset-1'>
      <!-- reportGenerate.php -->
      <div id='output'>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery.js"></script>
  <script>
    $(document).ready(function(){
        applyFilter();
    });
  </script>

</body>
</html>
