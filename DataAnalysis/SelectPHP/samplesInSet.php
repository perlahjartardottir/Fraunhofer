<?php
include '../../connection.php';
session_start();

$sampleSetID = mysqli_real_escape_string($link, $_POST['sampleSetID']);
$_SESSION["sampleSetID"] = $sampleSetID;
$sampleID = $_SESSION["sampleID"];

$sql = "SELECT sample_ID, sample_name, sample_material, sample_comment, sample_picture
FROM sample
WHERE sample_set_ID = '$sampleSetID';";

$materialsSql = "SELECT DISTINCT(sample_material)
FROM sample;";
$materialsResult = mysqli_query($link, $materialsSql);

$sampleSetNameSql = "SELECT sample_set_name
FROM sample_set
WHERE sample_set_ID = '$sampleSetID';";
$sampleSetName= mysqli_fetch_row(mysqli_query($link, $sampleSetNameSql))[0];

if($sampleSetID !== "-1"){
  echo"
  <div class='row well well-lg'>
    <h3 class='custom_heading'>Samples in set: ".$sampleSetName."</h3>
     <table class='table table-responsive' style='width:92%;'>
      <thead>
        <tr>
          <th>Sample name</th>
          <th>Material</th>
          <th>Comment</th>
          <th>Picture</th>
        </tr>
      </thead>
      <tbody>";
        $result = mysqli_query($link, $sql);
        while($row = mysqli_fetch_array($result)){
        echo"
          <tr>
           <td><a onclick='loadAndShowSampleModal(".$row[0].")'>".$row[1]."</a></td>
           <td>".$row[2]."</td>
           <td>".$row[3]."</td>";
           if($row[4]){
            echo"
              <td><a onclick='window.open(\"samplePicture.php?id=".$row[0]."\")'>Yes</a></td>";
           }
           else{
            echo"
              <td>No</td>";

           }
        echo"
         </tr>";
       }
       echo"
     </tbody>
   </table>
 </div>
 <div id='sample_modal_edit' class='modal'></div>";
}
echo"
<script>
  var modal = document.getElementById('sample_modal_edit');
  function loadAndShowSampleModal(sampleID){
    loadSampleModalEdit(sampleID);
    modal.style.display = 'block';
  }
  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == modal) {
      modal.style.display = 'none';
    }
  }
</script>";

?>
