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
      <h3>Coating overview</h3>
      <p>The data available from this view is </p>
      <ul>
        <li>The total number of runs</li>
        <li>The total number of tools</li>
        <li>Average price for tools</li>
        <li>Average time to run without weekends</li>
        <li>Sum of revenue</li>
      </ul>
      <p>The form on the left is used to filter the results.</p>
      <p>This data can be copied to an excel sheet.</p>
    </div>
    <div class='row well well-lg col-md-3'>
      <form>
        <div class='col-md-12 form-group'>
          <label>Coating: </label>
          <select id='coating_select' class='form-control'>
            <option value='coating'>All coatings</option>
            <option value=''>All coatings overall</option>
            <?php
            $sql = "SELECT coating_ID, coating_type
                    FROM coating;";
            $result = mysqli_query($link, $sql);
            if (!$result)
            {
              die("Database query failed: " . mysqli_error($link));
            }
            while($row = mysqli_fetch_array($result))
            {
              echo '<option value="'.$row['coating_ID'].'">'.$row['coating_type'].'</option>';
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
          <input type='button' class='btn btn-success form-control' onclick='applyCoatingFilter();' value='Apply'/>
        </div>
      </form>
    </div>
    <div class='row well well-lg col-md-8 col-md-offset-1'>
      <!-- reportGenerate.php -->
      <div id='output'>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function(){
        applyCoatingFilter();
        $('input[type=date]').each(function() {
          if  (this.type != 'date' ) $(this).datepicker({
            dateFormat: 'yy-mm-dd'
          });
        });
    });
  </script>

</body>
</html>
