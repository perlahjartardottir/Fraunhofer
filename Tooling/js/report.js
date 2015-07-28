function applyFilter(){
    var customer_sel    = document.getElementById("customer_select");
    var customer_ID     = customer_sel.options[customer_sel.selectedIndex].value;
    var group_by_select = document.getElementById("group_by_select");
    var date_type       = group_by_select.options[group_by_select.selectedIndex].value;
    var date_from       = $('#date_from').val();
    var date_to         = $('#date_to').val();
    var show_discount;
    console.log("testtest");
    if($('#show_discount').is(':checked'))
    {
        show_discount   = $('#show_discount').val();
    }
    $.ajax({
        url : "reportGenerate.php",
        type: "POST",
        data : {customer_ID : customer_ID,
                date_from   : date_from,
                date_to     : date_to,
                date_type   : date_type,
                show_discount : show_discount},
     success: function(data,status, xhr)
     {
        $( "#output" ).replaceWith(data);
     }
    });
}

function applyMachineFilter(){
    var machine_sel    = document.getElementById("machine_select");
    var machine_ID     = machine_sel.options[machine_sel.selectedIndex].value;
    var group_by_select = document.getElementById("group_by_select");
    var date_type       = group_by_select.options[group_by_select.selectedIndex].value;
    var date_from       = $('#date_from').val();
    var date_to         = $('#date_to').val();
    var show_discount;

    if($('#show_discount').is(':checked'))
    {
        show_discount   = $('#show_discount').val();
    }
    $.ajax({
        url : "reportMachineGenerate.php",
        type: "POST",
        data : {machine_ID : machine_ID,
                date_from   : date_from,
                date_to     : date_to,
                date_type   : date_type,
                show_discount : show_discount},
     success: function(data,status, xhr){
        $( "#output" ).replaceWith(data);
     }
    });
}

function applyCoatingFilter(){
    var coating_sel    = document.getElementById("coating_select");
    var coating_ID     = coating_sel.options[coating_sel.selectedIndex].value;
    var group_by_select = document.getElementById("group_by_select");
    var date_type       = group_by_select.options[group_by_select.selectedIndex].value;
    var date_from       = $('#date_from').val();
    var date_to         = $('#date_to').val();
    var show_discount;

    if($('#show_discount').is(':checked'))
    {
        show_discount   = $('#show_discount').val();
    }
    $.ajax({
        url : "reportCoatingGenerate.php",
        type: "POST",
        data : {coating_ID  : coating_ID,
                date_from   : date_from,
                date_to     : date_to,
                date_type   : date_type,
                show_discount : show_discount},
     success: function(data,status, xhr)
     {
        $( "#output" ).replaceWith(data);
     }
    });
}

function showPriceTable(customer_ID) {
  $.ajax({
    url: "../Report/priceTableGenerate.php",
    type: "POST",
    data: {
      customer_ID: customer_ID,
    },
    success: function(data, status, xhr) {
      $('#priceTable').replaceWith(data);
    }
  });
}

function generateCustomerPrice()
{
  var e           = document.getElementById("customer_select");
  var customer_ID = e.options[e.selectedIndex].value;
  var d           = document.getElementById("diameter_select");
  var diameter    = d.options[d.selectedIndex].value;
  var l           = document.getElementById("length_select");
  var length      = l.options[l.selectedIndex].value;
  $.ajax({
    url: "../Report/generateCustomerPrice.php",
    type: "POST",
    data: {
      customer_ID: customer_ID,
      diameter: diameter,
      length: length,
    },
    success: function(data, status, xhr) {
      if(data){
        // parse to float so we can display 2 decimal points
        data = parseFloat(data);
        // $('#current_price').val(data.toFixed(2));
        $('#current_price').val(data);
      }else{
        $('#current_price').val("N/A");
      }
    }
  });
}

function updateCustomerPrice() {
  var new_price   = $('#new_price').val();
  if (!new_price) {
    $("#invalidPrice").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: New price</div>");
    return 0;
  } else if (new_price <= 0) {
    $("#invalidPrice").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Your price has to be more then 0!</div>");
    return 0;
  }
  var e           = document.getElementById("customer_select");
  var customer_ID = e.options[e.selectedIndex].value;
  var d           = document.getElementById("diameter_select");
  var diameter    = d.options[d.selectedIndex].value;
  var l           = document.getElementById("length_select");
  var length      = l.options[l.selectedIndex].value;
  if(!customer_ID){
    $("#invalidPrice").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Customer</div>");
    return 0;
  } else if(!diameter){
    $("#invalidPrice").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Diameter</div>");
    return 0;
  } else if(!length){
    $("#invalidPrice").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Length</div>");
    return 0;
  }
  $.ajax({
    url: "../Report/updateCustomerPrice.php",
    type: "POST",
    data: {
      new_price: new_price,
      customer_ID: customer_ID,
      diameter:diameter,
      length:length
    },
    success: function(data, status, xhr){
      generateCustomerPrice();
    }
  });
}

function showPriceTableWithMultiplier() {
  var price_multiplier = $('#price_multiplier').val();
  var e           = document.getElementById("customer_select_multiply");
  var customer_ID = e.options[e.selectedIndex].value;
  if(price_multiplier <= 0 || !price_multiplier){
    $("#invalidMultiplier").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>The multiplier must be more the 0!</div>");
    return 0;
  }
  console.log(customer_ID);
  $.ajax({
    url: "../Report/priceTableGenerate.php",
    type: "POST",
    data: {
      customer_ID: customer_ID,
    },
    success: function(data, status, xhr) {
      $('#priceTable').replaceWith(data);
      // go through all prices and multiply them
      // with the input
      $('.table_price').each(function() {
        var value = $(this).html();
        var multiplied_value = value * price_multiplier;
        $(this).html(multiplied_value.toFixed(2));
      });
    }
  });
}
function updateAllCustomerPrice() {
  var price_multiplier = $('#price_multiplier').val();
  if(price_multiplier <= 0 || !price_multiplier){
    $("#invalidMultiplier").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>The multiplier must be more the 0!</div>");
    return 0;
  }
  var e                = document.getElementById("customer_select_multiply");
  var customer_ID      = e.options[e.selectedIndex].value;
  $.ajax({
    url: "../Report/updateAllCustomerPrice.php",
    type: "POST",
    data: {
      price_multiplier: price_multiplier ,
      customer_ID: customer_ID,
    },
    success: function(data, status, xhr){
    }
  });
}
