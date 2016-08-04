<?
include '../../connection.php';
session_start();

$resID = mysqli_real_escape_string($link, $_POST["resID"]);
$eqPropID = mysqli_real_escape_string($link, $_POST["eqPropID"]);

$propertySql = "SELECT p.anlys_prop_name as propName, e.anlys_eq_name as eqName, a.anlys_eq_prop_unit as unit, a.anlys_param_1 as param1, a.anlys_param_2 as param2, a.anlys_param_3 as param3,
a.anlys_param_1_unit as param1unit, a.anlys_param_2_unit as param2unit, a.anlys_param_3_unit as param3unit, a.anlys_aveg as dispAveg
FROM anlys_property p, anlys_equipment e, anlys_eq_prop a
WHERE a.anlys_eq_ID = e.anlys_eq_ID AND a.anlys_prop_ID = p.anlys_prop_ID
AND a.anlys_eq_prop_ID = '$eqPropID';";
$propertyRow = mysqli_fetch_array(mysqli_query($link, $propertySql));

$resultSql = "SELECT anlys_eq_prop_ID as eqPropID, anlys_res_result as result, anlys_res_comment as comment, anlys_res_1, anlys_res_2, anlys_res_3
FROM anlys_result
WHERE anlys_res_ID = '$resID';";
$resultRow = mysqli_fetch_array(mysqli_query($link, $resultSql));

echo"
<div class='modal-dialog'>
  <div class='modal-content '>
    <form role='form'>
      <div class='modal-header'>
        <div class='col-md-12'>
          <button type='button' id='close_modal' class='btn close glyphicon glyphicon-remove' data-dismiss='modal'></button>
        </div>
        <h3 class='center_heading'>".$propertyRow['propName']." - ".$propertyRow['eqName']."</h3>
      </div>
      <div class='modal-body'>
        <div id='error_message'></div>";
        // If we use the anlys_result field
        if($propertyRow['dispAveg'] || $propertyRow['propName'] == 'Adhesion'){
          echo"
          <div class='form-group'>
            <label>".$propertyRow['propName'];
              if($propertyRow['unit']){
                echo"
                (".$propertyRow['unit'].")";
              }
              echo"
            </label>
            <input type='number' id='anlys_res_result' value='".$resultRow['result']."' class='form-control'>
          </div>";
        }
        // Display parameters if any
        for($i = 3; $i < 6; $i++){
          if($propertyRow[$i]){
            echo"
            <div class='form-group'>
              <label>".$propertyRow[$i];
            // If parameters has units
                if($propertyRow[$i+3]){
                  echo"
                  (".$propertyRow[$i+3].")";
                }

                echo"
              </label>
              <!-- Param ids from 1-3 -->
              <input type='number' id='anlys_res_param_".($i-2)."' value='".$resultRow[$i]."' class='form-control'>
            </div>";
          }
        }
        echo"
        <div class='form-group'>
          <label>Comment</label>
          <textarea id='anlys_res_comment' class='form-control'>Comment</textarea> 
        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-danger glyphicon glyphicon-trash' onclick='deleteAnlysResult(".$resID.")'></button>
          <button type='button' class='btn btn-success' onclick='editAnlysResult(".$resID.",this.form,".$propertyRow['dispAveg'].",".$propertyRow['eqPropID'].",".$propertyRow['propName'].")'>Save</button> 
        </div>
      </form>
    </div>
  </div>
</div>
</div>";

?>
<script>

  document.getElementById('close_modal').onclick = function(){
    modal.style.display = 'none';
  }
</script>