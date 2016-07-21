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

	var sampleFile = $('#sample_file').val();

	$.ajax({
		url: "../InsertPHP/addSample.php",
		type: "POST",
		data: {
			sampleSetID : sampleSetID,
			sampleSetDate : sampleSetDate,
			sampleMaterial : sampleMaterial,
			sampleComment : sampleComment,

			sampleFile : sampleFile
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
			window.location.reload(true);
		}
	})
}

function deleteSample(sampleID, form){	
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
          		$(form).find("#error_message").html(errorMessage);
          	}
          	else{
				window.location.reload(true);
          	}
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

	var material = $('#material_edit-hidden').val();
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

function loadSampleModal(sampleID){
		$.ajax({
		url: "../SelectPHP/sampleModalFP.php",
		type: "POST",
		data: {
			sampleID : sampleID
		},
		success: function(data, status, xhr){
			$("#sample_modal").html(data);
			
		}
	})
}




