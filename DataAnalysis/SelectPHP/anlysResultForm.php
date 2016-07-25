<?php
include '../../connection.php';
session_start();

$sampleID = $_SESSION["sampleID"];
$propID = mysqli_real_escape_string($link, $_POST["propID"]);
$_SESSION["propID"] = $propID;
$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);
$_SESSION["eqID"] = $eqID;

if($propID !== "-1" && $eqID !== "-1"){

$propertySql = "SELECT a.anlys_eq_prop_ID, p.anlys_prop_name, e.anlys_eq_name, a.anlys_param_1, a.anlys_param_2, a.anlys_param_3, a.anlys_eq_prop_unit
FROM anlys_property p, anlys_equipment e, anlys_eq_prop a
WHERE a.anlys_eq_ID = e.anlys_eq_ID AND a.anlys_prop_ID = p.anlys_prop_ID
AND p.anlys_prop_ID = '$propID' AND e.anlys_eq_ID = '$eqID';";
$propertyResult = mysqli_query($link, $propertySql);
$propertyRow = mysqli_fetch_array($propertyResult);

$resultsSql = "SELECT anlys_res_result, anlys_res_comment, anlys_res_date, anlys_res_1, anlys_res_2, anlys_res_3
FROM anlys_result
WHERE sample_ID = '$sampleID' AND anlys_eq_prop_ID = '$propertyRow[0]'
ORDER BY anlys_res_ID;";
$resultsResult = mysqli_query($link, $resultsSql);

// Find the property ID  of overview and display. 
$noPropResultSql = "SELECT anlys_prop_ID
FROM anlys_property
WHERE anlys_prop_name LIKE 'Overview' OR anlys_prop_name LIKE 'Roughness';";
$noPropResultResult= (mysqli_query($link, $noPropResultSql));
$noPropResult = [];
while ($row = mysqli_fetch_row($noPropResultResult)){
  array_push($noPropResult, $row[0]);
}


// The result form.
echo"
<div class='form-group col-md-6'>";

// Don't display property name if the property is overview or roughness. 
if(!in_array($propID, $noPropResult)){
  echo"
    <label id='property_name'>".$propertyRow[1];
      // If the property has units display it.
      if($propertyRow[6]){
        echo " (".$propertyRow[6].")";
      }
      echo":</label>
    <input type='text' id='res_res' value='' class='form-control'>";
}

echo"
  <label>Date:</label>
  <div>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js'></script>
    <input type='date' id='res_date' class='custom_date' value='".date("Y-m-d")."' data-date='' data-date-format='YYYY-MM-DD'>
  </div>
  <label>Comment:</label>
  <input type='text' id='res_comment' value='' class='form-control'>
  <label>Add file: (No functionality)</label>
  <br>
  <label class='btn btn-default btn-file'>Choose File
    <input type='file' id='res_file' name='sample_file' onchange='$(\"#sample_file_path\").html(getFileName($(this).val()));' style='display: none;'>
  </label>
  <span id='sample_file_path'></span>
  <br>
  <button type='button' class='btn btn-primary col-md-2' onclick='addAnlysResult(".$propertyRow[0].",this.form)' style='float:right'>Add</button>
</div>
<div class='form-group col-md-6'>";
  for($i = 3; $i < 6; $i++){
   if($propertyRow[$i]){
     echo"
     <label>".$propertyRow[$i].":</label>
     <input type='text' name='res_param' class='form-control'>";
   }
 }
 echo"
</div>";

echo"
   <div id='anlys_result_table' class='col-md-12'></div>";

$avgSql = "SELECT TRUNCATE(AVG(anlys_res_result), 3)
FROM anlys_result
WHERE sample_ID = '$sampleID' AND anlys_eq_prop_ID = '$propertyRow[0]';";
$avgResult = mysqli_query($link, $avgSql);
$avgRow = mysqli_fetch_row($avgResult);
// Only display calculations if there are any results. 
if($avgRow[0]){
  echo"
  <div class='col-md-6'>
    <p class='table_style_text'><strong>Average: </strong>".$avgRow[0]."</p>
  </div>";
}

echo"
  <script>

  $(document).ready(function(){
    displayAnlysResultTable(".$sampleID.",".$propertyRow[0].");
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

    displayAnlysResultTable(sampleID, eqPropID)

  </script>";
}
?>
