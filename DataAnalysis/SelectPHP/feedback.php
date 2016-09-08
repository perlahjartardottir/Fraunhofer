<?php
include '../../connection.php';
session_start();

$sql = "SELECT f.fdbk_ID as fdbkID, f.employee_ID as employeeID, e.employee_name as employeeName,
f.fdbk_date as date, f.fdbk_location as location, f.fdbk_sample as sample, f.fdbk_description as description, 
f.fdbk_resolved as resolved, f.fdbk_dev_comment as devComment
FROM da_feedback f, employee e
WHERE f.employee_ID = e.employee_ID
ORDER BY f.fdbk_ID DESC;";
$result = mysqli_query($link, $sql);


while($row = mysqli_fetch_array($result)){
	echo"
	<div class='row well well-lg'>";

	if($row['resolved']){
	echo"
	<p class='color_success'><span class='glyphicon glyphicon-ok'></span><strong> Resolved</strong></p>";
}

		echo"
		<p>".$row['date']."</p>
		<p><strong>".$row['employeeName']."</strong><p>";
			if($row['location']){
				echo"
				<p><strong>Location: </strong>".$row['location']."</p>";
			}
			if($row['sample']){
				echo"
				<p><strong>Sample: </strong>".$row['sample']."</p>";
			}

			echo"
			<p><strong>ID: </strong>".$row['fdbkID']."<p>
			<p>".$row['description']."</p>";

			if($row['devComment']){
				echo"
				<p><strong>Developer's comment: </strong>".$row['devComment']."</p>";
			}

			echo"
		</div>";
	}

	?>