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
	var sampleName = $('#sample_name').val();
	var sampleMaterial = $('#sample_material').val();
	var sampleComment = $('#sample_comment').val();
	$.ajax({
		url: "../InsertPHP/addSample.php",
		type: "POST",
		data: {
			sampleName : sampleName,
			sampleMaterial : sampleMaterial,
			sampleComment : sampleComment

		},
		success: function(data, status, xhr){
			 window.location.reload(true);
		}
	});
}

// Display sample and reload page after adding it to a set.
function showSamplesInSet(){
	$.ajax({
		url: "../SelectPHP/showSamplesInSet.php",
		type: "POST",
		success: function(data,status, xhr){
			console.log("app.js: showSamplesInSet success");
			$("#samples_in_set").html(data);
		}
	})
}


