<?php
include '../../connection.php';
session_start();

$securityLevel = $_SESSION["securityLevelDA"];
// If the user security level is not high enough we kill the page and give him a link to the log in page.
if($securityLevel < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}

// If the user has chosen to view a specific set at front page.
if(isset($_GET['id'])) {
  $_SESSION["sampleSetID"] = $_GET['id'] ;
}
$sampleSetID = $_SESSION["sampleSetID"];
if(!$sampleSetID){
  $sampleSetID = "-1";
}

$numberOfSetsToDisplay = $_SESSION['numberOfSetsToDisplayInDD'];
$maxPictureSize = $_SESSION["pictureValidation"]["maxSize"];
$pictureFormats = $_SESSION["pictureValidation"]["formats"];

// $recentSampleSetsSql = "SELECT sample_set_ID, sample_set_name
// FROM sample_set
// ORDER BY MID(sample_set_name, 5, 6) DESC LIMIT $numberOfSetsToDisplay;";

$recentSampleSetsSql = "SELECT q.*
FROM(
SELECT sample_set_ID, sample_set_name
FROM sample_set
ORDER BY sample_set_ID DESC LIMIT $numberOfSetsToDisplay ) q
ORDER BY MID(sample_set_name, 5, 6) DESC;";
$recentSampleSetsResult = mysqli_query($link, $recentSampleSetsSql);

$materialsSql = "SELECT DISTINCT(sample_material)
FROM sample;";
$materialsResult = mysqli_query($link, $materialsSql);

$sampleSetNameSql = "SELECT sample_set_name
FROM sample_set
WHERE sample_set_ID = '$sampleSetID';";

?>

<head>
  <title>Data Analysis</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <?php echo "<input type='hidden' id='employee_ID' value='".$employee_ID."'>"; ?>
  <?php

?>
  <script type="text/javascript">
    window.onload = function() {
      $('input[type=date]').each(function() {
        if  (this.type != 'date' ) $(this).datepicker({
          dateFormat: 'yy-mm-dd'
        });
      });
    };
  </script>
  <div class='container'>
    <div class='row well well-lg'>
      <h5>The set name has the format "CCD-YYMMDD-XX".  XX is a running number from 01 and is reset every day.</h5>
      <h5>To edit a sample, choose it's set then click it's name in the overview table at the bottom of the page.</h5>
    </div>
    <div class='row well well-lg'>
      <h3 class='custom_heading'>Add a sample to a new set or an existing set.</h3>
      <form role='form' action='../InsertPHP/addSample.php' method="post" enctype="multipart/form-data">
    <div class='col-md-6'>
      <div class='col-md-12 form-group'>
        <label>Choose a set (existing or new): </label>
        <select id='sample_set_ID' name='sample_set_ID' class='form-control' onchange='showSamplesInSetAndRefresh(this.value)'  style='width:auto;'>
          <option value='-1'>New</option>
          <?
          while($sampleSetRow = mysqli_fetch_array($recentSampleSetsResult)){
            echo "<option value='".$sampleSetRow[0]."'>".$sampleSetRow[1]."</option>";
          }
          ?>
        </select>

      </div> 
      <?php
    // Adding to a new set.
      if($sampleSetID === "-1"){
        echo "
        <div class='col-md-12 form-group'>
          <label>When was the sample initialized? </label>
          <div>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js'></script>
            <input type='date' id='sample_set_date' name='sample_set_date' class='sample_set_name custom_date form-control' value='".date("Y-m-d")."' data-date='' data-date-format='YYYY-MM-DD'>
          </div>
        </div>
        <div id='sample_set_name_div' class='col-md-12 form-group'></div>";
      }
  // Adding to existing set.
      else{
        $sampleSetNameResult = mysqli_query($link,$sampleSetNameSql);
        $sampleSetNameRow = mysqli_fetch_row($sampleSetNameResult);
        $sampleSetName = $sampleSetNameRow[0];

    // Format: CCD-YYMMDD-XX-NN
    // Get the number for the sample. 
        // $latestSampleNumberSql = "SELECT COUNT(sample_id)
        // FROM sample
        // WHERE sample_set_ID = '$sampleSetID';";
        // $latestSampleNumber = mysqli_fetch_row(mysqli_query($link, $latestSampleNumberSql))[0];
        // $sampleNumber = str_pad(((int)$latestSampleNumber + 1), 2, '0', STR_PAD_LEFT);
        // $sampleName = $sampleSetName."-".$sampleNumber;

        $latestSampleNumberSql = "SELECT MAX(MID(sample_name, 15,2))
        FROM sample
        WHERE sample_set_ID = '$sampleSetID';";
        $latestSampleNumber = mysqli_fetch_row(mysqli_query($link, $latestSampleNumberSql))[0];
        $sampleNumber = str_pad(((int)$latestSampleNumber + 1), 2, '0', STR_PAD_LEFT);
        $sampleName = $sampleSetName."-".$sampleNumber;

        echo"
        <div class='col-md-12 form-group'>
          <label>Preview of sample name: </label>
          <br>
          <p>".$sampleName."</p>
          <input type='hidden' id='sample_name' name='sample_name' value='".$sampleName."'>
        </div>";
      }

      ?>
    </div> <!-- Sample -->
    <div class='col-md-6'>
      <div class='col-md-12 form-group'>
        <label for='material' >Substrate/Base material: </label>
        <input list="materials" id='material' class='col-md-12 form-control'>
        <datalist id="materials">
          <?
          while($row = mysqli_fetch_array($materialsResult)){
            echo"<option data-value='".$row[0]."'>".$row[0]."</option>";
          }
          ?>
        </datalist>
        <input type='hidden' name='material' id='material-hidden'>
      </div>
      <div class='col-md-12 form-group'>
        <label for='sample_comment'>Comment: </label>
        <textarea id='sample_comment' name='sample_comment' class='form-control'></textarea>
      </div>
      <div class='col-md-12 form-group'>
        <label for='sample_picture' style='display:block;'>Picture:</label>
        <label class="btn btn-default btn-file">Browse
          <input type="file" id='sample_picture' name='sample_picture' style='display: none;' accept='image/jpg,image/jpeg,image/png,image/bmp,image/gif,image/tif' onchange='$("#sample_file_path").html(getFileName($(this).val()));'>
        </label>
        <span id="sample_file_path"></span>
      </div>
      <div id='error_message_picture' class='col-md-12'></div>
    </div> <!-- Details -->
    <div class='col-md-12'>
      <button type='submit' class='btn btn-primary col-md-2' style='float:right'>Add sample</button>
<!--       <a href="../DownloadPHP/download.php?id=../readme.pdf">Download the cool PDF.</a> -->
    </div>

  </form>
</div>
<!-- SelectPHP/showSamplesInSet.php -->
<div id='samples_in_set'></div>


</div>
<script>

// Show the samples in the sample set on refresh.
$(document).ready(function(){
 var sampleSetID = <?php echo $sampleSetID; ?>;
 showSamplesInSet(sampleSetID);
 $("#nav_sample").button("toggle");
});

// Format the date input.
$("#sample_set_date").on("change", function(){
  this.setAttribute(
    "data-date",
    moment(this.value, "YYYY-MM-DD")
    .format( this.getAttribute("data-date-format"))
    );
    sampleSetDate = $("#sample_set_date").val().replace(/-/g,"").substring(2,8);
    getNewSampleSetName(sampleSetDate);

}).trigger("change")

// So user can input text as well as choose from a datalist. 
// http://stackoverflow.com/a/29882539
$('input[list]').on('input', function(e) {
  var input = $(e.target),
  options = $('#' + input.attr('list') + ' option'),
  hiddenInput = $('#' + input.attr('id') + '-hidden'),
  label = input.val();

  hiddenInput.val(label);

  for(var i = 0; i < options.length; i++) {
    var option = options.eq(i);

    if(option.text() === label) {
      hiddenInput.val( option.attr('data-value') );
      break;
    }
  }
});

// Picture validation. User can choose to ignore the message, but then the picture will not be uploaded.
$('#sample_picture').bind('change', function() {
  if(this.files[0].size > <?php echo $maxPictureSize?>){
    var errorMessage = "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
              "Sorry, your file is too large. The max size is: " + <?php echo strval(number_format($maxPictureSize/1000/1000,2)); ?> +" MB.</div>";
    $('#error_message_picture').html(errorMessage);
  }
});



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