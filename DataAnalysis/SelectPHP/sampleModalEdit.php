<?
include '../../connection.php';
session_start();

$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);
$maxPictureSize = $_SESSION["pictureValidation"]["maxSize"];
$pictureFormats = $_SESSION["pictureValidation"]["formats"] ;

$sql = "SELECT sample_ID, sample_name, sample_material, sample_comment, sample_picture
FROM sample
WHERE sample_ID = '$sampleID';";
$result = mysqli_query($link, $sql);
$sampleRow = mysqli_fetch_row($result);

$materialsSql = "SELECT DISTINCT(sample_material)
FROM sample;";
$materialsResult = mysqli_query($link, $materialsSql);

$sampleSetNameSql = "SELECT sample_set_name
FROM sample_set
WHERE sample_set_ID = '$sampleSetID';";
$sampleSetName= mysqli_fetch_row(mysqli_query($link, $sampleSetNameSql))[0];

echo"
 <div class='modal-dialog'>
  <div class='modal-content '>
      <div class='modal-header'>
        <div class='col-md-12'>
          <button type='button' id='close_modal' class='btn close glyphicon glyphicon-remove' data-dismiss='modal'></button>
        </div>
        <h3 class='center_heading'>".$sampleRow[1]."</h3>
      </div>
      <form id='sample_edit_form' role='form' action='../UpdatePHP/editSample.php' method='post' enctype='multipart/form-data'>
      <div class='modal-body'>
        <div id='error_message'></div>
        <div class='form-group'>
          <label>Material: </label>
          <input type='text' list='edit_materials' name='edit_material' class='form-control' value='".$sampleRow[2]."'>
          <datalist id='edit_materials'>";
            while($row = mysqli_fetch_array($materialsResult)){
              echo"<option value='".$row[0]."'>".$row[0]."</option>";
            }
            echo"
          </datalist>
        </div>
        <div class='form-group'>
          <label>Comment:</label>
          <textarea id='sample_comment' name='sample_comment' class='form-control'>".$sampleRow[3]."</textarea> 
        </div>";
        if($sampleRow[4]){
        echo"
          <div class='form-group'>
          <label>Sample picture:</label>
            <img id='sample_picture_thumbnail' src='".$sampleRow[4]."' class='img-responsive img-thumbnail' alt='Sample picture' onclick='window.open(\"samplePicture.php?id=".$sampleRow[0]."\")'>
          </div>
          <div class='form-group'>
            <label>Delete picture:</label>
            <input type='checkbox' id='sample_picture_delete' name='sample_picture_delete' value='yes'>
          </div>
          <div class='form-group'>
          <label style='display:block;'>Replace picture:</label>";
      }
      else{
          echo"
          <div class='form-group'>
          <label style='display:block;'>Upload a picture:</label>";
      }
      echo" 
            <input type='hidden' id='sample_ID' name='sample_ID' value='".$sampleID."' >
            <input type='hidden' id='sample_name' name='sample_name' value='".$sampleRow[1]."'>
            <label class='btn btn-default btn-file'>Browse
            <input type='file' id='sample_picture_edit' name='sample_picture_edit' style='display:none;' accept='image/jpg,image/jpeg,image/png,image/bmp,image/gif,image/tif' onchange='$(\"#new_sample_picture_name\").html(getFileName($(this).val()));'>
            </label>
            <span id='new_sample_picture_name'></span>
          </div>
          <div id='error_message_picture_edit'></div>
        </div>
        <div class='modal-footer'>
            <input type='submit' class='btn btn-success' style='float:right;' value='Save'>
            <button type='button' class='btn btn-danger glyphicon glyphicon-trash' style='float:right;' onclick='deleteSample(".$sampleRow[0].",this)' ></button>
        </div>  
      </div>
      </form>
    </div>
  </div>
</div>";

?>
<script>

  document.getElementById('close_modal').onclick = function(){
    modal.style.display = 'none';
    }

  // Picture validation. User can choose to ignore the message, but then the picture will not be uploaded.
$('#sample_picture_edit').bind('change', function() {
  if(this.files[0].size > <?php echo $maxPictureSize?>){
    var errorMessage = "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+ "Sorry, your file is too large. The max size is: " + <?php echo strval(number_format($maxPictureSize/1024/1024,2)); ?>  +" MB.</div>";
    $('#error_message_picture_edit').html(errorMessage);
  }
});

  </script>";