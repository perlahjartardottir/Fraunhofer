<?
include '../../connection.php';
$request_ID = mysqli_real_escape_string($link, $_POST['request_ID']);
$supplier_ID = mysqli_real_escape_string($link, $_POST['supplier_ID']);

// Get supplier name from its ID
$getSupplierNameSql = "SELECT supplier_name
                       FROM supplier
                       WHERE supplier_ID = '$supplier_ID';";
$getSupplierNameResult = mysqli_query($link, $getSupplierNameSql);
$supplierRow = mysqli_fetch_array($getSupplierNameResult);
$supplier_name = $supplierRow[0];

// Find all active requests that have the same supplier and are not the current request
$requestSql = "SELECT request_ID, request_supplier, request_date, employee_ID, request_description, part_number, quantity, unit_price, department, cost_code, unit_description
                          FROM order_request
                          WHERE active = 1
                          AND request_ID = '$request_ID';";
$requestResult = mysqli_query($link, $requestSql);
if(!$requestResult){
  die(mysqli_error($link));
}
$requestRow = mysqli_fetch_array($requestResult);

// Find all departments
$departmentSql = "SELECT department_name
                  FROM department;";
$departmentResult = mysqli_query($link, $departmentSql);

// Find the employee's name who's request this is.
$employeeSql = "SELECT employee_name FROM employee
              WHERE employee_ID = '$requestRow[3]';";
$employeeResult = mysqli_query($link, $employeeSql);
$employeeRow = mysqli_fetch_array($employeeResult);

 echo"
      <div class='modal-dialog'>
        <div class='modal-content col-md-12'>
          <div class='modal-header'>
            <h4>Request ID: ".$requestRow[0]."</h4>
            <h5>By ".$employeeRow[0]." on ".$requestRow[2]." for ".$requestRow[1]."</h5>
            <h5>".$requestRow[4]."</h5>
          </div>
          <div class='modal-body col-md-12'>";
          echo"
            <form class='form-horizontal'>
              <div class='form-group'>
                <div class='col-md-3'>
                  <label>Department: </label>
                </div>
                <div class='col-md-6'>
                	<input type='hidden' id='request_modal' value='yes'>
                	<input type='hidden' id='cost_code_selected' value='".$requestRow[9]."'>
                  <select id='req_department' class='form-control' onchange='updateCostCode()'>
                    <option value=''>All departments</option>";
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
                  <input type='text' id='req_description' class='form-control' value='".$requestRow[10]."'>
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
                  <input type='number' id='req_unit_price' class='form-control' value='".$requestRow[7]."'>
                </div>
              </div>
             </form>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-primary' onclick='addOrderItemFromRequest(".$requestRow[0].", this.form)'>Use</button>
            <button type='button' id='close_modal' class='btn'>Close</button>
          </div>

      </div>
    </div>";

?>
<script>

	$(document).ready(function() {
		costCode = $("#cost_code_selected").val();
		updateCostCode(costCode);
	})

	document.getElementById('close_modal').onclick = function(){
    	modal.style.display = 'none';
  	}


</script>