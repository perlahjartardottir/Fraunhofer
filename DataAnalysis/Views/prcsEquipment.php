<?php
include '../../connection.php';
session_start();

$securityLevel = $_SESSION["securityLevelDA"];

// If the user security level is not high enough we kill the page and give him a link to the log in page.
if($securityLevel < 2){
echo "<a href='../../Login/login.php'>Login Page</a></br>";
die("You don't have the privileges to view this site.");
}

$prcsActiveEqSql = "SELECT prcs_eq_ID, prcs_eq_name, prcs_eq_acronym, prcs_eq_comment
FROM prcs_equipment
WHERE prcs_eq_active = TRUE
ORDER BY prcs_eq_name;";
$prcsActiveEqResult = mysqli_query($link, $prcsActiveEqSql);

$prcsInactiveEqSql = "SELECT prcs_eq_ID, prcs_eq_name, prcs_eq_acronym, prcs_eq_comment
FROM prcs_equipment
WHERE prcs_eq_active = FALSE
ORDER BY prcs_eq_name;";
$prcsInactiveEqResult = mysqli_query($link, $prcsInactiveEqSql);

$prcsAllEqSql = "SELECT prcs_eq_ID, prcs_eq_name, prcs_eq_acronym, prcs_eq_comment, prcs_eq_active
FROM prcs_equipment
ORDER BY prcs_eq_name;";
$prcsAllEqResult = mysqli_query($link, $prcsAllEqSql);

?>

<head>
  <title>Data Analysis</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
  <?php include '../header.php';?>
  <div class="container">
    <!-- Active equipment -->
    <div class='row well well-lg'>
      <div class='col-md-12'>
        <h3 class='custom_heading center_heading'>Process Equipment</h3>
        <table id='prcs_eq_table' class='table table-borderless col-md-12'>
          <thead>
            <tr>
              <th>Name</th>
              <th>Acronym</th>
              <th>Comment</th>
            </tr>
          </thead>
          <tbody>
          <?
          while($row = mysqli_fetch_row($prcsActiveEqResult)){
          	echo"
          		<tr>
          			<td><a href='#' data-toggle='modal' data-target='#".$row[0]."'>".$row[1]."</a></td>
          			<td>".$row[2]."</td>
          			<td>".$row[3]."</td>
          		</tr>";
          }
          ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Inactive equipment -->
    <div class='row well well-lg'>
      <div class='col-md-12'>
        <h3 class='custom_heading center_heading text-muted'>Inactive Process Equipment</h3>
        <table id='prcs_eq_table' class='table table-borderless col-md-12 text-muted'>
          <thead>
            <tr>
              <th>Name</th>
              <th>Acronym</th>
              <th>Comment</th>
            </tr>
          </thead>
          <tbody>
          <?
          while($row = mysqli_fetch_row($prcsInactiveEqResult)){
            echo"
              <tr>
                <td><a href='#' data-toggle='modal' data-target='#".$row[0]."'>".$row[1]."</a></td>
                <td>".$row[2]."</td>
                <td>".$row[3]."</td>
              </tr>";
          }
          ?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Add equipment-->
    <?
   if($securityLevel >= 4){
    echo"
      <div class='row well well-lg'>
        <button type='button' href='#' data-toggle='modal' data-target='#new_eq' class='btn btn-primary form-control' style='padding:0px;'>Add new processing equipment</button>
      </div>";
    }
    echo"
    <div class='modal fade' id='new_eq' tabindex='-1' role='dialog' aria-labelledby='new_eq' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content col-md-12'>
          <form id='new_eq_form' role='form'>
            <div class='modal-header'>
              <div class='col-md-12'>
                <button type='button' id='close_modal' class='btn close glyphicon glyphicon-remove'data-dismiss='modal'></button>
              </div>
              <h3 class='center_heading'>New equipment</h3>
              </div>
            <div class='modal-body'>
              <div id='new_error_message'></div> 
              <div class='form-group'>
                <label>Name:</label>
                <input type='text' id='new_eq_name' name='new_eq_name' class='form-control'>
              </div>
              <div class='form-group'>
                <label>Acronym: </label>
                <input type='text' id='new_eq_acronym' name='new_eq_acronym' class='form-control'>
                <p class='text-muted'>Used when generating process run id. Must be unique.</p>
              </div>
              <div class='form-group'>
                <label>Comment:</label>
                <textarea id='new_eq_comment' name='new_eq_comment' class='form-control'></textarea> 
              </div>
              </div>
              <div class='modal-footer col-md-12'>
                <button type='button' class='btn btn-primary' onclick='addPrcsEquipment(this.form)'>Add</button>
              </div>
          </form>
        </div>
      </div>
    </div>";
    ?>

    <!-- Edit modals -->
    <?
    while($row = mysqli_fetch_row($prcsAllEqResult)){  
      echo "
      <div class='modal fade' id='".$row[0]."' tabindex='-1' role='dialog' aria-labelledby='".$row[0]."' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content col-md-12'>
            <form id='eqForm' role='form'>
              <div class='modal-header'>
                <div class='col-md-12'>
                  <button type='button' id='close_modal' class='btn close glyphicon glyphicon-remove'data-dismiss='modal'></button>
                </div>
                <h3 class='center_heading'>".$row[1]."</h3>";
                if($securityLevel < 4){
                  echo"
                  <div class='alert alert-danger fade in'>
                    <h5 class='center_heading'>You do not have the privileges to edit or delete analysis equipment.</h5>
                  ";
                }
                echo"
              </div>
              <div class='modal-body'>
                <div id='error_message'></div> 
                <div class='form-group'>
                  <label>Name:</label>
                  <input type='text' id='eq_name' name='eq_name' value='".$row[1]."' class='form-control'>
                </div>
                <div class='form-group'>
                  <label>Acronym:</label>
                  <input type='text' id='eq_acronym' name='eq_acronym' value='".$row[2]."' class='form-control'>
                  <p class='text-muted'>Used when generating process run id. Must be unique.</p>
                </div>
                <div class='form-group'>
                  <label>Comment:</label>
                  <textarea id='eq_comment' name='eq_comment' class='form-control'>".$row[3]."</textarea> 
                </div>
                </div>
                <div class='modal-footer col-md-12'>";
                if($securityLevel >= 4){
                  if($row[4] == 1){
                    echo"
                    <button type='button' class='btn btn-danger glyphicon glyphicon-trash' onclick='deletePrcsEquipment(".$row[0].",this.form)'></button>";
                  }
                  else{
                    echo"
                    <button type='button' class='btn btn-success' onclick='activatePrcsEquipment(".$row[0].",this.form)'>Activate</button>";
                  }
                  echo"
                    <button type='button' class='btn btn-success' onclick='editPrcsEquipment(".$row[0].",this.form)'>Save</button>";
                }
            echo"
              </div>
            </form>
          </div>
        </div>
      </div>";
    }
    
    ?>
  </div>
<script>

 $(document).ready(function(){
  $("#nav_overview").button("toggle");
});

</script>
</body>    