<?php
// Delete every child table before we can delete the PO
include '../connection.php';


$poid = mysqli_real_escape_string($link, $_POST['po_ID']);

// Delete from employee_pos table
$employeePOsSql = "DELETE FROM employee_pos
				   				 WHERE po_ID = '$poid'";
$employeePOsResult = mysqli_query($link, $employeePOsSql);

if(!$employeePOsResult){
	die("Employee. Input data is fail".mysqli_error($link));
}

// Delete from regular tools table
$regulartoolSql = "DELETE FROM regulartool
				   				 WHERE lineitem_ID IN (SELECT l.lineitem_ID
				   				 											 FROM lineitem l
				   				 								 			 WHERE l.po_ID = '$poid')";
$regulartoolResult = mysqli_query($link, $regulartoolSql);
if(!$regulartoolResult){
	die("Regular tool. Input data is fail".mysqli_error($link));
}

// Delete from odd shaped tools table
$oddshapedSql = "DELETE FROM oddshapedtool
				 				 WHERE lineitem_ID IN (SELECT l.lineitem_ID
				   			 											 FROM lineitem l
				   			 								 			 WHERE l.po_ID = '$poid')";
$oddshapedResult = mysqli_query($link, $oddshapedSql);
if(!$oddshapedResult){
	die("Odd tool. Input data is fail".mysqli_error($link));
}

// Delete from po_scan table
$scanSql = "DELETE FROM po_scan
				    WHERE po_ID = '$poid'";
$scanResult = mysqli_query($link, $scanSql);

if(!$scanResult){
	die("Scan. Input data is fail".mysqli_error($link));
}

// Delete from pos_run table
$posRunSql = "DELETE FROM pos_run
				      WHERE po_ID = '$poid'";
$posRunResult = mysqli_query($link, $posRunSql);

if(!$posRunResult){
	die("pos_run. Input data is fail".mysqli_error($link));
}

// Delete from lineitem_run table
$lineitemRunSql = "DELETE FROM lineitem_run
				   				 WHERE lineitem_ID IN (SELECT l.lineitem_ID
				   				 											 FROM lineitem l
				   				 								 			 WHERE l.po_ID = '$poid')";
$lineitemRunResult = mysqli_query($link, $lineitemRunSql);
if(!$lineitemRunResult){
	die("Lineitem run. Input data is fail".mysqli_error($link));
}

// Delete from discount table
$discountSql = "DELETE FROM discount
				   			WHERE lineitem_ID IN (SELECT l.lineitem_ID
				   														FROM lineitem l
				   														WHERE l.po_ID = '$poid')";
$discountResult = mysqli_query($link, $discountSql);

if(!$discountResult){
	die("Discount. Input data is fail".mysqli_error($link));
}

// Delete from lineitem table
$lineitemSql = "DELETE FROM lineitem
								WHERE po_ID = '$poid'";
$lineitemResult = mysqli_query($link, $lineitemSql);

if(!$lineitemResult){
	die("Input data is fail".mysqli_error($link));
}

// Finally we can delete the PO
$sql = "DELETE FROM POS
				WHERE po_ID = '$poid'";
$result = mysqli_query($link, $sql);
if(!$result){
	die("Input data is fail".mysqli_error($link));
}
mysqli_close($link);
?>
