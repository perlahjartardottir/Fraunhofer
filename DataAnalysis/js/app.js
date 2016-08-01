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
		beginDate = beginDate.replace(/-/g,"").substring(2,8);
	}

  	console.log(minThickness);
  	console.log(maxThickness);

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
	})
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
	})
}