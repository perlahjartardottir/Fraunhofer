<?
include '../../connection.php';
session_start();

$sampleID = mysqli_real_escape_string($link, $_POST["sampleID"]);

$sql = "SELECT sample_ID, sample_name, sample_material, sample_comment
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
    <form role='form'>
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
          <label>Comment</label>
          <textarea id='sample_comment' class='form-control'>".$sampleRow[3]."</textarea> 
        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-danger glyphicon glyphicon-trash' onclick='deleteSample(".$sampleRow[0].",this.form)' ></button>
          <button type='button' class='btn btn-success' onclick='editSample(".$sampleRow[0].",this.form)'>Save</button> 
        </div>
      </form>
    </div>
  </div>
</div>
</div>";

//Material: So user can both choose from datalist and enter text. 
?>
<script>

$('#material_edit-hidden').val(document.getElementById('material_edit').value);
console.log(document.getElementById('material_edit').value);

  document.getElementById('close_modal').onclick = function(){
    modal.style.display = 'none';
    }

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