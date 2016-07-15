  <?php
  include '../../connection.php';
  session_start();

  $securityLevel = $_SESSION["securityLevelDA"];

  // if the user security level is not high enough we kill the page and give him a link to the log in page
  if($securityLevel < 4){
    echo "<a href='../../Login/login.php'>Login Page</a></br>";
    die("You don't have the privileges to view this site.");
  } 

  $analysisEqSql = "SELECT e.anlys_eq_ID, e.anlys_eq_name, e.anlys_eq_comment, a.anlys_prop_ID, p.anlys_prop_name
  FROM anlys_equipment e, anlys_eq_prop a, anlys_property p
  WHERE e.anlys_eq_ID = a.anlys_eq_ID AND a.anlys_prop_ID = p.anlys_prop_ID AND e.anlys_eq_active = TRUE
  GROUP BY e.anlys_eq_ID
  ORDER BY e.anlys_eq_name;";
  $analysisEqResult = mysqli_query($link, $analysisEqSql);
  if (!$analysisEqResult){
   die("Database query failed: " . mysql_error());
 }

  $analysisInactiveEqSql = "SELECT e.anlys_eq_ID, e.anlys_eq_name, e.anlys_eq_comment, a.anlys_prop_ID, p.anlys_prop_name
  FROM anlys_equipment e, anlys_eq_prop a, anlys_property p
  WHERE e.anlys_eq_ID = a.anlys_eq_ID AND a.anlys_prop_ID = p.anlys_prop_ID AND e.anlys_eq_active = FALSE
  GROUP BY e.anlys_eq_ID
  ORDER BY e.anlys_eq_name;";
  $analysisInactiveEqResult = mysqli_query($link, $analysisInactiveEqSql);
  if (!$analysisInactiveEqResult){
   die("Database query failed: " . mysql_error());
 }

  $allEqSql = "SELECT e.anlys_eq_ID, e.anlys_eq_name, e.anlys_eq_comment, a.anlys_prop_ID, p.anlys_prop_name
  FROM anlys_equipment e, anlys_eq_prop a, anlys_property p
  WHERE e.anlys_eq_ID = a.anlys_eq_ID AND a.anlys_prop_ID = p.anlys_prop_ID
  GROUP BY e.anlys_eq_ID
  ORDER BY e.anlys_eq_name;";
  $allEqResult = mysqli_query($link, $allEqSql);
  if (!$allEqResult){
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
        <h3 class='custom_heading center_heading'>Analysis Equipment</h2>
          <table id='front_table' class='table table-borderless col-md-12'>
            <thead>
              <tr>
                <th>Name</th>
                <th>Property</th>
                <th>Comment</th>
              </tr>
            </thead>
            <tbody>
              <?php
              while($row = mysqli_fetch_array($analysisEqResult)){
                echo "<tr>
                <td><a href='#' data-toggle='modal' data-target='#".$row[0]."'>".$row[1]."</a></td>
                <td>".$row[4]."</td>
                <td>".$row[2]."</td>
              </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Inactive equipment-->
    <div class='row well well-lg'>
      <div class='col-md-12'>
        <h3 class='custom_heading center_heading'>Inactive Analysis Equipment</h2>
          <table id='front_table' class='table table-borderless col-md-12'>
            <thead>
              <tr>
                <th>Name</th>
                <th>Property</th>
                <th>Comment</th>
              </tr>
            </thead>
            <tbody>
              <?php
              while($row = mysqli_fetch_array($analysisInactiveEqResult)){
                echo "<tr>
                <td><a href='#' data-toggle='modal' data-target='#".$row[0]."'>".$row[1]."</a></td>
                <td>".$row[4]."</td>
                <td>".$row[2]."</td>
              </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
  </div>

  <?php
  // Modal window to edit analysis equipment.
  while($row = mysqli_fetch_array($allEqResult)){  
    echo "
    <div class='modal fade' id='".$row[0]."' tabindex='-1' role='dialog' aria-labelledby='".$row[0]."' aria-hidden='true'>
      <div class='modal-dialog'>
        <div class='modal-content col-md-12'>
          <form id='eqForm' role='form'>
            <div class='modal-header'>
              <div class='col-md-12'>
                <button type='button' id='close_modal' class='btn close glyphicon glyphicon-remove'data-dismiss='modal'></button>
              </div>
             <center><h3>".$row[1]."</h3></center>
            </div>
            <div class='modal-body'>
              <div id='error_message'></div> 
              <div class='form-group'>
                <label>Name:</label>
                <input type='text' id='eq_name' name='eq_name' value='".$row[1]."' class='form-control'>
              </div>
              <div class='form-group'>
                <label>Comment:</label>
                <textarea id='eq_comment' name='eq_comment' class='form-control'>".$row[2]."</textarea> 
              </div>
              <center><h3>Properties</h3></center>";
              $propCounter = 1;
              $analysisPropertySql = "SELECT p.anlys_prop_ID, p.anlys_prop_name
              FROM anlys_property p, anlys_eq_prop a
              WHERE p.anlys_prop_ID = a.anlys_prop_ID AND a.anlys_eq_id = '$row[0]';";
              $analysisPropertyResult = mysqli_query($link, $analysisPropertySql);
              while($propRow = mysqli_fetch_array($analysisPropertyResult)){
                echo " 
                <div class='form-group'>
                 <label>Property ".$propCounter.":</label>
                 <br>
                 <input type='hidden' name='prop_ID' value='".$propRow[0]."'>
                 <input type='text' name='prop_name' value='".$propRow[1]." 'class='col-md-8'>
                 <button type='button' class='btn btn-danger glyphicon glyphicon-trash'' onclick='deleteAnalysisEqProperty(".$propRow[0].",".$row[0].",this.form)'></button>
               </div>";

               $propCounter++;
             }    
             echo "
             <div id='prop' class='form-group'>
               <button type='button' id='button' class='btn btn-primary' onclick='addProp(this)'>Add a new property</button>
             </div>
           </div>

           <div class='modal-footer'>
              <button type='button' class='btn btn-danger glyphicon glyphicon-trash' onclick='deleteAnalysisEquipment(".$row[0].",this.form)'></button>
             <button type='button' class='btn btn-success' onclick='editAnalysisEquipment(".$row[0].",this.form)'>Save</button>
           </div>
         </form>
       </div>
     </div>
   </div>";
 }
 ?>

 <script>
  var divCounter = 1;
  function addProp(elem){  
    newProp = "<div id='new_prop_"+divCounter+"' class='form-group'>"+
    "<label>New property:</label>"+
    "<br>"+
    "<input type='hidden' name='prop_ID' value='-1'>"+
    "<input type='text' id='new_prop_name' name='prop_name' size='35'>"+
    "<button type='button'  class='btn btn-danger glyphicon glyphicon-trash' onclick='hideDiv(this)'></button>"+
    "</div>";

    $(elem).parent().prev().append(newProp);
    divCounter++;
  }

  function hideDiv(elem){
    document.getElementById($(elem).parent().attr("id")).hidden = true;
  }

</script>
</body>
