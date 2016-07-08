<?php
include '../../connection.php';
session_start();
// Find the current user.
$user = $_SESSION["username"];
// Find his level of security.
$secsql = "SELECT security_level, employee_ID
FROM employee
WHERE employee_name = '$user'";
$secResult = mysqli_query($link, $secsql);

while($row = mysqli_fetch_array($secResult)){
  $securityLevel = $row[0];
  $employee_ID = $row[1];
}
$securityLevel = str_split($securityLevel);
$securityLevel = $securityLevel[1];
// If the user security level is not high enough we kill the page and give him a link to the log in page.
if($securityLevel < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}

// Find the current chosen sampleID
$sampleSetID = $_SESSION["sampleSetID"];

// $allemployeeSql = "SELECT employee_ID, employee_name
// FROM employee
// ORDER BY employee_name ASC;";
// $allemployeeResult = mysqli_query($link, $allemployeeSql);

$recentSampleSetsSql = "SELECT sample_set_ID, sample_set_name
FROM sample_set
ORDER BY sample_set_ID DESC LIMIT 10;";
$recentSampleSetsResult = mysqli_query($link, $recentSampleSetsSql);
?>

<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <?php echo "<input type='hidden' id='employee_ID' value='".$employee_ID."'>"; ?>
  <div class='container'>
    <div class='row well well-lg'>
      <h5>The sample set name is on the format "CCD-YYMMDD-XX". XX is a running number from 01 and is reset every day.</h5>
    </div>
    <div class='row well well-lg'>
      <h3 class='custom_heading'>Add a new sample</h4>
      <form role='form'>
    <!-- <div class='col-md-4 form-group'>
      <label>Employee: </label>
      <input type='text' list='employees' name='employeeList' id='employeeList' value='' class='col-md-12 form-control'>
      <datalist id="employees">
        <?
         // while($row = mysqli_fetch_array($allemployeeResult)){
         //   echo"<option value='".$row[1]."'></option>";
         // }
        ?>
      </datalist>
    </div>-->
    <div class='col-md-6 form-group'>
      <label>Sample set: </label>
      <select class='form-control' onchange='showSamplesInSetAndRefresh(this.value)' id='sample_set_ID' style='width:auto;'>
        <option value='-1'>New</option>
        <?
        while($sampleSetRow = mysqli_fetch_array($recentSampleSetsResult)){
          echo "<option value='".$sampleSetRow[0]."'>".$sampleSetRow[1]."</option>";
        }
        ?>
      </select>
    </div>
    <div class='col-md-6 form-group'>
      <label>Material: </label>
      <input type='text' id='sample_material' class='form-control'>
    </div> 
              <?php 
      if($sampleSetID === "-1"){
        echo "
        <div class='col-md-6'>
        <label>Sample set name: </label>
        <div>
        <p class='sample_set_name'>CCD - </p>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js'></script>
        <input type='date' id='sample_set_date' class='sample_set_name' value='".date("Y-m-d")."' data-date='' data-date-format='YYMMDD'>
        <p class='sample_set_name'> - XX </p>
        </div>
      </div>
      ";
    }
    else{
      echo"
      <div class='col-md-6'>
      </div>";
    }
    ?>
    <div class='col-md-6 form-group'>
      <label>Comment: </label>
      <textarea id='sample_comment' class='form-control' rows='4'></textarea>
    </div>
    <div class='col-md-12'>
    <button type='button' class='btn btn-primary col-md-2' style='float:right' onclick='addSample()'>Add</button>
    </div>
  </form>
</div>
<!-- SelectPHP/showSamplesInSet.php-->
<div id='samples_in_set'></div>
</div>
<script>

  $("#sample_set_date").on("change", function() {
    this.setAttribute(
      "data-date",
      moment(this.value, "YYYY-MM-DD")
      .format( this.getAttribute("data-date-format"))
      )
  }).trigger("change")

      // Show the samples in the sample set on refresh.
      $(document).ready(function(){
       var sampleSetID = <?php echo $sampleSetID; ?>;
       showSamplesInSet(sampleSetID);
     });

    // Make the dropdown list select the currently chosen sample set on refresh.
    $("#sample_set_ID").val(<?php echo $sampleSetID; ?>)

  </script>
</body>