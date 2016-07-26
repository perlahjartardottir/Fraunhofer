<?php
include '../../connection.php';
session_start();

$securityLevel = $_SESSION["securityLevelDA"];

// if the user security level is not high enough we kill the page and give him a link to the log in page
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


$recentSampleSetsSql = "SELECT sample_set_ID, sample_set_name
FROM sample_set
ORDER BY sample_set_ID DESC LIMIT 10;";
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

$anlysAverageSql = "SELECT r.anlys_eq_prop_ID, e.anlys_eq_name, p.anlys_prop_name, TRUNCATE(AVG(r.anlys_res_result), 3) as avegResult
FROM anlys_result r, anlys_eq_prop a, anlys_equipment e, anlys_property p
WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND a.anlys_eq_ID = e.anlys_eq_ID AND
a.anlys_prop_ID = p.anlys_prop_ID AND r.sample_ID = '$sampleID'
GROUP BY r.anlys_eq_prop_ID;";


?>

<head>
	<title>Fraunhofer CCD</title>
	<link href='../css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
	<?php include '../header.php';?>
	<div class="container">
		<div class='row well well-lg'>
			<div class='col-md-12'>
       <h2 id='sample_overview_heading' class='custom_heading center_heading'>Sample overview</h2>
       <div class='col-md-4 form-group'>
       <!-- Set combo box -->
        <label>Set:<?php echo $sampleSetID;?>  <?php echo $sampleID;?></label>
        <select id='sample_set_ID' class='form-control' onchange='updateSamplesInSetAndRefresh()' style='width:auto;'>
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
      <th >Coating</th>
      <th>Coating property</th>
      <th class='text-left'>Average</th>
      <th>Equipment</th>
      </thead>
      <tbody>";
      $anlysAverageResult = mysqli_query($link, $anlysAverageSql);
      while($averageRow = mysqli_fetch_array($anlysAverageResult)){
        echo"
          <tr>
            <td>Coating</td>
            <td>".$averageRow[2]."</td>
            <td><a onclick='displayAnlysResultTable(".$sampleID.",".$averageRow[0].")'>";
            if($averageRow[avegResult] != 0){
              echo $averageRow[avegResult];
            }
            // If we cannot display the average e.g. for overview and roughness. 
            else{
              echo "N/A";
            }
            echo"
            </a></td>
            <td>".$averageRow[1]."</td>
          </tr>";
      }
    echo"
    </tbody>
    </table>";
    }
    else{
      echo"<p class='table_style_text'>This sample has not been analysed.</p>";
    }
    ?>
    </div>
    <div id='anlys_result_table' class='col-md-12'></div>
    <!-- Process -->
    <div class='col-md-8'>
    <h3 class='custom_heading'>Process</h3>
  </div>
</div>
</div>
</div>
<script>
  $(document).ready(function(){
    updateSamplesInSet(<?php echo $sampleSetID; ?>);
  })
    // Make the combo box select the currently chosen set and sample.
    $("#sample_set_ID").val(<?php echo $sampleSetID; ?>)
    
  </script>
</body>