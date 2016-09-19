<?php
include '../../connection.php';

 $allActivePropertiesSql = "SELECT anlys_prop_ID, anlys_prop_name
 FROM anlys_property
 WHERE anlys_prop_active = TRUE
 ORDER BY anlys_prop_name;";
$allActivePropertiesResultEditNew = mysqli_query($link, $allActivePropertiesSql);

// We use eq_prop_ID -1 to recognise that we are adding a record to anlys_eq_prop
echo"
<div class='form-group row col-md-8'>
  <input type='hidden' name='eq_prop_ID' value='-1'>
  <select name='prop_ID' class='form-control'>";

  while($allPropRow = mysqli_fetch_row($allActivePropertiesResultEditNew)){
      echo"
      <option value='".$allPropRow[0]."'>".$allPropRow[1]."</option>";
  }
  echo"
  </select>
</div>
<div class='form-group row col-md-2'>
  <input type='text' name='prop_unit' class='form-control' placeholder='Unit'>
</div>";

mysqli_close($link);

?>