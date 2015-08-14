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
function setSessionIDSearch(order_ID){
    $.ajax({
        url : "../UpdatePHP/setSessionID.php",
        type: "POST",
        data : {order_ID : order_ID},
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
  var first_date = $('#first_date').val();
  var last_date  = $('#last_date').val();
  var notReceived;
  if($('#notReceived').is(':checked')){
        notReceived = $('#notReceived').val();
    }
  $.ajax({
    url: '../SearchPHP/purchase_search_suggestions.php',
    type: 'POST',
    data: {order_name : order_name,
           first_date : first_date,
           last_date  : last_date,
           notReceived: notReceived},
    success: function(data, status, xhr) {
      $("#output").html(data);
    }
  });
}

function orderRequest(){
  var request_supplier     = $('#request_supplier').val();
  var approved_by_employee = $('#approved_by_employee').val();
  var request_description  = $('#request_description').val();
  var employee_ID          = $('#employee_ID').val();
  $.ajax({
    url: '../InsertPHP/addNewRequest.php',
    type: 'POST',
    data: {
      request_supplier     : request_supplier,
      approved_by_employee : approved_by_employee,
      request_description  : request_description,
      employee_ID          : employee_ID
    },
    success: function(data, status, xhr){
      window.location.reload();
    }
  });
}
function activeRequest(element){
  if(!element){
    $("#output").html("");
    return;
  }
  var request_ID    = $(element).parent().find('#request_ID').text();
  var employee_name = $(element).parent().find('#employee_name').text();

  $.ajax({
    url: '../SearchPHP/showRequest.php',
    type: 'POST',
    data: {
      request_ID    : request_ID,
      employee_name : employee_name
    },
    success: function(data, status, xhr){
      $("#output").html(data);
    }
  });
}

function delRequest(request_ID){
  var r = confirm("Are you sure you want to delete this request? \nThis has not yet been ordered");
  if(r === true){
    $.ajax({
      url: '../DeletePHP/deleteRequest.php',
      type: 'POST',
      data:{
        request_ID : request_ID
      },
      success: function(data, status, xhr){
        window.location.reload();
      }
    });
  }
}
function delOrderItem(order_item_ID){
  var r = confirm("Are you sure you want to delete this line item?");
  if(r === true){
    $.ajax({
      url: '../DeletePHP/deleteOrderItem.php',
      type: 'POST',
      data:{
        order_item_ID : order_item_ID
      },
      success: function(data, status, xhr){
        window.location.reload();
      }
    });
  }
}

function createPurchaseOrder(){

  // function to find the correct value from the datalist
  var employee_name = $("input[name='employeeList']").on('input', function(e){
    var $input = $(this),
        val = $input.val(),
        list = $input.attr('list'),
        match = $('#'+list + ' option').filter(function() {
           return ($(this).val() === val);
       });
  });

  // function to find the correct value from the datalist
  var supplier_name = $("input[name='supplierList']").on('input', function(e){
    var $input = $(this),
        val = $input.val(),
        list = $input.attr('list'),
        match = $('#'+list + ' option').filter(function() {
           return ($(this).val() === val);
       });
  });
  employee_name   = employee_name.val();
  supplier_name   = supplier_name.val();
  var employee_ID = $('#employee_ID').val();
  var approved_by = $('#approved_by').val();
  var request_ID  = $('#activeRequest').text();
  $.ajax({
    url: '../InsertPHP/addNewPurchaseOrder.php',
    type: 'POST',
    data:{
      employee_name : employee_name,
      employee_ID   : employee_ID,
      supplier_name : supplier_name,
      request_ID    : request_ID,
      approved_by   : approved_by
    },
    success: function(data, status, xhr){
      //console.log(data);
      window.location = "../views/addOrderItem.php";
    }
  });
}
function addOrderItem(){
  var quantity    = $('#quantity').val();
  var part_number = $('#part_number').val();
  var unit_price  = $('#unit_price').val();
  var description = $('#description').val();
  $.ajax({
    url: '../InsertPHP/addNewOrderItem.php',
    type: 'POST',
    data:{
      quantity    : quantity,
      part_number : part_number,
      unit_price  : unit_price,
      description : description
    },
    success: function(data, status, xhr){
      window.location.reload();
    }
  });
}
function showPOInfo(order_ID) {
  $.ajax({
    url: "../SelectPHP/POInfoForOrderItem.php",
    type: "POST",
    data: {
      order_ID: order_ID
    },
    success: function(data, status, xhr) {
      $("#poinfo").html(data);
    }
  });
}
function showPOInfoAndRefreshImage(order_ID) {
  $.ajax({
    url: "../SelectPHP/POInfoForOrderItem.php",
    type: "POST",
    data: {
      order_ID: order_ID
    },
    success: function(data, status, xhr) {
      $("#poinfo").html(data);
      window.location.reload(true);
    }
  });
}
function showOrderItems(order_ID){
  $.ajax({
    url: "../SelectPHP/showOrderItems.php",
    type: "POST",
    data: {
      order_ID: order_ID
    },
    success: function(data, status, xhr) {
      $("#orderItems").html(data);
    }
  });
}
function addNewSupplier(){
  var supplier_name    = $('#supplier_name').val();
  var supplier_address = $('#supplier_address').val();
  var supplier_phone   = $('#supplier_phone').val();
  var supplier_fax     = $('#supplier_fax').val();
  var supplier_email   = $('#supplier_email').val();
  var supplier_contact = $('#supplier_contact').val();
  var supplier_website = $('#supplier_website').val();
  var supplier_login   = $('#supplier_login').val();
  var supplier_password = $('#supplier_password').val();
  var supplier_accountNr = $('#supplier_accountNr').val();
  var supplier_notes = $('#supplier_notes').val();
  $.ajax({
    url: "../InsertPHP/addNewSupplier.php",
    type: "POST",
    data: {
      supplier_name    : supplier_name,
      supplier_address : supplier_address,
      supplier_phone   : supplier_phone,
      supplier_fax     : supplier_fax,
      supplier_email   : supplier_email,
      supplier_contact : supplier_contact,
      supplier_login   : supplier_login,
      supplier_password : supplier_password,
      supplier_accountNr : supplier_accountNr,
      supplier_website : supplier_website,
      supplier_notes : supplier_notes
    },
    success: function(data, status, xhr) {
      window.location = '../Views/supplierList.php';
    }
  });
}

// This function preserves the session order_ID
function POInfo(order_ID){
  $.ajax({
    url: '../SelectPHP/POInfo.php',
    type: "POST",
    data:{
      order_ID: order_ID
    },
    success: function(data, status, xhr) {
      window.location = "../Views/addOrderItem.php";
    }
  });
}
// This function preserves the session order_ID
function printoutInfo(order_ID){
  $.ajax({
    url: '../SelectPHP/POInfo.php',
    type: "POST",
    data:{
      order_ID: order_ID
    },
    success: function(data, status, xhr) {
      window.location = "../Printouts/purchaseOrder.php";
    }
  });
}
// Function for editing the supplier
function editSupplier(element){
  var r = confirm("Are you sure you want to edit this supplier?");
  if (r === true){
    var supplier_name = $(element).parent().prev().find("#supplier_name").val();
    var supplier_phone = $(element).parent().prev().find("#supplier_phone").val();
    var supplier_fax = $(element).parent().prev().find("#supplier_fax").val();
    var supplier_email = $(element).parent().prev().find("#supplier_email").val();
    var supplier_address = $(element).parent().prev().find("#supplier_address").val();
    var supplier_contact = $(element).parent().prev().find("#supplier_contact").val();
    var supplier_accountNr = $(element).parent().prev().find("#supplier_accountNr").val();
    var supplier_website = $(element).parent().prev().find("#supplier_website").text();
    var supplier_login = $(element).parent().prev().find("#supplier_login").val();
    var supplier_password = $(element).parent().prev().find("#supplier_password").val();
    var supplier_notes = $(element).parent().prev().find("#supplier_notes").val();

    $.ajax({
      url: '../UpdatePHP/editSupplier.php',
      type: "POST",
      data:{
        supplier_phone: supplier_phone,
        supplier_name: supplier_name,
        supplier_fax: supplier_fax,
        supplier_email: supplier_email,
        supplier_address: supplier_address,
        supplier_contact: supplier_contact,
        supplier_accountNr: supplier_accountNr,
        supplier_website: supplier_website,
        supplier_login: supplier_login,
        supplier_password: supplier_password,
        supplier_notes: supplier_notes
      },
      success: function(data, status, xhr) {
        window.location.reload();
      }
    });
  }
}

function packageReceived(order_ID){
  $.ajax({
    url: '../UpdatePHP/packageReceived.php',
    type: "POST",
    data:{
      order_ID: order_ID
    },
    success: function(data, status, xhr) {
      window.location.reload();
    }
  });
}
function setFinalInspectionNote(order_ID){
  var e                 = document.getElementById("rating_timeliness");
  var rating_timeliness = e.options[e.selectedIndex].value;
  e                     = document.getElementById("rating_quality");
  var rating_quality    = e.options[e.selectedIndex].value;
  e                     = document.getElementById("rating_price");
  var rating_price      = e.options[e.selectedIndex].value;
  $('textarea').select(); //select text inside
  var order_final_inspection = window.getSelection().toString();
  $.ajax({
    url: '../UpdatePHP/setFinalInspectionNote.php',
    type: "POST",
    data:{
      order_ID               : order_ID,
      order_final_inspection : order_final_inspection,
      rating_timeliness      : rating_timeliness,
      rating_price           : rating_price,
      rating_quality         : rating_quality
    },
    success: function(data, status, xhr) {
      window.location.reload();
    }
  });
}

// This function makes the request inactive
function finishRequest(request_ID){
  var r = confirm("Are you sure you are finished with this request?");
  if(r === true){
    $.ajax({
      url: '../UpdatePHP/finishRequest.php',
      type: 'POST',
      data:{
        request_ID : request_ID
      },
      success: function(data, status, xhr){
        window.location.reload();
      }
    });
  }
}
