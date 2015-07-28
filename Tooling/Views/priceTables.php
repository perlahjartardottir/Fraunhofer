<!DOCTYPE html>
<?php
include '../connection.php';
session_start();
//find the current user
$user = $_SESSION['username'];
//find his level of security
$secsql = "SELECT security_level
           FROM employee
           WHERE employee_name = '$user';";
$secResult = mysqli_query($link, $secsql);

$customerSql = 'SELECT customer_ID, customer_name
                FROM customer
                WHERE customer_ID IN (SELECT customer_ID
                                      FROM price);';
$customerResult = mysqli_query($link, $customerSql);

while ($row = mysqli_fetch_array($secResult)) {
    $user_sec_lvl = $row[0];
}
?>
<html>
<head>
  <title>Fraunhofer CCD</title>
  <link href='../css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
<?php include '../header.php'; ?>
<script src="../js/report.js"></script>
  <div class='container'>
    <div class='row well well-lg'>
      <div class='col-md-12'>
        <h2>Price tables</h2>
        <p>If a customer is not on this list that means he has no prices in his price table.</br>
           You can add prices to all customers below</p>
        <div class='btn-group'>
          <?php
          while ($customerRow = mysqli_fetch_array($customerResult)) {
              echo "<button type='button' class='btn btn-primary' onclick='showPriceTable(".$customerRow[0].")'>".$customerRow[1].'</button>';
          }
          ?>
        </div>
        <div id='priceTable' class='table-responsive'></div>
      </div>
    </div>
    <?php
      if ($user_sec_lvl >= 4) {
          echo"
              <div class='row well well-lg'>
                <div id='invalidPrice'></div>
                <div class='col-md-12'>
                  <form>
                    <h3>Add/Edit a single price in the price table</h3>
                    <p>If there already is a price set for the selected customer, diamter and length
                    the table is updated. If there is no price it will be inserted.
                    </p>
                    <div class='col-md-4 form-group'>
                    <select id='customer_select' onchange='generateCustomerPrice()' onfocus='generateCustomerPrice()' class='form-control'>";
                    $customer_sql = "SELECT customer_ID, customer_name
                                     FROM customer;";
                    $customerResult = mysqli_query($link, $customer_sql);
                    while($customerRow = mysqli_fetch_array($customerResult)){
                        echo "<option value='".$customerRow[0]."'>".$customerRow[1]."</option>";
                    }
              echo "
                  </select>
                </div>
                <div class='col-md-4 form-group'>
                  <select id='diameter_select' onchange='generateCustomerPrice()' onfocus='generateCustomerPrice()' class='form-control'>
                    <option value=''>Diameter</option>
                    <option value='1/8'>1/8</option>
                    <option value='3/16'>3/16</option>
                    <option value='1/4'>1/4</option>
                    <option value='3/8'>3/8</option>
                    <option value='1/2'>1/2</option>
                    <option value='5/8'>5/8</option>
                    <option value='3/4'>3/4</option>
                    <option value='1'>1</option>
                    <option value='1 1/4'>1 1/4</option>
                    <option value='1 3/8'>1 3/8</option>
                  </select>
                </div>
                <div class='col-md-4 form-group'>
                  <select id='length_select' name='length' onchange='generateCustomerPrice()' onfocus='generateCustomerPrice()' class='form-control'>
                    <option value=''>Length</option>
                    <option value='2'>2</option>
                    <option value='3'>3</option>
                    <option value='4'>4</option>
                    <option value='5'>5</option>
                    <option value='6'>6</option>
                    <option value='7'>7</option>
                    <option value='8'>8</option>
                    <option value='9'>9</option>
                  </select>
                </form>
                </div>
                <div class='col-md-12'>
                  <form class='form-inline'>
                    <span>Current price: <input type='text' id='current_price' value='0.0' disabled class='form-control'></input></span>
                    <span style='margin-left:10px;'>New price: <input type='number' id='new_price' class='form-control'></input></span>
                    <input type='button' class='btn btn-primary' value='Update price' onclick='updateCustomerPrice()' id='new_price'></input>
                  </form>
              </div>
            </div>
            </div>
            <div class='row well well-lg'>
              <div class='col-md-12'>
                <div id='invalidMultiplier'></div>
              <h3>Update all prices for a customer</h3>
              <p>Enter a customer and a multiplier.</p>
              <p><code>Preview changes</code> updates the table above but does not change the prices in the database.</p>
              <p><code>Apply changes</code> updates the prices in the database. <strong>You can not undo this action</strong></p>
              <form class='form-inline'>
              <select id='customer_select_multiply' onchange='generateCustomerPrice()' onfocus='generateCustomerPrice()' class='form-control'>";
              $customerResult = mysqli_query($link, $customer_sql);
              while($customerRow = mysqli_fetch_array($customerResult)){
                  echo "<option value='".$customerRow[0]."'>".$customerRow[1]."</option>";
              }
            echo "
                </select>
                <span style='margin-left:10px;'>Multiplier: <input type='number' id='price_multiplier' value='1' step='0.01' class='form-control'></input></span>
                <input type='button' class='btn btn-success' value='Preview changes' onclick='showPriceTableWithMultiplier()' id=''></input>
                <input type='button' class='btn btn-primary' value='Apply changes' onclick='updateAllCustomerPrice()' id=''></input>
              </form>
            </div>
          </div>";
      }
    ?>
  </div>
</body>
</html>
