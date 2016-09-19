<?
include '../../connection.php';
session_start();

$propID = mysqli_real_escape_string($link, $_POST["propID"]);

$sql = "SELECT anlys_prop_name
FROM anlys_property
WHERE anlys_prop_ID = '$propID';";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_row($result);

?>
<div class='modal-dialog'>
  <div class='modal-content '>
    <div class='modal-header'>
      <div class='col-md-12'>
        <button type='button' id='close_modal' class='btn close glyphicon glyphicon-remove' data-dismiss='modal'></button>
    </div>
      <?
        echo"
      <h3 class='center_heading'>Edit ".$row[0]."</h3>";
      ?>
    </div>
    <form id='prop_edit_form' role='form'>
    <div class='modal-body'>
      <div id='error_message'></div>
      <div class='form-group'>
        <label>Name:</label>
      <?
        // There may be special characters in the property name e.g. Young's Modulus
        echo"
        <input type='text' id='edit_prop_name' class='form-control' value='".htmlentities($row[0], ENT_QUOTES)."'>
        <p class='text-muted'>Name must be unique.</p>";
      ?>
      </div>
       <div class='modal-footer'>
        <?
        echo"
            <input type='submit' class='btn btn-success' style='float:right;' value='Save' onclick='editAnlysProperty(".$propID.", this.form)'>";
        ?>
        </div>  
    </div>
    </form>
  </div>
</div>
<script>
document.getElementById('close_modal').onclick = function(){
  modal.style.display = 'none';
}
</script>";

