<?php
/*
	This file searches for runs with the input from the user
*/
include '../connection.php';
session_start();

$input       = mysqli_real_escape_string($link, $_POST['run_number']);
$first_date  = mysqli_real_escape_string($link, $_POST['first_date']);
$last_date   = mysqli_real_escape_string($link, $_POST['last_date']);
$machine_ID  = mysqli_real_escape_string($link, $_POST['machine_ID']);
$coating_ID  = mysqli_real_escape_string($link, $_POST['coating_ID']);
$ah_pulses   = mysqli_real_escape_string($link, $_POST['ah_pulses']);
$top_runs 	 = mysqli_real_escape_string($link, $_POST['top_runs']);
$order_by    = mysqli_real_escape_string($link, $_POST['order_by']);
$limit 		 	 = "LIMIT 100";
// Only view the top runs results so the query result doesn't take as much time
// If the "show all result" checkbox is checked, we show all the results
// by having no limit on the sql query
if($top_runs == 'on'){
	$limit = "";
}

// put a wildcard char after the run_number so it displays everything that starts with this string
$stringInput = $input . '%';
$stringAhPulses = $ah_pulses . '%';

// build the basic sql statement
$sql = "SELECT SQL_CALC_FOUND_ROWS r.run_ID, run_number, run_date, run_comment, ah_pulses, SUM(lir.number_of_items_in_run), ROUND(SUM(lir.number_of_items_in_run * l.price), 2), ROUND(SUM(lir.number_of_items_in_run * l.price)/SUM(lir.number_of_items_in_run), 2), ROUND(SUM(lir.number_of_items_in_run * l.price), 2) / COUNT(DISTINCT(r.run_ID))
				FROM run r, lineitem_run lir, lineitem l
				WHERE 1
				AND r.run_ID = lir.run_ID
				AND lir.lineitem_ID = l.lineitem_ID ";
// footer of table SQL
$averageSql = "SELECT ROUND(SUM(lir.number_of_items_in_run * l.price) / COUNT(DISTINCT(r.run_ID)), 2), ROUND(SUM(lir.number_of_items_in_run * l.price)/SUM(lir.number_of_items_in_run), 2), ROUND(SUM(lir.number_of_items_in_run) / COUNT(DISTINCT(r.run_ID)),2)
						   FROM run r, lineitem_run lir, lineitem l
						   WHERE 1
						   AND r.run_ID = lir.run_ID
						   AND lir.lineitem_ID = l.lineitem_ID ";

// if the user picked any of the filter options they are added here
if(!empty($input)){
	$sql .= "AND r.run_number LIKE '$stringInput' ";
	$averageSql .= "AND r.run_number LIKE '$stringInput' ";
}
if(!empty($ah_pulses)){
	$sql .= "AND r.ah_pulses LIKE '$stringAhPulses' ";
	$averageSql .= "AND r.ah_pulses LIKE '$stringAhPulses' ";
}
if(!empty($first_date)){
	$sql .= "AND run_date >= '$first_date' ";
	$averageSql .= "AND run_date >= '$first_date' ";
}
if(!empty($last_date)){
	$sql .= "AND run_date <= '$last_date' ";
	$averageSql .= "AND run_date <= '$last_date' ";
}
if(!empty($machine_ID)){
	$sql .= "AND machine_ID = '$machine_ID' ";
	$averageSql .= "AND machine_ID = '$machine_ID' ";
}
if(!empty($coating_ID)){
	$sql .= "AND r.coating_ID = '$coating_ID' ";
	$averageSql .= "AND r.coating_ID = '$coating_ID' ";
}
$sql .= "GROUP BY r.run_ID ";

if(!empty($order_by)){
	$sql .= "ORDER BY ".$order_by." DESC ".$limit.";";
}
$result = mysqli_query($link, $sql);
// next query is to get total amount of rows without LIMIT 100
$countRowsSql = "SELECT FOUND_ROWS();";

$countRowResult = mysqli_query($link, $countRowsSql);

$countRows = mysqli_fetch_array($countRowResult);

$averageResult = mysqli_query($link, $averageSql);

$num_rows = mysqli_num_rows($result);
if(!$result){echo mysqli_error($link);}
?>
<div id='output'>
	<span>Showing <?php echo $num_rows; ?> out of <?php echo $countRows[0];?> rows. </span>
<table id='' class='table table-striped table-responsive'>
	<col width="100">
	<col width="100">
	<col width="183">
	<tr>
		<th>Run number</th>
		<th>Run date</th>
		<th>Run comment</th>
		<th>Ah/Pulses</th>
		<th># of tools in run</th>
		<th>Total $ in run</th>
		<th>Avg tool cost in run</th>
	<tr>
<?php
/*
*	This while loops generates buttons on each line in the table
*	And a modal page for every run
*	The Modals are displayed when the user clicks the run
*/
while($row = mysqli_fetch_array($result)){

	//This sql is to find the POS linked to the runs found. These show up if you click the run number
	$poSql = "SELECT l.po_ID, p.po_number, c.customer_name, lir.number_of_items_in_run, l.diameter, l.length, l.tool_ID
					  FROM run r, lineitem l, lineitem_run lir, pos p, customer c
					  WHERE r.run_ID = '$row[0]'
					  AND r.run_ID = lir.run_ID
					  AND lir.lineitem_ID = l.lineitem_ID
					  AND l.po_ID = p.po_ID
					  AND c.customer_ID = p.customer_ID;";
	$poResult = mysqli_query($link, $poSql);

	echo "<tr class=''>".
				"<td><a href='#' data-toggle='modal' data-target='#".$row[0]."'>".$row[1]."</td>". //run number
				"<td>".$row[2]."</td>". // run date
				"<td>".$row[3]."</td>". // run comment
				"<td>".$row[4]."</td>". // Ah/pulses
				"<td>".$row[5]."</td>". // # of tools in run
				"<td>$".$row[6]."</td>". // Total $ in run
				"<td>$".$row[7]."</td>". // Avg tool cost in run
			"</tr>";

	echo "<div class='modal fade' id='".$row[0]."' tabindex='-1' role='dialog' aria-labelledby='".$row[0]."' aria-hidden='true'>
			  <div class='modal-dialog'>
			    <div class='modal-content'>
			      <div class='modal-header'>
			        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
			        <h4 class='modal-title' id='myModalLabel'>Run number : ".$row[1]."</h4>
			      </div>
			      <div class='modal-body'>
			      	<h4>Tools in this run</h4>";
					while($poRow = mysqli_fetch_array($poResult)){
						echo "<p style='margin-bottom:5px; border: 1px solid black;'></p>
								<p><strong>PO#: </strong>".$poRow[1]."</p>
								<p><strong>Customer: </strong>".$poRow[2]."</p>
								<p><strong>Number of tools: </strong>".$poRow[3]."</p>
								<p><strong>Tool ID: </strong>".$poRow[6]."</p>
								<strong><p>Diameter: </strong>".$poRow[4]."</p>
								<strong><p>Length: </strong>".$poRow[5]."</p>
								<button class='btn btn-primary' onclick='trackSheetRedirect(".$poRow[0].")'>Track sheet</button>
								<button class='btn btn-success' onclick='generalInfoRedirect(".$poRow[0].")'>General info</button>
								<button class='btn btn-info' onclick='editTrackSheetRedirect(".$poRow[0].")'>Edit track sheet</button>
							 </p>";
					}
				    echo "</div>
			      <div class='modal-footer'>
			        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
			      </div>
			    </div>
			  </div>
		   </div>";
}
$row = mysqli_fetch_array($averageResult);
echo "<tr>
		<td>Average for selection</td>
		<td></td>
		<td></td>
		<td></td>
		<td>".$row[2]."</td>
		<td>".$row[0]."</td>
		<td>".$row[1]."</td>
	</tr>";
echo "</table></div>";
?>
