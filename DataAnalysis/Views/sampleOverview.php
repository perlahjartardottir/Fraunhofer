<?php
include '../../connection.php';
include '../GlobalsPHP/properties.php';
session_start();


$securityLevel = $_SESSION["securityLevelDA"];

// If the user security level is not high enough we kill the page and give him a link to the log in page.
if($securityLevel < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}

$sampleSetID = $_SESSION["sampleSetID"];
if(!$sampleSetID){
  $sampleSetID = "-1";
}

$sampleID = $_SESSION["sampleID"];
if(!$sampleID){
  $sampleID = "-1";
}


$numberOfSamplesToDisplay = 20;
$recentSampleSetsSql = "SELECT sample_set_ID, sample_set_name
FROM sample_set
ORDER BY MID(sample_set_name, 5, 6) DESC LIMIT $numberOfSamplesToDisplay;";
$recentSampleSetsResult = mysqli_query($link, $recentSampleSetsSql);
$recentSampleSetsResult = mysqli_query($link, $recentSampleSetsSql);

$samplesInSetSql = "SELECT sample_ID, sample_name
FROM sample
WHERE sample_set_ID = '$sampleSetID'
ORDER BY sample_ID;";
$samplesInSetResult = mysqli_query($link, $samplesInSetSql);

$sampleInfoSql = "SELECT sample_name, sample_material, sample_comment
FROM sample
WHERE sample_ID = '$sampleID';";
$sampleInfoResult = mysqli_query($link, $sampleInfoSql);
$sampleInfoRow = mysqli_fetch_row($sampleInfoResult);

$anlysAverageSql = "SELECT r.anlys_eq_prop_ID as eqPropID, e.anlys_eq_ID as eqID, e.anlys_eq_name as eqName, p.anlys_prop_ID as propID,
                    a.anlys_eq_prop_unit as unit, p.anlys_prop_name as propName, TRUNCATE(AVG(r.anlys_res_result), 3) as avegResult, a.anlys_aveg as dispAveg, COUNT(r.anlys_res_ID) as numberOfResults, r.anlys_res_result as singleResult, a.anlys_param_1_unit as param1unit, a.anlys_param_2_unit as param2unit, a.anlys_param_3_unit as param3unit
FROM anlys_result r, anlys_eq_prop a, anlys_equipment e, anlys_property p
WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND a.anlys_eq_ID = e.anlys_eq_ID AND
a.anlys_prop_ID = p.anlys_prop_ID AND r.sample_ID = '$sampleID'
GROUP BY r.anlys_eq_prop_ID;";

$sampleSetNameSql = "SELECT sample_set_name
FROM sample_set
WHERE sample_set_ID = '$sampleSetID';";

?>

<head>
	<title>Fraunhofer CCD</title>
<!-- 	<link href='../css/bootstrap.min.css' rel='stylesheet'> -->
</head>
<body>
	<?php include '../header.php';?>
	<div class="container">
		<div class='row well well-lg'>
			<div class='col-md-12'>
       <h2 id='sample_overview_heading' class='custom_heading center_heading'>Sample overview</h2>
       <div class='col-md-4 form-group'>
       <!-- Set combo box -->
        <label>Set:</label>
        <select id='sample_set_ID' class='form-control' onchange='updateSamplesInSetAndRefresh(this.value)' style='width:auto;'>
          <option value='-1'>Choose a set</option>
          <?
          while($sampleSetRow = mysqli_fetch_array($recentSampleSetsResult)){
            echo "<option value='".$sampleSetRow[0]."'>".$sampleSetRow[1]."</option>";
          }
          ?>
        </select>
      </div>
       <!-- Sample combo box -->
      <div id='samples_in_set' class='col-md-4 form-group'></div>
      <!-- Sample info -->
      <div class='col-md-4 form-group'>
        <p><strong>Material: </strong><?php echo $sampleInfoRow[1]; ?></p>
        <p><strong>Comment: </strong><?php echo $sampleInfoRow[2]; ?></p>
      </div>
    </div>
    <!-- Analysis -->
    <div class='col-md-8'>
      <h3 class='custom_heading'>Analysis</h3>
      <?
      $anlysResult = mysqli_query($link, $anlysAverageSql);
      if(mysqli_fetch_array($anlysResult)){

      echo"
      <table class='table table-responsive'>
      <thead>
        <th>Coating (Not ready)</th>
        <th>Coating property</th>
        <th class='text-left'>Measurement</th>
        <th>Equipment</th>
      </thead>
      <tbody>";
      $anlysAverageResult = mysqli_query($link, $anlysAverageSql);
      while($row = mysqli_fetch_array($anlysAverageResult)){
        echo"
          <tr>
            <td>Coating</td>
            <td>".$row['propName']."</td>
            <td><a onclick='displayAnlysResultTable(".$sampleID.",".$row[0].")'>";
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

              // TO DO: UNITS!

              echo "Ra: ".$ra." ".$row['param1unit'].", Rz: ".$rz." ".$row['param2unit'];
            }
            else{
              echo "N/A";
            }
            echo"
            </a></td>
            <td>".$row['eqName']."</td>
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
    <!-- Process -->
    <div class='col-md-8'>
    <h3 class='custom_heading'>Process</h3>
  </div>
  <div id='process_table' class='col-md-12'></div>
</div>
</div>
</div>
<script>
  $(document).ready(function(){
    updateSamplesInSet(<?php echo $sampleSetID; ?>);
    displayProcessTable(<?php echo $sampleID; ?>);
  $("#nav_overview").button("toggle");
  })

// Check if the user enters with a set that exists in the dropd down. 
var exists = false;
$('#sample_set_ID option').each(function(){
    if (this.value == '<?php echo $sampleSetID; ?>') {
        exists = true;
    }
});
// If the down does not contain the set, add it to the drop down. 
if(!exists){
  <?
    $sampleSetName = mysqli_fetch_row(mysqli_query($link,$sampleSetNameSql))[0];
  ?>
  $('#sample_set_ID').append($('<option>', {
      value: <?php echo $sampleSetID; ?>,
      text: '<?php echo $sampleSetName; ?>'
  }));
}

// Make the dropdown list select the currently chosen sample set on refresh.
$("#sample_set_ID").val(<?php echo $sampleSetID; ?>)
    
</script>
</body>