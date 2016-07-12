<?php
include '../../connection.php';
session_start();

$securityLevel = $_SESSION["securityLevelDA"];
// If the user has chosen to view a specific set when entering page. 
// if(isset($_GET['id'])) {
//   $_SESSION["sampleSetID"] = $_GET['id'] ;
// }
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
      <h5>Some text.</h5>
    </div>
    <div class='row well well-lg'>
      <h3 class='custom_heading'>Choose a sample to analyze</h3>
      <form role='form'>
        <div class='col-md-6 form-group'>
          <label>Sample set: </label>
          <select class='form-control' onchange='updateSamplesInSet()' id='sample_set_ID' style='width:auto;'>
            <option value='-1'>Choose a set</option>
            <?
            while($sampleSetRow = mysqli_fetch_array($recentSampleSetsResult)){
              echo "<option value='".$sampleSetRow[0]."'>".$sampleSetRow[1]."</option>";
            }
            ?>
          </select>
        </div>
        <div id='samples_in_set' class='col-md-6 form-group'>
        </div>

      </form>
    </div>
  </div>
</body>