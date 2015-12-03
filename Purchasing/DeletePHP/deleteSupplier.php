<?php
include '../../connection.php';
$supplier_ID = mysqli_real_escape_string($link, $_POST['supplier_ID']);

// delete the supplier that has this ID
// Right now this does not work for suppliers that have POs or ratings linked to them
// since we would have to delete those POs and that might not be what we want.
$sql = "DELETE FROM supplier
				WHERE supplier_ID ='$supplier_ID';";
$result = mysqli_query($link, $sql);
if(!$result){
	die("Could not delete supplier: ".mysqli_error($link));
}

mysqli_close($link);
?>
