<?php
/*
	This file generates the report data from the user input
*/
include '../connection.php';
session_start();

$customer      = mysqli_real_escape_string($link, $_POST['customer_ID']);
$first_date    = mysqli_real_escape_string($link, $_POST['date_from']);
$last_date     = mysqli_real_escape_string($link, $_POST['date_to']);
$date_type     = mysqli_real_escape_string($link, $_POST['date_type']);
$show_discount = mysqli_real_escape_string($link, $_POST['show_discount']);

if($show_discount != 'on'){
	$hide_discount = "style='display:none' ";
}
//Get customer name from customer_ID
$custSql = "SELECT customer_name
			FROM customer
			WHERE customer_ID = '$customer'";
$custResult = mysqli_query($link, $custSql);
while($row = mysqli_fetch_array($custResult)){
	$customer_name = $row[0];
}

$date_format = "";
if($date_type == "Year"){
	$date_format = "%Y";
}
if($date_type == "Month"){
	$date_format = "%Y/%m";
}
if($date_type == "Week"){
	$date_format = "%Y/%m/%u";
}

/*
	Query that fetches most of the info needed.
	We do not want to include POS that have not been shipped.
	The date type can be MONTH, YEAR or WEEK and the query
	is grouped by that.
*/

$poSql = "SELECT DATE_FORMAT(shipping_date, '".$date_format."'), count(p.po_ID), ROUND(AVG(final_price), 2), ROUND(AVG(TOTAL_WEEKDAYS(shipping_date, receiving_date)), 2), ROUND(SUM(p.final_price), 2), c.customer_name
		  FROM pos p, customer c
		  WHERE p.customer_ID = c.customer_ID ";

// Query that fetches info from the discount table and makes sure that
// we don't count the same lineitem twice.
// If the item doesn't have a discount, then the table will show 0 instead of NULL
$lineitemSql = "SELECT IF(SUM(p.amount) IS NULL, 0, SUM(p.amount)), IF(SUM(p.total) IS NULL, 0, SUM(ROUND(p.total, 2))), SUM(l.quantity)
								FROM lineitem AS l
				  					LEFT JOIN (SELECT lineitem_ID, discount_ID, SUM(number_of_tools) AS amount, SUM(discount * number_of_tools) AS total
				         					   FROM discount
				         					   GROUP BY lineitem_ID) AS p
				    				ON l.lineitem_ID = p.lineitem_ID, pos
								WHERE pos.po_ID = l.po_ID ";
if($customer != 'customer'){
	if(!empty($customer)){
		$poSql .= "AND p.customer_ID = '$customer' ";
		$lineitemSql .= "AND pos.customer_ID = '$customer' ";
	}
}

// We don't want to calculate the revenue from POs that haven't been shipped yet
$poSql .= "AND p.po_ID NOT IN (SELECT po_ID
		  				     FROM pos
		                     WHERE shipping_date IS NULL) ";
$lineitemSql .= "AND pos.po_ID NOT IN (SELECT po_ID
				  				   FROM pos
				                   WHERE shipping_date IS NULL) ";

// Filtering options
if(!empty($first_date)){
	$poSql 		 .= "AND shipping_date >= '$first_date' ";
	$lineitemSql .= "AND shipping_date >= '$first_date' ";
}
if(!empty($last_date)){
	$poSql 		 .= "AND shipping_date <= '$last_date' ";
	$lineitemSql .= "AND shipping_date <= '$last_date' ";
}
if($customer != 'customer'){
	$poSql 		 .= "GROUP BY YEAR(shipping_date), ".$date_type." (shipping_date) DESC;";
	$lineitemSql .= "GROUP BY YEAR(shipping_date), ".$date_type." (shipping_date) DESC;";
}else{
	$poSql 		 .= "GROUP BY p.customer_ID;";
	$lineitemSql .= "GROUP BY pos.customer_ID";
}
$poResult = mysqli_query($link, $poSql);

$lineitemResult = mysqli_query($link, $lineitemSql);

if(!$poResult){echo "po error " .mysqli_error($link);}

if(!$lineitemResult){echo "lineitem error " . mysqli_error($link);}

?>
<!-- The header of the output table -->
<div id='output'>
	<h4><?php echo $customer_name;?></h4>
	<table class='table table-striped table-bordered'>
		<tr style='width:auto;'>
			<?php
				if($customer != 'customer'){
					echo "<th>Date</th>";
				}else{
					echo "<th>Customer</th>";
				}
			?>
			<th># of POS</th>
		    <th>Avg PO price</th>
			<th>Avg turn around time W/O weekends</th>
			<th>Number of tools</th>
			<?php echo "<th ".$hide_discount.">Number of tools with discount</th>"; ?>
			<?php echo "<th ".$hide_discount.">Sum of discount</th>"; ?>
			<th>Avg tool price</th>
			<th>Revenue</th>
		<tr>
<?php
/*
	Fill an array with the data from the second query
	This is done so its easy to fetch the data in the While loop that iterates through
	the big query.
*/

while($row = mysqli_fetch_array($poResult)){
	$lrow = mysqli_fetch_array($lineitemResult);
	if($show_discount == 'on'){
		$final_price = $row[4] -$lrow[1];
		$avgToolPrice = ($final_price / $lrow[2]);
	}
	else{
		$final_price = $row[4];
		$avgToolPrice = ($row[4] / $lrow[2]);
	}
	echo "<tr>";
	if($customer != 'customer'){
		echo "<td>".$row[0]."</td>";// month
	}else{
		echo "<td>".$row[5]."</td>";// month
	}
		echo "<td>".$row[1]."</td>";// # of pos
		echo "<td>$".$row[2]."</td>";// AVG po price
		echo "<td>".$row[3]."</td>";// AVG turn around time
		echo "<td>".$lrow[2]."</td>";// total tools
		echo "<td ".$hide_discount.">".$lrow[0]."</td>";// Number of tools with discount
		echo "<td ".$hide_discount.">".$lrow[1]."</td>";//	Total Discount $
		echo "<td>$".round($avgToolPrice, 2)."</td>";// Average tool price
		echo "<td>$".$final_price."</td>";// Total price
}
?>
	</table>
</div>
