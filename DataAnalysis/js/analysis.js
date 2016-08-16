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

  function showSampleInfo(){
   sampleID = $("#sample_ID").val();

   $.ajax({
    url: "../SelectPHP/sampleInfo.php",
    type: "POST",
    data: {
     sampleID : sampleID
   },
   success: function(data,status, xhr){
     $("#sample_info").html(data);
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

//  function addAnlysResult(sampleID, eqPropID, form){
//   errorMessage = "";
//   employee = "";
//   date = "";
//   result = "";
//   propertyName = "";
//   comment = "";
//   params = [];

//   if(sampleID == "-1"){
//     errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Please choose a sample.</div>";
//   }
//   console.log(errorMessage);

//   if($(form).find('#property_name')[0]){
//       propertyName = $(form).find('#property_name')[0].innerText;
//       propertyName = propertyName.substring(0, (propertyName.length - 1));
//       result = $(form).find('#res_res').val();
//       if(result === ""){
//         errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: "+propertyName+".</div>";
//       }
//   }

//   comment = $(form).find('#res_comment').val();
//   date = $(form).find('#res_date').val();
//   employee = $(form).find('#employee_initials').val();

//   if(form.elements.res_param){
//     for (i = 0; i < form.elements.res_param.length; i++){
//       params.push(form.elements.res_param[i].value);
//     }
//   }

//   // var formData = new FormData();
//   // formData.append('file', $(form).find('#anlys_res_file')[0].files[0]);
//   // console.log(formData);

//   if(errorMessage){
//     $(form).find("#error_message").html(errorMessage);
//   }  else{
//     $.ajax({
//       url: "../InsertPHP/addAnlysResult.php",
//       type: "POST",
//       data: {
//         sampleID : sampleID,
//         eqPropID : eqPropID,
//         result : result,
//         comment : comment,
//         date : date,
//         params : params,
//         employee : employee
//       },
//       success: function(data,status, xhr){
//        console.log(data);
//        //displayAnlysResultTable(sampleID, eqPropID);
//        window.location.reload();
//      }
//    });
//   }
// }


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
    console.log("form validation false");
    return false;
  }
  console.log("form validation true");
  return true;
  
}

function displayAnlysResultTable(sampleID, eqPropID){
  
  // If the anlys result table is already being dislayed, hide it. 
  if($('#anlys_result_table').find('#eqPropID_hidden').val() == eqPropID) {
    $("#anlys_result_table").html("");
  }
  else{

  console.log("sampleID: "+sampleID);
  console.log("eqPropID: "+eqPropID);

  $.ajax({
    url: "../SelectPHP/anlysResultTable.php",
    type: "POST",
    data: {
      sampleID : sampleID,
      eqPropID : eqPropID
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

// function editAnlysResult(resID, form){
//   var errorMessage = "";
//   var result = "";
//   var paramRes1 = "";
//   var paramRes2 = "";
//   var paramRes3 = "";
//   var comment = "";
//   var propName = "";
  
//   result = $(form).find("#anlys_res_result").val();
//   paramRes1 = $(form).find("#anlys_res_param_1").val();
//   paramRes2 = $(form).find("#anlys_res_param_2").val();
//   paramRes3 = $(form).find("#anlys_res_param_3").val();
//   comment = $(form).find("#anlys_res_comment").val();
//   propName = $(form).find("#anlys_res_prop_name").text();
  
//   // If we are using the anlys_result field it cannot be left empty.
//   if(result === ""){
//     errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: "+propName+".</div>";
//   }

//   if(errorMessage){
//     $(form).find("#error_message_edit").html(errorMessage);
//   }  else{
//     $.ajax({
//       url: "../UpdatePHP/editAnlysResult.php",
//       type: "POST",
//       data: {
//         resID : resID,
//         result : result,
//         paramRes1 : paramRes1,
//         paramRes2 : paramRes2,
//         paramRes3 : paramRes3,
//         comment : comment
//       },
//       success: function(data, status, xhr){
//         console.log(data);
//         window.location.reload(true);
//       }
//     });
//   }
// }

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