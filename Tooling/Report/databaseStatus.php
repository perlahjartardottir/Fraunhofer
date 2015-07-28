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
			<h2>The status of the database in numbers</h2>
			<canvas id="canvas" height="450" width="600"></canvas>
		</div>
	<script>
	<?php 
		$lineitemSql = "SELECT COUNT(lineitem_ID)
						FROM lineitem;";
		$lineitemResult = mysqli_query($link, $lineitemSql);

		$runSql = "SELECT COUNT(run_ID)
				   FROM run;";
		$runResult = mysqli_query($link, $runSql);

		$poSql = "SELECT COUNT(po_ID)
				  FROM pos;";
		$poResult = mysqli_query($link, $poSql);

		while($row = mysqli_fetch_array($lineitemResult)){
			$lineitems = $row[0];
		}

		while($row = mysqli_fetch_array($poResult)){
			$pos = $row[0];
		}

		while($row = mysqli_fetch_array($runResult)){
			$runs = $row[0];
		}
	?>
	var myData = {
		labels : ["Lineitem", "Pos", "Runs"],
		datasets : [
			{
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,0.8)",
				highlightFill : "rgba(151,187,205,0.75)",
				highlightStroke : "rgba(151,187,205,1)",
				data : [<?php echo $lineitems;?>, <?php echo $pos;?>, <?php echo $runs;?>]
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
