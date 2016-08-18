function editAnalysisEquipment(eqID, form){
	var errorMessage = "";
	var name = $(form).find("#eq_name").val();
	var comment = $(form).find("#eq_comment").val();
	var propertyIDs = [];
	var propertyNames = [];
  var propertyUnits = [];

  if (!name){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Name</div>";
  }

  // It is not an array of elements
  if(!form.elements.prop_ID.length){
    propertyIDs.push(form.elements.prop_ID.value);
    propertyNames.push(form.elements.prop_name.value);
    propertyUnits.push(form.elements.prop_unit.value);
  }
  else{
   for (i = 0; i < form.elements.prop_ID.length; i++){
    propertyIDs.push(form.elements.prop_ID[i].value);
    propertyUnits.push(form.elements.prop_unit[i].value);
    propertyName = form.elements.prop_name[i].value;
    if(!propertyName){
     errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Property name</div>";
   }
   else{
     propertyNames.push(propertyName);
   }
 }
}

if(errorMessage){
  $(form).find("#error_message").html(errorMessage);
}  else{
  $.ajax({
   url: "../UpdatePHP/editAnalysisEquipment.php",
   type: "POST",
   data: {
    eqID : eqID,
    name : name,
    comment : comment
  },
  success: function(data, status, xhr){
    console.log(data);
    editAnalysisEqProperty(propertyIDs, propertyNames, propertyUnits, eqID);
  }
});
}
}

function editAnalysisEqProperty(propertyIDs, propertyNames, propertyUnits, eqID){
  $.ajax({
    url: "../UpdatePHP/editAnalysisEqProperty.php",
    type: "POST",
    data: {
      propertyIDs : propertyIDs,
      propertyNames : propertyNames,
      propertyUnits : propertyUnits,
      eqID : eqID
    },
    success: function(data, status, xhr){
      console.log(data);
      window.location.reload(true);
    }
  });
}

function deleteAnalysisEquipment(eqID){
  	// Display a confirmation popup window before proceeding.
  	var answer = confirm("Are you sure you want to deactive this equipment?");
  	if (answer === true){
  		$.ajax({
  			url: "../DeletePHP/deleteAnalysisEquipment.php",
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


  function deleteAnalysisEqProperty(propID, eqID, form){
  	var errorMessage = "";
    // Display a confirmation popup window before proceeding.
    var answer = confirm("Are you sure you want to delete this property?");
    if (answer === true){
      $.ajax({
       url: "../DeletePHP/deleteAnalysisEqProperty.php",
       type: "POST",
       data: {
        propID : propID,
        eqID : eqID
      },
      success: function(data, status, xhr){
        console.log(data);
        if(data.substring(0,6) === "Error1"){
          errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
          "Property cannot be deleted because there is analysis data depending on it.</div>";
          $(form).find("#error_message").html(errorMessage);
        }
        else{
          window.location.reload(true);
        }
      }
    });
    }
  }

  
function deleteAnlysResult(resID){
      $.ajax({
      url: "../DeletePHP/deleteAnlysResult.php",
      type: "POST",
      data: {
        resID : resID
      },
      success: function(data, status, xhr){
        console.log(data);
        window.location.reload(true);
      }
    });
}

function showAnlysResultForm(propID, eqID, sampleID, form){
   //sampleID = $(form).find('#sample_ID').val();

   $.ajax({
    url: "../SelectPHP/anlysResultForm.php",
    type: "POST",
    data: {
     sampleID : sampleID,
     propID : propID,
     eqID : eqID 
   },
   success: function(data,status, xhr){
     $("#res_div").html(data);

   }
 });
 }

 function anlysResultValidation(sampleID, eqPropID, form){
  errorMessage = "";
  result = "";
  propertyName = "";

  if(sampleID == "-1"){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Please choose a sample.</div>";
  }

  // If we are using the anlys_result field, it must not be empty.
  if($(form).find('#property_name')[0]){
      propertyName = $(form).find('#property_name')[0].innerText;
      propertyName = propertyName.substring(0, (propertyName.length - 1));
      result = $(form).find('#res_res').val();
      if(result === ""){
        errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: "+propertyName+".</div>";
      }
  }

  if(errorMessage){
    $(form).find("#error_message").html(errorMessage);
    return false;
  }
  return true; 
}

function displayAnlysResultTable(sampleID, eqPropID, prcsID, elem){
  
  // If the anlys result table is already being dislayed, hide it. 
  if($('#anlys_result_table').find('#eqPropID_hidden').val() == eqPropID &&
        $('#anlys_result_table').find('#prcsID_hidden').val() == prcsID) {
    $("#anlys_result_table").html("");
    $(elem).removeClass("bg-info");
  }
  else{

  // Remove the coloring of previously chosen row.
  $("tr").removeClass("bg-info");
  // Add color to chosen row.
  $(elem).addClass("bg-info");

  $.ajax({
    url: "../SelectPHP/anlysResultTable.php",
    type: "POST",
    data: {
      sampleID : sampleID,
      eqPropID : eqPropID,
      prcsID : prcsID
    },
    success: function(data,status, xhr){
      $("#anlys_result_table").html(data);
    }
  });
}
}

function loadAnlysResultModalEdit(resID, eqPropID){
    $.ajax({
    url: "../SelectPHP/anlysResultModalEdit.php",
    type: "POST",
    data: {
      resID : resID,
      eqPropID : eqPropID
    },
    success: function(data, status, xhr){
      $("#anlys_result_modal_edit").html(data);
      
    }
  });
}
