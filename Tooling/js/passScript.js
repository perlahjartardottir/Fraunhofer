function checkPass() {
  //Store the password field objects into variables ...
  var pass1 = document.getElementById('ePass');
  var pass2 = document.getElementById('ePassAgain');
  //Store the Confimation Message Object ...
  var message = document.getElementById('confirmMessage');
  //Set the colors we will be using ...
  var goodColor = "#66cc66";
  var badColor = "#ff6666";
  //Compare the values in the password field
  //and the confirmation field
  if (pass1.value == pass2.value) {
    //The passwords match.
    //Set the color to the good color and informxw
    //the user that they have entered the correct password
    pass2.style.backgroundColor = goodColor;
    message.style.color = goodColor;
    message.innerHTML = "Passwords Match!";
  } else {
    //The passwords do not match.
    //Set the color to the bad color and
    //notify the user.
    pass2.style.backgroundColor = badColor;
    message.style.color = badColor;
    message.innerHTML = "Passwords Do Not Match!";
  }
}

function addOldRun() {
  var e = document.getElementById("runsel");
  //this chooses the selected item from the dropdown list
  var old_run = e.options[e.selectedIndex].value;
  $.ajax({
    url: "../InsertPHP/addOldRun.php",
    type: "POST",
    data: {
      old_run: old_run
    },
    success: function(data, status, xhr) {
      $("#status_text").html(data);
      // if it is a success we want to refresh the PORuns list
      showPORuns();
    },
    error: function(jqXHR, status, errorThrown) {
      $("#status_text").html('there was an error ' + errorThrown + ' with status ' + textStatus);
    }
  });
}

function setSessionID() {
  var e = document.getElementById("packingsel");
  //this chooses the selected item from the dropdown list
  var po_ID = e.options[e.selectedIndex].value;
  console.log(po_ID);
  $.ajax({
    url: "../UpdatePHP/setSessionID.php",
    type: "POST",
    data: {
      po_ID: po_ID,
    },
    success: function(data, status, xhr) {
      $("#status_text").html(data);
    },
  });
}

function addPO() {
  var POID = $('#POID').val();
  var CID = $('#CID').val();
  var rDate = $('#rDate').val();
  var iInspect = $('#iInspect').val();
  var nrOfLines = $('#nrOfLines').val();
  var employeeId = $('#employeeId').val();
  var e = document.getElementById("shipping_sel");
  var shipping_info = e.options[e.selectedIndex].value;

  if(!POID) {
    $("#invalidPO").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: PO number</div>");
  } else if (!CID) {
      $("#invalidPO").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Company</div>");
  } else if (!employeeId) {
      $("#invalidPO").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Can not find employee, make sure you are logged in.</div>");
  } else if (!rDate) {
      $("#invalidPO").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Receiving date</div>");
  } else if (!nrOfLines) {
      $("#invalidPO").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Number of lines</div>");
  } else{
    $.ajax({
      url: "../InsertPHP/insertNewPO.php",
      type: "POST",
      data: {
        POID: POID,
        CID: CID,
        rDate: rDate,
        iInspect: iInspect,
        nrOfLines: nrOfLines,
        employeeId: employeeId,
        shipping_info: shipping_info
      },
      success: function(data, status, xhr) {
        if (data.indexOf("exists") > -1) {
            $("#invalidPO").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>That PO number already exists!</div>");
        } else {

          setSessionIDAfterAddingPO(POID);
          window.location.reload();
        }
      }
    });
  }
}

function showTools(po_ID) {
  $.ajax({
    url: "../SelectPHP/getPosForToolMenu.php",
    type: "POST",
    data: {
      po_ID: po_ID
    },
    success: function(data, status, xhr) {
      displayHelper();
      $("#poinfo").html(data);
    }
  });
}

function showToolsAndRefreshImage(po_ID) {
  console.log(po_ID);
  $.ajax({
    url: "../SelectPHP/getPosForToolMenu.php",
    type: "POST",
    data: {
      po_ID: po_ID
    },
    success: function(data, status, xhr) {
      $("#poinfo").html(data);
      window.location.reload(true);
    }
  });
}

function setSessionIDPrint(po_ID) {
  $.ajax({
    url: "../UpdatePHP/setSessionID.php",
    type: "POST",
    data: {
      po_ID: po_ID
    },
  });
}

function showToolsPrint(str) {
  if (str === "") {
    document.getElementById("txtHint").innerHTML = "";
    return;
  } else {
    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
      }
    };
    xmlhttp.open("GET", "../SelectPHP/getPOForPrinting.php?q=" + str, true);
    xmlhttp.send();
    return str;
  }
}

function showTrackPrint(str) {
  if (str === "") {
    document.getElementById("txtHint").innerHTML = "";
    return;
  } else {
    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
      }
    };
    xmlhttp.open("GET", "../SelectPHP/getPOForPrintingTrack.php?q=" + str, true);
    xmlhttp.send();
    return str;
  }
}

function showToolsTrack(str) {
  if (str === "") {
    document.getElementById("txtHint").innerHTML = "";
    return;
  } else {
    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
      }
    };
    xmlhttp.open("GET", "../SelectPHP/getPOForTrackSheet.php?q=" + str, true);
    xmlhttp.send();
    return str;
  }
}

function generatePrice() {
  var diameter = $('#diameter').val();
  var length = $('#length').val();
  var coating_dropdown = document.getElementById("coating_sel");
  var coating_ID = coating_dropdown.options[coating_dropdown.selectedIndex].text;
  $.ajax({
    url: "../SelectPHP/generatePrice.php",
    type: "POST",
    data: {
      diameter: diameter,
      length: length
    },
    success: function(data, status, xhr) {
      if (coating_ID === "DLC") {
        data = data * 2;
      }
      if ($('#dblEnd').is(":checked")){
        data = data * 2;
      }
      // output the data recieved from the php file into the price field.
      data = parseFloat(data);
      data = data.toFixed(2);
      document.getElementById('price').value = data;
    }
  });
}
function generatePriceTopNotch() {
  var coating_dropdown = document.getElementById("coating_sel_top");
  var coating_ID = coating_dropdown.options[coating_dropdown.selectedIndex].text;
  var e = document.getElementById("insert_size");
  var length = e.options[e.selectedIndex].text;
  $.ajax({
    url: "../SelectPHP/generatePrice.php",
    type: "POST",
    data: {
      length: length
    },
    success: function(data, status, xhr) {
      if (coating_ID === "DLC") {
        data = data * 2;
      }
      if ($('#dblEnd').is(":checked")){
        data = data * 2;
      }
      // output the data recieved from the php file into the price field.
      data = parseFloat(data);
      data = data.toFixed(2);
      document.getElementById('priceTop').value = data;
    }
  });
}
function generatePriceInsert() {
  var coating_dropdown = document.getElementById("coating_sel_insert");
  var coating_ID = coating_dropdown.options[coating_dropdown.selectedIndex].text;
  var e = document.getElementById("diameterInsert");
  var diameter = e.options[e.selectedIndex].text;
  $.ajax({
    url: "../SelectPHP/generatePrice.php",
    type: "POST",
    data: {
      diameter: diameter
    },
    success: function(data, status, xhr) {
      if (coating_ID === "DLC") {
        data = data * 2;
      }
      if ($('#dblEnd').is(":checked")){
        data = data * 2;
      }
      // output the data recieved from the php file into the price field.
      data = parseFloat(data);
      data = data.toFixed(2);
      document.getElementById('priceInsert').value = data;
    }
  });
}
function displayHelper() {
  $.ajax({
    url: "../SelectPHP/displayHelper.php",
    type: "GET",
    success: function(data, status, xhr) {
      $("#displayHelper").html(data);
    }
  });
}

function addTool() {
  // Remove all existing error messages
  $("#invalidTool").html("");

  var toolID = $('#tid').val();
  var lineItem = $('#lineItem').val();
  var quantity = $('#quantity').val();
  var diameter = $('#diameter').val();
  var length = $('#length').val();
  var e = document.getElementById('coating_sel');
  var coating_ID = e.options[e.selectedIndex].value;
  var dblEnd;

  if ($('#dblEnd').is(':checked')) {
    dblEnd = $('#dblEnd').val();
  }
  var price = document.getElementById('price').value;

  if (!lineItem) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Line on PO</div>");
  } else if (!toolID) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Tool ID number</div>");
  } else if (!quantity) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Quantity</div>");
  } else if (!coating_ID) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Coating</div>");
  } else if (!price) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Unit price</div>");
  } else {
    $.ajax({
      url: "../InsertPHP/insertNewToolToPo.php",
      type: "POST",
      data: {
        toolID: toolID,
        lineItem: lineItem,
        coating_ID: coating_ID,
        quantity: quantity,
        diameter: diameter,
        length: length,
        price: price,
        dblEnd: dblEnd,
      },
      success: function(data, status, xhr) {
        lineItem = parseInt(lineItem) + 1;
        $("#status_text").html(data);
        $('#toolID').val('');
        $('#lineItem').val(lineItem);
        showPOTools();
      }
    });
  }
}

function addToolInsert() {
  // Remove all existing error messages
  $("#invalidTool").html("");

  var toolID = $('#toolIDInsert').val();
  var lineItem = $('#lineItemInsert').val();
  var quantity = $('#quantityInsert').val();
  var diameter = $('#diameterInsert').val();
  var e = document.getElementById('coating_sel_insert');
  var coating_ID = e.options[e.selectedIndex].value;
  var price = document.getElementById('priceInsert').value;

  if (!lineItem) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Line on PO</div>");
  } else if (!toolID) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Tool ID number</div>");
  } else if (!quantity) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Quantity</div>");
  } else if (!coating_ID) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Coating</div>");
  } else if (!price) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Unit price</div>");
  } else {
    $.ajax({
      url: "../InsertPHP/insertNewToolToPo.php",
      type: "POST",
      data: {
        toolID: toolID,
        lineItem: lineItem,
        coating_ID: coating_ID,
        quantity: quantity,
        diameter: diameter,
        price: price,
      },
      success: function(data, status, xhr) {
        lineItem = parseInt(lineItem) + 1;
        $("#status_text").html(data);
        $('#toolIDInsert').val('');
        $('#lineItemInsert').val(lineItem);
        $('#quantityInsert').val('');
        showPOTools();
      }
    });
  }
}

function addToolOdd() {
  // Remove all existing error messages
  $("#invalidTool").html("");

  var toolID = $('#toolIDOdd').val();
  var lineItem = $('#lineItemOdd').val();
  var quantity = $('#quantityOdd').val();
  var e = document.getElementById('coating_sel_odd');
  var coating_ID = e.options[e.selectedIndex].value;
  var dblEnd;

  if ($('#dblEndOdd').is(':checked')) {
    dblEnd = $('#dblEndOdd').val();
  }
  var price = document.getElementById('priceOdd').value;

  if (!lineItem) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Line on PO</div>");
  } else if (!toolID) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Tool ID number</div>");
  } else if (!quantity) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Quantity</div>");
  } else if (!coating_ID) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Coating</div>");
  } else if (!price) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Unit price</div>");
  } else {
    $.ajax({
      url: "../InsertPHP/insertNewToolToPo.php",
      type: "POST",
      data: {
        toolID: toolID,
        coating_ID: coating_ID,
        lineItem: lineItem,
        quantity: quantity,
        price: price,
        dblEnd: dblEnd
      },
      success: function(data, status, xhr) {
        lineItem = parseInt(lineItem) + 1;
        $("#status_text").html(data);
        $('#toolID').val('');
        $('#lineItem').val(lineItem);
        $('#quantity').val('');
        // refresh the table with the newly inserted line
        showPOTools();
      }
    });
  }
}


function addToolTop() {
  // Remove all existing error messages
  $("#invalidTool").html("");

  var toolID   = $('#toolIDTop').val();
  var lineItem = $('#lineItemTop').val();
  var quantity = $('#quantityTop').val();
  var e = document.getElementById('coating_sel_top');
  var coating_ID = e.options[e.selectedIndex].value;
  var f = document.getElementById('insert_size');
  var insert_size = f.options[f.selectedIndex].value;

  var price = document.getElementById('priceTop').value;

  if (!lineItem) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Line on PO</div>");
  } else if (!toolID) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Tool ID number</div>");
  } else if (!insert_size) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Insert Size</div>");
  } else if (!quantity) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Quantity</div>");
  } else if (!coating_ID) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Coating</div>");
  } else if (!price) {
    $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Unit price</div>");
  } else {
    $.ajax({
      url: "../InsertPHP/insertNewToolToPo.php",
      type: "POST",
      data: {
        toolID     : toolID,
        coating_ID : coating_ID,
        lineItem   : lineItem,
        quantity   : quantity,
        price      : price,
        insert_size: insert_size
      },
      success: function(data, status, xhr) {
        console.log(data);
        lineItem = parseInt(lineItem) + 1;
        $("#status_text").html(data);
        $('#toolID').val('');
        $('#lineItem').val(lineItem);
        $('#quantity').val('');
        showPOTools();
      }
    });
  }
}

function addCustomer() {
  var cName = $('#cName').val();
  var cAddress = $('#cAddress').val();
  var cEmail = $('#cEmail').val();
  var cPhone = $('#cPhone').val();
  var cFax = $('#cFax').val();
  var cContact = $('#cContact').val();
  var cNotes = $('#cNotes').val();
  if (!cName) {
    $("#invalidCustomer").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Company Name</div>");
  }else{
    $.ajax({
      url: "../InsertPHP/insertNewCustomer.php",
      type: "POST",
      data: {
        cName: cName,
        cAddress: cAddress,
        cEmail: cEmail,
        cPhone: cPhone,
        cFax: cFax,
        cContact: cContact,
        cNotes: cNotes
      },
      success: function(data, status, xhr) {
        $('#cName').val('');
        $('#cAddress').val('');
        $('#cEmail').val('');
        $('#cPhone').val('');
        $('#cFax').val('');
        $('#cContact').val('');
        $('#cNotes').val('');
      }
    });
  }
}

function addEmployee() {
  var eName = $('#eName').val();
  var ePhoneNumber = $('#ePhoneNumber').val();
  var eEmail = $('#eEmail').val();
  var ePass = $('#ePass').val();
  var ePassAgain = $('#ePassAgain').val();
  var sec_lvl = $('#sec_lvl').val();
  if (!eName) {
    $("#invalidEmployee").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Employee name</div>");
    return 0;
  } else if (!sec_lvl) {
    $("#invalidEmployee").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Security level</div>");
    return 0;
  } else if (!ePass) {
    $("#invalidEmployee").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Password</div>");
    return 0;
  } else if (!ePassAgain) {
    $("#invalidEmployee").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>You must confirm the password</div>");
    return 0;
  } else if (!eEmail || !ePhoneNumber) {
    $("#invalidEmployee").html("<div class='alert alert-warning fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>There is missing information about this employee</br>you can add this info in the View All Employees page.</div>");
  }
  $.ajax({
    url: "../InsertPHP/insertNewEmployee.php",
    type: "POST",
    data: {
      eName: eName,
      ePhoneNumber: ePhoneNumber,
      eEmail: eEmail,
      ePass: ePass,
      sec_lvl: sec_lvl,
      ePassAgain: ePassAgain
    },
    success: function(data, status, xhr) {
      //alert("Employee added");
      $("#status_text").html(data);
      $('#eName').val('');
      $('#ePhoneNumber').val('');
      $('#eEmail').val('');
      $('#ePass').val('');
      $('#ePassAgain').val('');
    }
  });
}

function delTool(line) {
  var r = confirm("Are you sure you want to delete this tool?");
  if (r === true) {
    $.ajax({
      url: "../DeletePHP/deleteToolFromPO.php",
      type: "POST",
      data: {
        line: line
      },
      success: function(data, status, xhr) {
        if (data.indexOf("Error") > -1) {
          $("#invalidTool").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" + data + "</div>");
          return 0;
        }
        $("#status_text").html(data);
        showPOTools();
        //alert("Tool deleted successfully");
      }
    });
  }

}

function delRun(line) {
  var r = confirm("Are you sure you want to delete this run?");
  if (r === true) {
    $.ajax({
      url: "../DeletePHP/deleteRun.php",
      type: "POST",
      data: {
        line: line.id
      },
      success: function(data, status, xhr) {
        $("#status_text").html(data);
        showPORuns();
        //alert("Run deleted successfully");
      }
    });
  }
}

function delRunTool(lineitem, run_ID) {
  $.ajax({
    url: "../DeletePHP/deleteRunTool.php",
    type: "POST",
    data: {
      lineitem: lineitem,
      run_ID: run_ID
    },
    success: function(data, status, xhr) {
      $("#status_text").html(data);
      showRunTools();
    }
  });
}

// If entire == 1, we delete the PO and everything related to it
// Else we can only delete empty POs
function delPO(po_ID, entire) {
  var r;
  if (entire === 0) {
    r = confirm("Are you sure you want to delete this PO?");
    if (r === true) {
      $.ajax({
        url: "../DeletePHP/delEmptyPO.php",
        type: "POST",
        data: {
          po_ID: po_ID,
        },
        success: function(data, status, xhr) {
          //this refreshes the page after delete
          window.location.assign("../views/filterPOS.php");
        }
      });
    }
  } else if (entire == 1) {
    r = confirm("Are you sure you want to delete this PO? \n All info will be lost");
    if (r === true) {
      $.ajax({
        url: "../DeletePHP/delEntirePO.php",
        type: "POST",
        data: {
          po_ID: po_ID,
        },
        success: function(data, status, xhr) {
          //this refreshes the page after delete
          window.location.assign("../views/filterPOS.php");
        }
      });
    }
  }
}
function delOldPO(po_ID){
  var r = confirm("Are you sure you want to delete this PO? \n All info will be lost");
  if (r === true){
    $.ajax({
      url: "../DeletePHP/delEntirePO.php",
      type: "POST",
      data: {
        po_ID: po_ID
      },
      success: function(data, status, xhr) {
        // This refreshes the page after the delete
        window.location.reload();
      }
    });
  }
}
function delAllOldPOs(po_ID){
  $.ajax({
    url: "../DeletePHP/delEntirePO.php",
    type: "POST",
    data: {
      po_ID: po_ID
    }
  });
}

function addRun() {
  // Remove all existing error messages
  $("#invalidRun").html("");

  var runDate = $('#runDate').val();
  var rCoating = $('#coatingID').val();
  var machine_run_number = $('#machine_run_number').val();
  var ah_pulses = $('#ah_pulses').val();
  var machine = $('#machineID').val();
  var rcomments = $('#rcomments').val();
  var run_on_this_PO = $('#run_number').val();

  // Error messages for missing information
  if (!rCoating) {
    $("#invalidRun").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Coating</div>");
  } else if (!runDate) {
    $("#invalidRun").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Date</div>");
  } /*else if (!run_on_this_PO) {
    $("#invalidRun").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Run# on PO</div>");
  } */else if (!machine_run_number) {
    $("#invalidRun").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Machine run#</div>");
  } else if (!machine) {
    $("#invalidRun").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Machine</div>");
  } else if (!ah_pulses) {
    $("#invalidRun").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: AH/Pulses</div>");
  } else {
    $.ajax({
      url: "../InsertPHP/insertNewRunToTrackSheet.php",
      type: "POST",
      data: {
        runDate: runDate,
        rCoating: rCoating,
        machine_run_number: machine_run_number,
        ah_pulses: ah_pulses,
        machine: machine,
        run_on_this_PO: run_on_this_PO,
        rcomments: rcomments
      },

      success: function(data, status, xhr) {
        console.log(data);
        //Check for invalid input for the new run and returns error message
        if (data.indexOf("Error!") > -1) {
          $("#invalidRun").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" + data + "</div>");
          return 0;
        }

        // if there are no errors the php returns 2 strings. The run_ID and the run_number seperated by a whitespace
        var output = data.split(" ");

        // add the newly inserted run to the dropdown list.
        $('#runsel option:first').after($("<option></option>").attr("value", output[0]).text(output[1]));

        // refresh the table to show the inserted run.
        showPORuns();
      }
    });
  }
}
/*
    Adds one line item to a run
    You can add multiple lines of the
    same item here so you can add more than
    one line of tools to every run
    */
function addLineItemToRun() {
  var lineItem = $('#lineItem').val();
  var number_of_tools = $('#number_of_tools').val();
  var runNumber = $('#runNumber').val();
  var final_comment = $('#final_comment').val();

  if (!lineItem) {
    $("#invalidLineItem").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Line Item</div>");
  } else if (!number_of_tools) {
    $("#invalidLineItem").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Number of tools</div>");
  } else if (!runNumber) {
    $("#invalidLineItem").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Run number</div>");
  } else {
    $.ajax({
      url: "../InsertPHP/insertLineItemtoRun.php",
      type: "POST",
      data: {
        lineItem: lineItem,
        number_of_tools: number_of_tools,
        runNumber: runNumber,
        final_comment: final_comment,
      },
      success: function(data, status, xhr) {
        if (data.indexOf("Error!") > -1) {
          $("#invalidLineItem").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" + data + "</div>");
          return 0;
        }
        if (data.indexOf("Warning!") > -1) {
          $("#invalidLineItem").html("<div class='alert alert-warning fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" + data + "</div>");
        }
        // $("#status_text2").html(data);
        showRunTools();
      }
    });
  }
}
/*
    Adds a final comment
    and a shipping date to the chosen PO
    */

//   $(function() {
//   $( "#dialog-confirm" ).dialog({
//     resizable: false,
//     height:140,
//     modal: true,
//     buttons: {
//       "Delete all items": function() {
//         $( this ).dialog( "close" );
//       },
//       Cancel: function() {
//         $( this ).dialog( "close" );
//       }
//     }
//   });
// });
function confirmPO() {
  var date = $('#addShippingDate').val();
  var comment = $('#packing_list_comment').val();
  $.ajax({
    url: "../InsertPHP/confirmTrackSheet.php",
    type: "POST",
    data: {
      date: date
    },
    success: function(data, status, xhr) {
      if (data.indexOf("Error") > -1) {
        alert(data);
      } else if (data.indexOf("missing") > -1 || data.indexOf("assigned") > -1) {
        //if not all tools are assigned to runs
        var r = confirm(data);
        if (r === true) {
          addShipDateToPO(comment, date);
        }
      } else if (data.indexOf("Lineitem") > -1 || data.indexOf("Run") > -1) {
        // if a lineitem is missing a comment
        $('#myModal').modal('toggle');
        // var r = confirm(data);
        // if(r == true){
        //     window.location.replace("../Views/editPOTrack.php");
        // }
      } else {
        addShipDateToPO(comment, date);
      }
    }
  });
}
// alerts the user if the PO is not complete
// if the user does not mind that or if the PO is complete the print menu opens
function saveAndPrint() {
  var date = $('#addShippingDate').val();
  var comment = $('#packing_list_comment').val();
  var r;

  $.ajax({
    url: "../InsertPHP/confirmTrackSheet.php",
    type: "POST",
    data: {
      date: date
    },
    success: function(data, status, xhr) {
      if (data.indexOf("Error") > -1) { // if the shipping date is missing
        alert(data);
      } else if (data.indexOf("missing") > -1) { // if there are tools missing
        r = confirm(data);
        if (r === true) {
          addShipDateToPO(comment, date);
          window.print();
        }
      } else if (data.indexOf("assigned") > -1) { // if there are to many tools assigned
        r = confirm(data);
        if (r === true) {
          addShipDateToPO(comment, date);
          window.print();
        }
      } else { // nothing wrong, all PO info looks correct
        addShipDateToPO(comment, date);
        window.print();
      }
    }
  });
}

function addShipDateToPO(comment, date) {
  $.ajax({
    url: "../InsertPHP/insertShipDateToPO.php",
    type: "POST",
    data: {
      comment: comment,
      date: date
    },
    success: function(data, status, xhr) {
      if (data.indexOf("Error") > -1) {
        console.log(data);
      }
      window.location.reload(true);
    },
  });
}

function addCoating() {
  var coatingType = $('#coatingType').val();
  var coatingDesc = $('#coatingDesc').val();
  if (!coatingType) {
    $("#invalidCoating").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Coating type</div>");
    return 0;
  } else if (!coatingDesc) {
    $("#invalidCoating").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Coating description</div>");
    return 0;
  }
  $.ajax({
    url: "../InsertPHP/insertNewCoating.php",
    type: "POST",
    data: {
      coatingType: coatingType,
      coatingDesc: coatingDesc
    },
  });
}

function addNewMachine(line) {
  var mname = $('#mname').val();
  var macro = $('#macro').val();
  if (!mname) {
    $("#invalidMachine").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Machine Name</div>");
  }else if (!macro) {
    $("#invalidMachine").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Acronym</div>");
  }else{
    $.ajax({
      url: "../InsertPHP/insertNewMachine.php",
      type: "POST",
      data: {
        mname: mname,
        macro: macro
      },
      success: function(data, status, xhr) {
        if (data.indexOf("exists") > -1) {
            $("#invalidMachine").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+ data + "</div>");
        } else{
          $("#invalidMachine").html("<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>You successfully added "+ mname + " to the database!</div>");
        }
      },
      error: function(jqXHR, status, errorThrown) {
        $("#invalidMachine").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>An error occurred</div>");
      }
    });
  }
}
/*
  Function that checks if your password matches your
  username.
*/
function authenticate() {
  var userID = $('#userID').val();
  var password = $('#password').val();
  $.ajax({
    url: "../Login/logincheck.php",
    type: "POST",
    data: {
      userID: userID,
      password: password
    },
    success: function(data, status, xhr) {
      /*
        checks if the data recieved from the php file
        contains the string "error".

        data.indexOf("error") returns -1 only if the string
        is not found, so if the string is found it will return
        a number larger than -1 and we move to the selection site.
        The php file takes care of logging in or logging off the current user if he tries to log
        in with wrong information, this is done for security reasons
      */
      if (data.indexOf("error") > -1) {
        alert("Please enter the right information.");
      } else {
        window.location = "../selection.php";
      }
    }
  });
}

function logout() {
  $.ajax({
    url: "../Login/logout.php",
    type: "POST"
  }).done(function() {
    // redirect the user to the login page
    // this is done so you loose access to the site you are at
    // when you log out.
    window.location = "../Login/login.php";
  });
}

function deleteMachine() {
  var machine_ID = $('#input_machine_ID').val();
  $.ajax({
    url: "../DeletePHP/deleteMachine.php",
    type: "POST",
    data: {
      machine_ID: machine_ID
    },
    success: function(data, status, xhr) {
      window.location.reload(true);
      $("#status_text").html(data);
      $("#input_machine_ID").val("");
    }
  });
}

function deleteEmployee() {
  var employee_ID = $('#input_employee_ID').val();
  var r = confirm("Are you sure you want to delete employee with ID: " + employee_ID);
  if (r === true) {
    $.ajax({
      url: "../DeletePHP/deleteEmployee.php",
      type: "POST",
      data: {
        employee_ID: employee_ID
      },
      success: function(data, status, xhr) {
        window.location.reload(true);
        $("#status_text").html(data);
        $("#input_employee_ID").val("");
      }
    });
  }

}

function setSessionIDAfterAddingPO(po_ID) {
  $.ajax({
    url: "../UpdatePHP/setSessionIDWithPONumber.php",
    type: "GET",
    data: {
      po_ID: po_ID,
    },
    success: function(data, status, xhr) {
      $("#test").html(data);
    },
  });
}

function addFeedback() {
  var name = $('#name').val();
  var comment = $('#comment').val();
  $.ajax({
    url: "../InsertPHP/addFeedback.php",
    type: "POST",
    data: {
      name: name,
      comment: comment
    },
    success: function(data, status, xhr) {
      window.location.reload(true);
    }
  });
}

function storePackingList(po_ID) {
  var comment = $('#packing_list_comment').val();
  $.ajax({
    url: "../InsertPHP/addPackingList.php",
    type: "POST",
    data: {
      po_ID: po_ID,
      comment: comment
    }
  });
}

function deleteLineitem(POID, line) {
  $.ajax({
    url: "../DeletePHP/deleteToolFromPO.php",
    type: "POST",
    data: {
      POID: POID,
      line: line
    },
    success: function(data, status, xhr) {
      if (data.indexOf("Error!") > -1) {
          $("#invalidLineitem").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>" + data + "</div>");
      }else{
        $("#status_text").html(data);
        window.location.reload(true);
        //alert("Tool deleted successfully");
      }
    }
  });
}

function updateRunToolComment(lineitem_ID, run_ID) {
  $('textarea').select(); //select text inside
  comment = window.getSelection().toString();
  $.ajax({
    url: "../UpdatePHP/updateRunToolComment.php",
    type: "POST",
    data: {
      lineitem_ID: lineitem_ID,
      run_ID: run_ID,
      comment: comment
    },
    success: function(data, status, xhr) {
      // delay since bootstrap modal was
      // causing the page to be unscrollable after saving data.
      setTimeout(function() {
        showRunTools();
      }, 1000);
      //showRunTools();
    }
  });
}

function setSessionIDAndRefresh() {
  //this fetches the dropdownlist
  var e = document.getElementById("packingsel");
  //this chooses the selected item from the dropdown list
  var po_ID = e.options[e.selectedIndex].value;
  console.log(po_ID);
  $.ajax({
    url: "../UpdatePHP/setSessionID.php",
    type: "POST",
    data: {
      po_ID: po_ID,
    },
    success: function(data, status, xhr) {
      window.location.reload(true);
    },
  });
}

function updatePackinglistQuantity(lineitem_ID, quantity) {
  $.ajax({
    url: "../UpdatePHP/updatePackinglistQuantity.php",
    type: "POST",
    data: {
      lineitem_ID: lineitem_ID,
      quantity: quantity,
    },
    success: function(data, status, xhr) {},
  });
}

function showPOTools() {
  $.ajax({
    url: "../SelectPHP/getToolsForToolOverView.php",
    success: function(data, status, xhr) {
      $("#txtAdd").html(data);
    },
  });
}

function showRunTools() {
  $.ajax({
    url: "../SelectPHP/getToolsForRun.php",
    success: function(data, status, xhr) {
      $("#txtAddToolToRun").html(data);
    },
  });
}

function showPORuns() {
  $.ajax({
    url: "../SelectPHP/getRunsForPO.php",
    success: function(data, status, xhr) {
      $("#txtAddRun").html(data);
    },
  });
}

function changePOInfo(po_ID) {
  var input_date = $('#input_date').val();
  var input_initial_inspect = $('#input_initial_inspect').val();
  var input_number_of_lines = $('#input_number_of_lines').val();
  var input_po_number = $('#input_po_number').val();
  var e = document.getElementById("shipping_sel");
  //this chooses the selected item from the dropdown list
  var shipping_info = e.options[e.selectedIndex].value;
  $.ajax({
    url: "../UpdatePHP/changePOInfo.php",
    type: "POST",
    data: {
      po_ID: po_ID,
      input_date: input_date,
      input_po_number: input_po_number,
      input_initial_inspect: input_initial_inspect,
      input_number_of_lines: input_number_of_lines,
      shipping_info: shipping_info,
    },
    success: function(data, status, xhr) {
      window.location.reload(true);
    },
  });
}

function changeLineitemInfo(po_ID, element) {
  var line = $(element).parent().prev().find('#line').val();
  var input_quantity = $(element).parent().prev().find('#input_quantity').val();
  var input_price = $(element).parent().prev().find('#input_price').val();
  var input_tool = $(element).parent().prev().find('#input_tool').val();
  var input_diameter = $(element).parent().prev().find('#input_diameter').val();
  var input_length = $(element).parent().prev().find('#input_length').val();
  var input_end = $(element).parent().prev().find('#input_end').val();

  $.ajax({
    url: "../UpdatePHP/changeLineitemInfo.php",
    type: "POST",
    data: {
      po_ID: po_ID,
      line: line,
      input_quantity: input_quantity,
      input_price: input_price,
      input_tool: input_tool,
      input_diameter: input_diameter,
      input_length: input_length,
      input_end: input_end,
    },
    success: function(data, status, xhr) {
      window.location.reload(true);
    },
  });
}

function changeEmployee() {
  var employee_ID = $('#input_employee_ID').val();
  var employee_name = $('#input_employee_name').val();
  var employee_email = $('#input_employee_email').val();
  var employee_phone = $('#input_employee_phone').val();
  var security_level = $('#input_security_level').val();
  $.ajax({
    url: "../UpdatePHP/updateEmployee.php",
    type: "POST",
    data: {
      employee_ID: employee_ID,
      employee_name: employee_name,
      employee_email: employee_email,
      employee_phone: employee_phone,
      security_level: security_level,
    },
    success: function(data, status, xhr) {
      console.log(data);
      if (data.indexOf("invalid ID") > -1) {
        alert("Employee ID must be valid");
      }
      if (data.indexOf("invalid email") > -1) {
        alert("Invalid email");
      }
      if (data.indexOf("invalid phone number") > -1) {
        alert("Invalid phone number");
      }
      if (data.indexOf("invalid security level") > -1) {
        alert("Security level should be in the range 1-4");
      } else {
        window.location.reload(true);
      }
    }

  });
}

function applyDiscount() {
  var quantity;
  var discount;
  var reason;
  // the following code picks the first input field
  // with the right names that are not empty
  // this is done so we do put discount on a wrong lineitem.
  $('input[name="quantity"]').each(function() {
    if (this.value !== '') {
      quantity = this.value;
    }
  });
  $('input[name="discount"]').each(function() {
    if (this.value !== '') {
      discount = this.value;
    }
  });
  $('textarea[name="reason"]').each(function() {
    if (this.value !== '') {
      reason = this.value;
    }
  });
  $.ajax({
    url: "../UpdatePHP/addDiscountToLineitem.php",
    type: "POST",
    data: {
      quantity: quantity,
      discount: discount,
      reason: reason
    },
    success: function(data, status, xhr) {
      // TODO :
      //    Empty ALL input fields
      $('.discount_quantity').val('');
      $('.discount').val('');
      $('.discount_reason').val('');
    },
  });
}

function setSessionLineitemID(lineitem_ID) {
  $.ajax({
    url: "../UpdatePHP/setSessionLineitemID.php",
    type: "POST",
    data: {
      lineitem_ID: lineitem_ID
    },
  });
}

function changeCustomer() {
  var customer_ID = $('#input_customer_ID').val();
  var customer_name = $('#input_customer_name').val();
  var customer_address = $('#input_customer_address').val();
  var customer_phone = $('#input_customer_phone').val();
  var customer_email = $('#input_customer_email').val();
  var customer_fax = $('#input_customer_fax').val();
  var customer_contact = $('#input_customer_contact').val();
  var customer_notes = $('#input_customer_notes').val();
  $.ajax({
    url: "../UpdatePHP/updateCustomer.php",
    type: "POST",
    data: {
      customer_ID: customer_ID,
      customer_name: customer_name,
      customer_address: customer_address,
      customer_phone: customer_phone,
      customer_email: customer_email,
      customer_fax: customer_fax,
      customer_contact: customer_contact,
      customer_notes: customer_notes,
    },
    success: function(data, status, xhr) {
      window.location.reload(true);
    }

  });
}

function deleteCustomer() {
  var customer_ID = $('#input_customer_ID').val();
  var r = confirm("Are you sure you want to delete customer with ID: " + customer_ID);
  if (r === true) {
    $.ajax({
      url: "../DeletePHP/deleteCustomer.php",
      type: "POST",
      data: {
        customer_ID: customer_ID
      },
      success: function(data, status, xhr) {
        window.location.reload(true);
      }
    });
  }

}

function changeCoating() {
  var coating_ID = $('#input_coating_ID').val();
  var coating_type = $('#input_coating_type').val();
  var coating_description = $('#input_coating_description').val();
  $.ajax({
    url: "../UpdatePHP/updateCoating.php",
    type: "POST",
    data: {
      coating_ID: coating_ID,
      coating_type: coating_type,
      coating_description: coating_description,
    },
    success: function(data, status, xhr) {
      window.location.reload(true);
    }

  });
}

function deleteCoating() {
  var coating_ID = $('#input_coating_ID').val();
  $.ajax({
    url: "../DeletePHP/deleteCoating.php",
    type: "POST",
    data: {
      coating_ID: coating_ID
    },
    success: function(data, status, xhr) {
      window.location.reload(true);
    }
  });
}

function changeMachine() {
  var machine_ID = $('#input_machine_ID').val();
  var machine_name = $('#input_machine_name').val();
  var machine_acronym = $('#input_machine_acronym').val();
  var machine_comment = $('#input_machine_comment').val();
  $.ajax({
    url: "../UpdatePHP/updateMachine.php",
    type: "POST",
    data: {
      machine_ID: machine_ID,
      machine_name: machine_name,
      machine_acronym: machine_acronym,
      machine_comment: machine_comment,
    },
    success: function(data, status, xhr) {
      if(data.indexOf("Error!") > -1) {
        $("#invalidMachine").html("<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+ data +"</div>");
      }else{
        window.location.reload(true);
      }
    }
  });
}

function updateRunComment(run_ID, element) {
  $('textarea').select(); //select text inside
  comment = window.getSelection().toString();

  // Using element which is 'this' modal
  // parent() is modal-footer
  // parent().prev() is modal-body
  // and from there we find the correct id's
  var run_number_on_po   = $(element).parent().prev().find('#input_run_number').val();
  var machineID          = $(element).parent().prev().find('#input_machineID').val();
  var coatingID          = $(element).parent().prev().find('#input_coatingID').val();
  var machine_run_number = $(element).parent().prev().find('#input_machine_run_number').val();
  var runDate            = $(element).parent().prev().find('#input_runDate').val();
  var ah_pulses          = $(element).parent().prev().find('#input_ah_pulses').val();

  $.ajax({
    url: "../UpdatePHP/updateRunComment.php",
    type: "POST",
    data: {
      run_ID: run_ID,
      comment: comment,
      run_number_on_po: run_number_on_po,
      coatingID: coatingID,
      machineID: machineID,
      machine_run_number: machine_run_number,
      runDate: runDate,
      ah_pulses: ah_pulses
    },
    success: function(data, status, xhr) {
      // delay since bootstrap modal was
      // causing the page to be unscrollable after saving data.
      setTimeout(function() {
        showPORuns();
      }, 1000);
      //showPORuns();
    }
  });
}

function deleteDiscount(discount_ID) {
  var r = confirm("Are you sure you want to delete this discount?");
  if (r === true) {
    $.ajax({
      url: "../deletePHP/deleteDiscount.php",
      type: "POST",
      data: {
        discount_ID: discount_ID,
      },
      success: function(data, status, xhr) {
        window.location.reload(true);
      },
    });
  }
}

function deletePOScan(po_ID) {
  var r = confirm("Are you sure you want to delete this image?");
  if (r === true) {
    $.ajax({
      url: "../deletePHP/deletePOScan.php",
      type: "POST",
      data: {
        po_ID: po_ID,
      },
      success: function(data, status, xhr) {
        window.location.reload(true);
      },
    });
  }
}
