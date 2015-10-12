
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
$user_sec_lvl = str_split($user_sec_lvl);
$user_sec_lvl = $user_sec_lvl[0];
if($user_sec_lvl < 4){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}
?>
<html>
<head>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
  <?php include '../header.php'; ?>
</head>
<body>
  <script type="text/javascript">
    window.onload = function() {
      oldPOsTable();
    };
  </script>
  <!-- SearchPHP/oldPOsTable.php -->
  <div class='col-md-10 col-md-offset-1'>
    <table id='output' class='table table-responsive' style='width: 95%'>
    </table>
  </div>
  <form>
    <div class='col-md-10 col-md-offset-1'>
    <input type='button' class='btn btn-danger form-control' style='width:95%' value='Delete all old POs' onclick='deleteAllOldPOs()'>
  </form>
  <div id='deleteScript'></div>
  <script type="text/javascript">
    function deleteAllOldPOs(){
      var r = confirm("Are you sure you want to delete all these POs? \n All info will be lost");
      if (r === true){
        $.ajax({
          url: "../DeletePHP/delAllOldPOs.php",
          type: "POST",
          data: {
          },
          success: function(data, status, xhr) {
            // This refreshes the page after the delete
            $("#deleteScript").html(data);
            window.location.reload();
          }
        });
      }
    }
  </script>
</body>
</html>
