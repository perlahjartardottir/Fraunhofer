<?php
include '../../connection.php';
session_start();
$order_ID = $_SESSION['order_ID'];
$currency = $_SESSION["currency"];

// get the correct currency display
if($currency == 'EUR'){
  $currencySymbol = '&euro;';
} else if($currency == 'GBP'){
  $currencySymbol = '&pound;';
} else{
  $currencySymbol = '$';
}

$sql = "SELECT quantity, part_number, unit_price, description, order_item_ID, department_ID, order_ID
        FROM order_item
        WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);
if(mysqli_num_rows($result) == 0){
  die();
}

$departmentSql2 = "SELECT department_name
                  FROM department;";
$departmentResult2 = mysqli_query($link, $departmentSql2);

?>
<div class='row well well-lg'>
  <table class='table table-responsive' style='width:92%;'>
    <thead>
      <tr>
        <th>Pos. #</th>
        <th>Quantity</th>
        <th>Part #</th>
        <th>Description</th>
        <th>Department</th>
        <th><?php echo $currency; ?> Unit</th>
        <th><?php echo $currency; ?> Total</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $counter = 1;
      $totalOrderPrice = 0;
      while($row = mysqli_fetch_array($result)){
        $total = $row[0] * $row[2];
        $departmentSql = "SELECT department_name
                          FROM department
                          WHERE department_ID = '$row[5]';";
        $departmentResult = mysqli_query($link, $departmentSql);
        $departmentRow = mysqli_fetch_array($departmentResult);
        echo"<tr>
              <td><a href='#' data-toggle='modal' data-target='#".$row[4]."'>".$counter."</a></td>
              <td>".$row[0]."</td>
              <td>".$row[1]."</td>
              <td>".$row[3]."</td>
              <td>".$departmentRow[0]."</td>
              <td>".$currencySymbol."".number_format((float)$row[2], 2, '.', '')."</td>
              <td>".$currencySymbol."".number_format((float)$total, 2, '.', '')."<button style='float:right; margin-right:-50px' onclick='delOrderItem(".$row[4].")' class='btn btn-danger'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></button></td>
            </tr>
            <div class='modal fade' id='".$row[4]."' tabindex='-1' role='dialog' aria-labelledby='".$row[4]."' aria-hidden='true'>
              <div class='modal-dialog'>
                <div class='modal-content col-md-12'>
                  <div class='modal-header'>
                    <h4>Order item: ".$row[4]."</h4>
                  </div>
                  <div class='modal-body'>
                    <div class='col-md-12'>
                    <form>
                      <div class='col-md-6'>
                        <label>Quantity</label>
                        <input type='number' id='quantity' value='".$row[0]."' class='form-control'>
                      </div>
                      <div class='col-md-6'>
                        <label>Part #</label>
                        <input type='text' id='part_number' value='".$row[1]."' class='form-control'>
                      </div>
                      <div class='col-md-6'>
                        <label>Department</label>
                        <select id='department' class='form-control' onchange='updateModalCostCode(this)'>
                          <option value=''>All departments</option>";
                          while($departmentRow2 = mysqli_fetch_array($departmentResult2)){
                            echo "<option value='".$departmentRow2[0]."'"; if($departmentRow[0] == $departmentRow2[0]){echo" selected";} echo">".$departmentRow2[0]."</option>";
                          }
                          echo"
                        </select>
                      </div>
                      <div class='form-group col-md-6 result'>
                      </div>
                      <div class='col-md-6'>
                        <label>USD Unit</label>
                        <input type='text' id='unit_price' value='".$row[2]."' class='form-control'>
                      </div>
                      <div class='col-md-12'>
                        <label>Description</label>
                        <textarea id='description' class='form-control'>".$row[3]."</textarea>
                      </div>
                      <p>Purchase order ID: ".$row[6]."</p>
                    </form>
                    </div>
                  </div>
                  <div class='modal-footer'>
                    <button type='button' class='btn btn-success' data-dismiss='modal' onclick='editOrderItem(".$row[4].", this)'>Edit</button>
                    <button type='button' class='btn btn-primary' data-dismiss='modal'>Close</button>
                  </div>
                </div>
              </div>
            </div>";
        $counter = $counter + 1;
        $totalOrderPrice = $totalOrderPrice + $total;
      }
      echo"<tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <th>Total Order Price: </th>
            <th><u style='border-bottom: 1px solid black'>".$currencySymbol."".number_format((float)$totalOrderPrice, 2, '.', '')."</u></th>
          </tr>"; ?>
    </tbody>
  </table>
  <a href='../Printouts/purchaseOrder.php' class='btn btn-primary col-md-2' style='float:right;'>Printout</a>
  <form>
    <select onchange='setCurrency()' class='form-control' id='currency' style='width:auto; float:right; margin-right:5px;'>
      <option value='USD'<?php if($currency == 'USD'){echo "selected";}?>>$ USD</option>
      <option value='EUR'<?php if($currency == 'EUR'){echo "selected";}?>>&euro; EUR</option>
      <option value='GBP'<?php if($currency == 'GBP'){echo "selected";}?>>&pound; GBP</option>
    </select>
  </form>

</div>
