<?php
include '../../connection.php';
session_start();

$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);

// $propertySql = "SELECT a.anlys_eq_prop_ID, p.anlys_prop_name, e.anlys_eq_name, a.anlys_param_1, a.anlys_param_2, a.anlys_param_3, a.anlys_eq_prop_unit
// FROM anlys_property p, anlys_equipment e, anlys_eq_prop a
// WHERE a.anlys_eq_ID = e.anlys_eq_ID AND a.anlys_prop_ID = p.anlys_prop_ID
// AND a.anlys_eq_prop_ID = '$eqPropID'";
// $propertyResult = mysqli_query($link, $propertySql);
// $row = mysqli_fetch_row($propertyResult);

// $resultsSql = "SELECT anlys_res_result, anlys_res_date, anlys_res_comment, anlys_res_1, anlys_res_2, anlys_res_3, employee_ID
// FROM anlys_result
// WHERE sample_ID = '$sampleID' AND anlys_eq_prop_ID = '$eqPropID'
// ORDER BY anlys_res_ID;";
// $resultsResult = mysqli_query($link, $resultsSql);

$sql = "SELECT prcs_ID, employee_ID as employee, prcs_date as date, prcs_coating as coating, prcs_position as position,
    prcs_rotation as rotation, prcs_comment as comment
FROM process
WHERE sample_ID = '$sampleID'
ORDER BY prcs_date DESC;";
$result = mysqli_query($link, $sql);

if($hasProcessInfo = mysqli_fetch_row($result)){
?>
<table class='table table-responsive'>
<caption></caption>
  <thead>
    <tr>
      <th>Date</th>
      <th>Employee</th>
      <th>Coating</th>
      <th>Position</th>
      <th>Rotation</th>
      <th>Comment</th>
    </tr>
  </thead>
  <tbody>

  <?
    $result = mysqli_query($link, $sql);
    while($row = mysqli_fetch_array($result)){

    $employeeInitialsSql = "SELECT 
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
    WHERE employee_ID = $row[employee];";
    $employeeInitials = mysqli_fetch_row(mysqli_query($link, $employeeInitialsSql))[0];

      echo"
      <tr>
        <td>".$row['date']."</td>
        <td>".$employeeInitials."</td>
        <td>".$row['coating']."</td>
        <td>".$row['position']."</td>
        <td>".$row['rotation']."</td>
        <td>".$row['comment']."</td>
      </tr>";
    }

echo"
  </tbody>
</table>";
}

