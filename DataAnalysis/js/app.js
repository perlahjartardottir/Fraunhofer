function logout(){
	$.ajax({
		url: "../../Login/logout.php",
		type: "POST"
	}).done(function() {
    // So the user looses access to site on logout.
    window.location = "../../Login/login.php";
});
}

function displaySearchResults(){
	$('#search_results').html();
	var sampleName = $('#sample_name').val();
  	var beginDate = $('#begin_date').val();
  	var endDate  = $('#end_date').val();
	$.ajax({
		url : "../searchPHP/searchResults.php",
		type : "POST",
		data : {
			sampleName : sampleName,
			beginDate : beginDate,
			endDate : endDate
		},
		success : function(data, status, xhr){
			$("#search_results").html(data);
		}
	})
}