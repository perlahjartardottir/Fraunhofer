<?php
include '../../connection.php';
session_start();

$sampleSetID = mysqli_real_escape_string($link, $_POST['sampleSetID']);
$_SESSION["sampleSetID"] = $sampleSetID;


$sql = "SELECT sample_ID, sample_name, sample_material, sample_comment
FROM sample
WHERE sample_set_ID = '$sampleSetID';";


$sampleSetNameSql = "SELECT sample_set_name
FROM sample_set
WHERE sample_set_ID = '$sampleSetID';";
$sampleSetName= mysqli_fetch_row(mysqli_query($link, $sampleSetNameSql))[0];

if($sampleSetID !== "-1"){
  echo"
  <div class='row well well-lg'>
    <h3 class='custom_heading'>".$sampleSetName."</h4>
     <table class='table table-responsive' style='width:92%;'>
      <thead>
        <tr>
          <th class='span4'>Sample name</th>
          <th class='span2'>Material</th>
          <th class='span6'>Comment</th>
        </tr>
      </thead>
      <tbody>";
        $result = mysqli_query($link, $sql);
        while($row = mysqli_fetch_array($result)){
          echo"
          <tr>
           <td><a data-toggle='modal' data-target='#".$row[0]."'>".$row[1]."</a></td>
           <td>".$row[2]."</td>
           <td>".$row[3]."</td>
         </tr>";
       }
       echo"
     </tbody>
   </table>
   <button type='button' class='btn btn-primary' onclick=location.href='dataAnalysis.php' style='float:right'>Analyze</button>
   <button type='button' class='btn btn-primary' onclick=location.href='dataAnalysis.php' style='float:right'>Process</button>
 </div>";

 // Modal window to edit samples.
 $allSamplesResult = mysqli_query($link, $sql);
 while($sampleRow= mysqli_fetch_array($allSamplesResult)){
  echo"
  <div class='modal fade' id='".$sampleRow[0]."' tabindex='-1' role='dialog' aria-labelledby='".$sampleRow[0]."' aria-hidden='true'>
    <div class='modal-dialog'>
      <div class='modal-content '>
        <form role='form'>
          <div class='modal-header'>
            <center><h3>".$sampleRow[1]."</h3></center>
          </div>
          <div class='modal-body'>
            <div id='error_message'></div>
            <div class='form-group'>
              <label>Name</label>
              <input type='text' id='sample_name' value='".$sampleRow[1]."' class='form-control'>
            </div>
            <div class='form-group'>
              <label>Material</label>
              <input type='text' id='sample_material' value='".$sampleRow[2]."' class='form-control'>
            </div>
            <div class='form-group'>
              <label>Comment</label>
              <textarea id='sample_comment' class='form-control'>".$sampleRow[3]."</textarea> 

            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-success' onclick='editSample(".$sampleRow[0].",this.form)'>Edit</button>
              <button type='button' class='btn btn-danger glyphicon glyphicon-trash' onclick='deleteSample(".$sampleRow[0].")' ></button>
              <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>";
  }
}
?>
