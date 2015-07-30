function logout() {
  $.ajax({
    url: "../../Login/logout.php",
    type: "POST"
  }).done(function() {
    // redirect the user to the login page
    // this is done so you loose access to the site you are at
    // when you log out.
    window.location = "../../Login/login.php";
  });
}

function supplierSuggestions() {
  $('#output').html();
  var supplier_name = $('#supplier_name').val();
  $.ajax({
    url: '../SearchPHP/supplier_search_suggestions.php',
    type: 'POST',
    data: {supplier_name : supplier_name},
    success: function(data, status, xhr) {
      $("#output").html(data);
    }
  });
}
function purchaseSuggestions() {
  $('#output').html();
  var order_name = $('#order_name').val();
  $.ajax({
    url: '../SearchPHP/purchase_search_suggestions.php',
    type: 'POST',
    data: {order_name : order_name},
    success: function(data, status, xhr) {
      $("#output").html(data);
    }
  });
}

function orderRequest(){
  var request_supplier = $('#request_supplier').val();
  var request_quantity = $('#request_quantity').val();
  var approved_by_employee = $('#approved_by_employee').val();
  var request_description = $('#request_description').val();
  var employee_ID = $('#employee_ID').val();
  console.log(employee_ID);
  $.ajax({
    url: '../InsertPHP/addNewRequest.php',
    type: 'POST',
    data: {
      request_supplier : request_supplier,
      request_quantity : request_quantity,
      approved_by_employee : approved_by_employee,
      request_description : request_description,
      employee_ID : employee_ID
    },
    success: function(data, status, xhr){
      //window.location.reload();
    }
  });
}
