<?php
include '../connection.php';

// Escape user inputs for security
$po_number 	 = mysqli_real_escape_string($link, $_POST['POID']);
$CID 		 = mysqli_real_escape_string($link, $_POST['CID']);
$rDate 		 = mysqli_real_escape_string($link, $_POST['rDate']);
$iInspect	 = mysqli_real_escape_string($link, $_POST['iInspect']);
$nrOfLines   = mysqli_real_escape_string($link, $_POST['nrOfLines']);
$employeeId  = mysqli_real_escape_string($link, $_POST['employeeId']);
$shipping_info  = mysqli_real_escape_string($link, $_POST['shipping_info']);

// Check if PO number already exists
$checkIfPOExistssql = "SELECT po_number
          					   FROM pos
          					   WHERE po_number = '$po_number' ";
$checkIfPOExistssqlresult = mysqli_query($link, $checkIfPOExistssql);

// if the po_number is empty
if($po_number == ""){
	echo("empty" . mysqli_error($link));
}
// if the PO number already exists
elseif(mysqli_num_rows($checkIfPOExistssqlresult) != 0){
	echo("exists" . mysqli_error($link));
}
else{
	// inserts the PO in the database
	$sql = "INSERT INTO pos(po_number, customer_ID, receiving_date, initial_inspection, nr_of_lines, shipping_info)
          VALUES ('$po_number', '$CID', '$rDate', '$iInspect', '$nrOfLines', '$shipping_info')";
	$result = mysqli_query($link, $sql);
	if($result){
	    echo ("PO was stored");
	} else{
	    echo("PO storing didnt go right". mysqli_error($link));
	}
	//find the po_ID of the item that we just inserted
	$findingpo_IDsql = "SELECT po_ID FROM pos WHERE po_number = '$po_number'";
	$po_IDresult = mysqli_query($link, $findingpo_IDsql);

	while($row = mysqli_fetch_array($po_IDresult)){
		$po_ID = $row[0];
	}
	//inserts the data into the employee_pos table
	$employeeSql = "INSERT INTO employee_pos VALUES ('$po_ID', '$employeeId')";
	$employeeResult = mysqli_query($link, $employeeSql);
	if(!$employeeResult){
		mysqli_error($link);
	}

}
// close connection
mysqli_close($link);
?>
