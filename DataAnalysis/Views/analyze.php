<?php
include '../../connection.php';
session_start();

$securityLevel = $_SESSION["securityLevelDA"];

// If the user security level is not high enough we kill the page and give him a link to the log in page.
if($securityLevel < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}

$sampleID = $_SESSION["sampleID"];
if(!$sampleID){
  $sampleID = "-1";
}
$sampleSetID = $_SESSION["sampleSetID"];
if(!$sampleSetID){
  $sampleSetID = "-1";
}
$propID = $_SESSION["propID"];
if(!$propID){
  $propID = "-1";
}
$eqID = $_SESSION["eqID"];
if(!$eqID){
  $eqID = "-1";
}

$eqWithAnlysResults = [];
$numberOfSetsToDisplay = $_SESSION['numberOfSetsToDisplayInDD'];

$recentSampleSetsSql = "SELECT q.*
FROM(
SELECT sample_set_ID, sample_set_name
FROM sample_set
ORDER BY sample_set_ID DESC LIMIT $numberOfSetsToDisplay ) q
ORDER BY MID(sample_set_name, 5, 6) DESC;";
$recentSampleSetsResult = mysqli_query($link, $recentSampleSetsSql);

$sampleInfoSql = "SELECT sample_name, sample_material, sample_comment
FROM sample
WHERE sample_ID = '$sampleID';";
$sampleInfoResult = mysqli_query($link, $sampleInfoSql);
$sampleInfoRow = mysqli_fetch_row($sampleInfoResult);

$propertiesSql = "SELECT anlys_prop_ID, anlys_prop_name
FROM anlys_property
WHERE anlys_prop_active = TRUE
ORDER BY anlys_prop_name;";
$propertiesResult = mysqli_query($link, $propertiesSql);

$sampleSetNameSql = "SELECT sample_set_name
FROM sample_set
WHERE sample_set_ID = '$sampleSetID';";

// $eqWithAnlysResultsSql = "SELECT DISTINCT(CONCAT(a.anlys_eq_ID, a.anlys_prop_ID))
// FROM anlys_result r, sample s, anlys_eq_prop a
// WHERE r.sample_ID = s.sample_ID AND r.anlys_eq_prop_ID = a.anlys_eq_prop_ID AND r.sample_ID = '$sampleID';";
// $eqWithAnlysResultsResult = mysqli_query($link, $eqWithAnlysResultsSql);
// while ($row = mysqli_fetch_row($eqWithAnlysResultsResult)){
//     array_push($eqWithAnlysResults, $row[0]);
// }

?>

<head>
  <title>Fraunhofer CCD</title>
  <script>
      var bootstrapBlue = "#337AB7";
      var bootstrapDarkBlue = "#23527C";
      var bootstrapPurple = "#5E4485";
  </script>
</head>
<body>
  <?php include '../header.php'; ?>
  <?php echo "<input type='hidden' id='employee_ID' value='".$employee_ID."'>"; ?>
  <div class='container'>
  <div class='row well well-lg'>
  <div class='col-md-12'>
    <form role='form'>
      <div id='error_message'></div>
      <div id='sample_div' class='col-md-12'>
        <h4 class='custom_heading'>1. Choose a sample</h4>
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

      <div class='col-md-12'>
        <h4 id='prop_eq_div' class='custom_heading'>2. Choose a coating property and equipment</h4>
        <?php
      // For easy changing of layout of tables.
        $numTablesPerRow = 4;
        $colSize = 12/$numTablesPerRow;
        $tableCounter = 0;
        while($propertyRow = mysqli_fetch_array($propertiesResult)){
          if($tableCounter % $numTablesPerRow === 0){
            echo"
          </div>
          <div class='col-md-12'>"; 
          }
          $equipmentSql = "SELECT e.anlys_eq_ID, e.anlys_eq_name
          FROM anlys_equipment e, anlys_eq_prop a
          WHERE a.anlys_eq_ID = e.anlys_eq_ID AND a.anlys_prop_ID = '$propertyRow[0]' AND e.anlys_eq_active = TRUE
          ORDER BY e.anlys_eq_name;";
          $eqResult = mysqli_query($link, $equipmentSql);
          if(mysqli_fetch_array($eqResult)){
            echo"
            <table class='col-md-".$colSize."'>
              <thead>
                <tr>
                  <th>".$propertyRow[1]."</th>
                </tr>
              </thead>
              <tbody>";
                $equipmentResult = mysqli_query($link, $equipmentSql);
                while($equipmentRow = mysqli_fetch_array($equipmentResult)){
                  echo"
                  <tr>
                    <td><a id='".$equipmentRow[0].$propertyRow[0]."' onclick='showAnlysResultForm(".$propertyRow[0].",".$equipmentRow[0].",".$sampleID.",this.form)'>".$equipmentRow[1]."</a></td>
                  </tr>";
                  // Color the eq that has analysis results for the chosen sample. 
                  // if(in_array($equipmentRow[0].$propertyRow[0], $eqWithAnlysResults)){
                  //   echo"
                  //     <script>
                  //         console.log('coloring eq with results');
                  //         $('#".$equipmentRow[0].$propertyRow[0]."').css('color', bootstrapPurple);
                  //     </script>";
                  // }
                }
                echo"
              </tbody>
            </table>";
            $tableCounter++;
          }
        }
        ?>
      </div>
      <div class='col-md-12'>
        <h4 class='custom_heading'>3. Enter results</h4>
        <div id='res_div'></div>
      </div>
      <div class='col-md-12'>
        <button type='button' class='btn btn-primary col-md-2' style='float:right;'onclick='location.href="sampleOverview.php"'>Sample Overview</button>
      </div>
  </form>
</div>
</div>
</div>
<script>


  $(document).ready(function(){

    updateSamplesInSet(<?php echo $sampleSetID; ?>);

    $("#nav_analyze").button('toggle');
    // Color the equipment link that is chosen.
    $("#<?php echo $eqID.$propID; ?>").css("color", bootstrapPurple);
    $("#<?php echo $eqID.$propID; ?>").css("text-decoration", "underline");

  })

    // Color the equipment the user chose. 
    $("td a").click(function () { 
      $("td a").css("color", bootstrapBlue);
      $("td a").css("text-decoration", "none");
      $(this).css("color", bootstrapPurple);
      $(this).css("text-decoration", "underline");

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

    // Make the combo box select the currently chosen sample set.
    $("#sample_set_ID").val(<?php echo $sampleSetID; ?>)

    if(<?php echo $propID; ?>){
      if(<?php echo $eqID; ?>){
        showAnlysResultForm(<?php echo $propID; ?>,<?php echo $eqID; ?>);
      }
    }

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }


  </script>
</body>