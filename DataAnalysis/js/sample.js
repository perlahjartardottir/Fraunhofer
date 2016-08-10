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
	});
}

// To display samples in set at addSample.php
function showSamplesInSetAndRefresh(sampleSetID){
	$.ajax({
		url: "../SelectPHP/showSamplesInSet.php",
		type: "POST",
		data: {
			sampleSetID : sampleSetID
		},
		success: function(data, status, xhr){
			$("#samples_in_set").html(data);
			// Clear the id, since we have set the sampleSet session ID. 
			window.location.href="../Views/addSample.php";

		}
	});
}

function deleteSample(sampleID, element){	
	var errorMessage = "";
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
			if(data.substring(0,5) === "Error"){
          		errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"+
          		"Samples that have been analysed or processed cannot be deleted.</div>";
          		$(element).parent().parent().find("#error_message").html(errorMessage);
          	}
          	else{
				window.location.reload(true);
          	}
			}
		});
	}
}

function editSample(sampleID, element){
	var material = $('#material_edit-hidden').val();
	var comment = $(element).parent().parent().find("#sample_comment").val();

	$.ajax({
			url: "../UpdatePHP/editSample.php",
			type: "POST",
			data: {
				sampleID : sampleID,
				material : material,
				comment : comment
			},
			success: function(data, status, xhr){
				console.log(data);
				window.location.reload(true);
			}
		});
}

function loadSampleModal(sampleSetID, sampleID){
		$.ajax({
		url: "../SelectPHP/sampleModalFP.php",
		type: "POST",
		data: {
			sampleSetID : sampleSetID,
			sampleID : sampleID
		},
		success: function(data, status, xhr){
			$("#sample_modal").html(data);		
		}
	});
}

function loadSampleModalEdit(sampleID){
		$.ajax({
		url: "../SelectPHP/sampleModalEdit.php",
		type: "POST",
		data: {
			sampleID : sampleID
		},
		success: function(data, status, xhr){
			$("#sample_modal_edit").html(data);
			
		}
	});
}

  function updateSamplesInSet(sampleSetID){
  	if(sampleSetID === undefined){
  		sampleSetID = $("#sample_set_ID").val();
  	}
  	$.ajax({
  		url: "../SelectPHP/samplesInSetComboBox.php",
  		type: "POST",
  		data: {
  			sampleSetID : sampleSetID
  		},
  		success: function(data,status, xhr){
  			$("#samples_in_set").html(data);
  		}
  	});
  }

  // Update combo box at analyze.php
  function updateSamplesInSetAndRefresh(){
  	sampleSetID = $("#sample_set_ID").val();

  	$.ajax({
  		url: "../SelectPHP/samplesInSetComboBox.php",
  		type: "POST",
  		data: {
  			sampleSetID : sampleSetID
  		},
  		success: function(data,status, xhr){
  			
  			$("#samples_in_set").html(data);

  			window.location.reload(true);
  			setSampleID();
  		}
  	});
  }

  function setSampleID(sampleID){
  	if(sampleID === undefined){
  		sampleID = $("#sample_ID").val();
  	}
  	console.log("setSampleID: "+sampleID);
  	$.ajax({
  		url: "../UpdatePHP/setSampleID.php",
  		type: "POST",
  		data: {
  			sampleID : sampleID
  		},
  		success: function(data,status, xhr){
  			console.log(data);
  		}
  	});
  }
  function setSampleIDAndRefresh(){
  	sampleID = $("#sample_ID").val();
  	console.log("settings sampleID: "+sampleID);
  	$.ajax({
  		url: "../UpdatePHP/setSampleID.php",
  		type: "POST",
  		data: {
  			sampleID : sampleID
  		},
  		success: function(data,status, xhr){
  			window.location.reload(true);
  		}
  	});
  }
    function setSampleSetID(sampleSetID){
  	console.log("settings sampleSetID: "+sampleSetID);
  	$.ajax({
  		url: "../UpdatePHP/setSampleSetID.php",
  		type: "POST",
  		data: {
  			sampleSetID : sampleSetID
  		},
  		success: function(data,status, xhr){
  			console.log(data);
  		}
  	});
  }

  function setSampleSetIDAndRefresh(sampleSetID){
    console.log("settings sampleSetID: "+sampleSetID);
    $.ajax({
      url: "../UpdatePHP/setSampleSetID.php",
      type: "POST",
      data: {
        sampleSetID : sampleSetID
      },
      success: function(data,status, xhr){
        console.log(data);
        window.location.reload();
      }
    });
  }

  function getNewSampleSetName(sampleSetDate){
  	  	$.ajax({
  		url: "../SelectPHP/sampleSetName.php",
  		type: "POST",
  		data: {
  			sampleSetDate : sampleSetDate
  		},
  		success: function(data,status, xhr){
  			$("#sample_set_name_div").html(data);
  		}
  	});
  }



