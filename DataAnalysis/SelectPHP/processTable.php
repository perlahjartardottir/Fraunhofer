<?php
include '../../connection.php';
session_start();

$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);
$rowCounter = 0;

$sql = "SELECT p.prcs_ID as prcsID, p.employee_ID as employee, p.prcs_date as date, p.prcs_coating as coating, p.prcs_eq_ID as eqID, p.prcs_position as position,
    p.prcs_rotation as rotation, p.prcs_comment as comment, e.prcs_eq_acronym as eqAcronym
FROM process p, prcs_equipment e
WHERE p.prcs_eq_ID = e.prcs_eq_ID AND sample_ID = '$sampleID'
ORDER BY p.prcs_ID DESC;";
$result = mysqli_query($link, $sql);

if($hasProcessInfo = mysqli_fetch_row($result)){
?>
<table class='table table-responsive'>
<caption></caption>
  <thead>
    <tr>
     <th>#</th>
      <th>Date</th>
      <th>Employee</th>
      <th>Coating</th>
      <th>Equipment</th>
      <th>Position</th>
      <th>Rotation</th>
      <th>Comment</th>
    </tr>
  </thead>
  <tbody>

  <?
    $result = mysqli_query($link, $sql);
    while($row = mysqli_fetch_array($result)){
      $rowCounter++;
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
      <td><a onclick='loadAndShowPrcsModalEdit(".$row['prcsID'].")'>".$rowCounter."</a></td>
        <td>".$row['date']."</td>
        <td>".$employeeInitials."</td>
        <td>".$row['coating']."</td>
        <td>".$row['eqAcronym']."</td>
        <td>".$row['position']."</td>
        <td>".$row['rotation']."</td>
        <td>".$row['comment']."</td>
      </tr>";
    }

echo"
  </tbody>
</table>
<div id='prcs_modal_edit' class='modal'></div>";
}
else{
  echo"
   <p class='table_style_text'>This sample has not been processed.</p>";
}

?>
<script>
  // For the modal window to edit process.
  var modal = document.getElementById('prcs_modal_edit');
  function loadAndShowPrcsModalEdit(prcsID){
    loadPrcsModalEdit(prcsID);
    modal.style.display = "block";
  }
</script>
