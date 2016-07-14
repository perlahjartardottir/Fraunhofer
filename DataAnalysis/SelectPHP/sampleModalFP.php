<?php
include '../../connection.php';

$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);


$sql = "SELECT sample_name, ss.sample_set_name, sample_material, sample_comment
FROM sample s, sample_set ss
WHERE s.sample_set_ID = ss.sample_set_ID AND sample_ID = '$sampleID';";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);

echo"
 <div class='modal-dialog'>
      <div class='modal-content '>
          <div class='modal-header'>
          <div class='col-md-12'>
          <button type='button' id='close_modal' class='btn close glyphicon glyphicon-remove'</button>
          </div>
            <h3 class='center_heading'>".$row[0]."</h3>

          </div>
          <div class='modal-body'>
              <p><strong>Material: </strong>".$row[2]."</p>
              <p></p>
              <p><strong>Comment: </strong>".$row[3]."</p>            
            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-primary col-md-3' style='float:left;'>Process</button>
              <button type='button' class='btn btn-primary col-md-3' onclick='location.href=\"analyze.php?id=".$sampleID."\"''>Analyze</button>
              
            </div>
        </div>
      </div>
    </div>";
    echo"
    <script>
      document.getElementById('close_modal').onclick = function(){
        modal.style.display = 'none';
        }
    </script>";
?>