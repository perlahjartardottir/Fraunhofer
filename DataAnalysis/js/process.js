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

  console.log("sampleID: "+sampleID);
  
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