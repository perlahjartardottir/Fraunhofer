<?
include '../../connection.php';
session_start();

$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);

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
    <form role='form' action='../UpdatePHP/editSample.php' method='post' enctype='multipart/form-data'>
      <div class='modal-header'>
        <div class='col-md-12'>
          <button type='button' id='close_modal' class='btn close glyphicon glyphicon-remove' data-dismiss='modal'></button>
        </div>
        <h3 class='center_heading'>".$sampleRow[1]."</h3>
      </div>
      <div class='modal-body'>
        <div id='error_message'></div>
        <div class='form-group'>
          <label>Material: </label>
          <input list='materials_edit' id='material_edit' class='col-md-12 form-control' value='".$sampleRow[2]."'>
          <datalist id='materials_edit'>";
            while($row = mysqli_fetch_array($materialsResult)){
              echo"<option data-value='".$row[0]."'>".$row[0]."</option>";
            }
            echo"
          </datalist>
          <input type='hidden' name='material_edit' id='material_edit-hidden'>
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
          </div>";
      }
      echo" 
          <div class='form-group'>
            <input type=hidden id='sample_ID' name='sample_ID' value='".$sampleID."' >
            <input type=hidden id='sample_name' name='sample_name' value='".$sampleRow[1]."'>
            <input type=hidden id='sample_set_name' name='smaple_set_name' value='".$sampleSetName."'>
            <label style='display:block;'>Upload new/replace picture:</label>
            <label class='btn btn-default btn-file'>Browse
            <input type='file' id='sample_picture' name='sample_picture' style='display: none;' onchange='$(\"#new_sample_picture_name\").html(getFileName($(this).val()));'>
            </label>
            <span id='new_sample_picture_name'></span>
          </div>
        </form>
        ";
    echo"
    <div class='modal-footer'>
            <div class='form-group'>
          <button type='submit' class='btn btn-success' style='float:right;' onclick='editSample(".$sampleRow[0].",this)'>Save</button> 
          <button type='button' class='btn btn-danger glyphicon glyphicon-trash' style='float:right;' onclick='deleteSample(".$sampleRow[0].",this)' ></button>
        </div>
      </div>
    </div>
  </div>
</div>
</div>";

?>
<script>

$('#material_edit-hidden').val(document.getElementById('material_edit').value);

  document.getElementById('close_modal').onclick = function(){
    modal.style.display = 'none';
    }

  //Material: So user can both choose from datalist and enter text. 
  $('input[list]').on('input', function(e) {
    var input = $(e.target),
    options = $('#' + input.attr('list') + ' option'),
    hiddenInput = $('#' + input.attr('id') + '-hidden'),
    label = input.val();

    hiddenInput.val(label);

    for(var i = 0; i < options.length; i++) {
      var option = options.eq(i);

      if(option.text() === label) {
        hiddenInput.val( option.attr('data-value') );
        break;
      }
    }
  });
  </script>";