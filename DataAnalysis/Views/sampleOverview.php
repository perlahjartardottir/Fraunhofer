<?php
include '../../connection.php';
session_start();
$_SESSION["direct"]["redirect"] = "sampleOverview";
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


$numberOfSamplesToDisplay = $_SESSION["numberOfSetsToDisplayInDD"];
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

$sampleInfoSql = "SELECT sample_name, sample_material, sample_comment, sample_picture
FROM sample
WHERE sample_ID = '$sampleID';";
$sampleInfoResult = mysqli_query($link, $sampleInfoSql);
$sampleInfoRow = mysqli_fetch_row($sampleInfoResult);

// $anlysAverageSql = "SELECT r.anlys_eq_prop_ID as eqPropID, e.anlys_eq_ID as eqID, e.anlys_eq_name as eqName, p.anlys_prop_ID as propID,
//                     a.anlys_eq_prop_unit as unit, p.anlys_prop_name as propName, TRUNCATE(AVG(r.anlys_res_result), 3) as avegResult, a.anlys_aveg as dispAveg, COUNT(r.anlys_res_ID) as numberOfResults, r.anlys_res_result as singleResult, a.anlys_param_1_unit as param1unit, a.anlys_param_2_unit as param2unit, a.anlys_param_3_unit as param3unit, anlys_res_ID as resID
// FROM anlys_result r, anlys_eq_prop a, anlys_equipment e, anlys_property p
// WHERE r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND a.anlys_eq_ID = e.anlys_eq_ID AND
// a.anlys_prop_ID = p.anlys_prop_ID AND r.sample_ID = '$sampleID'
// GROUP BY r.anlys_eq_prop_ID;";

$sampleSetNameSql = "SELECT sample_set_name
FROM sample_set
WHERE sample_set_ID = '$sampleSetID';";

?>

<head>
	<title>Data Analysis</title>
<!-- 	<link href='../css/bootstrap.min.css' rel='stylesheet'> -->
</head>
<body>
  <script type="text/javascript">
    window.onload = function() {
      $('input[type=date]').each(function() {
        if  (this.type != 'date' ) $(this).datepicker({
          dateFormat: 'yy-mm-dd'
        });
      });
    };
  </script>
	<?php include '../header.php';?>
	<div class="container">
		<div class='row well well-lg'>
			<div class='col-md-12'>
       <h2 id='sample_overview_heading' class='custom_heading center_heading'>Sample overview</h2>
       <div class='col-md-3 form-group'>
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
      <div id='samples_in_set' class='col-md-3 form-group'></div>
      <!-- Sample info -->
      <div class='col-md-3 form-group'>
        <p><strong>Material: </strong><?php echo $sampleInfoRow[1]; ?></p>
        <p><strong>Comment: </strong><?php echo $sampleInfoRow[2]; ?></p>
      </div>
       <!-- Sample Picture -->
      
      <?
      if($sampleInfoRow[3]){
        echo"
          <div class='col-md-3 form-group'>
            <img id='sample_picture_thumbnail' src='".$sampleInfoRow[3]."' class='img-responsive img-thumbnail' alt='Sample picture' onclick='window.open(\"samplePicture.php?id=".$sampleRow[0]."\")'>
          </div>";
      }

      ?>
    </div>
    <!-- Process -->
    <div class='col-md-12'>
      <h3 class='custom_heading'>Coatings</h3>
    </div>
    <div id='process_table' class='col-md-12'></div>
     <!-- Analysis -->
     <div class=col-md-12>
      <h3 class='custom_heading'>Analysis</h3>
    </div>
    <div id='analysis_overview_table'></div>
<?
  include '../SelectPHP/anlysOverviewTable.php';
?>

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