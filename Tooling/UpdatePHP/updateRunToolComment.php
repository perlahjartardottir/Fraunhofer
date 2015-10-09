<?php
include '../connection.php';

$lineitem_ID = mysqli_real_escape_string($link, $_POST['lineitem_ID']);
$run_ID 	 = mysqli_real_escape_string($link, $_POST['run_ID']);
$comment 	 = mysqli_real_escape_string($link, $_POST['comment']);
$delete 	 = mysqli_real_escape_string($link, $_POST['delete']);
$number_of_items 	 = mysqli_real_escape_string($link, $_POST['number_of_items']);

$getSql = "SELECT l.est_run_number, lr.number_of_items_in_run, l.quantity, l.coating_ID, l.diameter, l.tool_type
					 FROM lineitem l, lineitem_run lr
					 WHERE l.lineitem_ID = '$lineitem_ID'
					 AND l.lineitem_ID = lr.lineitem_ID;";
$getResult = mysqli_query($link, $getSql);

$getRow = mysqli_fetch_array($getResult);
$est_run_number = $getRow[0];
$number_of_items_in_run = $getRow[1];
$quantity = $getRow[2];
$coating_ID = $getRow[3];
$diameter = $getRow[4];
$tool_type = $getRow[5];

// If we are deleting the tool from the run, then add these number of items to the run
// to the quantity, else subtract these from the quantity if we are adding tools to the run
if($delete == 'true'){
	$quantity = $quantity + $number_of_items;
} else{
	$quantity = $quantity - $number_of_items_in_run;
}

// update the est run number with the new quantity number
if($coating_ID == 2 && ($comment == 'ok' || $comment == 'OK')){
	if($tool_type == 'top'){
		if($diameter == 1 || $diameter == 2){
			$est_run_number = $quantity * 0.33;
			$est_run_number = $est_run_number / 159;
		}if($insert_size == 3){
			$est_run_number = $quantity * 0.4;
			$est_run_number = $est_run_number / 159;
		}
		if($insert_size == 4 || $insert_size == 5 || $insert_size == 6){
			$est_run_number = $quantity * 0.5;
			$est_run_number = $est_run_number / 159;
		}
	}else if($tool_type == 'round'){
		if($diameter == "1/8" ){
			$est_run_number = $quantity * 0.33;
			$est_run_number = $est_run_number / 159;
		} if($diameter == "3/16" || $diameter == "1/4"){
			$est_run_number = $quantity * 0.5;
			$est_run_number = $est_run_number / 159;
		} if($diameter == "3/8" || $diameter == "1/2"){
			$est_run_number = $quantity * 1;
			$est_run_number = $est_run_number / 159;
		} if($diameter == "5/8" || $diameter == "3/4"){
			$est_run_number = $quantity * 2;
			$est_run_number = $est_run_number / 159;
		} if($diameter == "1" || $diameter == "1 1/4" || $diameter == "1 3/8"){
			$est_run_number = $quantity * 3;
			$est_run_number = $est_run_number / 159;
		}
	}else if($tool_type == 'insert'){
		if($diameter == "1 1/4" || $diameter == "1 3/8"){
			$est_run_number = $quantity / 2;
			$est_run_number = $est_run_number / 159;
		} else if($diameter == "1/2" || $diameter == "5/8" || $diameter == "3/4" || $diameter == "1"){
			$est_run_number = $quantity / 2.5;
			$est_run_number = $est_run_number / 159;
		} else {
			$est_run_number = $quantity / 6;
			$est_run_number = $est_run_number / 159;
		}
	}
} else if($comment == 'ok' || $comment == 'OK'){
	if($tool_type == 'top'){
		if($diameter == 1 || $diameter == 2){
			$est_run_number = $quantity * 0.33;
			$est_run_number = $est_run_number / 143;
		}if($insert_size == 3){
			$est_run_number = $quantity * 0.4;
			$est_run_number = $est_run_number / 143;
		}
		if($insert_size == 4 || $insert_size == 5 || $insert_size == 6){
			$est_run_number = $quantity * 0.5;
			$est_run_number = $est_run_number / 143;
		}
	}else if($tool_type == 'round'){
		if($diameter == "1/8" ){
			$est_run_number = $quantity * 0.33;
			$est_run_number = $est_run_number / 143;
		} if($diameter == "3/16" || $diameter == "1/4"){
			$est_run_number = $quantity * 0.5;
			$est_run_number = $est_run_number / 143;
		} if($diameter == "3/8" || $diameter == "1/2"){
			$est_run_number = $quantity * 1;
			$est_run_number = $est_run_number / 143;
		} if($diameter == "5/8" || $diameter == "3/4"){
			$est_run_number = $quantity * 2;
			$est_run_number = $est_run_number / 143;
		} if($diameter == "1" || $diameter == "1 1/4" || $diameter == "1 3/8"){
			$est_run_number = $quantity * 3;
			$est_run_number = $est_run_number / 143;
		}
	}else if($tool_type == 'insert'){
		if($diameter == "1 1/4" || $diameter == "1 3/8"){
			$est_run_number = $quantity / 2;
			$est_run_number = $est_run_number / 143;
		} else if($diameter == "1/2" || $diameter == "5/8" || $diameter == "3/4" || $diameter == "1"){
			$est_run_number = $quantity / 2.5;
			$est_run_number = $est_run_number / 143;
		} else {
			$est_run_number = $quantity / 6;
			$est_run_number = $est_run_number / 143;
		}
	}
}

$updateSql = "UPDATE lineitem
							SET est_run_number = '$est_run_number'
							WHERE lineitem_ID = '$lineitem_ID';";
$updateResult = mysqli_query($link, $updateSql);

$sql = "UPDATE lineitem_run
		SET lineitem_run_comment = '$comment'
		WHERE lineitem_ID = '$lineitem_ID'
		AND run_ID = '$run_ID'";
$result = mysqli_query($link, $sql);

if (!$result) {
    $message  = 'Invalid query: ' . mysql_error();
}
mysqli_close($link);
?>
