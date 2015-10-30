<?php
/*
	This file searches for tools with the input from the user
*/
include '../connection.php';
session_start();

// Get the results from the filter options
$input 		 	 = mysqli_real_escape_string($link, $_POST['tool_ID']);
$first_date  = mysqli_real_escape_string($link, $_POST['first_date']);
$last_date   = mysqli_real_escape_string($link, $_POST['last_date']);
$top_runs 	 = mysqli_real_escape_string($link, $_POST['top_runs']);
$order_by    = mysqli_real_escape_string($link, $_POST['order_by']);
$limit 			 = "LIMIT 100";

// Only view the top 100 results so the query result doesn't take as much time
// If the "show all result" checkbox is checked, we show all the results
// by having no limit on the sql query
if($top_runs == 'on'){
	$limit = "";
}

// Add the % to the input string so we only have to write
// for instance the first letter for something and then
// the table shows all results that begin with that letter
$stringInput = '%' . $input . '%';

// Getting what we want to show in our table and connecting
// runs, pos and lineitems so the results match
$sql = "SELECT SQL_CALC_FOUND_ROWS l.tool_ID, COUNT(DISTINCT p.po_ID), run_date
				FROM lineitem l, pos p, run r, lineitem_run lir
				WHERE l.tool_ID LIKE '$stringInput'
				AND l.po_ID = p.po_ID
				AND l.lineitem_ID = lir.lineitem_ID
				AND r.run_ID = lir.run_ID ";

// filter for the dates, if the user puts in a date
// then we add that to the sql query
if(!empty($first_date)){
	$sql .= "AND run_date >= '$first_date' ";
}
if(!empty($last_date)){
	$sql .= "AND run_date <= '$last_date' ";
}

// group by the tool id's so all the tool id's
// are grouped together and don't show duplicate id's
$sql .= "GROUP BY l.tool_ID ";
if(!empty($order_by)){
	$sql .= "ORDER BY ".$order_by." ".$limit.";";
}
$result = mysqli_query($link, $sql);
// next query is to get total amount of rows without LIMIT 100
$countRowsSql = "SELECT FOUND_ROWS();";

$countRowResult = mysqli_query($link, $countRowsSql);

$countRows = mysqli_fetch_array($countRowResult);
$num_rows = mysqli_num_rows($result);

if(!$result){echo mysqli_error($link);}
?>
<!-- Make a wrapping div so when we change the search string we replace _everything_ -->
<div id='output'>
<span>Showing <?php echo $num_rows; ?> out of <?php echo $countRows[0];?> rows. </span>
<table id='' class='table table-striped table-bordered'>
	<tr>
		<th>Tool ID</th>
		<th># of POs</th>
		<th>Last price</th>
		<th>Last receiving date</th>
	<tr>

<?php
$counter = 0;
while($row = mysqli_fetch_array($result)){

	// SQL query to get the last price of a specific tool
	// To find that we use the max receiving date
	$lastPriceSql = "SELECT ROUND(AVG(l.price), 2), p.receiving_date
									 FROM lineitem l, pos p
									 WHERE l.po_ID = p.po_ID
									 AND l.tool_ID = '$row[0]'
									 AND p.receiving_date = (SELECT MAX(p1.receiving_date)
															 						 FROM pos p1, lineitem l1
									                         WHERE p1.po_ID = l1.po_ID
									                         AND l1.tool_ID = '$row[0]');";

	$lastPriceResult = mysqli_query($link, $lastPriceSql);
	$lRow = mysqli_fetch_array($lastPriceResult);

	// SQL query for the modal, do show the POs that the tool ID belongs to
	$poSql = "SELECT p.po_number, c.customer_name, l.po_ID
					  FROM lineitem l, pos p, customer c
					  WHERE l.PO_ID = p.PO_ID
					  AND p.customer_ID = c.customer_ID
					  AND l.tool_ID = '$row[0]';";
	$poResult = mysqli_query($link, $poSql);

	echo "<tr>".
				"<td><a href='#' data-toggle='modal' data-target='#".$counter."'>".$row[0]."</td>". // tool_ID
				"<td>".$row[1]."</td>". // # of POs
				"<td>".$lRow[0]."</td>". // Last Price
				"<td>".$lRow[1]."</td>". // Last Receiving date
		   "</tr>";

	echo "<div class='modal fade' id='".$counter."' tabindex='-1' role='dialog' aria-labelledby='".$row[0]."' aria-hidden='true'>
			  <div class='modal-dialog'>
			    <div class='modal-content'>
			      <div class='modal-header'>
			        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
			        <h4 class='modal-title' id='myModalLabel'>Tool ID : ".$row[0]."</h4>
			      </div>
			      <div class='modal-body'>
			      	<h4>POs this tool ID belongs to</h4>";
					while($poRow = mysqli_fetch_array($poResult)){
						echo "<p style='margin-bottom:5px; border: 1px solid black;'>
								<p><strong>Customer : </strong>".$poRow[1].
								"</p><p><strong> PO# : </strong>".$poRow[0]."</p>
								<button class='btn btn-primary' onclick='trackSheetRedirect(".$poRow[2].")'>Track sheet</button>
								<button class='btn btn-success' onclick='generalInfoRedirect(".$poRow[2].")'>General info</button>
							 </p>";
					}
				    echo "</div>
			      <div class='modal-footer'>
			        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
			      </div>
			    </div>
			  </div>
		   </div>";
	$counter++;
}
echo "</table></div>";
?>
