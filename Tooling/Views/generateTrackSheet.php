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
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
</head>
<input type="hidden" id="refresh" value="no">
<body>
  <?php include '../header.php'; ?>
  <script src="../js/passScript.js"></script>
  <!-- <script src="../js/searchScript.js"></script>
  <script src="../js/bootstrap.js"></script> -->
  <script>
  // refresh everytime you enter page, this is done so that when the user hits `Back` in the browser the page refreshes
    $(document).ready(function(e) {
   if ($("#refresh").val() == 'yes') { location.reload(true); } else { $('#refresh').val('yes'); }
  });
  </script>
  <div class='container'>
   <div class='row well well-lg'>
    <form>
      <div class='form-group'>
        <h3>Choose the right PO number</h3>
        <select name='POS' onchange='showToolsAndRefreshImage(this.value)' id='posel' class='form-control' style='width: auto;'>
          <option value''>Select a PO#: </option>
          <?php
            $sql = "SELECT po_ID, po_number
                    FROM pos
                    ORDER BY receiving_date DESC
                    LIMIT 12";
            $result = mysqli_query($link, $sql);
            while($row = mysqli_fetch_array($result)){
                echo '<option value="'.$row[0].'">'.$row[1].'</option>';
            }
           echo "</select>";
           ?>
           <br><div id="poinfo"><b>PO info will be listed here</b></div>
         </div>
       </form>
       <form class='form-inline'>
        <h4 style='margin-top: 30px;'>Recently added runs</h4>
        <select name="runsel" id="runsel" class='form-control'>
          <option value="">Choose a run number</option>
          <?php
              $sql = "SELECT run_ID, run_Number
                      FROM run
                      ORDER BY run_date DESC
                      LIMIT 6;";
              $result = mysqli_query($link, $sql);

              if (!$result)
              {
                die("Database query failed: " . mysqli_error($link));
              }
              while($row = mysqli_fetch_array($result))
              {
                echo '<option id="'.$row['run_ID'].'" value="'.$row['run_ID'].'">'.$row['run_Number'].'</option>';
              }
          ?>
        </select>
        <button type='button' id='old_run_btn' class='btn btn-primary'onclick="addOldRun()">Add run</button>
      </form>
     </div>
     <div id='invalidRun'></div>
     <div class='row well well-lg'>
      <div class='col-md-12'>
      <p><strong>Add info about a run. The run ID and run number are auto generated. <br> To edit the run, click the run ID. </strong></p>
      <form>
        <div class='col-md-3 form-group'>
          <label for="coatingID">Coating</label>
          <select id='coatingID' class='form-control'>
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
        </div>
        <div class='col-md-3 form-group'>
          <label for="machineID">Machine</label>
          <select id='machineID' class='form-control'>
            <option value="">Machine:</option>
            <?php
            $sql = "SELECT machine_ID, machine_acronym
                    FROM machine";
            $result = mysqli_query($link, $sql);
            if (!$result) {
              die("Database query failed: " . mysqli_error($link));
            }
            while($row = mysqli_fetch_array($result)){
              echo '<option value="'.$row['machine_ID'].'">'.$row['machine_acronym'].'</option>';
            }
            ?>
          </select>
        </div>
        <div class='col-md-3 form-group'>
       <label for="machine_run_number">Run# for machine today</label>
        <select id='machine_run_number' class='form-control'>
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
          ?>
        </select>
      </div>
      <div class='col-md-3'>
        <label for="ah_pulses">AH/Pulses </label>
        <input type="text" name="ah_pulses" id="ah_pulses" class='form-control'>
      </div>
    </div>
    <div class='col-md-12'>
      <div class='col-md-3 form-group '>
        <label for="runDate">Date </label>
        <input type="date" name="runDate" id="runDate" value='<?php echo date('Y-m-d'); ?>' class='form-control'>
      </div>
      <div class='col-md-3 form-group'>
        <label for="rcomments">Comments </label>
        <input type="text" name="rcomments" id="rcomments" class='form-control'>
      </div>
    </div>
      <button type='button'  class='btn btn-default col-md-offset-10' onclick='showPORuns()'>
        <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
      </button>
      <button type='button'  class='btn btn-default' onclick='addRun()'>
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
      </button>
      <p></p>
    </form>
      <!-- Edit this table in the SelectPHP/getRunsForPO.php file -->
      <!-- And edit the javascript function showPORuns() in js/passScript.js -->
      <div id="txtAddRun">
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
   <form>
    <p><strong>Assign runs to tools by using the right line item from the general information sheet.<br> To change the comment, click the line item # from the table.</strong></p>
    <div class='col-xs-4 form-group'>
      <label for="lineItem">Line item: </label>
      <input type='number' id='lineItem' name='lineItem' class='form-control'>
    </div>
    <div class='col-xs-4 form-group'>
      <label for="number_of_tools">Number of tools: </label>
      <input type="number" name="number_of_tools" id="number_of_tools" class='form-control'>
      <button type='button' class='btn btn-sm btn-success' style='margin-top:-56px; margin-left:221px;'type"button" name"display_tools_left" id="display_tools_left" onclick="tools_left()">
        All remaining tools
      </button>
    </div>
    <div class='col-xs-4 form-group'>
      <label for="runNumber">Run number: </label>
      <input type="text" name="runNumber" id="runNumber" class='form-control' placeholder='a, b, c...'>
    </div>
    <div class='col-xs-4 form-group'>
      <label for="final_comment">Final comment: </label>
      <input type="text" name="final_comment" id="final_comment" class='form-control'>
    </div>
    <button type='button'  class='btn btn-default col-xs-offset-10' onclick='showRunTools()'>
      <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
    </button>
    <button type='button'  class='btn btn-default' onclick='addLineItemToRun()'>
      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
    </button>
    <p></p>
    <!-- js/passScript.js in the function showRunTools -->
    <div id ='txtAddToolToRun'>
    </div>
    <div id="status_text2"></div>
  </form>
  <div class='col-xs-offset-9'>
    <div>
      <a class='btn btn-primary' style='margin-top:10px;' href='../printouts/packinglist.php'>Go to packing list for this PO</a>
    </div>
  </div>
</div>
<div id='runTools'></div>
<script>
<?php
    $po_ID = $_SESSION["po_ID"];
    $po_numberSql = "SELECT po_number
                     FROM pos
                     WHERE po_ID = '$po_ID'";

    $po_numberResult = mysqli_query($link, $po_numberSql);
    $po_number = mysqli_fetch_array($po_numberResult);
?>
//show the info for the PO chosen when you enter the page or refresh it
    $( document ).ready(function() {
        var po_ID = <?php echo $po_ID; ?>;
        showTools(po_ID);
        displayHelper();
        showPORuns();
        showRunTools();
    });
    //if the user enters the view with a PO not on the dropdownlist
    // check if the value is in the list already
    var exists = false;
    $('#posel option').each(function(){
        if (this.value == '<?php echo $po_ID; ?>') {
            exists = true;
        }
    });
    // if the list doesnt contain our PO we add it to the dropdown
    if(!exists){
        $('#posel').append($('<option>', {
            value: <?php echo $po_ID; ?>,
            text: '<?php echo $po_number[0]; ?>'
        }));
    }
    //make the dropdown list show the currently chosen PO
    $('#posel').val("<?php echo $po_ID;?>");
</script>
</body>
</html>
