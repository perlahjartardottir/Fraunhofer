<?php
include '../../connection.php';
session_start();

$securityLevel = $_SESSION["securityLevelDA"];

if($securityLevel < 4){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}

$propertiesSql = "SELECT anlys_prop_ID, anlys_prop_name, anlys_prop_active
FROM anlys_property
WHERE anlys_prop_active = 1
ORDER BY anlys_prop_name";
$propertiesResult = mysqli_query($link, $propertiesSql);

?>

<head>
</head>
<body>
  <?php include '../header.php';?>
    <div class='container'>
      <div id='error_message'></div>
      <div class='row well well-lg'>
        <form>
          <h2 class='custom_heading'>Coating Properties</h2>
          <div class='col-md-4'>
            <table id='property_table'>
              <thead>
              </thead>
              <tbody>
              <?
                while($row = mysqli_fetch_row($propertiesResult)){
                  echo"
                  <tr>
                    <td><a onclick='loadAndShowPropModalEdit(".$row[0].")'>".$row[1]."</a></td>
                  </tr>";
                }
              ?>
              </tbody>
          </table>
        </div>
        <div class='col-md-4'>
          <h3>Insert new property</h3>
          <h5>You can connect the property to an equipment in Overview > Analysis Equipment. </h5>
          <form>
            <div class='form-group'>
              <label>Name:</label>
                <input type='text' id='new_prop_name' class='form-control'>
                <p class='text-muted'>Name must be unique.</p>
            </div>
            <input type='button' class='btn btn-primary col-md-4' onclick='addNewAnlysProperty(this.form)' value='Add'>
          </form>
        </div>
        </form>
      </div>
    </div>
  <div id='prop_modal_edit' class='modal'></div>
<script>
var modal = document.getElementById('prop_modal_edit');
function loadAndShowPropModalEdit(propID){
  loadAnlysPropModalEdit(propID);
  modal.style.display = 'block';
}
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = 'none';
  }
}
</script>
</body>