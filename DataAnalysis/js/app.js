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
	// if(sampleSetID === ""){
	// 	sampleSetID = -1;
	// }
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

function editSample(sampleID, element){
	// Because we are fetching information from a modal, we need to use "this" or "element"
	// to find the correct modal.
	// parent() is modal-footer
	// parent().prev() is modal-body
	var name = $(element).parent().prev().find("#sample_name").val();
	var material = $(element).parent().prev().find("#sample_material").val();
	var comment = $(element).parent().prev().find("#sample_comment").val();
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
			window.location.reload();
		}
	})
}


function editAnalysisEquipment(eqID, element){
	var name = $(element).parent().prev().find("#eq_name").val();
	var comment = $(element).parent().prev().find("#eq_comment").val();


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
  		window.location.reload();
  	}
  })
}
