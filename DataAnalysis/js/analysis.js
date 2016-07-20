function editAnalysisEquipment(eqID, form){
	var errorMessage = "";
	var name = $(form).find("#eq_name").val();
	var comment = $(form).find("#eq_comment").val();
	var propertyIDs = [];
	var propertyNames = [];

	if (!name){
		errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Name</div>";
	}

  // It is not an array of elements
  if(!form.elements["prop_ID"].length){
    propertyIDs.push(form.elements["prop_ID"].value);
    propertyNames.push(form.elements["prop_name"].value);
  }
  else{
   for (i = 0; i < form.elements["prop_ID"].length; i++){
      propertyIDs.push(form.elements["prop_ID"][i].value);
      propertyName = form.elements["prop_name"][i].value;
      console.log("in for: " + propertyName);
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
      editAnalysisEqProperty(propertyIDs, propertyNames, eqID);
    }
  })
}
}

function editAnalysisEqProperty(propertyIDs, propertyNames, eqID){
  $.ajax({
    url: "../UpdatePHP/editAnalysisEqProperty.php",
    type: "POST",
    data: {
      propertyIDs : propertyIDs,
      propertyNames : propertyNames,
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
        if(data.substring(0,5) === "Error"){
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
  // Update combo box at analyze.php
  function updateSamplesInSet(){
  	sampleSetID = $("#sample_set_ID").val();

  	$.ajax({
  		url: "../SelectPHP/samplesInSetComboBox.php",
  		type: "POST",
  		data: {
  			sampleSetID : sampleSetID
  		},
  		success: function(data,status, xhr){
  			$("#samples_in_set").html(data);


  		}
  	})
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
  sampleID = $(form).find('#sample_ID').val();
  if(sampleID === "-1"){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Please choose a sample.</div>";
  }
  result = $(form).find('#res_res').val();
  propertyName = $(form).find('#property_name')[0].innerText;
  propertyNameCut = propertyName.substring(0, (propertyName.length - 1));
  if(result === ""){
    errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: "+propertyNameCut+".</div>";
  }
  comment = $(form).find('#res_comment').val();
  date = $(form).find('#res_date').val();
  console.log(date);

  params = [];
  if(form.elements["res_params"]){
    for (i = 0; i < form.elements["res_params"].length; i++){
      params.push(form.elements["res_params"][i].value);
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
       window.location.reload();
     }
   })
  }

}