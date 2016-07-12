<!-- In this view we only display some parts if the security level is high enough -->
<!-- This is the front page -->
<!DOCTYPE html>
<html>
<head>
  <?php
  include "../../connection.php";
  session_start();

  // For when entering addsample.php
  $_SESSION["sampleSetID"] = "-1";
  // Find the current user
  $user = $_SESSION["username"];
  // Find his level of security
  $secsql = "SELECT security_level
  FROM employee
  WHERE employee_name = '$user'";
  $secResult = mysqli_query($link, $secsql);
  while($row = mysqli_fetch_array($secResult)){
    $SecurityLevel = $row[0];
  }
  // Get the third digit from the security level since that digit represents the
  // security level of the data analysis database
  $SecurityLevel = str_split($SecurityLevel);
  $SecurityLevel = $SecurityLevel[2];

  // if the user security level is not high enough we kill the page and give him a link to the log in page
  if($SecurityLevel < 2){
    echo "<a href='../../Login/login.php'>Login Page</a></br>";
    die("You don't have the privileges to view this site.");
  }
  // $processEquipmentSql = "SELECT prcs_eq_ID, prcs_eq_name
  // FROM process_equipment
  // WHERE prcs_eq_active = TRUE;";
  // $processEquipmentResult = mysqli_query($link, $processEquipmentSql);

  // $analysisEquipmentSql = "SELECT anlys_eq_ID, anlys_eq_name, anlys_eq_comment
  // FROM anlys_equipment
  // WHERE anlys_eq_active = TRUE;";
  // $analysisEquipmentResult = mysqli_query($link, $analysisEquipmentSql);

  $recentSampleSetsSql = "SELECT sample_set_ID, sample_set_name
  FROM sample_set
  ORDER BY sample_set_ID DESC LIMIT 3;";


  // $recentSamplesSql = "SELECT sample_ID, sample_name
  // FROM sample
  // ORDER BY sample_ID DESC LIMIT 10;";
  // $recentSamplesResult = mysqli_query($link, $recentSamplesSql);

  ?>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
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
          <button type='button' class='btn btn-primary col-md-12'>Process</button>
        </div>
        <div class='col-md-2'>
          <button type='button' class='btn btn-primary col-md-12'>Analyze</button>
        </div>
        <div class='col-md-2'>
          <button type='button' class='btn btn-primary col-md-12'>Search</button>
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
        <h4 class='center_heading'>".$sampleSetRow[1]."</h4>
        <tbody>";

      $samplesInSetSql = "SELECT sample_ID, sample_name
      FROM sample
      WHERE sample_set_ID = '$sampleSetRow[0]'
      ORDER BY sample_ID;";
      $samplesInSetResult = mysqli_query($link, $samplesInSetSql);

      while($sampleRow = mysqli_fetch_array($samplesInSetResult)){
        echo"
        <tr >
          <td class='col-md-4'>".$sampleRow[1]."</td>
          <td class='col-md-4 text-center'>Coating</td>
          <td class='col-md-4 text-center'>Thickness</td>
        </tr>";
      }
      echo"
        </tbody>
      </table>";
    }
    ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>