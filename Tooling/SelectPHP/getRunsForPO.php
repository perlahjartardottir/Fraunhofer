<?php
include '../connection.php';
session_start();
$po_ID = $_SESSION['po_ID'];

// display the table
echo "<table style='width:97%;'><tr>".
        "<td>Run ID</td>".
        "<td>Coating type</td>".
        "<td>AH/Pulses</td>".
        "<td>Run number</td>".
        "<td>Comments</td>".
     "</tr>";
// select all the info about the run we need
$sql = "SELECT r.run_ID, r.run_number, c.coating_type, r.ah_pulses, posr.run_number_on_po, r.run_comment, r.run_date, r.machine_ID, r.coating_ID, m.machine_acronym
        FROM run r, pos_run posr, coating c, machine m
        WHERE r.run_ID = posr.run_ID
        AND posr.po_ID = '$po_ID'
        AND r.coating_ID = c.coating_ID
        AND r.machine_ID = m.machine_ID
        ORDER BY posr.run_number_on_po";
//run a query to find the right ID of our coating
$result = mysqli_query($link, $sql);


if (!$result) {
    $message  = 'Invalid query: ' . mysqli_error($link) . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
}
$date = "Y-m-d";

while($row = mysqli_fetch_array($result)){
    if($row[4] == 1){ $row[4] = a;}
    if($row[4] == 2){ $row[4] = b;}
    if($row[4] == 3){ $row[4] = c;}
    if($row[4] == 4){ $row[4] = d;}
    if($row[4] == 5){ $row[4] = e;}
    if($row[4] == 6){ $row[4] = f;}
    if($row[4] == 7){ $row[4] = g;}
    echo "<tr><td><a href='#' data-toggle='modal' data-target='#".$row[1]."'>".$row[1]."</td>".
         "<td>".$row[2]."</td>".
         "<td>".$row[3]."</td>".
         "<td>".$row[4]."</td>".
         "<td>".$row[5]."<button style='float:right; margin-right:-50px' class='btn btn-danger' onclick='delRun(".$row[1].")'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button></td>".
         "</tr>";
}
echo "</table>";
$result = mysqli_query($link, $sql);
while($row = mysqli_fetch_array($result)){
    $substr = substr($row[1], -1);
    $substr = "0".$substr;
    //var_dump($substr);
    if($row[4] == 1){ $row[4] = a;}
    if($row[4] == 2){ $row[4] = b;}
    if($row[4] == 3){ $row[4] = c;}
    if($row[4] == 4){ $row[4] = d;}
    if($row[4] == 5){ $row[4] = e;}
    if($row[4] == 6){ $row[4] = f;}
    if($row[4] == 7){ $row[4] = g;}
    echo "
            <div class='modal fade' id='".$row[1]."' tabindex='-1' role='dialog' aria-labelledby='".$row[1]."' aria-hidden='true'>
              <div class='modal-dialog'>
                <div class='modal-content col-md-12'>
                  <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h4 class='modal-title' id='myModalLabel'>Run ID: ".$row[1]."</h4>
                  </div>
                  <div class='modal-body col-md-12'>
                    <h4>Edit run information</h4>
                    <p></p>

                    <div class='col-md-12'>
                      <label>Run# on this PO: (a,b,c...) </label>
                      <input type='text' id='input_run_number' value='".$row[4]."'>
                    </div>
                    <p></p>
                    <div class='col-md-12'>
                      <label>Run# for machine today</label>
                      <select id='input_machine_run_number'>";
                        $counter = 1;
                        while ($counter < 10){
                          if('0'.$counter == $substr){
                            echo "<option value='0".$counter."' selected='selected'>".$counter."</option>";
                          }else{
                            echo "<option value='0".$counter."'>".$counter."</option>";
                          }
                          $counter = $counter + 1;
                        }
                      echo "
                      </select>
                    </div>
                    <div class='col-md-12'>
                      <label for='coatingID'>Coating</label>
                      <select id='input_coatingID'>";
                          $coatSql = "SELECT coating_ID, coating_type
                                      FROM coating
                                      ORDER BY coating_type ASC";
                          $coatResult = mysqli_query($link, $coatSql);
                          if (!$coatResult){
                            die("Database query failed: ". mysqli_error($link));
                          }
                          while($coatRow = mysqli_fetch_array($coatResult)){
                            if($coatRow['coating_ID'] == $row[8]){
                              echo '<option value="'.$coatRow['coating_ID'].'" selected=\'selected\'>'.$coatRow['coating_type'].'</option>';
                            }else{
                              echo '<option value="'.$coatRow['coating_ID'].'">'.$coatRow['coating_type'].'</option>';
                            }
                          }
                      echo "
                      </select>
                    </div>
                    <div class='col-md-6'>
                      <label>Machine</label>
                      <select id='input_machineID'>";
                        $machineSql = "SELECT machine_ID, machine_acronym
                                       FROM machine";
                        $machineResult = mysqli_query($link, $machineSql);
                        if (!$machineResult) {
                          die("Database query failed: ". mysqli_error($link));
                        }
                        while($machineRow = mysqli_fetch_array($machineResult)){
                          if($machineRow['machine_ID'] == $row[7]){
                            echo '<option value="'.$machineRow['machine_ID'].'" selected="selected">'.$machineRow['machine_acronym'].'</option>';
                          }else{
                            echo '<option value="'.$machineRow['machine_ID'].'">'.$machineRow['machine_acronym'].'</option>';
                          }
                        }
                      echo "
                      </select>
                    </div>
                    <div class='col-md-12'>
                      <label>AH/Pulses: </label>
                      <input type='text' id='input_ah_pulses' value='".$row[3]."'>
                    </div>
                    <p></p>
                    <div class='col-md-12'>
                      <label for='runDate'>Date: </label>
                      <input type='date' id='input_runDate' value='".$row[6]."'>
                    </div>
                    <div class='col-md-12'>
                      <label>Add or edit run comment:</label><br>
                      <textarea name='runcomment' id='new_comment'>".$row[5]."</textarea>
                    </div>
                  </div>
                  <div class='modal-footer'>
                    <button type='button' class='btn btn-default' onclick='showPOTools()' data-dismiss='modal'>Close</button>
                    <button type='button' class='btn btn-success comment_button' onclick='updateRunComment(".$row[0].", this);'  data-dismiss='modal'>Save changes</button>
                  </div>
                </div>
              </div>
           </div>";
}
?>
