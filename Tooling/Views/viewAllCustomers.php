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
        <h2>Customers</h2>
        <table id="report" class='col-md-12'>
          <tr>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Address</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Fax Number</th>
            <th>Contact Name</th>
            <th class ='col-md-2'>Notes</th>
          </tr>
          <?php
          $sql = "SELECT customer_ID, customer_name, customer_address, customer_email, customer_phone, customer_fax, customer_contact, customer_notes
                  FROM customer";
          $result = mysqli_query($link, $sql);
          if (!$result){
           die("Database query failed: " . mysql_error());
         }
         while($row = mysqli_fetch_array($result)){
          echo "<tr>".
          "<td>".$row[0]."</td>".
          "<td>".$row[1]."</td>".
          "<td>".$row[2]."</td>".
          // opens the default email program with that emails in the receievers address
          "<td>"."<a href='mailto:$row[3]'>".$row[3]." "."<span class='glyphicon glyphicon-envelope' aria-hidden='true'></span>"."</a>"."</td>".
          // opens the skype call function to that number
          "<td>"."<a href='skype:".$row[4]."?call'"."</a>".$row[4]." <span class='glyphicon glyphicon-earphone' aria-hidden='true'></span>"."</td>".
          "<td>".$row[5]."</td>".
          "<td>".$row[6]."</td>".
          "<td>".$row[7]."</td>".
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
        <div class='col-md-12'>
          <h3>Enter customer ID to change the value in some field of the customer. The customer ID can not be changed!</h3>
          <div class='col-md-12 form-group'>
            <h4>Enter the customer ID number: </h4>
            <input type='number' id='input_customer_ID' class='form-control'/>
          </div>
          <div class='col-md-3 form-group'>
            <label>Change customers name to:</label>
            <input type='text' id='input_customer_name' class='form-control'/>
          </div>
          <div class='col-md-3 form-group'>
            <label>Change customers address to:</label>
            <input type='text' id='input_customer_address' class='form-control'/>
          </div>
          <div class='col-md-3 form-group'>
            <label>Change customers email:</label>
            <input type='text' id='input_customer_email' class='form-control'/>
          </div>
          <div class='col-md-3 form-group'>
            <label>Change customers phone number:</label>
            <input type='text' id='input_customer_phone' class='form-control'/>
          </div>
          <div class='col-md-3 form-group'>
            <label>Change customers faxnumber:</label>
            <input type='text' id='input_customer_fax' class='form-control'/>
          </div>
          <div class='col-md-3 form-group'>
            <label>Change customers contact name:</label>
            <input type='text' id='input_customer_contact' class='form-control'/>
          </div>
          <div class='col-md-3 form-group'>
            <label>Change customers notes:</label>
            <textarea id='input_customer_notes' class='form-control'></textarea>
          </div>
          <div class='col-md-4 form-group'>
            <span><label>Delete Customer:</label>
              <button type='button'  class='btn btn-danger' onclick='deleteCustomer()'>
                <span class='glyphicon glyphicon-trash' aria-hidden='true'></span>
              </button>
            </span>
            <p></p>
          </div>
          <div class='col-md-1 col-md-offset-6'>
              <button type='button' class='btn btn-primary' onclick='changeCustomer()'>Submit changes</button>
          </div>";
    }
    ?>
    </div>
  </form>
  </div>
</body>
</html>
