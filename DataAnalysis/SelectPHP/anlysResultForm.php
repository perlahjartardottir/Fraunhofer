<?php
include '../../connection.php';
session_start();

$sampleID = $_SESSION["sampleID"];
$propID = mysqli_real_escape_string($link, $_POST["propID"]);
$_SESSION["propID"] = $propID;
$eqID = mysqli_real_escape_string($link, $_POST["eqID"]);
$_SESSION["eqID"] = $eqID;
$eqPropID = "-1";
$maxFileSize = $_SESSION["fileValidation"]["maxSize"];
$propsWithAnlysResults = [];
$prcsID = $_SESSION["prcsID"];
if(!$prcsID){
  $prcsID = -1;
}

// Don't display the form unless user has chosen equipment.
if($propID !== "-1" && $eqID !== "-1"){

  $propertySql = "SELECT a.anlys_eq_prop_ID, p.anlys_prop_name, e.anlys_eq_name, a.anlys_param_1, a.anlys_param_2, a.anlys_param_3, a.anlys_eq_prop_unit, a.anlys_aveg
  FROM anlys_property p, anlys_equipment e, anlys_eq_prop a
  WHERE a.anlys_eq_ID = e.anlys_eq_ID AND a.anlys_prop_ID = p.anlys_prop_ID
  AND p.anlys_prop_ID = '$propID' AND e.anlys_eq_ID = '$eqID';";
  $propertyResult = mysqli_query($link, $propertySql);
  $propertyRow = mysqli_fetch_array($propertyResult);
  $eqPropID = $propertyRow[0];

  $resultsSql = "SELECT anlys_res_result, anlys_res_comment, anlys_res_date, anlys_res_1, anlys_res_2, anlys_res_3, prcs_ID as prcsID
  FROM anlys_result
  WHERE sample_ID = '$sampleID' AND anlys_eq_prop_ID = '$propertyRow[0]'
  ORDER BY anlys_res_ID;";
  $resultsResult = mysqli_query($link, $resultsSql);

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

$coatingsSql = "SELECT p.prcs_ID, p.prcs_coating
FROM process p
WHERE p.sample_ID = '$sampleID'
ORDER BY p.prcs_ID DESC;";
$coatingsResult = mysqli_query($link, $coatingsSql);

// The result form.
  echo"
  <form class='col-md-12' role='form' action='../InsertPHP/addAnlysResult.php' method='post' enctype='multipart/form-data' onsubmit='return anlysResultValidation(".$sampleID.",".$eqPropID.",this)'>
  <div id='error_message'></div>
    <div class='form-group row'>
       <label class='col-md-2 col-form-label'>Layer of coating:</label>
      <div class='col-md-2'>
        <select id='coating' name='coating' class='form-control custom_select' onchange='setPrcsIDAndRefresh()'>";
            while($row = mysqli_fetch_row($coatingsResult)){
              echo "<option value='".$row[0]."'>".$row[1]."</option>";
            }
      echo"
          <option value='-1'>No Coating</option>
       </select>
      </div>
    </div>
    <div class='form-group row'>
      <input type='hidden' id='eq_prop_ID' name='eq_prop_ID' value=".$eqPropID.">
      <label class='col-md-2 col-form-label'>Date:</label>
      <div class='col-md-2'>
        <input type='date' id='res_date' name='res_date' class='custom_date form-control' value='".date("Y-m-d")."' data-date='' data-date-format='YYYY-MM-DD'>
      </div>
      <label class='col-md-2 col-form-label'>Employee:</label>
      <div class='col-md-2'>
        <select id='employee_initials' name='employee_initials' class='form-control'>";
          while($row = mysqli_fetch_row($employeeInitialsResult)){
            echo "<option value='".$row[0]."'>".$row[2]."</option>";
          }
      echo"
       </select>
      </div>
    </div>";

    // If Thickness & Calotte Grinder.
    if($propID === '1' && $eqID === '7'){
      // h=(sqrt(r2-d2)-sqrt(r2-D2))
      echo"
      <div class='form-group row'>
        <label class='col-md-2 col-form-label'>Inner diameter (&#181;m): </label>
        <div class='col-md-2'>
          <input type='number' id='res_calc_d' class='form-control' step='any' value='' >
        </div>
        <label class='col-md-2 col-form-label'>Outer diamter (&#181;m): </label>
        <div class='col-md-2'>
          <input type='number' id='res_calc_D'class='form-control' step='any' value='' >
        </div>
        <label class='col-md-2 col-form-label'>Radius of ball (&#181;m): </label>
        <div class='col-md-2'>
          <input type='number' id='res_calc_R' class='form-control' step='any' value='25400'>
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
           <label class='col-md-2 col-form-label'>".$propertyRow[$i].":</label>
           <div class='col-md-2'>
            <input type='number' name='res_param[".($i-3)."]' class='form-control' step='any'>
          </div>";
        }
      }
      echo"
    </div>";
  }

  echo"
  <div class='form-group row'>";
// Only couple of properties have res_res field. 
  if($propertyRow[7]){
    echo"
      <label id='property_name' class='col-md-2 col-form-label'>".$propertyRow[1];
      // If the property has units display it.
        if($propertyRow[6]){
          echo " (".$propertyRow[6].")";
        }
        echo":</label>
        <div class='col-md-2'>
          <input type='number' id='res_res' name='res_res' class='form-control' step='any' onclick='calcCGThickness()'>
        </div>";
    }
  echo"
      <label class='col-md-2 col-form-label'>Files:</label>
      <div class='col-md-4'>
        <input type='file' id='anlys_file' name='anlys_file[]' multiple accept='media_type' style='display:none' onchange='handleFiles(this.files)''>
        <a href='#' id='file_select' class='btn btn-default btn-file'>Browse</a> 
        <div id='file_list'>
          <p>No files selected.</p>
        </div>
      </div>
    </div>";

    // Set default value for comment based on coating property.
    // The request to include these values came late, so this is a quick fix. 
    $commentValue = "";
    // If Roughness
    if($propID === '2'){
      $commentValue = "Scan speed: \nScan mode: \nTip size: \nForce (N): \n";
    }
    // Coefficient of friction
    else if($propID === '8'){
      $commentValue = "Humidity (%): \nTemperature (°C): \nMax speed (cm/s): \nForce (N): \nDistance (m): \nStatic partner: ";
    }
    // UV VIS
    else if($eqID === '6'){
      $commentValue = "Range (nm): 2500 - 190 \nSlit width: double 20 \nMeasurement speed: very slow \nDetector: ISR ";
    }
    else if($eqID === '12'){
      $commentValue = "Fit description: ";
    }
    echo"
    <div class='form-group row'>
      <label class='col-md-2 col-form-label'>Comment:</label>
      <div class='col-md-4'>
        <textarea id='res_comment' name='res_comment' rows='4' class='form-control'>".$commentValue."</textarea>
      </div>
    </div>
      <div class='form-group row col-md-12'>
        <button type='submit' class='btn btn-primary col-md-2' style='float:right'>Add</button>
      </div>
  </form>";
   ?>


<?

  echo"
  <div id='anlys_result_table' class='col-md-12'></div>";
}
?>
<script>

  $(document).ready(function(){
    displayAnlysResultTable(<?php echo $sampleID; ?>, <?php echo $eqPropID; ?>, <?php echo $prcsID; ?>);
})
        // Make the combo box select the currently chosen sample set.
    $("#coating").val(<?php echo $prcsID; ?>)

    // Format the date input.
    $("#res_date").on("change", function(){
      this.setAttribute(
        'data-date',
        moment(this.value, 'YYYY-MM-DD')
        .format( this.getAttribute('data-date-format'))
        )
    }).trigger("change")

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

      // Make tge dropdown list select the currently logged in user.
      $("#employee_initials").val(<?php echo $userID; ?>);

      // File uploading.
      // https://developer.mozilla.org/en-US/docs/Using_files_from_web_applications
      window.URL = window.URL || window.webkitURL;
      var fileSelect = document.getElementById("file_select"),
      fileElem = document.getElementById("anlys_file"),
      fileList = document.getElementById("file_list");
      fileSelect.addEventListener("click", function (e) {
        if (fileElem) {
          fileElem.click();
        }
        e.preventDefault(); // prevent navigation to "#"
      }, false);
      function handleFiles(files) {
        if (!files.length) {
          fileList.innerHTML = "<p>No files selected.</p>";
        } else {
          fileList.innerHTML = "";
          var list = document.createElement("ul");
          fileList.appendChild(list);
          for (var i = 0; i < files.length; i++) {
            var li = document.createElement("li");
            list.appendChild(li);

            var info = document.createElement("span");
            if(files[i].size > <?php echo $maxFileSize; ?>){

              var errorMessage = "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
              "Sorry, " + files[i].name + " is too large. The max size is: " + <?php echo strval(number_format($maxFileSize/1000/1000,2)); ?> +" MB.</div>";
               info.innerHTML = files[i].name + " - " + (files[i].size/1000/1000).toFixed(2) + " MB" + errorMessage;
            }
            else{
            info.innerHTML = files[i].name;
            }
            li.appendChild(info);
          }
        }
      }

    </script>
