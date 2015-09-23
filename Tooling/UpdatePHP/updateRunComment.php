<?php
/*
	Update the comment for a run
	important so you can add runs before they are finished
*/
include '../connection.php';

$comment            = mysqli_real_escape_string($link, $_POST['comment']);
$run_ID             = mysqli_real_escape_string($link, $_POST['run_ID']);
$po_ID             = mysqli_real_escape_string($link, $_POST['po_ID']);
$run_number_on_po   = mysqli_real_escape_string($link, $_POST['run_number_on_po']);
$ah_pulses          = mysqli_real_escape_string($link, $_POST['ah_pulses']);
$coatingID          = mysqli_real_escape_string($link, $_POST['coatingID']);
$machineID          = mysqli_real_escape_string($link, $_POST['machineID']);
$machine_run_number = mysqli_real_escape_string($link, $_POST['machine_run_number']);
$runDate            = mysqli_real_escape_string($link, $_POST['runDate']);

$machineSql = "SELECT machine_acronym
			   FROM machine
			   WHERE machine_ID = '$machineID';";
$machineResult = mysqli_query($link, $machineSql);

while($row = mysqli_fetch_array($machineResult)){
	$machineAcro = $row[0];
}

$runDate = str_replace("-","",$runDate);
$run_number = $machineAcro.$runDate[2].$runDate[3].$runDate[4].$runDate[5].$runDate[6].$runDate[7].$machine_run_number;

// This checks what character the user inputed and changes that to the right
// integer for the database tables.
if($run_number_on_po == 'a' || $run_number_on_po == 'A'){ $run_number_on_po = 1;}
if($run_number_on_po == 'b' || $run_number_on_po == 'B'){ $run_number_on_po = 2;}
if($run_number_on_po == 'c' || $run_number_on_po == 'C'){ $run_number_on_po = 3;}
if($run_number_on_po == 'd' || $run_number_on_po == 'D'){ $run_number_on_po = 4;}
if($run_number_on_po == 'e' || $run_number_on_po == 'E'){ $run_number_on_po = 5;}
if($run_number_on_po == 'f' || $run_number_on_po == 'F'){ $run_number_on_po = 6;}
if($run_number_on_po == 'g' || $run_number_on_po == 'G'){ $run_number_on_po = 7;}

$sql = "UPDATE run
		SET run_comment = '$comment', ah_pulses = '$ah_pulses', run_date = '$runDate', run_number = '$run_number', machine_ID = '$machineID', coating_ID = '$coatingID'
		WHERE run_ID = $run_ID;";
$result = mysqli_query($link, $sql);

$runOnPOSql = "UPDATE pos_run
			   SET run_number_on_po = '$run_number_on_po'
			   WHERE run_ID = $run_ID
				 AND po_ID = '$po_ID';";

$runOnPOResult = mysqli_query($link, $runOnPOSql);

if (!$result || !$runOnPOResult) {
    $message  = 'Invalid query: ' . mysql_error();
}
mysqli_close($link);
?>
