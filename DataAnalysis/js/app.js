function logout(){
	$.ajax({
		url: "../../Login/logout.php",
		type: "POST"
	}).done(function() {
    // So the user looses access to site on logout.
    window.location = "../../Login/login.php";
});
}

// Trime the filepath to only the file name. 
function getFileName(s) {
	return s.replace(/^.*[\\\/]/, '');
}

function displaySearchResults(){
	$('#search_results').html();
	var sampleName = $("#sample_name").val();
	var minThickness = $("#min_thickness").val();
	var maxThickness = $("#max_thickness").val();
	var beginDate = $('#begin_date').val();
	var endDate  = $('#end_date').val();

	if(beginDate){
		// Trim the string down to our desired format. Before: YYYY-MM-DD. Afer: YYMMDD
		beginDate = beginDate.replace(/-/g,"").substring(2,8);
	}
	if(endDate){
		// Trim the string down to our desired format. Before: YYYY-MM-DD. Afer: YYMMDD
		endDate = beginDate.replace(/-/g,"").substring(2,8);
	}

	$.ajax({
		url : "../searchPHP/searchResults.php",
		type : "POST",
		data : {
			sampleName : sampleName,
			minThickness : minThickness,
			maxThickness : maxThickness,
			beginDate : beginDate,
			endDate : endDate
		},
		success : function(data, status, xhr){
			$("#search_results").html(data);
		}
	});
}

function displaySampleResults(){
	$('#sample_results').html();
	var sampleSetName = $("#sample_set_name").val();

	$.ajax({
		url : "../searchPHP/samplesFP.php",
		type : "POST",
		data : {
			sampleSetName : sampleSetName,
		},
		success : function(data, status, xhr){
			$("#sample_results").html(data);
		}
	});
}

function addFeedback(form){
	errorMessage = "";
	user = $(form).find("#feedback_user").val();
	console.log(user);
	date = $(form).find("#feedback_date").val();
	console.log(date);
	errorLocation = $(form).find("#feedback_location").val();
	console.log(errorLocation);
	sample = $(form).find("#feedback_sample").val();
	console.log(sample);
	description = $(form).find("#feedback_description").val();
	console.log(description);

	if(!description){
		errorMessage += "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Please add a description.</div>";
	}
	if(errorMessage){
		$(form).find("#error_message").html(errorMessage);
	} 
	else{
		$.ajax({
			url : "../insertPHP/addFeedback.php",
			type : "POST",
			data : {
				user : user,
				date : date,
				errorLocation : errorLocation,
				sample : sample,
				description : description
			},
			success : function(data, status, xhr){
				successMessage = "<div class='alert alert-success fade in row well-lg'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Thank you for your feedback!</div>";
				$("#success_message").html(successMessage);
				$("#feedback_form").trigger('reset');
				
				showFeedback();

			}
		});
	}
}

function showFeedback(){
	$.ajax({
		url: "../SelectPHP/feedback.php",
		type: "POST",
		data: {
		},
		success: function(data,status, xhr){
			$("#all_feedback").html(data);
			
		}
	});
}