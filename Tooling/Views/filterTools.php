
<!--                          ATTENTION!                             -->
<!-- If you want to edit the tool search table in this view then     -->
<!-- you have to edit the SearchPHP/tool_search_suggestions.php file -->
<!-- The javascript functions are located in js/searchScript.js file -->
<!-- in a function called tool_suggestions()                         -->

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
      tool_suggestions();
    };
  </script>
  <div class='container'>
    <!-- The filter for the search -->
    <div class='row well well-lg col-md-3'>
      <form>
        <h4>Enter info to search for a tool</h4>
        <!-- <p>Use '_' to represent a single character or use '%' to represent a string of characters</p> -->
        <!-- <p>K215_____1 would for example find all first runs of the day for 2015 on K2</p> -->
        <div class='col-md-12 form-group'>
          <label>Tool ID:</label>
          <input type="text" name="tool_ID" id="tool_ID" class='search_box form-control' onkeyup='tool_suggestions()'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>From:</label>
          <input type="date" name="datefirst" id="search_box_date_first" onchange='tool_suggestions()' class='form-control'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>To:</label>
          <input type="date" name="datelast" id="search_box_date_last" onchange='tool_suggestions()' class='form-control'/>
        </div>
        <div class='col-md-12'>
          <label>Show all results:
            <input type='checkbox' id='top_runs' onchange='tool_suggestions()'/>
          </label>
        </div>
      </form>
    </div>

    <!-- SearchPHP/tool_search_suggestions.php -->
    <div class='col-md-8 col-md-offset-1'>
      <table id='output' class='table table-responsive'>
      </table>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery.js"></script>

</body>
</html>
