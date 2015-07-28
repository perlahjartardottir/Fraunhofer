<!DOCTYPE html>
<?php
include '../connection.php';
session_start();
//find the current user
$user = $_SESSION["username"];
//find his level of security
$secsql = "SELECT security_level
           FROM employee
           WHERE employee_name = '$user'";
$secResult = mysqli_query($link, $secsql);

while($row = mysqli_fetch_array($secResult)){
  $user_sec_lvl = $row[0];
}
?>
<html>
<head>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
  <link href='../css/main.css' rel='stylesheet'>
</head>
<body>
<?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well well-lg'>
      <div class='col-md-12'>
        <h2>Machines</h2>
        <table id="report" class='col-md-12'>
          <tr>
            <th>Machine ID</th>
            <th>Machine Name</th>
            <th>Machine Acronym</th>
            <th>Comment</th>
          </tr>
          <?php
            $sql ="SELECT *
                   FROM machine";
            $result = mysqli_query($link, $sql);
            if (!$result){
             die("Database query failed: " . mysql_error());
           }
           while($row = mysqli_fetch_array($result)){
            echo "<tr>".
            "<td>".$row[0]."</td>".
            "<td>".$row[1]."</td>".
            "<td>".$row[2]."</td>".
            "<td>".$row[3]."</td>".
            "</tr>";
          }
        ?>
      </table>
    </div>
  </div>
  <?php
    if($user_sec_lvl >=3)
    {
      echo"
        <div id='invalidMachine'></div>
        <div class='row well well-lg'>
          <form>
            <h3>Enter Machine ID to insert or change some values in the table. The Machine ID can not be changed!</h3>
            <div class='col-md-3 form-group'>
              <label>Enter the Machine ID</label>
              <input type='number' id='input_machine_ID' class='form-control'/></br>
            </div>
            <div class='col-md-3 form-group'>
              <Label>Change machine name:</Label>
              <input type='text' id='input_machine_name' class='form-control'/>
            </div>
            <div class='col-md-3 form-group'>
              <Label>Change machine acronym:</Label>
              <input type='text' id='input_machine_acronym' class='form-control'/>
            </div>
            <div class='col-md-3 form-group'>
              <Label>Change machine comment:</Label>
              <input type='text' id='input_machine_comment' class='form-control'/>
            </div>
            <div class='col-md-10 form-group'>
              <label>Delete machine:</label>
              <button type='button' class='btn btn-danger' onclick='deleteMachine()'>
                <span class='glyphicon glyphicon-trash' aria-hidden='true'></span>
              </button>
            </div>
            <div class='col-md-2'>
            <input type='button' value='Submit' onclick='changeMachine()' class='btn btn-primary' style='float:right;'/>
          </div>
          </form>
        </div>";
    }
  ?>
</div>
</body>
</html>
