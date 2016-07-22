<?php
include '../../connection.php';

$sampleName = mysqli_escape_string($link, $_POST["sampleName"]);
$beginDate = mysqli_real_escape_string($link, $_POST["beginDate"]);
$endDate = mysqli_real_escape_string($link, $_POST["endDate"]);

$sampleName = "%".$sampleName."%";

$sampleSql = "SELECT s.sample_ID, s.sample_name
FROM sample s
WHERE s.sample_name LIKE '$sampleName';";
$sampleResult = mysqli_query($link, $sampleSql);



?>
<table class='table table-responsive table-condensed'>
    <thead>
    	<tr>
      		<th>Sample</th>
      		<th>Coating</th>
      		<th>Thickness</th>
      	</tr>
    </thead>
    <tbody>
    <?
    while($sampleRow = mysqli_fetch_array($sampleResult)){
    	echo"
    		<tr>
    			<td><a onclick='loadAndShowSampleModal(".$sampleRow[0].")'>".$sampleRow[1]."</a></td>
    			<td>Coating</td>";
    	$thicknessSql = "SELECT TRUNCATE(AVG(r.anlys_res_result), 3)
              FROM anlys_result r
              WHERE r.sample_ID = '1' AND anlys_eq_prop_ID IN (SELECT anlys_eq_prop_ID
              FROM anlys_eq_prop
              WHERE anlys_prop_ID = '$sampleRow[0]')
              GROUP BY anlys_eq_prop_ID
              ORDER BY anlys_res_ID DESC
              LIMIT 1;";
        $thicknessResult = mysqli_query($link, $thicknessSql);
        $thicknessRow = mysqli_fetch_row($thicknessResult);
       	echo"
    			<td>".$thicknessRow[0]."</td>
    		</tr>";
    }
    ?>

    </tbody>
</table>
<div id="sample_modal" class="modal"></div>
<script>
  var modal = document.getElementById('sample_modal');
  function loadAndShowSampleModal(sampleID){
    loadSampleModal(sampleID);
    modal.style.display = "block";
  }
 </script>




