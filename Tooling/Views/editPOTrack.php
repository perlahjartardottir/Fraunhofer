<!DOCTYPE html>
<?php
include '../connection.php';
session_start();
//find the current user
$user = $_SESSION["username"];
//find his level of security
$secsql = "SELECT security_level
FROM employee
WHERE employee_name = '$user'";
$secResult = mysqli_query($link, $secsql);

while($row = mysqli_fetch_array($secResult)){
  $user_sec_lvl = $row[0];
}
if($user_sec_lvl < 2){
  echo "<a href='../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}
?>
<html>
<head>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
  <link href='../css/main.css' rel='stylesheet'>


  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php
  // getting the right po_number from the Session po_ID
  $po_ID = $_SESSION["po_ID"];
  $sql = "SELECT po_number
          FROM pos
          WHERE po_ID = '$po_ID'";
  $result = mysqli_query($link, $sql);
  while($row = mysqli_fetch_array($result)){
    $po_number = $row[0];
  }
  ?>
  <?php include '../header.php'; ?>
  <script src="../js/passScript.js"></script>
    <script>
    $(document).ready(function(e) {
      // Reload the site when reached via browsers back button
        if ($("#refresh").val() == 'yes') { location.reload(true); } else { $('#refresh').val('yes'); }
    });
  </script>
  <div class='container'>
    <div class='row well well-lg'>
      <h4>PO number: <span id='POID'><?php echo $po_number;?></span></h4>
    </div>
    <div id='invalidRun'></div>
    <div class='row well well-lg'>
     <div class='col-xs-12'>
      <p class='col-xs-12'><strong>Add info about a run. The runID is auto generated. <br> To edit the comments for the run click the run ID. </strong></p>
    </div>
    <div class='col-xs-12'>
      <p class='col-xs-4'>
        <label for="coatingID">Coating</label>
        <select id='coatingID'>
          <option value="">Coating type:</option>
          <?php
          $sql = "SELECT coating_ID, coating_type
                  FROM coating
                  ORDER BY coating_type ASC";
          $result = mysqli_query($link, $sql);
          if (!$result)
          {
            die("Database query failed: " . mysqli_error($link));
          }
          while($row = mysqli_fetch_array($result))
          {
            echo '<option value="'.$row['coating_ID'].'">'.$row['coating_type'].'</option>';
          }
          ?>
        </select>
      </p>
      <p class='col-xs-4'>
        <label for="machine_run_number">Run# for machine today</label>
        <select id='machine_run_number'>
          <option value="">Select run number:</option>
          <option value='01'>1</option>
          <option value='02'>2</option>
          <option value='03'>3</option>
          <option value='04'>4</option>
          <option value='05'>5</option>
          <option value='06'>6</option>
          <option value='07'>7</option>
          <option value='08'>8</option>
          <option value='09'>9</option>
          <option value='10'>10</option>
          ?>
        </select>
      </p>
      <p class='col-xs-4'>
        <label for="ah_pulses">AH/Pulses </label>
        <input type="text" name="ah_pulses" id="ah_pulses">
      </p>
      <p class='col-xs-4'>
        <label for="machineID">Machine</label>
        <select id='machineID'>
          <option value="">Machine:</option>
          <?php
          $sql = "SELECT machine_ID, machine_acronym FROM machine";
          $result = mysqli_query($link, $sql);
          if (!$result) {
            die("Database query failed: " . mysqli_error($link));
          }
          while($row = mysqli_fetch_array($result)){
            echo '<option value="'.$row['machine_ID'].'">'.$row['machine_acronym'].'</option>';
          }
          ?>
        </select>
      </p>
      <p class='col-xs-4'>
        <label for="runDate">Date: </label>
        <input type="date" name="runDate" id="runDate" value='<?php echo date('Y-m-d'); ?>'>
      </p>
      <p class='col-xs-4'>
        <label for="rcomments">Comments </label>
        <input type="text" name="rcomments" id="rcomments">
      </p>
      <div id="status_text"></div>
      <button type='button'  class='btn btn-default col-xs-offset-10' onclick='showPORuns()'>
        <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
      </button>
      <button type='button'  class='btn btn-default' onclick='addRun()'>
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
      </button>
      <p></p>
      <table id='txtAddRun' style='width:95%;'>
      </table>
    </div>
  </div>
  <div class='row well well-lg'>
    <div id="displayHelper">
      <ul class="list-group">
        <!-- quick view of line items comes here from php -->
      </ul>
    </div>
  </div>
  <div id='invalidLineItem'></div>
  <?php
      //check if there is any tool with to many tools in run and let the user know.
      $quantitySql = "SELECT l.lineitem_ID, l.line_on_po, l.quantity_on_packinglist - l.quantity
                      FROM lineitem l
                      WHERE l.po_ID = 210
                      AND l.quantity < l.quantity_on_packinglist;";
      $quantityResult = mysqli_query($link, $quantitySql);
      while($row = mysqli_fetch_array($quantityResult)){
          echo "<div class='alert alert-warning fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Line number ".$row[1]." has ".$row[2]." extra tools assigned to runs. If this is ok ignore this warning</div>";
      }
  ?>
  <div class='row well well-lg'>
   <div class='col-xs-12'>
    <p><strong>Assign runs to tools by using the right line item from the general information sheet.<br> To change the comment click the </strong></p>
    <p class='col-xs-4'>
      <label for="lineItem">Line Item: </label>
      <input type='number' id='lineItem' name='lineItem'>
    </p>
    <p class='col-xs-4'>
      <label for="number_of_tools">Number of Tools: </label>
      <input type="number" name="number_of_tools" id="number_of_tools">
    </p>
    <p class='col-xs-4'>
      <label for="runNumber">RunNumber(a,b,c...): </label>
      <input type="text" name="runNumber" id="runNumber">
    </p>
    <p class='col-xs-4'>
      <label for="final_comment">Final Comment: </label>
      <input type="text" name="final_comment" id="final_comment">
    </p>
    <button type='button'  class='btn btn-default col-xs-offset-10' onclick='showRunTools()'>
      <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
    </button>
    <button type='button'  class='btn btn-default' onclick='addLineItemToRun()'>
      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
    </button>
    <p></p>

    <!-- SelectPHP/getToolsForRun.php -->
    <table id ='txtAddToolToRun' style='width:97%;'>
    </table>
    <div id="status_text2"></div>
  </div>
  <div class='col-xs-offset-9'>
  <p>
    <a class='btn btn-primary' style='margin-top:10px;'href='../printouts/packinglist.php'>Go to packing list for this PO</a>
  </p>
  </div>
</div>
<div id='runTools'></div>
<script>
$( document ).ready(function() {
  displayHelper();
  showPORuns();
  showRunTools();
});
</script>
</body>
</html>
