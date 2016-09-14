<?
include '../../connection.php';
$request_ID = mysqli_real_escape_string($link, $_POST['request_ID']);

// Find all active requests that have the same supplier and are not the current request
$requestSql = "SELECT request_ID, request_supplier, request_date, employee_ID, request_description, part_number, quantity, unit_price, department, cost_code, request_price, unit_description, timeframe
                          FROM order_request
                          WHERE active = 1
                          AND request_ID = '$request_ID';";
$requestResult = mysqli_query($link, $requestSql);

if(!$requestResult){
  die(mysqli_error($link));
}
$requestRow = mysqli_fetch_array($requestResult);
$timeframe = $requestRow[12];

// Find all departments
$departmentSql = "SELECT department_name
                  FROM department;";
$departmentResult = mysqli_query($link, $departmentSql);

// Find the employee's name who's request this is.
$employeeSql = "SELECT employee_name FROM employee
              WHERE employee_ID = '$requestRow[3]';";
$employeeResult = mysqli_query($link, $employeeSql);
$employeeRow = mysqli_fetch_array($employeeResult);

$suppliersSql = "SELECT supplier_name, credit_card
                FROM supplier
                ORDER BY supplier_name;";
$suppliersResult = mysqli_query($link, $suppliersSql);

 echo"
      <div class='modal-dialog'>
        <div class='modal-content col-md-12'>
          <div class='modal-header'>
            <button type='button' id='close_modal' class='btn close glyphicon glyphicon-remove' data-dismiss='modal'></button>
            <h4>Edit Request ".$requestRow[0]."</h4>
            <h5>By ".$employeeRow[0]." on ".$requestRow[2]." for ".$requestRow[1]."</h5>
          </div>
          <div class='modal-body col-md-12'>";
          echo"
            <form class='form-horizontal'>
              <div class='form-group'>
                <div class='col-md-3'>
                  <label>Supplier: </label>
                </div>
                <div class='col-md-6'>
              <input type='text' list='suppliers' name='supplierList' id='supplierList' class='col-md-12 form-control' value='".$requestRow[1]."'>
          <datalist id='suppliers'>";
            while($row = mysqli_fetch_array($suppliersResult)){
                 echo"<option value='".$row[0]."'>".$row[0]."</option>";
            }
          echo"
          </datalist>
                  </div>
                </div>
                <div class='form-group'>
                <div class='col-md-3'>
                  <label class='control-label'>Order by:</label>
                </div>
                <div class='col-md-6'>
                  <select id='orderTimeframe' class='form-control' onchange='displayDate()'>
                    <option value='With next order'>With next order</option>
                    <option value='Specific date'>Specific date</option>
                    <option value='Other'>Other</option>
                   </select>
                </div>
              </div>
              <div class='form-group orderTimeframeDate'>
                <div class='col-md-3'>
                  <label class='control-label orderTimeframeDate'>Order by date:</label>
                </div>
                <div class='col-md-6'>
                   <input type='date' id='orderTimeframeDate' class='form-control orderTimeframeDate'>
                </div>
              </div>
              <div class='form-group'>
                <div class='col-md-3'>
                  <label>Department: </label>
                </div>
                <div class='col-md-6'>
                	<input type='hidden' id='request_modal' value='yes'>
                	<input type='hidden' id='cost_code_selected' value='".$requestRow[9]."'>
                  <select id='req_department' class='form-control' onchange='updateCostCode()'>
                    <option value=' '>All departments</option>";
                    $departmentResultModal = mysqli_query($link, $departmentSql);
                  while($departmentRowModal = mysqli_fetch_array($departmentResultModal)){
                  	if ( $requestRow[8] === $departmentRowModal[0]){
                  		echo "<option selected value='".$departmentRowModal[0]."'>".$departmentRowModal[0]."</option>";
                  	}
                  	else{
                    	echo "<option value='".$departmentRowModal[0]."'>".$departmentRowModal[0]."</option>";
                    }
                  }
                echo"
                  </select>
                </div>
              </div>
              <div id='req_cost_code_div' class='form-group'>
                <div class='col-md-3'>
                  <label>Cost code: </label>
                </div>
                <div class='col-md-6 result'>
                </div>
              </div>
              <div class='form-group'>
                <div class='col-md-3'>
                  <label>Request Comment:</label>
                </div>
                <div class='col-md-6'>
                  <textarea id='req_description' class='form-control'>".$requestRow[4]."</textarea>
                </div>
              </div>
              <div class='form-group'>
                <div class='col-md-3'>
                  <label class='control-label'>Part number:</label>
                </div>
                <div class='col-md-6'>
                  <input type='text' id='req_part_number' class='form-control' value='".$requestRow[5]."'>
                </div>
              </div>
              <div class='form-group'>
                <div class='col-md-3'>
                  <label class='control-label'>Part description:</label>
                </div>
                <div class='col-md-6'>
                  <input type='text' id='req_part_description' class='form-control' value='".$requestRow[11]."'>
                </div>
              </div>
              <div class='form-group'>
                <div class='col-md-3'>
                  <label class='control-label'>Quantity:</label>
                </div>
                <div class='col-md-6'>
                  <input type='number' id='req_quantity' class='form-control' value='".$requestRow[6]."'>
                </div>
              </div>
              <div class='form-group'>
                <div class='col-md-3'>
                  <label class='control-label'>Unit price:</label>
                </div>
                <div class='col-md-6'>
                  <input type='number' id='req_unit_price' class='form-control' value='".$requestRow[7]."' onclick='calcTotalPrice()'>
                </div>
              </div>
              <div class='form-group'>
                <div class='col-md-3'>
                  <label class='control-label'>Total price:  </label>
                </div>
                <div class='col-md-6'>
                  <input type='number' id='req_price' class='form-control' value='".$requestRow[10]."'>
                </div>
              </div>
             </form>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-success' onclick='editRequest(".$request_ID.")'>Save</button>
          </div>
      </div>
    </div>";

?>
<script>

	$(document).ready(function() {
		costCode = $("#cost_code_selected").val();
		updateCostCode(costCode);

    // Display the order by and date in request edit modal. 
    $(".orderTimeframeDate").hide();
    $('input[type=date]').each(function() {
        if  (this.type != 'date' ) $(this).datepicker({
          dateFormat: 'yy-mm-dd'
        });
    });
    // Choose the "order by" from the drop down in edit request modal. 
    timeframe = String(<?php echo json_encode($timeframe); ?>);
    $("select option[value='"+timeframe+"']").attr("selected","selected");

    // If the string includes a number
    function hasNumber(myString) {
      return (
        /\d/.test(
        myString));
    }
    // For checking if the timeframe is a date.
    if(hasNumber(timeframe)){
      // Need to use .prop for Safari
      $("select option[value='Specific date']").prop('selected', true);
      $(".orderTimeframeDate").show();
      $("#orderTimeframeDate").val(timeframe);
    }

	})

  function displayDate() {
    if($("#orderTimeframe").val() == "Specific date"){
      $(".orderTimeframeDate").show();
    }
    else{
      $(".orderTimeframeDate").hide();
    }
  }

	document.getElementById('close_modal').onclick = function(){
    	modal.style.display = 'none';
  	}

  function calcTotalPrice() {
    var unitPrice = $("#req_unit_price").val();
    var quantity = $("#req_quantity").val();
    totalPrice = unitPrice*quantity;
    $("#req_price").val(totalPrice);
  }

  $("#req_unit_price").blur(function(){
    calcTotalPrice();
  })

    $("#req_quantity").blur(function(){
    calcTotalPrice();
  })


</script>