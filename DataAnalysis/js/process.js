function addProcess(sampleID, form){
  errorMessage = "";
  date = "";
  employee = "";
  coating = "";
  position = "";
  rotation = "";
  comment = "";

  if(sampleID === "-1"){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Please choose a sample.</div>";
  }

  date = $(form).find('#prcs_date').val();
  employee = $(form).find('#employee_initials').val();
  coating = $(form).find('#prcs_coating').val();
  equipment = $(form).find('#prcs_eq_acronyms').val();
  position = $(form).find('#prcs_position').val();
  rotation = $(form).find('#prcs_rotation').val();
  comment = $(form).find('#prcs_comment').val();

  if(!coating){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: coating.</div>";
  }

  if(errorMessage){
    $(form).find("#error_message").html(errorMessage);
  }  else{
    $.ajax({
      url: "../InsertPHP/addProcess.php",
      type: "POST",
      data: {
        sampleID : sampleID,
        date : date, 
        employee : employee,
        coating : coating,
        equipment : equipment,
        position : position,
        rotation : rotation,
        comment : comment
      },
      success: function(data,status, xhr){
       console.log(data);
       //displayAnlysResultTable(sampleID, eqPropID);
       window.location.reload();
     }
   });
  }
}

function displayProcessTable(sampleID){
  
  // If we are already displaying the table, hide it. 
  if(!$('#process_table').is(':empty')) {
    $("#process_table").html("");
  }
  else{
  
  $.ajax({
    url: "../SelectPHP/processTable.php",
    type: "POST",
    data: {
      sampleID : sampleID,
    },
    success: function(data,status, xhr){
      $("#process_table").html(data);
    }
  });
}
}

function loadPrcsModalEdit(prcsID){
    $.ajax({
    url: "../SelectPHP/PrcsModalEdit.php",
    type: "POST",
    data: {
      prcsID : prcsID
    },
    success: function(data, status, xhr){
      $("#prcs_modal_edit").html(data);
      
    }
  });
}

function editPrcs(prcsID, form){
  var errorMessage = "";
  var coating = "";
  var equipment = "";
  var position = "";
  var rotation = "";
  var comment = "";

  coating = $(form).find('#prcs_coating').val();
  eqID = $(form).find('#prcs_eq').val();
  position = $(form).find('#prcs_position').val();
  rotation = $(form).find('#prcs_rotation').val();
  comment = $(form).find('#prcs_comment').val();

  if(coating === ""){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: "+coating+".</div>";
  }

  if(errorMessage){
    $(form).find("#error_message_edit").html(errorMessage);
  }  else{
    $.ajax({
      url: "../UpdatePHP/editPrcs.php",
      type: "POST",
      data: {
        prcsID : prcsID,
        coating : coating,
        eqID : eqID,
        position : position,
        rotation : rotation,
        comment : comment
      },
      success: function(data, status, xhr){
        console.log(data);
        window.location.reload(true);
      }
    });
  }
}

function deletePrcs(prcsID){
  $.ajax({
    url: "../DeletePHP/deletePrcs.php",
    type: "POST",
    data: {
      prcsID : prcsID
    },
    success: function(data, status, xhr){
      console.log(data);
      window.location.reload(true);
    }
  });
}


function editPrcsEquipment(eqID, form){
  var errorMessage = "";
  var name = $(form).find("#eq_name").val();
  var acronym = $(form).find("#eq_acronym").val();
  var comment = $(form).find("#eq_comment").val();

  if (!name){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Name</div>";
  }
  if (!acronym){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Acronym</div>";
  }

  if(errorMessage){
    $(form).find("#error_message").html(errorMessage);
  }  else{
    $.ajax({
     url: "../UpdatePHP/editPrcsEquipment.php",
     type: "POST",
     data: {
      eqID : eqID,
      name : name,
      acronym : acronym,
      comment : comment
      },
      success: function(data, status, xhr){
        console.log(data);
        window.location.reload(true);
      }
    });
  }
}

function deletePrcsEquipment(eqID){
    // Display a confirmation popup window before proceeding.
    var answer = confirm("Are you sure you want to deactive this equipment?\n\nIf data has been conntected to the equipment it gets deactivated, else deleted.");
    if (answer === true){
      $.ajax({
        url: "../DeletePHP/deletePrcsEquipment.php",
        type: "POST",
        data: {
          eqID : eqID,
        },
        success: function(data, status, xhr){
          console.log(data);
          window.location.reload(true);
        }
      });
    }
  }

function activatePrcsEquipment(eqID){
  // Display a confirmation popup window before proceeding.
  var answer = confirm("Are you sure you want to activate this equipment?");
  if (answer === true){
    $.ajax({
      url: "../UpdatePHP/activatePrcsEquipment.php",
      type: "POST",
      data: {
        eqID : eqID,
      },
      success: function(data, status, xhr){
        console.log(data);
        window.location.reload(true);
      }
    });
  }
}

function addPrcsEquipment(form){
  var errorMessage = "";
  var name = $(form).find("#new_eq_name").val();
  var acronym = $(form).find("#new_eq_acronym").val();
  var comment = $(form).find("#new_eq_comment").val();

  if (!name){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Name</div>";
  }
  if (!acronym){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Acronym</div>";
  }

  if(errorMessage){
    $(form).find("#new_error_message").html(errorMessage);
  }  else{
    $.ajax({
     url: "../InsertPHP/addPrcsEquipment.php",
     type: "POST",
     data: {
      name : name,
      acronym : acronym,
      comment : comment
      },
      success: function(data, status, xhr){
        console.log(data);
        window.location.reload(true);
      }
    });
  }
}

function setPrcsIDAndRefresh(){
  prcsID = $("#coating").val();
  console.log("settings prcsID: "+prcsID);
  $.ajax({
    url: "../UpdatePHP/setPrcsID.php",
    type: "POST",
    data: {
      prcsID : prcsID
    },
    success: function(data,status, xhr){
      window.location.reload(true);
    }
  });

}
