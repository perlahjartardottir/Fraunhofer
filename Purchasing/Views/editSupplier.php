<?php
include '../../connection.php';
session_start();
$supplier_ID = $_SESSION["supplier_ID"];
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
$user_sec_lvl = $user_sec_lvl[1];
// if the user security level is not high enough we kill the page and give him a link to the log in page
if($user_sec_lvl < 3){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}
$sql = "SELECT supplier_name, supplier_address, supplier_contact, supplier_phone, supplier_fax, supplier_email,
               supplier_website, supplier_login, supplier_password, supplier_accountNr, supplier_notes, net_terms, credit_card
        FROM supplier
        WHERE supplier_ID = '$supplier_ID';";
$result = mysqli_query($link, $sql);
if(!$result){
	die("Could not find supplier: ".mysqli_error($link));
}
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <div class='container'>
    <div class='row well well-lg'>
      <h5>*Only the fields you edit will be changed, all other fields will remain unchanged</h5>
      <form>
        <?php
        $row = mysqli_fetch_array($result);
        echo"
        <h3>Edit supplier</h3>
        <div class='form-group col-md-4'>
          <label>Name:</label>
          <input type='text' class='form-control' id='supplier_name' value='".$row[0]."'>
        </div>
        <div class='form-group col-md-4'>
          <label>Address:</label>
          <input type='text' class='form-control' id='supplier_address' value='".$row[1]."'>
        </div>
        <div class='form-group col-md-4'>
          <label>Contact:</label>
          <input type='text' class='form-control' id='supplier_contact' value='".$row[2]."'>
        </div>
        <div class='form-group col-md-4'>
          <label>Phone</label>
          <input type='text' class='form-control' id='supplier_phone' value='".$row[3]."'>
        </div>
        <div class='form-group col-md-4'>
          <label>Fax:</label>
          <input type='text' class='form-control' id='supplier_fax' value='".$row[4]."'>
        </div>
        <div class='form-group col-md-4'>
          <label>Email:</label>
          <input type='text' class='form-control' id='supplier_email' value='".$row[5]."'>
        </div>
        <div class='form-group col-md-4'>
          <label>Website:</label>
          <input type='text' class='form-control' id='supplier_website' value='".$row[6]."'>
        </div>
        <div class='form-group col-md-4'>
          <label>Login:</label>
          <input type='text' class='form-control' id='supplier_login' value='".$row[7]."'>
        </div>
        <div class='form-group col-md-4'>
          <label>Password:</label>
          <input type='text' class='form-control' id='supplier_password' value='".$row[8]."'>
        </div>
        <div class='form-group col-md-4'>
          <label>Account Nr.:</label>
          <input type='text' class='form-control' id='supplier_accountNr' value='".$row[9]."'>
        </div>
        <div class='form-group col-md-4'>
          <label>Net terms (in days):</label>
          <input type='number' class='form-control' id='net_terms' value='".$row[11]."'>
        </div>
        <div class='form-group col-md-4'>
          <label>Credit card required:</label>
          <br>";
          if($row[12] == 1){
            echo "<input checked type='checkbox' id='credit_card' value='1'>";
          }
          else{
            echo "<input type='checkbox' id='credit_card' value='1'>";
          }
        echo"  
        </div>
        <div class='form-group col-md-12'>
          <label>Notes:</label>
          <br>
          <div class='col-md-4' style='padding:0px;'>
          <textarea class='form-control' id='supplier_notes'>".$row[10]."</textarea>
          </div>
        </div>
        <button type='button' class='btn btn-primary form-control' onclick='editSupplier(".$supplier_ID.")'>Edit</button>";
        ?>
      </form>
    </div>
  </div>
</body>
