<!doctype html>
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
		  <link href='../css/bootstrap.min.css' rel='stylesheet'>
	</head>
	<body>
		<?php include '../header.php'; ?>
		<div style="width: 50%" class='col-md-12 col-md-offset-3'>
			<h2>$Total for each company</h2>
			<canvas id="canvas" height="450" width="600"></canvas>
		</div>
	<script>
	<?php 
		//get Sum and customer name for each company
		$bigQuery = "SELECT ROUND(SUM(quantity * price),2), c.customer_name
					 FROM lineitem l, pos p, customer c
					 WHERE l.po_ID = p.po_ID
					 AND p.customer_ID = c.customer_ID
					 GROUP BY p.customer_ID;";
	?>
	var myData = {
		labels : [<?php 
					$labels = mysqli_query($link, $bigQuery);
					while($row = mysqli_fetch_array($labels)){
							echo '"'.$row[1].'",';
						}
				  ?>],// list of companies
		datasets : [
			{
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : [<?php
							$data = mysqli_query($link, $bigQuery);
							while($row = mysqli_fetch_array($data)){
								echo $row[0].",";
							}
					   ?>]//sum for $ for each company
			}
		]
	}
	window.onload = function(){
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myBar = new Chart(ctx).Bar(myData, {
			responsive : true
		});
	}

</script>
	</body>
</html>
