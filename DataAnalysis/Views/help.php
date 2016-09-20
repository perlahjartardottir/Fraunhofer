<?php
include '../../connection.php';
session_start();

$securityLevel = $_SESSION["securityLevelDA"];
$user = $_SESSION["username"];
// If the user security level is not high enough we kill the page and give him a link to the log in page.
if($securityLevel < 2){
	echo "<a href='../../Login/login.php'>Login Page</a></br>";
	die("You don't have the privileges to view this site.");
}

$userIDSql = "SELECT employee_ID
FROM employee
WHERE employee_name = '$user'";
$userID = mysqli_fetch_row(mysqli_query($link, $userIDSql))[0];

?>
<head>
	<title>Data Analysis</title>
</head>
<body>
	<?php include '../header.php';?>
	<div class='container'>
		<div id='success_message'></div>
		<h2 class='custom_heading center_heading'>How to...</h2>
		
		<center>
			<h3 class='custom_help_heading'>Add a sample</h3>
			<video width='950' height='710' controls>
				<source src='../videos/addSample.mp4' type="video/mp4">
					Your browser does not support the video tag.
				</video>
			</center>
		
		<center>
			<h3 class='custom_help_heading'>Process a sample</h3>
			<video width='950' height='600' controls>
				<source src='../videos/process.mp4' type="video/mp4">
					Your browser does not support the video tag.
			</video>
		</center>
		<center>
			<h3 class='custom_help_heading'>Analyse a sample</h3>
			<video width='950' height='600' controls>
				<source src='../videos/analysis.mp4' type="video/mp4">
					Your browser does not support the video tag.
			</video>
		</center>
		<center>
			<h3 class='custom_help_heading' style='top-margin:px;'>Add a new coating property to an existing equipment</h3>
			<h5 class='custom_heading'>Requires a security level 4 or higher.</h5>
		<ul style="list-style-type:none">
			<li>Start by adding a coating propery, if it does not exists.</li>
			<li>Add the new equipment, if it does not exists.</li>
			<li>Find the equipment in the list.</li>
			<li>Click "add a new property" and choose the right property.</li>
		</ul>
			<video width='950' height='600' controls>
				<source src='../videos/newProperty.mp4' type="video/mp4">
					Your browser does not support the video tag.
			</video>
		</center>
		<center>
			<h3 class='custom_help_heading'>Security levels</h3>
			<h5></h5>
			<ul style="list-style-type:none">
				<li>1: Edit profile. Data Analysis button visible.</li>
				<li>2: General usage.</li>
				<li>4: Add, edit & delete equipment. Add,edit & delete analysis properties.</li>
			</ul>
		</center>
	</div>
</body>