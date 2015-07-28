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
  <link href='../css/main.css' rel='stylesheet'>
  
  
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
   <div class='row well well-lg'>
    <div class='col-md-3'>
      <p >Input the PO number</p>
      <input type="text" name="search" id="search_box_PO" class='search_box'/>
      <button  type='button'  class='btn btn-primary' onclick='searchPO()'>
        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
      </button>
    </div>
    <div class='col-md-3'>
      <p >Input the Company Name</p>
      <input type="text" name="search" id="search_box_company" class='search_box'/>
      <button type='button'  class='btn btn-primary' onclick='searchPOCompany()'>
        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
      </button>
    </div>
    <div class='col-md-3'>
      <p >Input employee name</p>
      <input type="text" name="search" id="search_box_employee" class='search_box'/>
      <button type='button'  class='btn btn-primary' onclick='searchPOEmployee()'>
        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
      </button>
    </div>
  </div>  
</div>    
<ul id="results" class="update">
</ul>
</body>
</html>