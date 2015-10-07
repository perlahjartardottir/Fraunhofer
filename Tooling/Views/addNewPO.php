<!DOCTYPE html>
<?php
include '../connection.php';
session_start();
//find the current user
$user = $_SESSION["username"];
//find his level of security
$secsql = "SELECT security_level, employee_ID
           FROM employee
           WHERE employee_name = '$user'";
$secResult = mysqli_query($link, $secsql);

while($row = mysqli_fetch_array($secResult)){
  $user_sec_lvl = $row[0];
  $employee_ID = $row[1];
}
// if the users security level is to low he cant access this page.
if($user_sec_lvl < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
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
      <?php echo "<input type='hidden' id='employeeId' value='".$employee_ID."'>"; ?>
        <div class='container'>
          <div id='invalidPO'></div>
          <div class='row well well-lg'>
            <form>
              <h3>Add new PO</h3>
              <p class='col-md-4 form-group'>
                <label for="POID">PO number: </label>
                <input type="text" name="POID" id="POID" class='form-control'>
              </p>
              <p class='col-md-4 form-group'>
                <label for="CID">Company: </label>
                <select id='CID' class='form-control'>
                  <option value="">Company:</option>
                  <?php
                    $sql = "SELECT customer_ID, customer_name
                            FROM customer";
                    $result = mysqli_query($link, $sql);

                    if (!$result) {
                      die("Database query failed: " . mysqli_error($link));
                    }
                    while($row = mysqli_fetch_array($result)){
                      echo '<option value="'.$row['customer_ID'].'">'.$row['customer_name'].'</option>';
                    }
                  ?>
                </select>
              </p>
              <p class='col-md-4 form-group'>
                <label for="rDate">Receiving Date:</label>
                <input type="date" value="<?php echo date('Y-m-d'); ?>" name='rDate' id='rDate' class='form-control'>
              </p>
              <p class='col-md-4 form-group'>
                <label for="iInspect">Initial Inspection:</label>
                <input type="text" name="iInspect" id="iInspect" class='form-control'>
              </p>
              <p class='col-md-4 form-group'>
                <label for="nrOfLines">Number of Lines:</label>
                <input type="number" name='nrOfLines' id='nrOfLines' class='form-control'>
              </p>
              <p class='col-md-4 form-group'>
                <label for="shipping_sel">Shipping info:</label>
                <select id='shipping_sel' class='form-control'>
                  <option value='Ground'>Ground</option>
                  <option value='3 day'>3 day</option>
                  <option value='2 day'>2 day</option>
                  <option value='next day'>Next day</option>
                  <option value='fedex'>Fedex</option>
                  <option value='other'>Other</option>
                </select>
              </p>
              <div class='form-group'>
                <input class='col-md-offset-9 btn btn-primary' type="button" onclick='addPO()' value="Add PO">
                <button type='button' class='btn btn-primary' onclick="location.href='addTools2.php'">Add tools to PO</a>
                </button>
              </div>
            </form>
          </div>
        </div>
  </body>
  </html>
