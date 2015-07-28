<?php
include '../connection.php';

$poid = mysqli_real_escape_string($link, $_POST['po_ID']);

$workedOnSql = "DELETE FROM employee_pos
								WHERE po_ID = '$poid'";
$workedonResult = mysqli_query($link, $workedOnSql);

$sql = "DELETE FROM POS
				WHERE po_ID = '$poid'";
$result = mysqli_query($link, $sql);

mysqli_close($link);
?>
