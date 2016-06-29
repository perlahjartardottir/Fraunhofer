<?php
include '../../connection.php';
session_start();

$sampleSetID = mysqli_real_escape_string($link, $_POST['sampleSetID']);
$_SESSION["sampleSetID"] = $sampleSetID;


$sql = "SELECT sample_ID, sample_name, sample_material, sample_comment
FROM sample
WHERE sample_set_ID = '$sampleSetID';";
$result = mysqli_query($link, $sql);


if($sampleSetID !== "-1"){
  echo"
  <div class='row well well-lg'>
   <table class='table table-responsive' style='width:92%;'>
    <thead>
      <tr>
        <th></th>
        <th>Sample Name</th>
        <th>Material</th>
        <th>Comment</th>
      </tr>
    </thead>
    <tbody>";
      while($row = mysqli_fetch_array($result)){
        echo"
        <tr>
         <td><button onclick='deleteSample(".$row[0].")' class='btn btn-danger'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button></td>
         <td><a href='#' data-toggle='modal' data-target='#".$row[0]."'>".$row[1]."</a><td>
           <td>".$row[2]."</td>
           <td>".$row[3]."</td>
         </tr>";
       }
       echo"
     </tbody>
   </table>
   <!--<button type='button' class='btn btn-primary col-md-2' onclick='' style='float:right'>Finish</button>-->
 </div>

 <div class='row well well-lg'>
  <h5>Samples that have been analysed or processed cannot be deleted. </h5>
 </div>";

 // Modal window to edit samples.
 $allSamplesResult = mysqli_query($link, $sql);
    while($sampleRow= mysqli_fetch_array($allSamplesResult)){
      echo"
      <div class='modal fade' id='".$sampleRow[0]."' tabindex='-1' role='dialog' aria-labelledby='".$sampleRow[0]."' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content col-md-12'>
            <div class='modal-header'>
              <center><h3>".$sampleRow[1]."</h3></center>
            </div>
            <div class='modal-body'>
              <form>
                <div class='col-md-6'>
                  <label>Name</label>
                  <input type='text' id='sample_name' value='".$sampleRow[1]."' class='form-control'>
                </div>
                <div class='col-md-6'>
                  <label>Material</label>
                  <input type='text' id='sample_material' value='".$sampleRow[2]."' class='form-control'>
                </div>
                <div class='col-md-6'>
                  <label>Comment</label>
                  <textarea id='sample_comment' value='".$sampleRow[3]."' class='form-control'></textarea> 
                </div>
              </form>
            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-success' onclick='editSample(".$sampleRow[0].",this)'>Edit</button>
              <button type='button' class='btn btn-danger' onclick=''>Delete</button>
              <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
            </div>
          </div>
        </div>
      </div>";
    }
}
