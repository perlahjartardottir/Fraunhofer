<?php
include '../../connection.php';
session_start();

$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);
$eqPropID = mysqli_real_escape_string($link, $_POST["eqPropID"]);
$prcsID = mysqli_real_escape_string($link, $_POST["prcsID"]);
$coatingName = "";


$propertySql = "SELECT a.anlys_eq_prop_ID as eqPropID, p.anlys_prop_name as propName, e.anlys_eq_name as eqName, a.anlys_param_1 as param1, a.anlys_param_2 as param2, a.anlys_param_3 as param3, a.anlys_param_1_unit as param1unit, a.anlys_param_2_unit as param2unit, a.anlys_param_3_unit as param3unit, a.anlys_eq_prop_unit as unit, a.anlys_prop_ID as propID, a.anlys_aveg as dispAveg
FROM anlys_property p, anlys_equipment e, anlys_eq_prop a
WHERE a.anlys_eq_ID = e.anlys_eq_ID AND a.anlys_prop_ID = p.anlys_prop_ID
      AND a.anlys_eq_prop_ID = '$eqPropID';";
$propertyResult = mysqli_query($link, $propertySql);
$propertyRow = mysqli_fetch_array($propertyResult);

// From anlysOverviewTable: Cannot send null with function to javascript,  therefor -1.
if($prcsID === '-1'){
   $resultsSql = "SELECT anlys_res_result as result, anlys_res_date as date, anlys_res_comment as comment, anlys_res_1, anlys_res_2, anlys_res_3, employee_ID as employee, anlys_res_ID as resID, prcs_ID as prcsID
  FROM anlys_result
  WHERE anlys_eq_prop_ID = '$eqPropID' AND sample_ID = '$sampleID' AND prcs_ID IS NULL
  ORDER BY anlys_res_ID;";

  $coatingName = "No coating";
}
else{
  $resultsSql = "SELECT anlys_res_result as result, anlys_res_date as date, anlys_res_comment as comment, anlys_res_1, anlys_res_2, anlys_res_3, employee_ID as employee, anlys_res_ID as resID
  FROM anlys_result
  WHERE anlys_eq_prop_ID = '$eqPropID' AND sample_ID = '$sampleID' AND prcs_ID = '$prcsID'
  ORDER BY anlys_res_ID;";

  $coatingNameSql = "SELECT prcs_coating
  FROM process
  WHERE prcs_ID = '$prcsID';";
  $coatingName = mysqli_fetch_row(mysqli_query($link, $coatingNameSql))[0];
}
$resultsResult = mysqli_query($link, $resultsSql);


echo"

<!-- For displayAnlysResultTable to know what div is being displayed -->
<input type='hidden' id='eqPropID_hidden' value='".$eqPropID."'>
<input type='hidden' id='prcsID_hidden' value='".$prcsID."'>

<div class='col-md-12'>
</div>
<table class='table table-responsive'>
<caption>Results for: ".$coatingName." - ".$propertyRow['propName']." - ".$propertyRow['eqName']."</caption>
  <thead>
    <tr>
      <th></th>
      <th>Date</th>
      <th>Employee</th>";
      // Only display anlys_res_result if we should calc average or if it has units e.g. adhesion.
      if($propertyRow[dispAveg] || $propertyRow['unit']){
              echo"
      <th>".$propertyRow['propName'];
      // If the property has units display it.
      if($propertyRow['unit']){
        echo " (".$propertyRow['unit'].")";
      }
      echo"
      </th>";
      }
      echo"
      <th>Comment</th>";
      for($i = 3; $i < 6; $i++){
        if($propertyRow[$i]){
          echo"
            <th>".$propertyRow[$i];
            if($propertyRow[$i+3]){
              echo"
              (".$propertyRow[$i + 3].")";
            }
            echo"
              </th>";
        }
      }
    echo"
      <th>File</th>
    </tr>
  </thead>
  <tbody>";

    while($resultRow = mysqli_fetch_array($resultsResult)){

    $employeeInitialsSql = "SELECT 
      CONCAT_WS('',
        SUBSTRING(employee_name, 1, 1),
        CASE WHEN LENGTH(employee_name)-LENGTH(REPLACE(employee_name,' ',''))>2 THEN
          LEFT(SUBSTRING_INDEX(employee_name, ' ', -3), 1)
        END,
        CASE WHEN LENGTH(employee_name)-LENGTH(REPLACE(employee_name,' ',''))>1 THEN
          LEFT(SUBSTRING_INDEX(employee_name, ' ', -2), 1)
        END,
        CASE WHEN LENGTH(employee_name)-LENGTH(REPLACE(employee_name,' ',''))>0 THEN
          LEFT(SUBSTRING_INDEX(employee_name, ' ', -1), 1)
        END) as initials
    FROM employee
    WHERE employee_ID = '$resultRow[6]';";
    $employeeInitials = mysqli_fetch_row(mysqli_query($link, $employeeInitialsSql))[0];

    $resID = $resultRow['resID'];
    $anlysFilesSql = "SELECT anlys_res_file_ID, anlys_res_file
    FROM anlys_res_file
    WHERE anlys_res_ID = '$resID';";
    $anlysFilesResult = mysqli_query($link, $anlysFilesSql);

      echo"
      <tr>
        <td><a class='glyphicon glyphicon-edit' onclick='loadAndShowAnlysResultModalEdit(".$resultRow['resID'].",".$eqPropID.")'></a></td>
        <td>".$resultRow[1]."</td>
        <td>".$employeeInitials."</td>";
      if($propertyRow[dispAveg] || $propertyRow['unit']){
        echo"
        <td>".$resultRow[0]."</td>";
      }
      echo"
        <td>".$resultRow[2]."</td>";
        for($i = 3; $i < 6; $i++){
        if($propertyRow[$i]){
            echo"
              <td>";
            if($resultRow[$i]){
              echo $resultRow[$i];
            }
            echo"
            </td>";
        }
      }
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
      </td>
      </tr>";
    }
    echo"
  </tbody>
</table>
 <div id='anlys_result_modal_edit' class='modal'></div>";

// Average calculations displayed below table. 
if($propertyRow['dispAveg']){
  if($prcsID === '-1'){
    $avegSql = "SELECT TRUNCATE(AVG(anlys_res_result), 3)
    FROM anlys_result
    WHERE sample_ID = '$sampleID' AND anlys_eq_prop_ID = '$eqPropID' AND prcs_ID IS NULL
    GROUP BY anlys_eq_prop_ID, prcs_ID;";
  }
  else{
    $avegSql = "SELECT TRUNCATE(AVG(anlys_res_result), 3)
    FROM anlys_result
    WHERE sample_ID = '$sampleID' AND anlys_eq_prop_ID = '$eqPropID' AND prcs_ID = '$prcsID'
    GROUP BY anlys_eq_prop_ID, prcs_ID;";
  }
    $avegResult = mysqli_fetch_row(mysqli_query($link, $avegSql))[0];

    echo"
      <p class='table_style_text'><span class='table_style_text_bold'>Average: </span>".$avegResult." ".$propertyRow['unit']."</p>";
}
// If the property is roughness.
if($propertyRow['propID'] === '2'){
    $roughnessSql = "SELECT TRUNCATE(AVG(anlys_res_1), 3) as avegResParam1, TRUNCATE(AVG(anlys_res_2), 3) as avegResParam2
    FROM anlys_result
    WHERE sample_ID = '$sampleID' AND anlys_eq_prop_ID = '$eqPropID'
    GROUP BY anlys_eq_prop_ID;";
    $roughnessRow = mysqli_fetch_array(mysqli_query($link, $roughnessSql));
    $ra = $roughnessRow['avegResParam1'];
    $rz = $roughnessRow['avegResParam2'];

    echo"
      <p class='table_style_text_bold'>Average: </p><p class='table_style_text'>Ra: ".$ra." ".$propertyRow['param1unit'].", Rz: ".$rz." ".$propertyRow['param2unit']."</p>";
}
?>
<script>

  // For the modal window to edit analysis results.
  var modal = document.getElementById('anlys_result_modal_edit');
  function loadAndShowAnlysResultModalEdit(resID, eqPropID){
    loadAnlysResultModalEdit(resID, eqPropID);
    modal.style.display = "block";
  }
  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }

</script>