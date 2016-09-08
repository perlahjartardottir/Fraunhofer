<?php
include "../../connection.php";

$resID = mysqli_real_escape_string($link, $_POST["resID"]);

include "deleteAnlysFile.php";

$allFilesSql = "SELECT anlys_res_file_ID
FROM anlys_res_file
WHERE anlys_res_ID = $resID;";
$allFilesResult = mysqli_query($link, $allFilesSql);
if(!$allFilesResult){
	die("Could not get files to delete: ".mysqli_error($link));
}
while($resFileIDRow = mysqli_fetch_row($allFilesResult)){
	deleteFile($resFileIDRow[0]);
} 

$sql = "DELETE FROM anlys_result
WHERE anlys_res_ID = '$resID';";
$result = mysqli_query($link, $sql);

if(!$result){
	die("Could not delete analysis result: ".mysqli_error($link));
}

mysqli_close($link);

?>