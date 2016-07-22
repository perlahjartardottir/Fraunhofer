<!DOCTYPE html>
<html>
<head>
  <?php
  include "../../connection.php";
  session_start();

$securityLevel = $_SESSION["securityLevelDA"];
// iI the user security level is not high enough we kill the page and give him a link to the log in page.
if($securityLevel < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}

  $recentSampleSetsSql = "SELECT sample_set_ID, sample_set_name
  FROM sample_set
  ORDER BY sample_set_ID DESC LIMIT 5;";

  ?>
<head>
<title>Fraunhofer CCD</title>
</head>
<body>
  <?php include "../header.php";?>
  <div class="container">
    <div class='row well well-lg'>
      <div class='col-md-12 col-md-offset-1'>
        <div class='col-md-2'>
          <button type='button' class='btn btn-primary col-md-12' onclick="location.href='addSample.php'">Add sample</button>
        </div>
        <div class='col-md-2'>
          <button type='button' class='btn btn-primary col-md-12' onclick="location.href=''">Process</button>
        </div>
        <div class='col-md-2'>
          <button type='button' class='btn btn-primary col-md-12' onclick="location.href='analyze.php'">Analyze</button>
        </div>
        <div class='col-md-2'>
          <button type='button' class='btn btn-primary col-md-12' onclick="location.href='search.php'">Search</button>
        </div>
        <div class='col-md-2 btn-group'>
          <button type='button' class='btn btn-primary' onclick="location.href='overview.php'">Overview</button>
          <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
            <span class='caret'></span>
            <span class='sr-only'>Toggle Dropdown</span>
          </button>
          <ul class='dropdown-menu' role='menu'>
            <li><a href='viewAnalysisEquipment.php'>Analysis equipment</a></li>
            <li><a href='../../Tooling/Views/viewAllMachines.php'>Process equipment</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class='col-md-12'>
      <h2 class='custom_heading center_heading'>Sample sets</h2>
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
    <?
    $recentSampleSetsResult = mysqli_query($link, $recentSampleSetsSql);
    while ($sampleSetRow = mysqli_fetch_array($recentSampleSetsResult)) {
      echo"
      <table id='front_table' class='table table-borderless col-md-12'>
        <h4 class='center_heading'><a href='addSample.php?id=".$sampleSetRow[0]."'>".$sampleSetRow[1]."</a></h4>
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

</div>
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
</body>