  <?php
  include '../../connection.php';
  session_start();

  $securityLevel = $_SESSION["securityLevelDA"];

  // if the user security level is not high enough we kill the page and give him a link to the log in page
  if($securityLevel < 2){
  	echo "<a href='../../Login/login.php'>Login Page</a></br>";
  	die("You don't have the privileges to view this site.");
  } 
  ?>

  <head>
  	<title>Fraunhofer CCD</title>
  	<link href='../css/bootstrap.min.css' rel='stylesheet'>
  </head>
  <body>
  	<?php include '../header.php';?>
  	<div class="container">
  		<div class='row well well-lg'>
  			<div class='col-md-12'>
  			<h3 class='custom_heading center_heading'>Sample overview</h2>
  				</h3>
  			</div>
  		</div>
  	</div>
  </body>