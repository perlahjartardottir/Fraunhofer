<?php
include '../../connection.php';

$sampleSetName = mysqli_escape_string($link, $_POST["sampleSetName"]);
$sampleSetName = "%".$sampleSetName."%";

$numberOfSamplesToDisplay = 20;

$recentSampleSetsSql = "SELECT sample_set_ID, sample_set_name
FROM sample_set
WHERE sample_set_name LIKE '$sampleSetName'
ORDER BY MID(sample_set_name,5,6) DESC LIMIT $numberOfSamplesToDisplay;";

?>
    <div class='col-md-12'>
    <h2 class='center_heading custom_heading'>Samples</h2>
      <table id='front_table' class='table table-borderless col-md-12'>
       <thead>
        <tr>
          <th class='col-md-4'>Name</th>
          <th class='col-md-4 text-center'>Coating</th>
          <th class='col-md-4 text-center'>Thickness</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
    <!-- Samples -->
    <?
    $recentSampleSetsResult = mysqli_query($link, $recentSampleSetsSql);
    while ($sampleSetRow = mysqli_fetch_array($recentSampleSetsResult)) {
      echo"
      <table id='front_table' class='table table-borderless col-md-12'>
        <tbody>";

          $samplesInSetSql = "SELECT sample_ID, sample_name
          FROM sample
          WHERE sample_set_ID = '$sampleSetRow[0]'
          ORDER BY sample_ID;";
          $samplesInSetResult = mysqli_query($link, $samplesInSetSql);

          while($sampleRow = mysqli_fetch_array($samplesInSetResult)){
            echo"
            <tr >
              <td><a onclick='loadAndShowSampleModal(".$sampleSetRow[0].",".$sampleRow[0].")'>".$sampleRow[1]."</a></td>";

              // Get the latest coating for the sample. 
              $coatingSql = "SELECT prcs_coating
              FROM process
              WHERE sample_ID = '$sampleRow[0]'
              ORDER BY prcs_ID DESC
              LIMIT 1;";
              $coating = mysqli_fetch_row(mysqli_query($link, $coatingSql))[0];
              if($coating){
              echo"
              <td class='col-md-4 text-center'>".$coating."</td>";
              }
              else{
                echo"
                <td class='col-md-4 text-center'>N/A</td>";
              }

              // Get thickness results for each sample. The results are sorted by equipment: Calotte Grinder, Dektak, AFM
              $thicknessSql = "SELECT  r.anlys_res_result, a.anlys_eq_prop_unit
              FROM anlys_result r, anlys_eq_prop a
              WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND r.sample_ID = '$sampleRow[0]' AND a.anlys_eq_prop_ID IN (SELECT aa.anlys_eq_prop_ID
              FROM anlys_eq_prop aa
              WHERE aa.anlys_prop_ID = '1')
              GROUP BY r.anlys_eq_prop_ID
              ORDER BY r.anlys_eq_prop_ID = 5 DESC, r.anlys_eq_prop_ID = 3 DESC, r.anlys_eq_prop_ID = 25 DESC
              LIMIT 1;";
              $thicknessResult = mysqli_query($link, $thicknessSql);
              $thicknessRow = mysqli_fetch_row($thicknessResult);
              if($thicknessRow[0]){
                echo"
                  <td class='col-md-4 text-center'>".$thicknessRow[0]." ".$thicknessRow[1]."</td>";
              }
              else{
                echo"
                  <td class='col-md-4 text-center'>N/A</td>";
              }

            echo"  
              </tr>";
          }
          echo"
        </tbody>
      </table>";
    }
    ?>
  </tbody>
</table>
<div id="sample_modal" class="modal"></div>
</div>
<script>
  var modal = document.getElementById('sample_modal');
  // When user clicks a sample name we set the sampleSetId and sampleID SESSION variables. 
  function loadAndShowSampleModal(sampleSetID, sampleID){
    loadSampleModal(sampleSetID, sampleID);
    modal.style.display = "block";
  }
  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
  </script>