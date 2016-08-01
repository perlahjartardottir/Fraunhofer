<?php
include '../../connection.php';
// session_start();

$propsWithAvegResults = [];

// Find the ID of properties where we would like to calculate the average from anlys_result
$propsWithAvegResultsSql = "SELECT a.eq_anlys_prop_ID
FROM anlys_property p, anlys_eq_prop a
WHERE p.anlys_prop_ID = a.anlys_prop_ID, p.anlys_prop_name LIKE '%coefficient%friction%' OR p.anlys_prop_name LIKE '%contact angle%' OR p.anlys_prop_name LIKE '%roughness%' OR p.anlys_prop_name LIKE '%thickness%' OR p.anlys_prop_name LIKE '%wear%rate%' OR p.anlys_prop_name LIKE '%young%modulus%';";
$propsWithAvegResultsResult = mysqli_query($link, $propsWithAvegResultsSql);
while($row = mysqli_fetch_row($propsWithAvegResultsResult)){
	array_push($propsWithAvegResults, $row[0]);
	echo "in array: ".$row[0];
}

// mysqli_close($link);
?>
