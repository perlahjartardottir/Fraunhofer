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
$user_sec_lvl = str_split($user_sec_lvl);
$user_sec_lvl = $user_sec_lvl[0];
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
        <h2>Coatings</h2>
        <table id="report" class='col-md-12'>
          <tr>
            <th>Machine ID</th>
            <th>Coating type</th>
            <th>Coating Description</th>
          </tr>
          <?php
            $sql ="SELECT *
                   FROM coating";
            $result = mysqli_query($link, $sql);

            if (!$result){
              die("Database query failed: " . mysql_error());
           }
           while($row = mysqli_fetch_array($result)){
              echo "<tr>".
              "<td>".$row[0]."</td>".
              "<td>".$row[1]."</td>".
              "<td>".$row[2]."</td>".
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
        <div class='row well well-lg'>
          <form>
            <h4>Enter Coating ID to insert or change some values in the table. The coating ID can not be changed!</h4>
            <div class='col-md-4 form-group'>
              <label>Enter the Coating ID Number</label>
              <input type='number' id='input_coating_ID' class='form-control'/>
            </div>
              <div class='col-md-4 form-group'>
                <label>Change coating type:</label>
                <input type='text' id='input_coating_type' class='form-control'/>
              </div>
              <div class='col-md-4 form-group'>
                <label>Change coating description:</label>
                <input type='text' id='input_coating_description' class='form-control'/>
              </div>
          <div class='col-md-3'>
              <span><label>Delete Coating:</label>
                <button type='button' class='btn btn-danger' onclick='deleteCoating()'>
                  <span class='glyphicon glyphicon-trash' aria-hidden='true'></span>
                </button>
              </span>
            </div>
            <div class='col-md-2' style='float:right;'>
              <button value='Submit' onclick='changeCoating()' class='btn btn-primary'>Submit changes</button>
            </div>
          </form>
        </div>";
      }
    if($user_sec_lvl >3)
    {
      echo"
        <div class='row well well-lg'>
          <form>
          <h4>Add a new coating</h4>
              <div class='col-md-4 form-group'>
                <label>Coating type:</label>
                <input type='text' id='coating_type' class='form-control'/>
              </div>
              <div class='col-md-4 form-group'>
                <label>Coating description:</label>
                <input type='text' id='coating_description' class='form-control'/>
              </div>
            <div class='col-md-4'>
              <button value='Submit' onclick='addCoating()' class='btn btn-primary col-md-12' style='float:right; margin-top:24px'>Insert</button>
            </div>
          </form>";
      }
  ?>
</div>
</body>
</html>
