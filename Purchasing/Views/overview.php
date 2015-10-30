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
if($user_sec_lvl < 4){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site. ".$user. " ".$user_sec_lvl);
}
$departmentSql = "SELECT department_name
                  FROM department;";
$departmentResult = mysqli_query($link, $departmentSql);
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <script type="text/javascript">
    window.onload = function() {
      overview();
      updateCostCode();
			$('input[type=date]').each(function() {
        if  (this.type != 'date' ) $(this).datepicker();
      });
    };
  </script>
  <div class='container'>
    <div class='row well well-lg col-md-3'>
      <form>
        <div class='col-md-12 form-group'>
          <label>Department: </label>
          <select class='form-control' id='department' onchange='updateCostCode(); overview();'>
            <option selected value='department'>All departments</option>
            <option value='costCode'>All cost codes</option>
            <option value=''>All departments overall</option>
            <?php
            while($departmentRow = mysqli_fetch_array($departmentResult)){
              echo "<option value='".$departmentRow[0]."'>".$departmentRow[0]."</option>";
            }?>
          </select>
        </div>
        <div class='form-group col-md-12 result'>
        </div>
        <div class='col-md-12 form-group'>
          <label>Time interval: </label>
          <select id='group_by_select' class='form-control' onchange='overview()'>
            <option value="Month">Month</option>
            <option value="Year">Year</option>
            <option value="Week">Week</option>
          </select>
        </div>
        <div class='col-md-12 form-group'>
          <label>Order date from:</label>
          <input type="date" name="date_from" id="date_from" class='form-control' onchange='overview()'/>
        </div>
        <div class='col-md-12 form-group'>
          <label>Order date to:</label>
          <input type="date" name="date_to" id="date_to" class='form-control' onchange='overview()'/>
        </div>
      </form>
    </div>

    <!-- SearchPHP/overview.php -->
    <div class="col-md-8 col-md-offset-1">
      <div id='output'>
      </div>
    </div>
  </div>
</body>
