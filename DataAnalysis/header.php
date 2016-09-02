<!-- The header file for all views. -->
<!-- Includes all the .css and .js needed.  -->

<?php
include '../connection.php';
session_start();
// Find the current user.
$user = $_SESSION["username"];

// Find his level of security.
$secSql = "SELECT security_level
FROM employee
WHERE employee_name = '$user'";
$secResult = mysqli_query($link, $secSql);

while($row = mysqli_fetch_array($secResult)){
  $securityLevel = $row[0];
}
$securityLevel = str_split($securityLevel);
$securityLevel = $securityLevel[2];

$_SESSION["securityLevelDA"] = $securityLevel;

// How many sets we display in drop downs. 
$_SESSION["numberOfSetsToDisplayInDD"] = 10;

// How large a picture can be. 5 MB.
$_SESSION["pictureValidation"]["maxSize"] =  5000000;

// What picture formats are accpeted.
$_SESSION["pictureValidation"]["formats"] = ["jpg", "jpeg", "png", "gif", "bmp", "tif"];

// How large a file can be. 5 MB.
$_SESSION["fileValidation"]["maxSize"] = 5000000;

?>

<meta charset="utf-8">
<meta name="google" content="notranslate">
<meta http-equiv="Content-Language" content="en">
<link href='../css/main.css' rel='stylesheet'>
<link href='../css/header.css' rel='stylesheet'>
<link href='../css/bootstrap.min.css' rel='stylesheet'>
<link href='../css/jquery-ui.min.css' rel='stylesheet'>
<link href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js'></script> <!-- For formating input date -->
<!-- <script src='../dest/fraunhofer.min.js'></script> -->
<script src='../js/app.js'></script>
<script src='../js/sample.js'></script>
<script src='../js/analysis.js'></script>
<script src='../js/process.js'></script>
<script src='../js/bootstrap.js'></script>
<!-- Toolbar -->
<div class="navbar navbar-default collapse navbar-collapse navHeaderCollapse navbar-static-top">
  <div class="container">
    <ul class='navbar-brand navbar-left'>
      <a href='../../Views/menu.php'>Menu</a>
      <a href='../../Views/editProfile.php' class='username'><?php echo $_SESSION["username"];?></a>
    </ul>
    <ul class='navbar-right btn-group' data-toggle='buttons' >
      <button type='button' id='nav_comment' onclick="location.href='feedback.php'"class='btn btn-primary header_btn ' role='button'>Comment</button>
      <button type='button' class='btn btn-danger header_btn' onclick='logout()'>Logout</button>
    </ul>
  </div>
</div>
<!-- Navigation bar-->
<div class="container nav_bar_lower">
  <div class='btn-group col-md-12 nav_bar_lower' data-toggle='buttons'>
    <button type='button' id='nav_home' class='btn btn-primary col-md-2' onclick="location.href='dataAnalysis.php'">Home</button>
    <button type='button' id='nav_sample' class='btn btn-primary col-md-2' onclick="location.href='addSample.php'">Add Sample</button>
    <button type='button' id='nav_process' class='btn btn-primary col-md-2' onclick="location.href='process.php'">Process</button>
    <button type='button' id='nav_analyze' class='btn btn-primary col-md-2' onclick="location.href='analyze.php'">Analyze</button>
    <button type='button' id='nav_search' class='btn btn-primary col-md-2' onclick="location.href='search.php'">Search</button>
    <div id='nav_overview_btn_group' class='btn-group col-md-2' role='group'>
      <button type='button' id='nav_overview' class='btn btn-primary col-md-12 dropdown-toggle'  data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
        Overview
        <span class='caret'></span>
      </button>
      <ul class='dropdown-menu'>
        <li><a onclick="location.href='sampleOverview.php'">Sample Overview</a></li>
        <li><a onclick="location.href='anlysEquipment.php'">Analysis equipment</a></li>
        <li><a onclick="location.href='prcsEquipment.php'">Process equipment</a></li>
      </ul>
    </div>
  </div>
</div>
</div>


