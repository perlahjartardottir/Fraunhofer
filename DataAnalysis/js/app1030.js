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
			window.location.reload(true);
		}
	})
}

function editAnalysisEquipment(eqID, element){
	//var name = $(element).parent().find("#eq_name").val();
	//var comment = $(element).parent().find("#eq_comment").val();

	var name = $(element).find("#eq_name").val();
	var comment = $(element).find("#eq_comment").val();
	console.log(name);
	console.log(comment);


	// $.ajax({
	// 	url: "../UpdatePHP/editAnalysisEquipment.php",
	// 	type: "POST",
	// 	data: {
	// 		eqID : eqID,
	// 		name : name,
	// 		comment : comment
	// 	},
	// 	success: function(data, status, xhr){
	// 		console.log(data);
	// 		window.location.reload(true);
	// 	}
	// })
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

 function editAnalysisEqProperty(propID, element){
	//var name = $(element).parent().find("#prop_name").val();
	//console.log(name);

	//names = $(element).parent().getElementsByName("prop_name");
	//var names= $(element).parent().find("prop_name").val();

	console.log(element);
	console.log(element.length);

	 for (i = 0; i < element.length; i++) {
       console.log(element.elements[i].value + "\n");
	}

	// $.ajax({
	// 	url: "../UpdatePHP/editAnalysisEqProperty.php",
	// 	type: "POST",
	// 	data: {
	// 		propID : propID,
	// 		name : name
	// 	},
	// 	success: function(data, status, xhr){
	// 		console.log(data);
	// 		window.location.reload(true);
	// 	}
	// })
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
