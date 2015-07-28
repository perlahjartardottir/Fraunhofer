<?php
/*
	This file searches for pos with the data from the user
*/
include '../connection.php';
session_start();

//find the current user
$user = $_SESSION["username"];
//find his level of security
$secsql = "SELECT security_level
           FROM employee
           WHERE employee_name = '$user'";
$secResult = mysqli_query($link, $secsql);

while($secRow = mysqli_fetch_array($secResult)){
  $user_sec_lvl = $secRow[0];
}

// if this returns true the PO is empty and
// users with low security level can delete it
function safe_delete($poID){
	include '../connection.php';
	// SQL to check if any table contains this po_ID
	$deleteSql = "SELECT p.po_ID
      				  FROM pos p
      				  WHERE po_ID = $poID
      				  AND p.po_ID NOT IN (SELECT l.po_ID
      				  					  FROM lineitem l)
      				  AND p.po_ID NOT IN (SELECT posr.po_ID
      				  				      FROM pos_run posr)
      				  AND p.po_ID NOT IN (SELECT po_ID
      				  				      FROM po_scan
      				                      WHERE po_ID IS NOT NULL);";
	$deleteResult = mysqli_query($link, $deleteSql);
	// if the query returns 0 rows its safe to delete the PO
	if(mysqli_num_rows($deleteResult) > 0){
		return true;
	}
	return false;
}

$input       = mysqli_real_escape_string($link, $_POST['po_number']);
$customer_ID = mysqli_real_escape_string($link, $_POST['customer_ID']);
$first_date  = mysqli_real_escape_string($link, $_POST['first_date']);
$last_date   = mysqli_real_escape_string($link, $_POST['last_date']);
$order_by    = mysqli_real_escape_string($link, $_POST['order_by']);
$top_100   	 = mysqli_real_escape_string($link, $_POST['top_100']);
$limit 		   = "LIMIT 100";

// Only view the top 100 results so the query result doesn't take as much time
// If the "show all result" checkbox is checked, we show all the results
// by having no limit on the sql query
if($top_100 == 'on'){
	$limit = "";
}

// put a wildcard char after the po_number so it displays everything that starts with this string
$stringInput = $input . '%';

// build the basic sql statement
// use left join so we also show pos that dont have items on it
$sql = "SELECT SQL_CALC_FOUND_ROWS p.po_ID, po_number, c.customer_name, receiving_date, SUM(l.quantity), shipping_date, ROUND(SUM(l.price * l.quantity), 2)
  	    FROM pos p LEFT JOIN lineitem l
  	    	ON p.po_ID = l.po_ID , customer c
  	    WHERE 1
  	    AND p.po_number LIKE '$stringInput'
  	    AND c.customer_ID = p.customer_ID ";

// if the user has picked some customer_ID it adds that to the query. Same with all the following If statements.
if(!empty($customer_ID))
{
	$sql .= "AND p.customer_ID = '$customer_ID' ";
}
if(!empty($first_date)){
	$sql .= "AND receiving_date >= '$first_date' ";
}
if(!empty($last_date)){
	$sql .= "AND receiving_date <= '$last_date' ";
}
$sql .= "GROUP BY p.po_ID ";
$sql .= "ORDER BY ".$order_by." DESC ".$limit.";";
$result = mysqli_query($link, $sql);
// next query is to get total amount of rows without LIMIT 100

$countRowsSql = "SELECT FOUND_ROWS();";

$countRowResult = mysqli_query($link, $countRowsSql);

$countRows = mysqli_fetch_array($countRowResult);

if(!$result){echo mysqli_error($link);}

$num_rows = mysqli_num_rows($result);
?>
<div id='output'>
  <span>Showing <?php echo $num_rows; ?> out of <?php echo $countRows[0];?> rows. </span>
<table id='' class='table table-striped table-responsive'>
	<tr>
		<th>PO number</th>
		<th>Customer</th>
		<th>Receiving Date</th>
		<th width="70">Number of tools</th>
	    <th>Shipping date</th>
		<th>Final Price</th>
	<tr>
<?php
/*
*	This while loops generates buttons on each line in the table
*	And a modal page for every PO number
*	The Modals are displayed when the user clicks the button
*/
while($row = mysqli_fetch_array($result)){
	echo "<tr class=''>".
			"<td><a href='#' data-toggle='modal' onclick='setSessionIDSearch(".$row[0].")' data-target='#".$row[0]."'>".$row[1]."</td>".
			"<td>".$row[2]."</td>".
			"<td>".$row[3]."</td>".
			"<td>".$row[4]."</td>".
			"<td>".$row[5]."</td>".
			"<td>$".$row[6]."</td>".
		  "</tr>";

	echo "<div class='modal fade' id='".$row[0]."' tabindex='-1' role='dialog' aria-labelledby='".$row[0]."' aria-hidden='true'>
			  <div class='modal-dialog'>
			    <div class='modal-content'>
			      <div class='modal-header'>
			        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
			        <h4 class='modal-title' id='myModalLabel'>PO number: ".$row[1]."</h4>
			      </div>
			      <div class='modal-body'>
			        <h3>PO information<h3>
			        <div class='btn-group'>
			            <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
			              Printout <span class='caret'></span>
			            </button>
			            <ul class='dropdown-menu' role='menu'>
			              <li><a href='../Printouts/tracksheet.php'>Track sheet</a></li>
			              <li><a href='../Printouts/generalinfo.php'>General info</a></li>
			              <li><a href='../Printouts/packingList.php'>Packing list</a></li>
			              <li><a href='../Printouts/scanprintout.php'>View PO scan</a></li>
			            </ul>
			            <p></p>
			        </div>
			        <div class='btn-group'>
			            <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
			              Edit <span class='caret'></span>
			            </button>
			            <ul class='dropdown-menu' role='menu'>
			              <li><a href='../Views/editPO.php'>Edit PO</a></li>
			              <li><a href='../Views/generateTrackSheet.php'>Edit PO track sheet</a></li>
			              <li><a href='../Views/addTools2.php'>Add lineitems</a></li>
			            </ul>
			        </div>";
			        if($user_sec_lvl > 3){
			        	echo "<button class='btn btn-danger' style='margin-left:5px;' onclick='delPO(".$row[0].", 1)'>Delete PO</button>";
			        }else if(safe_delete($row[0])){
			        	echo "<button class='btn btn-danger' style='margin-left:5px;' onclick='delPO(".$row[0].", 0)'>Delete PO</button>";
			    	}
		echo " </div>
			      <div class='modal-footer'>
			        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
			      </div>
			    </div>
			  </div>
		   </div>";
}
echo "</table></div>";
?>
