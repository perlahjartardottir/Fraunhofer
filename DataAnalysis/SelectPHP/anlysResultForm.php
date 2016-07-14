<?php
include '../../connection.php';
session_start();

$propID = mysqli_real_escape_string($link, $_POST['propID']);
$_SESSION['propID'] = $propID;
$eqID = mysqli_real_escape_string($link, $_POST['eqID']);
$_SESSION['eqID'] = $eqID;

$propertySql = "SELECT a.anlys_eq_prop_ID, p.anlys_prop_name, e.anlys_eq_name, a.anlys_param_1, a.anlys_param_2, a.anlys_param_3
FROM anlys_property p, anlys_equipment e, anlys_eq_prop a
WHERE a.anlys_eq_ID = e.anlys_eq_ID AND a.anlys_prop_ID = p.anlys_prop_ID
	AND p.anlys_prop_ID = '$propID' AND e.anlys_eq_ID = '$eqID';";
$propertyResult = mysqli_query($link, $propertySql);
$propertyRow = mysqli_fetch_array($propertyResult);
	echo"
	<div class='form-group col-md-6'>
		<label id='property_name'>".$propertyRow[1].":</label>
        <input type='text' id='res_res' value='' class='form-control'>
        <label>Comment:</label>
        <input type='text' id='res_comment' value='' class='form-control'>
        <label>Add file:</label>
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
    <script>
    	// Trime the filepath to only the file name. 
  		function getFileName(s) {
    		return s.replace(/^.*[\\\/]/, '');
  		}
  	</script>";
?>