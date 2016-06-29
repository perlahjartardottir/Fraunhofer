<!-- This is the front page -->
<!DOCTYPE html>
<html>
<head>
  <?php
  include '../../connection.php';
  session_start();
  // find the current user
  $user = $_SESSION["username"];
  // find his level of security
  $securitySql = "SELECT security_level
  FROM employee
  WHERE employee_name = '$user'";
  $securityResult = mysqli_query($link, $securitySql);
  while($row = mysqli_fetch_array($securityResult)){
    $securityLevel = $row[0];
  }
  // Get the third digit from the security level since that digit represents the
  // security level of the data analysis database
  $securityLevel = str_split($securityLevel);
  $securityLevel = $securityLevel[2];

  // // if the user security level is not high enough we kill the page and give him a link to the log in page
  // if($securityLevel < 4){
  //   echo "<a href='../../Login/login.php'>Login Page</a></br>";
  //   die("You don't have the privileges to view this site.");
  // } 

  ?>

  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
  <?php include '../header.php';?>
  <div class="container">
  <div class='row well well-lg'>
  <div class='col-md-12'>
  <h2>Analysis Equipment</h2>
        <table id="report" class='col-md-12'>
          <tr>
            <th>Machine Name</th>
            <th>Comment</th>
          </tr>
          <?php
            $sql ="SELECT *
                   FROM anlys_equipment";
            $result = mysqli_query($link, $sql);
            if (!$result){
             die("Database query failed: " . mysql_error());
           }
           while($row = mysqli_fetch_array($result)){
            echo "<tr>".
            "<td>".$row[1]."</td>".
            "<td>".$row[2]."</td>".
            "</tr>";
          }
        ?>
      </table>
  </div>
  </div>
  </div>
</body>