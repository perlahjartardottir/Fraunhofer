// Safari support for datalists
$(document).ready(function () {
  var nativedatalist = !!('list' in document.createElement('input')) &&
    !!(document.createElement('datalist') && window.HTMLDataListElement);

  if (!nativedatalist) {
    $('input[list]').each(function () {
      var availableTags = $('#' + $(this).attr("list")).find('option').map(function () {
        return this.value;
      }).get();
      $(this).autocomplete({ source: availableTags });
    });
  }
});

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
function addFeedback() {
  var comment = $('#comment').val();
  $.ajax({
    url: "../InsertPHP/addFeedback.php",
    type: "POST",
    data: {
      comment: comment
    },
    success: function(data, status, xhr) {
      window.location.reload(true);
    }
  });
}
function esignatureCheck(){
  var esignature;
  if($('#esignature').is(':checked')){
    esignature = $('#esignature').val();
  }
  $.ajax({
    url: '../UpdatePHP/esignature.php',
    type: 'POST',
    data: {
      esignature : esignature
    },
    success: function(data, status, xhr) {
      $('#output').html(data);
    }
  });
}
function payByCredit(){
  var credit;
  if($('#credit').is(':checked')){
    credit = $('#credit').val();
  }
  if(credit === 'on'){
    $.ajax({
        url : "../UpdatePHP/editNetTerms.php",
        type: "POST",
        data : {
          net_terms : 0
        }
    });
  }
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
  var supplier_contact = $('#supplier_contact').val();
  var supplier_phone = $('#supplier_phone').val();
  var supplier_email = $('#supplier_email').val();
  var supplier_address = $('#supplier_address').val();
  $.ajax({
    url: '../SearchPHP/supplier_search_suggestions.php',
    type: 'POST',
    data: {supplier_name : supplier_name,
           supplier_contact: supplier_contact,
           supplier_phone: supplier_phone,
           supplier_email: supplier_email,
           supplier_address: supplier_address
    },
    success: function(data, status, xhr) {
      $("#output").html(data);
    }
  });
}

function overview(){
  var department = $('#department').val();
  var cost_code = $('#cost_code').val();
  var timeInterval = $('#group_by_select').val();
  var date_from = $('#date_from').val();
  var date_to = $('#date_to').val();
  $.ajax({
    url: '../SearchPHP/overview.php',
    type: 'POST',
    data: {department   : department,
           cost_code    : cost_code,
           timeInterval : timeInterval,
           date_from    : date_from,
           date_to      : date_to},
    success: function(data, status, xhr) {
      $("#output").html(data);
    }
  });
}

function forecast(){
  var supplier_name = $('#supplier_name').val();
  var order_name = $('#order_name').val();
  var date_from = $('#date_from').val();
  var date_to = $('#date_to').val();
  $.ajax({
    url: '../SearchPHP/forecast.php',
    type: 'POST',
    data: {supplier_name: supplier_name,
           order_name   : order_name,
           date_from    : date_from,
           date_to      : date_to},
    success: function(data, status, xhr) {
      $("#output").html(data);
    }
  });
}

function purchaseSuggestions() {
  $('#output').html();
  var order_name = $('#order_name').val();
  var supplier_name = $('#supplier_name').val();
  var first_date = $('#first_date').val();
  var last_date  = $('#last_date').val();
  console.log(first_date);
  console.log(last_date);
  var notReceived;
  if($('#notReceived').is(':checked')){
    notReceived = $('#notReceived').val();
  }
  var noFinalInspection;
  if($('#noFinalInspection').is(':checked')){
    noFinalInspection = $('#noFinalInspection').val();
  }

  $.ajax({
    url: '../SearchPHP/purchase_search_suggestions.php',
    type: 'POST',
    data: {order_name : order_name,
           supplier_name : supplier_name,
           first_date : first_date,
           last_date  : last_date,
           noFinalInspection  : noFinalInspection,
           notReceived: notReceived},
    success: function(data, status, xhr) {
      $("#output").html(data);
    }
  });
}
function orderItemSuggestions() {
  $('#output').html();
  var part_number = $('#part_number').val();
  var description = $('#description').val();
  var department = $('#department').val();
  var first_date = $('#first_date').val();
  var last_date  = $('#last_date').val();

  var noFinalInspection;
  if($('#noFinalInspection').is(':checked')){
    noFinalInspection = $('#noFinalInspection').val();
  }

  $.ajax({
    url: '../SearchPHP/order_item_suggestions.php',
    type: 'POST',
    data: {part_number : part_number,
           description : description,
           department : department,
           first_date : first_date,
           last_date  : last_date,
           noFinalInspection  : noFinalInspection},
    success: function(data, status, xhr) {
      $("#output").html(data);
    }
  });
}

function quoteSuggestions() {
  $('#output').html();
  var order_name = $('#order_name').val();
  var quote_number = $('#quote_number').val();
  var description = $('#quote_description').val();
  var supplier_name = $('#supplier_name').val();
  var first_date = $('#first_date').val();
  var last_date  = $('#last_date').val();

  $.ajax({
    url: '../SearchPHP/quote_suggestions.php',
    type: 'POST',
    data: {order_name : order_name,
           quote_number : quote_number,
           description : description,
           supplier_name : supplier_name,
           first_date : first_date,
           last_date  : last_date},
    success: function(data, status, xhr) {
      $("#output").html(data);
    }
  });
}

function orderRequest(redirect, form){
  var supplier_name = $("input[name='supplierList']").on('input', function(e){
    var $input = $(this),
        val = $input.val(),
        list = $input.attr('list'),
        match = $('#'+list + ' option').filter(function() {
           return ($(this).val() === val);
       });
  });
  var request_supplier     = supplier_name.val();
  var department           = $('#department').val();
  var cost_code             = $('#cost_code').val();
  var orderTimeframe       = $('#orderTimeframe').val();
  var orderTimeframeDate    = $('#orderTimeframeDate').val();
  var request_description  = $('#request_description').val();
  var employee_ID          = $('#employee_ID').val();
  var part_number          = $('#part_number').val();
  var quantity             = $('#quantity').val();
  var unitPrice             = $('#unit_price').val();
  var request_price        = $('#request_price').val();
  var unit_price          = $('#unit_price').val();
  var errorMessage = "";
  
  if(orderTimeframe === "Specific date" && orderTimeframeDate === ""){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Date</div>";
  }
  if(quantity === ""){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Quantity</div>";
  }
  if(department === ""){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Department</div>";
  }
  if(cost_code === ""){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Cost code</div>";
  }

  if(checkIfRecommended(request_supplier) === 'Not Recommended'){
    r = confirm('This supplier is not recommended, are you sure you wish to proceed?');
    if (r !== true){
      return;
    }
  }

  if(errorMessage){
    $("#invalidRequest").html(errorMessage);
  }else{
    $.ajax({
      url: '../InsertPHP/addNewRequest.php',
      type: 'POST',
      data: {
        request_supplier     : request_supplier,
        department           : department,
        cost_code            : cost_code,
        orderTimeframe       : orderTimeframe,
        orderTimeframeDate   : orderTimeframeDate,
        request_description  : request_description,
        employee_ID          : employee_ID,
        part_number          : part_number,
        request_price        : request_price,
        unit_price           : unit_price,
        quantity             : quantity
      },
      success: function(data, status, xhr){
        if(redirect === "yes"){
          window.location = "purchasing.php";
        }
        else{
          infoMessage = "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Your request has been sent.</div>";
          // Too imitade a reload and to keep information from form. 
          $("#invalidRequest").html("");
          $("#requestSent").html("");
          $("#requestSent").html(infoMessage);
          // Reset form except a few inputs.
          $('#requestForm')[0].reset();
          $("input[name='supplierList']").val(request_supplier);
          $('#orderTimeframe').val(orderTimeframe);
          displayDate();
          if(orderTimeframeDate != ""){
            $('#orderTimeframeDate').val(orderTimeframeDate);
          }
          // To clear cost code drop down.
          updateCostCode();
          if(data){
            emailMessage = "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>It sounds like your last request was urgent, "+data+" has been notified.</div>";
             $("#emailSent").html(emailMessage);

          }
        }
        
      }
    });
  }
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

function requestApproval(){
  var employee_ID = $('#approvedBy').val();
  $.ajax({
    url: '../SelectPHP/requestApproval.php',
    type: 'POST',
    data:{
      employee_ID : employee_ID
    },
    success: function(data, status, xhr){
      window.location.reload();
      console.log(data);
    //   setTimeout(function() {
    //     showRunTools();
    //   }, 1000);
    }
  });
}

function declineApprovalRequest(order_ID, element){
  var approval_response = $(element).parent().prev().find('#approval_response').val();
  if(approval_response === ""){
    var r = confirm("Are you sure you want to decline this without any response message?");
    if(r === true){
      $.ajax({
        url: '../UpdatePHP/declineApprovalRequest.php',
        type: 'POST',
        data:{
          order_ID : order_ID,
          approval_response : approval_response
        },
        success: function(data, status, xhr){
          window.location.reload();
        }
      });
    }
  }else{
    $.ajax({
      url: '../UpdatePHP/declineApprovalRequest.php',
      type: 'POST',
      data:{
        order_ID : order_ID,
        approval_response : approval_response
      },
      success: function(data, status, xhr){
        window.location.reload();
      }
    });
  }
}

function approveApprovalRequest(order_ID, element){
  var approval_response = $(element).parent().prev().find('#approval_response').val();
  if(approval_response === ""){
    var r = confirm("Are you sure you want to approve this without any response message?");
    if(r === true){
      $.ajax({
        url: '../UpdatePHP/approveApprovalRequest.php',
        type: 'POST',
        data:{
          order_ID : order_ID,
          approval_response : approval_response
        },
        success: function(data, status, xhr){
          window.location.reload();
        }
      });
    }
  } else{
    $.ajax({
      url: '../UpdatePHP/approveApprovalRequest.php',
      type: 'POST',
      data:{
        order_ID : order_ID,
        approval_response : approval_response
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

function delPurchaseOrder(order_ID){
  var r = confirm("Are you sure you want to cancel this purchase order?");
  if(r === true){
    $.ajax({
      url: '../DeletePHP/deletePurchaseOrder.php',
      type: 'POST',
      data:{
        order_ID : order_ID
      },
      success: function(data, status, xhr){
        window.location.reload();
      }
    });
  }
}

function cancelPurchaseOrder(order_ID){
  var r = confirm("Are you sure you want to cancel this purchase order?");
  if(r === true){
    $.ajax({
      url: '../UpdatePHP/cancelPurchaseOrder.php',
      type: 'POST',
      data:{
        order_ID : order_ID
      },
      success: function(data, status, xhr){
        window.location.reload();
      }
    });
  }
}

function checkIfRecommended(supplier_name){
  var recommended;
  $.ajax({
    url: '../SelectPHP/checkIfRecommended.php',
    type: 'POST',
    async: false,
    data:{
      supplier_name : supplier_name
    },
    success: function(data, status, xhr){
      recommended = data;
    }
  })

  return recommended;
}
function createPurchaseOrder(){
  var recommended;
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
  var request_ID  = $('#activeRequest').text();

  // if(checkIfRecommended(supplier_name) === 'Not Recommended'){
  //   r = confirm('This supplier is not recommended, are you sure you wish to proceed?');
  //   if (r !== true){
  //     return;
  //   }
  // }

  if(!employee_name){
    $("#invalidPO").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Employee</div>");
  } else if (!supplier_name){
    $("#invalidPO").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Supplier</div>");
  } else{
    $.ajax({
      url: '../InsertPHP/addNewPurchaseOrder.php',
      type: 'POST',
      data:{
        employee_name : employee_name,
        employee_ID   : employee_ID,
        supplier_name : supplier_name,
        request_ID    : request_ID
      },
      success: function(data, status, xhr){
        if(data.indexOf('invalidEmployee') > -1){
          $("#invalidPO").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Please choose a valid employee</div>");
        } else if (data.indexOf('invalidSupplier') > -1){
          $("#invalidPO").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Please choose a valid supplier</div>");
        } else{
            window.location = "../views/addOrderItem.php";
        }
      }
    });
  }
}
function addOrderItem(){
  var quantity    = $('#quantity').val();
  var part_number = $('#part_number').val();
  var unit_price  = $('#unit_price').val();
  var description = $('#description').val();
  var department  = $('#department').val();
  var cost_code   = $('#cost_code').val();

  $.ajax({
    url: '../InsertPHP/addNewOrderItem.php',
    type: 'POST',
    data:{
      quantity    : quantity,
      part_number : part_number,
      unit_price  : unit_price,
      department  : department,
      cost_code   : cost_code,
      description : description
    },
    success: function(data, status, xhr){
      window.location.reload();
      //console.log(data);
    }
  });
}

function displayAddOrderItemFromRequestModal(request_ID, supplier_ID){
    $.ajax({
    url: '../SelectPHP/addOrderItemFromRequestModal.php',
    type: 'POST',
    data:{
      request_ID    : request_ID,
      supplier_ID : supplier_ID
    },
    success: function(data, status, xhr){
        $("#addRequestModal").html(data);
    }
  });
}

function editRequestModal(request_ID){
    $.ajax({
    url: '../SelectPHP/editRequestModal.php',
    type: 'POST',
    data:{
      request_ID    : request_ID,
    },
    success: function(data, status, xhr){
        $("#editRequestModal").html(data);
    }
  });
}

function editRequest(request_ID){
    var supplier_name = $("input[name='supplierList']").on('input', function(e){
    var $input = $(this),
        val = $input.val(),
        list = $input.attr('list'),
        match = $('#'+list + ' option').filter(function() {
           return ($(this).val() === val);
       });
  });
  var request_supplier     = supplier_name.val();
  var quantity    = $('#req_quantity').val();
  var part_number = $('#req_part_number').val();
  var unit_price  = $('#req_unit_price').val();
  var description = $('#req_description').val();
  var department  = $('#req_department').val();
  var cost_code  = $('#req_cost_code').val();
  var request_price = $("#req_price").val();

  $.ajax({
    url: '../UpdatePHP/editRequest.php',
    type: 'POST',
    data:{
      request_ID       : request_ID,
      request_supplier : request_supplier,
      quantity         : quantity,
      part_number      : part_number,
      unit_price       : unit_price,
      department       : department,
      cost_code        : cost_code,
      description      : description,
      request_price    : request_price 
    },
    success: function(data, status, xhr){
      window.location.reload();
      //console.log(data);
    }
  });
}

function addOrderItemFromRequest(request_ID, form){
  var quantity    = $('#req_quantity').val();
  var part_number = $('#req_part_number').val();
  var unit_price  = $('#req_unit_price').val();
  var description = $('#req_description').val();
  var department  = $('#req_department').val();
  var cost_code  = $('#req_cost_code').val();

  $.ajax({
    url: '../InsertPHP/addOrderItemFromRequest.php',
    type: 'POST',
    data:{
      request_ID  : request_ID,
      quantity    : quantity,
      part_number : part_number,
      unit_price  : unit_price,
      department  : department,
      cost_code   : cost_code,
      description : description
    },
    success: function(data, status, xhr){
      window.location.reload();
      //console.log(data);
    }
  });
}

function addNewRequest(request_ID){
  var r = confirm('Are you sure you are finished with your current request?');
  if (r === true){
    $.ajax({
      url: '../UpdatePHP/addRequestToPO.php',
      type: 'POST',
      data: {
        request_ID : request_ID
      },
      success: function(data, status, xhr){
        window.location.reload();
      }
    })
  }
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

// Function to edit the order number of a purchase order
function editOrderNumber(){
  var order_name = $('#order_name').val();
  $.ajax({
    url: "../UpdatePHP/editOrderNumber.php",
    type: "POST",
    data: {
      order_name: order_name
    },
    success: function(data, status, xhr) {
      window.location.reload();
    }
  });
}

// if this function returns false the file is not added
function checkSize(max_img_size) {
  var input = document.getElementById("fileToUpload");
  if (input.files && input.files.length == 1) {
    if (input.files[0].size > max_img_size) {
      alert("The file size must be less than " + (max_img_size / 1024) + "KB");
      return false;
    }
  } else {
    alert("No image chosen.");
    return false;
  }
  return true;
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

function checkIfSupplierExists(){
  console.log('hehe');
    var supplier_address = $('#supplier_address').val();
    var supplier_phone   = $('#supplier_phone').val();
    var supplier_email   = $('#supplier_email').val();
    $.ajax({
      url: "../InsertPHP/checkIfSupplierExists.php",
      type: "POST",
      data: {
        supplier_address : supplier_address,
        supplier_phone   : supplier_phone,
        supplier_email   : supplier_email
      },
      success: function(data, status, xhr) {
        if(data.indexOf('address') > -1){
          $("#invalidSupplier").html("<div class='alert alert-warning fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" + data + "</div>");
        }
        if(data.indexOf('phone') > -1){
          $("#invalidSupplier").html("<div class='alert alert-warning fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" + data + "</div>");
        }
        if(data.indexOf('email') > -1){
          $("#invalidSupplier").html("<div class='alert alert-warning fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" + data + "</div>");
        }
      }
    })
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
  var net_terms        = $('#net_terms').val();
  var supplier_notes = $('#supplier_notes').val();
  if (net_terms === ''){
    net_terms = 30;
  }
  var credit_card = $('#credit_card');
    if(credit_card.is(':checked')){
        credit_card = $('#credit_card').val();
      }else{
        credit_card = 0;
      }
  console.log(net_terms);
  if(!supplier_name){
    $("#invalidSupplier").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Supplier name</div>");
  } else{
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
        net_terms        : net_terms,
        supplier_website : supplier_website,
        supplier_notes   : supplier_notes,
        credit_card      : credit_card
      },
      success: function(data, status, xhr) {
        window.location = '../Views/supplierList.php';
      }
    });
  }
}

function updateCostCode(cost_code, departmentName){
  
  var department_name = "";

  // For modal window at showOrderItems.php
  if(departmentName){
    department_name = departmentName
  }
    // For modal window at addOrderItem.php (cannot have two identical ids).
  else if($('#req_department').val() !== undefined){
    department_name = $('#req_department').val();
  }
  else if($('#department_edit').val() !== undefined){
    department_name = $('#department_edit').val();
  }
  else if($('#department').val() !== undefined){
    department_name = $('#department').val();
  }
  console.log(department_name);

  var group_by_select = $('#group_by_select').val();
  var request_modal = $('#request_modal').val();
  var edit_modal = $('#edit_modal').val();

  console.log(edit_modal);

  $.ajax({
    url: "../UpdatePHP/costCode.php",
    type: "POST",
    data: {
      department_name : department_name,
      group_by_select : group_by_select,
      request_modal   : request_modal,
      edit_modal      : edit_modal,
      cost_code       : cost_code
    },
    success: function(data, status, xhr) {
      $('.result').html(data);
    }
  });
}
function updateCostCodeOnClick(){
  var department_name = document.getElementById("departmentInTable").innerHTML;
  $.ajax({
    url: "../UpdatePHP/costCode.php",
    type: "POST",
    data: {
      department_name : department_name
    },
    success: function(data, status, xhr) {
      $('.result').html(data);
    }
  });
}
function updateModalCostCode(element){
  var department_name = $(element).parent().find('#department').val();
  console.log(department_name);
  $.ajax({
    url: "../UpdatePHP/costCode.php",
    type: "POST",
    data: {
      department_name : department_name
    },
    success: function(data, status, xhr) {
      $('.result').html(data);
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
// Function for setting the session supplier ID
function setSupplierID(element){
  var supplier_ID = $(element).parent().prev().find("#supplier_ID").val();
  $.ajax({
    url: '../UpdatePHP/setSupplierID.php',
    type: "POST",
    data:{
      supplier_ID : supplier_ID
    },
    success: function(data, status, xhr) {
     window.location="../Views/editSupplier.php";
    }
  });
}

// Function for deleting the supplier
function deleteSupplier(element){
  var supplier_ID = $(element).parent().prev().find("#supplier_ID").val();
  var r = confirm("Are you sure you want to delete this supplier?");
  if(r === true){
    $.ajax({
      url: '../DeletePHP/deleteSupplier.php',
      type: "POST",
      data:{
        supplier_ID: supplier_ID
      },
      success: function(data, status, xhr){
        window.location.reload();
      }
    })
  }
}

// Function for editing the supplier
function editSupplier(supplier_ID){
  var r = confirm("Are you sure you want to edit this supplier?");
  if(r === true){
    var supplier_name = $("#supplier_name").val();
    var supplier_phone = $("#supplier_phone").val();
    var supplier_fax = $("#supplier_fax").val();
    var net_terms   = $("#net_terms").val();
    var supplier_email = $("#supplier_email").val();
    var supplier_address = $("#supplier_address").val();
    var supplier_contact = $("#supplier_contact").val();
    var supplier_accountNr = $("#supplier_accountNr").val();
    var supplier_website = $("#supplier_website").val();
    var supplier_login = $("#supplier_login").val();
    var supplier_password = $("#supplier_password").val();
    var supplier_notes = $("#supplier_notes").val();
    var credit_card = $("#credit_card").val();
    var credit_card = $('#credit_card');
    if(credit_card.is(':checked')){
        credit_card = $('#credit_card').val();
      }else{
        credit_card = 0;
      }


    console.log(credit_card);
    $.ajax({
      url: '../UpdatePHP/editSupplier.php',
      type: "POST",
      data:{
        supplier_name : supplier_name,
        supplier_ID : supplier_ID,
        supplier_phone : supplier_phone,
        supplier_fax : supplier_fax,
        net_terms : net_terms,
        supplier_email : supplier_email,
        supplier_address : supplier_address,
        supplier_contact : supplier_contact,
        supplier_accountNr : supplier_accountNr,
        supplier_website : supplier_website,
        supplier_login : supplier_login,
        supplier_password : supplier_password,
        supplier_notes : supplier_notes,
        credit_card : credit_card
      },
      success: function(data, status, xhr) {
      //  window.location="../Views/supplierList.php";
      console.log(data);
      window.location.reload(true);
      }
    });
  }
}

// Delete Purchase Scan
function deletePurchaseScan(scan_ID){
  var r = confirm("Are you sure you want to delete this scan?");
  if(r === true){
    $.ajax({
      url: '../DeletePHP/deleteScan.php',
      type: "POST",
      data:{
        scan_ID : scan_ID
      },
      success: function(data, status, xhr) {
        window.location.reload();
      }
    });
  }
}

// Edit supplier for this quote
function editQuoteSupplier(quote_ID, element){
  var supplier_name = $(element).parent().find("#quoteSupplier").val();
  $.ajax({
    url: '../UpdatePHP/editQuoteSupplier.php',
    type: 'POST',
    data: {
      supplier_name : supplier_name,
      quote_ID : quote_ID
    }
  })
}

// Delete quote from database
function deleteQuote(quote_ID){
  var r = confirm("Are you sure you want to delete this quote?");
  if(r === true){
    $.ajax({
      url: '../DeletePHP/deleteQuote.php',
      type: "POST",
      data:{
        quote_ID : quote_ID
      },
      success: function(data, status, xhr) {
        window.location.reload();
      }
    });
  }
}
// Remove quote from request list
function removeQuoteFromRequest(quote_ID){
  var r = confirm("Are you sure you want to remove this quote?");
  if(r === true){
    $.ajax({
      url: '../UpdatePHP/removeQuoteFromRequest.php',
      type: "POST",
      data:{
        quote_ID : quote_ID
      },
      success: function(data, status, xhr) {
        window.location.reload();
      }
    });
  }
}

// Make quotes inactive and redirect to overview
function addQuoteToOverview(){
  $.ajax({
    url: '../UpdatePHP/deactivateQuotes.php',
    type: "POST",
    data:{
    },
    success: function(data, status, xhr) {
      window.location = '../Views/quotes.php';
    }
  });
}

// Set the rating and receiving date of the purchase order
function packageReceived(order_ID, element){
  var receiveDate = $(element).parent().find("#receiveDate").val();
  var rating_timeliness = $("#rating_timeliness").val();
  var rating_quality    = $("#rating_quality").val();
  var rating_price      = $("#rating_price").val();
  var customer_service      = $("#customer_service").val();
  var order_final_inspection = $('#order_final_inspection').val();

  $.ajax({
    url: '../UpdatePHP/packageReceived.php',
    type: "POST",
    data:{
      order_ID          : order_ID,
      receiveDate       : receiveDate,
      order_final_inspection : order_final_inspection,
      rating_timeliness : rating_timeliness,
      rating_price      : rating_price,
      customer_service      : customer_service,
      rating_quality    : rating_quality
    },
    success: function(data, status, xhr) {
      window.location = "../Views/purchasing.php";
    }
  });
}
//This adds the comment to the purchase order
function addCommentToPO(){
  var order_final_inspection = $('#order_final_inspection').val();
  $.ajax({
    url: '../UpdatePHP/updatePOComment.php',
    type: "POST",
    data:{
      order_final_inspection : order_final_inspection
    },
    success: function(data, status, xhr) {
      window.location.reload();
    }
  });
}

// Set the currency on the printout since not all purchase orders are in $
function setCurrency(){
  var currency = $('#currency').val();
  $.ajax({
    url: '../UpdatePHP/updateCurrency.php',
    type: "POST",
    data:{
      currency : currency
    },
    success: function(data, status, xhr) {
      window.location.reload();
    }
  });
}

// Function to edit the order item
function editOrderItem(order_item_ID, element){
  // Because we are fetching information from a modal, we need to use "this" or "element"
  // to find the correct modal
  // parent() is modal-footer
  // parent().prev() is modal-body
  // and from there we find the correct id's
  var quantity    = $(element).parent().prev().find("#quantity").val();
  var part_number = $(element).parent().prev().find('#part_number').val();
  var department  = $(element).parent().prev().find('#department').val();
  var cost_code   = $('#edit_cost_code').val();
  if(cost_code == undefined){
      cost_code   = $('#cost_code').val();
  }
  var unit_price  = $(element).parent().prev().find('#unit_price').val();
  var description = $(element).parent().prev().find('#description').val();

  $.ajax({
    url: '../UpdatePHP/editOrderItem.php',
    type: "POST",
    data:{
      order_item_ID : order_item_ID,
      quantity      : quantity,
      part_number   : part_number,
      department    : department,
      cost_code     : cost_code,
      unit_price    : unit_price,
      description   : description
    },
    success: function(data, status, xhr) {
      window.location.reload();
    }
  });
}

function updateCostCodeModal(element){
  var department_name = $(element).parent().find('#department').val();
  $.ajax({
    url: "../UpdatePHP/costCode.php",
    type: "POST",
    data: {
      department_name : department_name
    },
    success: function(data, status, xhr) {
      $('.result').html(data);
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

// Edit expected delivery date
function editExpectedDeliveryDate(){
  var expected_delivery_date = $('#expected_delivery_date').val();
  $.ajax({
    url: '../UpdatePHP/editExpectedDeliveryDate.php',
    type: 'POST',
    data:{
      expected_delivery_date : expected_delivery_date
    },
    success: function(data, status, xhr){
      window.location.reload();
    }
  });
}
// Edit net terms
function editNetTerms(){
  var net_terms = $('#net_terms').val();
  $.ajax({
    url: '../UpdatePHP/editNetTerms.php',
    type: 'POST',
    data:{
      net_terms : net_terms
    },
    success: function(data, status, xhr){
      window.location.reload();
    }
  });
}

// This function confirms the final inspection notes for every order item in this purchase order
function confirmFinalInspection(order_ID){
  var final_inspection;
  var order_item_ID;
  var ok;

  //Find how many rows we have in the table
  var cells = $('#finalInspectionTable > tbody > tr');
  var length = cells.length;

  //A function that goes through every row and finds final_inspection for each row
  $('#finalInspectionTable > tbody > tr').each(function(i) {
    if(i < length - 1){
      final_inspection = $(this).find('#final_inspection').val();
      order_item_ID    = $(this).find('#order_item_ID').val();
      ok               = $(this).find('#ok');
      if(ok.is(':checked')){
        ok = $('#ok').val();
      }else{
        ok = "";
      }
      $.ajax({
        url: '../UpdatePHP/setFinalInspectionNote.php',
        type: 'POST',
        data:{
          order_item_ID     : order_item_ID,
          order_ID          : order_ID,
          final_inspection  : final_inspection,
          ok                : ok
        },
        success: function(data, status, xhr){
          window.location.reload();
        }
      });
    }
  });
}

function createRequestFromQuotes(){
  var selected = [];
  $('#mytable').find('input[type="checkbox"][value="chooseQuote"]:checked').each(function (){
    selected.push($(this).attr('name'));
  });
  var array = selected.join(",");
  $.ajax({
    url: '../SelectPHP/createRequestFromQuotes.php',
    type: 'POST',
    data:{
      selected : array
    },
    success: function(data, status, xhr) {
      window.location = "../Views/request.php";
    }
  });
}

// function createPOFromQuotes(){
//   var selected = [];
//   $('#mytable').find('input[type="checkbox"][value="chooseQuotePO"]:checked').each(function (){
//     selected.push($(this).attr('name'));
//   });
//   var array = selected.join(",");
//   $.ajax({
//     url: '../SelectPHP/createPOFromQuotes.php',
//     type: 'POST',
//     data:{
//       selected : array
//     },
//     success: function(data, status, xhr) {
//       window.location = "../Views/addOrderItem.php";
//     }
//   });
// }

// Update the final inspection for the order item
function updateFinalInspection(final_inspection, order_item_ID, element){

  // I need to use element and parent to get the correct table row
  // $('#ok') would always fetch the top row which is not what we want
  var ok = $(element).parent().find('#ok');

  if(ok.is(':checked')){
    ok = $('#ok').val();
  }else{
    ok = "";
  }
  $.ajax({
    url: '../UpdatePHP/setFinalInspectionNote.php',
    type: 'POST',
    data:{
      order_item_ID : order_item_ID,
      final_inspection : final_inspection,
      ok : ok
    },
    success: function(data, status, xhr) {
      window.location.reload();
    }
  });
}

function addInProgressComment(order_ID, element){
  // Because we are fetching information from a modal, we need to use "this" or "element"
  // to find the correct modal
  // parent() is modal-body since this button is located in the body
  // and from there we find the correct id's
  var comment = $(element).parent().find("#inProgressComment").val();
  $.ajax({
    url: '../UpdatePHP/setInProgressComment.php',
    type: 'POST',
    data: {
      order_ID : order_ID,
      comment  : comment
    }
  })
}
