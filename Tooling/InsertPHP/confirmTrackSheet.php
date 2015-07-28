<?php
include '../connection.php';
session_start();

$po_ID = $_SESSION["po_ID"];
$date  = mysqli_real_escape_string($link, $_POST['date']);

// finds lineitems without comment with the session po_ID
$noCommentLineitemsql = "SELECT l.quantity, l.tool_ID, r.run_number
						 						 FROM lineitem l, lineitem_run lir, run r
						 						 WHERE po_ID = '$po_ID'
						 						 AND l.lineitem_ID = lir.lineitem_ID
						 						 AND lir.run_ID = r.run_ID
						 						 AND lir.lineitem_run_comment LIKE '';";

$noCommentLineitemResult = mysqli_query($link, $noCommentLineitemsql);

if(mysqli_num_rows($noCommentLineitemResult) > 0){
	while($row = mysqli_fetch_array($noCommentLineitemResult)){
		$errorMessage .= "Lineitem with tool ID: ".$row[1]." in run: ".$row[2]." does not have a comment. \r\n";
	}
	$errorMessage .= "Click ok to go to the edit track sheet menu.";
	die($errorMessage);
}

// finds runs without comment with a specific po_ID
$noRunCommentSql = "SELECT r.run_number, m.machine_name
										FROM run r, machine m, pos_run posr
										WHERE posr.po_ID = '$po_ID'
										AND posr.run_ID = r.run_ID
										AND r.machine_ID = m.machine_ID
										AND r.run_comment LIKE '';";

$noRunCommentResult = mysqli_query($link, $noRunCommentSql);

if(mysqli_num_rows($noRunCommentResult) > 0){
	while($row = mysqli_fetch_array($noRunCommentResult)){
		$errorMessage .= "Run with run number : ".$row[0]." in machine : ".$row[1]." does not have a comment. \r\n";
	}
	$errorMessage .= "Click ok to go to the edit track sheet menu. ";
	die($errorMessage);
}


// if the date is empty we dont insert it to the DB
if($date == ""){
	echo "Error. Empty date";
}
else{
// the following code is to check if we have coated all the tools. The user
// decides if he wants to continue if there are missing tools
$quantitySql = "SELECT SUM(quantity)
								FROM lineitem
								WHERE po_ID = '$po_ID';";
$quantityResult = mysqli_query($link, $quantitySql);

$coatedToolsSql = "SELECT SUM(number_of_items_in_run)
				   				 FROM lineitem_run lr, lineitem l
				   				 WHERE l.po_ID = '$po_ID'
				   				 AND l.lineitem_ID = lr.lineitem_ID;";
$coatedToolResult = mysqli_query($link, $coatedToolsSql);

while($row = mysqli_fetch_array($quantityResult)){
	$quantity = $row[0];
}
while($row = mysqli_fetch_array($coatedToolResult)){
	$coatedToolSum = $row[0];
}
if($quantity > $coatedToolSum){
	$missing = $quantity - $coatedToolSum;
	echo "Some tools have not been assigned to runs. You are missing : ".$missing." tools. If you want to continue anyway press OK";
}
if($quantity < $coatedToolSum){
	$missing = $coatedToolSum - $quantity;
	echo "There have been more tools assigned to runs then received from the customer. There are : ".$missing." extra tools. If you want to continue anyway press OK";
}


mysqli_close($link);
}
?>
