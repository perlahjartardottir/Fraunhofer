<?php
include '../../connection.php';
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

$supplier_ID = $_SESSION['supplier_ID'];
$sql = "SELECT supplier_name, supplier_address, supplier_contact, supplier_phone, supplier_fax, supplier_email,
               supplier_website, supplier_login, supplier_password, supplier_accountNr, supplier_notes
        FROM supplier
        WHERE supplier_ID = '$supplier_ID';";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
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
        <?php echo"
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
          <label>Notes:</label>
          <textarea class='form-control' id='supplier_notes'>".$row[10]."</textarea>
        </div>
        <button type='button' class='btn btn-primary form-control' onclick='editSupplier()'>Edit</button>";
        ?>
      </form>
    </div>
  </div>
</body>
