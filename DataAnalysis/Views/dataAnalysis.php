<!DOCTYPE html>
<html>
<head>
<?php
include "../../connection.php";
include "../header.php";
session_start();

$securityLevel = $_SESSION["securityLevelDA"];
// iI the user security level is not high enough we kill the page and give him a link to the log in page.
if($securityLevel < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}

// How mamy sets we display in drop downs. 
$_SESSION['numberOfSetsToDisplayInDD'] = 10;

  ?>
<head>
<title>Fraunhofer CCD</title>
</head>
<body>
  <div class='container'>
    <div class='col-md-12'>
        <form class='form-inline pull-xs-right'>
        <input type="text" id='sample_set_name' class="form-control" style='float: right;' placeholder='Quick search...' data-toggle="tooltip" data-placement="bottom" title="Search for sets by name or date." onkeyup='displaySampleResults()'>
      </form>
    </div>

 <div id='sample_results'></div>
</div>
</div>
<script>

  $(document).ready(function(){
    $("#nav_home").button('toggle');
    displaySampleResults();
  })

 $('#sample_set_name').tooltip()

</script>
</body>