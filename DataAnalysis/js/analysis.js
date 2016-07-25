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
  if(!form.elements["prop_ID"].length){
    propertyIDs.push(form.elements["prop_ID"].value);
    propertyNames.push(form.elements["prop_name"].value);
    propertyUnits.push(form.elements["prop_unit"].value);
  }
  else{
   for (i = 0; i < form.elements["prop_ID"].length; i++){
    propertyIDs.push(form.elements["prop_ID"][i].value);
    propertyUnits.push(form.elements["prop_unit"][i].value);
    propertyName = form.elements["prop_name"][i].value;
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
})
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
  })
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
  		})
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
    })
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
 })
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
 })
 }

 function addAnlysResult(eqPropID, form){
  errorMessage = "";
  sampleID = "";
  propertyName = "";
  result = "";
  comment = "";
  date = "";
  params = [];

  sampleID = $(form).find('#sample_ID').val();
  if(sampleID === "-1"){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Please choose a sample.</div>";
  }

  if($(form).find('#property_name')[0]){
      propertyName = $(form).find('#property_name')[0].innerText;
      propertyName = propertyName.substring(0, (propertyName.length - 1));
      result = $(form).find('#res_res').val();
      if(result === ""){
        errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: "+propertyName+".</div>";
      }
  }


  comment = $(form).find('#res_comment').val();
  date = $(form).find('#res_date').val();
  console.log(date);

  if(form.elements["res_param"]){
    for (i = 0; i < form.elements["res_param"].length; i++){
      params.push(form.elements["res_param"][i].value);
      console.log(form.elements["res_param"][i].value);
    }
  }

  if(errorMessage){
    $(form).find("#error_message").html(errorMessage);
  }  else{
    $.ajax({
      url: "../InsertPHP/addAnlysResult.php",
      type: "POST",
      data: {
        sampleID : sampleID,
        eqPropID : eqPropID,
        result : result,
        comment : comment,
        date : date,
        params : params
      },
      success: function(data,status, xhr){
       console.log(data);
       //displayAnlysResultTable(sampleID, eqPropID);
       window.location.reload();
     }
   })
  }
}

function displayAnlysResultTable(sampleID, eqPropID){
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
  })
}