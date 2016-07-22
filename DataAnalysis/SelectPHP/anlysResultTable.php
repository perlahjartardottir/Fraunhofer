<?php
include '../../connection.php';
session_start();

$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);
$eqPropID = mysqli_real_escape_string($link, $_POST["eqPropID"]);

$propertySql = "SELECT a.anlys_eq_prop_ID, p.anlys_prop_name, e.anlys_eq_name, a.anlys_param_1, a.anlys_param_2, a.anlys_param_3, a.anlys_eq_prop_unit
FROM anlys_property p, anlys_equipment e, anlys_eq_prop a
WHERE a.anlys_eq_ID = e.anlys_eq_ID AND a.anlys_prop_ID = p.anlys_prop_ID
AND a.anlys_eq_prop_ID = '$eqPropID'";
$propertyResult = mysqli_query($link, $propertySql);
$propertyRow = mysqli_fetch_row($propertyResult);

$resultsSql = "SELECT anlys_res_result, anlys_res_date, anlys_res_comment, anlys_res_1, anlys_res_2, anlys_res_3
FROM anlys_result
WHERE sample_ID = '$sampleID' AND anlys_eq_prop_ID = '$eqPropID'
ORDER BY anlys_res_ID;";
$resultsResult = mysqli_query($link, $resultsSql);

echo"
<div class='col-md-12'>

</div>
<table class='table table-responsive'>
<caption>Detailed analysis results for: ".$propertyRow[2]."</caption>
  <thead>
    <tr>
      <th>Date</th>
      <th>".$propertyRow[1];
      // If the property has units display it.
      if($propertyRow[6]){
        echo " (".$propertyRow[6].")";
      }
      echo"
      </th>
      <th>Comment</th>";
      for($i = 3; $i < 6; $i++){
        if($propertyRow[$i]){
          echo"
            <th>".$propertyRow[$i]."</th>";
        }
      }
    echo"
    </tr>
  </thead>
  <tbody>";

    while($resultRow = mysqli_fetch_array($resultsResult)){
      echo"
      <tr>
        <td>".$resultRow[1]."</td>
        <td>".$resultRow[0]."</td>
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
      </tr>";
    }
    echo"
  </tbody>
</table>";

?>