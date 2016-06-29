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

  $analysisEqSql = "SELECT *
  FROM anlys_equipment";
  $analysisEqResult = mysqli_query($link, $analysisEqSql);
  if (!$analysisEqResult){
   die("Database query failed: " . mysql_error());
  }

 ?>

 <head>
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
          while($row = mysqli_fetch_array($analysisEqResult)){
            echo "<tr>
            <td><a href='#' data-toggle='modal' data-target='#".$row[0]."'>".$row[1]."</a></td>
            <td>".$row[2]."</td>
          </tr>";
        }
        ?>
      </table>
    </div>
  </div>
  </div>
  <?php
  // Modal window to edit analysis equipment.
   $analysisEqResult2 = mysqli_query($link, $analysisEqSql);
   while($row = mysqli_fetch_array($analysisEqResult2)){  
    echo "
      <div class='modal fade' id='".$row[0]."' tabindex='-1' role='dialog' aria-labelledby='".$row[0]."' aria-hidden='true'>
        <div class='modal-dialog'>
          <div class='modal-content col-md-12'>
            <div class='modal-header'>
              <center><h3>".$row[1]."</h3></center>
            </div>
            <div class='modal-body'>
              <form id='propertyForm' role='form'>
                <div class='form-group'>
                  <label>Name</label>
                  <input type='text' id='eq_name' value='".$row[1]."' class='form-control'>
                </div>
                <div class='form-group'>
                  <label>Comment</label>
                  <textarea id='eq_comment' class='form-control'>".$row[2]."</textarea> 
                </div>
                <center><h4>Properties</h4></center>";
                $propCounter = 1;
                $analysisPropertySql = "SELECT anlys_prop_name
                                        FROM anlys_property
                                        WHERE anlys_eq_ID = '$row[0]';";
                $analysisPropertyResult = mysqli_query($link, $analysisPropertySql);
                //$meta = mysql_fetch_field($analysisPropertyResult, 0);
                while($propRow = mysqli_fetch_array($analysisPropertyResult)){
                  echo " 
                  <div class='form-group'>
                  <label>Property ".$propCounter."</label>
                  <input type='text' name='prop_name' value='".$propRow[0]."' class='form-control'>
                  </div>";
                  $propCounter++;        
                }    
                echo "
              </form>
            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-success' onclick='editAnalysisEquipment(".$row[0].",this)'>Edit</button>
              <button type='button' class='btn btn-danger' onclick=''>Delete</button>
              <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
            </div>
          </div>
        </div>
      </div>";
    }
  ?>
</body>