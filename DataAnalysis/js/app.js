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
	if(sampleSetID === ""){
		sampleSetID = -1;
	}
	var sampleName = $('#sample_name').val();
	var sampleMaterial = $('#sample_material').val();
	var sampleComment = $('#sample_comment').val();
	$.ajax({
		url: "../InsertPHP/addSample.php",
		type: "POST",
		data: {
			sampleName : sampleName,
			sampleMaterial : sampleMaterial,
			sampleComment : sampleComment,
			sampleSetID : sampleSetID
		},
		success: function(data, status, xhr){
			 console.log(data);
			 window.location.reload(true);
			 
		}
	});
}

// Display sample and reload page after adding it to a set.
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

// Display sample and reload page after adding it to a set.
function showSamplesInSetAndRefresh(sampleSetID){
	$.ajax({
		url: "../SelectPHP/showSamplesInSet.php",
		type: "POST",
		data: {
			sampleSetID : sampleSetID
		},
		success: function(data,status, xhr){
			$("#samples_in_set").html(data);
			window.location.reload(true);
		}
	})
}

function deleteSample(sampleID){
	$.ajax({
		url: "../DeletePHP/deleteSample.php",
		type: "POST",
		data: {
			sampleID : sampleID
		},
		success: function(data, status, xhr){
			window.location.reload(true);
		}
	})
}
