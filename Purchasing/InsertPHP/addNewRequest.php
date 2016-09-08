<?php
include '../../connection.php';

$request_supplier     = mysqli_real_escape_string($link, $_POST['request_supplier']);
$timeframe            = mysqli_real_escape_string($link, $_POST['orderTimeframe']);
$timeframeDate        = mysqli_real_escape_string($link, $_POST['orderTimeframeDate']);
$department           = mysqli_real_escape_string($link, $_POST['department']);
$cost_code            = mysqli_real_escape_string($link, $_POST['cost_code']);
$request_description  = mysqli_real_escape_string($link, $_POST['request_description']);
$employee_ID          = mysqli_real_escape_string($link, $_POST['employee_ID']);
$part_number          = mysqli_real_escape_string($link, $_POST['part_number']);
$quantity             = mysqli_real_escape_string($link, $_POST['quantity']);
$request_price        = mysqli_real_escape_string($link, $_POST['request_price']);
$unit_price          	= mysqli_real_escape_string($link, $_POST['unit_price']);
$unit_description       = mysqli_real_escape_string($link, $_POST['unit_description']);

// If the user has chosen a specific date, add that but not the text "specific date".
if($timeframe === "Specific date"){
	$timeframe = $timeframeDate;
}
// Insert all the fields to the request, no matter if they are empty or not
$sql = "INSERT INTO order_request (employee_ID, timeframe, department, cost_code, request_description, request_date, active, request_supplier, part_number, quantity, request_price, unit_price, unit_description)
        VALUES ('$employee_ID', '$timeframe', '$department', '$cost_code', '$request_description', CURDATE(), 1, '$request_supplier', '$part_number', '$quantity', '$request_price','$unit_price','$unit_description');";
$result = mysqli_query($link, $sql);

// mysqli_insert_id fetches the last inserted row
$request_ID = mysqli_insert_id($link);

// create_request = 0 means that the quote is no longer active
// this query deactivates all active quotes and links them to the request
$quoteSql = "UPDATE quote
             SET create_request = 0, request_ID = '$request_ID'
             WHERE create_request = 1;";
$quoteResult = mysqli_query($link, $quoteSql);

if(!$result){
	echo("Something went wrong : ".mysqli_error($link));
}

// If this request is needed within 2 days, send an email to office manager (ID 6).
if($timeframeDate !== ""){
	$today = date("Y-m-d");
	$difference = abs(strtotime($today) - strtotime($timeframeDate));
	$days = floor(($difference - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
	if($days < 1){

		$employeeSql = "SELECT employee_name
		FROM employee
		WHERE employee_ID = '$employee_ID';";
		$employeeName = mysqli_fetch_row(mysqli_query($link, $employeeSql))[0];

		$purchasingPersonSql = "SELECT employee_email
		FROM employee
		WHERE employee_ID = '6';";
		$purchasingPerson = mysqli_fetch_row(mysqli_query($link, $purchasingPersonSql))[0];
		
		$subject = "";
		if($days < 1){
			$subject = "Request ".$request_ID." is required today!";
		}
		else{
			$subject = "Request ".$request_ID." is required by ".$timeframeDate;
		}
		
		$message = "Requested by: ".$employeeName."\nSupplier: ".$request_supplier."\nRequired by: ".$timeframeDate."\nTotal price: ".$request_price."\n\n";
		$message .= "You can process the order at: localhost:8887/Fraunhofer/Purchasing/views/processOrder.php\n\n";
		$to = $purchasingPerson;
		$headers = "From: ccd.purchasing@gmail.com";

		if(mail($to,$subject,$message,$headers)){
			echo $purchasingPerson;
		}
	}
}

mysqli_close($link);

?>
