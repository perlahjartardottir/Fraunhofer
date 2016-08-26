<?php
include '../../connection.php';
session_start();
$order_ID = $_SESSION['order_ID'];
$currency = $_SESSION["currency"];

// For debugging reasons, can be deleted
function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
  }

// get the correct currency display
if($currency == 'EUR'){
  $currencySymbol = '&euro;';
} else if($currency == 'GBP'){
  $currencySymbol = '&pound;';
} else{
  $currencySymbol = '$';
}

$sql = "SELECT quantity, part_number, unit_price, description, order_item_ID, department_ID, order_ID, cost_code_ID
        FROM order_item
        WHERE order_ID = '$order_ID';";
$result = mysqli_query($link, $sql);
if(mysqli_num_rows($result) == 0){
  die();
}

$departmentSql2 = "SELECT department_name
                   FROM department;";

?>
<div class='row well well-lg'>
  <table class='table table-responsive' style='width:92%;'>
    <thead>
      <tr>
        <th>Pos. #</th>
        <th>Quantity</th>
        <th>Cost code</th>
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
        // multiply quantity with unit price to find the total price
        $total = $row[0] * $row[2];

        // Find the department name from the department ID
        $departmentSql = "SELECT department_name
                          FROM department
                          WHERE department_ID = '$row[5]';";
        $departmentResult = mysqli_query($link, $departmentSql);
        $departmentRow = mysqli_fetch_array($departmentResult);

        // Find the cost code name from the cost code ID
        $costCodeSql = "SELECT cost_code_name
                        FROM cost_code
                        WHERE cost_code_ID = '$row[7]';";
        $costCodeResult = mysqli_query($link, $costCodeSql);
        $costCodeRow = mysqli_fetch_array($costCodeResult);
        if($costCodeRow[0] == 'CVD'){
           $costCode = 'C-000';
        } else if($costCodeRow[0] == 'PVD'){
          $costCode = 'P-000';
        } else if($costCodeRow[0] == 'INF'){
          $costCode = 'I-000';
        } else if($costCodeRow[0] == 'ANA'){
          $costCode = 'A-000';
        } else if($costCodeRow[0] == 'OH'){
          $costCode = 'O-000';
        } else{
          $costCode = $costCodeRow[0];
        }

        echo"<tr>
              <td><a href='#' data-toggle='modal' onclick='updateCostCode(".json_encode($costCode).",".json_encode($departmentRow[0]).")' data-target='#".$row[4]."'>".$counter."</a></td>
              <td>".$row[0]."</td>
              <td>".$costCode."</td>
              <td>".$row[1]."</td>
              <td>".$row[3]."</td>
              <td id='departmentInTable'>".$departmentRow[0]."</td>
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
                        <label>Department</label>";
                        $noCostCode = "noCostCode";
                        echo"
                        <select id='department_edit' class='form-control' onchange='updateCostCode()'>
                          <option value=''>All departments</option>";
                          $departmentResult2 = mysqli_query($link, $departmentSql2);
                          while($departmentRow2 = mysqli_fetch_array($departmentResult2)){
                            if($departmentRow[0] == $departmentRow2[0]){
                              echo "<option value='".$departmentRow2[0]."' selected>".$departmentRow2[0]."</option>";
                            }
                            else{
                              echo "<option value='".$departmentRow2[0]."'>".$departmentRow2[0]."</option>";
                            }
                            // echo "<option value='".$departmentRow2[0]."'"; if($departmentRow[0] == $departmentRow2[0]){echo" selected";} echo">".$departmentRow2[0]."</option>";
                          //   echo "<option value='".$departmentRow2[0]."'>".$departmentRow2[0]."</option>";
                          //   if($departmentRow[0] == $departmentRow2[0]){
                          //     echo'
                          //   <script>
                          //   dep = String('.json_encode($departmentRow[0]).');
                          //   $("select option[value=\'"+dep+"\']").attr("selected","selected");
                          // </script>';
                          //   }
                          }

                          echo"
                        </select>
                      </div>
                      <div class='col-md-6'>
                        <label>Cost code:</label>
                        <div class='result'>
                        </div>
                      </div>
                      <div class='col-md-12'>
                        <label>USD Unit</label>
                        <input type='text' id='unit_price' value='".$row[2]."' class='form-control' style='width:auto;'>
                      </div>
                      <div class='col-md-12'>
                        <label>Description</label>
                        <textarea id='description' class='form-control'>".$row[3]."</textarea>
                      </div>
                      <p>Purchase order ID: ".$row[6]."</p>
                    </form>
                  </div>
                  <div class='modal-footer'>
                    <button type='button' class='btn btn-success' onclick='editOrderItem(".$row[4].", this)'>Edit</button>
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

