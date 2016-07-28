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
              <td><a onclick='loadAndShowSampleModal(".$sampleRow[0].")'>".$sampleRow[1]."</a></td>
              <td class='col-md-4 text-center'>Coating</td>";

              $thicknessSql = "SELECT TRUNCATE(AVG(anlys_res_result), 3)
              FROM anlys_result
              WHERE sample_ID = '$sampleRow[0]' AND anlys_eq_prop_ID IN (SELECT anlys_eq_prop_ID
              FROM anlys_eq_prop
              WHERE anlys_prop_ID = '1')
              GROUP BY anlys_eq_prop_ID
              ORDER BY anlys_res_ID DESC
              LIMIT 1;";
              $thicknessResult = mysqli_query($link, $thicknessSql);
              $thicknessRow = mysqli_fetch_row($thicknessResult);
              if($thicknessRow[0]){
                echo"
                  <td class='col-md-4 text-center'>".$thicknessRow[0]."</td>";
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
  function loadAndShowSampleModal(sampleID){
    loadSampleModal(sampleID);
    modal.style.display = "block";
  }
  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
  </script>