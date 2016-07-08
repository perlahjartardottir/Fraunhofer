function logout(){
	$.ajax({
		url: "../../Login/logout.php",
		type: "POST"
	}).done(function() {
    // Redirect the user to the login page.
    // This is done so you loose access to the site you are at
    // when you log out.
    window.location = "../../Login/login.php";
});
}

function addSample(){
	var sampleSetID = $('#sample_set_ID').val();
	var sampleSetDate = $('#sample_set_date').val()

	if(sampleSetDate){
		// Trim the string down to our desired format. Before: YYYY-MM-DD. Afer: YYMMDD
		sampleSetDate = sampleSetDate.replace(/-/g,"").substring(2,8);
	}

	var sampleMaterial = $('#material-hidden').val();
	var sampleComment = $('#sample_comment').val();

	$.ajax({
		url: "../InsertPHP/addSample.php",
		type: "POST",
		data: {
			sampleSetID : sampleSetID,
			sampleSetDate : sampleSetDate,
			sampleMaterial : sampleMaterial,
			sampleComment : sampleComment
		},
		success: function(data, status, xhr){
			console.log(data);
			window.location.reload(true);

		}
	});
}

function showSamplesInSet(sampleSetID){
	$.ajax({
		url: "../SelectPHP/showSamplesInSet.php",
		type: "POST",
		data: {
			sampleSetID : sampleSetID
		},
		success: function(data,status, xhr){
			$("#samples_in_set").html(data);
			
		}
	})
}

function showSamplesInSetAndRefresh(sampleSetID){
	$.ajax({
		url: "../SelectPHP/showSamplesInSet.php",
		type: "POST",
		data: {
			sampleSetID : sampleSetID
		},
		success: function(data, status, xhr){
			$("#samples_in_set").html(data);
			window.location.reload(true);
		}
	})
}

function deleteSample(sampleID){	
	// Display a confirmation popup window before proceeding.
	var r = confirm("Are you sure you want to delete this sample?");
	if (r === true){
		$.ajax({
			url: "../DeletePHP/deleteSample.php",
			type: "POST",
			data: {
				sampleID : sampleID
			},
			success: function(data, status, xhr){
				console.log(data);
				window.location.reload(true);
			}
		})
	}
}

function editSample(sampleID, form){
	var errorMessage = "";
	var name = $(form).find("#sample_name").val();
	if (!name){
		errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Name</div>";
	}

	var material = $(form).find("#sample_material").val();
	var comment = $(form).find("#sample_comment").val();
	
	if(errorMessage){
		$(form).find("#error_message").html(errorMessage);
	}  else{
		$.ajax({
			url: "../UpdatePHP/editSample.php",
			type: "POST",
			data: {
				sampleID : sampleID,
				name : name,
				material : material,
				comment : comment
			},
			success: function(data, status, xhr){
				console.log(data);
				window.location.reload(true);
			}
		})
	}
	}

	function editAnalysisEquipment(eqID, form){
		var errorMessage = "";
		var name = $(form).find("#eq_name").val();
		var comment = $(form).find("#eq_comment").val();
		var propertyIDs = [];
		var propertyNames = [];

		if (!name){
			errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Name</div>";
		}

		for (i = 0; i < form.elements["prop_ID"].length; i++){
			propertyIDs.push(form.elements["prop_ID"][i].value);
			propertyName = form.elements["prop_name"][i].value;
			if(!propertyName){
				errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Missing information: Property name</div>";
			}
			else{
				propertyNames.push(name);
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
			//window.location.reload(true);
			editAnalysisEqProperty(propertyIDs, propertyNames, eqID)
		}
	})
		}
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

  function deleteAnalysisEqProperty(propID){
  	// Display a confirmation popup window before proceeding.
  	var answer = confirm("Are you sure you want to delete this property?");
  	if (answer === true){
  		$.ajax({
  			url: "../DeletePHP/deleteAnalysisEqProperty.php",
  			type: "POST",
  			data: {
  				propID : propID,
  			},
  			success: function(data, status, xhr){
  				console.log(data);
  				window.location.reload(true);
  			}
  		})
  	}
  }
