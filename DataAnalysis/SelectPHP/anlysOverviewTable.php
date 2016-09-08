<?php
include '../../connection.php';
session_start();

$sampleID = $_SESSION["sampleID"];

// $anlysAverageSql = "SELECT r.anlys_eq_prop_ID as eqPropID, e.anlys_eq_ID as eqID, e.anlys_eq_name as eqName, p.anlys_prop_ID as propID,
//                     a.anlys_eq_prop_unit as unit, p.anlys_prop_name as propName, TRUNCATE(AVG(r.anlys_res_result), 3) as avegResult, a.anlys_aveg as dispAveg, COUNT(r.anlys_res_ID) as numberOfResults, r.anlys_res_result as singleResult, a.anlys_param_1_unit as param1unit, a.anlys_param_2_unit as param2unit, a.anlys_param_3_unit as param3unit, anlys_res_ID as resID
// FROM anlys_result r, anlys_eq_prop a, anlys_equipment e, anlys_property p
// WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND a.anlys_eq_ID = e.anlys_eq_ID AND
// a.anlys_prop_ID = p.anlys_prop_ID AND r.sample_ID = '$sampleID'
// GROUP BY r.anlys_eq_prop_ID;";

$anlysAverageSql = "SELECT r.anlys_eq_prop_ID as eqPropID, e.anlys_eq_ID as eqID, e.anlys_eq_name as eqName, p.anlys_prop_ID as propID,
a.anlys_eq_prop_unit as unit, p.anlys_prop_name as propName, TRUNCATE(AVG(r.anlys_res_result), 3) as avegResult,
a.anlys_aveg as dispAveg, COUNT(r.anlys_res_ID) as numberOfResults, r.anlys_res_result as singleResult,
a.anlys_param_1_unit as param1unit, a.anlys_param_2_unit as param2unit, a.anlys_param_3_unit as param3unit,
anlys_res_ID as resID, r.prcs_ID as prcsID
FROM anlys_result r, anlys_eq_prop a, anlys_equipment e, anlys_property p
WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND a.anlys_eq_ID = e.anlys_eq_ID AND
a.anlys_prop_ID = p.anlys_prop_ID AND r.sample_ID = '$sampleID'
GROUP BY r.anlys_eq_prop_ID, r.prcs_ID
ORDER BY r.prcs_ID DESC;";
?>

<div class='col-md-12'>
<?
$anlysResult = mysqli_query($link, $anlysAverageSql);
if(mysqli_num_rows($anlysResult)){

	echo"
	<table id='anlys_overview_table' class='table table-responsive table-hover'>
		<thead>
			<th>Coating</th>
			<th>Coating property</th>
			<th class='text-left'>Measurement</th>
			<th>Equipment</th>
			<th>Files</th>
		</thead>
		<tbody>";
	while($row = mysqli_fetch_array($anlysResult)){
			
		$resID = $row['resID'];
		$prcsID = $row['prcsID'];

    	// We cannot join process table with anlys_result table on prcs_ID because it can be null.
		$coatingNameSql = "SELECT prcs_coating
		FROM process
		WHERE prcs_ID = '$prcsID';";
		if($prcsID) {
			$coatingName = mysqli_fetch_row(mysqli_query($link, $coatingNameSql))[0];
		}
		else{
			$coatingName = "No coating";
			$prcsID = -1;
		}

		echo"
		<tr onclick='displayAnlysResultTable(".$sampleID.",".$row[0].",".$prcsID.",this)'>
			<td >".$coatingName."</td>
			<td>".$row['propName']."</td>
			<td>";
				// If this eqprop should display avegs and the aveg is not 0.
				if($row[avegResult] && $row['dispAveg']){
					echo $row[avegResult]." ".$row['unit'];
				}
				// If the property is adhesion and we have a result, display one value.
				else if($row['propID'] == '4' && $row['numberOfResults']){
					echo $row['singleResult']." ".$row['unit'];
				}
				// If the property is roughness display avegs for Ra (param1) and Rz (param2).
				else if($row['propID'] == '2'){
					$roughnessSql = "SELECT TRUNCATE(AVG(anlys_res_1), 3) as avegResParam1, TRUNCATE(AVG(anlys_res_2), 3) as avegResParam2
					FROM anlys_result
					WHERE sample_ID = '$sampleID' AND anlys_eq_prop_ID = $row[0]
					GROUP BY anlys_eq_prop_ID;";
					$roughnessRow = mysqli_fetch_array(mysqli_query($link, $roughnessSql));
					$ra = $roughnessRow[0];
					$rz = $roughnessRow[1];
					echo "Ra: ".$ra." ".$row['param1unit'].", Rz: ".$rz." ".$row['param2unit'];
				}
				else{
					echo "N/A";
				}
				echo"
			</td>
			<td>".$row['eqName']."</td>";

			$anlysFilesSql = "SELECT anlys_res_file_ID, anlys_res_file
			FROM anlys_res_file
			WHERE anlys_res_ID = '$resID';";
			$anlysFilesResult = mysqli_query($link, $anlysFilesSql);
			echo"
			<td>";
				if(mysqli_num_rows($anlysFilesResult) > 0){
					$fileCounter = 1;
					while($fileRow = mysqli_fetch_row($anlysFilesResult)){
						echo"
						<a href='../DownloadPHP/downloadAnlysFile.php?id=".$fileRow[1]."'>".$fileCounter."</a> ";
						$fileCounter++;
					}
				}
				else{
					echo"
					No";
				}
				echo"
			</td>";
			echo"  
		</tr>";
	}

	echo"
</tbody>
</table>";
}
else{
	echo"<p class='table_style_text'>This sample has not been analyzed.</p>";
}

?>

</div>
<div id='anlys_result_table' class='col-md-12'></div>




