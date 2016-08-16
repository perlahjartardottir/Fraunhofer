<?php
include '../../connection.php';

function deleteFile($resFileID){
		// To be able to access the global var inside a function.
		global $link;

		// Get the path to the picture. 
		$getFileSql = "SELECT anlys_res_file
		FROM anlys_res_file
		WHERE anlys_res_file_ID = $resFileID;";
		$file = mysqli_fetch_row(mysqli_query($link,$getFileSql))[0];

		// Remove the pathname from database.
		$removeFilePathSql = "DELETE FROM anlys_res_file WHERE anlys_res_file_ID = $resFileID;";
		$removeFilePathResult = mysqli_query($link, $removeFilePathSql);
		if(!$removeFilePathResult){
			echo "resFileID: ".$resFileID;
			die("Could not remove analysis file: ".mysqli_error($link));
		}

		// Remove the picture from the server.
		if(file_exists($file)){
			unlink($file);
		}

		// If the directory is empty, delete it.
		$directory = dirname($file);
		if(is_dir($file)){
			rmdir($file);
		}
}

?>