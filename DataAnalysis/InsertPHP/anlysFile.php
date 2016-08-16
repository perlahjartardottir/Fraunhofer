<?php
include '../../connection.php';
session_start();

$maxFileSize = $_SESSION["fileValidation"]["maxSize"];
$uploadOk = 1;

$file = "";
if($action === "edit"){
	$file = "anlys_file_edit";
}
else if ($action === "insert"){
	$file = "anlys_file";
}

// If the user is uploading a file.
$uploadCounter = 0;
while($_FILES[$file]["name"][$uploadCounter]){

	$sampleNameSql = "SELECT sample_name
	FROM sample
	WHERE sample_ID = '$sampleID';";
	$sampleName = mysqli_fetch_row(mysqli_query($link, $sampleNameSql))[0];

	$eqNameSql = "SELECT REPLACE(e.anlys_eq_name, ' ', '')
	FROM anlys_equipment e, anlys_eq_prop a
	WHERE e.anlys_eq_ID = a.anlys_eq_ID AND a.anlys_eq_prop_ID = '$eqPropID';";
	$eqName = mysqli_fetch_row(mysqli_query($link, $eqNameSql))[0];

	// Build the path
	$targetDir = "../../../Fraunhofer Uploads/Data Analysis/".date("Y")."/".date("m")."/".$sampleName."/";
	// If the folder does not exist create it.
	if (!file_exists($targer_dir)){
		mkdir($targetDir, 0777, true);
	}
	$temp = explode(".", $_FILES[$file]["name"][$uploadCounter]);
	$newName = $sampleName."_".$eqName.".".end($temp);
	$targetFile = $targetDir.$newName;

 	// If this file already exists we don't want to overrite it, but rather add an extension to it's name e.g. fileName(1).pdf
	$fileCounter = 1;
	$newPath = $targetFile;
	while(file_exists($newPath)){
		$newPieces = explode(".", $targetFile);
		$frontpath = str_replace('.'.end($newPieces),'',$targetFile);
		$newPath = $frontpath."(".$fileCounter.").".end($newPieces);
		$targetFile = $newPath;
		$fileCounter++;
	}

	if ($_FILES[$file]["size"][$uploadCounter] > $maxFileSize) {
		$uploadOk = 0;
	}
	if($uploadOk == 1){
		if (move_uploaded_file($_FILES[$file]["tmp_name"][$uploadCounter], $targetFile)) {

			$sql = "INSERT INTO anlys_res_file (anlys_res_ID, anlys_res_file) VALUES ('$resID','$targetFile');";
			$result = mysqli_query($link, $sql);

			if(!$result){
				die("Could not insert anlys result file: ".mysqli_error($link));
			}

		}
	}

	$uploadCounter++;

}

?>