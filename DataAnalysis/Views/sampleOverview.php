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
			<h3 class='custom_heading center_heading'>Sample overview</h3>
			<div class='col-md-4 form-group'>
        <label>Set:</label>
        <select id='sample_set_ID' class='form-control' onchange='updateSamplesInSetAndRefresh()' style='width:auto;'>
          <option value='-1'>Choose a set</option>
          <?
          while($sampleSetRow = mysqli_fetch_array($recentSampleSetsResult)){
            echo "<option value='".$sampleSetRow[0]."'>".$sampleSetRow[1]."</option>";
          }
          ?>
        </select>
      </div>
      <div id='samples_in_set' class='col-md-4 form-group'></div>
      <!-- <div id='sample_info' class='col-md-4 form-group'></div> -->
      <div class='col-md-4 form-group'>
        <p><strong>Material: </strong><?php echo $sampleInfoRow[1]; ?></p>
        <p><strong>Comment: </strong><?php echo $sampleInfoRow[2]; ?></p>
        </div>
      </div>
    </div>

			</div>
		</div>
	</div>
  <script>
    $(document).ready(function(){
    updateSamplesInSet(<?php echo $sampleSetID; ?>);
  })
    // Make the combo box select the currently chosen set adn sample.
    $("#sample_set_ID").val(<?php echo $sampleSetID; ?>)
    $('#sample_ID').val(<?php echo $sampleID; ?>);
  </script>
</body>