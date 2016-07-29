<?php
include '../../connection.php';
session_start();

$sampleID = $_SESSION["sampleID"];
$propID = mysqli_real_escape_string($link, $_POST["propID"]);
$_SESSION["propID"] = $propID;
$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);
$_SESSION["eqID"] = $eqID;
$eqPropID = "-1";

if($propID !== "-1" && $eqID !== "-1"){

  $propertySql = "SELECT a.anlys_eq_prop_ID, p.anlys_prop_name, e.anlys_eq_name, a.anlys_param_1, a.anlys_param_2, a.anlys_param_3, a.anlys_eq_prop_unit
  FROM anlys_property p, anlys_equipment e, anlys_eq_prop a
  WHERE a.anlys_eq_ID = e.anlys_eq_ID AND a.anlys_prop_ID = p.anlys_prop_ID
  AND p.anlys_prop_ID = '$propID' AND e.anlys_eq_ID = '$eqID';";
  $propertyResult = mysqli_query($link, $propertySql);
  $propertyRow = mysqli_fetch_array($propertyResult);
  $eqPropID = $propertyRow[0];

  $resultsSql = "SELECT anlys_res_result, anlys_res_comment, anlys_res_date, anlys_res_1, anlys_res_2, anlys_res_3
  FROM anlys_result
  WHERE sample_ID = '$sampleID' AND anlys_eq_prop_ID = '$propertyRow[0]'
  ORDER BY anlys_res_ID;";
  $resultsResult = mysqli_query($link, $resultsSql);

// Find the ID of properties where we don't need a number input field for anlys_result.
  $noPropResultSql = "SELECT anlys_prop_ID
  FROM anlys_property
  WHERE anlys_prop_name LIKE 'Overview' OR anlys_prop_name LIKE 'Roughness' OR anlys_prop_name LIKE 'Reflectance' OR anlys_prop_name LIKE 'Transparency' OR anlys_prop_name LIKE 'Atomic composition';";
  $noPropResultResult= (mysqli_query($link, $noPropResultSql));
  $noPropResult = [];
  while ($row = mysqli_fetch_row($noPropResultResult)){
    array_push($noPropResult, $row[0]);
  }

// The result form.
  echo"
  <form class='col-md-12'>
    <div class='form-group row'>
      <label class='col-xs-2 col-form-label'>Date:</label>
      <div class='col-md-3'>
        <input type='date' id='res_date' class='custom_date form-control' value='".date("Y-m-d")."' data-date='' data-date-format='YYYY-MM-DD'>
      </div>
    </div>";

// If Thickness & Calotte Grinder.
    if($propID === '1' && $eqID === '7'){
  // h=(sqrt(r2-d2)-sqrt(r2-D2))
      echo"
      <div class='form-group row'>
        <label class='col-xs-2 col-form-label'>Inner diameter (&#181;m): </label>
        <div class='col-md-2'>
          <input type='number' id='res_calc_d' class='form-control' value='' >
        </div>
        <label class='col-xs-2 col-form-label'>Outer diamter (&#181;m): </label>
        <div class='col-md-2'>
          <input type='number' id='res_calc_D' class='form-control' value='' >
        </div>
        <label class='col-xs-2 col-form-label'>Radius of ball (&#181;m): </label>
        <div class='col-md-2'>
          <input type='number' id='res_calc_R' class='form-control' value='25400'>
        </div>
      </div>";
    }

// Display param 1 - 3 if there are any. 
    if($propertyRow[3]){
      echo"
      <div class='form-group row'>";
        for($i = 3; $i < 6; $i++){
         if($propertyRow[$i]){
           echo"
           <label class='col-xs-2 col-form-label'>".$propertyRow[$i].":</label>
           <div class='col-md-2'>
            <input type='number' name='res_param' class='form-control'>
          </div>";
        }
      }
      echo"
    </div>";
  }

// Only couple of properties have res_res field. 
  if(!in_array($propID, $noPropResult)){
    echo"
    <div class='form-group row'>
      <label id='property_name' class='col-xs-2 col-form-label'>".$propertyRow[1];
      // If the property has units display it.
        if($propertyRow[6]){
          echo " (".$propertyRow[6].")";
        }
        echo":</label>
        <div class='col-md-2'>
          <input type='number' id='res_res' class='form-control' value='' onclick='calcCGThickness()'>
        </div>
      </div>";
    }
    echo"
    <div class='form-group row'>
      <label class='col-xs-2 col-form-label'>Comment:</label>
      <div class='col-md-3'>
        <textarea  id='res_comment' class='form-control' value=''></textarea>
      </div>
      <div class='col-md-1'>
      </div>
      <label class='col-xs-2 col-form-label'>File: (No functionality) </label>
      <div class='col-md-4'>
        <label class='btn btn-default btn-file'>Browse...
          <input type='file' id='fileToUpload' name='fileToUpload' style='display: none;' onchange='$(\"#sample_file_path\").html(getFileName($(this).val()));'>
        </label>
        <span id='sample_file_path' class='table_style_text'></span>
      </div>
    </div>
    <div class='col-md-12'>
      <button type='button' class='btn btn-primary col-md-2' onclick='addAnlysResult(".$propertyRow[0].",this.form)' style='float:right'>Add</button>
    </div>
    </form>";

    echo"
    <div id='anlys_result_table' class='col-md-12'></div>";

    // Only display averages where there is a res_res field except for Adhesion.
    if(!in_array($propID, $noPropResult) && $propID !== '4'){
    $avgSql = "SELECT TRUNCATE(AVG(anlys_res_result), 3)
    FROM anlys_result
    WHERE sample_ID = '$sampleID' AND anlys_eq_prop_ID = '$propertyRow[0]';";
    $avgResult = mysqli_query($link, $avgSql);
    $avgRow = mysqli_fetch_row($avgResult);
// Only display calculations if there are any results. 
    // if($avgRow[0]){
      echo"
      <div class='col-md-6'>
        <p class='table_style_text'><strong>Average: </strong>".$avgRow[0]."</p>
      </div>";
    //}
  }
}
?>
    <script>

      $(document).ready(function(){
        displayAnlysResultTable(<?php echo $sampleID; ?>, <?php echo $eqPropID; ?>);
      })
    // Trime the filepath to only the file name. 
      function getFileName(s) {
        return s.replace(/^.*[\\\/]/, '');
      }

    // Format the date input.
      $('#res_date').on('change', function() {
        this.setAttribute(
        'data-date',
        moment(this.value, 'YYYY-MM-DD')
        .format( this.getAttribute('data-date-format'))
        )
      }).trigger('change')

      // When user clicks res_res field when using thickness & Calotte Grinder calculate the thickness. 
      function calcCGThickness(){
        var d = $("#res_calc_d").val();
        var D = $("#res_calc_D").val();
        var R = $("#res_calc_R").val();
        var h = (Math.sqrt((Math.pow(R,2)-(Math.pow(d,2)))) - Math.sqrt((Math.pow(R,2)-(Math.pow(D,2)))))/2;
        $("#res_res").val(h.toFixed(3));
      }

      // If user does not click the res_res field, on previous input field blur, calculate results. 
      $("#res_calc_R").blur(function(){
        calcCGThickness();
      })

    </script>
