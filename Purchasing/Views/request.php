<?php
include '../../connection.php';
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
$user_sec_lvl = str_split($user_sec_lvl);
$user_sec_lvl = $user_sec_lvl[1];
// if the user security level is not high enough we kill the page and give him a link to the log in page
if($user_sec_lvl < 2){
  echo "<a href='../../Login/login.php'>Login Page</a></br>";
  die("You don't have the privileges to view this site.");
}

$sql = "SELECT quote_ID, image, quote_number, supplier_ID, quote_date
        FROM quote
        WHERE create_request = 1;";
$result = mysqli_query($link, $sql);

$departmentSql = "SELECT department_name
                  FROM department;";
$departmentResult = mysqli_query($link, $departmentSql);

// Query for supplier list
$supplierSql = "SELECT supplier_name, credit_card
                FROM supplier
                ORDER BY supplier_name;";
$supplierResult = mysqli_query($link, $supplierSql);
?>
<head>
  <title>Fraunhofer CCD</title>
</head>
<body>
  <?php include '../header.php'; ?>
  <?php echo "<input type='hidden' id='employee_ID' value='".$employee_ID."'>"; ?>
  <div class='container'>
    <?php if(mysqli_num_rows($result) > 0){ ?>
    <div class='row well'>
      <h3><center>Quotes</center></h3>
      <div class='col-md-12'>
        <?php
        while($row = mysqli_fetch_array($result)){
          $supplierNameSql = "SELECT supplier_name
                              FROM supplier
                              WHERE supplier_ID = '$row[3]';";
          $supplierNameResult = mysqli_query($link, $supplierNameSql);
          $supplierNameRow = mysqli_fetch_array($supplierNameResult);
          echo"<div class='col-md-4'>
                  <div class='col-md-5'>
                  <p><input type='image' src='../Scan/getQuoteImage.php?id=".$row[0]."' width='100' height='100' onerror=\"this.src='../images/noimage.jpg'\" onclick=\"window.open('../Printouts/quotePrintout.php?id=".$row[0]."')\"/></p>
                  <button class='btn btn-danger' style='margin-top:5px; margin-right:200px' onclick='removeQuoteFromRequest(".$row[0].")'>Deactivate</button>
                </div>
                <div class='col-md-7'>
                  <p><strong>Quote number: </strong><a href='../SelectPHP/download.php?id=".$row[0]."'>".$row[2]."</a><br></p>
                  <p><strong>Supplier: </strong>".$supplierNameRow[0]."</p>
                  <p><strong>Date issued: </strong>".$row[4]."</p>
                </div>
               </div>";
        }
         ?>
      </div>
    </div>
    <?php } ?>
    <div class='row well well-lg'>
      <h5>To add quotes to the request, please add them <strong><u>before</u></strong> you create the request (Press Quotes button on Home view).</h5>
      <h5>*Required.</h5>
      <form id='requestForm'>
        <div id='invalidRequest'></div>
        <div id='requestSent'></div>
        <div id='emailSent'></div>
        <h3>Make a request for a purchase order</h3>
        <div class='col-md-4 form-group'>
          <label>Supplier: </label>
          <input type='text' list="suppliers" name="supplierList" id='supplierList' class='col-md-12 form-control'>
          <datalist id="suppliers">
            <?
            while($row = mysqli_fetch_array($supplierResult)){
              if($row[1]){
                 echo"<option value='".$row[0]."'>Credit card</option>";
              }
              else{
                echo"<option value='".$row[0]."'></option>";
              }
              
            }
            ?>
          </datalist>
        </div>
        <div class='col-md-8 form-group'>
          <div class='col-md-6' style='padding:0px;'>
            <label>Required by: * </label>
            <select id='orderTimeframe' class='form-control' onchange='displayDate()'>
              <option value='With next order'>With next order</option>
              <option value='Specific date'>Specific date</option>
              <option value='Other'>Other</option>
             </select>
            </div>
            <div class='col-md-6'>
            <label class='orderTimeframeDate'>Required by date:*</label>
              <input type='date' id='orderTimeframeDate' class='form-control orderTimeframeDate' value='<?php echo date("Y-m-d"); ?>'>
            </div>
        </div>
        <div class='col-md-4 form-group'>
          <label>Department: *</label>
          <select id='department' class='form-control' onchange='updateCostCode()'>
            <option value=''>All departments</option>
            <?php
            while($departmentRow = mysqli_fetch_array($departmentResult)){
              echo "<option value='".$departmentRow[0]."'>".$departmentRow[0]."</option>";
            }?>
          </select>
       </div>
       <div class='col-md-8 form-group'>
        <div class='col-md-6' style='padding:0px;'>
            <label>Cost code: * </label>
            <div class='result'></div>
        </div>
        </div>

        <div class='col-md-4 form-group'>
          <label>Part #: </label>
          <input type="text" id='part_number' class='form-control'>
        </div>
        <div class='col-md-2 form-group'>
          <label>Quantity: *  </label>
          <input type="number" id='quantity' class='form-control'>
        </div>
        <div class='col-md-2 form-group'>
          <label>Unit price:  </label>
          <input type='number' id='unit_price' class='form-control' onclick='calcTotalPrice()'>
        </div>
        <div class='col-md-2 form-group'>
          <label>Total price:  </label>
          <input type='number' id='request_price' class='form-control'>
        </div>
        <div class='col-md-4 form-group'>
          <label>Description: </label>
          <textarea id='request_description' class='form-control' rows='4'></textarea>
        </div>
      </div>

        <input class='form-control btn btn-primary' type="button" value="Request - I have another order!" onclick='orderRequest("",this.form)' style='margin-top:25px;'>
      <input class='form-control btn btn-primary' type="button" value="Request - I'm done!" onclick='orderRequest("yes", this.form)' style='margin-top:25px;'>
      </form>

    </div>
  </div>
  <script>
  $(document).ready(function() {
      updateCostCode();
      $(".orderTimeframeDate").hide();
  });

  function displayDate() {
    if($("#orderTimeframe").val() == "Specific date"){
      $(".orderTimeframeDate").show();
    }
    else{
      $(".orderTimeframeDate").hide();
    }
  }

  function calcTotalPrice() {
    var unitPrice = $("#unit_price").val();
    var quantity = $("#quantity").val();
    totalPrice = unitPrice*quantity;
    $("#request_price").val(totalPrice);
  }

  $("#unit_price").blur(function(){
    calcTotalPrice();
  })

    $("#quantity").blur(function(){
    calcTotalPrice();
  })


  </script>
</body>
