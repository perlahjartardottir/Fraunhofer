
<!--                          ATTENTION!                             -->
<!-- If you want to edit the run search table in this view then      -->
<!-- you have to edit the SearchPHP/run_search_suggestions.php file  -->
<!-- The javascript functions are located in js/searchScript.js file -->
<!-- in a function called run_suggestions()                          -->

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

  <script type="text/javascript" src='../js/searchScript.js'></script>
</head>
<body>
  <?php include '../header.php'; ?>
  <script type="text/javascript">
    window.onload = function() {
      run_suggestions();
    };
  </script>
  <div class='container'>
    <!-- The filter for the search -->
    <div class='row well well-lg col-md-3'>
      <form>
        <h4>Enter info to search for a run</h4>
        <!-- <p>Use '_' to represent a single character or use '%' to represent a string of characters</p> -->
        <!-- <p>K215_____1 would for example find all first runs of the day for 2015 on K2</p> -->
        <div class='col-md-12 form-group'>
          <label>Run number:</label>
          <input type="text" name="run_number" id="search_box_run" class='search_box form-control' onkeyup='run_suggestions()'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>AH/Pulses:</label>
          <input type="text" name="ah/pulses" id="search_box_ah" onkeyup='run_suggestions()' class='form-control'/>
        </div>
        <div class='col-md-12 form-group'>
            <label>From:</label>
            </br>
            <input type="date" name="datefirst" id="search_box_date_first" onchange='run_suggestions()' class='form-control'/>
          </div>
          <div class='col-md-12 form-group'>
            <label>To:</label>
            </br>
            <input type="date" name="datelast" id="search_box_date_last" onchange='run_suggestions()' class='form-control'/>
          </div>
        <div class='col-md-12 form-group'>
          <label>Machine: </label>
          </br>
          <select id='machine_select' onchange='run_suggestions()' class='form-control'>
            <option value="">All machines: </option>
            <?php
              $sql = "SELECT machine_ID, machine_acronym
                      FROM machine;";
              $result = mysqli_query($link, $sql);
              if (!$result){
                die("Database query failed: " . mysqli_error($link));
              }
              while($row = mysqli_fetch_array($result)){
                echo '<option value="'.$row['machine_ID'].'">'.$row['machine_acronym'].'</option>';
              }
            ?>
          </select>
        </div>
        <div class='col-md-12 form-group'>
        <label>Coating: </label>
        </br>
          <select id='coating_select' onchange='run_suggestions()' class='form-control'>
            <option value="">All coatings: </option>
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
        <label>Order by: </label>
        <br>
          <select id='order_by_select' onchange='run_suggestions()' class='form-control'>
            <option value='run_date'>Run date</option>
            <option value='run_number'>Run number</option>
            <option value='ah_pulses'>AH/Pulses</option>
            <option value='SUM(lir.number_of_items_in_run)'>Tools in run</option>
            <option value='SUM(lir.number_of_items_in_run * l.price) / COUNT(DISTINCT(r.run_ID))'>Total $ in run</option>
            <option value='SUM(lir.number_of_items_in_run * l.price)/SUM(lir.number_of_items_in_run)'>Average tool $ in run</option>
          </select>
      </div>
        <div class='col-md-12'>
          <label>Show all results:
            <input type='checkbox' id='top_runs' onchange='run_suggestions()'/>
          </label>
        </div>
      </form>
    </div>

    <!-- SearchPHP/run_search_suggestions.php -->
    <div class='col-md-8 col-md-offset-1'>
      <div id='output' class='table table-responsive'>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery.js"></script>

</body>
</html>
