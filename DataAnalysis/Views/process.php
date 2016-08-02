<?php
include "../../connection.php";
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

$sampleInfoSql = "SELECT sample_name, sample_material, sample_comment
FROM sample
WHERE sample_ID = '$sampleID';";
$sampleInfoResult = mysqli_query($link, $sampleInfoSql);
$sampleInfoRow = mysqli_fetch_row($sampleInfoResult);

$employeeInitialsSql = "SELECT employee_ID, employee_name,
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
ORDER BY initials, employee_name;";
$employeeInitialsResult = mysqli_query($link, $employeeInitialsSql);

$user = $_SESSION["username"];
$userIDSql = "SELECT employee_ID
           FROM employee
           WHERE employee_name = '$user'";
$userID = mysqli_fetch_row(mysqli_query($link, $userIDSql))[0];

?>

<head>
    <title>Fraunhofer CCD</title>
</head>
<body>
    <?php include '../header.php';?>
    <div class="container">
        <div class='row well well-lg'>
            <!-- 1. Choose sample -->
            <div class='col-md-12'>
                <h4 class='custom_heading'>1. Choose a sample</h4>
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
    <!-- 2. Enter process data-->
    <div class='col-md-12'>
        <h4 id='prop_eq_div' class='custom_heading'>2. Enter process information</h4>
        <form role=form>
            <div class='form-group row'>
              <label class='col-xs-2 col-form-label'>Date:</label>
              <div class='col-md-2'>
                <input type='date' id='prcs_date' name='prcs_date' class='custom_date form-control' value='<? echo date("Y-m-d") ?>' data-date='' data-date-format='YYYY-MM-DD'>
            </div>
            <label class='col-xs-2 col-form-label'>Employee:</label>
            <div class='col-md-2'>
                <select id='employee_initials' class='form-control'>";
                    <?
                    while($row = mysqli_fetch_row($employeeInitialsResult)){
                      echo "<option value='".$row[0]."'>".$row[2]."</option>";
                  }
                  ?>
              </select>
              <span id='employee_name' class='table_style_text'></span>
          </div>
      </div>
      <div class='form-group row'>
        <label class='col-xs-2 col-form-label'>Coating: </label>
        <div class='col-md-2'>
            <input type='text' id='prcs_coating' name='prcs_coating' class='form-control' value=''>
        </div>
        <label class='col-xs-2 col-form-label'>Position: </label>
        <div class='col-md-2'>
            <input type='text' id='prcs_position' name='prcs_position' class='form-control' value=''>
        </div>
        <label class='col-xs-2 col-form-label'>Rotation: </label>
        <div class='col-md-2'>
            <input type='number' id='prcs_rotation' name='prcs_rotation' class='form-control' value=''>
        </div>
    </div>
    <div class='form-group row'>
      <label class='col-xs-2 col-form-label'>Comment:</label>
      <div class='col-md-2'>
        <textarea  id='prcs_comment' name='prcs_comment' class='form-control' value=''></textarea>
    </div>
    <label class='col-xs-2 col-form-label'>File: (No functionality) </label>
    <div class='col-md-4'>
        <label class='btn btn-default btn-file'>Browse...
          <input type='file' id='fileToUpload' name='fileToUpload' style='display: none;' onchange='$("#sample_file_path").html(getFileName($(this).val()));'>
      </label>
      <span id='sample_file_path' class='table_style_text'></span>
  </div>
</div>
  <button type='button' class='btn btn-primary col-md-2' onclick='addProcess("<? echo $sampleID; ?>",this.form)' style='float:right'>Add</button>
</form>
</div>
<div id='process_table' class='col-md-12'></div>
  <div class='col-md-12'>
    <button type='button' class='btn btn-primary' style='float:right;'onclick='location.href="sampleOverview.php"'>Sample Overview</button>
  </div>
</div>
</div>
<script>
  $(document).ready(function(){
    updateSamplesInSet(<?php echo $sampleSetID; ?>);
    displayProcessTable(<?php echo $sampleID; ?>);
    $("#nav_process").button('toggle');
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
// Format the date input.
    $('#prcs_date').on('change', function() {
      this.setAttribute(
        'data-date',
        moment(this.value, 'YYYY-MM-DD')
        .format( this.getAttribute('data-date-format'))
        )
    }).trigger('change')

// Make the dropdown list select the currently chosen sample set on refresh.
$("#sample_set_ID").val(<?php echo $sampleSetID; ?>)
// Make tge dropdown list select the currently logged in user.
$("#employee_initials").val(<?php echo $userID; ?>);

</script>
</body>
